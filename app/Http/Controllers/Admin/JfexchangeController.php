<?php


namespace App\Http\Controllers\Admin;
    use App\Member;
    use App\Memberlevel;
    use DB;
    use Illuminate\Http\Request;
    use Session;
    use Cache;


class JfexchangeController extends BaseController
{

    private $table="jfexchanges";


    public function __construct(Request $request)
    {
        parent::__construct($request);



/*        if(Cache::has("admin.payment")){
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

        view()->share("payment",$this->payment);*/

    }



    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }




    public function lists(Request $request){



      //  \App\Memberwithdrawal::AddWithdrawal(1,5000);
        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }



        if($request->ajax()){
            $listDB = DB::table($this->table)
                ->select($this->table.'.*')
            ->where(function ($query) {
                $s_siteid=[];
                if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                    $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                }

                $query->where($s_siteid);
            })
                ->where(function ($query) {
                $s_status=[];
                if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                    $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                }

                $query->where($s_status);
            });

            $list=$listDB->orderBy($this->table.".id","desc")
                ->paginate($pagesize);

            if($list){



                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{



            return $this->ShowTemplate([]);
        }

    }

    public function store(Request $request){

       

    }






    public function update(Request $request)
    {
        if($request->isMethod("post")){



            $Model = DB::table($this->table)->where("id",$request->get('id'))->first();



       /*     $type="兑换";
            if($Model->type==2){
                $type="抽奖";
            }*/

            if($Model->status==0){
                if($Model->type==1){
                if($request->status=='1'){
                   //$data= \App\Memberwithdrawal::ConfirmWithdrawal($Model->id);
                    DB::table($this->table)->where("id",$request->get('id'))->update(["status"=>"1"]);
                    $data=["msg"=>"兑换成功","status"=>0];


                    $msg=[
                        "userid"=>$Model->userid,
                        "username"=>$Model->username,
                        "title"=>"兑换成功",
                        "content"=>"兑换成功(".$Model->productname.")",
                        "from_name"=>"系统通知",
                        "types"=>"积分兑换",
                    ];
                    \App\Membermsg::Send($msg);

                }else if($request->status=='-1'){
                    //$data= \App\Memberwithdrawal::CancelWithdrawal($Model->id);
                    DB::table($this->table)->where("id",$request->get('id'))->update(["status"=>"-1"]);
                    $data=["msg"=>"取消兑换成功","status"=>0];

                    $Member= Member::find($Model->userid);
                    $amount=  $Member->integral;
                    $Member->increment('integral',$Model->integral);

                    $msg=[
                        "userid"=>$Model->userid,
                        "username"=>$Model->username,
                        "title"=>"取消兑换",
                        "content"=>"取消兑换失败(".$Model->productname.")",
                        "from_name"=>"系统通知",
                        "types"=>"积分兑换",
                    ];
                    \App\Membermsg::Send($msg);


                    $msg=[
                        "userid"=>$Model->userid,
                        "username"=>$Model->username,
                        "title"=>"积分退回",
                        "content"=>"您的兑换商品(".$Model->productname.")的积分已退回(".$Model->integral.")",
                        "from_name"=>"系统通知",
                        "types"=>"积分兑换",
                    ];
                    \App\Membermsg::Send($msg);


                    $log=[
                        "userid"=>$Model->userid,
                        "username"=>$Model->username,
                        "money"=>$Model->integral,
                        "notice"=>"积分退回".$Model->productname."(+)",
                        "type"=>"积分兑换",
                        "status"=>"+",
                        "yuanamount"=>$amount,
                        "houamount"=>$Member->integral,
                        "ip"=>\Request::getClientIp(),
                    ];

                    \App\Moneylog::AddLog($log);

                }

                if($request->ajax()){
                    return response()->json($data);
                }

                }else if($Model->type==2){

                    //抽奖奖品



                    if($request->status=='1'){
                        //$data= \App\Memberwithdrawal::ConfirmWithdrawal($Model->id);
                        DB::table($this->table)->where("id",$request->get('id'))->update(["status"=>"1"]);
                        $data=["msg"=>"抽奖奖品成功","status"=>0];


                        $msg=[
                            "userid"=>$Model->userid,
                            "username"=>$Model->username,
                            "title"=>"抽奖奖品发货",
                            "content"=>"抽奖奖品发货(".$Model->productname.")",
                            "from_name"=>"系统通知",
                            "types"=>"抽奖",
                        ];
                        \App\Membermsg::Send($msg);

                    }else if($request->status=='-1'){
                        //$data= \App\Memberwithdrawal::CancelWithdrawal($Model->id);
                        DB::table($this->table)->where("id",$request->get('id'))->update(["status"=>"-1"]);
                        $data=["msg"=>"取消抽奖奖品发放","status"=>0];


                        $msg=[
                            "userid"=>$Model->userid,
                            "username"=>$Model->username,
                            "title"=>"取消抽奖奖品发放",
                            "content"=>"取消抽奖奖品发放(".$Model->productname.")",
                            "from_name"=>"系统通知",
                            "types"=>"抽奖",
                        ];
                        \App\Membermsg::Send($msg);



                    }

                    if($request->ajax()){
                        return response()->json($data);
                    }


                }

            }




        }else{


            $Model = DB::table($this->table)->where("id",$request->get('id'))->first();

            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
        }

    }


    public function sendsms(Request $request)
    {
        if($request->isMethod("post")){



            $Model = DB::table($this->table)->where("id",$request->get('id'))->first();

            if($Model->sendsms==0){
                DB::table($this->table)->where("id",$request->get('id'))->update(["sendsms"=>"1"]);
            }

            DB::table($this->table)->where("id",$request->get('id'))->update(["memo"=>$request->get('value')]);

            //\App\Sendmobile::SendUid($Model->userid,'txcg');//短信通知

            $type='积分兑换';
            if($Model->type==2){
                $type='抽奖';
            }

            $msg=[
                "userid"=>$Model->userid,
                "username"=>$Model->username,
                "title"=>"商品发货(".$Model->productname.")",
                "content"=>$request->get('value'),
                "from_name"=>"系统通知",
                "types"=>$type,
            ];
            \App\Membermsg::Send($msg);


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



}
