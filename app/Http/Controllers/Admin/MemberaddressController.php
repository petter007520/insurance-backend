<?php

namespace App\Http\Controllers\Admin;
    use App\Grplist;
    use App\Grplog;
    use App\Site;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Session;
    use Cache;
    use Storage;

class MemberaddressController extends BaseController{
    
    private $table="memberaddress";
    
     public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Models=new Grplist();
        
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
            ->leftjoin('member as me' ,'me.id','=',$this->table.'.userid')
            ->select($this->table.'.*','me.username')
            ->where(function ($query) {
                $s_key=[];
                if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                    $s_key[]=["me.username","=",$_REQUEST['s_key']];
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
    
   
    
    
}