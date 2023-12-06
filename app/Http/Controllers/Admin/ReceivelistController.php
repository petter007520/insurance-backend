<?php


namespace App\Http\Controllers\Admin;
    use App\Admin;
    use App\Site;
    use Carbon\Carbon;
    use DB;
    use App\Receivelist;
    use Illuminate\Http\Request;
    use Session;
    use Cache;
    use App\Loginlog;

class ReceivelistController extends BaseController
{

    private $table="receivelist";

     public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Models = new Receivelist();
    }

    public function index(Request $request){

        return redirect("admin/".$this->controller_name."/lists");

    }

    public function lists(Request $request){

        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }

    
        $list = DB::table($this->table)
            ->select($this->table.'.*')
            ->where(function ($query) {
                $s_key_name=[];
                if(isset($_REQUEST['s_key'])){
                    $s_key_name[]=[$this->table.".name","like","%".$_REQUEST['s_key']."%"];
                }

                $query->orwhere($s_key_name);
            })
            ->paginate($pagesize);


        if($request->ajax()){
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }
        
    }

    public function store(Request $request){


        if($request->isMethod("post")){

            $messages = [
                'name.required' => '联系名称不能为空!',
                'value.required' => '联系方式不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                'value' => "required",
            ], $messages);


            $Model = $this->Models;
            $Model->name = $request->get('name');
            $Model->value = $request->input('value');
            $Model->sort = $request->input('sort');
            $Model->thumb_url = $request->input('thumb_url');

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

            $messages = [
                'name.required' => '联系名称不能为空!',
                'value.required' => '联系方式不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                'value' => "required",
            ], $messages);

            $Model = $this->Models->find($request->get('id'));
            $Model->name = $request->input('name');
            $Model->value = $request->input('value');
            $Model->sort = $request->input('sort');
            $Model->thumb_url = $request->input('thumb_url');


            $Model->save();



            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }


        }else{


            $Model = $this->Models->find($request->get('id'));


            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
        }

    }



    public function delete(Request $request){




        if($request->ajax()) {
            if($request->input("id")){

                $Model = DB::table($this->table)
                    ->where(['id' => $request->input("id")])
                    ->first();
                if($Model){


                    $delete = DB::table($this->table)->where('id', '=', $request->input("id"))->delete();
                    if ($delete) {
                        return ["status" => 0, "msg" => "删除成功"];
                    } else {
                        return ["status" => 1, "msg" => "删除失败"];
                    }


                }else{
                    return ["status"=>1,"msg"=>"您没有权限删除其他操作"];
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


    public function setstatus(Request $request)
    {
        if($request->isMethod("post")){



            $Model = $this->Models::find($request->input('id'));

            $Model->status = $request->input('status');

            $Model->save();


            if($request->ajax()){
                return response()->json([
                    "msg"=>"操作成功","status"=>0
                ]);
            }
        }
    }

}
