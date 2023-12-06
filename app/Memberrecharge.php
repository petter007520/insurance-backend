<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;
use DB;
use Cache;
use App\Membercurrencys;

class Memberrecharge extends Model
{
    protected $table="memberrecharge";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = [
        'ordernumber',
        'userid',
        'username',
        'amount',
        'memo',
        'paymentid',
        'status',
        'paytime',
        'payimg',
        'ip',
        'bank',
        'accNo',
        'sendsms',
        'type'
        ];
    protected $dates = ['created_at', 'updated_at'];


    protected function Recharge($Data){

      $Rec=  new Memberrecharge();
      $Rec->ordernumber=isset($Data['ordernumber'])?$Data['ordernumber']:Carbon::now()->format("YmdHis").rand(10000000,99999999);
      $Rec->userid=$Data['userid'];
      $Rec->username=isset($Data['username'])?$Data['username']:DB::table("member")->where("id",$Data['userid'])->value("username");
      $Rec->amount=$Data['amount'];
      $Rec->ip=$Data['ip'];
      $Rec->type=$Data['type'];
      $Rec->paymentid=$Data['paymentid'];
      if(isset($Data['payimg'])){
        $Rec->payimg=$Data['payimg'];
        }
    //   $Rec->payimg=$Data['payimg'];
      $Rec->memo=$Data['memo'];
      $Rec->status=isset($Data['status'])?$Data['status']:0;
    //   $Rec->vip_no=isset($Data['vip_no'])?$Data['vip_no']:'';
      $Rec->sendsms=0;
      $Rec->save();



    //   if(isset($Data['status']) && $Data['status']==1) {
    //       /**积分奖励**/
    //       $Member = Member::find($Data['userid']);
    //       $integral = floor($Data['amount'] / intval(Cache::get("integralratio")));

    //       if ($integral >= 1) {

    //           $yuanintegral = $Member->integral;
    //           $Member->increment('integral', $integral);
    //           $Member->activation=1;//激活帐号
    //           $Member->save();
    //           $msg = [
    //               "userid" => $Data['userid'],
    //               "username" => $Data['amount'],
    //               "title" => "积分奖励",
    //               "content" => "积分奖励(" . $integral . ")",
    //               "from_name" => "系统审核",
    //               "types" => "积分奖励",
    //           ];
    //           \App\Membermsg::Send($msg);


    //           $log = [
    //               "userid" => $Member->id,
    //               "username" => $Member->username,
    //               "money" => $integral,
    //               "notice" => "积分奖励(+)",
    //               "type" => "积分奖励",
    //               "status" => "+",
    //               "yuanamount" => $yuanintegral,
    //               "houamount" => $Member->integral,
    //               "ip" => \Request::getClientIp(),
    //           ];

    //           \App\Moneylog::AddLog($log);


    //       }
    //   }

            return ["status"=>0,"data"=>$Rec];

    }

    protected function ConfirmRecharge($id,$status){


        $Model = Memberrecharge::find($id);

        if($Model->status==0){
            $Model->status=$status;
            $Model->paytime==Carbon::now();
            $Model->save();
            $type = 1;
            if($status==1){

                    $Member= Member::find($Model->userid);
                    $amount=  $Member->ktx_amount;
                    // $Member->activation=1;//激活帐号
                    $Member->save();
                    $Member->increment('ktx_amount',$Model->amount);
                    $log=[
                        "userid"=>$Model->userid,
                        "username"=>$Model->username,
                        "money"=>$Model->amount,
                        "notice"=>"充值成功(+)",
                        "type"=>"充值",
                        "status"=>"+",
                        "yuanamount"=>$amount,
                        "houamount"=>$Member->ktx_amount,
                        "ip"=>\Request::getClientIp(),
                        "recharge_id"=>$id,
                    ];

                    \App\Moneylog::AddLog($log);



                //购买累计进入总金额




                $msg=[
                    "userid"=>$Model->userid,
                    "username"=>$Model->username,
                    "title"=>"充值订单",
                    "content"=>"您的充值成功(".$Model->amount.")",
                    "from_name"=>"系统审核",
                    "types"=>"充值",
                ];

                \App\Membermsg::Send($msg);

                //添加个人统计
                DB::table('statistics')->where('user_id',$Model->userid)->increment('team_total_recharge',$Model->amount);
                //添加后台统计
                DB::table('statistics_sys')->where('id',1)->increment('recharge_amount',$Model->amount);


        /*-----*/
        // $yunbi_info = DB::table('products')->where(['title'=>'云币'])->first();
        //     $productid = $yunbi_info->id;
        //     $goumai_num = $Model->amount;

        //     //是否为股权，可复投

        // $NewProductbuy['userid'] = $Member->id;
        // $NewProductbuy['username'] = $Member->username;
        // $NewProductbuy['level'] = $Member->level;
        // $NewProductbuy['productid'] = $productid;

        // $NewProductbuy['category_id'] = $yunbi_info->category_id;
        // $NewProductbuy['amount'] = $Model->amount;
        // $NewProductbuy['ip'] = '';
        // $NewProductbuy['useritem_time'] = Carbon::now();
        // $NewProductbuy['useritem_time2'] = '';
        // $NewProductbuy['sendDay_count'] = 99999;

        //  $NewProductbuy['num'] = $Model->amount;//购买数量
        // $NewProductbuy['unit_price'] = $Model->amount;//购买时单价$yunbi_info->qtje
        // $NewProductbuy['zsje'] = $yunbi_info->zsje;
        // $NewProductbuy['zscp_id'] = $yunbi_info->zscp_id?$yunbi_info->zscp_id:0;

        // $NewProductbuy['reason'] = '用户充值';
        // $NewProductbuy['status'] = 1;
        // // dump($NewProductbuy);
        // $res = DB::table('productbuy')->insert($NewProductbuy);



        //如果是货币，添加到会员货币表
        // if($product->category_id == 11 && $pay_type == 1){






            // $currencys= new Membercurrencys();
            // $total_num = 0;
            // $user_currencys_info = DB::table('membercurrencys')->where(['userid'=>$Member->id,'productid'=>$productid])->orderBy('created_at','desc')->first();
            // if($user_currencys_info){
            //     // $update_currencys['num'] = $user_currencys_info->num + $request->number;
            //     // $update_currencys['total_num'] = $user_currencys_info->total_num + $request->number;
            //     $update_currencys['updated_at'] = Carbon::now();
            //     DB::table('membercurrencys')->where(['userid'=>$Member->id,'productid'=>$productid])->increment('num',intval($Model->amount));
            //      DB::table('membercurrencys')->where(['userid'=>$Member->id,'productid'=>$productid])->increment('total_num',intval($Model->amountr));
            // }else{
            //     $insert['userid'] = $Member->id;
            //     $insert['productid'] = $productid;
            //     $insert['num'] = $Model->amount;
            //     $insert['total_num'] = $total_num + $Model->amount;
            //     $insert['created_at'] = $insert['updated_at'] = Carbon::now();

            //     $currencys_res = DB::table('membercurrencys')->insert($insert);
            // }
        // }
        /*-----*/

                // $Member->activation=1;//激活帐号
                // $Member->save();
                // $msg=[
                //     "userid"=>$Model->userid,
                //     "username"=>$Model->username,
                //     "title"=>"充值成功",
                //     "content"=>"您的充值成功(".$Model->amount.")",
                //     "from_name"=>"系统审核",
                //     "types"=>"充值",
                // ];
                // \App\Membermsg::Send($msg);



                //  $log=[
                //             "userid"=>$Model->userid,
                //             "username"=>$Model->username,
                //             "money"=>$Model->amount,
                //             "notice"=>"充值成功(+)",
                //             "type"=>"充值",
                //             "status"=>"+",
                //             "yuanamount"=>$amount,
                //             "houamount"=>$Member->amount,
                //             "ip"=>\Request::getClientIp(),
                //  ];

                // \App\Moneylog::AddLog($log);



                /**积分奖励**/

            //   $integral= floor($Model->amount/intval(Cache::get("integralratio")));

            //   if($integral>=1) {

            //       $yuanintegral = $Member->integral;
            //       $Member->increment('integral', $integral);

            //       $msg = [
            //           "userid" => $Model->userid,
            //           "username" => $Model->username,
            //           "title" => "积分奖励",
            //           "content" => "积分奖励(" . $integral . ")",
            //           "from_name" => "系统审核",
            //           "types" => "积分奖励",
            //       ];
            //       \App\Membermsg::Send($msg);


            //       $log = [
            //           "userid" => $Model->userid,
            //           "username" => $Model->username,
            //           "money" => $integral,
            //           "notice" => "积分奖励(+)",
            //           "type" => "积分奖励",
            //           "status" => "+",
            //           "yuanamount" => $yuanintegral,
            //           "houamount" => $Member->integral,
            //           "ip" => \Request::getClientIp(),
            //       ];

            //       \App\Moneylog::AddLog($log);

            //   }



            }else{
                if($Model->type == '购买等级LV1' || $Model->type == '购买等级LV2' || $Model->type == '购买等级LV3'){
                    $type = 2;
                    if($Model->type == '购买等级LV1'){
                        $level = 1;
                    }elseif($Model->type == '购买等级LV2'){
                        $level = 2;
                    }elseif($Model->type == '购买等级LV3'){
                        $level = 3;
                    }
                }
                $msg=[
                    "userid"=>$Model->userid,
                    "username"=>$Model->username,
                    "title"=>$type == 1?"充值失败":"购买等级LV".$level."失败",
                    "content"=>$type == 1?"您的充值失败(".$Model->amount.")":"您购买等级LV".$level."失败",
                    "from_name"=>"系统审核",
                    "types"=>$type == 1?"充值":"购买等级LV".$level,
                ];
                \App\Membermsg::Send($msg);
            }


        }

      return ["status"=>0];

    }


}
