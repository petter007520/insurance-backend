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

class MoneylogController extends BaseController
{

    private $table="moneylog";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=DB::table($this->table);

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
        if($request->s_category_id!=''){
            $Where=[["moneylog_type",$request->s_category_id]];
        }

        $Where2=[];
        if($request->s_status!=''){
            $Where2=[["moneylog_status",$request->s_status]];
        }
        $Where3=[];
        if($request->s_key!=''){
            $Where3=[["moneylog_user",$request->s_key]];
        }
        $list=[];
        // dump($Where);
        //  dump($Where3);
        // exit;
        if($request->ajax()){

            $list = DB::table($this->table)
                ->where($Where)
                ->where($Where2)
                ->where($Where3)
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
