<?php
namespace App\Http\Controllers\Admin;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CategoryController extends BaseController
{
    private $table="category";

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Category();
        $modellist= config('model');
        view()->share("modellist",$modellist);
    }

    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }
    public function lists(Request $request){
        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }
        isset($_REQUEST['s_key'])?$s_key=$_REQUEST['s_key']:$s_key='';
        $listDB = DB::table($this->table)
            ->select($this->table.'.*')
           ->where(function ($query) {
                $s_key_name=[];
                if(isset($_REQUEST['s_key'])){
                    $s_key_name[]=[$this->table.".name","like","%".$_REQUEST['s_key']."%"];
                }
                $query->orwhere($s_key_name);
            })
            ->where(function ($query) {
                $s_model=[];
                if(isset($_REQUEST['s_model']) && $_REQUEST['s_model']!=''){
                    $s_model[]=[$this->table.".model","=",$_REQUEST['s_model']];
                }

                $query->where($s_model);
            })
            ->where("parent","0");
            $list=$listDB->orderBy($this->table.".sort","desc")
                ->paginate($pagesize);
        if($request->ajax()){
            if($list){
                $model=config('model');
                $modelname=[];
                foreach ($model as $v){
                    $modelname[$v['key']]=$v['name'];
                }
                foreach ($list as $v){
                    $v->modename=  isset($modelname[$v->model])?$modelname[$v->model]:'';
                    $v->list=$this->Model->tree($v->id,$s_key);
                }
                return ["status"=>0,"tree"=>1,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }
    }

    public function store(Request $request){

        if($request->isMethod("post")){
            $messages = [
                'name.required' => '名称不能为空!',
                'sort.required' => '排序不能为空!',
            ];
           $this->validate($request, [
                "name"=>"required",
                "sort"=>"required",
            ], $messages);
            if($request->input('model')!='links'){
               $conut= $this->Model::where("links",$request->input('links'))->count();
               if($conut>0){
                   if($request->ajax()){
                       return response()->json([
                           "msg"=>"目录名称已存在","status"=>1
                       ]);
                   }else{
                       return redirect(route($this->RouteController.'.store'))->with(["msg"=>"目录名称已存在","status"=>1]);
                   }
               }

            }

            $Model = $this->Model;
            $Model->name = $request->get('name');
            $Model->sort = $request->input('sort');
            $Model->thumb_url = $request->input('thumb_url');
            $Model->color = $request->input('color');
            $Model->model = $request->input('model');
            $Model->parent = $request->input('parent');
            $Model->links = $request->input('links');
            $Model->ctitle = \App\Formatting::ToFormat($request->input('ctitle'));
            $Model->ckeywords = \App\Formatting::ToFormat($request->input('ckeywords'));
            $Model->cdescription = \App\Formatting::ToFormat($request->input('cdescription'));
            $Model->ccontent = \App\Formatting::ToFormat($request->input('ccontent'));
            $Model->classname = $request->input('classname');
            $Model->save();


            if($request->ajax()){
                if($request->input('model') == 'products'){
                    Cache::forget('index_category');
                }
                return response()->json([
                    "msg"=>"添加成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.store'))->with(["msg"=>"添加成功","status"=>0]);
            }



        }else{

            view()->share("tree_option",$this->Model->tree_option());

            return $this->ShowTemplate();
        }

    }






    public function update(Request $request)
    {
        if($request->isMethod("post")){
            $messages = [
                'name.required' => '名称不能为空!',
                'sort.required' => '排序不能为空!',
            ];
            $this->validate($request, [
                "name"=>"required",
                "sort"=>"required",
            ], $messages);
            if($request->input('model')!='links'){
                $conut= $this->Model::where("links",$request->input('links'))->whereNotIn("id",[$request->input('id')])->count();
                if($conut>0){
                    if($request->ajax()){
                        return response()->json([
                            "msg"=>"目录名称已存在","status"=>1
                        ]);
                    }else{
                        return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"目录名称已存在","status"=>1]);
                    }
                }
            }
            $Model = $this->Model::find($request->input('id'));
            $Model->name = $request->get('name');
            $Model->sort = $request->input('sort');
            $Model->thumb_url = $request->input('thumb_url');
            $Model->model = $request->input('model');
            $Model->links = $request->input('links');
            $Model->parent = $request->input('parent');
            $Model->color = $request->input('color');
            $Model->ctitle = \App\Formatting::ToFormat($request->input('ctitle'));
            $Model->classname = $request->input('classname');
            $Model->ckeywords = \App\Formatting::ToFormat($request->input('ckeywords'));
            $Model->cdescription = \App\Formatting::ToFormat($request->input('cdescription'));
            $Model->ccontent = \App\Formatting::ToFormat($request->input('ccontent'));
            $Model->save();
            if($request->ajax()){
                if($request->input('model') == 'products'){
                    Cache::forget('index_category');
                }
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }
        }else{
            $Model = $this->Model::find($request->get('id'));
            view()->share("tree_option",$this->Model->tree_option(0,0,$Model->parent,$Model->id));
            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
        }
    }

    public function atindex(Request $request)
    {
        if($request->isMethod("post")){

            $data=$request->all();

            $Model = $this->Model::find($request->input('id'));
            if(isset($data['atindex'])){
                $Model->atindex = $request->input('atindex');
            }

            if(isset($data['atfoot'])){
                $Model->atfoot = $request->input('atfoot');
            }

            if(isset($data['ismenus'])){
                $Model->ismenus = $request->input('ismenus');
            }



            $Model->save();

            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
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


            }


        }else{
            return ["status"=>1,"msg"=>"非法操作"];
        }

    }



}
