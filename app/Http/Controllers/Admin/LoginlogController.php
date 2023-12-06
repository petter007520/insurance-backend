<?php


namespace App\Http\Controllers\Admin;
    use App\Admin;
    use App\Site;
    use Carbon\Carbon;
    use DB;
    use App\Storage;
    use App\Product;
    use Illuminate\Http\Request;
    use Session;
    use Cache;
    use App\Loginlog;

class LoginlogController extends BaseController
{

    private $table="loginlogs";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Loginlog();

    }



    public function init(){

    }



    public function lists(Request $request){

        $this->init();
        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }
        $Where=[];
        if($this->Admin->authid>1){
            //$Where=[["admins.adminid",$this->Admin->id]];
        }
        $list=[];
        if($request->ajax()){

            $list = DB::table($this->table)
                ->leftjoin("admins","admins.id","=",$this->table.".adminid")
                ->select($this->table.".*","admins.username as AdminName")
                ->where($Where)
                ->orderBy( "id", "desc")
                ->paginate($pagesize);


            if($list){

                /*foreach($list as $item){
                    $item->AdminName = Admin::find($item->adminid)->value("username");
                }*/
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }

    public function delete(Request $request)
    {

        if ($request->ajax()) {
            if ($request->input("ids")) {

                    $delete = DB::table($this->table)->whereIn('id',  $request->input("ids"))->delete();
                    if ($delete) {
                        return ["status" => 0, "msg" => "删除成功"];
                    } else {
                        return ["status" => 1, "msg" => "删除失败"];
                    }

            }


        } else {
            return ["status" => 1, "msg" => "非法操作"];
        }
    }



}
