<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Session\Session;
use Cache;
use DB;


class Smstmp extends Model
{
    protected $table="smstmp";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['sms_txtname','sms_type','sms_content'];


    protected function GetMsg($data){

        if(isset($data['type'])){

            $Tmp=[];
            if(Cache::has("mobile.smstmp")){
                $Tmp=Cache::get("mobile.smstmp");
            }else{
                $list= DB::table($this->table)->get();

                foreach($list as $item){
                    $Tmp[$item->sms_type]=$item->sms_content;
                }

                Cache::put("mobile.smstmp",$Tmp,Cache::get('cachetime'));

            }


            if(isset($Tmp[$data['type']])){

                $msg=$Tmp[$data['type']];
                if(isset($data['code'])){
                    $msg=str_replace('{code}',$data['code'],$msg);
                }

                if(isset($data['username'])){
                    $msg=str_replace('{username}',$data['username'],$msg);
                }else{
                    $msg=str_replace('{username}','',$msg);
                }

                return $msg;

            }



        }

        return '';
    }
    protected function GetTypeName($type){



            $Tmp=[];
            if(Cache::has("mobile.smstmp.TypeName")){
                $Tmp=Cache::get("mobile.smstmp.TypeName");
            }else{
                $list= DB::table($this->table)->get();

                foreach($list as $item){
                    $Tmp[$item->sms_type]=$item->sms_txtname;
                }

                Cache::put("mobile.smstmp.TypeName",$Tmp,Cache::get('cachetime'));

            }


            if(isset($Tmp[$type])){

                $msg=$Tmp[$type];

                return $msg;

            }





        return '';
    }

    protected function GetTypeNameList(){

            $Tmp=[];
            if(Cache::has("mobile.smstmp.TypeName")){
                $Tmp=Cache::get("mobile.smstmp.TypeName");
            }else{
                $list= DB::table($this->table)->get();

                foreach($list as $item){
                    $Tmp[$item->sms_type]=$item->sms_txtname;
                }

                Cache::put("mobile.smstmp.TypeName",$Tmp,Cache::get('cachetime'));

            }


        return $Tmp;
    }

    protected function GetContentList(){

            $Tmp=[];
            if(Cache::has("mobile.smstmp.Content")){
                $Tmp=Cache::get("mobile.smstmp.Content");
            }else{
                $list= DB::table($this->table)->get();

                foreach($list as $item){
                    $Tmp[$item->sms_type]=$item->sms_content;
                }

                Cache::put("mobile.smstmp.Content",$Tmp,Cache::get('cachetime'));

            }


        return $Tmp;
    }










}
