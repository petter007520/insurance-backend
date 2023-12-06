<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cache;

class Sendmobile extends Model
{
    protected $table="sendmobile";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['mobile','content','result','action','ip'];


    protected function SendUid($userid,$action){


        $Member=  Member::find($userid);

        if($Member) {

            $code=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
            $content= \App\Smstmp::GetMsg(["type"=>$action,"username"=>$Member->username,"code"=>$code]);//rechargeok

            $mobile = \App\Member::DecryptPassWord($Member->mobile);
            $results = $this->sendMobile($mobile, $content);
            Cache::put("mobile.code.".$mobile,$code,600);//缓存短信验证码
            $Model = new Sendmobile();
            $Model->mobile = $Member->mobile;
            $Model->result = $results > 0 ? "发送成功" : "发送失败";
            $Model->content = $content;
            $Model->action = $action;
            $Model->ip = \Request::getClientIp();
            $Model->save();
            return ["status" => 0];
        }

    }

    protected function SendUContent($userid,$content){


        $Member=  Member::find($userid);

        if($Member) {

            $code=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
            //$content= \App\Smstmp::GetMsg(["type"=>$action,"username"=>$Member->username,"code"=>$code]);//rechargeok

            $mobile = \App\Member::DecryptPassWord($Member->mobile);
            $results = $this->sendMobile($mobile, $content);
            //Cache::put("mobile.code.".$mobile,$code,600);//缓存短信验证码
            $Model = new Sendmobile();
            $Model->mobile = $Member->mobile;
            $Model->result = $results > 0 ? "发送成功" : "发送失败";
            $Model->content = $content;
            $Model->action = '';
            $Model->ip = \Request::getClientIp();
            $Model->save();
            return ["status" => 0];
        }

    }

    protected function SendPhone($mobile,$action,$code){


           if($code==''){
              $code=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
           }



            Cache::put("mobile.code.".$mobile,$code,600);//缓存短信验证码

            $content= \App\Smstmp::GetMsg(["type"=>$action,"code"=>$code]);

            $results = $this->sendMobile($mobile, $content);

            $Model = new Sendmobile();
            $Model->mobile = \App\Member::EncryptPassWord($mobile);
            $Model->result = $results > 0 ? "发送成功" : "发送失败";
            $Model->content = $content;
            $Model->action = $action;
            $Model->ip = \Request::getClientIp();
            $Model->save();
            return ["status" => 0];


    }

    //发送短信接口
    function sendMobile($PHONE,$CONTENT){

        $smsApi=Cache::get('smsApi');

        if($smsApi=='信使'){

            $timestamp= Carbon::now()->format("YmdHis");
            $account = Cache::get('messengeraccounts');
            $pwd = Cache::get('messengerpass');
            $uid = Cache::get('messengeruserid');
            $post_data = array();
            $post_data['userid'] = $uid;
            $post_data['account'] = $account;
            $post_data['password'] = $pwd;
            $post_data['content'] = Cache::get('messengersign').$CONTENT; //短信内容需要用urlencode编码下
            $post_data['mobile'] = $PHONE;
            $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
            $post_data['timestamp'] = $timestamp;
            $post_data['sign'] = md5($account.$pwd.$timestamp); //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
//dd($post_data['sign']);
            return $result = $this->CorporateMessenger($post_data);//网建短信

        }else{

            $appid = Cache::get('smsappid');
            $pwd = Cache::get('smspass');

            $uid = "";
            $url = 'http://utf8.sms.webchinese.cn/?Uid=' . $appid . '&Key=' . $pwd . '&smsMob=' . $PHONE . '&smsText=' . urlencode($CONTENT);
            return $result = $this->WebChineseSms($url);//网建短信
        }


    }


    //网建短信API
    function WebChineseSms($url)
    {
        $ch = curl_init();
        /** curl_init()需要php_curl.dll扩展 **/
        $timeout = 5;
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }


    //企业信使

    function CorporateMessenger($post_data){


        $url=Cache::get('messengerurl');
        $o='';
        foreach ($post_data as $k=>$v)
        {
            $o.="$k=".urlencode($v).'&';
        }
        $post_data=substr($o,0,-1);
       // dd($post_data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
        $xml = curl_exec($ch);

        $xml =simplexml_load_string($xml); //xml转object
        $xml= json_encode($xml);  //objecct转json
        $xml=json_decode($xml,true); //json转array

        if(isset($xml['returnstatus']) && isset($xml['message'])){

            if($xml['returnstatus']=='Success' && $xml['message']=='ok'){
                return '1';
            }else{
                return '0';
            }

        }else{
            return '0';
        }



    }
}
