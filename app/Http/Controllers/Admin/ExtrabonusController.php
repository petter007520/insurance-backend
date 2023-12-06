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

class ExtrabonusController extends BaseController{
    
    private $table="extra_bonus";
    
     public function __construct(Request $request)
    {
        parent::__construct($request);
        // $this->Models=new Grplog();
        //  $this->AdMbModels= new Grplist();
        // view()->share("admb",$this->AdMbModels->get());
            
    }



    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }
    
     // 领取详情
    public function lists(Request $request){

        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }


        $list = DB::table($this->table)
            // ->leftJoin('grplist as g','g.id', '=', $this->table.'.grp_id')
            // ->select($this->table.'.*','g.type')
            // ->where(['activity_id'=>'1'])
            ->where(function ($query) {
                $s_key_name=[];
               if(isset($_REQUEST['s_key']) && $_REQUEST['s_key'] !=''){
                    $s_key_name[]=["username","like","%".$_REQUEST['s_key']."%"];
                }

                $query->orwhere($s_key_name);
            })
            // ->where(function ($query){
            //     $s_key_name=[];
            //     if(isset($_REQUEST['s_grptype']) && $_REQUEST['s_grptype']>0){
            //         $s_key_name[]=["g.type","=",$_REQUEST['s_grptype']];
            //     }
            //     $query->where($s_key_name);
            // })
            
            ->orderBy($this->table.".id","desc")
            ->paginate($pagesize);

        
        if($request->ajax()){
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{

            // $grptype=  DB::table("grplist")->orderBy("type","desc")->get();
            
            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }
    
    
    
}