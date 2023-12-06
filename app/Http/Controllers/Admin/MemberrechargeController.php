<?php


namespace App\Http\Controllers\Admin;
    use App\Memberlevel;
    use App\Memberrecharge;
    use DB;
    use Illuminate\Http\Request;
    use Session;
    use Cache;


class MemberrechargeController extends BaseController
{

    private $table="memberrecharge";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Memberrecharge();


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

        return redirect(route($this->RouteController.".lists"));

    }




    public function lists(Request $request){

/*        'ordernumber',
        'userid',
        'username',
        'amount',
        'memo',
        'paymentid',
        'status',
        'paytime',
        'ip',
        'bank',
        'accNo',
        'sendsms',
        'type'*/

        $adminAuthID =$request->session()->get('adminAuthID');
        $adminID =$request->session()->get('adminID');

/*
      \App\Memberrecharge::Recharge([
            "userid"=>1, //会员ID
            "username"=>'qq',//会员帐号
            "amount"=>100,//金额
            "memo"=>'支付宝充值100元',//备注
            "paymentid"=>1,//充值方式 1支付宝,2微信,3银行卡
            "ip"=>$request->getClientIp(),//IP
            "type"=>'用户充值',//类型 Cache(RechargeType):系统充值|优惠活动|优惠充值|后台充值|用户充值

        ]);
*/

        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }



        if($request->ajax()){
            $listDB = DB::table($this->table)
                ->select($this->table.'.*')
                ->where('type','<>','购买等级LV1')
                ->where('type','<>','购买等级LV2')
                ->where('type','<>','购买等级LV3')
                ->where(function ($query) {
                    $s_siteid=[];
                    if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                        $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                    }

                    $query->where($s_siteid);
                })

            ->where(function ($query) {
                $s_siteid=[];
                if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']>0){
                    $s_siteid[]=[$this->table.".paymentid","=",$_REQUEST['s_category_id']];
                }

                $query->where($s_siteid);
            })->where(function ($query) {
                $s_status=[];
                if(isset($_REQUEST['s_type']) && $_REQUEST['s_type']!=''){
                    $s_status[]=[$this->table.".type","=",$_REQUEST['s_type']];
                }

                $query->where($s_status);
            })->where(function ($query) {
                $s_status=[];
                if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                    $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                }

                $query->where($s_status);
            });

            $list=$listDB->orderBy($this->table.".id","desc")
                ->paginate($pagesize);

            if($list){
                foreach ($list as $item){
                    $item->paymentname=isset($this->payment[$item->paymentid])?$this->payment[$item->paymentid]:'';
                }
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{



            return $this->ShowTemplate([]);
        }

    }

    public function store(Request $request){

        if($request->isMethod("post")){

           $amount= intval($request->amount);

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

           if($request->type==''){
               return response()->json([
                   "msg"=>"充值类型错误","status"=>1
               ]);
           }
           if($request->userid<1){
               return response()->json([
                   "msg"=>"会员帐号错误","status"=>1
               ]);
           }

           $memo= $request->memo!=''?$request->memo:$this->payment[$request->paymentid].'充值'.$request->amount;



            \App\Memberrecharge::Recharge([
                "userid"=>$request->userid, //会员ID
                //"username"=>'qq',//会员帐号
                "amount"=>$request->amount,//金额
                "memo"=>$memo,//备注
                "paymentid"=>$request->paymentid,//充值方式 1支付宝,2微信,3银行卡
                "ip"=>$request->getClientIp(),//IP
                "type"=>$request->type,//类型 Cache(RechargeType):系统充值|优惠活动|优惠充值|后台充值|用户充值

            ]);



            if($request->ajax()){
                return response()->json([
                    "msg"=>"充值成功","status"=>0
                ]);
            }else{

                return redirect(route($this->RouteController.'.store'))->with(["msg"=>"充值成功","status"=>0]);
            }



        }else{

            $member= DB::table("member")->where("state","1")->get();

            return $this->ShowTemplate(["member"=>$member]);
        }

    }






    public function update(Request $request)
    {
        if($request->isMethod("post")){



            $Model = $this->Model::find($request->get('id'));

            if($Model->status==0){
                \App\Memberrecharge::ConfirmRecharge($Model->id,$request->status);
            }

            if($request->ajax()){
                return response()->json([
                    "msg"=>"操作成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }


        }else{


            $Model = $this->Model::find($request->get('id'));

            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
        }

    }


    public function sendsms(Request $request)
    {
        if($request->isMethod("post")){



            $Model = $this->Model::find($request->get('id'));

            if($Model->sendsms==0){
                $Model->sendsms=1;
                $Model->save();
            }

            \App\Sendmobile::SendUid($Model->userid,'rechargeok');//短信通知
            //\App\Sendmobile::SendUid($Model->userid,'regcode');//短信通知



            if($request->ajax()){
                return response()->json([
                    "msg"=>"操作成功","status"=>0
                ]);
            }


        }

    }





    public function delete(Request $request){

          if($request->ajax()) {
            if($request->input("id")){

                $member = DB::table($this->table)
                    ->where(['id' => $request->input("id")])
                    ->first();
                if($member){

                       $delete = DB::table($this->table)->where('id', '=', $request->input("id"))->delete();
                        if ($delete) {
                            return ["status" => 0, "msg" => "删除成功"];
                        } else {
                            return ["status" => 1, "msg" => "删除失败"];
                        }


                }else{
                    return ["status"=>1,"msg"=>"您没有权限删除操作"];
                }


            }


        }else{
            return ["status"=>1,"msg"=>"非法操作"];
        }

    }

    public function buyviplist(Request $request){



        $adminAuthID =$request->session()->get('adminAuthID');
        $adminID =$request->session()->get('adminID');

        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }


        if($request->ajax()){
            $listDB = DB::table($this->table)
                ->select($this->table.'.*')
                ->where('type','like','购买等级%')
                ->where(function ($query) {
                    $s_siteid=[];
                    if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                        $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                    }

                    $query->where($s_siteid);
                })

            ->where(function ($query) {
                $s_siteid=[];
                if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']>0){
                    $s_siteid[]=[$this->table.".paymentid","=",$_REQUEST['s_category_id']];
                }

                $query->where($s_siteid);
            })->where(function ($query) {
                $s_status=[];
                if(isset($_REQUEST['s_type']) && $_REQUEST['s_type']!=''){
                    $s_status[]=[$this->table.".type","=",$_REQUEST['s_type']];
                }

                $query->where($s_status);
            })->where(function ($query) {
                $s_status=[];
                if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                    $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                }

                $query->where($s_status);
            });

            $list=$listDB->orderBy($this->table.".id","desc")
                ->paginate($pagesize);

            if($list){
                foreach ($list as $item){
                    $item->paymentname=isset($this->payment[$item->paymentid])?$this->payment[$item->paymentid]:'';
                }
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{

            return $this->ShowTemplate([]);
        }

    }

}
