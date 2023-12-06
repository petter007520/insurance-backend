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

class WheelController extends Controller
{
    public $cachetime=600;
    public $Template='wap';
    public function __construct(Request $request)
    {
        $this->Template=env("WapTemplate");

        $this->middleware(function ($request, $next) {
            //dd($request->session()->all());

            $UserId =$request->session()->get('UserId');

            if($UserId>0){
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
        $memberlevelwheels=[];
        foreach($memberlevel as $item){
            $memberlevelName[$item->id]=$item->name;
            $memberlevelwheels[$item->id]=$item->wheels;
        }

        $this->memberlevelName=$memberlevelName;
        $this->memberlevelwheels=$memberlevelwheels;

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


        $LotteryPeizhi= Lotteryconfig::orderBy("id","asc")->get();
        view()->share("LotteryPeizhi",$LotteryPeizhi);

    }

    /***大转盘***/
    public function index(Request $request){


           $UserId =$request->session()->get('UserId');
            view()->share("UserId",$UserId);
            $Member=   Member::find($UserId);
            if($Member) {

                $this->memberlevelwheels[$Member->level];
                view()->share("Userwheels", $this->memberlevelwheels[$Member->level]);
            }

           return view($this->Template.".wheel.index");


    }


    /***大转盘 会员余额***/
    public function amount(Request $request){
           $UserId =$request->session()->get('UserId');

           $Member=   Member::find($UserId);

           return sprintf("%.2f",$Member->amount);

    }

    /***大转盘 会员余额***/
    public function luckdraws(Request $request){
           $UserId =$request->session()->get('UserId');
           $Member=   Member::find($UserId);
           if($Member){
               return $Member->luckdraws;
           }else{
               return 0;
           }


    }
    /***大转盘***/
    public function winlist(Request $request){
       $list=    Moneylog::where("moneylog_type","大转盘")->where("moneylog_status","+")->orderBy("id","desc")->limit(50)->get();
        $str='';
       foreach($list as $item){
           $str.= '<div id="'.$item->id.'">
                恭喜  '.substr($item->moneylog_user,0,4).'****'.substr($item->moneylog_user,8,3).'  的用户抽中  <span id="gift_coupon">'.$item->moneylog_notice.'</span>
            </div>';

       }
       echo $str;

    }
    /***抽奖***/
    public function cjwinlist(Request $request){

       $week= Carbon::now()->weekday();
       $hour= Carbon::now()->format("H");

       if($week==0 && $hour>9) {
           $list = Moneylog::where("moneylog_type", "抽奖")
               ->where("moneylog_status", "+")
               ->whereDate("created_at",">",Carbon::now()->addDay(-7)->format("Y-m-d"))
               ->orderBy("id", "desc")
               ->limit(20)
               ->get();
           $str = '';
           foreach ($list as $item) {
               $str .= '<li>
                恭喜  ' . $item->moneylog_user . '    <span id="gift_coupon">' . $item->moneylog_notice . '</span>
            </li>';

           }

           echo $str;
       }else{
           echo '';
       }

    }


    /***大转盘***/
    public function click(Request $request){
        $UserId =$request->session()->get('UserId');


        //{"state":1,"msg":"\u8c22\u8c22\u53c2\u4e0e","index":8}

        if($UserId<1){
            return ["state"=>0,"msg"=>"请先登录"];
        }




        $Member=   Member::find($UserId);
        if(!$Member){
            return ["state"=>0,"msg"=>"请先登录"];
        }

        $clicks=Moneylog::where("moneylog_type","大转盘")
            ->where("moneylog_status","+")
            ->where("moneylog_userid",$UserId)
            ->whereDate("created_at",Carbon::now()->format("Y-m-d"))
            ->orderBy("id","desc")
            ->count();


        $levelwheels= $this->memberlevelwheels[$Member->level];


        if($clicks>=$levelwheels){
            return ["state"=>0,"msg"=>"今天抽奖次数已用完"];
        }

        $LotteryPeizhi= Lotteryconfig::orderBy("id","asc")->get();

        foreach ($LotteryPeizhi as $lott){
            $prizeConfig[]=$lott->winningrate;
            $prize[]=$lott->prize;
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


        $this->zhongjiang($winIndex+1);


        return ["state"=>1,"msg"=>$prize[$winIndex],"index"=>$winIndex+1,"levelwheels"=>$levelwheels,"clicks"=>$clicks];





    }
    /***抽奖***/
    public function Luckdraw(Request $request){
        $UserId =$request->session()->get('UserId');


        if($UserId<1){
            return ["state"=>0,"msg"=>"请先登录"];
        }




        $Member=   Member::find($UserId);
        if(!$Member){
            return ["state"=>0,"msg"=>"请先重新登录"];
        }

        if($Member && $Member->luckdraws<1){
            return ["state"=>0,"msg"=>"您没有抽奖券"];
        }

        $Member->decrement("luckdraws",1);
        $rnd = mt_rand(1,10000);



        $Luckdraws=  Cache::get("Luckdraw");
        if($rnd<$Luckdraws){
            $sp= DB::table("jfshops")->where("status",2)->inRandomOrder()->first();


            //站内消息
            $msg=[
                "userid"=>$this->Member->id,
                "username"=>$this->Member->username,
                "title"=>"抽奖中奖",
                "content"=>$sp->title,
                "from_name"=>"系统通知",
                "types"=>"抽奖",
            ];
            \App\Membermsg::Send($msg);

            $Member= Member::find($UserId);

            $data['ip'] =  $request->getClientIp();
            $data['integral'] =  0;
            $data['productid'] =  $sp->id;
            $data['productname'] =  $sp->title;
            $data['type'] =  2;
            $data['number'] =  1;
            $data['name'] =  $Member->realname;
            $data['phone'] =  \App\Member::DecryptPassWord($Member->mobile);
            $data['shouhuodizhi'] =  $Member->address;
            $data['userid'] =  $Member->id;
            $data['username'] =  $Member->username;
            $data['created_at']=$data['updated_at']=Carbon::now();

            $id= DB::table("jfexchanges")->insertGetId($data);


            $log = [
                "userid" => $Member->id,
                "username" => $Member->username,
                "money" => 0,
                "notice" => "抽奖中奖".$sp->title."X1",
                "type" => "抽奖",
                "status" => "+",
                "yuanamount" => $Member->integral,
                "houamount" => $Member->integral,
                "ip" => \Request::getClientIp(),
            ];

            \App\Moneylog::AddLog($log);




            return ["state"=>1,"msg"=>"恭喜您中奖","jiangping"=>"奖品:".$sp->title,"luckdraws"=>$Member->luckdraws];
        }else{
            return ["state"=>0,"msg"=>"未抽中奖品,再接再励","luckdraws"=>$Member->luckdraws];
        }

        //$this->zhongjiang($winIndex+1);





    }



    public function zhongjiang($id){


        $UserId =$this->Member->id;

        $Member=   Member::find($UserId);

        $LotteryPeizhi= Lotteryconfig::find($id);

        $amountPay= $LotteryPeizhi->moneys;



            $Mamount = $Member->amount;
            $notice = $amountPay > 0 ? $LotteryPeizhi->prize . ",系统已经存入您的余额中" : $LotteryPeizhi->prize;
            if($amountPay>0) {
                //站内消息
                $msg = [
                    "userid" => $this->Member->id,
                    "username" => $this->Member->username,
                    "title" => "大转盘中奖[" . $LotteryPeizhi->name . "]",
                    "content" => $notice,
                    "from_name" => "系统通知",
                    "types" => "大转盘",
                ];
                \App\Membermsg::Send($msg);
            }

            if ($amountPay > 1) {
                $Member->increment('amount', $amountPay);
            }

            $notice = $LotteryPeizhi->prize;

            $log = [
                "userid" => $this->Member->id,
                "username" => $this->Member->username,
                "money" => $amountPay,
                "notice" => $notice,
                "type" => "大转盘",
                "status" => "+",
                "yuanamount" => $Mamount,
                "houamount" => $Member->amount,
                "ip" => \Request::getClientIp(),
            ];

            \App\Moneylog::AddLog($log);



    }



}


?>
