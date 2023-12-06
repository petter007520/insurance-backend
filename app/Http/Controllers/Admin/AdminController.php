<?php

namespace App\Http\Controllers\Admin;
use App\Auth;
use App\Channel;
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

class AdminController extends BaseController
{

    private $table="admins";
    private $Models;
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $Models= new Admin();
        view()->share("adminlist",$Models::orderBy("id","asc")->get());

    }


public function index(Request $request){

    return redirect(route($this->RouteController.'.lists'));

}

    public function lists(Request $request){


        $where_auth=[];
        $AuthName=[];
        if($request->auth){
            $where_auth=[["admins.authid","=",$request->auth]];
            $Auth=Auth::find($request->auth);
            if($Auth){
                $AuthName=$Auth->toArray();
            }

        }



        $Where=[];
        $WhereAuth=[];

        $authid= $request->session()->get('adminAuthID');

        if($authid>1){
            $Where=[["admins.authid",">=",$authid]];
            $WhereAuth=[["disabled",0]];
        }

        if($request->ajax()){


            $pagesize=10;//默认分页数
            if(Cache::has('pagesize')){
                $pagesize=Cache::get('pagesize');
            }


            // $list = DB::table($this->table)
            //     ->leftJoin('auth', 'auth.id', '=', 'admins.authid')
            //     ->leftJoin('admins as admin', 'admins.adminid', '=', 'admin.id')
            //     ->select('admins.*','auth.name as authname','admin.username as padminname')
            //     ->orderBy("admins.disabled","asc")
            $list = DB::table($this->table)
                ->leftJoin('auth', 'auth.id', '=', 'admins.authid')
                // ->leftJoin('admins as admin', 'admins.adminid', '=', 'admin.id')
                ->select('admins.*','auth.name as authname',$this->table.'.username as padminname')
                ->orderBy("admins.disabled","asc")
                ->where($Where)
                ->where(function ($query) {
                    $s_key_name=[];
                    $s_key_phone=[];
                    $s_key_email=[];
                    $s_key_username=[];
                    $s_key_authname=[];
                    if(isset($_REQUEST['s_key'])){
                        $s_key_name[]=["admins.name","like","%".$_REQUEST['s_key']."%"];
                        $s_key_username[]=["admins.username","like","%".$_REQUEST['s_key']."%"];
                        $s_key_phone[]=["admins.phone","like","%".$_REQUEST['s_key']."%"];
                        $s_key_email[]=["admins.email","like","%".$_REQUEST['s_key']."%"];
                        $s_key_authname[]=["auth.name","like","%".$_REQUEST['s_key']."%"];

                    }

                    $query->orwhere($s_key_phone)
                        ->orwhere($s_key_name)
                        ->orwhere($s_key_username)
                        ->orwhere($s_key_authname)
                        ->orwhere($s_key_email);
                })
                ->where(function ($query) {
                    $s_authid=[];
                    if(isset($_REQUEST['authid']) && $_REQUEST['authid']>0){
                        $s_authid[]=["admins.authid","=",$_REQUEST['authid']];
                    }

                    $query->where($s_authid);
                })
                ->where($where_auth)
                ->paginate($pagesize);

            if($list){

                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{


            $ahths= Auth::where($WhereAuth)->orderBy("sort","desc")->get();
            return $this->ShowTemplate(["AuthName"=>$AuthName,"ahths"=>$ahths]);
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



            if($request->input("phone")){
                $admin = DB::table($this->table)
                    ->where(['phone' => $request->input("phone")])
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
            ];

            $result = $this->validate($request, [
                "username"=>"required",
                "password"=>"required",
            ], $messages);

           $AdminId= $this->Admin->id;

          if($this->Admin->authid==1){
              $AdminId=$request->input("adminid")?$request->input("adminid"):$this->Admin->id;
          }


            $Admin = new Admin();
            if($request->input('name')){
                $Admin->name = $request->input('name');
            }

            $Admin->username = $request->input('username');



                if ($request->input('authid') > 0) {
                    $Admin->authid = $request->input('authid');
                }

                    $Admin->disabled =1;

                if ($request->input('disabled') != '') {
                    $Admin->disabled = $request->input('disabled');
                }

                if($request->input('limit')!=''){
                    $Admin->limit = $request->input('limit');
                }





            $Admin->phone = $request->input('phone');

            if($request->input('name')){
                $Admin->remarks = $request->input('remarks');
            }

            $Admin->password = Crypt::encrypt($request->input('password'));
            $Admin->adminid = $AdminId;

            $Admin->save();

            


            if($request->ajax()){
                return response()->json([
                    "msg"=>"添加成功","status"=>0
                ]);
            }else{

                return redirect(route($this->RouteController.'.store'))->with(["status"=>0,"msg"=>"添加成功"]);
            }



        }else{
            $WhereAuth=[["disabled",0]];
            if($this->Admin->authid>1){
                $authlist=DB::table("auth")
                    ->where("id",">=",$this->Admin->authid)
                    ->orderBy(
                        "id","asc"
                    )
                    ->get();

            }else{
                $authlist=DB::table("auth")->orderBy(
                    "sort","desc"
                )->get();

            }


            $list = DB::table($this->table)->orderBy("authid","asc")->get();



            return $this->ShowTemplate(["authlist"=>$authlist,"Admin"=>$this->Admin]);
        }

    }



    public function update(Request $request)
    {
        if($request->isMethod("post")){

            if($request->input("phone")){
                $admin = DB::table($this->table)
                    ->where([['phone' ,'=', $request->input("phone")],["id","<>",$request->input("id")]])
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







            $Admin = Admin::find($request->input('id'));

            if($request->input('authid')>1) {
                $Admin->authid = $request->input('authid');
            }

            $AdminId= $Admin->adminid;
            if($this->Admin->authid==1){
                $AdminId=$request->input("adminid")?$request->input("adminid"):$Admin->adminid;
            }
            $Admin->phone = $request->input('phone');


            $Admin->adminid =$AdminId;


            if($request->input('name')){
                $Admin->name = $request->input('name');
            }

            if($request->input('remarks')){
                $Admin->remarks = $request->input('remarks');
            }




                if ($request->input('authid') > 0) {
                    $Admin->authid = $request->input('authid');
                }



                if ($request->input('disabled') != '') {
                    $Admin->disabled = $request->input('disabled');
                }

                if($request->input('limit')!=''){
                    $Admin->limit = $request->input('limit');
                }




            if($request->input('password')!='') {
                $Admin->password = Crypt::encrypt($request->input('password'));
            }
            $Admin->save();


            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }



        }else{
            if($this->Admin->authid>1){
                    $authlist=DB::table("auth")
                        ->where("id",">=",$this->Admin->authid)
                        ->orderBy(
                            "id","asc"
                        )

                        ->get();


            }else{
                $authlist=DB::table("auth")->orderBy(
                    "sort","desc"
                )->get();
            }


            $list = DB::table($this->table)->orderBy("authid","asc")->get();

            $Admin = Admin::where("id",$request->get('id'))->first();



            return $this->ShowTemplate(["authlist"=>$authlist,"edit"=>$Admin,"Admin"=>$this->Admin]);

        }

    }



    public function delete(Request $request){


        if($request->ajax()) {
            
            if($request->input("ids")!='' &&count($request->input("ids"))>0){

                $admins = DB::table($this->table)
                    ->whereIn('id',  $request->input("ids"))
                    ->where('authid',  "1")
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

                    if($admin->authid>1) {
                        $delete = DB::table($this->table)->where('id', '=', $request->input("id"))->delete();
                        if ($delete) {
                            return ["status" => 0, "msg" => "删除成功"];
                        } else {
                            return ["status" => 1, "msg" => "删除失败"];
                        }
                    }else{
                        return ["status" => 1, "msg" => "系统用户组不允许删除"];
                    }

                }


            }

            return ["status"=>1,"msg"=>"非法操作"];
        }else{
            return ["status"=>1,"msg"=>"非法操作"];
        }

    }


    public function switchonoff(Request $request){

        $Admin=Admin::find($request->id);
        if($Admin){

                if($Admin->disabled==0){
                    $Admin->disabled=1;
                }else{
                    $Admin->disabled=0;
                }

            $Admin->save();

        }

        return ['status'=>0,'msg'=>'操作成功'];
    }


}
