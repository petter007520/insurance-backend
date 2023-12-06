<?php

namespace App\Http\Controllers\Api;
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
use App\statistics;
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

            $lastsession = $request->lastsession;
            if(!$lastsession){
                return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
            }else{
                $Member = Member::where("lastsession",$request->lastsession)->first();
                if(!$Member){
                    return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
                }else{
                    $request->session()->put('UserId',$Member->id, 120);
                    $request->session()->put('UserName',$Member->username, 120);
                    $request->session()->put('Member',$Member, 120);
                }
            }

            $UserId = $request->session()->get('UserId');

            $this->Member = Member::find($UserId);
            if(!$this->Member){
               return response()->json(["status"=>-1,"msg"=>"请先登录!"]);
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
//
//        /**菜单导航栏**/
//        if(Cache::has('wap.category')){
//            $footcategory=Cache::get('wap.category');
//        }else{
//            $footcategory= DB::table('category')->where("atfoot","1")->orderBy("sort","desc")->limit(5)->get();
//            Cache::put('wap.category',$footcategory,$this->cachetime);
//        }
//        view()->share("footcategory",$footcategory);
//        /**菜单导航栏 END **/
//
//
//        if(Cache::has('memberlevel.list')){
//            $memberlevel=Cache::get('memberlevel.list');
//        }else{
//            $memberlevel= DB::table("memberlevel")->orderBy("id","asc")->get();
//            Cache::get('memberlevel.list',$memberlevel,Cache::get("cachetime"));
//        }
//
//        $memberlevelName=[];
//        foreach($memberlevel as $item){
//            $memberlevelName[$item->id]=$item->name;
//        }
//
//        $this->memberlevelName=$memberlevelName;
//
//        view()->share("memberlevel",$memberlevel);
//        view()->share("memberlevelName",$memberlevelName);
//
//        $Memberlevels= Memberlevel::get();
//
//        foreach ($Memberlevels as $Memberlevel){
//            $this->Memberlevels[$Memberlevel->id]=$Memberlevel;
//        }
//
//
//        if(Cache::has("admin.payment")){
//            $this->payment =Cache::get("admin.payment");
//        }else {
//            $payments = DB::table("payment")->get();
//            $payment = [];
//            foreach ($payments as $pay) {
//                $payment[$pay->id] = $pay->pay_name;
//            }
//            $this->payment =$payment;
//            Cache::put("admin.payment",$payment,Cache::get("cachetime"));
//        }
//
//
//
//        /**广告数据**/
//        $Ad=new Ad();
//
//        if(Cache::has("wap.jfad")){
//            $wapad= Cache::get("wap.jfad");
//            view()->share("wapad",$wapad );
//        }else{
//            $wapad['banner']=$Ad->GetAd('积分商城');
//            Cache::put("wap.jfad",$wapad,$this->cachetime);
//            view()->share("wapad", $wapad);
//        }



    }

    /***商品***/
    public function index(Request $request){
        $UserId =$request->session()->get('UserId');
        $pageSize = $request->get('pageSize',5);
        $list = DB::table("jfshops")
            ->select('id','title','keyinfo','descr','integral','original_price','image','content','created_at')
            ->where("status",2)
            ->orderBy("sort","desc")
            ->paginate($pageSize);
//        foreach ($list as $item){
//            $item->date=date("m-d H:i",strtotime($item->created_at));
//        }
        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$list]);
    }


    /*商品详情*/
    public function product(Request $request){

        if(!$request->id || !is_numeric($request->id)){
            return response()->json(["status"=>0,"msg"=>"商品不存在或已下架！！"]);
        }
        if(0 && Cache::has('wap.product.'.$request->id)){
            $product=Cache::get('wap.jfshops.product.'.$request->id);
        }else{
            $product=DB::table("jfshops")
                ->select('id','title','keyinfo','integral','original_price','descr','image','photos','content','specs')
                ->where(['id'=>$request->id,'status'=>2])
                ->first();
            if(!$product){
                return response()->json(["status"=>0,"msg"=>"商品不存在或已下架！"]);
            }
            DB::table("jfshops")->where("id",$request->id)->increment('click_count',1);
            $product->specs = explode(',',$product->specs);
            $product->photos = json_decode($product->photos);
//            $product->links= DB::table("category")->where("id",$product->category_id)->value('links');
//            $product->thumb_url= DB::table("category")->where("id",$product->category_id)->value('thumb_url');

            Cache::put('wap.jfshops.product.'.$request->id,$product,$this->cachetime);
        }
        $UserId =$request->session()->get('UserId');
        return response()->json(["status"=>1,"msg"=>"返回成功！","data"=>$product]);
    }

    /*商品订单确认*/
    public function submit_order(Request $request ){
        $UserId =$request->session()->get('UserId');
        if($UserId<1){
            return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
        }
        $address= Db::table('memberaddress')->select('id','receiver','mobile','area','address')->where(['userid'=>$UserId,'status'=>1])->first();
        if($address){
            return response()->json(["status"=>1,"msg"=>"返回成功！","data"=>$address]);
        }else{
            return response()->json(["status"=>0,"msg"=>"未设置默认地址！"]);
        }
    }

    /***购买商品订单详情页***/
    public function order_details(Request $request){
        $UserId =$request->session()->get('UserId');
        $UserName =$request->session()->get('UserName');
        if($UserId<1){
            return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
        }
        if(!$request->productid || !is_numeric($request->productid)){
            return response()->json(["status"=>0,"msg"=>"商品不存在或已下架！！"]);
        }
        if($request->number<1 || !is_numeric($request->number)){
            return response()->json(["status"=>0,"msg"=>"商品数量错误！"]);
        }
        $product=DB::table("jfshops")->select('id','integral','title','status')->where(['id'=>$request->productid,'status'=>2])->first();
        if(!$product){
            return response()->json(["status"=>0,"msg"=>"商品不存在或已下架！"]);
        }
        if(!$request->addressid){
            return response()->json(["status"=>0,"msg"=>"收货地址不能为空！！"]);
        }
        if(!$request->specs){
            return response()->json(["status"=>0,"msg"=>"请选择购买规格！"]);
        }

        $Member= Member::select('id','username','amount','paypwd','state','realname','mobile')->where('state',1)->find($UserId);
        $integrals= $product->integral*$request->number;
        // if($request->pay_type == 0 && $integrals>$Member->amount){
        //     return ["status"=>0,"msg"=>"余额不足,当前余额：".$Member->amount];
        // }

        //商品信息
        $data['name'] = $product->title;
        $data['integral'] = $product->integral;
        $data['number'] = $request->number;
        $data['specs'] = $request->specs;
        $data['total_integral'] = $integrals;//总金额
        //订单信息
        $data['order_number'] = 'SMY'.date("YmdHmi",time()).$this->make_aid(6);
        $data['order_time'] = date("Y-m-d H:m:i",time());
        //银行卡默认信息
        if($request->pay_type == 2){
            $bank_info= Db::table('payment')->where(['pay_code'=>'ChinaPay'])->value('pay_bank');
            $bank = explode('<br>',$bank_info);
            $data['bank_info'] = $bank;
        }else{
            $data['bank_info'] = '余额支付';
        }
        //收货地址信息
        $address_info= Db::table('memberaddress')->select('id','receiver','area','mobile','address')->where(['id'=>$request->addressid,'userid'=>$UserId])->first();
        if(!$address_info){
            return response()->json(["status"=>0,"msg"=>"收货地址信息有误！"]);
        }

        $jfexchanges_data['ordernumber'] =  $data['order_number'];
        $jfexchanges_data['userid'] =  $UserId;
        $jfexchanges_data['username'] =  $UserName;
        $jfexchanges_data['productid'] =  $request->productid ;
        $jfexchanges_data['productname'] =  $product->title;
        $jfexchanges_data['specs'] =  $request->specs;
        $jfexchanges_data['integral'] =  $product->integral;//单价
        $jfexchanges_data['memo'] =  $request->memo;//备注
        $jfexchanges_data['number'] =  $request->number;
        $jfexchanges_data['ip'] =  $request->getClientIp();
        $jfexchanges_data['name'] =  $address_info->receiver;
        $jfexchanges_data['phone'] =  $address_info->mobile;
        $jfexchanges_data['shouhuodizhi'] =  $address_info->area.$address_info->address;
        // $jfexchanges_data['type'] = $request->pay_type;
        $jfexchanges_data['created_at']=$jfexchanges_data['updated_at']=Carbon::now();

        $id= DB::table("jfexchanges")->insertGetId($jfexchanges_data);
        if($id){
            return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$data]);
        }else{
            return response()->json(["status"=>0,"msg"=>"提交订单失败！"]);
        }
//        $data['bankreal_name'] = $bank_info->bankrealname;
//        $data['bank_code'] = $bank_info->bankcode;

    }

    /*订单支付*/
    public function exchange(Request $request){
        $UserId = $request->session()->get('UserId');
        $UserName = $request->session()->get('UserName');
        if($UserId<1){
            return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
        }
        if(!$request->productid || !is_numeric($request->productid)){
            return response()->json(["status"=>0,"msg"=>"商品不存在或已下架！！"]);
        }
        if($request->number<1 || !is_numeric($request->number)){
            return response()->json(["status"=>0,"msg"=>"商品数量错误！"]);
        }
//        if($request->ajax()){
        $product = DB::table("jfshops")->select('id','integral','title','status')->where(['id'=>$request->productid,'status'=>2])->first();
        if(!$product){
            return response()->json(["status"=>0,"msg"=>"商品不存在或已下架！"]);
        }
        $isorder = DB::table('jfexchanges')->select('id','productname')->where(['ordernumber'=>$request->ordernumber,'userid'=>$UserId,'productid'=>$request->productid,'number'=>$request->number,'status'=>0])->first();
        if(!$isorder){
            return response()->json(["status"=>0,"msg"=>"订单存在异常，请重新购买！"]);
        }
        if(!$request->payimg &&$request->pay_type != 1){
            return response()->json(["status"=>0,"msg"=>"支付凭证不能为空！"]);
        }
        $Member = Member::select('id','username','integral','amount','paypwd','state','realname','mobile')->where('state',1)->find($UserId);
        if(!$Member){
            return ["status"=>0,"msg"=>"会员不存在"];
        }
        if((!$request->paypwd || !is_numeric($request->paypwd)) &&$request->pay_type == 1){
            return response()->json(["status"=>0,"msg"=>"支付密码错误！！"]);
        }
        $integrals = $product->integral*$request->number;
        if($request->pay_type ==1 && $integrals > $Member->amount){
            return ["status"=>0,"msg"=>"余额不足,当前余额：".$Member->amount];
        }
        $Member_paypwd=  \App\Member::DecryptPassWord($Member->paypwd);
        if(($request->paypwd != $Member_paypwd )&&$request->pay_type == 1){
            return response()->json(["status"=>0,"msg"=>"支付密码错误！"]);
        }

        $yuanamount = $Member->amount;
        //余额支付
        if($request->pay_type == 1){
            $Member->decrement('amount',$integrals);
        }

        $msg = [
            "userid" => $Member->id,
            "username" => $Member->username,
            "title" => "商品购买",
            "content" => "商品购买(" . $integrals . ")".$isorder->productname."X".$request->number,
            "from_name" => "系统审核",
            "types" => $request->pay_type == 1? "余额购买":"银行卡购买",
        ];
        \App\Membermsg::Send($msg);


        $log = [
            "userid" => $Member->id,
            "username" => $Member->username,
            "money" => $integrals,
            "notice" => "商品购买(-)".$isorder->productname."X".$request->number,
            "type" => "商品购买",
            "status" => "-",
            "yuanamount" => $yuanamount,
            "houamount" => $Member->amount,
            "category_id"=>'0',
            "ip" => \Request::getClientIp(),
        ];

        \App\Moneylog::AddLog($log);

        $isorder = DB::table('jfexchanges')
            ->where(['ordernumber'=>$request->ordernumber,'userid'=>$UserId,'productid'=>$request->productid,'number'=>$request->number])
            ->update(['status'=>1,'type'=>$request->pay_type]);
        if($isorder){
            return response()->json(["status"=>1,"msg"=>"购买成功，请等待审核发货！"]);
        }else{
            return response()->json(["status"=>0,"msg"=>"购买失败！"]);
        }



//        }else{
//
//            if(Cache::has('wap.jfshops.product.'.$request->id)){
//                $product=Cache::get('wap.jfshops.product.'.$request->id);
//            }else{
//                $product=DB::table("jfshops")->where("id",$request->id)->first();
//
//                $product->links= DB::table("category")->where("id",$product->category_id)->value('links');
//                $product->thumb_url= DB::table("category")->where("id",$product->category_id)->value('thumb_url');
//
//                Cache::put('wap.jfshops.product.'.$request->id,$product,$this->cachetime);
//            }
//            view()->share("title",'兑换'.$product->title);
//            view()->share("product",$product);
//            $UserId =$request->session()->get('UserId');
//            view()->share("UserId",$UserId);
//            view()->share("request",$request);
//            return view($this->Template.".jfshop.exchange");
//        }




    }


    /***商品订单列表***/
    public function exchangelog(Request $request){

        $pageSize = $request->get('pageSize',5);
        $status = $request->get('status',0);
        $UserId =$request->session()->get('UserId');
        if($status == 3){
            $where = [];
        }else{
            $where = ["a.status"=>$status];
        }
        if($UserId<1){
            return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
        }

//            $pagesize=Cache::get("pcpagesize");
            $list = DB::table("jfexchanges as a")
                ->join('jfshops', 'jfshops.id', '=', 'a.productid')
                ->select('a.id','a.ordernumber','a.productid','a.productname','a.integral','a.number','a.specs','a.status','a.created_at','jfshops.image','jfshops.id as bid')
                ->where("userid",$UserId)
                ->where($where)
                ->orderBy("a.id","desc")
                ->paginate($pageSize);

            return response()->json(["status"=>1,"msg"=>"返回成功！","data"=>$list]);

    }

    /*订单详情*/
    public function exchangeDetails(Request $request){
        $UserId =$request->session()->get('UserId');
        if($UserId<1){
            return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
        }
        if(!$request->ordernumber){
            return response()->json(["status"=>0,"msg"=>"订单存在异常！！"]);
        }
        $list = DB::table("jfexchanges as a")
            ->join('jfshops', 'jfshops.id', '=', 'a.productid')
            ->select('a.id as orderid','a.productid','a.productname','a.integral','a.number','a.status','a.type','a.specs','a.memo','a.created_at','ordernumber','a.name','a.phone','a.shouhuodizhi','a.created_at','jfshops.image')
            ->where(["userid"=>$UserId,"a.ordernumber"=>$request->ordernumber])
            ->first();
//            $product->links= DB::table("category")->where("id",$product->category_id)->value('links');

        return response()->json(["status"=>1,"msg"=>"返回成功！","data"=>$list]);
    }

    /*订单4 取消 5 删除*/
    public function orderCancel(Request $request){
        $UserId =$request->session()->get('UserId');
        $status = $request->get('status');
        if($UserId<1){
            return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
        }
        if(!$request->ordernumber || !in_array($status,['4','5'])){
            return response()->json(["status"=>0,"msg"=>"订单存在异常！！"]);
        }else{
            $res = DB::table("jfexchanges")
                ->where(["userid"=>$UserId,"ordernumber"=>$request->ordernumber])
                ->first();
            if(!$res){
                return response()->json(["status"=>0,"msg"=>"订单存在异常！"]);
            }
        }
        $list = DB::table("jfexchanges")
            ->where(["userid"=>$UserId,"ordernumber"=>$request->ordernumber])
            ->update(['status'=>$status]);
        if($list){
            return response()->json(["status"=>1,"msg"=>"取消成功！"]);
        }else{
            return response()->json(["status"=>0,"msg"=>"取消失败！"]);
        }
    }

    //更改支付方式
    public function change_pay(Request $request){
        $UserId =$request->session()->get('UserId');
        // $paycode = $request->paycode;
        if($UserId<1){
            return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
        }
		$list = DB::table('payment')->select('id','pay_code','pay_name','pay_pic','bankname','bankrealname','bankcode','enabled','is_default','pay_channel','pay_type')->where(['enabled'=>1])->get();

		foreach ($list as $v){
            if($v->pay_type == 2){
                if ($request->type == 'new') {
					$res['offline'][] = $v;
				} else {
					$res['offline'] = $v;
				}
            }else{
                $res['online'][] = $v;
            }
        }

        if($res){
            return response()->json(["status"=>1,"msg"=>"返回成功！","data"=>$res]);
        }else{
            return response()->json(["status"=>0,"msg"=>"！"]);
        }
    }


    public function make_aid( $length = 4 ){
        // 密码字符集，可任意添加你需要的字符
        $chars = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand( $chars, $length );

        $password = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            // 将 $length 个数组元素连接成字符串
            $password .= $chars[$keys[$i]];
        }
        return $password;
    }
}
?>
