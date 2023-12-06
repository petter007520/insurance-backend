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

class RechargeController extends Controller
{
    public $AlipayApiUrl = 'http://api.shangdubook.com/Api/Pay/unionOrder';
    public $callback_url = 'http://www.d0ga1.com/api/recharge_online_pay_not';//异步接口
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
        Log::channel('recharge')->alert($request);
        $platform_product_id = $request->platform_product_id;//平台产品ID
        $status = $request->status;//订单状态 1.待处理 3.完成 4.失败
        $pay_order_id = $request->pay_order_id;//平台订单号
        $out_uid = $request->out_uid;//
        $out_trade_no = $request->out_trade_no;//商户订单号
        $amount = $request->amount;//订单金额 元/两位小数
        $real_amount = $request->real_amount;//实付金额 元/两位小数
        $cny_rate = $request->cny_rate;//人民币汇率
        $exchange_rate = $request->exchange_rate;//兑换比例
        $notify_time = $request->notify_time;//通知时间
        $sign = $request->sign;
        
        $paramArr = [
            'platform_product_id'=>$platform_product_id,
            'status'=>$status,
            'pay_order_id'=>$pay_order_id,
            'out_uid'=>ENV('pay_out_uid'),
            'out_trade_no'=>$out_trade_no,
            'amount'=>$amount,
            'real_amount'=>$real_amount,
            'cny_rate'=>$cny_rate,
            'exchange_rate'=>$exchange_rate,
            'notify_time'=>$notify_time,
            ];
            
        $has_recharge = DB::table('memberrecharge')
            ->where(['ordernumber'=>$out_trade_no,'paymentid'=>4,'third_party_order'=>$pay_order_id,'pay_status'=>0,'status'=>0])
            ->first();
            // dump($has_recharge);exit;
        // $log = DB::getQueryLog();
        if(!$has_recharge){
            $request['msg'] = '查无订单1';
            // $request['sql'] = $log;
            Log::channel('recharge')->warning($request);
            return response()->json(["status"=>0, "msg"=>"查无订单"]);
        }
        
        $check_sign = $this->setsign($paramArr,ENV('pay_app_key'));
        // $check_sign = md5(ENV('pay_app_id').$paramArr['orderno'].$paramArr['money'].$paramArr['merorderno'].$paramArr['time'].ENV('pay_app_key'));
        if($check_sign != $sign){
            $request['msg'] = '签名错误';
            Log::channel('recharge')->warning($request);
            return response()->json(["status"=>0, "msg"=>"签名错误"]);
        }
        
        if($status == 3){//1.待处理 3.完成 4.失败
            DB::beginTransaction();
            try{
                DB::table('memberrecharge')
                    ->where(['ordernumber'=>$out_trade_no,'paymentid'=>4,'third_party_order'=>$pay_order_id,'pay_status'=>0])
                    ->update(['pay_status'=>1,'status'=>1,'pay_order_check_time'=>Carbon::now()]);
                $this->succ_post($has_recharge->id);
                 DB::commit();
            }catch(\Exception $exception){
                Log::channel('recharge')->alert($exception);
                DB::rollBack();
                return ['status'=>0,'msg'=>'提交失败，请重试'];
            } 
        }else{
            DB::table('productbuy')
                ->where(['order'=>$out_trade_no,'paymentid'=>4,'third_party_order'=>$pay_order_id,'pay_status'=>0])
                ->update(['pay_status'=>$status,'status'=>3,'pay_order_check_time'=>Carbon::now()]);
        }
        Log::channel('recharge')->alert('[success]'.$out_trade_no.'-'.$pay_order_id);
        
        return 'success';
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

        $amount= intval($request->amount);

        $recharge_min_money = DB::table('setings')->where('keyname','recharge_min_money')->value('value');
        if($amount < $recharge_min_money){
            return response()->json(["status"=>0, "msg"=>"最小充值金额为".$recharge_min_money]);
        }  
        $payment_id = 4;
        
        $payment_info =  DB::table('payment')->select('bankcode','enabled')->where(['id'=>$payment_id])->first();
        if($payment_info->enabled != 1){
            return response()->json(["status"=>0,"msg"=>"通道未开启"]);
        }
        $agent_code = $payment_info->bankcode;
        
        $memo= '第三方充值'.$request->amount;
        $order = "RF".date('YmdHis').$this->get_random_code(8);
        $res =  \App\Memberrecharge::Recharge([
            "userid"=>$UserId, //会员ID
            "amount"=>$request->amount,//金额
            "memo"=>$memo,//备注
            "paymentid"=>"4",//充值方式 1支付宝,2微信,3银行卡
            "ip"=>$request->getClientIp(),//IP
            "paytime"=>date("Y-m-d H:i:s"),
            "type"=>"用户充值",//类型 Cache(RechargeType):系统充值|优惠活动|优惠充值|后台充值|用户充值
            "ordernumber"=>$order,
            // "payimg"=>$request->payimg,//支付凭证
            // "vip_no"=>$vip_no,//vip编码
        ]);
        // dump($res);
        // dump($res['data']->id);exit;
        if(!$res){
            return response()->json(["status"=>0,"msg"=>"充值失败，请重新操作"]);
        }else{
            
            $time = time();
            $integrals = $request->amount;
            $paramArr = [
                'app_id'=>ENV('pay_app_id'),
                'agent_code'=>$agent_code,//通道编码
                'out_uid'=>ENV('pay_out_uid'),
                'out_trade_no'=>$order,
                'amount'=>sprintf("%.2f",$integrals),//订单金额 单位：元 保留2位小数
                // 'amount'=>1.00,
                'currency'=>2,//货币类型 ，固定值 2
                'timestamp'=>$time,
                'callback_url'=>$this->callback_url,
                'create_ip'=>'101.227.131.220',
            ];
            
            //签名
            $paramArr['sign'] = $this->setsign($paramArr,ENV('pay_app_key'));

            Log::channel('recharge')->notice(json_encode($paramArr,JSON_UNESCAPED_UNICODE));

            $post_res = $this->curl($this->AlipayApiUrl,$paramArr);

            $post_res_str = json_encode($post_res,JSON_UNESCAPED_UNICODE);//放log用
Log::channel('recharge')->info('['.$order.']'.$post_res_str);
            if(isset($post_res->status) && $post_res->status== 200){
                Log::channel('recharge')->info('['.$order.']'.$post_res_str);
                DB::table('memberrecharge')
                ->where(['id'=>$res['data']->id])
                ->update(['third_party_order'=>$post_res->data->platform_order_no]);
                return response()->json(["status"=>1, "msg"=>"跳转支付",'payUrl'=>$post_res->data->pay_url,'data'=>$post_res]);
            }else{
                Log::channel('recharge')->error('['.$order.']'.$post_res_str);
                return response()->json(["status"=>0, "msg"=>$post_res->msg]);
            }
        }
        
    }
    
    public function succ_post($pro_buy_id){
       
        $pro_recharge_data = DB::table("memberrecharge")->select('id','ordernumber','userid','username','status','amount','pay_status')->where(['id'=>$pro_buy_id,'pay_status'=>1])->first();
        
        if(!$pro_recharge_data){
            Log::channel('recharge')->warning('查无订单('.$pro_buy_id.')');
            return response()->json(["status"=>0,"msg"=>"查无订单!"]);
        }
        if( $pro_recharge_data->status!=1 || $pro_recharge_data->pay_status != 1){
            Log::channel('recharge')->warning('该订单还未完成支付('.$pro_buy_id.')');
            return response()->json(["status"=>0,"msg"=>"该订单还未完成支付"]);
        }
        // $Member =  DB::table("member")->select('id','username','amount','level','mtype')->where(['id'=>$pro_buy_data->userid])->first();
        /*支付成功添加moneylog*/
        $Member= Member::find($pro_recharge_data->userid);
        $amount=  $Member->amount;
        $Member->increment('amount',$pro_recharge_data->amount);
        $log=[
            "userid"=>$pro_recharge_data->userid,
            "username"=>$pro_recharge_data->username,
            "money"=>$pro_recharge_data->amount,
            "notice"=>"充值成功(+)",
            "type"=>"充值",
            "status"=>"+",
            "yuanamount"=>$amount,
            "houamount"=>$Member->amount,
            "ip"=>\Request::getClientIp(),
            "recharge_id"=>$pro_buy_id,
        ];

        \App\Moneylog::AddLog($log);
        
         //添加个人统计
        DB::table('statistics')->where('user_id',$pro_recharge_data->userid)->increment('team_total_recharge',$pro_recharge_data->amount);
        //添加后台统计
        DB::table('statistics_sys')->where('id',1)->increment('recharge_amount',$pro_recharge_data->amount);
        
    }
    
}


?>
