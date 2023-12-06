<?php


namespace App\Http\Controllers\Admin;
    use App\Grplist;
    use App\Grplog;
    use App\Extrabonustype;
    use App\Site;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Session;
    use Cache;
    use Storage;

class ExtrabonustypeController extends BaseController{
    
    private $table="extra_bonus_type";
    
     public function __construct(Request $request)
    {
        parent::__construct($request);
       $this->Models=new Extrabonustype();
    }



    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }
    
    //列表
    public function lists(Request $request){
        
        // $tel = '15659728467';
        // return encrypt($tel);
        
         $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }
        
        $list = DB::table($this->table)
            ->select($this->table.'.*')
            
            // ->orderBy($this->table.".id","desc")
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
                'min_money.required' => '金额不能为空!',
                'money.required' => '金额不能为空!',
            ];

            $result = $this->validate($request, [
                "money"=>"required",
                'min_money' => "required",
            ], $messages);

            $Model = $this->Models::find($request->input('id'));
            $Model->money = $request->input('money');
            $Model->min_money =$request->input('min_money');
            


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
    
    // 
    public function store(Request $request){
        
        if($request->isMethod("post")){

            $messages = [
                'min_money.required' => '金额不能为空!',
                'money.required' => '金额不能为空!',
                
            ];

            $result = $this->validate($request, [
                "min_money"=>"required",
                'money' => "required",
               
            ], $messages);


            $Model = $this->Models;
            $Model->min_money = $request->get('min_money');
            $Model->money =$request->input('money');

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