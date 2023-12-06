<?php

namespace App\Http\Controllers\Admin;
use DB;
use App\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Cache;
use App\Seting;

class StoreController extends BaseController
{

    public $table='stores';

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }



    public function index(Request $request){
        return redirect("admin/store/lists");
    }

    public function lists(Request $request){


        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }


        $list = DB::table($this->table)
            ->orderBy("sort","desc")
            ->paginate($pagesize);
        if($list){
            if($request->ajax()){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }else{
                return $this->ShowTemplate("store.lists",["list"=>$list,"pagesize"=>$pagesize]);
            }
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

            $Models = Site::find($request->input('id'));
            $Models->name = $request->input('name');
            $Models->domain = $request->input('domain');
            $Models->template = $request->input('template');
            $Models->seotitle = $request->input('seotitle');
            $Models->keywords = $request->input('keywords');
            $Models->description = $request->input('description');
            $Models->disabled =  $request->input('disabled')=='on'?0:1;
            $Models->save();

            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route('store_update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }


        }else{

            $Models = Site::find($request->get('id'));
            return $this->ShowTemplate("store.update",["edit"=>$Models,"status"=>0]);
        }

    }




    public function updates(Request $request){

        if($request->ajax()) {
            $data[$request->input("keys")]=$request->input("value");
                DB::table('stores')
                    ->where('id', $request->input("id"))
                    ->update($data);
            return ["status" => 0,"msg" => "操作成功"];
        }
    }


}
