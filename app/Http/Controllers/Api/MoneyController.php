<?php

namespace App\Http\Controllers\Api;
use App\Channel;
use App\Http\Controllers\Controller;
use App\Member;
use App\Memberlevel;
use App\Memberwithdrawal;
use App\Order;
use App\Payment;
use App\Product;
use App\Productbuy;
use App\TreeProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Session;

class MoneyController extends Controller
{
    public $cachetime=600;
    public $Template='wap';
    private $Member = [];
    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            //请求预检返回 200
            if($request->method() == 'OPTIONS'){
                return response()->json(["status" => 1, "msg" => "ok"]);
            }
            $lastsession = $request->header('lastsession');
            if(!$lastsession){
                return response()->json(["status"=>-1,"msg"=>"请先登录！"],401);
            }else{
                $Member = Member::where("lastsession",$lastsession)->first();
                if(!$Member){
                    return response()->json(["status"=>-1,"msg"=>"请先登录！"],401);
                }else{
                    $request->session()->put('UserId',$Member->id, 120);
                    $request->session()->put('UserName',$Member->username, 120);
                    $request->session()->put('Member',$Member, 120);
                }
            }
            $UserId = $request->session()->get('UserId');
            $this->Member = Member::find($UserId);
            if(!$this->Member){
               return response()->json(["status"=>-1,"msg"=>"请先登录!"],401);
            }
            if($this->Member->state == -1){
                  return response()->json(["status"=>0,"msg"=>"帐号禁用中"]);
             }
           return $next($request);
       });
        /**网站缓存功能生成**/
        if(Cache::has("admin.payment")){
            $this->payment =Cache::get("admin.payment");
        }else {
            $payments = DB::table("payment")->get();
            $payment = [];
            foreach ($payments as $pay) {
                $payment[$pay->id] = $pay->pay_name;
            }
            $this->payment =$payment;
            Cache::put("admin.payment",$payment,Cache::get("cachetime"));
        }
    }

    /***充值***/
    public function recharge(Request $request){

        //充值直接跳转购买云币


        $UserId =$request->session()->get('UserId');
        $amount= intval($request->amount);
        // $type =$request->get('type',1);
        // $level =$request->get('level');

        $recharge_min_money = DB::table('setings')->where('keyname','recharge_min_money')->value('value');
        if($amount<$recharge_min_money){
            return response()->json(["status"=>0, "msg"=>"最小充值金额为".$recharge_min_money]);
        }

        //是否购买等级
        // if($type == 2){
        //     if(!in_array($level,[1,2,3])){
        //         return response()->json(["status"=>0, "msg"=>"等级错误"]);
        //     }

        //     $user_vip_info = DB::table('memberlevel')->get();
        //     if($level == 1 && $user_vip_info[0]->price != $amount){
        //         return response()->json(["status"=>0, "msg"=>"金额错误"]);
        //     }elseif($level == 2 && $user_vip_info[1]->price != $amount){
        //         return response()->json(["status"=>0, "msg"=>"金额错误"]);
        //     }elseif ($level == 3 && $user_vip_info[2]->price != $amount){
        //         return response()->json(["status"=>0, "msg"=>"金额错误"]);
        //     }
        // }
$request->paymentid = 3;
        if(!isset($this->payment[$request->paymentid])){
            return response()->json(["status"=>0, "msg"=>"充值方式错误"]);
        }
        if($request->paymentid == 3 && !isset($request->payimg)){
            return response()->json(["status"=>0, "msg"=>"请上传支付凭证"]);
        }

        // $member_level = DB::table('member')->where('id',$UserId)->value('level');
        // if($member_level != 0 && $member_level >= $level){
        //     return response()->json(["status"=>0, "msg"=>"请勿重复购买同级或低等级VIP"]);
        // }
        // $has_memberrecharge = DB::table('memberrecharge')->where(['userid'=>$UserId,'status'=>0])->first();
        // if($has_memberrecharge){
        //     return response()->json(["status"=>0, "msg"=>"您有待审核订单，请勿重复购买"]);
        // }

         $memo= $this->payment[$request->paymentid].'充值'.$request->amount;
//        $memo= $this->payment[$request->paymentid].'充值云币'.$request->amount;

        // $vip_no = $this->get_random_code();
        // while(DB::table('memberrecharge')->select('id')->where(['vip_no'=>$vip_no])->first()){
        //     $vip_no = $this->get_random_code();
        // }
         \App\Memberrecharge::Recharge([
            "userid"=>$UserId, //会员ID
            "amount"=>$request->amount,//金额
            "memo"=>$memo,//备注
            "paymentid"=>'3',//充值方式 1支付宝,2微信,3银行卡
            "ip"=>$request->getClientIp(),//IP
            "paytime"=>date("Y-m-d H:i:s"),
            "type"=>"用户充值",//类型 Cache(RechargeType):系统充值|优惠活动|优惠充值|后台充值|用户充值
            "payimg"=>$request->payimg,//支付凭证
            // "vip_no"=>$vip_no,//vip编码
        ]);

        $msg=[
            "userid"=>$UserId,
            "username"=>$this->Member->username,
            "title"=>'充值订单',
            "content"=>"您的充值申请提交成功(".$request->amount.")",
            "from_name"=>"系统通知",
            "types"=>"充值",
        ];
        \App\Membermsg::Send($msg);

        return response()->json(["status"=>1, "msg"=>"申请提交成功"]);
    }

    //等级信息
    public function getLevelInfo(Request $request){
        $UserId =$request->session()->get('UserId');
        $data['mylevel'] = DB::table('member')->where('id',$UserId)->value('level');
        $data['vip_info'] = DB::table('memberlevel')->get();
        $gift_equity_lv1 = DB::table('setings')->where(['keyname'=>'gift_equity_lv1'])->first();
        $gift_equity_lv2 = DB::table('setings')->where(['keyname'=>'gift_equity_lv2'])->first();
        $gift_equity_lv3 = DB::table('setings')->where(['keyname'=>'gift_equity_lv3'])->first();
        $gift_equity = [];
        array_push($gift_equity ,$gift_equity_lv1,$gift_equity_lv2,$gift_equity_lv3);
        foreach ($gift_equity as $k=>$v){
            $v->level = $k+1;
        }
        $data['gift_equity'] = $gift_equity;

        return response()->json(["status"=>1, "msg"=>"返回成功",'data'=>$data]);
    }

    /*咨询页查询等级编码*/
    public function queryLevelCode(Request $request){
        $UserId =$request->session()->get('UserId');
        $code = $request->get('code');

        $data = DB::table('member')->select('introduction','vip_no')->where(['vip_no'=>$code])->first();
        // $data = '暂未开放';
        return response()->json(["status"=>1, "msg"=>"返回成功",'data'=>$data]);
    }

    function get_random_code()
    {
        // $codeSeeds = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
        // $codeSeeds .= "abcdefghijklmnpqrstuvwxyz";
        // $codeSeeds .= "0123456789_";

        $codeSeeds_num = "123456789";
        $codeSeeds_en = "ABCDEFGHIJKLMNPQRSTUVWXYZ";

        $len = strlen($codeSeeds_en);
        $code_en = "";
        for ($i = 0; $i < 2; $i++) {
            $rand = rand(0, $len - 1);
            $code_en .= $codeSeeds_en[$rand];
        }
        $len = strlen($codeSeeds_num);
        $code_num = "";
        for ($i = 0; $i < 6; $i++) {
            $rand = rand(0, $len - 1);
            $code_num .= $codeSeeds_num[$rand];
        }
        $code = $code_en.$code_num;
        return $code;
    }

    /***提现***/
    public function withdraw(Request $request)
    {
        $UserId = $this->Member->id;
        $type = $request->post('type',1);
        if(!in_array($type,[1,3])){
            return response()->json(["status"=>0, "msg"=>"提现方式不支持"]);
        }
        $txtime=Cache::get("tixiantime");
        if(!$txtime){
            $txtime = Db::table("setings")->where('keyname','tixiantime')->value('value');
        }
        $timerarr= explode("-",$txtime);
        if(isset($timerarr) && count($timerarr)==2) {
            $Nowtime=Carbon::now()->getTimestamp();
            $Stime=date("Y-m-d H:i", strtotime($timerarr[0]));
            $Etime=date("Y-m-d H:i", strtotime($timerarr[1]));
            if($Nowtime< strtotime($Stime) || $Nowtime>strtotime($Etime)) {
                return response()->json(["status"=>0, "msg"=>"提款时间为" . date("H:i", strtotime($timerarr[0])) . "-" . date("H:i", strtotime($timerarr[1]))]);
            }
        }
        $Daywithdrawals=Cache::get("Daywithdrawals");
        if(!$Daywithdrawals){
            $Daywithdrawals = Db::table("setings")->where('keyname','Daywithdrawals')->value('value');
        }
        $TodayWithdrawals = Memberwithdrawal::where("userid",$UserId)
            ->whereIn("status",[0,1])
            ->whereDate("created_at",Carbon::now()->format("Y-m-d"))
            ->count();
        if($Daywithdrawals<=$TodayWithdrawals){
            return response()->json(["status"=>0, "msg"=>"您的今日提款次数已收完,每日提现次数:".$Daywithdrawals."次"]);
        }
        $amount= intval($request->amount);
        $withdrawalmin = Cache::get("withdrawalmin");
        if(!$withdrawalmin){
            $withdrawalmin = Db::table("setings")->where('keyname','withdrawalmin')->value('value');
        }
        if($amount<$withdrawalmin){
            return response()->json(["status"=>0, "msg"=>"最低提款金额为".$withdrawalmin]);
        }

        $has_bank = DB::table('memberbank')->select('id')->where(['id'=>$request->bank_id,'userid'=>$UserId])->first();
        if(!$request->bank_id || !$has_bank){
            return response()->json(["status"=>0, "msg"=>"请选择正确提现账号"]);
        }

        if(\App\Member::DecryptPassWord($this->Member->paypwd)!=$request->paypwd){
            return response()->json(["status"=>0, "msg"=>"交易密码错误"]);

        }

        $Member= Member::find($UserId);
        if($Member->ktx_amount<$amount){
            return response()->json(["status"=>0, "msg"=>"帐户余额不足"]);
        }

        //手续费
        $withdra_fee = Db::table("setings")->where('keyname','withdra_fee')->value('value');
        $fee = sprintf("%.2f",$withdra_fee *$amount *0.01);
        $after_amount = sprintf("%.2f",$amount- $fee);

        $data = \App\Memberwithdrawal::AddWithdrawal($UserId, $amount,$request->bank_id,$after_amount,$fee,$type);
        if($data['status']== 1){
            return response()->json(["status"=>1, "msg"=>"提现成功"]);
        }else{
            return response()->json(["status"=>0, "msg"=>$data['msg']]);
        }
    }

    /***我的充值记录***/
    public function recharges(Request $request){
        $UserId =$request->session()->get('UserId');
        $pageSize = $request->get('pageSize',15);

        $list = DB::table("memberrecharge")
            ->select('ordernumber','username','amount','paymentid','status','created_at','payimg','type')
            ->where("userid",$UserId)
            ->where('type','<>','购买等级LV1')
            ->where('type','<>','购买等级LV2')
            ->where('type','<>','购买等级LV3')
            ->orderBy("id","desc")
            ->paginate($pageSize);
        $alipay = DB::table('payment')->select('pay_pic')->find(1);
        $weixin = DB::table('payment')->select('pay_pic')->find(2);
        $ChinaPay = DB::table('payment')->select('pay_bank')->find(3);

        foreach ($list as $item){
            if($item->paymentid == 3){
                $item->payinfo = explode('<br>',$ChinaPay->pay_bank);
            }else if($item->paymentid == 1){
                $item->payinfo = $alipay->pay_pic;
            }else{
                $item->payinfo = $weixin->pay_pic;
            }
        }
        return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$list]);
    }

    /***我的提款记录***/
    public function withdraws(Request $request){
        $UserId =$request->session()->get('UserId');
        $pageSize = $request->get('pageSize',15);

        $list = DB::table("memberwithdrawal")
            ->select('amount','status','bankid','created_at')
            ->where("userid",$UserId)
            ->orderBy("id","desc")
            ->paginate($pageSize);
            foreach ($list as $item){
                $item->bankInfo = DB::table('memberbank')->select('bankname','bankrealname','bankcode','bankaddress','type')->where('id',$item->bankid)->first();
            }
        return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$list]);

    }

    //提现温馨提示
    public function withdra_reminder(){
        $data = DB::table('setings')->where(['keyname'=>'withdra_reminder'])->value('value');
        $min = DB::table('setings')->where(['keyname'=>'withdrawalmin'])->value('value');
        $withdra_fee = Db::table("setings")->where('keyname','withdra_fee')->value('value');
        $withdra_feerw = Db::table("setings")->where('keyname','withdra_feerw')->value('value');
        return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$data,'min'=>$min,'withdra_fee'=>intval($withdra_fee),'withdra_feerw'=>intval($withdra_feerw)]);
    }

    //股权列表页说ing
    public function equity_reminder(){
        $data = DB::table('setings')->where(['keyname'=>'equity_reminder'])->value('value');

        return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$data]);
    }

    //会员福利说明
    public function benefit_description(){
        $data = DB::table('setings')->where(['keyname'=>'benefit_description'])->value('value');
        return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$data]);
    }

    /***收益列表 tender***/
    //我购买的项目
    public function myProduct(Request $request){



        $pageSize = $request->get('pageSize',15);
        $category_id = isset($request->type)?$request->type:11;

        // $yesterday1 = date("Y-m-d 00:00:00",strtotime("-1 day"));
        // $yesterday2 = date("Y-m-d 23:59:59",strtotime("-1 day"));
        $yesterday1 = date("Y-m-d 00:00:00",time());
        $yesterday2 = date("Y-m-d 23:59:59",time());

        $UserId =$request->session()->get('UserId');

        $products=Product::where('category_id',$category_id)->get();

        $productData=[];
        foreach ($products as $product){
            $productData[$product->id]=$product;
        }

        $list = DB::table("productbuy")
                ->select('id','productid','amount','num','useritem_time','level','created_at','category_id','status','unit_price',\DB::raw('SUM(amount) as amounts'))
                ->where(['userid'=>$UserId,'category_id'=>$category_id])
                ->groupBy('productid')
                ->orderBy("created_at","desc")
                ->paginate($pageSize);

        // if($category_id != 13){
        //     $list = DB::table("productbuy")
        //         ->select('id','productid','amount','num','useritem_time','level','created_at','category_id','status','unit_price')
        //         ->where(['userid'=>$UserId,'category_id'=>$category_id])
        //         ->groupBy('productid')
        //         ->orderBy("created_at","desc")
        //         ->paginate($pageSize);
        // }else{
        //     $list = DB::table("productbuy")
        //         ->select('id','productid','amount','num','useritem_time','level','created_at','category_id','status','reason','unit_price','sum(num) as nums')
        //         ->where(['userid'=>$UserId,'category_id'=>$category_id])
        //         ->orderBy("created_at","desc")
        //         ->paginate($pageSize);
        // }
        $totalNum = 0;
        $second2 = time();
        foreach ($list as $item){
            // if(isset($productData[$item->productid])){
            $item->title=$productData[$item->productid]->title;
            $item->jyrsy=$productData[$item->productid]->jyrsy;
            if($category_id==11){
                // $item->zxjg = ($productData[$item->productid]->jyrsy * 0.01 * $productData[$item->productid]->qtje) + $productData[$item->productid]->qtje;
                 $item->zxjg =  $productData[$item->productid]->qtje;
            }
            $item->qtje=$productData[$item->productid]->qtje;//每股价格
            $item->shijian=$productData[$item->productid]->shijian;
            $item->qxdw=$productData[$item->productid]->qxdw=='个小时'?'时':'天';
            $item->pic=$productData[$item->productid]->pic;
            $item->market_value=$productData[$item->productid]->market_value;//市值
            $item->increase=$productData[$item->productid]->increase;//增幅比例
            $item->num=$productData[$item->productid]->num;//数量
            if($item->category_id==11){
                // $item->nums = DB::table('productbuy')->where(['userid'=>$UserId,'productid'=>$item->productid])->whereIn('status',[1,2])->sum("num");
                // $item->fxj = $productData[$item->productid]->fxj;
                $item->nums = DB::table('membercurrencys')->where(['userid'=>$UserId,'productid'=>$item->productid])->value("num");
                $item->nums = $item->nums?$item->nums:0;
                $item->sum_amount = sprintf("%.2f",$item->nums * $item->qtje);
            }else{
                $item->nums = DB::table('productbuy')->where(['userid'=>$UserId,'productid'=>$item->productid])->whereIn('status',[1,2])->sum("num");
            }
            $item->created_at = date('Y.m.d',strtotime($item->created_at));
            // else if($item->category_id==12){
            //     $item->nums = DB::table('productbuy')->where(['userid'=>$UserId,'productid'=>$item->productid])->whereIn('status',[1,2])->sum("num");
            // }
            // $item->type=$item->category_id==28?'静态日收益':'动态日收益';
            switch ($item->status) {
                case '0':
                    $item->status = '已结束';
                    break;
                case '1':
                    $item->status = '收益中';
                    break;
                case '2':
                    $item->status = '审核中';
                    break;
                case '3':
                    $item->status = '未通过';
                    break;
            }

            //剩余倒计时
            $second1 = strtotime($item->useritem_time);
            $hold_day = round(($second2 - $second1) / 86400);//购买到现在的天数
            //根据需求满足条件的重新计算90倒计时
            // $sj = $v->currlog_type == '0'?$this->Products[$v->productid]->shijian:180;
            // $diff_day = $sj - $hold_day;
            $diff_day = $this->Products[$v->productid]->shijian - $hold_day;
            $item->surplus_day = $diff_day > 0?$diff_day :1;

            //昨日收益
            // $yseterday_moneyCount = \App\Moneylog::where("moneylog_userid",$UserId)
            //     ->where("moneylog_type","项目分红")
            //     ->where("updated_at",'<=',$yesterday2)
            //     ->where("updated_at",'>=',$yesterday1)
            //     ->where("category_id",$category_id)
            //     ->where("product_id",$item->productid)
            //     ->sum("moneylog_money");
            // $item->moneyCount= sprintf("%.2f",round($yseterday_moneyCount,2));

            //总收益
//             $shouyis = \App\Moneylog::where("moneylog_userid",$UserId)
//                 ->where("moneylog_type","项目分红")
//                 ->where("created_at",'<=',$yesterday2)
// //                            ->where("created_at",'<=',$yesterday1)
//                 ->where("category_id",$category_id)
//                 ->where("product_id",$item->productid)
//                 ->sum("moneylog_money");
//             $item->shouyis= sprintf("%.2f",round($shouyis,2));
//             $totalNum += $item->nums * $item->qtje;//货币总价值

            // $item->rlj=sprintf("%.2f",$productData[$item->productid]->hbrsy * $item->amount * 0.01);
            // $item->rktx=sprintf("%.2f",$productData[$item->productid]->jyrsy * $item->amount * 0.01);
            // $item->rzsy=sprintf("%.2f",$item->rlj + $item->rktx);


        }

        //购买总金额
        // $totalAmount = \App\Productbuy::where(['userid'=>$UserId,'category_id'=>$category_id])->where('status',1)->sum("amount");

        //云货币购买总数量
        // $totalNum = DB::table('membercurrencys')->where(['userid'=>$UserId])->sum("num");
        // $totalNum = $category_id==12?DB::table('productbuy')->where(['userid'=>$UserId,'category_id'=>$category_id])->whereIn('status',[1,2])->sum("num"):sprintf("%.2f",$totalNum);


        //昨日收益
        // $yesterdayRevenue = \App\Moneylog::where("moneylog_userid",$UserId)
        //     ->where("moneylog_type","项目分红")
        //     ->where("updated_at",'<=',$yesterday2)
        //     ->where("updated_at",'>=',$yesterday1)
        //     ->where("category_id",$category_id)
        //     ->sum("moneylog_money");
        //累计预收益
        // $totalRevenue = DB::table('productbuy')->where(['userid'=>$UserId,'category_id'=>$category_id,'status'=>1])->sum('grand_total');
        //总收益
        // $totalRevenue = \App\Moneylog::where(['moneylog_userid'=>$UserId,'moneylog_type'=>'项目分红','category_id'=>$category_id])->sum("moneylog_money");
        // $totalRevenue = DB::table('moneylog')->where(['moneylog_userid'=>$UserId,'moneylog_type'=>'项目分红','category_id'=>$category_id])->sum("moneylog_money");
        //累计预收益
        // $advancetotalRevenue = DB::table('productbuy')->where(['userid'=>$UserId,'category_id'=>$category_id,'status'=>1])->sum('grand_total');
        // return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$list,"totalAmount"=>sprintf("%.2f",$totalAmount),"yesterdayRevenue"=>sprintf("%.2f",$yesterdayRevenue),"totalRevenue"=>sprintf("%.2f",$totalRevenue),"advancetotalRevenue"=>sprintf("%.2f",$advancetotalRevenue)]);

        //期权总资产要显示，购买的总金额加收益的
        // $totalAmount = $category_id==12?$totalAmount+$totalRevenue:$totalAmount;

        // return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$list,"totalAmount"=>sprintf("%.2f",$totalAmount),"yesterdayRevenue"=>sprintf("%.2f",$yesterdayRevenue),"totalRevenue"=>sprintf("%.2f",$totalRevenue),'totalNum'=>$totalNum,'yesterday1'=>$yesterday1,'yesterday2'=>$yesterday2]);
            return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$list]);

    }

    //项目详情
    public function myProduct_detail(Request $request){
        $pageSize = $request->get('pageSize',15);
        $UserId =$request->session()->get('UserId');
        if(!isset($request->id)){
            return response()->json(["status"=>0, "msg"=>"参数不能为空!"]);
        }
        $id = $request->id;
        $yesterday1 = date("Y-m-d 00:00:00",strtotime("-1 day"));
        $yesterday2 = date("Y-m-d 23:59:59",strtotime("-1 day"));

        $products_info = Product::where(['id'=>$id])->first();
        if(!$products_info){
            return response()->json(["status"=>0, "msg"=>"该项目不存在!"]);
        }
        $category_id = $products_info->category_id;
        // if($category_id != 13){
        //     $productbuy_info = DB::table("productbuy as p")
        //         ->join('products', 'products.id', '=', 'p.productid')
        //         ->select('p.id','p.productid','p.amount','p.num','p.useritem_time','p.level','p.created_at','p.category_id','p.status','p.reason','p.unit_price','products.title','p.currlog_id','p.currlog_type','p.order')
        //         ->where(['p.userid'=>$UserId,'p.productid'=>$id])
        //         ->orderBy("p.created_at","desc")
        //         ->paginate($pageSize);
        // }else{
        //     $productbuy_info = DB::table("productbuy as p")
        //         ->join('products pr', 'pr.id', '=', 'p.productid')

        //         ->select('p.id','p.productid','p.amount','p.num','p.useritem_time',
        //             'p.level','p.created_at','p.category_id','p.status','p.reason','p.unit_price','pr.title','p.order')
        //         ->where(['p.id'=>$id])
        //         ->first();
        // }
         $productbuy_info = DB::table("productbuy as p")
                ->join('products', 'products.id', '=', 'p.productid')
                ->select('p.id','p.productid','p.amount','p.num','p.useritem_time','p.level','p.created_at','p.category_id','p.status','p.reason','p.unit_price','products.title','p.currlog_id','p.currlog_type','p.order','products.shijian')
                ->where(['p.userid'=>$UserId,'p.productid'=>$id])
                ->orderBy("p.created_at","desc")
                ->paginate($pageSize);

        foreach ($productbuy_info as $item){
             switch ($item->status) {
                case '0':
                    $item->statusname = '已结束';
                    break;
                case '1':
                    $item->statusname = '收益中';
                    break;
                case '2':
                    $item->statusname = '审核中';
                    break;
                case '3':
                    $item->statusname = '未通过';
                    break;
            }
            $item->end_time = \App\Productbuy::DateAdd("d",$item->shijian, $item->useritem_time);
        }

        //总收益
        $totalRevenue = \App\Moneylog::where(['moneylog_userid'=>$UserId,'moneylog_type'=>'项目分红','category_id'=>$category_id])->sum("moneylog_money");
        //昨日收益
        $yesterdayRevenue = \App\Moneylog::where("moneylog_userid",$UserId)
            ->where("moneylog_type","项目分红")
            ->where("updated_at",'<=',$yesterday2)
            ->where("updated_at",'>=',$yesterday1)
            ->where("category_id",$category_id)
            ->sum("moneylog_money");
        //总数量
        $totalNum = DB::table("productbuy")->where(['userid'=>$UserId,'category_id'=>$category_id])->sum('num');

        $totalAmount = DB::table("productbuy")->where(['userid'=>$UserId,'productid'=>$id])->whereIn('status',[0,1])->sum("amount");

        return response()->json(["status"=>1, "msg"=>"返回成功",
            "data"=>$productbuy_info,
            "yesterdayRevenue"=>sprintf("%.2f",$yesterdayRevenue),
            "totalRevenue"=>sprintf("%.2f",$totalRevenue),
            'totalNum'=>$totalNum,
            'totalAmount'=> sprintf("%.2f",round($totalAmount,2)),

            ]);
    }

    /***收益列表 tender0628***/
    public function tender(Request $request){
        $pageSize = $request->get('pageSize',99);
        $category_id = isset($request->type)?$request->type:12;
        $pay_type = isset($request->pay_type)?$request->pay_type:2;


        $yesterday1 = date("Y-m-d 00:00:00",strtotime("-1 day"));
        $yesterday2 = date("Y-m-d 23:59:59",strtotime("-1 day"));

            $UserId =$request->session()->get('UserId');
            $member = Member::find($UserId);
            $rate = 0;
            if($member->level > 0){
                $level = Memberlevel::find($member->level);
                $rate =$level->rate;
            }
            // if($category_id==27){
            //     $products=Product::where('category_id',$category_id)->orwhere('category_id',28)->get();
            // }else{
                $products=Product::where('category_id',$category_id)->get();
            // }
            // $products=Product::get();//目前显示全部，前段默认传13
            $productData=[];
            foreach ($products as $product){
                $productData[$product->id]=$product;
            }


                $list = DB::table("productbuy")
                  //  ->select('id','productid','amount','useritem_time','created_at','category_id','status','reason','num','order','gq_order','pay_type','pay_status')
                    ->where("userid",$UserId)
                    ->where('category_id',$category_id)//
                    // ->where('status','!=','3')
                    ->orderBy("id","desc")
                    ->paginate($pageSize);
                $second2 = time();
                foreach ($list as $k=>$item){

                    if(isset($productData[$item->productid])){
                        $item->title=$productData[$item->productid]->title;
                        $item->jyrsy=$productData[$item->productid]->jyrsy;
                        $item->qxdw=$productData[$item->productid]->qxdw;
                        $item->rate=$rate;
                        $item->foldstate=1;

                        $item->qtje=$productData[$item->productid]->qtje;
                        $item->shijian=$productData[$item->productid]->shijian;
                        $item->fxj=$productData[$item->productid]->fxj;
                        $item->pic=$productData[$item->productid]->pic;
                        // $item->type=$item->category_id==28?'静态日收益':'动态日收益';
                        $time_arr= explode(" ",$item->created_at);
                        $item->created_at = $time_arr[0];
                        $item->zsje = $productData[$item->productid]->zsje;
                        $item->th_day = $productData[$item->productid]->th_day;
                        $item->nihua = $productData[$item->productid]->nihua;
                        switch ($item->status) {
                            case '0':
                                $item->statusname = '已结束';
                                break;
                            case '1':
                                $item->statusname = '收益中';
                                break;
                            case '2':
                                $item->statusname = '审核中';
                                break;
                            case '3':
                                $item->statusname = '未通过';
                                break;
                        }
                        $item->mrfh = sprintf("%.2f",$item->amount * $productData[$item->productid]->jyrsy * 0.01);//每日分红(元)
                        $item->equity_code = $productData[$item->productid]->equity_code;
                        //剩余倒计时
                        $second1 = strtotime($item->useritem_time);
                        $hold_day = round(($second2 - $second1) / 86400);//购买到现在的天数
                        $diff_day = $productData[$item->productid]->shijian - $hold_day;
                        $item->surplus_day = $diff_day > 0?$diff_day :1;
                        if($item->category_id ==42){
                            $item->finish_time = date('Y-m-d',strtotime(\App\Productbuy::DateAdd("d",$productData[$item->productid]->th_day, $item->created_at)));
                        }
                    }
                }
        $qishulist = DB::table("jijinqishu")->orderBy("id","asc")->get();
        return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$list,"qishulist"=>$qishulist]);
    }

    /***小树收益列表 tender0628***/
    public function treetender(Request $request){
        $pageSize = $request->get('pageSize',99);
        $category_id = isset($request->type)?$request->type:12;





        $UserId =$request->session()->get('UserId');
        $member = Member::find($UserId);
        $rate = 0;
        if($member->level > 0){
            $level = Memberlevel::find($member->level);
            $rate =$level->rate;
        }
        // if($category_id==27){
        //     $products=Product::where('category_id',$category_id)->orwhere('category_id',28)->get();
        // }else{
        $products=TreeProduct::where('category_id',$category_id)->get();
        // }
        // $products=Product::get();//目前显示全部，前段默认传13
        $productData=[];
        foreach ($products as $product){
            $productData[$product->id]=$product;
        }


        $list = DB::table("tree_productbuy")

            ->where("userid",$UserId)
            ->where('category_id',$category_id)//
            ->where('status','>','0')
            ->orderBy("id","desc")
            ->paginate($pageSize);

        foreach ($list as $k=>$item){

            if(isset($productData[$item->productid])){


                $item->title=$productData[$item->productid]->title;




                $item->pic=$productData[$item->productid]->pic;

                $time_arr= explode(" ",$item->created_at);
                $item->created_at = $time_arr[0];

                $item->content = $productData[$item->productid]->content;
                switch ($item->status) {
                    case '0':
                        $item->statusname = '已结束';
                        break;
                    case '1':
                        $item->statusname = '收益中';
                        break;
                    case '2':
                        $item->statusname = '审核中';
                        break;
                    case '3':
                        $item->statusname = '未通过';
                        break;
                }



            }

        }

        return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$list]);

    }
    /***协议***/
//     public function agreement(Request $request){

//             $UserId =$request->session()->get('UserId');

//             $ProBuy=  Productbuy::select('id','productid','useritem_time','amount')->where("id",$request->sgin)->where("userid",$UserId)->first();

//             if($ProBuy){
//                 $Pro=  Product::select('id','title','qxdw','shijian','jyrsy')->where("id",$ProBuy->productid)->first();
// //                dump($Pro);
//                 if(!$Pro){
//                     return response()->json(["status"=>0, "msg"=>"协议未找到"]);
//                 }
//                 $Mb=  Member::select('realname','mobile','bankrealname','bankcode','username')->where("id",$UserId)->first();
//                 $Mb->mobile =  \App\Member::DecryptPassWord($Mb->mobile);;
//                 $yslx = round($Pro->shijian*$ProBuy->amount*$Pro->jyrsy*0.01,2);//到期应收利息

//                 $Pro->yslx = $yslx;
//                 $Pro->qxdw = $Pro->qxdw=='个小时'?'时':'天';//投资期限
//                 $Pro->CompanyLong = Db::table('setings')->where('keyname','CompanyLong')->value('name');//公司长名字

//                 $d = $ProBuy->useritem_time;
//                 $Pro->fksj = date("Y-m-d",strtotime("$d+1day"))."-".date("Y-m-d",strtotime("$d+ {$Pro->shijian} day"));//返款时间
//                 $Pro->useritem_time = date('Y年m月d日',strtotime($ProBuy->useritem_time));

//                 $seal = Db::table('setings')->where('keyname','offiseal')->value('name');//印章
//                 $Pro->seal = "uploads/".$seal;
//                 $data['Mb'] = $Mb;
//                 $data['Pro'] = $Pro;
//                 $data['ProBuy'] = $ProBuy;
//                 return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$data]);
// //                return view($this->Template.".user.agreement",["Mb"=>$Mb,"Pro"=>$Pro,"ProBuy"=>$ProBuy]);
//             }else{
//                 return response()->json(["status"=>0, "msg"=>"协议未找到！"]);
//             }
//     }

    /***协议***/
    public function agreement(Request $request){

            $UserId =$request->session()->get('UserId');

            $ProBuy=  Productbuy::select('id','productid','useritem_time','num','amount','category_id','created_at')->where("id",$request->sgin)->where("userid",$UserId)->first();

            if($ProBuy){
                $num = 100000 + $ProBuy->id;
                $ProBuy->number = 'YM'.$num;
                $Pro=  Product::select('id','title','qxdw','shijian','jyrsy','qtje')->where("id",$ProBuy->productid)->first();
//                dump($Pro);
                if(!$Pro){
                    return response()->json(["status"=>0, "msg"=>"协议未找到"]);
                }
                if($ProBuy->category_id==12){
                    $share_amount = DB::table("productbuy")->where(['userid'=>$UserId,'productid'=>$ProBuy->productid,'status'=>1])->sum('amount');//理财
                    $ProBuy->share = sprintf("%.2f",$share_amount/$Pro->qtje);//总股权
                }
                $Mb=  Member::select('realname','mobile','bankrealname','bankcode','username','card')->where("id",$UserId)->first();
                $Mb->mobile =  $Mb->username;
                $yslx = round($Pro->shijian*$ProBuy->amount*$Pro->jyrsy*0.01,2);//到期应收利息

                $Pro->yslx = $yslx;
                $Pro->qxdw = $Pro->qxdw=='个小时'?'时':'天';//投资期限
                $Pro->CompanyLong = Db::table('setings')->where('keyname','CompanyLong')->value('value');//公司长名字

                $d = $ProBuy->useritem_time;
                $Pro->fksj = date("Y-m-d",strtotime("$d+1day"))."-".date("Y-m-d",strtotime("$d+ {$Pro->shijian} day"));//返款时间
                $Pro->useritem_time = date('Y年m月d日',strtotime($ProBuy->useritem_time));
                $ProBuy->buy_time = date('Y.m.d',strtotime($ProBuy->useritem_time));
                $ProBuy->Y = date('Y',strtotime($ProBuy->created_at));
                $ProBuy->M = date('m',strtotime($ProBuy->created_at));
                $ProBuy->D = date('d',strtotime($ProBuy->created_at));

                $seal = Db::table('setings')->where('keyname','offiseal')->value('value');//印章
                $Pro->seal = "uploads/".$seal;

                $ProBuy->corporate_name = Db::table('setings')->where('keyname','corporate_name')->value('value');//单位名称
                $ProBuy->organization_code = Db::table('setings')->where('keyname','organization_code')->value('value');//组织机构代码证号
                $ProBuy->subject_matter = Db::table('setings')->where('keyname','subject_matter')->value('value');//事由

                $AppDownloadUrl = DB::table('setings')->where('keyname','AppDownloadUrl')->value('value');
                $data['Mb'] = $Mb;
                $data['Pro'] = $Pro;
                $data['ProBuy'] = $ProBuy;
                $data['AppDownloadUrl'] = $AppDownloadUrl;
                return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$data]);
//                return view($this->Template.".user.agreement",["Mb"=>$Mb,"Pro"=>$Pro,"ProBuy"=>$ProBuy]);
            }else{
                return response()->json(["status"=>0, "msg"=>"协议未找到！"]);
            }
    }

    /***合同***/
    public function contract(Request $request){

            $UserId =$request->session()->get('UserId');
            if(empty($request->buy_id)){
                 return response()->json(["status"=>0, "msg"=>"参数错误"]);
            }

            $ProBuy=  Productbuy::select('id','productid','useritem_time','sendday_count','amount','order')->where(['category_id'=>12,'userid'=>$UserId,'id'=>$request->buy_id])->first();

            if($ProBuy){
                $Pro =  Product::select('id','title','qxdw','shijian','jyrsy')->where("id",$ProBuy->productid)->first();
                if(!$Pro){
                    return response()->json(["status"=>0, "msg"=>"合同未找到"]);
                }
                $Mb =  Member::select('id','realname','card')->where("id",$UserId)->first();//姓名 身份证

                $yslx = round($Pro->shijian*$ProBuy->amount*$Pro->jyrsy*0.01,2);//到期应收利息

                $htbh = $Mb->id.substr($ProBuy->order,-8);;//合同编号
                $syq = $Pro->shijian.($Pro->qxdw=='个小时'?'时':'天');//投资期限
                $CompanyLong = Db::table('setings')->where('keyname','CompanyLong')->value('value');//合同长名字

                $d = $ProBuy->useritem_time;
                // $Pro->fksj = date("Y-m-d",strtotime("$d+1day"))."-".date("Y-m-d",strtotime("$d+ {$Pro->shijian} day"));//返款时间
                $fksj = date("Y-m-d",strtotime("$d+ {$Pro->shijian} day"));//返款时间
                $useritem_time = date('Y-m-d',strtotime($ProBuy->useritem_time));//起始日期

                $seal = Db::table('setings')->where('keyname','offiseal')->value('name');//印章
                $Pro->seal = "uploads/".$seal;
                $data = [
                    'realname'  => $Mb->realname,
                    'card'      => $Mb->card,
                    'yslx'      => $yslx,
                    'htbh'      => $htbh,
                    'syq'       => $syq,
                    'com'       => $CompanyLong,
                    'retrn_at'  => $fksj,
                    'start_at'  => $useritem_time,
                    'pro_title' => $Pro->title,
                    'jyrsy'     => $Pro->jyrsy,
                    'amount'    => $ProBuy->amount,
                    'time'      => date('Y年m月d日',strtotime($ProBuy->useritem_time))
                ];
                return response()->json(["status"=>1, "msg"=>"返回成功","data"=>$data]);
            }else{
                return response()->json(["status"=>0, "msg"=>"合同未找到！"]);
            }
    }


    /***会员中心***/
    // public function index(Request $request){


    //     $UserId =$request->session()->get('UserId');

    //        return view($this->Template.".user.index");


    // }

    /***收益列表 shouyi***/
    // public function shouyi(Request $request){


    //     if($request->ajax()){
    //         $UserId =$request->session()->get('UserId');
    //         $pagesize=15;

    //         if($request->id=='all'){
    //             //$pagesize=Cache::get("pcpagesize");
    //             $where=[];

    //             $list = DB::table("moneylog")
    //                 ->where("moneylog_userid",$UserId)
    //                 ->orderBy("id","desc")
    //                 ->paginate($pagesize);
    //             foreach ($list as $item){
    //                 $item->date=date("m-d H:i",strtotime($item->created_at));
    //             }

    //             return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
    //         }

    //         $type=[
    //             "1"=>"项目分红",
    //             "2"=>"加入项目",
    //             "3"=>"项目本金返款",
    //         ];

    //         $id=1;
    //         if($request->id>0){
    //             $id=$request->id;
    //         }

    //             //$pagesize=6;
    //            // $pagesize=Cache::get("pcpagesize");
    //             $where=[];

    //             $list = DB::table("moneylog")
    //                 ->where("moneylog_userid",$UserId)
    //                 ->where("moneylog_type",$type[$id])
    //                 ->orderBy("id","desc")
    //                 ->paginate($pagesize);
    //             foreach ($list as $item){
    //                 $item->date=date("m-d H:i",strtotime($item->created_at));
    //             }

    //         return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
    //     }else {

    //         return view($this->Template.".user.shouyi",["id"=>$request->id]);
    //     }

    // }






    /***资金统计***/
    // public function moneylog(Request $request){

    //             $UserId =$request->session()->get('UserId');

    //            return view($this->Template.".user.moneylog",[]);

    // }





    /***充值***/
    public function payconfig(Request $request){

                $UserId =$request->session()->get('UserId');

                $Payment=  Payment::find($request->payid);
                $Paynumber= $request->amount;

              if($Payment){
                  $html=' <div class="formGroup"><span class="left">帐户可用余额</span> <span class="right">¥<font style="color:#F00; font-size:22px;font-weight:700">'.sprintf("%.2f",$this->Member->amount).' </font>元</span></div>
                   <div class="formGroup"><span class="left">充值金额</span> <input class="right" type="number" name="amount" id="price" value="'.$Paynumber.'" maxlength="8" placeholder="输入充值金额"></div>
                  <div class="formGroup"><span class="left" style="margin:10px;line-height:30px;height:30px;padding:2px;margin-left:0;">充值方式</span>
                   <div class="tabbox"><ul>';

                        foreach($this->payment as $pk=>$Pay){
                            $selected='';
                            if($pk==$Payment->id){
                                $selected='class="active"';
                            }
                            $html.=' <li data="'.$pk.'" '.$selected.' >'.$Pay.'</li>';
                        }
                $html.='</ul>
        </div><script type="text/javascript">
        function paycfg(){
        $(".tabbox li").click(function ()
　　{
　　	payconfig($(this).attr("data"));
　　　　//获取点击的元素给其添加样式，讲其兄弟元素的样式移除
　　　　$(this).addClass("active").siblings().removeClass("active");
　　　　//获取选中元素的下标
　　　　var index = $(this).index();
　　　　$(this).parent().siblings().children().eq(index).addClass("active")
　　　　.siblings().removeClass("active");
　　});
        }
        function isenpay(){
        if($("#price").val()>=2000){$(".tabbox li:last-child").siblings().unbind("click");$(".tabbox li:last-child").siblings().addClass("tabdisabled");}
        }
$(function ()
{
	paycfg();
	isenpay();
$("#price").bind("input propertychange",function(event){
       console.log($("#price").val());
       if($("#price").val()>=2000){$(".tabbox li:last-child").click()}else{if($(".tabbox li:first-child").hasClass("tabdisabled")){$(".tabbox li").removeClass("tabdisabled");paycfg();}};
       //isenpay();
});
	   });
</script></div>';

        $html.='<div id="pay_desc_29" class="pay_descs" style="padding: 0px 10px; display: block;">
            <table align="center" cellspacing="0" class="table_form" style="font-size:14px;border-top:1px solid #eee;border-left:1px solid #eee;border-right:1px solid #eee;" width="100%">
                <tbody><tr height="60">
                        <td class="tb_class1" align="right" width="100"></td>
                        <td class="tb_class1" ></td>
                    </tr>';

                  if($Payment->pay_bank!=''){
                      $html.=' <tr height="60">
                        <td class="tb_class1" align="right">收款账号：</td>
                        <td class="tb_class1" >'.$Payment->pay_bank.'</td>
                    </tr>';
                  }

                  if($Payment->pay_pic!=''){
                      $html.=' <tr height="60">
                        <td class="tb_class1" align="right">扫码支付：</td>
                        <td class="tb_class1" ><img src="'.$Payment->pay_pic.'" width="100%"/></td>
                    </tr>';
                  }

                  if($Payment->pay_desc!='') {
                      $html .= '  <tr height="60">
                        <td class="tb_class1" align="right">温馨提示：</td>
                        <td class="tb_class2" >' . $Payment->pay_desc . '</td>
                    </tr>';
                  }


                $html .= '</tbody>
            </table>
            <br>
            <strong>尊敬的'.Cache::get('CompanyShort').'会员：转账成功后请及时联系在线客服提供转账成功的凭证以及需要充值的用户名，方便财务为您及时处理充值。<br>
                （注:用户名为APP的登录账号）</strong><br>
            <br>	<label for="pay_checkbox_29"><input type="checkbox" name="paycheck" value="1">我已完成存款，确认并提交订单</label>
        </div>';


        $html.='<input type="hidden" name="paymentid" value="' . $Payment->id . '">
                <input type="hidden" name="_token" value="' . csrf_token() . '">

        <input type="button" name="dosubmit" id="dosubmit" class="finishReg" onclick="SubForm();" value="确认并提交汇款订单">';

                  return ["status"=>0,"html"=>$html];
              }
    }






    /***我的下线记录***/
//     public function offline(Request $request){

//         $UserId =$request->session()->get('UserId');

//         if($request->ajax()){

//             $pagesize=6;
//             $pagesize=Cache::get("pcpagesize");
//             $where=[];

//             $list = DB::table("membercashback")
//                 ->where("userid",$UserId)
//                 ->orderBy("id","desc")
//                 ->paginate($pagesize);
//             foreach ($list as $item){
//                 $item->date=date("m-d H:i",strtotime($item->created_at));
//             }

//             return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
//         }else {

//             /**抽成金额**/
//             $chenggong= DB::table("membercashback")
//                 ->where("userid",$UserId)
//                 ->where("status","1")
//                 ->sum('preamount');

//             return view($this->Template.".user.offline",[
// "chenggong"=>$chenggong
//             ]);
//         }
//     }


    /***我的下线收支***/
    // public function budget(Request $request){

    //     $UserId =$request->session()->get('UserId');

    //     if($request->ajax()){

    //         $datauserids=  \App\Member::treeuid($this->Member->invicode);
    //         $datalvs=  \App\Member::treelv($this->Member->invicode,1);

    //         $pagesize=6;
    //         $pagesize=Cache::get("pcpagesize");
    //         $where=[];

    //         $list = DB::table("member")
    //             ->whereIn("id",$datauserids)
    //             ->orderBy("id","desc")
    //             ->paginate($pagesize);
    //         foreach ($list as $item){
    //             $item->date=date("m-d H:i",strtotime($item->created_at));
    //             $item->cenji=$datalvs[$item->id];

    //             $item->recharge= DB::table("memberrecharge")
    //                 ->where("userid",$item->id)
    //                 ->whereNotIn("type",['优惠活动','优惠充值'])
    //                 ->where("status","1")
    //                 ->sum('amount');

    //             $item->withdrawal= DB::table("memberwithdrawal")
    //                 ->where("userid",$item->id)
    //                 ->where("status","1")
    //                 ->sum('amount');
    //         }

    //         return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
    //     }else {

    //         $datauserids=  \App\Member::treeuid($this->Member->invicode);

    //         $recharge= DB::table("memberrecharge")
    //             ->whereIn("userid",$datauserids)
    //             ->where("status","1")
    //             ->sum('amount');

    //         $withdrawal= DB::table("memberwithdrawal")
    //             ->whereIn("userid",$datauserids)
    //             ->where("status","1")
    //             ->sum('amount');

    //         return view($this->Template.".user.budget",[
    //             "recharge"=>$recharge,
    //             "withdrawal"=>$withdrawal
    //         ]);
    //     }
    // }


    /***我的推广记录***/
    public function record(Request $request){

        $UserId =$request->session()->get('UserId');

        if($request->ajax()){

            $datauserids=  \App\Member::treeuid($this->Member->invicode);
            $datalvs=  \App\Member::treelv($this->Member->invicode,1);

            $pagesize=6;
            $pagesize=Cache::get("pcpagesize");
            $where=[];

            $list = DB::table("member")
                ->whereIn("id",$datauserids)
                ->orderBy("id","desc")
                ->paginate($pagesize);
            foreach ($list as $item){
                $item->date=date("m-d H:i",strtotime($item->created_at));
                $item->cenji=$datalvs[$item->id];
            }

            return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
        }else {

            return view($this->Template.".user.record",[

            ]);
        }
    }


    /***我的推广链接 ***/
    public function mylink(Request $request){

        $UserId =$request->session()->get('UserId');

        $datauserids=  \App\Member::treeuid($this->Member->invicode);

        $recharge= DB::table("memberrecharge")
            ->whereIn("userid",$datauserids)
            ->where("status","1")
            ->sum('amount');

        $withdrawal= DB::table("memberwithdrawal")
            ->whereIn("userid",$datauserids)
            ->where("status","1")
            ->sum('amount');

            return view($this->Template.".user.mylink",[
                "recharge"=>sprintf("%.2f",$recharge),
                "withdrawal"=>sprintf("%.2f",$withdrawal)
            ]);

    }


}
?>
