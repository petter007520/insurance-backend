<?php

namespace App\Jobs;

use App\Http\Controllers\Api\PayOrderController;
use App\Http\Controllers\App\BankCardStatementController;
use App\Mail\PcSendMailUtil;
use App\Mail\SendMailUtil;
use App\Member;
use App\Models\Bank;
use App\Models\BankCardStatement;
use App\Models\BankCardStatementExport;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 对碰奖励发放队列
 */
class CollisionReward implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $member = [];
    public $productInfo = [];
    public $amount = 0;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($member, $integrals, $product)
    {
        $this->member = $member;
        $this->amount = $integrals;
        $this->productInfo = $product;
        $this->onQueue('collisionReward');
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $topid = $this->member->top_uid;    //上级ID
        $integrals = $this->amount;       //金额
        $Member = $this->member;          //业绩产出用户
        $product = $this->productInfo;    //产品信息
        $region = $this->member->region;  //用户区域
        $level_amount = 0; // 星级奖励金额(新增业绩)

        $area = '左区';
        if ($region == 2) {
            $area = '右区';
        }

        Log::channel('collision_reward')->info(now() . $area . $this->member->username . '购买' . $product->title . '对碰奖励开始');

        $ratio = DB::table("setings")->where("keyname", "dp_reward_ratio")->value("value");
        //会员总数(业绩往上累计)
        $count = DB::table('member')->where('id','<',$this->member->id)->count();
        //新增的小区业绩是否已被拿走(星级奖励)
        $small_region_score_is_use = false;
        echo '本次需要计算次数'.$count."\n";
        try {
            for ($i = 0; $i < $count; $i++) {
                echo 'topid--'.$topid."\n";
                $topmemeber = Member::find($topid);
                echo '开始计算-topid-'.$topid.'----上级用户名'.$topmemeber->username."\n";
                //存在上级 且为激活状态
                if ($topmemeber) {
                    $field = $region == 1 ? 'left': 'right';
                    $region_text = $region == 1 ? '左区': '右区';
                    //对应区域展示总业绩增加
                    $before_show_amount = $topmemeber->$field.'_amount_show';
                    $topmemeber->increment($field.'_amount_show', $integrals);
                    //记录
                    $log = [
                        "userid" => $topmemeber->id,
                        "username" => $topmemeber->username,
                        "money" => $integrals,
                        "notice" => $region_text."展示总业绩新增",
                        "type" => "业绩变动",
                        "status" => "-",
                        "yuanamount" => $before_show_amount,
                        "houamount" => $topmemeber->$field.'_amount_show',
                        "ip" => \Request::getClientIp(),
                    ];
                    \App\Moneylog::AddLog($log);

                    if($topmemeber->status == 1){
                        //对应区域总业绩增加
                        $before_amount = $topmemeber->$field.'_amount';
                        $topmemeber->increment($field.'_amount', $integrals);
                        //记录
                        $log = [
                            "userid" => $topmemeber->id,
                            "username" => $topmemeber->username,
                            "money" => $integrals,
                            "notice" => $region_text."总业绩新增",
                            "type" => "业绩变动",
                            "status" => "-",
                            "yuanamount" => $before_amount,
                            "houamount" => $topmemeber->$field.'_amount',
                            "ip" => \Request::getClientIp(),
                        ];
                        \App\Moneylog::AddLog($log);

                        //业绩余额增加
                        $before_blance = $topmemeber->$field.'_blance';
                        $topmemeber->increment($field.'_blance', $integrals);
                        //记录
                        $log = [
                            "userid" => $topmemeber->id,
                            "username" => $topmemeber->username,
                            "money" => $integrals,
                            "notice" => $region_text."业绩余额新增",
                            "type" => "业绩变动",
                            "status" => "-",
                            "yuanamount" => $before_blance,
                            "houamount" => $topmemeber->$field.'_blance',
                            "ip" => \Request::getClientIp(),
                        ];
                        \App\Moneylog::AddLog($log);

                        //不存在大小区
                        if ($topmemeber->left_blance == 0 && $topmemeber->right_blance == 0) {
                            //增加总业绩和业绩余额
                            $topid = $topmemeber->top_uid;
                            $region = $topmemeber->region;
                            continue;
                        }

                        // 小区业绩
                        $small_region_score = $integrals;
                        DB::beginTransaction();
                        try {
                            //用户原本金额
                            $yuanmoney = $topmemeber->ktx_amount;
                            $before_left_balance = $topmemeber->left_blance;
                            $before_right_balance = $topmemeber->right_blance;
                            $collision_amount = 0;
                            $notice = '';
                            $mark_amount = '';
                            $level_amount_remark = '';
                            //新增业绩的用户在左区
                            if ($region == 1) {
                                //左区的业绩余额小于右区的业绩金额(即用户在左区且左区为小区，则产生对碰)
                                if ($topmemeber->left_blance < $topmemeber->right_blance) {
                                    //对碰金额 返给上级
                                    $collision_amount = round($small_region_score * $ratio, 2);
                                    $collision_amount = PayOrderController::get_real_amount($topmemeber->id, $collision_amount);
                                    $notice = '左小右大';
                                    $mark_amount = '左-'.$topmemeber->left_blance.'| 右'.$topmemeber->right_blance;
                                }

                                //当前用户在左区，上级的左区为大区 左区的业绩余额大于右区的业绩余额 并且右区业绩大于0
                                if ($topmemeber->left_blance > $topmemeber->right_blance && $topmemeber->right_blance > 0){
                                        $small_region_score = $topmemeber->right_blance;
                                        $collision_amount = round($small_region_score * $ratio, 2);
                                        $collision_amount = PayOrderController::get_real_amount($topmemeber->id, $collision_amount);
                                        $notice = '左大右小';
                                    $mark_amount = '左-'.$topmemeber->left_blance.'| 右'.$topmemeber->right_blance;
                                }
                            }


                            if ($region == 2) {
                                //右区的金额小于左区的金额(右区为小区) 产生对碰
                                if ($topmemeber->right_blance < $topmemeber->left_blance) {
                                    //对碰金额 返给上级
                                    $collision_amount = round($small_region_score * $ratio, 2);
                                    $collision_amount = PayOrderController::get_real_amount($topmemeber->id, $collision_amount);
                                    $notice = '左大右小';
                                    $mark_amount = '左-'.$topmemeber->left_blance.'| 右'.$topmemeber->right_blance;
                                }
                                //右区的业绩余额大于左区的业绩余额 并且左区业绩大于0
                                if ($topmemeber->right_blance > $topmemeber->left_blance && $topmemeber->left_blance > 0) {
                                    $small_region_score = $topmemeber->left_blance;
                                    $collision_amount = round($small_region_score * $ratio, 2);
                                    $collision_amount = PayOrderController::get_real_amount($topmemeber->id, $collision_amount);
                                    $notice = '左小右大';
                                    $mark_amount = '左-'.$topmemeber->left_blance.'| 右'.$topmemeber->right_blance;
                                }
                            }

                            //左右大区业绩余额相等 且左右大区业绩余额都大于0
                            if ($topmemeber->right_blance > 0 && $topmemeber->left_blance > 0 && $topmemeber->right_blance == $topmemeber->left_blance){
                                $small_region_score = $topmemeber->right_blance;
                                $collision_amount = round($small_region_score * $ratio, 2);
                                $collision_amount = PayOrderController::get_real_amount($topmemeber->id, $collision_amount);
                                $notice = '左右相等';
                                $mark_amount = '左-'.$topmemeber->left_blance.'| 右'.$topmemeber->right_blance;
                            }

                            $top_user_small_region = 0;
                            //业绩新增后上级的小区为左区 且 新增的业绩来自左区(业绩来自小区)，星级奖只发一次，标记小区的业绩已被使用
                            if($Member->region == 1 && $topmemeber->left_blance <= $topmemeber->right_blance && !$small_region_score_is_use){
                                $level_amount = $small_region_score;
                                $small_region_score_is_use = true;
                            }
                            //新增的业绩来自左区(业绩来自小区) 且 业绩新增后上级的小区为右区，此时 新增业绩来自左区，上级对碰的金额是右区(小区)的，右区业绩并不是来源于信新增，
                            if($Member->region == 1 && $topmemeber->left_blance > $topmemeber->right_blance){
                                $level_amount = $small_region_score;
                            }

                            //业绩新增后上级的小区为右区 且 新增的业绩来自右区(业绩来自小区)，星级奖只发一次，标记小区的业绩已被使用
                            if($Member->region == 2 && $topmemeber->right_blance <= $topmemeber->left_blance && !$small_region_score_is_use){
                                $level_amount = $small_region_score;
                                $small_region_score_is_use = true;
                            }
                            //新增的业绩来自右区(业绩来自小区) 且 业绩新增后上级的小区为左区，此时 新增业绩来自右区，上级对碰的金额是左区(小区)的，左区业绩并不是来源于信新增，
                            if($Member->region == 2 && $topmemeber->right_blance > $topmemeber->left_blance){
                                $level_amount = $small_region_score;
                            }


                            //碰撞金额大于0
                            if ($collision_amount > 0) {
                                $topmemeber->increment('ktx_amount', $collision_amount);
                                $log = [
                                    "userid" => $topmemeber->id,
                                    "username" => $topmemeber->username,
                                    "money" => $collision_amount,
                                    "notice" => "下线(" . $this->member->username . ")购买(" . $product->title . ")对碰奖励",
                                    "type" => "对碰奖励",
                                    "status" => "+",
                                    "yuanamount" => $yuanmoney,
                                    "houamount" => $topmemeber->ktx_amount,
                                    "ip" => \Request::getClientIp(),
                                ];
                                \App\Moneylog::AddLog($log);

                                //减少左区业绩余额
                                $topmemeber->decrement('left_blance', $small_region_score);
                                //记录
                                $log = [
                                    "userid" => $topmemeber->id,
                                    "username" => $topmemeber->username,
                                    "money" => $small_region_score,
                                    "notice" => "对碰(".$notice.")-左区业绩扣除",
                                    "type" => "左区业绩变动",
                                    "status" => "-",
                                    "yuanamount" => $before_left_balance,
                                    "houamount" => $topmemeber->left_balance,
                                    "ip" => \Request::getClientIp(),
                                ];
                                \App\Moneylog::AddLog($log);

                                $topmemeber->decrement('right_blance', $small_region_score);
                                //记录
                                $log = [
                                    "userid" => $topmemeber->id,
                                    "username" => $topmemeber->username,
                                    "money" => $small_region_score,
                                    "notice" => "对碰(".$notice.")-右区业绩扣除",
                                    "type" => "右区业绩变动",
                                    "status" => "-",
                                    "yuanamount" => $before_right_balance,
                                    "houamount" => $topmemeber->right_blance,
                                    "ip" => \Request::getClientIp(),
                                ];
                                \App\Moneylog::AddLog($log);
                            }


                            /** 上级会员星级奖励 **/
                            if($topmemeber->level > 0){
                                $rate = DB::table("memberlevel")->where(['level'=>$topmemeber->level])->value('rate');
                                if($rate && $rate > 0 && $level_amount > 0){
                                    echo '会员星级奖励--'.$topmemeber->username.'等级--'.$topmemeber->level.'星--比例--'.$rate.'---本次小区业绩'.$level_amount."\n";
                                    $vip_reward = round($level_amount * $rate /100, 2);
                                    $vip_reward = PayOrderController::get_real_amount($topmemeber->id, $vip_reward);
                                    echo '本次星级奖励应得--'.round($level_amount * $rate /100, 2).'实得--'.$vip_reward;
                                    if($vip_reward > 0 ){
                                        $before_money = $topmemeber->ktx_amount;
                                        $topmemeber->increment('ktx_amount', $vip_reward);
                                        //记录
                                        $log = [
                                            "userid" => $topmemeber->id,
                                            "username" => $topmemeber->username,
                                            "money" => $vip_reward,
                                            "notice" => "下线(" . $this->member->username . ")购买(" . $product->title . ")星级额外奖励",
                                            "type" => "星级奖励",
                                            "status" => "+",
                                            "yuanamount" => $before_money,
                                            "houamount" => $topmemeber->ktx_amount,
                                            "ip" => \Request::getClientIp(),
                                        ];
                                        \App\Moneylog::AddLog($log);
                                    }
                                    $level_amount_remark = $topmemeber->username.'-星级-'.$topmemeber->level.'星'.'--获得星级奖励，比例：'.$rate.'%,小区金额：'.$small_region_score.'--应得额外分红'.round($small_region_score * $rate /100, 2).'--实得'.$vip_reward;
                                    $level_amount = 0;
                                }
                            }

                            /******************************* 星级升级  ************************************/

                            //会员星级升级(这里只判断0升1星的用户)
                            $level_list = DB::table("memberlevel")->orderBy('id', 'ASC')->get()->toArray();
                            $lid = $topmemeber->level;
                            $level_one = DB::table('memberlevel')->where(['level'=>1])->first(['need_amount','welfare']);
                            //升级
                            if($lid == 0 && min($topmemeber->left_amount, $topmemeber->right_amount) >= $level_one->need_amount){
                                $topmemeber->level = 1;
                                $topmemeber->save();
                                //记录
                                $log = [
                                    "userid" => $topmemeber->id,
                                    "username" => $topmemeber->username,
                                    "money" => 0,
                                    "notice" => "下线(" . $this->member->username . ")购买(" . $product->title . ")星级升级",
                                    "type" => "星级升级",
                                    "status" => "+",
                                    "yuanamount" => $topmemeber->ktx_amount,
                                    "houamount" => $topmemeber->ktx_amount,
                                    "ip" => \Request::getClientIp(),
                                ];
                                \App\Moneylog::AddLog($log);

                                //奖励
                                $welfare = explode("|", $level_one->welfare);
                                if(count($welfare) > 0 ){
                                    foreach ($welfare as $val){
                                        $hsa_log = DB::table("travellog")->where(['userid' => $topmemeber->id,'travel_id'=>1,'travel_name'=>$val])->first();
                                        if (!$hsa_log) {
                                            $travel_data = [
                                                'userid' => $topmemeber->id,
                                                'username' => $topmemeber->username,
                                                'travel_id' => 1,
                                                'travel_name' => $val,
                                            ];
                                            DB::table('travellog')->insert($travel_data);
                                        }
                                    }
                                }
                            }
                            DB::commit();
                        } catch (\Exception $exception) {
                            Log::channel('collision_reward')->alert($exception);
                            DB::rollBack();
                        }
                        $collision_remark = $area.'新增业绩-'.$integrals.'--碰撞小区金额'.$small_region_score.'--'.$mark_amount.'----碰撞应得：'.round($small_region_score * $ratio, 2).'---实得：'.$collision_amount;
                        //日志记录
                        $logData = [
                            'user_id' => $this->member->id,
                            'username' => $this->member->username,
                            'user_region' => $this->member->region,
                            'to_user_id' => $topmemeber->id,
                            'to_username' => $topmemeber->username,
                            'to_user_region' => $topmemeber->region,
                            'new_score' => $integrals,
                            'collision_amount' => $small_region_score,
                            'collision_remark' => $collision_remark,
                            'level_amount_remark' => $level_amount_remark,
                            'create_time' => date('Y-m-d H:i:s',time()),
                        ];
                        DB::table('collision_log')->insert($logData);
                    }
                    if ($topmemeber->top_uid == 0) {
                        break;
                    } else {
                        $topid = $topmemeber->top_uid;
                        $region = $topmemeber->region;
                        $Member = $topmemeber;
                    }
                } else {
                    break;
                }
            }
        }catch (\Exception $e){
            Log::channel('collision_fail')->alert($e->getMessage());
        }
        Log::channel('collision_reward')->info(now() . $area . $this->member->username . '购买' . $product->title . '对碰奖励结束');
    }

    private function checkChildLevel($level){

    }

    public function fail($exception = null)
    {
        Log::channel('collision_reward')->error($exception);
    }
}
