<?php


namespace App\Http\Controllers\Admin;
use App\Store;
use DB;
use App\Advertisement;
use App\Productclassify;
use Illuminate\Http\Request;
use Session;
use Cache;

class AdvertisementController extends   BaseController
{


    private $table="advertisements";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Models= new Advertisement();
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

            ->where(function ($query) {
                $s_key_name=[];
                $s_key_price=[];
                if(isset($_REQUEST['s_key'])){
                    $s_key_name[]=[$this->table.".name","like","%".$_REQUEST['s_key']."%"];

                }

                $query->orwhere($s_key_name);
            })

            ->orderBy($this->table.".sort","desc")

            ->paginate($pagesize);
        //ajax 和普通请求返回不同数据类型 get_parent

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
                'name.required' => '名称不能为空!',
                'maxnum.required' => '最大保存数不能为空!',
                'maxnum.numeric' => '最大保存数为整数!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",                
                "maxnum"=>"required|numeric",
            ], $messages);




            $Model =$this->Models;
            $Model->name = $request->get('name');
            $Model->sort = $request->input('sort');

            $Model->modelname = $request->input('modelname');
            $Model->maxnum = $request->input('maxnum');
            $Model->thumb_url = $request->input('thumb_url');

            $Model->extention = $request->input('extention');
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
                'name.required' => '名称不能为空!',
                'maxnum.required' => '最大保存数不能为空!',
                'maxnum.numeric' => '最大保存数为整数!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                "maxnum"=>"required|numeric",
            ], $messages);

            $Model = Advertisement::find($request->input('id'));





            $Model = $this->Models->find($request->input('id'));
            $Model->name = $request->get('name');
            $Model->sort = $request->input('sort');
            $Model->modelname = $request->input('modelname');
            $Model->maxnum = $request->input('maxnum');
            $Model->thumb_url = $request->input('thumb_url');
            $Model->extention = $request->input('extention');
            $Model->save();



            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }


        }else{


            $Model = Advertisement::find($request->get('id'));


            return $this->ShowTemplate(["edit"=>$Model]);
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
                    return ["status"=>1,"msg"=>"系统不允许您的删除操作"];
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



