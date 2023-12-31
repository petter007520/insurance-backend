<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cache;

class Productbuy extends Model
{
    protected $table="productbuy";
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

       $BMeb= Member::where("username",$username)->value("invite_uid");
       $Shja= Member::where("id",$BMeb)->value("username");
        return $Shja;
    }

    public function product(){
        return $this->belongsTo(Product::class,'productid','id');
    }
}
