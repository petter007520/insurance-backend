<?php

namespace App\Http\Controllers\Admin;
use DB;
use App\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Cache;




class AuthController extends BaseController
{
    private $table="auth";
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Models= new Auth();
    }

    public function index(Request $request){
        return redirect(route($this->RouteController.".lists"));
    }

    public function lists(Request $request){


        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }


        if($request->ajax()){

            $list = DB::table($this->table)
                ->orderBy("sort","desc")
                ->paginate($pagesize);
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }

        }else{

            $list = DB::table($this->table)
                ->orderBy("sort","desc")
                ->paginate($pagesize);
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }

    public function store(Request $request){

        if($request->isMethod("post")){

            $messages = [
                'name.required' => '名称不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
            ], $messages);


            $Auth = $this->Models;
            $Auth->name = $request->input('name');
            $Auth->sort = $request->input('sort');
			$Auth->authority= serialize([]);
			$Auth->atlogintime= serialize($request->input("atlogintime"));
            $Auth->disabled = $request->input('disabled')=='on'?0:1;
            $Auth->save();

            if($request->ajax()){
                return response()->json([
                    "msg"=>"添加成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.store'))->with(["msg"=>"添加成功","status"=>0]);
            }



        }else{
            return $this->ShowTemplate(["status"=>0]);
        }

    }




    public function update(Request $request)
    {



        if($request->isMethod("post")){

            $messages = [
                'name.required' => '名称不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
            ], $messages);


            $Auth = $this->Models->find($request->input('id'));
            $Auth->name = $request->input('name');
            $Auth->sort = $request->input('sort');
			if($Auth->authority==''){
				$Auth->authority= serialize([]);
			}
            $Auth->atlogintime= serialize($request->input("atlogintime"));
            $Auth->disabled =  $request->input('disabled')=='on'?0:1;
            $Auth->save();

            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }



        }else{

            $Auth = $this->Models->where("id",$request->get('id'))->first();
            $Auth->logintime=unserialize($Auth->atlogintime);
           // print_r($Auth->logintime);
            return $this->ShowTemplate(["edit"=>$Auth,"status"=>0]);
        }

    }

    public function set(Request $request)
    {

        if($request->isMethod("post")){


            $model=[];
            $contr=[];
            $action=[];

            if(count($request->input("setid"))>0) {


                $Auth = $this->Models->find($request->input('id'));
                $Auth->authority = json_encode($request->input("setid"));
                $Auth->save();


            }else{
                $Auth = $this->Models->find($request->input('id'));
                $Auth->authority = json_encode([]);
                $Auth->save();
            }
            return redirect(route($this->RouteController.'.set',["id"=>$request->input("id"),"ids"=>$request->input("setid")]))->with(["msg"=>"修改成功","status"=>0]);

        }else{
            $list = DB::table('menus')
                ->where([["parent","=","0"],["disabled","=","0"]])
                ->orderBy("sort","desc")
                ->get();
            foreach($list as $mv){
                $mv->menus= DB::table('menus')->where([["parent","=",$mv->id],["disabled","=","0"]]) ->orderBy("sort","desc")->get();
            }
            $ids=[];
            $Auth = $this->Models->where("id",$request->get('id'))->first();

            if($Auth->authority!=''){
                $ids=  json_decode($Auth->authority,true);
            }


            if(!empty($ids)){
                return $this->ShowTemplate(["edit"=>$Auth,"ids"=>$ids,"list"=>$list,"id"=>$request->get('id'),"status"=>0]);
            }else{
                return $this->ShowTemplate(["edit"=>$Auth,"list"=>$list,"id"=>$request->get('id'),"status"=>0]);
            }

        }

    }




    public function delete(Request $request){

        if($request->ajax()) {
            if($request->input("id")){

                $auth = DB::table($this->table)
                    ->where(['id' => $request->input("id")])
                    ->first();
                if($auth){
                    if($auth->id>1) {
                        $delete = DB::table($this->table)->where('id', '=', $request->input("id"))->delete();
                        if ($delete) {
                            return ["status" => 0, "msg" => "删除成功"];
                        } else {
                            return ["status" => 1, "msg" => "删除失败"];
                        }
                    }else{
                        return ["status" => 1, "msg" => "系统组不允许删除"];
                    }

                }


            }


        }else{
            return ["status"=>1,"msg"=>"非法操作"];
        }

    }

}
