<?php


namespace App\Http\Controllers\Admin;

    use App\Jfshop;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Session;
    use Cache;


class JfshopController extends BaseController
{

    private $table="jfshops";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Jfshop();
        $modellist= config('model');
        view()->share("modellist",$modellist);
        $this->CategoryModel=new Category();
        $category_id=$request->s_categoryid;
        view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$category_id,0,$this->table));
    }



    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }




    public function lists(Request $request){




        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }



        isset($_REQUEST['s_categoryid'])?$s_categoryid=$_REQUEST['s_categoryid']:$s_categoryid=0;
        isset($_REQUEST['s_key'])?$s_key=$_REQUEST['s_key']:$s_key='';



        $listDB = DB::table($this->table)
            ->select($this->table.'.*')
           ->where(function ($query) {
                $s_key_name=[];
                $s_key_author=[];
                if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                    $s_key_name[]=[$this->table.".title","like","%".$_REQUEST['s_key']."%"];
                    $s_key_author[]=[$this->table.".author","=",$_REQUEST['s_key']];
                }


                $query->orwhere($s_key_name)->orwhere($s_key_author);
            })
            ->where(function ($query) {
                $s_category_id=[];
                if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']>0){
                    $s_category_id[]=[$this->table.".category_id","=",$_REQUEST['s_categoryid']];
                }

                $query->where($s_category_id);
            });


            $list=$listDB->orderBy($this->table.".sort","desc")->orderBy($this->table.".id","desc")
                ->paginate($pagesize);





        if($request->ajax()){
            if($list){
                $model=config('model');
                $modelname=[];
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{



            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }

    public function store(Request $request){

        if($request->isMethod("post")){


            $data=$request->all();
            $photos=isset($data['productimage'])?json_encode($data['productimage']):'';
            $data['photos']=$photos;
            unset($data['_token']);
            unset($data['thumb']);
            unset($data['file']);
            unset($data['productimage']);
            unset($data['editormd-image-file']);




            $data['title']=\App\Formatting::ToFormat($data['title']);
            $data['author']=\App\Formatting::ToFormat($data['author']);
            $data['keyinfo']=\App\Formatting::ToFormat($data['keyinfo']);
            $data['descr']=\App\Formatting::ToFormat($data['descr']);
            $data['content']=\App\Formatting::ToFormat($data['content']);

            $data['category_name']=$this->CategoryModel->where("id",$data['category_id'])->value('name');
            $data['created_at']=$data['updated_at']=Carbon::now();

            DB::table($this->table)->insert($data);


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
            $photos=isset($data['productimage'])?json_encode($data['productimage']):'';
            $id= $data['id'];
            $data['photos']=$photos;
            unset($data['_token']);
            unset($data['id']);
            unset($data['thumb']);
            unset($data['file']);
            unset($data['productimage']);
            unset($data['editormd-image-file']);


            $data['title']=\App\Formatting::ToFormat($data['title']);
            $data['author']=\App\Formatting::ToFormat($data['author']);
            $data['keyinfo']=\App\Formatting::ToFormat($data['keyinfo']);
            $data['descr']=\App\Formatting::ToFormat($data['descr']);
            $data['content']=\App\Formatting::ToFormat($data['content']);

            $data['category_name']=$this->CategoryModel->where("id",$data['category_id'])->value('name');
            $data['updated_at']=Carbon::now();

            DB::table($this->table)->where("id",$id)->update($data);


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

            $Model->status = $request->input('status');
            //$Model->top_time = Carbon::now();

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



}
