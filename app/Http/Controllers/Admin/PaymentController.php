<?php


namespace App\Http\Controllers\Admin;
    use App\Payment;
    use DB;
    use Illuminate\Http\Request;
    use Session;
    use Cache;

class PaymentController extends BaseController
{
    private $table="payment";

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Payment();
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
            $listDB = DB::table($this->table)
                ->select($this->table.'.*');
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
            $Model->pay_name = $request->input('pay_name','');
            $Model->pay_code = $request->input('pay_code','');
            $Model->pay_desc = $request->input('pay_desc','');
            $Model->pay_pic = $request->input('pay_pic','');
            $Model->enabled = $request->input('enabled',0);
            $Model->bankname = $request->input('bankname','');
            $Model->bankcode = $request->input('bankcode','');
            $Model->bank_type = $request->input('bank_type','');
            $Model->bankrealname = $request->input('bankrealname','');
            $Model->pay_type = $request->input('pay_type',0);
            $Model->type = $request->input('type',0);
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
            DB::table($this->table)
               ->where('is_default', 1)
               ->update(['is_default' => 0]);
            $Model = $this->Model::find($request->input('id'));
            $Model->pay_name = $request->input('pay_name','');
            $Model->pay_code = $request->input('pay_code','');
            $Model->pay_desc = $request->input('pay_desc','');
            $Model->pay_pic = $request->input('pay_pic','');
            $Model->enabled = $request->input('enabled',0);
            $Model->bankname = $request->input('bankname','');
            $Model->bankcode = $request->input('bankcode','');
            $Model->bank_type = $request->input('bank_type','');
            $Model->bankrealname = $request->input('bankrealname','');
            $Model->pay_type = $request->input('pay_type',0);
            $Model->type = $request->input('type',0);
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
