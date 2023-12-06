<?php


namespace App\Http\Controllers\Admin;
    use App\Article;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use Illuminate\Http\Request;
    use Session;
    use Cache;
    use Storage;

class ArticleController extends BaseController
{

    private $table="articles";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Article();
        $modellist= config('model');
        view()->share("modellist",$modellist);
        $this->CategoryModel=new Category();
        $category_id=$request->s_categoryid;
        view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$category_id,0,'articles',['ismenus'=>1]));
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
         //   ->where(['status'=>2])
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
            unset($data['files']);
            unset($data['productimage']);
            unset($data['editormd-image-file']);
            unset($data['s']);




            $data['title']=\App\Formatting::ToFormat($data['title']);
            // $data['author']=\App\Formatting::ToFormat($data['author']);
            $data['keyinfo']=\App\Formatting::ToFormat($data['keyinfo']);
            $data['descr']=\App\Formatting::ToFormat($data['descr']);
            $data['content']=\App\Formatting::ToFormat($data['content']??'');
            // $data['video_url']=\App\Formatting::ToFormat($data['video_url']);

            $data['category_name']=$this->CategoryModel->where("id",$data['category_id'])->value('name');
            $data['created_at']=$data['updated_at']=Carbon::now();

            DB::table("articles")->insert($data);


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
            unset($data['files']);
            unset($data['productimage']);
            unset($data['editormd-image-file']);
            unset($data['s']);


            $data['title']=\App\Formatting::ToFormat($data['title']);
            // $data['author']=\App\Formatting::ToFormat($data['author']);
            $data['keyinfo']=\App\Formatting::ToFormat($data['keyinfo']);
            $data['descr']=\App\Formatting::ToFormat($data['descr']);
            $data['content']=\App\Formatting::ToFormat($data['content']??'');
            // $data['video_url']=\App\Formatting::ToFormat($data['video_url']);
            // $data['category_name']=$this->CategoryModel->where("id",$data['category_id'])->value('name');
            $data['updated_at']=Carbon::now();

            DB::table("articles")->where("id",$id)->update($data);
            //20为首页的弹出公告 34首页公告
            if($data['category_id'] == 7 && $data['title'] == '首页弹出公告'){
                Cache::forget('index_notice');
            }
            if($data['category_id'] == 7 && $data['title'] == '首页滚动公告'){
                Cache::forget('index_scroll');
            }
            Cache::forget('articles_detail_'.$id);

            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }
        }else{


            $Model = $this->Model::find($request->get('id'));

            view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$Model->category_id,0,'articles'));
            view()->share("photos",json_decode($Model->photos));

            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
        }

    }

    public function settop(Request $request)
    {
        if($request->isMethod("post")){



            $Model = $this->Model::find($request->input('id'));

            $Model->top_status = $request->input('top_status');
            $Model->top_time = Carbon::now();

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

    public function uploadvideo(Request $request)
    {

        if ($request->isMethod('post')) {

            $file = $request->file('files');

            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();     // image/jpeg

                // 上传文件
                if($request->get("name")!=''){
                    $filename = $request->get("name");
                }else{
                    // $filename = uniqid() . '.' . $ext;
                    $filename = 'videos/art/' . uniqid() . '.' . $ext;
                }


                // 使用我们新建的uploads本地存储空间（目录）
                $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));

                return ["status"=>0 ,"msg"=>"上传成功","src"=>"/uploads/".$filename];

            }

        }

    }

}
