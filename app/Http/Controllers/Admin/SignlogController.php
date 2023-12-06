<?php


namespace App\Http\Controllers\Admin;
    use App\Grplist;
    use App\Grplog;
    use App\Site;
    use App\Signlog;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Session;
    use Cache;
    use Storage;

class SignlogController extends BaseController{
    
    private $table="signlog";
    
     public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Models=new Signlog();
            
    }



    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }
    
    //红包发布列表
    public function lists(Request $request){
        
        // $tel = '15659728467';
        // return encrypt($tel);
        
         $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }
        
        $list = DB::table($this->table)
            ->select($this->table.'.*')
            ->where(function ($query) {
                $s_key=[];
                if(isset($_REQUEST['s_key'])){
                    $s_key[]=[$this->table.".username","like","%".$_REQUEST['s_key']."%"];
                }

                $query->orwhere($s_key);
            })
            ->orderBy($this->table.".id","desc")
            ->paginate($pagesize);
        

        if($request->ajax()){
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{

            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }
    
   
    //红包修改
    public function update(Request $request){
        
        if($request->isMethod("post")){

            $messages = [
                'name.required' => '名称不能为空!',
                'value.required' => '金额不能为空!',
                'weight.required' => '权重不能为空!',
                'stock.required' => '库存不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                'value' => "required",
                'weight' => "required",
                "stock"=>"required",
                "type"=>"required",
            ], $messages);

            $Model = $this->Models::find($request->input('id'));
            $Model->name = $request->get('name');
            $Model->value =$request->input('value');
            $Model->weight = $request->input('weight');
            $Model->stock = $request->input('stock');
            $Model->type = $request->input('type');


            $Model->save();

            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }

        }else{
            
            $Model = $this->Models::find($request->get('id'));

            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
        }

        
    }
    
    // 增加红包活动
    public function store(Request $request){
        
        if($request->isMethod("post")){

            $messages = [
                'name.required' => '名称不能为空!',
                'value.required' => '金额不能为空!',
                'weight.required' => '权重不能为空!',
                'stock.required' => '库存不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
                'value' => "required",
                'weight' => "required",
                "stock"=>"required",

            ], $messages);


            $Model = $this->Models;
            $Model->name = $request->get('name');
            $Model->value =$request->input('value');
            $Model->weight = $request->input('weight');
            $Model->stock = $request->input('stock');
            $Model->type = $request->input('type');

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

    
    
    
    //活动显示与隐藏
    public function hide(Request $request){
        
        if($request->isMethod("post")){



            $Model = $this->Models::find($request->input('id'));

            $Model->status = $request->input('status');
            

            $Model->save();



            if($request->ajax()){
                return response()->json([
                    "msg"=>"操作成功","status"=>0
                ]);
            }


        }
    }
    
    
}