<?php


namespace App\Http\Controllers\Admin;
    use App\Administrators;
    use App\Site;
    use App\Admin;
    use DB;
    use App\Category;
    use Illuminate\Http\Request;
    use Session;
    use Cache;

class SiteController extends BaseController
{

    private $table="sites";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Site();
        $modellist= config('model');
        view()->share("modellist",$modellist);

       $TemplateList=json_decode(Cache::get('TemplateList'),true);

        view()->share("TemplateList",$TemplateList);


    }


    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }




    public function lists(Request $request){


        $adminAuthID =$request->session()->get('adminAuthID');
        $adminID =$request->session()->get('adminID');

        $Where=[];
        if($adminAuthID>1){
            $Where=["adminid"=>$adminID];
        }


        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }


        $listDB = DB::table($this->table)
            ->select($this->table.'.*')
           ->where(function ($query) {
                $s_key_name=[];
                if(isset($_REQUEST['s_key'])){
                    $s_key_name[]=[$this->table.".name","like","%".$_REQUEST['s_key']."%"];
                }

                $query->orwhere($s_key_name);
            });

        if($adminAuthID>1) {
            $list = $listDB->where($Where)->orderBy($this->table . ".sort", "desc")
                ->paginate($pagesize);
        }else{
            $list = $listDB->orderBy($this->table . ".sort", "desc")
                ->paginate($pagesize);
        }

        $Administrator=new Administrators();

        $Admins= $Administrator->getAdmin();
        $AdminName=[];
        foreach ($Admins as $v){
            $AdminName[$v->id]= $v->name .'['. $v->username .']';
        }

        if($request->ajax()){
            if($list){
                foreach($list as $v){
                    isset($AdminName[$v->adminid])?$v->AdminName=$AdminName[$v->adminid]:'';
                }
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }

    public function store(Request $request){

        if($request->isMethod("post")){


          $domain=  DB::table($this->table)->where("domain",$request->input('domain'))->count();
          if($domain){
              return response()->json([
                  "msg"=>$request->input('domain')."已存在,请换个域名","status"=>1
              ]);
          }

            $Model = $this->Model;
            $Model->name = $request->get('name');
            $Model->sort = $request->input('sort');
            $Model->domain = $request->input('domain');
            $Model->logo = $request->input('logo');
            $Model->template = $request->input('template');
            $Model->seotitle = $request->input('seotitle');
            $Model->keywords = $request->input('keywords');
            $Model->description = $request->input('description');
            $Model->adminid = $request->input('adminid');
            $Model->disabled = $request->input('disabled')=='on'?0:1;
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


            $domain=  DB::table($this->table)->where("domain",$request->input('domain'))->where("id","<>",$request->input('id'))->count();
            if($domain){
                return response()->json([
                    "msg"=>$request->input('domain')."已存在,请换个域名","status"=>1
                ]);
            }

            $Model = $this->Model::find($request->input('id'));
            $Model->name = $request->get('name');
            $Model->sort = $request->input('sort');
            $Model->domain = $request->input('domain');
            $Model->logo = $request->input('logo');
            $Model->template = $request->input('template');
            $Model->seotitle = $request->input('seotitle');
            $Model->keywords = $request->input('keywords');
            $Model->description = $request->input('description');
            $Model->adminid = $request->input('adminid');
            $Model->disabled = $request->input('disabled')=='on'?0:1;

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
