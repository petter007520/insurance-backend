<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Session;
use App\Productbuy;

class PaymentController extends Controller
{
    private $AlipayApiUrl = 'http://jubapay.xyz:56700/api/pay/create_order';
    private $callback_url = 'http://www.x4c8c27a.com/api/online_pay_notify/notify';//支付成功回调url
    private $mchid = '20000307';
    private $productId = '8032';
    private $currency = 'cny';
    private $secretkey = '5ZJ0QUDI5WPZUZQJ0PUMFGJSJXOUHRJUQG9DHXHFYRTLBDEJOL1TOII2CPM2BOOIIMYYKOQDW6FJ4IRUBLRJEJUNQZXFBZUAZB88MUS6L80UJEKEQZSTOQI2O6RE7M51';

    private function set_productId($pay_type){
        switch($pay_type){
            case '3':
                $this->productId = '8049';
                break;
            case '4':
                $this->productId = '8044';
                break;
        }
        return true;
    }


    /**
     * 获取三方支付URL
     * @param $order_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function thirdToPay($order_id): \Illuminate\Http\JsonResponse
    {
        $order = Productbuy::where(['id' => $order_id,'status'=>2,'pay_status'=>0])->first(['userid','order','amount','real_amount','third_party_order','pay_type']);
        if(!$order){
            return response()->json(["status"=>0, "msg"=>'订单不存在','data'=>'']);
        }
        //获取通道
        $this->set_productId($order->pay_type);
        $signBody = [
            'mchId'      => $this->mchid,
            'productId'  => $this->productId,
            'mchOrderNo' => $order->order,
            'amount'     => intval($order->real_amount * 100), //单位：分
            'currency'   => $this->currency,
            'notifyUrl'  => $this->callback_url,
            'subject'    => '五行之旅',
            'body'       => '五行世界之旅',
            'reqTime'    => date('YmdHis',time()),
            'version'    => '1.0'
        ];
        $sign = $this->sign($signBody);
        $signBody = array_merge($signBody,['sign'=>$sign]);
        Log::channel('pay')->notice(json_encode($signBody,JSON_UNESCAPED_UNICODE));
        $res = $this->curl($this->AlipayApiUrl,http_build_query($signBody));
        $pay_res_str = json_encode($res,JSON_UNESCAPED_UNICODE);//放log用
        if(isset($res['retCode']) && $res['retCode'] == 0){
            Log::channel('pay')->info('['.$order->order.']'.$pay_res_str);
            $url = $this->get_pay_url($res);
            if(empty($url)){
                return response()->json(["status"=>0, "msg"=>'支付通道异常！','data'=>'']);
            }
            //更新三方订单号
            DB::table('productbuy')->where(['id' => $order_id,'pay_type'=>3,'status'=>2,'pay_status'=>0])->update(['third_party_order'=>$res['payOrderId']]);
            return response()->json(["status"=>1, "msg"=>"跳转支付",'payUrl'=>$url,'data'=>$res,'order_no'=>$order->order]);
        }
        Log::channel('payfail')->error('['.$order->order.']'.$pay_res_str.'|');
        return response()->json(["status"=>0, "msg"=>'支付失败，请稍后重试','data'=>$res]);
    }

    /**
     * 支付回调
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse|string
     */
    public function thirdPayNotify(Request $request){
        $input = file_get_contents('php://input');
        Log::channel('pay_notify')->alert($input);
        // 字符串转为数组
        parse_str($input,$inputArray);
        $third_order_no =$inputArray['payOrderId'];//三方支付订单号
        $order = $inputArray['mchOrderNo'];//商户订单号
        $amount = $inputArray['amount'];//订单金额 单位是分
        $income = $inputArray['income'];//用户实际付款的金额,单位分
        $status = $inputArray['status'];//支付状态,-2:订单已关闭,0-订单生成,1-支付中,2-支付成功,3-业务处理完成,4-已退款（2和3都表示支付成功,3表示支付平台回调商户且返回成功后的状态）
        $payTime = $inputArray['paySuccTime'];//支付时间
        $backType = $inputArray['backType'];//支付时间
        $reqTime = $inputArray['reqTime'];//通知请求时间
        $sign = $inputArray['sign'];
        $has_productbuy_order = DB::table('productbuy')
            ->where(['order'=>$order,'pay_status'=>0,'status'=>2])
            ->first(['id','pay_type']);
        if(!$has_productbuy_order){
            $inputArray['msg'] = '查无订单';
            Log::channel('pay')->warning($request);
            Log::channel('pay')->warning($inputArray);
            return response()->json(["status"=>0, "msg"=>"查无订单"]);
        }
        //获取通道
        $this->set_productId($has_productbuy_order->pay_type);
        // 签名内容
        $signBody = [
            'payOrderId' => $third_order_no,
            'mchId' => $this->mchid,
            'productId' => $this->productId,
            'mchOrderNo' => $order,
            'amount' => $amount,
            'income' => $income,
            'status' => $status,
            'paySuccTime' => $payTime,
            'backType' => $backType,
            'reqTime'  => $reqTime,
        ];

        $sign_check_build = $this->sign($signBody);
        if($sign_check_build !== $sign){
            $inputArray['msg'] = '签名错误';
            Log::channel('payfail')->warning($inputArray);
            return response()->json(["status"=>0, "msg"=>"签名错误"]);
        }
        //支付成功
        if($status == 2){
            DB::beginTransaction();
            try{
                DB::table('productbuy')
                    ->where(['order'=>$order,'pay_type'=>3,'pay_status'=>0])
                    ->update(['pay_status'=>1,'status'=>1,'pay_order_check_time'=>Carbon::now()]);
                $ret = (new PayOrderController())->third_pay_finish_payment($has_productbuy_order->id);
                if($ret['status'] == 0){
                    Log::channel('pay')->alert($ret['msg']);
                    DB::rollBack();
                    return ['status'=>0,'msg'=>'提交失败，请重试'];
                }
                DB::commit();
            }catch(\Exception $exception){
                Log::channel('pay')->alert($exception->getMessage());
                DB::rollBack();
                return ['status'=>0,'msg'=>'提交失败，请重试'];
            }
        }
        Log::channel('pay')->alert('[success]'.$order);
        return 'success';
    }

    private function get_pay_url($payment){
        switch ($payment['payMethod']){
            case 'formJump':
                $url = $payment['payUrl'];
                break;
            case 'codeImg':
                $url = $payment['codeUrl'];
                break;
            case 'wxApp':
                $url = $payment['payParams']['appStr'];
                break;
            default:
                $url = '';
        }
        return $url;
    }

    /**
     * 生成签名
     * @param $signBody
     * @return string
     */
    private function sign($signBody): string
    {
        //签名
        ksort($signBody);//ASCII码排序
        $signStr = "";
        foreach ($signBody as $key => $val) {
            $signStr .= $key."=".$val."&";
        }
        $signParams = $signStr.'key='.$this->secretkey;
        return strtoupper(md5($signParams));
    }

    private function curl($url, $data){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result,true);
    }
}
