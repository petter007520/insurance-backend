<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Member;
use App\Productbuy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    public $cachetime=600;
    public $Template='wap';
    public function __construct(Request $request)
    {
       $this->cachetime=Cache::get('cachetime');
    }

    /**
     * 首页公共信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        $data['banner'] = DB::table('advertisementdatas')->select('thumb_url','title')->where(['adverid'=>1])->limit(4)->get();;
        /* 弹出公告 */
        if(Cache::has("index_notice")){
            $index_notice = Cache::get("index_notice");
        }else{
            $alert_notice = DB::table("articles")->where(['category_id'=>7,'title'=>'首页弹出公告'])->value('content');
            $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
            $index_notice = preg_replace($pregRule, '<img src="' . ENV('FILE_URL') . '${1}" style="width:100%">', $alert_notice);
            Cache::forever("index_notice",$index_notice);
        }
        $data['alert_notice'] = $index_notice;

        /*滚动公告*/
        if(Cache::has("index_scroll")){
            $list = Cache::get("index_scroll");
        }else{
            $list = [];
            $index_scroll_notice = DB::table('articles')->where(['category_id'=>7,'title'=>'首页滚动公告'])->get(['descr'])->toArray();
            foreach ($index_scroll_notice as $index){
                $list[] = $index->descr;
            }
            Cache::forever("index_scroll",$list);
        }
        $data['scroll_notice'] = $list;
        $wealth_list = DB::table('member_wealth')->orderBy('create_data','desc')->limit(50)->get(['name','num_name'])->toArray();
        $data['wealth_list'] = $wealth_list;
        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$data]);
    }

    public function wealthList(){
        $list = DB::table('product_wealth')->limit(50)->orderBy('id','desc')->get(['name','time']);
        foreach ($list as &$val){
            $val->time = Carbon::parse($val->time)->diffForHumans();
        }
        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$list]);
    }

	public function getselltips(Request $request){
        $data['curr_sell_tips'] = DB::table("setings")->where('keyname','curr_sell_tips')->value('value');//卖出提示
        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$data]);
    }

    /**
     * 产品列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function product(Request $request): \Illuminate\Http\JsonResponse
    {
        $pageSize = $request->get('pageSize',10);
        $page = $request->get('page',1);
        $category_id = $request->get('category_id',8);

         $list=DB::table("products")
             ->select('id','title','price')
              ->where(['category_id'=>$category_id,'status'=>1])
              ->orderBy("sort","desc")
                ->paginate($pageSize, ['*'], 'page', $page);

        return response()->json(["status"=>1,"msg"=>"ok","data"=>$list]);
    }


    /**项目**/
    public function product_detail(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = $request->get('id',0);
        if($id<=0){
            return response()->json(["status"=>0,"msg"=>"该项目不存在或已下架！"]);
        }
        $product=DB::table("products")
            ->select('id','title','pic','describe','price')
            ->where(['id'=>$id,'status'=>1])
            ->first();
        return response()->json(["status"=>1,"msg"=>"ok","data"=>$product]);
    }

    /***客服列表***/
    public function contact(Request $request){
        $data['list1'] =  DB::table("contact")->where('status',1)->where('url','<>','')->orderBy("id","desc")->get();
        $data['list2'] =  DB::table("contact")->where('status',1)->where('url','=','')->orderBy("id","desc")->get();
        $data['kefu_banner'] =  DB::table("setings")->where('keyname','kefu_banner')->value('value');
        $data['kefu_banner'] =  '/uploads/'.$data['kefu_banner'].'?time='.time();
        return response()->json(['status'=>1,'data'=>$data]);
    }


        /***客服列表***/
    public function get_link(Request $request){

        $UserId = $request->session()->get('UserId');

        $data['invite_link'] =  DB::table("setings")->where('keyname','invite_link')->value('value');
        $data['invite_code'] =  DB::table("member")->where('id',$UserId)->value('invicode');

        return response()->json(['status'=>1,'data'=>$data]);
    }

    /**项目投资**/
    public function buy(Request $request)
    {
        if(Cache::has('wap.product.'.$request->id)){
            $product=Cache::get('wap.product.'.$request->id);
        }else{
            $product=DB::table("products")->where("id",$request->id)->first();

            $product->links= DB::table("category")->where("id",$product->category_id)->value('links');
            $product->thumb_url= DB::table("category")->where("id",$product->category_id)->value('thumb_url');

            Cache::put('wap.product.'.$request->id,$product,$this->cachetime);
        }
        $Memberamount=0;
        $UserId = $request->session()->get('UserId');
        if($UserId>0){
            $Member= Member::find($UserId);
            $Memberamount=$Member->amount;
        }
        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$Memberamount]);
    }

    //转盘红包
    public function get_red_packet(Request $request){
        $prize = DB::table('grplist')->where(['status'=>1])->get()->toArray();

        if(empty($prize)){
            return response()->json(['status'=>0,'msg'=>'活动暂未开启']);
        }
        $UserId = $request->session()->get('UserId');
        //查看领取开启条件是否满足
        $luckdraws = DB::table('member')->where(['id'=>$UserId])->value('luckdraws');
        if($luckdraws == 0){
            return response()->json(["status"=>0,"msg"=>"抽奖券不足，进行签到获得抽奖券"]);
        }

        $prize_probability = [];//商品概率集合
        $rand = 0;
        foreach ($prize as $k=>$v){
            $prize_probability[] = $v->rate;
            $rand += $v->rate;
        }

        $winning_num = $this->lock($prize_probability,$rand);


         /*添加Moneylog*/
            $Member = Member::find($UserId);



        //判断商品是否有库存
        $stock = $prize[$winning_num]->stock;
        if($stock && $stock>0){
           $red_amount = $prize[$winning_num]->value;//领取红包金额

        }else{
            $grp_log=[
                "user_id"=>$this->Member->id,
                "username"=>$this->Member->username,
                "grp_id"=>$prize[$winning_num]->id,
                "grp_name"=>$prize[$winning_num]->name,
                "value"=>$prize[$winning_num]->value,
                "status"=>2,//1:领取到  2:没领到
                "created_at"=> Carbon::now(),
                "activity_id"=>101
            ];
            DB::table('grplog')->insert($grp_log);

           return response()->json(['status'=>1,'msg'=>'红包领完了',"data"=>$prize[$winning_num]->id,'type'=>'0']);
        }
        $type = $prize[$winning_num]->type;

        DB::beginTransaction();
        try{
            $red_id = $prize[$winning_num]->id;
            $Member->decrement('luckdraws', 1);
            if($type == 3){


            $red_product_id = DB::table('grplist')->where(['id'=>$red_id])->value('p_id');

            if($red_product_id > 0 ){
                $red_product_info = DB::table("products")
                    ->select('id','title','category_id','qtje','isft','tzzt','hkfs','shijian','zgje','qxdw','zsje','zsje_type','jyrsy','qtsl','zscp_id')
                    ->where(['id'=>$red_product_id])
                    ->first();
                if($red_product_info){
                    //赠送数量
                    $red_produc_pcount = DB::table('grplist')->where(['id'=>$red_id])->value('value');
                    //赠送总金额
                    $red_product_money = $red_produc_pcount * $red_product_info->qtje;

                    //判断下一次领取时间
                    $hkfs = $red_product_info->hkfs;
                    $zhouqi    = trim($red_product_info->shijian);//周期
                    $sendDay_count = $hkfs == 1?1:$zhouqi;

                    $useritem_time2 = \App\Productbuy::DateAdd("d",1, date('Y-m-d 0:0:0',time()));
                    $NewProductbuy= new Productbuy();
                    $NewProductbuy->userid=$UserId;

                    $NewProductbuy->username=$Member->username;

                    $NewProductbuy->productid=$red_product_id;
                    $NewProductbuy->category_id=$red_product_info->category_id;
                    $NewProductbuy->amount= $red_product_money;
                    $NewProductbuy->ip= \Request::getClientIp();
                    $NewProductbuy->useritem_time = Carbon::now();
                    $NewProductbuy->useritem_time2=$useritem_time2;
                    $NewProductbuy->reason = "红包赠送产品(".$red_product_info->title.")";
                    $NewProductbuy->sendDay_count=$sendDay_count;
                    $NewProductbuy->num = $red_produc_pcount;//赠送数量
                    $NewProductbuy->unit_price = $red_product_info->qtje;//赠送时单价
                    $res = $NewProductbuy->save();
                    //站内消息
                    $msg=[
                        "userid"=>$UserId,
                        "username"=>$Member->username,
                        "title"=>"红包赠送产品",
                        "content"=>"成功加入项目(".$red_product_info->title.")",
                        "from_name"=>"系统通知",
                        "types"=>"加入项目",
                    ];
                    \App\Membermsg::Send($msg);
                    //
                    $give_log=[
                        "userid"=>$UserId,
                        "username"=>$Member->username,
                        "money"=> $red_product_money,
                        "notice"=>"红包赠送产品(".$red_product_info->title.")[".$NewProductbuy->id."]",
                        "type"=>"红包赠送项目",
                        "status"=>"+",
                        "yuanamount"=>0,
                        "houamount"=>0,
                        "ip"=>\Request::getClientIp(),
                        "category_id"=>$red_product_info->category_id,
                        "product_id"=>$red_product_info->id,
                        "product_title"=>$red_product_info->title,
                    ];
                    \App\Moneylog::AddLog($give_log);

                    //赠送End
                    $my_statistics['capital_flow'] = $red_product_money;
                    $my_statistics_data['capital_flow'] = $red_product_money;
                }
            }
                $notice = '领取奖品'.$prize[$winning_num]->name;
            }else{
                $Member->increment('integral', $red_amount);
                $notice = '领取红包'.$red_amount.'元';

            }

            /*站内消息*/
            $msg=[
                "userid"=>$this->Member->id,
                "username"=>$this->Member->username,
                "title"=>"红包到账",
                "content"=>$notice,
                "from_name"=>"系统通知",
                "types"=>"领取红包",
            ];
            \App\Membermsg::Send($msg);
            /*站内消息END*/

           $yuanamount = $Member->amount;

            $log=[
                "userid"=>$this->Member->id,
                "username"=>$this->Member->username,
                "money"=>$red_amount,
                "notice"=>$notice,
                "type"=>"领取红包",
                "status"=>"+",
                "yuanamount"=>$yuanamount,
                "houamount"=>$Member->amount,
                "ip"=>\Request::getClientIp(),
                "category_id"=>0,
                "product_id"=>0,
                "product_title"=>0,
            ];
            \App\Moneylog::AddLog($log);
            /*Moneylog  END*/
            $grp_log=[
                "user_id"=>$this->Member->id,
                "username"=>$this->Member->username,
                "grp_id"=>$prize[$winning_num]->id,
                "grp_name"=>$prize[$winning_num]->name,
                "value"=>$prize[$winning_num]->value,
                "status"=>1,//1:领取到  2:没领到
                "created_at"=> Carbon::now(),
                 "activity_id"=>1
            ];
            DB::table('grplog')->insert($grp_log);

            DB::commit();

            return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$notice,]);
      }catch(\Exception $exception){
          dump($exception);
            DB::rollback();
            return response()->json(['code'=>0,'msg'=>'领取失败']);
        }
    }

    //奖品池
    public function lock($data,$rand)
    {
        $array = $data;
        $ss = rand(100, 1);
        $grade = 0;
        for($i =0 ; $i<count($data); $i++)
        {
            if ($array[$i] == 0)
            {
                continue;
            }
            if ($ss <= $array[$i]) {
                $grade = $i;
                break;
            }
            $ss = $ss-$array[$i];
        }

        return $grade;
    }

    //领取红包  记得后台领取列表的活动id也要改
    public function new_get_red_packet(Request $request){
        // return response()->json(["status"=>0,"msg"=>"活动已结束"]);
        $prize =  DB::table('grplist')->where(['status'=>1])->get()->toArray();
        if(empty($prize)){
            return response()->json(['status'=>0,'msg'=>'活动暂未开启']);
        }

        $UserId = $request->session()->get('UserId');

        //一个月按30天算，查询最近30天抽奖记录
        // $enddata = date('Y-m-d 23:59:59', time());
        // $startdata = date("Y-m-d 00:00:00", strtotime('-30day'));
        // $last_moneylog = DB::table('moneylog')
        //     ->where(['moneylog_userid'=>$UserId,'moneylog_type'=>'领取红包'])
        //     ->where('created_at','>=',$startdata)
        //     ->where('created_at','<=',$enddata)
        //     ->get();
        //  $last_moneylog = DB::table('grplog')
        //     ->where(['user_id'=>$UserId,'activity_id'=>3])
        //     // ->where('created_at','>=',$startdata)
        //     // ->where('created_at','<=',$enddata)
        //     ->get();

        //近期是否购买过产品
        $startdata = DB::table('setings')->where('keyname','red_start_data')->value('value');
        $enddata = DB::table('setings')->where('keyname','red_end_data')->value('value');
        $time_start = strtotime($startdata);
        $time_end = strtotime($enddata);
        if(time() < $time_start || time()>$time_end){
            return response()->json(['status'=>0,'msg'=>'活动暂未开启!']);
        }

        $productbuy_amount = DB::table('productbuy')
            ->where(['userid'=>$UserId,'status'=>1])
            ->where('category_id','<>',11)
            ->whereNull('reason')
            // ->where(function ($query) {
            //     $query->whereNull('reason')
            //           ->orwhere('buy_from_id','>',0);
            // })
            ->where("updated_at",'>=',$startdata)
            ->where("updated_at",'<=',$enddata)
            ->sum('amount');
        if($productbuy_amount < 1000){
            return response()->json(["status"=>0,"msg"=>"您还未在活动时间内购一款满1000元产品，暂时无法参加幸运转盘活动。请先购买产品成功后再参加此幸运转盘活动"]);
        }
        //可领取次数
        $receive_time = intval($productbuy_amount / 1000);
        //领取红包次数
        $grplog_count = DB::table('grplog')
            ->where(['user_id'=>$UserId,'activity_id'=>1])
            ->where("created_at",'>=',$startdata)
            ->where("created_at",'<=',$enddata)
            ->count();
        if($grplog_count >= $receive_time){
            return response()->json(["status"=>0,"msg"=>"您还未在活动时间内购一款满1000元产品，暂时无法参加幸运转盘活动。请先购买产品成功后再参加此幸运转盘活动"]);
        }
        // $red_monthly_limit = DB::table('setings')->where('keyname','red_monthly_limit')->value('value');
        // if(count($last_moneylog) >= $red_monthly_limit){
        //     return response()->json(["status"=>0,"msg"=>"您近期已领取过".$red_monthly_limit."次红包，请勿重复领取"]);
        // }

        //红包奖品信息
        $prize =  DB::table('grplist')->where(['status'=>1])->get()->toArray();
        // dump($prize);
        $prize_probability = []; //商品概率集合
        $rand = 0;
        $gifts = [];//奖品池
        foreach ($prize as $k=>$v){
            //将奖品按权重数量加入奖品池
            for ($i = 0; $i < $v->weight; $i++) {
                $gifts[]=[
                    'id'=>$v->id,
                    'name'=>$v->name,
                    'value'=>$v->value,
                    'type'=>$v->type,
                ];
            }
        }

        $randMax = sizeof($gifts);//最大随机数
        $stockList = Db::table("grplist")->select('id','stock')->where(['status'=>1])->get()->toArray();

        $stockArr  = array_column($stockList, 'stock', 'id');//id键值对

        //打乱奖品池的奖品顺序
        shuffle($gifts);

        //产生一个随机数
        $rand = rand(0,$randMax-1);

        //取出奖品
        $gift = $gifts[$rand];

        //判断商品是否有库存
        $stock = $stockArr[$gift['id']];
        if($stock && $stock>0){
           $red_amount = $gift['value'];

        }else{
            $grp_log=[
                "user_id"=>$this->Member->id,
                "grp_id"=>$gift['id'],
                "grp_name"=>$gift['name'],
                "value"=>$gift['value'],
                "status"=>2,//1:领取到  2:没领到
                "created_at"=> Carbon::now(),
                "activity_id"=>101
            ];
            DB::table('grplog')->insert($grp_log);

           return response()->json(['status'=>1,'msg'=>'红包领完了',"data"=>$gift['id'],'type'=>'0']);
        }
        // dump($red_amount);
        DB::beginTransaction();
        try{
            /*站内消息*/
            // $msg=[
            //     "userid"=>$this->Member->id,
            //     "username"=>$this->Member->username,
            //     "title"=>"红包到账",
            //     "content"=>"领取红包".$red_amount."元",
            //     "from_name"=>"系统通知",
            //     "types"=>"领取红包",
            // ];
            // \App\Membermsg::Send($msg);
            /*站内消息END*/

            /*添加Moneylog*/
            $Member = Member::find($UserId);
            $yuanamount = $Member->amount;

            //扣除奖品库存，添加余额
            $res = Db::table("grplist")->where(["id"=>$gift['id'],'status'=>1])->where('stock','>','0')->decrement("stock");
            if(!$res){
                $grp_log=[
                    "user_id"=>$this->Member->id,
                    "grp_id"=>$gift['id'],
                    "grp_name"=>$gift['name'],
                    "value"=>$gift['value'],
                    "status"=>2,//1:领取到  2:没领到
                    "created_at"=> Carbon::now(),
                    "activity_id"=>101
                ];
                DB::table('grplog')->insert($grp_log);

                return response()->json(['status'=>1,'msg'=>'红包领完了',"data"=>$gift['id'],'type'=>'0']);
            }
            if($gift['type'] == 1){//3为实物
                $Member->increment('amount', $red_amount);
                $notice = '领取红包'.$red_amount.'元';
            }else{
                $notice = '领取奖品'.$gift['name'];
            }
            // $Member->increment('amount', $red_amount);

            $log=[
                "userid"=>$this->Member->id,
                "username"=>$this->Member->username,
                "money"=>$red_amount,
                "notice"=>$notice,
                "type"=>'领取红包',
                "status"=>'+',
                "yuanamount"=>$yuanamount,
                "houamount"=>$Member->amount,
                "ip"=>\Request::getClientIp(),
                "category_id"=>0,
                "product_id"=>0,
                "product_title"=>0,
            ];
            \App\Moneylog::AddLog($log);
            /*Moneylog  END*/

            $grp_log=[
                "user_id"=>$this->Member->id,
                "grp_id"=>$gift['id'],
                "grp_name"=>$gift['name'],
                "value"=>$gift['value'],
                "status"=>1,//1:领取到  2:没领到
                "created_at"=> Carbon::now(),
                 "activity_id"=>1
            ];
            DB::table('grplog')->insert($grp_log);
            DB::commit();

            return response()->json(["status"=>1,"msg"=>"返回成功".$red_amount,"data"=>$gift['id'],'type'=>'1']);
        }catch(\Exception $exception){
            DB::rollback();
            return response()->json(['status'=>0,'msg'=>'领取失败']);
        }
    }

    //我的红包记录
    public function my_red_packet_list(Request $request){
        $prize =  DB::table('grplist')->where(['status'=>1])->get()->toArray();
        if(empty($prize)){
            return response()->json(['status'=>0,'msg'=>'活动暂未开启']);
        }

        $UserId = $request->session()->get('UserId');

        // $data = DB::table('moneylog')
        //     ->select('moneylog_money','updated_at')
        //     ->where(['moneylog_userid'=>$UserId,'moneylog_type'=>'领取红包'])
        //     ->get(); //一个月一次，所以不做分页
        $data = DB::table('grplog')->select('grp_id','value','created_at','grp_name')->where(['status'=>1,'activity_id'=>1,'user_id'=>$UserId])->orderBy('created_at','desc')->get();
        foreach ($data as $v){
            $v->updated_at = $v->created_at;
            $v->moneylog_money = $v->value;
            // $v->grp_name = $v->grp_name.'(联系博客众联客服张丽娜，博客众聊号：1639875315)';
            $v->grp_name = $v->grp_name;
            $v->grp_id = $v->grp_id;
        }

        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$data]);
    }

    //红包页面信息
    public function get_red_packet_info(Request $request){
        $UserId = $request->session()->get('UserId');
        $prize =  DB::table('grplist')->where(['status'=>1])->get()->toArray();
        if(empty($prize)){
            return response()->json(['code'=>0,'msg'=>'活动暂未开启']);
        }
        //当前余额
        //当前账号
        $my_info = DB::table('member')->select('username','amount')->where('id',$UserId)->first();
        $my_info->username = substr_replace($my_info->username,'****',3,4);
        $data['my_info'] = $my_info;

         //抽奖次数
        //期限内购买过产品
        $startdata = DB::table('setings')->where('keyname','red_start_data')->value('value');
        $enddata = DB::table('setings')->where('keyname','red_end_data')->value('value');
        $productbuy_amount = DB::table('productbuy')
            ->where(['userid'=>$UserId,'status'=>1])
            ->where('category_id','<>',11)
            ->whereNull('reason')
            // ->where(function ($query) {
            //     $query->whereNull('reason')
            //           ->orwhere('buy_from_id','>',0);
            // })
            ->where("updated_at",'>=',$startdata)
            ->where("updated_at",'<=',$enddata)
            ->sum('amount');
        //可领取次数
        $receive_time = intval($productbuy_amount / 1000);
        //领取红包次数
        $grplog_count = DB::table('grplog')
            ->where(['user_id'=>$UserId,'activity_id'=>1])
            ->count();
        $my_time = $receive_time - $grplog_count;
        // if($my_time < 0){
        //     return response()->json(["status"=>0,"msg"=>"您还未在活动时间内购一款满1000元基金，暂时无法参加幸运转盘活动。请先购买基金成功后再参加此幸运转盘活动"]);
        // }
        if($my_time < 0){
           $my_time = 0;
        }
        $data['my_time'] = $my_time;

        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$data,'uid'=>$UserId]);
    }

    //邀请好友页面团队激励
    public function team_rewards(Request $request){
        $data = DB::table('memberticheng')->get();
        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$data]);
    }


    /**产品管理**/
    public function stproduct(Request $request)
    {
        /**基金**/
        $pageSize = $request->get('pageSize',10);
        $page = $request->get('page',1);
        $type = isset($request->type)?$request->type:12;
        $UserId =$request->session()->get('UserId');

       /* if(!in_array($type,[11,12,13])){
            return response()->json(["status"=>0,"msg"=>"参数错误"]);
        }*/
        /**项目分类菜单导航栏**/


        $ProductsFunds=DB::table("stproduct")
            //->select('id','category_name','category_id','name','content','brief','fee','store','firstlevel','secondlevel','sort','picurl','qtsl','created_at')
            ->where("category_id",$type)
            //->where('tzzt','<>',2)
            ->orderBy("sort","desc")
           ->paginate($pageSize, ['*'], 'page', $page);
        foreach ($ProductsFunds as $k=>$v){
           // $v->mrfh = sprintf("%.2f",$v->qtje * $v->jyrsy * 0.01 * $v->qtsl);//每日分红，具体值(元)
            //$v->mrfh2 = sprintf("%.2f",$v->qtje * $v->jyrsy2 * 0.01 * $v->qtsl);//每日累计分红，具体值(元)
        }
        $data['products'] = $ProductsFunds;

        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$data]);
        /**股权**/
    }
    /**项目**/
    public function stproductinfo(Request $request)
    {
        /**基金**/
        if(!$request->id || !is_numeric($request->id)){
            return response()->json(["status"=>0,"msg"=>"3333该项目不存在或已下架！"+$request->id]);
        }

        $product=DB::table("stproduct")
           // ->select('id','category_name','category_id','name','content','brief','fee','store','firstlevel','secondlevel','sort','picurl','qtsl','created_at')
            ->where("id",$request->id)
            ->first();
        $url = "http://".$_SERVER ['HTTP_HOST'];
        $product->content = str_replace("<img src=\"","<img src=\"".$url,$product->content);
        if($product){
            //日分红，具体值
           // $product->mrfh = sprintf("%.2f",$product->qtje * $product->jyrsy * 0.01 * $product->qtsl);

        }else{
            return response()->json(["status"=>0,"msg"=>"该项目不存在或已下架！"]);
        }


        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$product]);
        /****/
    }

     public function stproductbuy(Request $request)
    {
        /**基金**/
        $pageSize = $request->get('pageSize',10);
        $type = isset($request->type)?$request->type:12;
        $status = isset($request->status)?$status=$request->status:$status=10;
        $UserId =$request->session()->get('UserId');

       /* if(!in_array($type,[11,12,13])){
            return response()->json(["status"=>0,"msg"=>"参数错误"]);
        }*/
        /**项目分类菜单导航栏**/

        if($status==10){
            $ProductsFunds=DB::table("stproductbuy")
                ->where("userid",$UserId)
                ->where('status','<',2)
                ->orderBy("created_at","desc")
                ->paginate($pageSize);
        }else{
            $ProductsFunds=DB::table("stproductbuy")
                ->where("userid",$UserId)
                ->where("status",$status)

                //->where('tzzt','<>',2)
                ->orderBy("created_at","desc")
                ->paginate($pageSize);
        }

        foreach ($ProductsFunds as $k=>$v){
            $guige = DB::table("stproduct")->where('id',$v->stproductid)->value('guige');//卖出提示
            $v->guige = $guige;
          //  DB::table("stproductbuy")->
           // $v->mrfh = sprintf("%.2f",$v->qtje * $v->jyrsy * 0.01 * $v->qtsl);//每日分红，具体值(元)
            //$v->mrfh2 = sprintf("%.2f",$v->qtje * $v->jyrsy2 * 0.01 * $v->qtsl);//每日累计分红，具体值(元)
        }
        $data['list'] = $ProductsFunds;

        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$data]);
        /**股权**/
    }
      public function stproductbuyinfo(Request $request)
    {
        /**基金**/
        $pageSize = $request->get('pageSize',10);
        $type = isset($request->id)?$request->id:0;
        $UserId =$request->session()->get('UserId');

       /* if(!in_array($type,[11,12,13])){
            return response()->json(["status"=>0,"msg"=>"参数错误"]);
        }*/
        /**项目分类菜单导航栏**/


        $ProductsFunds=DB::table("stproductbuy")
            ->where("userid",$UserId)
            //->where('tzzt','<>',2)
            ->where("id",$request->id)
            ->first();
        if($ProductsFunds){
            //日分红，具体值
           // $product->mrfh = sprintf("%.2f",$product->qtje * $product->jyrsy * 0.01 * $product->qtsl);

        }else{
            return response()->json(["status"=>0,"msg"=>"该项目不存在或已下架！"]);
        }


        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$ProductsFunds]);
        /**股权**/
    }
    //获取app版
    public function getappversion(){
        $data['version'] = DB::table("setings")->where('keyname','app_ver')->value('value');//卖出提示
        $data['version_sn'] = DB::table("setings")->where('keyname','app_versn')->value('value');//卖出提示
        $data['downloadurl'] = DB::table("setings")->where('keyname','AppDownloadUrl')->value('value');//卖出提示
        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$data]);
    }
    //获取合作品牌
    public function hzpp(){
        $data['hzpp'] = DB::table("hzpp")->orderBy('id','ASC')->get();//卖出提示

        return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$data]);
    }
}


?>
