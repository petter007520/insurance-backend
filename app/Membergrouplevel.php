<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;

class Membergrouplevel extends Model
{
    protected $table="membergrouplevel";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['name','rate','inte','wheels','offlines'];


/*    //加密密码串
    protected function EncryptPassWord($password){


        return Crypt::encrypt($password);


    }

    //解密密码串
    protected function DecryptPassWord($password){

       return Crypt::decrypt($password);
    }*/


}
