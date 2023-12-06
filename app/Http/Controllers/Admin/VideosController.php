<?php


namespace App\Http\Controllers\Admin;

use App\Store;
use Carbon\Carbon;
use DB;
use App\Videos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Cache;
use Session;
use App\Category;
use Image;
use Storage;
use App\RouteURL;

class VideosController extends  BaseController
{


    private $table="videos";

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Models= new Videos();
         $this->CategoryModel=new Category();
        $category_id=$request->s_categoryid;
        view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$category_id,0,$this->table));
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
                    $s_key_name[]=[$this->table.".videos_name","like","%".$_REQUEST['s_key']."%"];
                }

                $query->orwhere($s_key_name);
            })
            ->orderBy($this->table.".sort","desc")
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
                'name.required' => '名称不能为空!',
                'title.required' => '标题不能为空!',
                'thumb_url.required' => '视频图片不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                'title' => "required",
                "thumb_url"=>"required",
            ], $messages);

            $Model = $this->Models;
            $Model->videos_name = $request->get('name');
            $Model->sort = $request->input('sort');
            
            $Model->video_url = $request->input('video_url');
            $Model->thumb_url = $request->input('thumb_url');
            $Model->video_category = $request->input('category_id');
            $Model->title = $request->input('title');
            $Model->code = $request->input('code');            

            $Model->description = $request->input('description');
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
                'title.required' => '标题不能为空!',
                'thumb_url.required' => '视频图片不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                'title' => "required",
                "thumb_url"=>"required",
            ], $messages);
            
             
            $Model = $this->Models->find($request->input('id'));
            $Model->videos_name = $request->get('name');
            $Model->sort = $request->input('sort');
            $Model->code =$request->input('code');
            $Model->video_url = $request->input('video_url');
            $Model->thumb_url = $request->input('thumb_url');
            $Model->video_category = $request->input('category_id');
            $Model->title = $request->input('title');
           
            $Model->description = $request->input('description');

            $Model->save();
            Cache::forget('video_id_'.$request->input('id'));

            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }

        }else{
            
            $Model = $this->Models->find($request->get('id'));
            view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$Model->video_category,0,$this->table));

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
                    // $filename = uniqid() . '.' . $ext;
                    $filename = 'videos/' . time().uniqid() . '.' . $ext;
                }


                // 使用我们新建的uploads本地存储空间（目录）
                $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));
            
                return ["status"=>0 ,"msg"=>"上传成功","src"=>"/uploads/".$filename];

            }

        }

    }

}



