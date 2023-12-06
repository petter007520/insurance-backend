<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;

class Memberphone extends Model
{


    //用户手机号是否注册
    protected function IsReg($PHONE){


        $Members= Member::get();


        foreach($Members as $member){
            if(Crypt::decrypt($member->mobile)==$PHONE){
                return true;
            }
        }


        return false;
    }

    //用户手机号是否注册
    protected function IsUpdate($PHONE,$Mid){


        $Members= Member::get();


        foreach($Members as $member){
            if(Crypt::decrypt($member->mobile)==$PHONE && $member->id!=$Mid){
                return true;
            }
        }


        return false;
    }


}
