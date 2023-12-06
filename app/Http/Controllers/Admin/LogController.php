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
    use App\Log;

class LogController extends BaseController
{

    private $table="logs";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Log();

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
                ->where(function ($query) {
                    $s_key_name=[];
                    $s_key_title=[];

                    if(isset($_REQUEST['s_key'])){
                        $s_key_name[]=[$this->table.".username","like","%".$_REQUEST['s_key']."%"];
                        $s_key_title[]=[$this->table.".title","like","%".$_REQUEST['s_key']."%"];

                    }

                    $query->orwhere($s_key_name)->orwhere($s_key_title);
                })
                ->where(function ($query) {
                    $s_key_name=[];

                    if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']!=''){
                        $s_key_name[]=[$this->table.".type","=",$_REQUEST['s_categoryid']];

                    }

                    $query->orwhere($s_key_name);
                })
                ->where($Where)
                ->orderBy( "id", "desc")
                ->paginate($pagesize);


            if($list){


                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }

    public function delete(Request $request)
    {

        // if ($request->ajax()) {
        //     if ($request->input("ids")) {

        //             $delete = DB::table($this->table)->whereIn('id',  $request->input("ids"))->delete();
        //             if ($delete) {
        //                 return ["status" => 0, "msg" => "删除成功"];
        //             } else {
        //                 return ["status" => 1, "msg" => "删除失败"];
        //             }

        //     }


        // } else {
        //     return ["status" => 1, "msg" => "非法操作"];
        // }
    }



}
