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

class LotterysController extends Controller
{
    public $cachetime=600;
    public $Template='wap';
    public function __construct(Request $request)
    {
        $this->Template=env("WapTemplate");

        $this->middleware(function ($request, $next) {
            //dd($request->session()->all());

            $UserId =$request->session()->get('UserId');

            if($UserId<1){
                return redirect()->route("wap.login");
            }

           $this->Member= Member::find($UserId);


            view()->share("Member",$this->Member);

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


    }

    /***大转盘***/
    public function index(Request $request){


           $UserId =$request->session()->get('UserId');

           return view($this->Template.".lotterys.index");


    }


    /***大转盘 会员余额***/
    public function amount(Request $request){
           $UserId =$request->session()->get('UserId');

           $Member=   Member::find($UserId);

           return sprintf("%.2f",$Member->amount);

    }
    /***大转盘 会员余额***/
    public function winlist(Request $request){
       $list=    Moneylog::where("moneylog_type","大转盘")->where("moneylog_status","+")->orderBy("id","desc")->limit(50)->get();
       foreach($list as $item){
           echo '会员:' ,substr($item->moneylog_user,0,4)."****".substr($item->moneylog_user,8,3) , ' ' , $item->moneylog_notice , ' ' , $item->created_at , '<br>';
       }

    }


    /***大转盘 会员余额***/
    public function click(Request $request){
        $UserId =$request->session()->get('UserId');

        if($UserId<1){
            echo 'cjgo(0.00,0)';
            exit;
        }

        $Member=   Member::find($UserId);

        //sprintf("%.2f",$Member->amount);
        $amountPay= Cache::get('lotterypoint');

        if($Member->amount <$amountPay){
            echo 'cjgo(' , $Member->amount , ',0)';
            exit;
        }

       // $Member->decrement('amount',$lotterypoint);
        $Mamount=  $Member->amount;
        $notice= "大转盘消费(".$amountPay."元)";
        //站内消息
/*
       $msg=[
            "userid"=>$this->Member->id,
            "username"=>$this->Member->username,
            "title"=>"大转盘",
            "content"=>$notice,
            "from_name"=>"系统通知",
            "types"=>"大转盘",
        ];
        \App\Membermsg::Send($msg);

*/



        $Member->decrement('amount',$amountPay);
        $log=[
            "userid"=>$this->Member->id,
            "username"=>$this->Member->username,
            "money"=>$amountPay,
            "notice"=>$notice,
            "type"=>"大转盘",
            "status"=>"-",
            "yuanamount"=>$Mamount,
            "houamount"=>$Member->amount,
            "ip"=>\Request::getClientIp(),
        ];

        \App\Moneylog::AddLog($log);


       $LotteryPeizhi= Lotteryconfig::orderBy("id","asc")->get();//winningrate

        foreach ($LotteryPeizhi as $lott){
            $prizeConfig[]=$lott->winningrate;
        }


        $rnd = mt_rand(1,10000);
        $pb = 0;
        $winIndex = -1;
        for($ci = 0;$ci < count($prizeConfig);$ci++){
            $pb += $prizeConfig[$ci];
            if($pb >= $rnd){
                //中了
                $winIndex = $ci;
                break;
            }
        }
        //修正 为最后一个，即未中奖
        if($winIndex == -1){
            $winIndex = count($prizeConfig) - 1;
        }

        switch($winIndex){
            case 0:
                $this->zhongjiang(1);
                break;
            case 1:

                $this->zhongjiang(2);

                break;
            case 2:

                $this->zhongjiang(3);

                break;
            case 3:
                $this->zhongjiang(4);

                break;
            case 4:
                $this->zhongjiang(5);

                break;
            case 5:
                //再接再厉

                break;
        }

        echo 'cjgo(' , sprintf("%.2f",$Member->amount) , ',' , ($winIndex + 1) , ')';



    }



    public function zhongjiang($id){


        $UserId =$this->Member->id;

        $Member=   Member::find($UserId);

        $LotteryPeizhi= Lotteryconfig::find($id);

        $amountPay= $LotteryPeizhi->moneys;

        $Mamount=  $Member->amount;
        $notice= $amountPay>0?$LotteryPeizhi->prize.",系统已经存入您的余额中":$LotteryPeizhi->prize;
        //站内消息
        $msg=[
            "userid"=>$this->Member->id,
            "username"=>$this->Member->username,
            "title"=>"大转盘中奖[".$LotteryPeizhi->name."]",
            "content"=>$notice,
            "from_name"=>"系统通知",
            "types"=>"大转盘",
        ];
        \App\Membermsg::Send($msg);

        if($amountPay>1){
            $Member->increment('amount',$amountPay);
        }

        $notice= $amountPay>0?"恭喜您抽中".$LotteryPeizhi->name.",".$amountPay."元":$LotteryPeizhi->prize;

        $log=[
            "userid"=>$this->Member->id,
            "username"=>$this->Member->username,
            "money"=>$amountPay,
            "notice"=>$notice,
            "type"=>"大转盘",
            "status"=>"+",
            "yuanamount"=>$Mamount,
            "houamount"=>$Member->amount,
            "ip"=>\Request::getClientIp(),
        ];

        \App\Moneylog::AddLog($log);


    }



}


?>
