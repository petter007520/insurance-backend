<?php


namespace App\Http\Controllers\Admin;
    use App\Site;
    use DB;
    use App\Link;
    use Illuminate\Http\Request;
    use Session;
    use Cache;

class LinkController extends BaseController
{

    private $table="links";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Link();
    }


    public function index(Request $request){

        return redirect("admin/".$this->controller_name."/lists");

    }




    public function lists(Request $request){

        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }


        $list = DB::table($this->table)
            ->select($this->table.'.*')
           ->where(function ($query) {
                $s_key_name=[];
                if(isset($_REQUEST['s_key'])){
                    $s_key_name[]=[$this->table.".name","like","%".$_REQUEST['s_key']."%"];
                }

                $query->orwhere($s_key_name);
            })
            ->orderBy($this->table.".sort","desc")
            ->paginate($pagesize);


        if($request->ajax()){
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{
            return $this->ShowTemplate($this->controller_name.".lists",["list"=>$list,"pagesize"=>$pagesize]);
        }

    }

    public function store(Request $request){

        if($request->isMethod("post")){
            $messages = [
                'name.required' => '名称不能为空!',
                'url.required' => '链接地址不能为空!',
            ];
            $result = $this->validate($request, [
                "name"=>"required",
                "url"=>"required",
            ], $messages);




            $Model = $this->Model;
            $Model->name = $request->get('name');
            $Model->sort = $request->input('sort');
            $Model->thumb_url = $request->input('thumb_url');
            $Model->color = $request->input('color');
            $Model->url = $request->input('url');
            $Model->adminid = $this->Admin->id;
            $Model->save();


            if($request->ajax()){
                return response()->json([
                    "msg"=>"添加成功","status"=>0
                ]);
            }else{
                return redirect(route($this->controller_name.'_store'))->with(["msg"=>"添加成功","status"=>0]);
            }



        }else{
            return $this->ShowTemplate($this->controller_name.".store");
        }

    }






    public function update(Request $request)
    {
        if($request->isMethod("post")){

            $messages = [
                'name.required' => '名称不能为空!',
                'url.required' => '链接地址不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                "url"=>"required",
            ], $messages);


            $Model = $this->Model::find($request->input('id'));
            $Model->name = $request->get('name');
            $Model->sort = $request->input('sort');
            $Model->thumb_url = $request->input('thumb_url');
            $Model->url = $request->input('url');
            $Model->color = $request->input('color');
            $Model->adminid = $this->Admin->id;
            $Model->save();



            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->controller_name.'_update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }


        }else{


            $Model = $this->Model::find($request->get('id'));

            return $this->ShowTemplate($this->controller_name.".update",["edit"=>$Model,"status"=>0]);
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
