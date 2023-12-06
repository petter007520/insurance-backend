<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;

class Member extends Model
{
    protected $table="member";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['username','password','paypwd','mobile','email','logintime','lognum','state','inviter','ip','amount','realname','level','address','qq','question','answer','ismobile','card','isquestion','isbank','bankname','bankrealname','bankcode','bankaddress','is_dongjie','ttop_uid','top_uid','invicode','picImg','reg_from','lastqiandao','mtype','integral','luckdraws','activation','glevel',
        'region','status','left_amount','right_amount','left_blance','right_blance','collision_amount','collision_amount_finsh'];
    protected $dates = ['created_at', 'updated_at'];
    protected $treeuid=[];
    protected $treelv=[];

    //加密密码串
    protected function EncryptPassWord($password){


        return Crypt::encrypt($password);


    }

    //解密密码串
    protected function DecryptPassWord($password){

       return Crypt::decrypt($password);
    }


    //替代*号
    protected  function half_replace($str){
        $len = strlen($str)/2;
        return substr_replace($str,str_repeat('*',$len),ceil(($len)/2),$len);
    }



    protected function treelv($invicode='',$lv=1)
    {


        $MemberD = Member::where('inviter', $invicode);
        $XiaXianMember = $MemberD->get();

        $arr = array();
        if (sizeof($XiaXianMember) !=0){
            foreach ($XiaXianMember as $k =>$datum) {
                $this->treelv($datum['invicode'],$lv+1);
                //$datum['list'] =
                //$datum['lv'] = $lv."层下级";
               // $arr[]=$datum;
                $this->treelv[$datum['id']]=$lv."层下级";
            }
        }
       // return $arr;
        return $this->treelv;

    }

    protected function treeuid($invicode='')
    {


        $MemberD = Member::where('inviter', $invicode);
        $XiaXianMember = $MemberD->get();

        $arr = array();
        if (sizeof($XiaXianMember) !=0){
            foreach ($XiaXianMember as $k =>$datum) {
                //$datum['list'] =
                $this->treeuid($datum['invicode']);
                //$datum['lv'] = $lv."层下级";
                //$arr[]=$datum;
                $this->treeuid[]=$datum['id'];
            }
        }
        //return $arr;
        return $this->treeuid;

    }


    //同级下线人数
    protected function leveluid($invicode='',$level='')
    {


        $MemberD = Member::where('inviter', $invicode)->where("level",$level)->count();

        return $MemberD;

    }


    //等级升级功能
    protected function Upgrade($invicode='')
    {


        /**

         * 发展下线升级功能 20191209

         ***/

        $YaoqingRen= Member::where("invicode",$invicode)->first();



        if( $YaoqingRen && $YaoqingRen->activation==1) {


            $member_level_amount= \App\Member::leveluid($YaoqingRen->invicode,$YaoqingRen->level);


            //用户等级
            $levels= \App\Memberlevel::where("offlines","<=",$member_level_amount)->where("id",">",$YaoqingRen->level)->orderBy("id","asc")->first();

            if($levels && $YaoqingRen->level<$levels->id){
                $YaoqingRen->level=$levels->id;
                $YaoqingRen->save();


                $msg=[
                    "userid"=>$YaoqingRen->id,
                    "username"=>$YaoqingRen->username,
                    "title"=>"会员等级升级",
                    "content"=>"恭喜您升级为".$levels->name,
                    "from_name"=>"系统通知",
                    "types"=>"通知",
                ];
                \App\Membermsg::Send($msg);

                \App\Member::Upgrade($YaoqingRen->inviter);

            }


        }

    }







}
