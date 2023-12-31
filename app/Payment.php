<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    protected $table="payment";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['pay_code','pay_name','pay_bank','pay_pic','pay_desc','enabled','sort'];


    protected function GetPayment(){
            $Tmp=[];
            if(Cache::has("mobile.payment")){
                $Tmp=Cache::get("mobile.payment");
            }else{
                $list= DB::table($this->table)->where("enabled","1")->orderBy("id","asc")->get();

                foreach($list as $item){
                    $Tmp[$item->pay_code]=$item;
                }

                Cache::put("mobile.payment",$Tmp,Cache::get('cachetime'));

            }
        return $Tmp;
    }

    protected function GetPaymentName($type){
            $Tmp=[];
            if(Cache::has("mobile.paymentname")){
                $Tmp=Cache::get("mobile.paymentname");
            }else{
                $list= DB::table($this->table)->where("enabled","1")->orderBy("id","asc")->get();

                foreach($list as $item){
                    $Tmp[$item->pay_code]=$item->pay_name;
                }

                Cache::put("mobile.paymentname",$Tmp,Cache::get('cachetime'));

            }


        if(isset($Tmp[$type])){
            return $Tmp[$type];
        }


        return '';
    }










}
