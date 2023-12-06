<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cache;

class TreeProductbuy extends Model
{
    protected $table="tree_productbuy";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = [
        'userid',
        'username',
        'productid',
        'amount',
        'ip',
        'useritem_time',
        'useritem_time1',
        'useritem_time2',
        'useritem_count',
        'status',
        'sendday_count',
        'level'
    ];

    //查询返佣比例
    protected function checkBayong($id){

        $Product=  Product::find($id);
        return $Product->tqsyyj;
    }


    //返回今天星期几
    protected  function weekname($time){

        $weekarray=array("日","一","二","三","四","五","六");
        return "星期".$weekarray[$time];
    }



    protected function DateAdd($part, $number, $date){
        $date_array = getdate(strtotime($date));
        $hor = $date_array["hours"];
        $min = $date_array["minutes"];
        $sec = $date_array["seconds"];
        $mon = $date_array["mon"];
        $day = $date_array["mday"];
        $yar = $date_array["year"];
        switch($part){
            case "y": $yar += $number; break;
            case "q": $mon += ($number * 3); break;
            case "m": $mon += $number; break;
            case "w": $day += ($number * 7); break;
            case "d": $day += $number; break;
            case "h": $hor += $number; break;
            case "n": $min += $number; break;
            case "s": $sec += $number; break;
        }
        $FengHongDateFormat='Y-m-d H:i:s';
        if(Cache::has('FengHongDateFormat')){
            $FengHongDateFormat=Cache::get('FengHongDateFormat');
        }
        return date($FengHongDateFormat, mktime($hor, $min, $sec, $mon, $day, $yar));
    }


    //查询上家账号
    protected function checkTjr($username){

/*        global $db,$db_prefix;
//	$sql = "select * from {$db_prefix}member where username = '{$username}'";
        $sql = "SELECT username from {$db_prefix}member WHERE invicode = (SELECT inviter FROM {$db_prefix}member where username = '{$username}')";
        $rs = $db->get_one($sql);*/
       $BMeb= Member::where("username",$username)->value("top_uid");
       $Shja= Member::where("id",$BMeb)->value("username");
        return $Shja;
    }



    /***全球分红 全球分红奖励***/

    protected function GlobalBonus($invicode){


        $GlobalBonuLadder=Cache::get('GlobalBonuLadder');
        $userid=  Member::where("invicode",$invicode)->value('id');

          $XiaXians= Member::where("inviter",$invicode)
              ->whereDate("created_at",Carbon::now()->format("Y-m-d"))
              ->pluck("id");
        $Productbuys=  Productbuy::whereIn("userid",$XiaXians)
            ->whereDate("created_at",Carbon::now()->format("Y-m-d"))
            ->count();

        $Ladder=intval($Productbuys/$GlobalBonuLadder);

        for($i=1;$i<=$Ladder;$i++){
            $this->GlobalBonusLogs($userid,$i);

        }

    }


    protected function GlobalBonusLogs($userid,$Ladder){

        $GlobalBonuMoney=Cache::get('GlobalBonuMoney');



        $notice=  '全球分红('.$Ladder.'阶)';

       $Moneylogs= Moneylog::where("moneylog_userid",$userid)
            ->where("moneylog_type","全球分红奖励")
            ->where("moneylog_notice",$notice)
            ->whereDate("created_at",Carbon::now()->format("Y-m-d"))
            ->count();

       if($Moneylogs==0) {
           $BuyMember = Member::find($userid);
           //站内消息
           $msg = [
               "userid" => $BuyMember->id,
               "username" => $BuyMember->username,
               "title" => "全球分红奖励",
               "content" => $notice . '奖励' . $GlobalBonuMoney . '元',
               "from_name" => "系统通知",
               "types" => "全球分红奖励",
           ];
           \App\Membermsg::Send($msg);

           $Mamount = $BuyMember->amount;

           $BuyMember->increment('amount', $GlobalBonuMoney);
           $log = [
               "userid" => $BuyMember->id,
               "username" => $BuyMember->username,
               "money" => $GlobalBonuMoney,
               "notice" => $notice,
               "type" => "全球分红奖励",
               "status" => "+",
               "yuanamount" => $Mamount,
               "houamount" => $BuyMember->amount,
               "ip" => \Request::getClientIp(),
           ];

           \App\Moneylog::AddLog($log);
       }
    }

}
