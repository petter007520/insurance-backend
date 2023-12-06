<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\PayOrderController;
use App\Member;
use App\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Level extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'level';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '会员等级计算(1星用户起算)';

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
        $member_level = DB::table('memberlevel')->where('level','>',1)->get(['level','name','need_star_num','need_star_level','welfare']);
        $list = DB::table('member')->where(['is_auth'=>1])->where('level','>',0)->get(['id','username','level','child_left_uid','child_right_uid','ktx_amount']);
        foreach ($list as $user){
            foreach ($member_level as $level){
                //左右双区是否达到条件
                if($level->level > $user->level && $this->getChildLevelCount($user->id,$user->child_left_uid,$level->need_star_level,$level->need_star_num)
                    && $this->getChildLevelCount($user->id,$user->child_right_uid,$level->need_star_level,$level->need_star_num)){

                    //记录
                    $log = [
                        "userid" => $user->id,
                        "username" => $user->username,
                        "money" => 0,
                        "notice" => "星级升级(".$user->level.'星升'.$level->name.")",
                        "type" => "星级升级",
                        "status" => "+",
                        "yuanamount" => $user->ktx_amount,
                        "houamount" => $user->ktx_amount,
                        "ip" => \Request::getClientIp(),
                    ];
                    \App\Moneylog::AddLog($log);
                    //升级
                    DB::table('member')->where(['id'=>$user->id])->update(['level'=>$level->level]);

                    //奖励
                    $welfare = explode("|", $level->welfare);
                    if(count($welfare) > 0 ){
                        foreach ($welfare as $val){
                            $hsa_log = DB::table("travellog")->where(['userid' => $user->id,'travel_id'=>$level->level,'travel_name'=>$val])->first();
                            if (!$hsa_log) {
                                $travel_data = [
                                    'userid' => $user->id,
                                    'username' => $user->username,
                                    'travel_id' => $level->level,
                                    'travel_name' => $val,
                                ];
                                DB::table('travellog')->insert($travel_data);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * 查询用户伞下对应星级数量是否达标
     * @param $user_id
     * @param $child_uid
     * @param $level
     * @param $level_num
     */
    public function getChildLevelCount($user_id,$child_uid,$level,$level_num): bool
    {
        //首先查询用户下面左右双区用户是否为自己推荐的且符合查询要求
        $child = DB::table('member')->where(['id'=>$child_uid])->first(['id','invite_uid','level']);
        if($child && $child->invite_uid == $user_id && $child->level == $level && $level_num == 1){
            return true;
        }
        //查询用户伞下对应区域的用户是否符合要求
        $count = DB::table('member')->where('id','>',$child->id)->where(['level'=>$level])->whereRaw('FIND_IN_SET(?,family_ids)',[$child->id])->count();
        if($count >= $level_num){
            return true;
        }
        return false;
    }
}
