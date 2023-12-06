<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;

class Jijinqishu extends Model
{
    protected $table="jijinqishu";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['title','rate','days'];


/*    //加密密码串
    protected function EncryptPassWord($password){


        return Crypt::encrypt($password);


    }

    //解密密码串
    protected function DecryptPassWord($password){

       return Crypt::decrypt($password);
    }*/


}
