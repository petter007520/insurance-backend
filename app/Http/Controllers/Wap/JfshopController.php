<?php

namespace App\Http\Controllers\Wap;
use App\Auth;
use App\Category;
use App\Channel;
use App\Http\Controllers\Controller;
use App\Lotteryconfig;
use App\Member;
use App\Memberlevel;
use App\Membermsg;
use App\Memberticheng;
use App\Moneylog;
use App\Order;
use App\Payment;
use App\Product;
use App\Productbuy;
use Carbon\Carbon;
use DB;
use App\Admin;
use App\Ad;
use App\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Session;

class JfshopController extends Controller
{
    public $cachetime=600;
    public $Template='wap';
    public function __construct(Request $request)
    {
        $this->Template=env("WapTemplate");

        $this->middleware(function ($request, $next) {


            $UserId =$request->session()->get('UserId');

            if($UserId>=1){
                $this->Member= Member::find($UserId);
                view()->share("Member",$this->Member);
            }



            return $next($request);
        });


        /**网站缓存功能生成**/

        if(!Cache::has('setings')){
            $setings=DB::table("setings")->get();

            if($setings){
                $seting_cachetime=DB::table("setings")->where("keyname","=","cachetime")->first();

                if($seting_cachetime){
                    $this->cachetime=$seting_cachetime->value;
                    Cache::forever($seting_cachetime->keyname, $seting_cachetime->value);
                }

                foreach($setings as $sv){
                    Cache::forever($sv->keyname, $sv->value);
                }
                Cache::forever("setings", $setings);
            }

        }

        $this->cachetime=Cache::get('cachetime');

        /**菜单导航栏**/
        if(Cache::has('wap.category')){
            $footcategory=Cache::get('wap.category');
        }else{
            $footcategory= DB::table('category')->where("atfoot","1")->orderBy("sort","desc")->limit(5)->get();
            Cache::put('wap.category',$footcategory,$this->cachetime);
        }
        view()->share("footcategory",$footcategory);
        /**菜单导航栏 END **/


        if(Cache::has('memberlevel.list')){
            $memberlevel=Cache::get('memberlevel.list');
        }else{
            $memberlevel= DB::table("memberlevel")->orderBy("id","asc")->get();
            Cache::get('memberlevel.list',$memberlevel,Cache::get("cachetime"));
        }

        $memberlevelName=[];
        foreach($memberlevel as $item){
            $memberlevelName[$item->id]=$item->name;
        }

        $this->memberlevelName=$memberlevelName;

        view()->share("memberlevel",$memberlevel);
        view()->share("memberlevelName",$memberlevelName);

        $Memberlevels= Memberlevel::get();

        foreach ($Memberlevels as $Memberlevel){
            $this->Memberlevels[$Memberlevel->id]=$Memberlevel;
        }


        if(Cache::has("admin.payment")){
            $this->payment =Cache::get("admin.payment");
        }else {
            $payments = DB::table("payment")->get();
            $payment = [];
            foreach ($payments as $pay) {
                $payment[$pay->id] = $pay->pay_name;
            }
            $this->payment =$payment;
            Cache::put("admin.payment",$payment,Cache::get("cachetime"));
        }



        /**广告数据**/
        $Ad=new Ad();

        if(Cache::has("wap.jfad")){
            $wapad= Cache::get("wap.jfad");
            view()->share("wapad",$wapad );
        }else{
            $wapad['banner']=$Ad->GetAd('积分商城');
            Cache::put("wap.jfad",$wapad,$this->cachetime);
            view()->share("wapad", $wapad);
        }



    }

    /***积分商品***/
    public function index(Request $request){


           $UserId =$request->session()->get('UserId');

            if($request->ajax()){
                $pagesize=Cache::get("pcpagesize");
                $list = DB::table("jfshops")
                    ->where("status",2)
                    ->orderBy("sort","desc")
                    ->paginate($pagesize);
                foreach ($list as $item){
                    $item->date=date("m-d H:i",strtotime($item->created_at));
                }

                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];

            }else{
                return view($this->Template.".jfshop.index",["title"=>"积分商城"]);
            }




    }



    public function product(Request $request){

        DB::table("jfshops")->where("id",$request->id)->increment('click_count',1);

        if(Cache::has('wap.jfshops.product.'.$request->id)){
            $product=Cache::get('wap.jfshops.product.'.$request->id);
        }else{
            $product=DB::table("jfshops")->where("id",$request->id)->first();

            if(!$product){
                return redirect()->route("wap.shop");
            }

            $product->links= DB::table("category")->where("id",$product->category_id)->value('links');
            $product->thumb_url= DB::table("category")->where("id",$product->category_id)->value('thumb_url');

            Cache::put('wap.jfshops.product.'.$request->id,$product,$this->cachetime);
        }
        view()->share("title",$product->category_name.'-'.$product->title);
        view()->share("product",$product);
        $UserId =$request->session()->get('UserId');

        if($product->status!=2){
            return redirect()->route("wap.shop");
        }
        return view($this->Template.".jfshop.product",["UserId"=>$UserId]);

    }


    /***积分商品兑换***/
    public function exchange(Request $request){


        $UserId =$request->session()->get('UserId');
        $UserName =$request->session()->get('UserName');
        if($UserId<1){
            return redirect()->route("wap.login");
        }
        if($request->ajax()){
            $product=DB::table("jfshops")->where("id",$request->productid)->first();
            $data=$request->all();
            $integrals= $product->integral*$request->number;
            $Member= Member::find($UserId);

            if(!$Member){
                return ["status"=>1,"msg"=>"会员不存在"];
            }
            if($integrals>$Member->integral){
                return ["status"=>1,"msg"=>"积分不足"];
            }

            $Member= Member::find($UserId);
            $yuanintegral=  $Member->integral;
            $Member->decrement('integral',$integrals);

            unset($data['_token']);
            $data['ip'] =  $request->getClientIp();
            $data['integral'] =  $integrals;
            $data['userid'] =  $UserId;
            $data['username'] =  $UserName;

            $data['created_at']=$data['updated_at']=Carbon::now();

            $id= DB::table("jfexchanges")->insertGetId($data);



            $msg = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "title" => "积分兑换",
                "content" => "积分兑换(" . $integrals . ")".$product->title."X".$request->number,
                "from_name" => "系统审核",
                "types" => "积分兑换",
            ];
            \App\Membermsg::Send($msg);


            $log = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "money" => $integrals,
                "notice" => "积分兑换(-)".$product->title."X".$request->number,
                "type" => "积分兑换",
                "status" => "-",
                "yuanamount" => $yuanintegral,
                "houamount" => $Member->integral,
                "ip" => \Request::getClientIp(),
            ];

            \App\Moneylog::AddLog($log);



            return ["status"=>0,"msg"=>"兑换成功"];

        }else{

            if(Cache::has('wap.jfshops.product.'.$request->id)){
                $product=Cache::get('wap.jfshops.product.'.$request->id);
            }else{
                $product=DB::table("jfshops")->where("id",$request->id)->first();

                $product->links= DB::table("category")->where("id",$product->category_id)->value('links');
                $product->thumb_url= DB::table("category")->where("id",$product->category_id)->value('thumb_url');

                Cache::put('wap.jfshops.product.'.$request->id,$product,$this->cachetime);
            }
            view()->share("title",'兑换'.$product->title);
            view()->share("product",$product);
            $UserId =$request->session()->get('UserId');
            view()->share("UserId",$UserId);
            view()->share("request",$request);
            return view($this->Template.".jfshop.exchange");
        }




    }


    /***积分商品***/
    public function exchangelog(Request $request){


        $UserId =$request->session()->get('UserId');
        if($UserId<1){
            return redirect()->route("wap.login");
        }
        if($request->ajax()){
            $pagesize=Cache::get("pcpagesize");
            $list = DB::table("jfexchanges")
                ->where("userid",$UserId)
                ->orderBy("id","desc")
                ->paginate($pagesize);
            foreach ($list as $item){
                $item->date=date("m-d H:i",strtotime($item->created_at));
            }

            return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];

        }else{
            return view($this->Template.".jfshop.exchangelog",["title"=>"兑换记录"]);
        }




    }


}


?>
