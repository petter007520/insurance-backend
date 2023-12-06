<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Memberticheng;
use App\Productbuy;
use App\Member;

class PaymentdbController extends Controller
{
    public $AlipayApiUrl = 'http://46.8.199.40/Pay_Index.html';
    public $callback_url = 'http://www.d0ga1.com/api/online_pay_not/db';//异步接口
    // public $out_uid = 4648216;//商户客户端用户ID
   
    
    /* LOG
    * notice 提交参数
    * info 支付返回
    * debug  支付失败
    * alert 异步返回数据
    * warning  异步数据
    */
    //代收异步结果通知（接收）
    public function notify_res(Request $request){
        // Log::channel('pay')->alert($request);
        $status = $request->returncode;
        $money = $request->amount;//订单金额 单位是分
        $merorderno = $request->orderid;//商户订单号
        $datetime = $request->datetime;//
        $sign = $request->sign;
        
        $requestarray = [
            'memberid'=>ENV('db_pay_app_id'),
            'orderid'=>$merorderno,
            'amount'=>$money,
            'datetime'=>$datetime,
            'returncode'=>$status,
            ];
            
        $has_productbuy = DB::table('productbuy')
            ->where(['order'=>$merorderno,'pay_type'=>6,'pay_status'=>0,'status'=>2])
            ->first();
            // dump($merorderno);
        //  dump($has_productbuy);exit;
        // $log = DB::getQueryLog();
        if(!$has_productbuy){
            $request['msg'] = '查无订单1';
            // $request['sql'] = $log;
            Log::channel('pay')->warning($request);
            return response()->json(["status"=>0, "msg"=>"查无订单"]);
        }
       
        //签名
        ksort($requestarray);                               //ASCII码排序
        reset($requestarray);                               //定位到第一个下标
        $md5str = "";
        foreach ($requestarray as $key => $val) {
            $md5str = $md5str.$key."=>".$val."&";
        }
        $check_sign= strtoupper(md5($md5str."key=".ENV('db_pay_app_key')));
        
        if($check_sign != $sign){
            $request['msg'] = '签名错误';
            Log::channel('recharge')->warning($request);
            return response()->json(["status"=>0, "msg"=>"签名错误"]);
        }
        
        if($status == 00){//1.待处理 3.完成 4.失败
            DB::beginTransaction();
            try{
                DB::table('productbuy')
                    ->where(['order'=>$merorderno,'pay_type'=>6,'pay_status'=>0])
                    ->update(['pay_status'=>1,'status'=>1,'pay_order_check_time'=>Carbon::now()]);
                $this->succ_post($has_productbuy->id);
                 DB::commit();
            }catch(\Exception $exception){
                Log::channel('pay')->alert($exception);
                DB::rollBack();
                return ['status'=>0,'msg'=>'提交失败，请重试'];
            } 
        }else{
            DB::table('productbuy')
                ->where(['order'=>$merorderno,'pay_type'=>6,'pay_status'=>0])
                ->update(['pay_status'=>$status,'status'=>3,'pay_order_check_time'=>Carbon::now()]);
        }
        Log::channel('pay')->alert('[success]'.$merorderno);
        
        return 'success';
    }
    
    //代收结果查询接口
    public function return_res(Request $request){
        // $orderNo = $request->orderNo;
        // $param = [
        //     'merchantNum'=>$this->merchantNum,
        //     'merchantOrderNo'=>$orderNo,
        //     'sign'=>($this->merchantNum.$orderNo.$this->merchantKey),//md5(商户号+商户订单号+商户秘钥)
        //     ];
        // $res = $this->paycurl($this->return_res_api,$param);
        // return response()->json(["status"=>1, "msg"=>"",'data'=>$res]);
    }
    
    public function curl($url,$data){ 
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_POST, 1); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT,'TEST'); 
        $result = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        return json_decode($result);
    }
    
    function setsign($param,$appSecret){
        $params = array_filter($param);
        ksort($params);
    
    
        $dataStr = http_build_query($params);
        $dataStr = urldecode($dataStr);
    
        $dataStr .= $appSecret;
        $sign = md5($dataStr);
        return $sign;
    }
    
    function get_random_code($num)
    {
        $codeSeeds = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
        // $codeSeeds .= "abcdefghijklmnpqrstuvwxyz";
        // $codeSeeds .= "0123456789_";
        $codeSeeds .= "123456789";
        $len = strlen($codeSeeds);
        $code = "";
        for ($i = 0; $i < $num; $i++) {
            $rand = rand(0, $len - 1);
            $code .= $codeSeeds[$rand];
        }
        return $code;
    }
    
    public function thirdToMoney(Request $request){
            
        $lastsession = $request->lastsession;
        if(!$lastsession){
            return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
        }else{
            $Member = Member::where("lastsession",$request->lastsession)->first();
            if(!$Member){
                return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
            }else{
                $request->session()->put('UserId',$Member->id, 120);
                $request->session()->put('UserName',$Member->username, 120);
                $request->session()->put('Member',$Member, 120);
            }
        }

        $UserId = $request->session()->get('UserId');

        $this->Member = Member::find($UserId);
        if(!$this->Member){
           return response()->json(["status"=>-1,"msg"=>"请先登录!"]);
        }
        if($this->Member->state == '0' || $this->Member->state == '-1'){
             return response()->json(["status"=>0,"msg"=>"帐号禁用中"]);
        }
        /*判断登陆*/    
        
// if(!in_array($UserId,[22433,22434])){
//   return response()->json(["status"=>0,"msg"=>"通道未开启"]);
// } 
            
        $pay_type = $request->pay_type;//付款方式
        if(!in_array($pay_type,[3,4,5,6])){
            return response()->json(["status"=>0,"msg"=>"支付方式错误"]);
        }
        $payment_id = 5;
        $payment_info =  DB::table('payment')->select('bankcode','enabled')->where(['id'=>$payment_id])->first();
        if($payment_info->enabled != 1){
            return response()->json(["status"=>0,"msg"=>"通道未开启"]);
        }
        $agent_code = $payment_info->bankcode;
        
        //购买项目
        $UserId =$request->session()->get('UserId');
        if($UserId<1){
            return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
        }
        // $checkSM = DB::table("member")->select('realname','card')->where(['id'=>$UserId])->first();
        // if(empty($checkSM->realname) || empty($checkSM->card)){
        //     return response()->json(["status"=>0,"msg"=>"请先完成实名后进行购买"]);
        // }
        
        if(!$request->productid || !is_numeric($request->productid)){
            return response()->json(["status"=>0,"msg"=>"项目不存在或已下架！！"]);
        }
        if($request->number<1 || !is_numeric($request->number)){
            return response()->json(["status"=>0,"msg"=>"购买项目数量错误！"]);
        }

        $product=DB::table("products")
            ->select('id','title','category_id','qtje','isft','tzzt','hkfs','shijian','zgje','qxdw','zsje','zsje_type','jyrsy','qtsl','zscp_id')
            ->where(['id'=>$request->productid])
            ->first();
        
        if(!$product){
            return response()->json(["status"=>0,"msg"=>"项目不存在或已下架！"]);
        }
        
        if((int)$request->number < (int)$product->qtsl){
            return response()->json(["status"=>0,"msg"=>"低于项目最低起投数量"]);
        }

        $Member= Member::select('id','username','amount','paypwd','state','realname','mobile','level','mtype')->where('state',1)->find($UserId);

        $integrals= $product->qtje*$request->number;
        
        $hkfs      = trim($product->hkfs);  //还款方式
        $zhouqi    = trim($product->shijian);//周期
        // if($product->category_id == 12){
        //     $hkfs = 4;
        // }
        //判断项目是否停止
        if($product->tzzt != 0){
            return response()->json(["status"=>0,"msg"=>"该项目已售罄"]);
        }
        //判断起投数量
        if($product->qtje > $integrals){
            return response()->json(["status"=>0,"msg"=>"您购买项目起投金额为".$product->qtje]);
        }
        //判断最高投
        if((int)$product->zgje !== 0){
            if($integrals > $product->zgje){
                return response()->json(["status"=>0,"msg"=>"您购买项目最高投入金额为".$product->zgje]);
            }
        }
        //判断投资是否投过
        if($product->isft == 0){
            $Productbuy= Productbuy::where("productid",$request->productid)->where("userid",$this->Member->id)->where('status','<>',3)->first();
            if($Productbuy){
                return response()->json(["status"=>0,"msg"=>"抱歉，该项目只允许投一次"]);
            }
        }
        
        //判断下一次领取时间
        $useritem_time2 = \App\Productbuy::DateAdd("d",1, date('Y-m-d 0:0:0',time()));

        $ip = $request->getClientIp();

        $sendDay_count = $hkfs == 1?1:$zhouqi;
        $NewProductbuy= new Productbuy();

        //赠送金额
        if($product->zsje_type == 2 ){
            $product->zsje = intval($integrals * (zsje * 0.01));
        }
        $order = 'DBF'.date('YmdHis').$this->get_random_code(8);

        $NewProductbuy->userid=$Member->id;
        $NewProductbuy->username=$Member->username;
        $NewProductbuy->level=$Member->level;
        $NewProductbuy->productid=$request->productid;
        $NewProductbuy->category_id=$product->category_id;
        $NewProductbuy->amount=$integrals;
        $NewProductbuy->ip=$ip;
        $NewProductbuy->useritem_time=Carbon::now();
        $NewProductbuy->useritem_time2=$useritem_time2;
        $NewProductbuy->sendday_count=$sendDay_count;
        $NewProductbuy->status = 2;               //未审核
        $NewProductbuy->num = $request->number; //购买数量
        $NewProductbuy->unit_price = $product->qtje;//购买时单价
        $NewProductbuy->zsje=$product->zsje;
        $NewProductbuy->zscp_id=$product->zscp_id?$product->zscp_id:0;
        $NewProductbuy->order = $order;
        // $NewProductbuy->gq_order = 'C'.$request->productid.($Member->id+2354);
        $NewProductbuy->created_date = date('Y-m-d');
        $NewProductbuy->pay_type = 6;           //支付
        $NewProductbuy->pay_status = 0;
        $NewProductbuy->mtype=$Member->mtype;
        // if($product->category_id == 12 ){
            // $NewProductbuy->gq_order = 'Y'.$this->get_random_code(9);
            // while(DB::table('productbuy')->where('gq_order',$NewProductbuy->gq_order)->first()){
                // $NewProductbuy->gq_order = 'Y'.$this->get_random_code(9);
            // }
        // }
        $res = $NewProductbuy->save();
        
        if(!$res){
            return response()->json(["status"=>0,"msg"=>"投资失败，请重新操作"]);
        }else{
            $time = time();
            // $integrals = $integrals * 100;
            $requestarray = [
                "pay_memberid" => ENV('db_pay_app_id'),//商户ID
                "pay_orderid" => $order,//订单号
                "pay_amount" => $integrals,//交易金额（元）
                "pay_applydate" => date('Y-m-d H:i:s',$time),//订单时间（例如：2021-05-06 10:20:09）
                "pay_bankcode" => 1,// 有填值就行，签名用  
                "pay_notifyurl" => $this->callback_url,
                "pay_callbackurl" => 'test',//页面跳转返回地址
            ];
            
            //签名
            ksort($requestarray);                               //ASCII码排序
            reset($requestarray);                               //定位到第一个下标
            $md5str = "";
            foreach ($requestarray as $key => $val) {
                $md5str = $md5str.$key."=>".$val."&";
            }
            $requestarray['pay_md5sign'] = strtoupper(md5($md5str."key=".ENV('db_pay_app_key')));
            
            $requestarray["tongdao"] = $agent_code;//通道编码  必传
            $requestarray["return_type"] = 1;//返回类型  0直接支付  1返回支付地址和平台订单号的json数据 2扫码支付
            // dump($paramArr);exit;
            Log::channel('pay')->notice(json_encode($requestarray,JSON_UNESCAPED_UNICODE));
// dump($this->AlipayApiUrl);
// dump($requestarray);
            $post_res = $this->curl($this->AlipayApiUrl,$requestarray);
            // dump($post_res);exit;
            // is_array($post_res);
            // is_object($post_res);
            // exit;
            $post_res_str = json_encode($post_res,JSON_UNESCAPED_UNICODE);//放log用
Log::channel('pay')->info('['.$order.']'.$post_res_str);
            if(isset($post_res->code) && $post_res->code== '1'){
                Log::channel('pay')->info('['.$order.']'.$post_res_str);
                // DB::table('productbuy')
                // ->where(['id'=>$NewProductbuy->id])
                // ->update(['third_party_order'=>$post_res->data->platform_order_no]);
                return response()->json(["status"=>1, "msg"=>"跳转支付",'payUrl'=>$post_res->pay_url,'data'=>$post_res]);
            }else{
                Log::channel('recharge')->error('['.$order.']'.$post_res_str);
                return response()->json(["status"=>0, "msg"=>'支付失败，请稍后重试','data'=>$post_res]);
            }
        }
        
    }
    
    public function succ_post($pro_buy_id){
       
        $pro_buy_data = DB::table("productbuy")->select('id','userid','username','productid','status','num','amount','pay_status')->where(['id'=>$pro_buy_id,'pay_status'=>1])->first();
        
        if(!$pro_buy_data){
            Log::channel('pay')->warning('查无订单('.$pro_buy_id.')');
            return response()->json(["status"=>0,"msg"=>"查无订单!"]);
        }
        if( $pro_buy_data->status!=1 || $pro_buy_data->pay_status != 1){
            Log::channel('pay')->warning('该订单还未完成支付('.$pro_buy_id.')');
            return response()->json(["status"=>0,"msg"=>"该订单还未完成支付"]);
        }
        $Member =  DB::table("member")->select('id','username','amount','level','mtype','activation','integral')->where(['id'=>$pro_buy_data->userid])->first();
        /*支付成功添加moneylog*/
        $product_info = DB::table('products')->select('title','category_id')->where(['id'=>$pro_buy_data->productid])->first();
        $notice = "参与项目(".$product_info->title.")";
        $money_log=[
            "userid"=>$Member->id,
            "username"=>$Member->username,
            "money"=>$pro_buy_data->amount,
            "notice"=>$notice,
            "type"=>"参与项目,第三方付款()",
            "status"=>"-",
            "yuanamount"=>$Member->amount,
            "houamount"=>$Member->amount,
            "ip"=>\Request::getClientIp(),
            "category_id"=>$product_info->category_id,
            "product_id"=>$pro_buy_data->productid,
            "product_title"=>$product_info->title,
           
        ];
        \App\Moneylog::AddLog($money_log);
        
        //站内消息
        $msg=[
            "userid"=>$Member->id,
            "username"=>$Member->username,
            "title"=>"参与项目",
            "content"=>"成功参与项目(".$product_info->title.")",
            "from_name"=>"系统通知",
            "types"=>"加入项目",
        ];
        \App\Membermsg::Send($msg);
        /*****/
        
        /**************支付成功赠送产品*****************/
        $product=DB::table("products")
            ->select('id','title','category_id','qtje','isft','tzzt','hkfs','shijian','zgje','qxdw','zsje','zsje_type','jyrsy','qtsl','zscp_id')
            ->where(['id'=>$pro_buy_data->productid])
            ->first();
        if($product->zscp_id != 0 && in_array($product->zsje_type,[1,3])){
            //赠送的产品信息
            $zscp_info = DB::table("products")
                ->select('id','title','category_id','qtje','isft','tzzt','hkfs','shijian','zgje','qxdw','zsje','zsje_type','jyrsy','qtsl','zscp_id')
                ->where(['id'=> $product->zscp_id])
                ->first();
                
            $zscp_id = $product->zscp_id;//赠送产品id
            //判断下一次领取时间
            if($Member->mtype == 0){
                $zscp['gq_order'] = '-1';
                //未设置时间默认第二天。
                $useritem_time2 = \App\Productbuy::DateAdd("d",1, date('Y-m-d 0:0:0',time()));
            }else{
                $dividend_type = DB::table('dividend_type')->where('id',$Member->mtype)->first();
                $useritem_time2 = date('Y-m-d 00:00:00',strtotime("+".$dividend_type->dividend_day." day"));
            }
            
            
            
            $sendDay_count = $product->shijian;//周期
           
            if($product->zsje_type == 3){
                $zszsl = $product->zsje * $pro_buy_data->num;//赠送倍数 * 购买数量
                $zszje = intval($zszsl * $zscp_info->qtje);//赠送总金额
            }else{
                $zszsl = $product->zsje;
                $zszje = intval($zszsl * $zscp_info->qtje);
            }
                
            $ip = \Request::getClientIp();
                
            //赠送项目
            $zscp_log=[
                "userid"=>$Member->id,
                "username"=>$Member->username,
                "money"=> $zszje,
                "notice"=>"参与慈善项目(".$product->title."),赠送项目(".$zscp_info->title.")",
                "type"=>"赠送项目",
                "status"=>"+",
                "yuanamount"=>$Member->amount,
                "houamount"=>$Member->amount,
                "ip"=>$ip,
                "category_id"=>$zscp_info->category_id,
                "product_id"=>$zscp_info->id,
                "product_title"=>$zscp_info->title,
                
            ];
            \App\Moneylog::AddLog($zscp_log);

            $zscp['userid'] = $Member->id;
            $zscp['username'] = $Member->username;
            $zscp['level'] = $Member->level;
            $zscp['productid'] = $zscp_info->id;
            $zscp['category_id'] = $zscp_info->category_id;
            $zscp['amount'] = $zszje; //赠送总金额
            $zscp['ip'] = $ip;
            $zscp['useritem_time'] = Carbon::now();
            $zscp['useritem_time2'] = $useritem_time2;
            $zscp['sendday_count'] = $sendDay_count;
            $zscp['status'] = 1;
            $zscp['num'] = $zszsl;//购买数量
            $zscp['unit_price'] = $zscp_info->qtje;//购买时单价
            $zscp['zsje'] = 0;
            $zscp['buy_from_id'] = $pro_buy_data->id;
            $zscp['created_date'] = date('Y-m-d');
            $zscp['mtype'] = $Member->mtype;
            
            $zscp['reason'] = "参与项目(".$product->title."),赠送项目(".$zscp_info->title.")";
            DB::table('productbuy')->insert($zscp);
            
        }
       
        
        //添加个人统计
        DB::table('statistics')->where('user_id',$Member->id)->increment('capital_flow',$pro_buy_data->amount);
        //添加后台统计
        DB::table('statistics_sys')->where('id',1)->increment('buy_amount',$pro_buy_data->amount);
        //统计表end
        
        //激活当前账号
        if($Member->activation == 0){
            DB::table('member')->where('id',$Member->id)->update(['activation'=>1]);
            if($Member->integral > 0){
                $activation_yuanamount = $Member->amount;
                $add_amount = $Member->integral;
                DB::table('member')->where('id',$Member->id)->increment('amount',$add_amount);
                $acc_log=[
                    "userid"=>$Member->id,
                    "username"=>$Member->username,
                    "money"=>$add_amount,
                    "notice"=>'激活账号,释放补贴',
                    "type"=>"激活账号释放补贴",
                    "status"=>"+",
                    "yuanamount"=>$activation_yuanamount,
                    "houamount"=>$activation_yuanamount + $add_amount,
                    "ip"=>\Request::getClientIp(),
                    "category_id"=>'0',
                    "product_id"=>'0',
                    "product_title"=>'0',
                    'num'=>'0',
                ];
                \App\Moneylog::AddLog($acc_log);
                DB::table('member')->where('id',$Member->id)->decrement('integral',$add_amount);
            }
        }
        // DB::table('member')->where('id',$Member->id)->update(['activation'=>1]);
        
         /**************支付成功判断团队奖励*****************/
        //上级 是否满足团队奖励
        $shangji_id = DB::table('membergrade')->where(['uid'=>$Member->id,'level'=>1])->value('pid');
        //  $lower_level_amount = DB::table('statistics')
        //         ->where('top_one_uid',$shangji_id)
        //         ->orwhere('top_two_uid',$shangji_id)
        //         ->orwhere('top_three_uid',$shangji_id)
        //         ->orwhere('top_four_uid',$shangji_id)
        //         ->orwhere('top_five_uid',$shangji_id)
        //         ->sum('capital_flow');
        // $teamrewards = DB::table('teamrewards')->where('team_amount','<',$lower_level_amount)->orderBy('id','desc')->first();
         $shangji_info = DB::table('member')->select('level','mtype','username','activation','amount','integral')->where('id',$shangji_id)->first();
        // if($teamrewards && $shangji_info){
        //     $invite_count = DB::table('membergrade')->where('pid',$shangji_id)->count();
           
            //当金额大于团队认购金额 且人数 
        //     if($lower_level_amount > $teamrewards->team_amount && $invite_count >= $teamrewards->team_num && $shangji_info->level < $teamrewards->id){
        //         //更新团队长等级
        //         DB::table('member')->where('id',$shangji_id)->update(['level'=>$teamrewards->id]);
        //         if($teamrewards->reward_equ_pid > 0 && $teamrewards->reward_equ_num > 0 ){
        //             $pid = $teamrewards->reward_equ_pid;
        //             $reg_give_product_info = DB::table("products")
        //                 ->select('id','title','category_id','qtje','isft','tzzt','hkfs','shijian','zgje','qxdw','zsje','zsje_type','jyrsy','qtsl','zscp_id')
        //                 ->where(['id'=>$pid])
        //                 ->first();
        //             //赠送数量
        //             $reg_give_product_pcount = $teamrewards->reward_equ_num;
        //             //赠送总金额
        //             $reg_give_product_money = $reg_give_product_pcount * $reg_give_product_info->qtje;
        //             $reg_give_product_id = $pid;
        //             //判断下一次领取时间
        //             $hkfs = $reg_give_product_info->hkfs;
        //             $zhouqi    = trim($reg_give_product_info->shijian);//周期
        //             $sendDay_count = $hkfs == 1?1:$zhouqi;
                    
        //             //如果当前账号未设置时间，则赋值-1
        //             if($shangji_info->mtype == 0){
        //                 $zscp['gq_order'] = '-1';
        //                 //未设置时间默认第二天。
        //                 $useritem_time2 = \App\Productbuy::DateAdd("d",1, date('Y-m-d 0:0:0',time()));
        //             }else{
        //                 $dividend_type = DB::table('dividend_type')->where('id',$shangji_info->mtype)->first();
        //                 $useritem_time2 = date('Y-m-d 00:00:00',strtotime("+".$dividend_type->dividend_day." day"));
        //             }
                    
        //             $NewProductbuy= new Productbuy();
        //             $NewProductbuy->userid = $shangji_id;
        //             $NewProductbuy->username = $shangji_info->username;
        //             $NewProductbuy->productid = $reg_give_product_id;
        //             $NewProductbuy->category_id=$reg_give_product_info->category_id;
        //             $NewProductbuy->amount= $reg_give_product_money;
        //             // $NewProductbuy->ip= \Request::getClientIp();
        //             $NewProductbuy->useritem_time = Carbon::now();
        //             $NewProductbuy->useritem_time2=$useritem_time2;
        //             $NewProductbuy->reason = "团队奖励赠送产品(".$reg_give_product_info->title.")";
        //             $NewProductbuy->sendDay_count=$sendDay_count;
        //             $NewProductbuy->num = $reg_give_product_pcount;//赠送数量
        //             $NewProductbuy->unit_price = $reg_give_product_info->qtje;//赠送时单价
        //             $NewProductbuy->zsje=0;//赠送金额
        //             $NewProductbuy->zscp_id=0;//
        //             $NewProductbuy->created_date=date('Y-m-d');
        //             $NewProductbuy->mtype=$shangji_info->mtype;
                   
        //             // $NewProductbuy->order = substr((date('YmdHis').$RegMember->id.$this->get_random_code(6)),0,25);
        //             $res = $NewProductbuy->save();
        //             //站内消息
        //             $msg=[
        //                 "userid"=>$shangji_id,
        //                 "username"=>$shangji_info->username,
        //                 "title"=>"团队奖励赠送产品",
        //                 "content"=>"成功加入项目(".$reg_give_product_info->title.")",
        //                 "from_name"=>"系统通知",
        //                 "types"=>"加入项目",
        //             ];
        //             \App\Membermsg::Send($msg);
        //             //
        //             $give_log=[
        //                 "userid"=>$shangji_id,
        //                 "username"=>$shangji_info->username,
        //                 "money"=> $reg_give_product_money,
        //                 "notice"=>"团队奖励产品(".$reg_give_product_info->title.")",
        //                 "type"=>"团队奖励项目",
        //                 "status"=>"+",
        //                 "yuanamount"=>0,
        //                 "houamount"=>0,
        //                 "ip"=>\Request::getClientIp(),
        //                 "category_id"=>$reg_give_product_info->category_id,
        //                 "product_id"=>$reg_give_product_info->id,
        //                 "product_title"=>$reg_give_product_info->title,
        //                 "num"=>$reg_give_product_pcount,
                        
        //             ];
        //             \App\Moneylog::AddLog($give_log);
                    
        //             DB::table('statistics')->where(['user_id'=>$shangji_id])->increment('team_capital_flow', $reg_give_product_money);
        //             // $next_reward_time = date('Y-m-d 00:00:00', strtotime(date('Y-m-01', time()) . ' +1 month'));//下次收时间(下月初)
        //             // DB::table('teamrewards_log')->where('uid',$userid)->update(['reward_time'=>$now_time,'next_reward_time'=>$next_reward_time]);
        //         }
                
        //         if($teamrewards->reward_amount > 0){
        //             $member_info = Member::select('amount')->find($shangji_id);
        //             $yuanamount = $member_info->amount;
        //             $member_info->increment('amount',$teamrewards->reward_amount);
        //             $give_amount_log=[
        //                 "userid"=>$shangji_id,
        //                 "username"=>$shangji_info->username,
        //                 "money"=> $teamrewards->reward_amount,
        //                 "notice"=>"团队奖励赠送金额(".$teamrewards->reward_amount.")",
        //                 "type"=>"团队奖励赠送金额",
        //                 "status"=>"+",
        //                 "yuanamount"=>$yuanamount,
        //                 "houamount"=>$member_info->amount,
        //                 "ip"=>\Request::getClientIp(),
        //                 "category_id"=>0,
        //                 "product_id"=>0,
        //                 "product_title"=>0,
                        
        //             ];
        //             \App\Moneylog::AddLog($give_amount_log);
        //         }
        //     }
        // }
        //团队奖励END
        
         //判断激活上级 激活要求：购买任意一个扶贫政策项目，或者介绍邀请三个好友，三个好友都有购买激活均可
        if($shangji_info && $shangji_info->activation == 0){
            $xiaji_count = DB::table('member')->where(['top_uid'=>$shangji_id,'activation'=>1])->count();
            if($xiaji_count >=3 && $shangji_info->integral > 0){
                $amount =  sprintf("%.2f",$shangji_info->amount + $shangji_info->integral);
                $sj_acc_log=[
                    "userid"=>$shangji_id,
                    "username"=>$shangji_info->username,
                    "money"=>$shangji_info->integral,
                    "notice"=>'激活账号,释放补贴',
                    "type"=>"激活账号释放补贴",
                    "status"=>"+",
                    "yuanamount"=>$shangji_info->amount,
                    "houamount"=>$shangji_info->amount + $shangji_info->integral,
                    "ip"=>\Request::getClientIp(),
                    "category_id"=>'0',
                    "product_id"=>'0',
                    // "product_title"=>'0',
                    'num'=>'0',
                ];
                \App\Moneylog::AddLog($sj_acc_log);
                DB::table('member')->where('id',$shangji_id)->update(['activation'=>1,'amount'=>$amount,'integral'=>0]);
            }
        }
        //激活end
                            
        /**************支付成功返佣金*****************/
        $Tichengs= Memberticheng::orderBy("id","asc")->get();//percent提成比例
        $checkBayong = \App\Productbuy::checkBayong($product->id);//查返佣比例
        $username= $buyman = $Member->username;
        $now_time = Carbon::now();
        $hideeen_username = substr_replace($Member->username, '****', 3,5);
        $UserId = $Member->id;
        $integrals = $pro_buy_data->amount;
        $products_title = $product_info->title;
        
        foreach ($Tichengs as $recent){
            $shangjia = \App\Productbuy::checkTjr($username);//上家姓名 username

            $ShangjiaMember= Member::where("username",$shangjia)->first();
            // $ShangjiaMember= Member::where("id",$shangjia)->first();
            // $checkBayong = 1;
            if(!$ShangjiaMember){
                break;
            }
            $has_log = DB::table('moneylog')->select('id')->where(['moneylog_userid'=>$ShangjiaMember->id,'from_uid'=>$UserId,'from_uid_buy_id'=>$pro_buy_id])->first();
            if (empty($shangjia) || empty($checkBayong) || $has_log) {
                break;
            }
            //分成钱数
            $rewardMoney = intval($integrals * $recent->percent * $checkBayong / 100);

            $MOamount=$ShangjiaMember->amount;

            $ShangjiaMember->increment('amount',$rewardMoney);

            $notice = "下线[".$hideeen_username."]购买(".$products_title.")项目分成(+)";

            $log=[
              "userid"=>$ShangjiaMember->id,
              "username"=>$ShangjiaMember->username,
              "money"=>$rewardMoney,
              "notice"=>$notice,
              "type"=>"下线购买分成",
              "status"=>"+",
              "yuanamount"=>$MOamount,
              "houamount"=>$ShangjiaMember->amount,
              "ip"=>\Request::getClientIp(),
              "category_id"=>$product_info->category_id,
              "product_id"=>$pro_buy_data->productid,
              "from_uid"=>$UserId,
              "from_uid_buy_id"=>$pro_buy_id,
            ];
            \App\Moneylog::AddLog($log);

            $data=[
              "userid"=>$ShangjiaMember->id,
              "username"=>$ShangjiaMember->username,
              "xxuserid"=>$UserId,
              "xxusername"=>$Member->username,
              "amount"=>$integrals,
              "preamount"=>$rewardMoney,
              "type"=>"下线分成",
              "status"=>"1",
              "xxcenter"=>$recent->name,
              "created_at"=>$now_time,
              "updated_at"=>$now_time,
            ];
            DB::table("membercashback")->insert($data);
            
          
            
            //上级
            // DB::table('statistics')->where('user_id',$ShangjiaMember->id)->increment('team_order_commission',$integrals);
            $username=$shangjia;
         }
        
        
        //只反一次
        // $membergrade_info = DB::table('membergrade')->where('uid',$Member->id)
        //     ->where(function($query){
        //         $query->where('level',1)
        //             ->orwhere('level',2)
        //             ->orwhere('level',3)
        //             ->orwhere('level',4)
        //             ->orwhere('level',5);
        //     })
        //     ->get();
        // $top_one_uid = $top_two_uid = $top_three_uid = $top_four_uid = $top_five_uid = 0;
        // foreach ($membergrade_info as $v){
        //     switch ($v->level) {
        //         case 1:
        //             $top_one_uid = $v->pid;
        //             break;
        //         case 2:
        //             $top_two_uid = $v->pid;
        //             break;
        //         case 3:
        //             $top_three_uid = $v->pid;
        //             break;
        //         case 4:
        //             $top_four_uid = $v->pid;
        //             break;
        //         case 5:
        //             $top_five_uid = $v->pid;
        //             break;
        //     }
        // }
                
        // foreach ($Tichengs as $k=>$recent){
            
        //     switch ($k) {
        //         case '0':
        //             $shangjia_id = $top_one_uid;
        //             break;
        //         case '1':
        //             $shangjia_id = $top_two_uid;
        //             break;
        //         case '2':
        //             $shangjia_id = $top_three_uid;
        //             break;
        //         case '3':
        //             $shangjia_id = $top_four_uid;
        //             break;
        //         case '4':
        //             $shangjia_id = $top_five_uid;
        //             break;
        //     }
        //     if (empty($shangjia_id) || empty($checkBayong) || $shangjia_id == '0') {
        //         break;
        //     }
            
        //     $ShangjiaMember= Member::select('id','username','introduction')->where("id",$shangjia_id)->first();
        //     $shangjia = $ShangjiaMember->username;
        //     $introduction_arr = explode(',',$ShangjiaMember->introduction);
        //     if(in_array($UserId,$introduction_arr)){
        //         continue;
        //     }
        //     //分成钱数
        //     $rewardMoney = intval($integrals * $recent->percent  / 100);
           
        //     $title = "尊敬的{$shangjia}会员您好！您的{$recent->name}分成已到账";
        //     $content = "您的下线{$buyman}购买项目成功,{$rewardMoney}元已赠送到您的账号,当前的提成比例为".$recent->percent * $checkBayong."%";
        //     //站内消息
        //     $msg=[
        //       "userid"=>$ShangjiaMember->id,
        //       "username"=>$ShangjiaMember->username,
        //       "title"=>$title,
        //       "content"=>$content,
        //       "from_name"=>"系统通知",
        //       "types"=>"下线购买分成",
        //     ];
        //     \App\Membermsg::Send($msg);

        //     $MOamount=$ShangjiaMember->amount;
            
        //     $ShangjiaMember->increment('amount',$rewardMoney);
            
        //     $notice = "下线(".$hideeen_username.")购买(".$product->title.")项目分成";
            
        //     $log=[
        //         "userid"=>$ShangjiaMember->id,
        //         "username"=>$ShangjiaMember->username,
        //         "money"=>$rewardMoney,
        //         "notice"=>$notice,
        //         "type"=>"下线购买分成",
        //         "status"=>"+",
        //         "yuanamount"=>$MOamount,
        //         "houamount"=>$ShangjiaMember->amount,
        //         "ip"=>\Request::getClientIp(),
        //         "category_id"=>$product->category_id,
        //         "product_id"=>$product->id,
        //         "from_uid"=>$UserId,
        //         "from_uid_buy_id"=>$NewProductbuy->id,
               
        //     ];
        //     \App\Moneylog::AddLog($log);
            
        //     $data=[
        //         "userid"=>$ShangjiaMember->id,
        //         "username"=>$ShangjiaMember->username,
        //         "xxuserid"=>$Member->id,
        //         "xxusername"=>$Member->username,
        //         "amount"=>$integrals,
        //         "preamount"=>$rewardMoney,
        //         "type"=>"下线分成",
        //         "status"=>"1",
        //         "xxcenter"=>$recent->name,
        //         "created_at"=>$now_time,
        //         "updated_at"=>$now_time,
        //     ];
        //     DB::table("membercashback")->insert($data);
            
        //     //更新已返佣uid
        //     if(empty($ShangjiaMember->introduction)){
        //         $introduction = $UserId;
        //     }else{
        //         $introduction = $ShangjiaMember->introduction.','.$UserId;
        //     }
        //     DB::table('member')->where(['id'=>$ShangjiaMember->id])->update(['introduction'=>$introduction]);
        //     $shangjia_id = 0;
            
        // }
    }
    
}


?>
