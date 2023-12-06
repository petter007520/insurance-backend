<?php
namespace App\Http\Controllers\Admin;
use App\Storage;
use App\TravelLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Cache;
class TravelLogController extends BaseController
{
    private $table="travellog";

    public function __construct(Request $request)
    {
        \App\Http\Controllers\Admin\BaseController::__construct($request);
        $this->Model= new TravelLog();
    }

    public function lists(Request $request){
        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }
        $Where2=[];
        if($request->s_status!=''){
            $Where2=[["status",$request->s_status]];
        }
        $Where3=[];
        if($request->s_key!=''){
            $Where3=[["username",$request->s_key]];
        }
        $list=[];
        if($request->ajax()){
            $list = DB::table($this->table)
                ->where($Where2)
                ->where($Where3)
                ->orderBy( "id", "desc")
                ->paginate($pagesize);
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }
    }

    public function set_notice(Request $request)
    {
        if($request->isMethod("post")){
            $Model = $this->Model::find($request->get('id'));
            if($Model->status==0){
                $Model->status=1;
                $Model->save();
            }
            if($request->ajax()){
                return response()->json([
                    "msg"=>"操作成功","status"=>0
                ]);
            }
        }
    }

    public function delete(Request $request)
    {
         if ($request->ajax()) {
             if ($request->input("ids")) {
                     $delete = DB::table($this->table)->whereIn('id',  $request->input("ids"))->delete();
                     if ($delete) {
                         return ["status" => 0, "msg" => "删除成功"];
                     } else {
                         return ["status" => 1, "msg" => "删除失败"];
                     }
             }
         } else {
             return ["status" => 1, "msg" => "非法操作"];
         }
    }



}
