<?php

namespace App\Http\Controllers\Admin;
use App\Auth;
use App\Channel;
use App\Member;
use App\Order;
use Carbon\Carbon;
use DB;
use App\Admin;
use App\Club;
use App\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Session;

class StatisticsController extends BaseController
{

    private $table="member";
    private $Models;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $Models= new Member();

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

        view()->share("payment",$this->payment);

    }


public function index(Request $request){

    return redirect(route($this->RouteController.'.lists'));

}

    public function lists(Request $request){



        if($request->ajax()){


            $pagesize=10;//默认分页数
            if(Cache::has('pagesize')){
                $pagesize=Cache::get('pagesize');
            }


            $list = DB::table($this->table)
                ->where(function ($query) {
                    $s_key_username=[];
                    $s_key_invicode=[];
                    $s_key_realname=[];

                    if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                        $s_key_username[]=[$this->table.".username","like","%".$_REQUEST['s_key']."%"];
                        $s_key_invicode[]=[$this->table.".invicode","like","%".$_REQUEST['s_key']."%"];
                        $s_key_realname[]=[$this->table.".realname","like","%".$_REQUEST['s_key']."%"];


                    }

                    $query->orwhere($s_key_invicode)
                        ->orwhere($s_key_realname)
                        ->orwhere($s_key_username);
                })

                ->where(function ($query) {

                    if(isset($_REQUEST['s_mtype']) && $_REQUEST['s_mtype']!=''){
                        $query->where("mtype","=",$_REQUEST['s_mtype']);
                    }


                })

                ->where(function ($query) {

                    if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']!=''){
                        $query->where("level","=",$_REQUEST['s_categoryid']);
                    }


                })
                ->where(function ($query) {
                    $date_s=[];
                    if(isset($_REQUEST['date_s']) && $_REQUEST['date_s']!=''){

                        $query->whereDate("created_at",">=",$_REQUEST['date_s']." 00:00:00");


                    }


                })

                ->where(function ($query) {
                    $date_s=[];
                    if(isset($_REQUEST['date_e']) && $_REQUEST['date_e']!=''){

                        $query->whereDate("created_at","<=",$_REQUEST['date_e']." 23:59:59");


                    }


                })
                ->orderBy("id","desc")

                ->paginate($pagesize);

            if($list){

                foreach ($list as $item){

                    $item->tuiguangren=DB::table("member")->where("inviter",$item->invicode)->pluck("id");

                    $item->tuiguangrens=count($item->tuiguangren);
                    $item->withdrawals=DB::table("memberwithdrawal")->where("status","1")->where("userid",$item->id)->sum("amount");
                    $item->recharges=DB::table("memberrecharge")->where("status","1")->where("userid",$item->id)->sum("amount");
                    $item->buys=DB::table("productbuy")->where("userid",$item->id)->sum("amount");
                    $item->url=route("admin.productbuy.lists",["s_key"=>$item->username]);



                    $item->levelName=isset($this->memberlevelName[$item->level])?$this->memberlevelName[$item->level]:'';
                    $item->inviterName=$item->inviter!=''?DB::table($this->table)->where("invicode",$item->inviter)->value("username"):'';
                   // $item->Showpassword=\App\Member::DecryptPassWord($item->password);
                   // $item->Showpaypwd=\App\Member::DecryptPassWord($item->paypwd);
                   // $item->Showmobile=\App\Member::DecryptPassWord($item->mobile);

                    $item->tuiguangs=DB::table("member")->where("inviter",$item->invicode)->count();

                }

                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{



            return $this->ShowTemplate();
        }

    }

    public function store(Request $request){



        if($request->isMethod("post")){

            if($request->input("username")){
                $admin = DB::table($this->table)
                    ->where(['username' => $request->input("username")])
                    ->first();

                if($admin){

                    if($request->ajax()){
                        return response()->json([
                            "msg"=>"用户名已经存在","status"=>1
                        ]);
                    }else{
                        return redirect(route($this->RouteController.'.store'))->withErrors($request->all(), 'store')->with(["status"=>1,"msg"=>"用户名已经存在：".$request->input("username")]);
                    }


                }
            }



            if($request->input("mobile")){
                $admin = DB::table($this->table)
                    ->where(['mobile' => $request->input("mobile")])
                    ->first();
                if($admin){
                    if($request->ajax()){
                        return response()->json([
                            "msg"=>"手机号码已经存在","status"=>1
                        ]);
                    }else{

                    return redirect(route($this->RouteController.'.store'))->withErrors($request->all(), 'store')->with(["status"=>1,"msg"=>"手机号码已经存在：".$request->input("phone")]);
                    }
                }
            }

            $messages = [
                'username.required' => '登录帐号不能为空!',
                'password.required' => '密码不能为空!',
                'paypwd.required' => '支付密码不能为空!',
            ];

            $result = $this->validate($request, [
                "username"=>"required",
                "password"=>"required",
                "paypwd"=>"required",
            ], $messages);




            $data=$request->all();

            unset($data['_token']);
            unset($data['thumb']);
            unset($data['file']);
            unset($data['productimage']);
            unset($data['password2']);
            unset($data['editormd-image-file']);

            $data['reg_from']='admin/add';
            $data['password']=\App\Member::EncryptPassWord($data['password']);
            $data['paypwd']=\App\Member::EncryptPassWord($data['paypwd']);
            $data['mobile']=\App\Member::EncryptPassWord($data['mobile']);

            $data['ip'] =  $request->getClientIp();

            $data['created_at']=$data['updated_at']=Carbon::now();

            DB::table($this->table)->insert($data);



            /*$Member = new Member();


            $Member->username = $request->input('username');



            $Member->pwd = Crypt::encrypt($request->input('password'));


            $Member->save();*/



            if($request->ajax()){
                return response()->json([
                    "msg"=>"添加成功","status"=>0
                ]);
            }else{

                return redirect(route($this->RouteController.'.store'))->with(["status"=>0,"msg"=>"添加成功"]);
            }



        }else{


            return $this->ShowTemplate();
        }

    }



    public function update(Request $request)
    {
        if($request->isMethod("post")){

            if($request->input("mobile")){
                $admin = DB::table($this->table)
                    ->where([['mobile' ,'=', $request->input("mobile")],["id","<>",$request->input("id")]])
                    ->first();
                if($admin){
                    if($request->ajax()){
                        return response()->json([
                            "msg"=>"手机号码已经存在","status"=>1
                        ]);
                    }else{
                        return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->withErrors($request->all(), 'store')->with(["status"=>1,"msg"=>"手机号码已经存在：".$request->input("phone")]);
                    }

                }
            }






/*
            $Member = Member::find($request->input('id'));
            $Member->save();
*/

            $data=$request->all();
            $id= $data['id'];
            unset($data['_token']);
            unset($data['id']);
            unset($data['thumb']);
            unset($data['password2']);
            unset($data['file']);
            unset($data['productimage']);
            unset($data['editormd-image-file']);


            if($data['paypwd']==''){
                unset($data['paypwd']);
            }else{


                $data['paypwd']=\App\Member::EncryptPassWord($data['paypwd']);
            }

            if($data['password']==''){
                unset($data['password']);
            }else{
                $data['password']=\App\Member::EncryptPassWord($data['password']);
            }

            $data['mobile']=\App\Member::EncryptPassWord($data['mobile']);

            //$data['category_name']=$this->CategoryModel->where("id",$data['category_id'])->value('name');
            $data['updated_at']=Carbon::now();

            DB::table($this->table)->where("id",$id)->update($data);


            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }



        }else{


            $Member = Member::where("id",$request->get('id'))->first();

            return $this->ShowTemplate(["edit"=>$Member]);

        }

    }


    public function switchonoff(Request $request){

        $Member=Member::find($request->id);
        if($Member){

            if($Member->state==0){
                $Member->state=1;
            }else{
                $Member->state=0;
            }

            $Member->save();

        }

        return ['status'=>0,'msg'=>'操作成功'];
    }


    public function delete(Request $request){


        if($request->ajax()) {

            if(count($request->input("ids"))>0){

                $admins = DB::table($this->table)
                    ->whereIn('id',  $request->input("ids"))
                    ->count();
                if($admins>0){
                    return ["status" => 1, "msg" => "系统用户组不允许删除"];
                }

                $delete = DB::table($this->table)->whereIn('id', $request->input("ids"))->delete();
                if ($delete) {
                    return ["status" => 0, "msg" => "批量删除成功"];
                } else {
                    return ["status" => 1, "msg" => "批量删除失败"];
                }
            }

            if($request->input("id")){

                $admin = DB::table($this->table)
                    ->where(['id' => $request->input("id")])
                    ->first();
                if($admin){

                    $delete = DB::table($this->table)->where('id', '=', $request->input("id"))->delete();
                    if ($delete) {
                        return ["status" => 0, "msg" => "删除成功"];
                    } else {
                        return ["status" => 1, "msg" => "删除失败"];
                    }


                }


            }

            return ["status"=>1,"msg"=>"非法操作"];
        }else{
            return ["status"=>1,"msg"=>"非法操作"];
        }

    }

    //资金操作

    public function moneys(Request $request){

        view()->share("request",$request);
        if($request->isMethod("post")){



        if($request->moneytype=='+') {

            $amount = intval($request->amount);

            if ($amount < 1) {
                return response()->json([
                    "msg" => "充值金额错误", "status" => 1
                ]);
            }

            if (!isset($this->payment[$request->paymentid])) {
                return response()->json([
                    "msg" => "充值方式错误", "status" => 1
                ]);
            }

            if ($request->type == '') {
                return response()->json([
                    "msg" => "充值类型错误", "status" => 1
                ]);
            }
            if ($request->userid < 1) {
                return response()->json([
                    "msg" => "会员帐号错误", "status" => 1
                ]);
            }

            $memo = $request->memo != '' ? $request->memo : $this->payment[$request->paymentid] . '充值' . $request->amount;


            $data = \App\Memberrecharge::Recharge([
                "userid" => $request->userid, //会员ID
                "amount" => $request->amount,//金额
                "memo" => $memo,//备注
                "status" => 1,//备注
                "paytime" => Carbon::now(),//充值时间
                "paymentid" => $request->paymentid,//充值方式 1支付宝,2微信,3银行卡
                "ip" => $request->getClientIp(),//IP
                "type" => $request->type,//类型 Cache(RechargeType):系统充值|优惠活动|优惠充值|后台充值|用户充值

            ]);


            $Member = Member::find($request->userid);
            $amount = $Member->amount;
            $Member->increment('amount', $request->amount);

            $msg = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "title" => "充值成功",
                "content" => "您的充值成功(" . $data['data']->ordernumber . ")",
                "from_name" => "系统充值",
                "types" => "充值",
            ];
            \App\Membermsg::Send($msg);


            $log = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "money" => $request->amount,
                "notice" => "充值成功(+)",
                "type" => "充值",
                "status" => "+",
                "yuanamount" => $amount,
                "houamount" => $Member->amount,
                "ip" => \Request::getClientIp(),
            ];

            \App\Moneylog::AddLog($log);


            if ($request->ajax()) {
                return response()->json([
                    "msg" => "充值成功", "status" => 0
                ]);
            }

        }else if($request->moneytype=='-'){

            $memo = $request->memo != '' ? $request->memo : $this->payment[$request->paymentid] . '扣款' . $request->amount;

            $Member = Member::find($request->userid);
            $amount = $Member->amount;
            $Member->decrement('amount', $request->amount);

            $msg = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "title" => "扣款",
                "content" => $memo,
                "from_name" => "系统扣款",
                "types" => "扣款",
            ];
            \App\Membermsg::Send($msg);


            $log = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "money" => $request->amount,
                "notice" => "扣款成功(-)",
                "type" => "扣款",
                "status" => "-",
                "yuanamount" => $amount,
                "houamount" => $Member->amount,
                "ip" => \Request::getClientIp(),
            ];

            \App\Moneylog::AddLog($log);


            if ($request->ajax()) {
                return response()->json([
                    "msg" => "扣款成功", "status" => 0
                ]);
            }


        }



        }else{

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

            view()->share("payment",$this->payment);

            $member= DB::table("member")->where("state","1")->get();

            return $this->ShowTemplate(["member"=>$member]);
        }


    }


    //冻结资金操作

    public function frozen(Request $request){

        view()->share("request",$request);
        if($request->isMethod("post")){

        if($request->moneytype=='+') {

            $amount = intval($request->amount);

            if ($amount < 1) {
                return response()->json([
                    "msg" => "金额错误", "status" => 1
                ]);
            }



            $Member = Member::find($request->userid);

            if($Member->is_dongjie<$request->amount){
                return response()->json([
                    "msg" => "冻结金额小于解冻金额", "status" => 1
                ]);
            }
            $memo = $request->memo != '' ? $request->memo :  '解冻资金(' . $request->amount.')';
            $amount = $Member->amount;
            $Member->increment('amount', $request->amount);
            $Member->decrement('is_dongjie', $request->amount);

            $msg = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "title" => "解冻资金",
                "content" => $memo,
                "from_name" => "系统解冻",
                "types" => "解冻资金",
            ];
            \App\Membermsg::Send($msg);


            $log = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "money" => $request->amount,
                "notice" => "解冻资金(+)",
                "type" => "解冻资金",
                "status" => "+",
                "yuanamount" => $amount,
                "houamount" => $Member->amount,
                "ip" => \Request::getClientIp(),
            ];

            \App\Moneylog::AddLog($log);


            if ($request->ajax()) {
                return response()->json([
                    "msg" => "解冻成功", "status" => 0
                ]);
            }

        }else if($request->moneytype=='-'){

            //|解冻资金+ 冻结资金-
            $memo = $request->memo != '' ? $request->memo :  '冻结资金(' . $request->amount.')';

            $Member = Member::find($request->userid);

            if($Member->amount<$request->amount){
                return response()->json([
                    "msg" => "余额小于冻结金额", "status" => 1
                ]);
            }

            $amount = $Member->amount;
            $Member->decrement('amount', $request->amount);
            $Member->increment('is_dongjie', $request->amount);

            $msg = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "title" => "冻结资金",
                "content" => $memo,
                "from_name" => "系统冻结",
                "types" => "冻结资金",
            ];
            \App\Membermsg::Send($msg);


            $log = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "money" => $request->amount,
                "notice" => "冻结资金(-)",
                "type" => "冻结资金",
                "status" => "-",
                "yuanamount" => $amount,
                "houamount" => $Member->amount,
                "ip" => \Request::getClientIp(),
            ];

            \App\Moneylog::AddLog($log);


            if ($request->ajax()) {
                return response()->json([
                    "msg" => "冻结成功", "status" => 0
                ]);
            }


        }



        }else{


            $member= DB::table("member")->where("state","1")->get();

            return $this->ShowTemplate(["member"=>$member]);
        }


    }


}
