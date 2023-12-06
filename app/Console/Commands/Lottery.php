<?php

namespace App\Console\Commands;

use App\Member;
use App\Memberidentity;
use App\Product;
use App\Productbuy;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Lottery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lottery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '开奖';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ordinary_rate = 2;
        $star_rate = 3;
        $today = date('Y-m-d');
        $product_list = DB::table('products')->where(['next_lottery_time'=>$today])->get(['id','title','category_id','lottery_num','lottery_cycle','next_lottery_time','list','lottery_rate','lottery_star_rate']);
        foreach ($product_list as $list){
            echo $list->title.'开始抽奖-'.$today."\n";
            $order_list = DB::table('productbuy')->where(['productid'=>$list->id,'status'=>1])->get(['id','userid','username']);

            echo count($order_list).'条记录参与抽奖,'.'本产品设定获奖人数'.$list->lottery_num.'人'."\n";

            if(count($order_list) < $list->lottery_num){
                echo '本产品抽奖订单小于抽奖最小数量限制-'.$list->lottery_num."\n";
                break;
            }
            $num = $list->lottery_num;
            $user_list = [];
            //优先匹配内定中奖
            if(!empty($list->list)){
                echo '内定中奖用户名单-'.$list->list."\n";
                $user_list = explode('|',$list->list);
                $num = $list->lottery_num - count($user_list);
            }
            //内定人员匹配完成后还有剩余抽奖名额
            if($num > 0){
                //确保中奖的人数和设定的中奖人数相等
                do {
                    $round_list = $order_list->pluck('username')->toArray();
                    shuffle($round_list);
                    $keys = array_rand($round_list,$num);
                    if($num == 1){
                        $result = [$round_list[$keys]];
                    }else{
                        $result = array_slice($round_list,$keys[0],$num);
                    }
                    $user_list = array_merge($user_list,$result);
                    //中奖名单去重，保证获奖人数等于设定的获奖人数
                    $user_list = array_unique($user_list);
                }
                while (count($user_list) < $list->lottery_num);
            }
            if(count($user_list) <= 0){
                echo '中奖名单为空'."\n";
                break;
            }
            DB::beginTransaction();
            try {
                //中奖
                foreach ($user_list as $val){
                    $order = Productbuy::where(['username'=>$val,'status'=>1])->first();
                    $rate = $list->lottery_rate;
                    if($order){
                        $user = Member::where(['username'=>$val,'status'=>1])->first(['id','username','nickname','level','collision_amount','collision_amount_finsh','ktx_amount','status']);
                        $ext = '';
                        if($user){
                            if($user->level > 1){
                                $rate = $list->lottery_star_rate;
                            }
                            $amount = intval($rate * $order->amount);
                            $ext = $rate.'倍财富力-出局金额'.$amount;
                            //用户出局，重置用户对碰金额
                            $user->status = 2;
                            $user->collision_amount = 0;
                            $user->collision_amount_finsh = 0;
                            $user->save();
                            $before_amount = $user->ktx_amount;
                            $user->increment('ktx_amount', $amount);
                            //添加金额log表
                            $log = [
                                "userid" => $user->id,
                                "username" => $user->username,
                                "money" => $amount,
                                "notice" => $today.'财富运星中奖',
                                "type" => '财富抽奖',
                                "status" => '+',
                                "yuanamount" => $before_amount,
                                "houamount" => $user->ktx_amount,
                                "ip" => '',
                                "product_id" => $list->id,
                                "category_id" => $list->category_id,
                                "product_title" => $list->title,
                                "buy_id" => $order->id,
                                "moneylog_type_id" => '10_' . $order->id . '_' . $today,
                                'created_at' => date('Y-m-d H:i:s',time()),
                                'created_date' => $today
                            ];
                            \App\Moneylog::AddLog($log);
                        }
                        $order->status = 0;
                        $order->reason = $today.'抽奖中奖，用户出局，'.$ext;
                    }
                    //内定中奖用户，没有激活或者不存在该用户，只增加中奖滚动信息
                    $data = [
                        'user_id'  => isset($user) && $user ? $user->id : 0,
                        'name'     => isset($user) && $user ? $user->nickname : $this->generateNickname(),
                        'num'      => $rate,
                        'num_name' => $this->getRateText($rate),
                        'order_id' => isset($order) && $order ? $order->id:0,
                        'status'   => 0,
                        'create_time' => time(),
                        'create_data' => $today
                    ];
                    DB::table('member_wealth')->insert($data);
                }
                //抽奖完成，更新下次抽奖时间
                $next_time = Carbon::now()->addDays($list->lottery_cycle)->format('Y-m-d');
                DB::table('products')->where(['id'=>$list->id])->update(['next_lottery_time'=>$next_time]);
                DB::commit();
            } catch (\Exception $exception) {
                Log::channel('lottery')->alert($exception->getMessage());
                DB::rollBack();
            }
            echo $list->title.'抽奖完成-'.$today."\n";
        }
    }

    private function generateNickname(): string
    {
        // 生成一个3-4个汉字的昵称
        $length = mt_rand(3, 4);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= chr((mt_rand(0xB0,0xD0))).chr((mt_rand(0xA1, 0xF0)));
        }
        return iconv('GB2312','UTF-8',$str);
    }

    private function getRateText($num): string
    {
        $text = '';
        switch ($num){
            case 1:
                $text = '一倍';
                break;
            case 2:
                $text = '二倍';
                break;
            case 3:
                $text = '三倍';
                break;
            case 4:
                $text = '四倍';
                break;
            case 5:
                $text = '五倍';
                break;
            case 6:
                $text = '六倍';
                break;
            case 7:
                $text = '七倍';
                break;
            case 8:
                $text = '八倍';
                break;
            case 9:
                $text = '九倍';
                break;
        }
        return $text;
    }
}
