<?php

namespace App\Http\Controllers\Admin;
use DB;
use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Session;


class ManageController extends BaseController
{


    private $table="admins";
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request){




        if($request->isMethod("post")){

            if($request->input("email")){
                $admin = DB::table($this->table)
                    ->where([['email' ,'=', $request->input("email")],["id","<>",$this->Admin->id]])
                    ->first();
                if($admin){

                    if($request->ajax()){
                        return response()->json([
                            "msg"=>"邮箱地址已经存在","status"=>1
                        ]);
                    }else{
                        return redirect(route($this->RouteController.'.update',["id"=>$this->Admin->id]))->withErrors($request->all(), 'store')->with(["status"=>1,"msg"=>"邮箱地址已经存在：".$request->input("email")]);
                    }


                }
            }



            if($request->input("phone")){
                $admin = DB::table($this->table)
                    ->where([['phone' ,'=', $request->input("phone")],["id","<>",$this->Admin->id]])
                    ->first();
                if($admin){

                    if($request->ajax()){
                        return response()->json([
                            "msg"=>"手机号码已经存在","status"=>1
                        ]);
                    }else{
                        return redirect(route($this->RouteController.'.update',["id"=>$this->Admin->id]))->withErrors($request->all(), 'store')->with(["status"=>1,"msg"=>"手机号码已经存在：".$request->input("phone")]);
                    }

                }
            }


            $Admin = Admin::find($this->Admin->id);
            $Admin->name = $request->input('name');
            $Admin->phone = $request->input('phone');
            $Admin->email = $request->input('email');
            $Admin->remarks = $request->input('remarks');

            if($request->input('password')!='') {

                if( Crypt::decrypt($Admin->password)!=$request->input("oldpassword")){

                    if($request->ajax()){
                        return response()->json([
                            "msg"=>"旧密码不正确","status"=>1
                        ]);
                    }else{
                        return redirect(route($this->RouteController.'.update',["id"=>$this->Admin->id]))->withErrors($request->all(), 'store')->with(["status"=>1,"msg"=>"旧密码不正确"]);
                    }


                }

                $Admin->password = Crypt::encrypt($request->input('password'));
            }
            $Admin->save();

            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$this->Admin->id]))->with(["msg"=>"修改成功","status"=>0]);
            }



        }else{
            $authlist=DB::table("auth")->orderBy(
                "sort","desc"
            )->get();
           
            $Admin = Admin::find($this->Admin->id);
            $title=$Admin->name.'的个人中心';
            return $this->ShowTemplate(["authlist"=>$authlist,"edit"=>$Admin,"title"=>$title]);
        }
    }

    public function resetpw(){
        return 'resetpw';
    }
}
