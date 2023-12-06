<?php


namespace App\Http\Controllers\Admin;
    use App\Article;
    use App\Celebrity;
    use App\Loginlog;
    use App\Membermsg;
    use App\Site;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Session;
    use Cache;


class MembermsgController extends BaseController
{

    private $table="membermsg";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Membermsg();

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
               $s_username=[];
               $s_title=[];
               $s_content=[];

                if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                    $s_username[]=[$this->table.".username","like","%".$_REQUEST['s_key']."%"];
                    $s_title[]=[$this->table.".title","like","%".$_REQUEST['s_key']."%"];
                    $s_content[]=[$this->table.".content","like","%".$_REQUEST['s_key']."%"];

                }

                $query->orwhere($s_username)->orwhere($s_title)->orwhere($s_content);
            })

            ->where(function ($query) {
                $s_types=[];
                if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']!=''){
                    $s_types[]=[$this->table.".types","=",$_REQUEST['s_categoryid']];
                }

                $query->where($s_types);
            });

            $list=$listDB->orderBy($this->table.".id","desc")
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
                'userid.required' => '会员不能为空!',
                'title.required' => '标题不能为空!',
                'content.required' => '内容不能为空!',
            ];
            $result = $this->validate($request, [
                "userid"=>"required",
                "title"=>"required",
                "content"=>"required",
            ], $messages);



            if($request->get('userid')==0){
                $members= DB::table("member")->where("state","1")->get();

                foreach ($members as $mbmber){


                    $Model = new Membermsg();
                    $Model->userid = $mbmber->id;
                    $Model->username = $mbmber->username;
                    $Model->types = $request->input('types');
                    $Model->title = $request->input('title');
                    $Model->content = $request->input('content');

                    $Model->save();

                }

            }else{

                $Model = $this->Model;
                $Model->userid = $request->get('userid');
                $Model->username = DB::table("member")->where("id",$request->get('userid'))->value("username");
                $Model->types = $request->input('types');
                $Model->title = $request->input('title');
                $Model->content = $request->input('content');

                $Model->save();
            }




            if($request->ajax()){
                return response()->json([
                    "msg"=>"发送成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.store'))->with(["msg"=>"添加成功","status"=>0]);
            }



        }else{

            $member= DB::table("member")->where("state","1")->get();

            return $this->ShowTemplate(["member"=>$member]);
        }

    }






    public function update(Request $request)
    {


    }

    public function settop(Request $request)
    {
        if($request->isMethod("post")){
          $status=  DB::table($this->table)->where(['id' => $request->input("id")])->value('status');

            $date['status'] = $status==1?0:1;
            $date['hfdate'] = Carbon::now();


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
