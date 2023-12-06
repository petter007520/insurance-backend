<?php


namespace App\Http\Controllers\Admin;
    use App\Grplist;
    use App\Grplog;
    use App\Site;
    use App\Teamrewards;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Session;
    use Cache;
    use Storage;

class TeamrewardsController extends BaseController{
    
    private $table="teamrewards";
    
     public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Models=new Teamrewards();
        
        // if(\Illuminate\Support\Facades\Cache::has('product.list')){
        //     $productlist=Cache::get('product.list');
        // }else{
        //     $productlist= DB::table("products")->select('id','title')->where(['tzzt'=>0])->get();
        //     Cache::get('product.list',$productlist,Cache::get("cachetime"));
        // }
        $productlist= DB::table("products")->select('id','title')->where(['category_id'=>12])->get();
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
            $p_id = $request->input('p_id');
            $messages = [
                'team_num.required' => '团队认购人数不能为空!',
                'team_amount.required' => '金额不能为空!',
                'reward_amount.required' => '比例不能为空!',
                'reward_equ_num.required' => '库存不能为空!',
            ];

            $result = $this->validate($request, [
                "team_num"=>"required",
                'team_amount' => "required",
                'reward_amount' => "required",
                "reward_equ_num"=>"required",
            ], $messages);

            $Model = $this->Models::find($request->input('id'));
            $Model->team_num =$request->input('team_num');
            $Model->team_amount = $request->input('team_amount');
            $Model->reward_amount = $request->input('reward_amount');
            $Model->reward_equ_num = $request->input('reward_equ_num');
            $Model->reward_equ_pid= $p_id;
            $title= DB::table("products")->where(['id'=>$p_id])->value('title');
            $Model->reward_equ= $title;
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