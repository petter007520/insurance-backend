<?php

namespace App\Http\Controllers\Admin;
    use App\Grplist;
    use App\Grplog;
    use App\Site;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use App\Dividend;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Session;
    use Cache;
    use Storage;

class DividendController extends BaseController{
    
    private $table="dividend_type";
    
     public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Models=new Dividend();
        
        if(\Illuminate\Support\Facades\Cache::has('product.list')){
            $productlist=Cache::get('product.list');
        }else{
            $productlist= DB::table("products")->select('id','title')->where(['tzzt'=>0])->get();
            Cache::get('product.list',$productlist,Cache::get("cachetime"));
        }
        view()->share("productlist",$productlist);    
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
           
            ->orderBy($this->table.".id","asc")
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
                'type_name.required' => '收益周期不能为空!',
                "dividend_day"=>"周期天数不能为空",
                "dividend_ratio"=>"比例不能为空",
            ];

            $result = $this->validate($request, [
                "type_name"=>"required",
                "dividend_day"=>"required",
                "dividend_ratio"=>"required",
            ], $messages);

            $Model = $this->Models::find($request->input('id'));
            $Model->type_name =$request->input('type_name');
            $Model->dividend_day =$request->input('dividend_day');
            $Model->dividend_ratio =$request->input('dividend_ratio');


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
    
}