<?php


namespace App\Http\Controllers\Admin;
    use App\Version;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\DB;

class VersionController extends BaseController
{

    private $table="version";
    public $model;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->model=new Version();
    }

    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }

    public function lists(Request $request){
        $pageSize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pageSize=Cache::get('pagesize');
        }
        $list = DB::table($this->table)->orderBy('id',"desc")->paginate($pageSize);
        if($request->ajax()){
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pageSize];
            }
        }else{
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pageSize]);
        }
    }

    public function store(Request $request){
        if($request->isMethod("post")){
            $data=$request->post();
            unset($data['_token']);
            $data['create_time'] = Carbon::now();
            DB::table("version")->insert($data);
            if($request->ajax()){
                return response()->json([
                    "msg"=>"添加成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.store'))->with(["msg"=>"添加成功","status"=>0]);
            }
        }else{
            return $this->ShowTemplate();
        }
    }

    public function update(Request $request)
    {
        if($request->isMethod("post")){
            $data=$request->post();
            $id = $request->post('id',0);
            unset($data['_token']);
            DB::table("version")->where("id",$id)->update($data);
            Cache::forget('version'.$id);
            if($request->ajax()){
                Cache::forget('version_'.$data['platform']);
                return response()->json(["msg"=>"修改成功","status"=>0]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }
        }else{
            $Model = $this->model::find($request->post('id'));
            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
        }
    }

    public function settop(Request $request)
    {
        if($request->isMethod("post")){
            $Model = $this->model::find($request->input('id'));
            $Model->save();
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
            }else if($request->input("ids")){
                $delete = DB::table($this->table)->whereIn('id',  $request->input("ids"))->delete();
                if ($delete) {
                    return ["status" => 0, "msg" => "删除成功"];
                } else {
                    return ["status" => 1, "msg" => "删除失败"];
                }
            }
        }else{
            return ["status"=>1,"msg"=>"非法操作"];
        }

    }
}
