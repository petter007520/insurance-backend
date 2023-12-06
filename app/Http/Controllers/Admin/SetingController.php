<?php

namespace App\Http\Controllers\Admin;
use App\Seting;
use Illuminate\Http\Request;
use Cache;
use Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Image;

class SetingController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
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
            $list = DB::table('setings')
                ->orderBy("sort","desc")
                ->paginate($pagesize);
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }

        }else{
            $list = DB::table('setings')
                ->paginate($pagesize);
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize,"status"=>0]);
        }

    }
    public function renwu(Request $request){


        $pagesize=100;//默认分页数





        if($request->ajax()){
            $list = DB::table('setings')
                ->where("is_rw",1)
                ->orderBy("sort","desc")
                ->paginate($pagesize);
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }

        }else{
            $list = DB::table('setings')
                ->where("is_rw",1)
                ->orderBy("sort","desc")
                ->paginate($pagesize);
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize,"status"=>0]);
        }

    }

    public function store(Request $request){


        if($request->isMethod("post")){

            $messages = [
                'name.required' => '名称不能为空!',
                'value.required' => '设置值不能为空!',
                'keyname.required' => '键名不能为空!',
                'type.required' => '类型不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                "value"=>"required",
                "keyname"=>"required",
                "type"=>"required",
            ], $messages);


            $Models = new Seting();
            $Models->name = $request->input('name');
            $Models->value = $request->input('value');
            $Models->valuelist = $request->input('valuelist');
            $Models->type = $request->input('type');
            $Models->sort = $request->input('sort');
            $Models->keyname = $request->input('keyname');
            $Models->save();
            Cache::flush();

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
                'value.required' => '设置值不能为空!',
                'type.required' => '类型不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                "value"=>"required",
                "type"=>"required",
            ], $messages);

            $Models = Seting::find($request->input('id'));
            $Models->name = $request->input('name');
            $Models->value = $request->input('value');
            $Models->valuelist = $request->input('valuelist');
            $Models->sort = $request->input('sort');
            $Models->keyname = $request->input('keyname');//关闭修改键名功能,键名是程序所调取的，一般是固定的，不可随便修改
            $Models->type = $request->input('type');
            $Models->save();
            Cache::flush();

            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }




        }else{

            $Models = Seting::find($request->get('id'));
            return $this->ShowTemplate(["edit"=>$Models,"status"=>0]);
        }

    }



    public function delete(Request $request){


        if($request->ajax()) {
            if($request->input("id")){

                $auth = DB::table('setings')
                    ->where(['id' => $request->input("id")])
                    ->first();
                if($auth){
                    if($auth->id>1) {
                        $delete = DB::table('setings')->where('id', '=', $request->input("id"))->delete();
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


    public function siteset(Request $request){
        if($request->isMethod("post")){

            if($request->input("id")==''){
                return ["status"=>1,"msg"=>"系统错误"];
            }

            if($request->input("setvalue")==''){
                return ["status"=>1,"msg"=>"系统错误"];
            }

            $Models = Seting::find($request->input('id'));
            if($request->input("checked")){
/*
                if($request->input('checked')=='true'){
                    $data_value=explode("|",$Models->value);
                    $data_value[]=$request->input('setvalue');
                    $data_str=implode("|",$data_value);
                    $Models->value=$data_str;
                }else{
                    $data_value=explode("|",$Models->value);
                    $data_s=[];
                    if(count($data_value)>0) {
                        foreach ($data_value as $v) {
                            if($v!=$request->input('setvalue')){
                                $data_s[]=$v;
                            }
                        }
                    }

                    $data_str=implode("|",$data_s);

                    $Models->value=$data_str;
                }*/


                //$data_value=explode("|",$Models->value);
                $data_value=$request->input('setvalue');
                $data_str=implode("|",$data_value);
                $Models->value=$data_str;

            }else{
                $Models->value = $request->input('setvalue');
            }

            $Models->save();
            Cache::flush();
            $list = Seting::where("is_rw",0)->orderBy("sort","desc")->get();
            if($request->ajax()){
                return ["status"=>0,"msg"=>"设置成功"];
            }else{
                return redirect(route($this->RouteController.'.siteset',["list"=>$list]))->with(["msg"=>"修改成功","status"=>0]);
            }


        }else{

            $Models = Seting::where("is_rw",0)->orderBy("sort","desc")->get();
            return $this->ShowTemplate(["list"=>$Models]);
        }
    }


    public function systemphotos(Request $request){
        if($request->isMethod("post")){

            if($request->input("id")==''){
                return ["status"=>1,"msg"=>"系统错误"];
            }

            if($request->input("setvalue")==''){
                return ["status"=>1,"msg"=>"系统错误"];
            }

            $Models = Seting::find($request->input('id'));

            $Models->value = implode(",",$request->input('setvalue'));


            $Models->save();
            Cache::flush();
            $list = Seting::orderBy("sort","desc")->get();
            if($request->ajax()){
                return ["status"=>0,"msg"=>"修改成功"];
            }else{
                return redirect(route($this->RouteController.'.siteset',["list"=>$list]))->with(["msg"=>"修改成功","status"=>0]);
            }


        }else{

            $Models = Seting::orderBy("sort","desc")->get();
            return $this->ShowTemplate(["list"=>$Models]);
        }
    }


    public function uplodeimg(Request $request)
    {

        if ($request->isMethod('post')) {
            $file = $request->file('files');
            // 文件是否上传成功
            if ($file->isValid()) {
                // 获取文件相关信息
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();     // image/jpeg

                $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
                // 使用我们新建的uploads本地存储空间（目录）
                $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
                $name = $request->get("name",'');
                if(!empty($name)){
                   DB::table('setings')->where(['keyname' => $name])->update(['value'=>'/uploads/'.$filename]);
                }
                return ["status"=>0 ,"msg"=>"上传成功","src"=>url("uploads/".$filename)];
            }

        }

    }

    public function uploadvideo(Request $request)
    {

        if ($request->isMethod('post')) {

            $file = $request->file('files');

            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();     // image/jpeg

                // 上传文件
                if($request->get("name")!=''){
                    $filename = $request->get("name");
                }else{
                    $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
                }


                // 使用我们新建的uploads本地存储空间（目录）
                $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));


                return ["status"=>0 ,"msg"=>"上传成功","src"=>url("uploads/".$filename)];

            }

        }

    }


}
