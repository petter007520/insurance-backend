<?php
namespace App\Http\Controllers\Admin;
use App\Advertisement;
use App\Advertisementdata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdvertisementdataController  extends  BaseController
{
    private $table="advertisementdatas";
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Models= new Advertisementdata();
        $this->AdMbModels= new Advertisement();
        view()->share("admb",$this->AdMbModels->get());
    }

    public function index(Request $request){
        return redirect("admin/".$this->controller_name."/lists");
    }

    public function lists(Request $request){
        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }
        $s_posid=[];
        if($request->input('posid')){
            $s_storeid[]=[$this->table.".adverid","=",$_REQUEST['posid']];
        }
        $list = DB::table($this->table)
            ->leftJoin('advertisements as adv', 'adv.id', '=', $this->table.'.adverid')
            ->select($this->table.'.*','adv.name as posname')

            ->where($s_posid)
            ->where(function ($query) {
                $s_key_name=[];
                if(isset($_REQUEST['s_key'])){
                    $s_key_name[]=[$this->table.".name","like","%".$_REQUEST['s_key']."%"];

                }

                $query->orwhere($s_key_name);
            })
            ->where(function ($query){
                $s_key_name=[];
                if(isset($_REQUEST['s_adverid']) && $_REQUEST['s_adverid']>0){
                    $s_key_name[]=[$this->table.".adverid","=",$_REQUEST['s_adverid']];
                }
                $query->where($s_key_name);
            })

            ->orderBy($this->table.".sort","desc")

            ->paginate($pagesize);
        //ajax 和普通请求返回不同数据类型 get_parent

        if($request->ajax()){
            if($list){

                foreach ($list as $item){
                    $item->category_name=$this->AdMbModels->where("id",$item->adverid)->value("name");
                }
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{
            $adver=  DB::table("advertisements")->orderBy("sort","desc")->get();
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize,"adver"=>$adver]);
        }

    }

    public function store(Request $request){


        if($request->isMethod("post")){



            $messages = [
                'name.required' => '名称不能为空!',
                'url.required' => '链接地址不能为空!',
                'adverid.required' => '广告模板ID不能为空!',

            ];

            $result = $this->validate($request, [
                "name"=>"required",
                "url"=>"required",
                "adverid"=>"required|numeric",
            ], $messages);


            if($request->input('adverid')<1){
                if ($request->ajax()) {
                    return response()->json([
                        "msg" => "模板不可为空请选择", "status" => 1
                    ]);
                } else {
                    return redirect(route($this->controller_name . '_store', ["id" => $request->input("id")]))->withErrors($request->all(), 'store')->with(["msg" => "模板不可为空请选择", "status" => 1]);
                }
            }


            $Position = Advertisement::where("id", $request->input('adverid'))->first();


            if(!$Position){
                if ($request->ajax()) {
                    return response()->json([
                        "msg" => "模板不存在", "status" => 1
                    ]);
                } else {
                    return redirect(route($this->controller_name . '_store', ["id" => $request->input("id")]))->withErrors($request->all(), 'store')->with(["msg" => "模板不存在", "status" => 1]);
                }
            }



            $Model = $this->Models;
            $Model->name = $request->get('name');
            $Model->sort = $request->input('sort');
            $Model->adverid = $request->input('adverid');

            $Model->url = $request->input('url');
            $Model->thumb_url = $request->input('thumb_url');

            $Model->title = $request->input('title');
            $Model->code = $request->input('code');

            $Model->description = $request->input('description');
            $Model->save();


            if($request->ajax()){
                if($request->input('adverid')==1){
                    Cache::forget('index_banner');
                }
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
                'adverid.required' => '模板ID不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                "adverid"=>"required|numeric",
            ], $messages);




            if($request->input('adverid')<1){
                if ($request->ajax()) {
                    return response()->json([
                        "msg" => "广告位不可为空请选择", "status" => 1
                    ]);
                } else {
                    return redirect(route($this->RouteController . '.update', ["id" => $request->input("id")]))->withErrors($request->all(), 'store')->with(["msg" => "广告位不可为空请选择", "status" => 1]);
                }
            }

            $Model = $this->Models->find($request->input('id'));


            if($Model->fid==0 && $request->input('url')==''){

                    if ($request->ajax()) {
                        return response()->json([
                            "msg" => "请设置URL链接地址", "status" => 1
                        ]);
                    } else {
                        return redirect(route($this->RouteController . '.update', ["id" => $request->input("id")]))->withErrors($request->all(), 'store')->with(["msg" => "请设置URL链接地址", "status" => 1]);
                    }


            }



            $Position = Advertisement::where("id", $request->input('adverid'))->first();


            if(!$Position){
                if ($request->ajax()) {
                    return response()->json([
                        "msg" => "广告模板不存在", "status" => 1
                    ]);
                } else {
                    return redirect(route($this->RouteController . '.update', ["id" => $request->input("id")]))->withErrors($request->all(), 'store')->with(["msg" => "广告模板不存在", "status" => 1]);
                }
            }




            $Model = $this->Models->find($request->input('id'));
            $Model->name = $request->get('name');
            $Model->sort = $request->input('sort');
            $Model->adverid = $request->input('adverid');
            $Model->code =$request->input('code');
            $Model->url = $request->input('url');
            $Model->thumb_url = $request->input('thumb_url');

            $Model->title = $request->input('title');

            $Model->description = $request->input('description');

            $Model->save();
            Cache::forget('index_banner');


            if($request->ajax()){
                if($request->input('adverid')==1){
                    Cache::forget('index_banner');
                }
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



    public function getposition(Request $request){


        if($request->isMethod("post")) {
            if($request->input("storeid")){
                $storeid[]=$request->input("storeid");
            }
                $storeid[]=0;


            $list = Advertisement::whereIn("storeid",$storeid)->orderBy("sort","desc")->get();

            return response()->json([
                "list" => $list, "status" => 0
            ]);
        }


    }







}



