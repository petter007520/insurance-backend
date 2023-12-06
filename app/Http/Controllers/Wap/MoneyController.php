<?php

namespace App\Http\Controllers\Wap;
use App\Auth;
use App\Category;
use App\Channel;
use App\Http\Controllers\Controller;
use App\Member;
use App\Memberlevel;
use App\Membermsg;
use App\Memberticheng;
use App\Memberwithdrawal;
use App\Order;
use App\Payment;
use App\Product;
use App\Productbuy;
use Carbon\Carbon;
use App\Moneylog;
use DB;
use App\Admin;
use App\Ad;
use App\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Session;

class MoneyController extends Controller
{
    public $cachetime=600;
    public $Template='wap';
    public function __construct(Request $request)
    {

        $this->Template=env("WapTemplate");
        $this->middleware(function ($request, $next) {
            //dd($request->session()->all());

            $UserId =$request->session()->get('UserId');

            if($UserId<1){
                return redirect()->route("wap.login");
            }

           $this->Member= Member::find($UserId);


            view()->share("Member",$this->Member);

            return $next($request);
        });


        /**网站缓存功能生成**/

        if(!Cache::has('setings')){
            $setings=DB::table("setings")->get();

            if($setings){
                $seting_cachetime=DB::table("setings")->where("keyname","=","cachetime")->first();

                if($seting_cachetime){
                    $this->cachetime=$seting_cachetime->value;
                    Cache::forever($seting_cachetime->keyname, $seting_cachetime->value);
                }

                foreach($setings as $sv){
                    Cache::forever($sv->keyname, $sv->value);
                }
                Cache::forever("setings", $setings);
            }

        }

        $this->cachetime=Cache::get('cachetime');

        /**菜单导航栏**/
        if(Cache::has('wap.category')){
            $footcategory=Cache::get('wap.category');
        }else{
            $footcategory= DB::table('category')->where("atfoot","1")->orderBy("sort","desc")->limit(5)->get();
            Cache::put('wap.category',$footcategory,$this->cachetime);
        }
        view()->share("footcategory",$footcategory);
        /**菜单导航栏 END **/


        if(Cache::has('memberlevel.list')){
            $memberlevel=Cache::get('memberlevel.list');
        }else{
            $memberlevel= DB::table("memberlevel")->orderBy("id","asc")->get();
            Cache::get('memberlevel.list',$memberlevel,Cache::get("cachetime"));
        }

        $memberlevelName=[];
        foreach($memberlevel as $item){
            $memberlevelName[$item->id]=$item->name;
        }

        $this->memberlevelName=$memberlevelName;

        view()->share("memberlevel",$memberlevel);
        view()->share("memberlevelName",$memberlevelName);

        $Memberlevels= Memberlevel::get();

        foreach ($Memberlevels as $Memberlevel){
            $this->Memberlevels[$Memberlevel->id]=$Memberlevel;
        }


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

    /***会员中心***/
    public function index(Request $request){


        $UserId =$request->session()->get('UserId');

           return view($this->Template.".user.index");


    }




    /***收益列表 shouyi***/
    public function shouyi(Request $request){


        if($request->ajax()){
            $UserId =$request->session()->get('UserId');
            $pagesize=15;

            if($request->id=='all'){
                //$pagesize=Cache::get("pcpagesize");
                $where=[];

                $list = DB::table("moneylog")
                    ->where("moneylog_userid",$UserId)
                    ->orderBy("id","desc")
                    ->paginate($pagesize);
                foreach ($list as $item){
                    $item->date=date("m-d H:i",strtotime($item->created_at));
                }

                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }

            $type=[
                "1"=>"项目分红",
                "2"=>"加入项目",
                "3"=>"项目本金返款",
            ];

            $id=1;
            if($request->id>0){
                $id=$request->id;
            }

                //$pagesize=6;
                // $pagesize=Cache::get("pcpagesize");
                $where=[];

                $list = DB::table("moneylog")
                    ->where("moneylog_userid",$UserId)
                    ->where("moneylog_type",$type[$id])
                    ->orderBy("id","desc")
                    ->paginate($pagesize);
                foreach ($list as $item){
                    $item->date=date("m-d H:i",strtotime($item->created_at));
                }

            return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
        }else {

            return view($this->Template.".user.shouyi",["id"=>$request->id]);
        }

    }


    /***收益列表 tender***/
    public function tender(Request $request){



        if($request->ajax()){
            $UserId =$request->session()->get('UserId');
            $products=Product::get();
            $productData=[];
            foreach ($products as $product){
                $productData[$product->id]=$product;
            }


                $pagesize=6;
                $pagesize=Cache::get("pcpagesize");
                $where=[];
                if($request->status=='1'){
                    $where=["status"=>"0"];
                }else{
                    $where=["status"=>"1"];
                }

                $list = DB::table("productbuy")
                    ->where("userid",$UserId)
                    ->where($where)
                    ->orderBy("id","desc")
                    ->paginate($pagesize);
                foreach ($list as $item){
                    if(isset($productData[$item->productid])){
                        $item->title=$productData[$item->productid]->title;
                        $item->jyrsy=$productData[$item->productid]->jyrsy;
                        $item->shijian=$productData[$item->productid]->shijian;
                        $item->qxdw=$productData[$item->productid]->qxdw;
                        //$item->sendday_count=$productData[$item->productid]->sendday_count;
                        $item->rate=isset($this->Memberlevels[$item->level])?$this->Memberlevels[$item->level]->rate:0;

                        if($productData[$item->productid]->hkfs == 0){
                            $moneyCount = $productData[$item->productid]->jyrsy * $item->amount/100;
                            $item->moneyCount= round($moneyCount,2);
                        }else{
                            $moneyCount = $productData[$item->productid]->jyrsy * $item->amount/100*$productData[$item->productid]->shijian;
                            $item->moneyCount= round($moneyCount,2);
                        }


                        if($productData[$item->productid]->hkfs == 0){
                            $elseMoney = $item->rate * $item->amount/100;
                            $item->elseMoney= round($elseMoney,2);
                        }else{
                            $elseMoney = $item->rate * $item->amount/100*$productData[$item->productid]->shijian;
                            $item->elseMoney= round($elseMoney,2);
                        }


                        $item->shouyis=round($item->moneyCount+$item->elseMoney,2);

                    }
                    $item->date=date("m-d H:i",strtotime($item->created_at));
                    $item->url=\route("user.agreement",["sgin"=>$item->id]);
                }

            return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize,"where"=>$where];
        }else {

            return view($this->Template.".user.tender");
        }

    }




    /***资金统计***/
    public function moneylog(Request $request){

                $UserId =$request->session()->get('UserId');

               return view($this->Template.".user.moneylog",[]);

    }



    /***充值***/
    public function recharge(Request $request){

                $UserId =$request->session()->get('UserId');

                if($request->ajax()){

                    $amount= intval($request->amount);

                    if($request->paycheck<1){
                        return response()->json([
                            "msg"=>"请选择'我已完成存款，确认并提交订单'","status"=>1
                        ]);
                    }

                    if($amount<1){
                        return response()->json([
                            "msg"=>"充值金额错误","status"=>1
                        ]);
                    }

                    if(!isset($this->payment[$request->paymentid])){
                        return response()->json([
                            "msg"=>"充值方式错误","status"=>1
                        ]);
                    }


                    $memo= $request->memo!=''?$request->memo:$this->payment[$request->paymentid].'充值'.$request->amount;



                    \App\Memberrecharge::Recharge([
                        "userid"=>$UserId, //会员ID
                        "amount"=>$request->amount,//金额
                        "memo"=>$memo,//备注
                        "paymentid"=>$request->paymentid,//充值方式 1支付宝,2微信,3银行卡
                        "ip"=>$request->getClientIp(),//IP
                        "type"=>"用户充值",//类型 Cache(RechargeType):系统充值|优惠活动|优惠充值|后台充值|用户充值

                    ]);

                    $msg=[
                        "userid"=>$UserId,
                        "username"=>$this->Member->username,
                        "title"=>"充值订单",
                        "content"=>"您的充值申请提交成功(".$request->amount.")",
                        "from_name"=>"系统通知",
                        "types"=>"充值",
                    ];
                    \App\Membermsg::Send($msg);

                    if($request->ajax()){
                        return response()->json([
                            "msg"=>"充值成功","status"=>0
                        ]);
                    }


                }else{

                    $Payments=  Payment::where("enabled","1")->get();

                    return view($this->Template.".user.recharge",["Payments"=>$Payments]);
                }



    }

    /***提现***/
    public function withdraw(Request $request){

                $UserId =$request->session()->get('UserId');

                if($request->ajax()){




                    $txtime=Cache::get("tixiantime");
                    $timerarr= explode("-",$txtime);
                    if(isset($timerarr) && count($timerarr)==2) {
                        $Nowtime=Carbon::now()->getTimestamp();
                        $Stime=date("Y-m-d H:i", strtotime($timerarr[0]));
                        $Etime=date("Y-m-d H:i", strtotime($timerarr[1]));


                        if($Nowtime< strtotime($Stime) || $Nowtime>strtotime($Etime)) {
                            return response()->json([
                                "msg" => "提款时间为" . date("H:i", strtotime($timerarr[0])) . "-" . date("H:i", strtotime($timerarr[1])), "status" => 1,
                                "data" => $timerarr
                            ]);
                        }
                    }



                    $Daywithdrawals=Cache::get("Daywithdrawals");


                    $TodayWithdrawals = Memberwithdrawal::where("userid",$UserId)
                        ->whereIn("status",[0,1])
                        ->whereDate("created_at",Carbon::now()->format("Y-m-d"))
                        ->count();


                    if($Daywithdrawals<=$TodayWithdrawals){
                        return response()->json([
                            "msg"=>"您的今日提款次数已收完,每日提现次数:".Cache::get("Daywithdrawals")."次","status"=>1
                        ]);
                    }


                    $amount= intval($request->amount);

                    if($amount<Cache::get("withdrawalmin")){
                        return response()->json([
                            "msg"=>"最低提款金额为".Cache::get("withdrawalmin"),"status"=>1
                        ]);
                    }



                  if($this->Member->isbank==0){
                      return response()->json([
                          "msg"=>"您还未绑定银行账号","status"=>1,"url"=>\route("user.bank")
                      ]);
                  }

                  if(\App\Member::DecryptPassWord($this->Member->paypwd)!=$request->paypwd){
                      return response()->json([
                          "msg"=>"交易密码错误","status"=>1
                      ]);
                  }




                    $yanzheng= \App\Memberwithdrawal::WithdrawalAmount($UserId,$amount);

                    if(isset($yanzheng)) {

                        if($yanzheng['status']==1){
                            return response()->json($yanzheng);
                        }

                        $data = \App\Memberwithdrawal::AddWithdrawal($UserId, $amount);


                        if ($request->ajax()) {
                            return response()->json($data);
                        }

                    }else{

                        return response()->json([
                            "msg"=>"系统错误","status"=>1
                        ]);
                    }


                 /*  $data= \App\Memberwithdrawal::AddWithdrawal($UserId,$amount);



                    if($request->ajax()){
                        return response()->json($data);
                    }*/


                }else{

                                        $WithdrawalAmounts = Memberwithdrawal::where("userid",$this->Member->id)->whereIn("status",[0,1])->sum("amount");

                    $TixianAmounts=  $WithdrawalAmounts;

                    $Buymoneys = Moneylog::where("moneylog_userid",$this->Member->id)
                        ->where("moneylog_status","+")
                        ->whereIn("moneylog_type",["每日签到","项目分红","项目本金返款","下线项目分红","下线购买分成"])
                        ->sum("moneylog_money");

                    $Regmoneys = Moneylog::where("moneylog_userid",$this->Member->id)
                        ->where("moneylog_status","+")
                        ->where("moneylog_type","充值")
                        ->where("moneylog_notice","新手礼包(+)")
                        ->sum("moneylog_money");

                    $UserMoneys=  $Buymoneys+ $Regmoneys-$TixianAmounts;

                    if($this->Member->amount<$UserMoneys){
                        $UserMoneys=$this->Member->amount;
                    }

                    return view($this->Template.".user.withdraw",["UserMoneys"=>sprintf("%.2f",$UserMoneys)]);
                }



    }

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


    /***协议***/
    public function agreement(Request $request){

            $UserId =$request->session()->get('UserId');

            $ProBuy=  Productbuy::where("id",$request->sgin)->where("userid",$UserId)->first();


            if($ProBuy){
                $Pro=  Product::where("id",$ProBuy->productid)->first();
                if(!$Pro){
                    return view("hui.error",["icon"=>"layui-icon-404","msg"=>"协议未找到"]);
                }
                $Mb=  Member::where("id",$UserId)->first();

                return view($this->Template.".user.agreement",["Mb"=>$Mb,"Pro"=>$Pro,"ProBuy"=>$ProBuy]);
            }else{
                return view("hui.error",["icon"=>"layui-icon-404","msg"=>"协议未找到"]);
            }
    }


    /***我的充值记录***/
    public function recharges(Request $request){
        $UserId =$request->session()->get('UserId');

        if($request->ajax()){


            $pagesize=6;
            $pagesize=Cache::get("pcpagesize");
            $where=[];

            $list = DB::table("memberrecharge")
                ->where("userid",$UserId)
                ->orderBy("id","desc")
                ->paginate($pagesize);
            foreach ($list as $item){
                $item->date=date("m-d H:i",strtotime($item->created_at));
            }

            return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
        }else {

            /**充值成功**/
           $chenggong= DB::table("memberrecharge")
                ->where("userid",$UserId)
                ->where("status","1")
                ->sum('amount');

            /**充值等待**/
          $dendai=  DB::table("memberrecharge")
                ->where("userid",$UserId)
                ->where("status","0")
                ->sum('amount');

            /**充值失败**/
         $shibai=   DB::table("memberrecharge")
                ->where("userid",$UserId)
                ->where("status","-1")
                ->sum('amount');
            return view($this->Template.".user.recharges",[
                "chenggong"=>$chenggong,
                "dendai"=>$dendai,
                "shibai"=>$shibai,
            ]);
        }

    }

    /***我的提款记录***/
    public function withdraws(Request $request){
        $UserId =$request->session()->get('UserId');

        if($request->ajax()){


            $pagesize=6;
            $pagesize=Cache::get("pcpagesize");
            $where=[];

            $list = DB::table("memberwithdrawal")
                ->where("userid",$UserId)
                ->orderBy("id","desc")
                ->paginate($pagesize);
            foreach ($list as $item){
                $item->date=date("m-d H:i",strtotime($item->created_at));
            }

            return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
        }else {

            /**充值成功**/
           $chenggong= DB::table("memberwithdrawal")
                ->where("userid",$UserId)
                ->where("status","1")
                ->sum('amount');

            /**充值等待**/
          $dendai=  DB::table("memberwithdrawal")
                ->where("userid",$UserId)
                ->where("status","0")
                ->sum('amount');

            /**充值失败**/
         $shibai=   DB::table("memberwithdrawal")
                ->where("userid",$UserId)
                ->where("status","-1")
                ->sum('amount');
            return view($this->Template.".user.withdraws",[
                "chenggong"=>$chenggong,
                "dendai"=>$dendai,
                "shibai"=>$shibai,
            ]);
        }
    }

    /***我的下线记录***/
    public function offline(Request $request){

        $UserId =$request->session()->get('UserId');

        if($request->ajax()){

            $pagesize=6;
            $pagesize=Cache::get("pcpagesize");
            $where=[];

            $list = DB::table("membercashback")
                ->where("userid",$UserId)
                ->orderBy("id","desc")
                ->paginate($pagesize);
            foreach ($list as $item){
                $item->date=date("m-d H:i",strtotime($item->created_at));
            }

            return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
        }else {

            /**抽成金额**/
            $chenggong= DB::table("membercashback")
                ->where("userid",$UserId)
                ->where("status","1")
                ->sum('preamount');

            return view($this->Template.".user.offline",[
"chenggong"=>$chenggong
            ]);
        }
    }


    /***我的下线收支***/
    public function budget(Request $request){

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

                $item->recharge= DB::table("memberrecharge")
                    ->where("userid",$item->id)
                    ->whereNotIn("type",['优惠活动','优惠充值'])
                    ->where("status","1")
                    ->sum('amount');

                $item->withdrawal= DB::table("memberwithdrawal")
                    ->where("userid",$item->id)
                    ->where("status","1")
                    ->sum('amount');
            }

            return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
        }else {

            $datauserids=  \App\Member::treeuid($this->Member->invicode);

            $recharge= DB::table("memberrecharge")
                ->whereIn("userid",$datauserids)
                ->where("status","1")
                ->sum('amount');

            $withdrawal= DB::table("memberwithdrawal")
                ->whereIn("userid",$datauserids)
                ->where("status","1")
                ->sum('amount');

            return view($this->Template.".user.budget",[
                "recharge"=>$recharge,
                "withdrawal"=>$withdrawal
            ]);
        }
    }


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
