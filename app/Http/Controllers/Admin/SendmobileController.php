<?php


namespace App\Http\Controllers\Admin;
    use App\Article;
    use App\Celebrity;
    use App\Loginlog;
    use App\Sendmobile;
    use App\Site;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Session;
    use Cache;


class SendmobileController extends BaseController
{

    private $table="sendmobile";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Sendmobile();

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
                $s_types=[];
                if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']!=''){
                    $s_types[]=[$this->table.".action","=",$_REQUEST['s_categoryid']];
                }

                $query->where($s_types);
            });

            $list=$listDB->orderBy($this->table.".id","desc")
                ->paginate($pagesize);





        if($request->ajax()){
            if($list){
                foreach ($list as $item){

                    $item->typeName=\App\Smstmp::GetTypeName($item->action);

                    $item->Showmobile=\App\Member::DecryptPassWord($item->mobile);

                }
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{



            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }

    public function store(Request $request){
        if($request->isMethod("post")){



            if($request->sendtype==2 && $request->contents==''){
                return response()->json([
                    "msg"=>"短信内容不可以为空","status"=>1
                ]);
            }



            if($request->get('userid')==0){
                $members= DB::table("member")->where("state","1")->get();
                //sendtype category_id content
                foreach ($members as $mbmber){
                    if($request->sendtype==1){
                         \App\Sendmobile::SendUid($mbmber->id,$request->category_id);//短信通知
                    }else{
                         \App\Sendmobile::SendUContent($mbmber->id,$request->contents);//短信通知
                    }

                }

            }else{
                if($request->sendtype==1){
                     \App\Sendmobile::SendUid($request->userid,$request->category_id);//短信通知
                }else{
                     \App\Sendmobile::SendUContent($request->userid,$request->contents);//短信通知
                }
            }




            if($request->ajax()){
                return response()->json([
                    "msg"=>"发送成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.store'))->with(["msg"=>"发送成功","status"=>0]);
            }



        }else{

            $member= DB::table("member")->where("state","1")->get();

            return $this->ShowTemplate(["member"=>$member]);
        }

    }






    public function update(Request $request)
    {


        if($request->isMethod("post")){



            $Model = $this->Model::find($request->get('id'));
            $Model->result='已重新发送';
            $Model->save();
            \App\Sendmobile::SendPhone(\App\Member::DecryptPassWord($Model->mobile),$Model->action);//短信通知




            if($request->ajax()){
                return response()->json([
                    "msg"=>"重新发送成功","status"=>0
                ]);
            }


        }

       //return $this->Model->find($request->id);

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
