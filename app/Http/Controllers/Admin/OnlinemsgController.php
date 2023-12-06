<?php


namespace App\Http\Controllers\Admin;
    use App\Article;
    use App\Celebrity;
    use App\Site;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Session;
    use Cache;


class OnlinemsgController extends BaseController
{

    private $table="onlinemsg";


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


        isset($_REQUEST['s_key'])?$s_key=$_REQUEST['s_key']:$s_key='';



        $listDB = DB::table($this->table)
            ->select($this->table.'.*')
           ->where(function ($query) {
                $s_key_name=[];
                $s_key_msg=[];
                if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                    $s_key_name[]=[$this->table.".name","like","%".$_REQUEST['s_key']."%"];
                    $s_key_msg[]=[$this->table.".msg","like","%".$_REQUEST['s_key']."%"];
                }

                $query->orwhere($s_key_name)->orwhere($s_key_msg);
            });

            $list=$listDB->orderBy($this->table.".id","desc")
                ->paginate($pagesize);

        if($request->ajax()){
            if($list){
                $model=config('model');
                $modelname=[];
                foreach ($list as $item){
                    $item->imgs = explode(',',$item->img);
                }
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{

            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }

    public function store(Request $request){

    }

    public function update(Request $request)
    {

    }

    public function settop(Request $request)
    {
        if($request->isMethod("post")){
          $status=  DB::table($this->table)->where(['id' => $request->input("id")])->value('status');

            $date['status'] = $status==1?0:1;
            $date['updated_at'] = Carbon::now();


            DB::table($this->table)->where(['id' => $request->input("id")])->update($date);



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



}
