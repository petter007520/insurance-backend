<?php
namespace App\Http\Controllers\Admin;
use App\Product;
use Carbon\Carbon;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
class ProductController extends BaseController
{
    private $table="products";

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Product();
        $modellist= config('model');
        view()->share("modellist",$modellist);
        $this->CategoryModel=new Category();
        $category_id=$request->s_categoryid;
        view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$category_id,0,$this->table));
        $productlist= DB::table("products")->select('id','title')
        ->get();
        view()->share("productlist",$productlist);
        if(Cache::has('memberlevel.list')){
            $memberlevel=Cache::get('memberlevel.list');
        }else{
            $memberlevel= DB::table("memberlevel")->orderBy("id","asc")->get();
            Cache::get('memberlevel.list',$memberlevel,Cache::get("cachetime"));
        }
        view()->share("memberlevel",$memberlevel);
    }

    public function index(Request $request){
        return redirect(route($this->RouteController.".lists"));
    }

    public function lists(Request $request){
        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }
        $listDB = DB::table($this->table)
            ->select($this->table.'.*')
           ->where(function ($query) {
                $s_key_name=[];
                $s_key_bljg=[];
                $s_key_content=[];
                if(isset($_REQUEST['s_key'])){
                    $s_key_name[]=[$this->table.".title","like","%".$_REQUEST['s_key']."%"];
                }
                $query->orwhere($s_key_name)->orwhere($s_key_bljg)->orwhere($s_key_content);
            })
            ->where(function ($query) {
                $s_siteid=[];
                if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']>0){
                    $s_siteid[]=[$this->table.".category_id","=",$_REQUEST['s_categoryid']];
                }
                $query->where($s_siteid);
            })->where(function ($query) {
                $s_status=[];
                if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                    $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                }
                $query->where($s_status);
            });
            $list=$listDB->orderBy($this->table.".sort","desc")
                ->orderBy($this->table.".id","desc")
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
            $data=$request->all();
            unset($data['_token']);
            unset($data['thumb']);
            unset($data['file']);
            unset($data['productimage']);
            unset($data['editormd-image-file']);
            unset($data['s']);
            $data['title']=\App\Formatting::ToFormat($data['title']);
            if(!empty($data['content'])){
                $data['content']=\App\Formatting::ToFormat($data['content']);
            }
            $data['category_name']=$this->CategoryModel->where("id",$data['category_id'])->value('name');
            $data['created_at']=Carbon::now();
            DB::table($this->table)->insertGetId($data);
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


            $data=$request->all();
            $id= $data['id'];
            unset($data['_token']);
            unset($data['id']);
            unset($data['thumb']);
            unset($data['file']);
            unset($data['productimage']);
            unset($data['editormd-image-file']);
            unset($data['s']);


            $data['title']=\App\Formatting::ToFormat($data['title']);
            if(!empty($data['content'])){
                $data['content']=\App\Formatting::ToFormat($data['content']);
            }
            $data['category_name']=$this->CategoryModel->where("id",$data['category_id'])->value('name');
            $data['updated_at']=Carbon::now();
            DB::table($this->table)->where(['id'=>$id])->update($data);
             Cache::forget("index_projects_".$data['category_id']);
            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }
        }else{
            $Model = $this->Model::find($request->get('id'));
            view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$Model->category_id,0,$this->table));
            view()->share("photos",json_decode($Model->photos));
            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
        }
    }

    public function settop(Request $request)
    {
        if($request->isMethod("post")){
            $Model = $this->Model::find($request->input('id'));
            $Model->issy = $request->input('top_status');
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

     protected function DateAdd($part, $number, $date){
        $date_array = getdate(strtotime($date));
        $hor = $date_array["hours"];
        $min = $date_array["minutes"];
        $sec = $date_array["seconds"];
        $mon = $date_array["mon"];
        $day = $date_array["mday"];
        $yar = $date_array["year"];
        switch($part){
            case "y": $yar += $number; break;
            case "q": $mon += ($number * 3); break;
            case "m": $mon += $number; break;
            case "w": $day += ($number * 7); break;
            case "d": $day += $number; break;
            case "h": $hor += $number; break;
            case "n": $min += $number; break;
            case "s": $sec += $number; break;
        }
        $FengHongDateFormat='Y-m-d H:i:s';
        return date($FengHongDateFormat, mktime($hor, $min, $sec, $mon, $day, $yar));
    }
}
