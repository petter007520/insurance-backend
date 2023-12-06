<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Session\Session;
use Cache;
use DB;


class Formatting extends Model
{

    //统一处理显示网站内容 \App\Formatting::Format()
    protected function Format($infos){
          return $infos;

          $UserName=  Session("UserName");

          $msg=str_replace('{CompanyLong}',Cache::get('CompanyLong'),$infos);
          $msg=str_replace('{CompanyShort}',Cache::get('CompanyShort'),$msg);
          $msg=str_replace('{UserName}',$UserName,$msg);

          return $msg;
    }


    //统一处理数据为内容标签格式
    protected function ToFormat($infos){
          return $infos;

          $msg=str_replace(Cache::get('CompanyLong'),'{CompanyLong}',$infos);
          $msg=str_replace(Cache::get('CompanyShort'),'{CompanyShort}',$msg);

          return $msg;
    }










}
