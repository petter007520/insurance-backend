<?php


namespace App\Http\Controllers\Admin;
    use App\Memberbank;
    use DB;
    use Illuminate\Http\Request;
    use Session;
    use Cache;


class MemberbankController extends BaseController
{
    private $table="memberbank";

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Memberbank();
    }

    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }

    public function lists(Request $request){
        $adminAuthID =$request->session()->get('adminAuthID');
        $adminID =$request->session()->get('adminID');
        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }

        if($request->ajax()){
            $listDB = DB::table($this->table)
                ->leftjoin('member as me' ,'me.id','=',$this->table.'.userid')
                ->where(function ($query) {
                    $s_siteid=[];
                    if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                        $s_siteid[]=["me.username","=",$_REQUEST['s_key']];
                    }
                    $query->where($s_siteid);
                })
                ->select($this->table.'.*','me.username');

            $list=$listDB->orderBy($this->table.".id","asc")
                ->paginate($pagesize);
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{
            return $this->ShowTemplate([]);
        }
    }

    public function store(Request $request){
        if($request->isMethod("post")){
            $Model = $this->Model;
            $Model->name = $request->input('name');
            $Model->rate = $request->input('rate');
            $Model->inte = $request->input('inte');
            $Model->wheels = $request->input('wheels');
            $Model->offlines = $request->input('offlines');
            $Model->save();
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
            $Model = $this->Model::find($request->input('id'));
            $Model->bankname = $request->input('bankname');
            $Model->type = $request->input('type',1);
            $Model->bankrealname = $request->input('bankrealname');
            $Model->bankcode = $request->input('bankcode');
            $Model->bankaddress = $request->input('bankaddress');
            $Model->address = $request->input('address','');
            $Model->save();
            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }
        }else{
            $Model = $this->Model::find($request->get('id'));
            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
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
