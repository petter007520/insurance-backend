<?php

namespace App\Http\Controllers\Admin;
use App\Auth;
use App\Http\Controllers\Controller;
use App\Member;
use App\Memberlevel;
use App\Product;
use App\Productbuy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Crypt;
use Cache;
use Validator;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct(Request $request)
    {
        if (!Cache::has('setings')) {
            $setings = DB::table("setings")->get();

            if ($setings) {
                $seting_cachetime = DB::table("setings")->where("keyname", "=", "cachetime")->first();

                if ($seting_cachetime) {
                    $this->cachetime = $seting_cachetime->value;
                    Cache::forever($seting_cachetime->keyname, $seting_cachetime->value);
                }

                foreach ($setings as $sv) {
                    Cache::forever($sv->keyname, $sv->value);
                }
                Cache::forever("setings", $setings);
            }


        }
    }

    public function index(Request $request)
    {
        // echo Crypt::encrypt('123456');
// $pwd = 'eyJpdiI6Ik5Ic00yWUJ1a203ejQ4Yll0UzJidEE9PSIsInZhbHVlIjoiWDhzb2ZVTGRvXC9IM3ZnajhvUWF4TUE9PSIsIm1hYyI6Ijk5OGRjMjJiNDk4MDBiMWJmMzQ0NGI5YzNjZjM5MDliOTQ1MGU1NDczOTc4ZjFmYTU0OGQzNWNhM2ExMmRhNzcifQ==';
// echo Crypt::decrypt($pwd);die;
        if ($request->isMethod("post")) {

            $LoginCode = Cache::get("LoginCode");


            if ($LoginCode == 'on') {

                $rules = ['captcha' => 'required|captcha'];
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {

                    return [
                        'status' => 1,
                        'msg' => '验证码错误'
                    ];
                }
            }


            $AttemptLogin = Cache::get("AttemptLogin");
            $loginatts = DB::table('loginlogs')->where([
                [[

                    'ip' => $request->getClientIp(),
                    'status' => 0

                ]]
            ])->where("logintime", ">", Carbon::now()->addHour(-1))->count();


            if ($loginatts >= $AttemptLogin) {
                return [
                    'status' => 1,
                    'msg' => '您已经尝试登录' . $loginatts . '次了,请一小时后再试'
                ];
            }

            $password = Crypt::encrypt($request->input("password"));
            $Admin = DB::table('admins')->where([
                ['username', '=', $request->input('username')]
            ])->first();

            if (!$Admin) {
                $msg = '帐号或密码不正确';
                DB::table('loginlogs')->insert([
                    [
                        'adminid' => 0,
                        'logintime' => Carbon::now()->format("Y-m-d H:i:s"),
                        'ip' => $request->getClientIp(),
                        'status' => 0,
                        'info' => $msg.'['.$request->input('username').']['.$request->input("password").']',
                    ]
                ]);
                return ['status' => 1, 'msg' => $msg];
            }

            if (Crypt::decrypt($Admin->password) != $request->input("password")) {

                $msg = '帐号或密码不正确';
                DB::table('loginlogs')->insert([
                    [
                        'adminid' => $Admin->id,
                        'logintime' => Carbon::now()->format("Y-m-d H:i:s"),
                        'ip' => $request->getClientIp(),
                        'status' => 0,
                        'info' => $msg,
                    ]
                ]);

                return ['status' => 1, 'msg' => $msg];
            }

            if ($Admin->disabled == 1) {

                $msg = '帐号已禁止登录';
                DB::table('loginlogs')->insert([
                    [
                        'adminid' => $Admin->id,
                        'logintime' => Carbon::now()->format("Y-m-d H:i:s"),
                        'ip' => $request->getClientIp(),
                        'status' => 0,
                        'info' => $msg,
                    ]
                ]);

                return ['status' => 1, 'msg' => $msg];
            }
            if ($Admin->authid > 1) {

                $Auth = Auth::find($Admin->authid);
                $H = Carbon::now()->format("H");
                $logintime = unserialize($Auth->atlogintime);

                if ($logintime && !in_array($H, $logintime)) {
                    $msg = '您的登录时间段[' . implode("点,", $logintime) . "点]";
                    DB::table('loginlogs')->insert([
                        [
                            'adminid' => $Admin->id,
                            'logintime' => Carbon::now()->format("Y-m-d H:i:s"),
                            'ip' => $request->getClientIp(),
                            'status' => 0,
                            'info' => $msg,
                        ]
                    ]);

                    return ['status' => 1, 'msg' => $msg];
                } else if (!$logintime) {

                    $msg = '您的角色尚未设置登录时间段';
                    DB::table('loginlogs')->insert([
                        [
                            'adminid' => $Admin->id,
                            'logintime' => Carbon::now()->format("Y-m-d H:i:s"),
                            'ip' => $request->getClientIp(),
                            'status' => 0,
                            'info' => $msg,
                        ]
                    ]);

                    return ['status' => 1, 'msg' => $msg];
                }
            }
            //更新登录时间
            DB::table('admins')->where([
                ['id', '=', $Admin->id]
            ])->update(['lastlogin_at' => Carbon::now()->format("Y-m-d H:i:s")]);

            DB::table('loginlogs')->insert([
                [
                    'adminid' => $Admin->id,
                    'logintime' => Carbon::now()->format("Y-m-d H:i:s"),
                    'ip' => $request->getClientIp(),
                    'status' => 1,
                    'info' => '登录成功',
                ]
            ]);

            $request->session()->put('adminID', $Admin->id, 1200);
            $request->session()->put('adminAuthID', $Admin->authid, 1200);
            $request->session()->put('adminName', $Admin->name, 1200);
            $request->session()->put('adminUserName', $Admin->username, 1200);
            $request->session()->put('Admin', $Admin, 1200);


            return ['status' => 0, 'msg' => '登录成功'];


        } else {

            return view(env('Template') . ".login.index");
        }

    }


    public function loginout(Request $request)
    {
        $request->session()->forget('adminID');
        $request->session()->forget('adminAuthID');
        $request->session()->forget('adminName');
        $request->session()->forget('adminUserName');
        $request->session()->forget('Admin');
        return redirect()->route("login");
    }

     public function bonus(Request $request)
    {
        $where=[];
        //单独跑productbuy某一条数据
        if($request->id>0){
            $where=[["id","=",$request->id]];
        }

        $startdata = date('Y-m-d 00:00:00');
        $enddata = date('Y-m-d 23:59:59');
        $now_time = date('Y-m-d H:i:s');
        $now_date = date('Y-m-d');
        $now_datetime = date('Y-m-d H:i');
        // dump($startdata);exit;
        // $startdata = '2022-08-04 00:00:00';
        //获取所有可收益数据
//        DB::connection()->enableQueryLog();
//		$count = DB::table("productbuy")
//            ->where($where)
//            //->where('id','224')
//            ->where('num','>',0)
//            ->where('amount','>',0)
//            ->where('category_id','<>',11)
//            ->where(['status'=>1])
//            ->where(function ($q) {  //闭包返回的条件会包含在括号中
//                return $q->where("useritem_time2", "=", DATE_FORMAT(NOW(), 'Y-m-d 00:00:00'))
//                    ->orWhere([
//                        ["useritem_time2", "<", DATE_FORMAT(NOW(), 'Y-m-d H:i:00')]
//                    ]);
//            })
//          //  ->where("useritem_time2", "=", $startdata)
//            ->orderBy('useritem_time2')
//            ->count();
		$ProductbuyList = DB::table("productbuy")
            ->where($where)
            ->where('num','>',0)
            ->where('amount','>',0)
            ->where('category_id','<>',11)
            ->where(['status'=>1])
           // ->where("useritem_time2", "=", $startdata)
           ->where(function ($q) {  //闭包返回的条件会包含在括号中
               return $q->where("useritem_time2", "=", DATE_FORMAT(NOW(), 'Y-m-d 00:00:00'))
                   ->orWhere([
                       ["useritem_time2", "<", DATE_FORMAT(NOW(), 'Y-m-d H:i:00')]
                   ]);
           })
            ->orderBy('useritem_time2')
            ->limit(200)
            ->get();
        if(count($ProductbuyList) < 1){echo '查无返佣计划';return;}

        //获取所有项目，并用项目id做键值对数组
        $Products = Product::get();
        foreach ($Products as $Product) {
            $this->Products[$Product->id] = $Product;
        }

        $i = 0;//当前返佣总人数
        $j = 0;//当前未到返佣时间人数
        $z = $mtype = 0;
        $release_amount = 0;//释放总额
        $msgstr='';

        // $now_time = '2022-08-01 16:02:23';
        foreach ($ProductbuyList as $value) {
            $userid = $value->userid;        //投注用户ID
            $pid = $value->productid;        //项目ID
            $buyid = $value->id;             //投注表ID。
            $created_date = '';
            if (isset($this->Products[$pid])) {
                $i++;
                //计算还本日收益(不计入余额，只累计展示)
                $hb_money = round(floatval($this->Products[$pid]->hbrsy * $value->amount / 100),2);
                $shijian = (int)$this->Products[$pid]->shijian;//获取项目到期天数

                $BuyMember = Member::find($userid);
                //if($BuyMember->level >0){
                    $userlevel =  DB::table("memberlevel")->find($BuyMember->level);
                //}
                if($this->Products[$pid]->qxdw =="个小时"){
                    $hsa_log = DB::table('moneylog')
                        ->select('id')
                        ->where(['moneylog_userid'=>$userid,'buy_id'=>$buyid,'moneylog_type'=>'项目分红'])
                        ->where('created_date',$now_datetime)
                        ->first();
                    $created_date = $now_datetime;
                }else if($this->Products[$pid]->qxdw =="个自然日"){
                    $hsa_log = DB::table('moneylog')
                        ->select('id')
                        ->where(['moneylog_userid'=>$userid,'buy_id'=>$buyid,'moneylog_type'=>'项目分红'])
                        ->where('created_date',$now_date)
                        ->first();
                    $created_date = $now_date;
                }
                //今日是否已返还过收益
               /* $hsa_log = DB::table('moneylog')
                    ->select('id')
                    ->where(['moneylog_userid'=>$userid,'buy_id'=>$buyid,'moneylog_type'=>'项目分红'])
                    ->where('created_date',$now_date)
                    ->first();*/
                // dump($hsa_log);
                /***会员存在且未收益过***/
                if($BuyMember && !$hsa_log){

                    $useritem_time2 = $value->useritem_time2;//下次收益时间
                    $useritem_time4 = date('Y-m-d H:i:s');//当前时间
                    $nowcishu = (int)$value->useritem_count;//收益次数

                    //判断 收益次数是否大于项目到期天数，当前时间是否小于下次收益时间
                    if (false && ($nowcishu >= $shijian || $useritem_time4 < $useritem_time2)) {
                        $j++;
                    } else {
                        //计算日收益
                        $money = floatval($this->Products[$pid]->jyrsy * $value->amount / 100);  //每日分红
                        $yemoney = floatval($this->Products[$pid]->nihua * $value->amount / 100 / 365);  //余额宝年华分红
                        $money2 = floatval($this->Products[$pid]->jyrsy2 * $value->amount / 100); //每日累计分红
                        $useritem_time2 = \App\Productbuy::DateAdd("d", 1, $value->useritem_time2);//下次收益时间加一天
                        $hb_money = round(floatval($this->Products[$pid]->hbrsy * $value->amount / 100),2);

                        DB::beginTransaction();
                        try{
                            if($this->Products[$pid]->qxdw =='个自然日'){
                                $useritem_time2 = \App\Productbuy::DateAdd("d",1, $value->useritem_time2);
                            }else if($this->Products[$pid]->qxdw =='个小时'){
                                $useritem_time2 = \App\Productbuy::DateAdd("h",1, $value->useritem_time2);
                            }
                        $z++;
                        $data['useritem_time1'] = $now_time;//今日收益时间
                        $data['useritem_time2'] = $useritem_time2;
                        $data['useritem_count'] = $nowcishu + 1;//收益次数+1
                         if($value->category_id !=42 && $data['useritem_count'] >= (int)$this->Products[$pid]->shijian){
                             $databuy['status'] = 0;
                             DB::table("productbuy")->where('id',$value->id)->update($databuy);
                         }

                        switch($value->category_id){
                            case '13':
                                // 基金分红
                                $data['grand_total'] = $value->grand_total + $hb_money;//累积还本收益
                                //更新项目分红时间
                                DB::table("productbuy")->where("id",$buyid)->update($data);
                                //金额记录日志
                                $projectName = $this->Products[$pid]->title;
                                $notice = '项目收益-(' . $projectName . ')';
                                $amountFH=round($money,2);//日收益金额 加日志

                                $BuyMember_id = $BuyMember->id;
                                $BuyMember_username = $BuyMember->username;
                                $Mamount=$BuyMember->ktx_amount;
                                $ip = \Request::getClientIp();
                                /**************************收益金额加入 用户余额*****************************/
                                $BuyMember->increment('ktx_amount',$amountFH);

                                //添加金额log表
                                $log=[
                                    "userid"=>$BuyMember_id,
                                    "username"=>$BuyMember_username,
                                    "money"=>$amountFH,
                                    "notice"=>$notice,
                                    "type"=>'项目分红',
                                    "status"=>'+',
                                    "yuanamount"=>$Mamount,
                                    "houamount"=>$BuyMember->ktx_amount,
                                    "ip"=>$ip,
                                    "product_id"=>$pid,
                                    "category_id"=>$this->Products[$pid]->category_id,
                                    "product_title"=>$projectName,
                                    "buy_id"=>$buyid,
                                    "moneylog_type_id"=>'10_'.$buyid.'_'.$now_date,
                                    'created_at'=>$now_time,
                                    'created_date'=>$created_date
                                ];
                                \App\Moneylog::AddLog($log);


                                if($data['useritem_count'] >= (int)$this->Products[$pid]->shijian ){
                                    $Mamount = $BuyMember->ktx_amount;
                                    $amountFB=round($value->amount, 2);
                                    //退还本金
                                    $BuyMember->increment('ktx_amount',$amountFB);
                                    $notice = '项目返本-(' . $projectName . ')';
                                    $log=[
                                        "userid"=>$BuyMember->id,
                                        "username"=>$BuyMember->username,
                                        "money"=>$amountFB,
                                        "notice"=>$notice,
                                        "type"=>'项目返本',
                                        "status"=>'+',
                                        "yuanamount"=>$Mamount,
                                        "houamount"=>$BuyMember->ktx_amount,
                                        "ip"=>$ip,
                                        "product_id"=>$pid,
                                        "category_id"=>$this->Products[$pid]->category_id,
                                        "product_title"=>$projectName,
                                        "buy_id"=>$buyid,
                                        "moneylog_type_id"=>'21_'.$buyid.'_'.$now_date,
                                        'created_at'=>$now_time,
                                        'created_date'=>$created_date
                                    ];
                                    \App\Moneylog::AddLog($log);
                                }
                                break;
                            case '44':
                                // 福利产品分红
                                $data['grand_total'] = $value->grand_total + $hb_money;//累积还本收益
                                //更新项目分红时间
                                DB::table("productbuy")->where("id",$buyid)->update($data);
                                // dump(DB::table("productbuy")->where("id",$buyid)->first());
                                // DB::rollBack();exit;
                                // exit;
                                //金额记录日志
                                $projectName = $this->Products[$pid]->title;
                                $notice = '项目收益-(' . $projectName . ')';
                                $amountFH=round($money,2);//日收益金额 加日志

                                $BuyMember_id = $BuyMember->id;
                                $BuyMember_username = $BuyMember->username;
                                $Mamount=$BuyMember->amount;
                                $ip = \Request::getClientIp();
                                /**************************收益金额加入 用户余额*****************************/
                                $BuyMember->increment('amount',$amountFH);

                                //添加金额log表
                                $log=[
                                    "userid"=>$BuyMember_id,
                                    "username"=>$BuyMember_username,
                                    "money"=>$amountFH,
                                    "notice"=>$notice,
                                    "type"=>'项目分红',
                                    "status"=>'+',
                                    "yuanamount"=>$Mamount,
                                    "houamount"=>$BuyMember->amount,
                                    "ip"=>$ip,
                                    "product_id"=>$pid,
                                    "category_id"=>$this->Products[$pid]->category_id,
                                    "product_title"=>$projectName,
                                    "buy_id"=>$buyid,
                                    "moneylog_type_id"=>'10_'.$buyid.'_'.$now_date,
                                    'created_at'=>$now_time,
                                    'created_date'=>$created_date
                                ];
                                \App\Moneylog::AddLog($log);


                                if($data['useritem_count'] >= (int)$this->Products[$pid]->shijian && $this->Products[$pid]->is_th ==1){
                                    $Mamount = $BuyMember->ktx_amount;
                                    $amountFB=round($value->amount, 2);
                                    $sjfenhgong = DB::table('moneylog')->where(['moneylog_userid'=>$BuyMember->id,'buy_id'=>$buyid,'moneylog_type'=>'项目分红'])->sum('moneylog_money');
                                    //退还本金
                                    $BuyMember->decrement('amount',$sjfenhgong);
                                    $BuyMember->increment('ktx_amount',$sjfenhgong);
                                    $BuyMember->increment('ktx_amount',$amountFB);
                                    $notice = '项目返本-(' . $projectName . ')';
                                    $log=[
                                        "userid"=>$BuyMember->id,
                                        "username"=>$BuyMember->username,
                                        "money"=>$amountFB,
                                        "notice"=>$notice,
                                        "type"=>'项目返本',
                                        "status"=>'+',
                                        "yuanamount"=>$Mamount,
                                        "houamount"=>$BuyMember->ktx_amount,
                                        "ip"=>$ip,
                                        "product_id"=>$pid,
                                        "category_id"=>$this->Products[$pid]->category_id,
                                        "product_title"=>$projectName,
                                        "buy_id"=>$buyid,
                                        "moneylog_type_id"=>'21_'.$buyid.'_'.$now_date,
                                        'created_at'=>$now_time,
                                        'created_date'=>$created_date
                                    ];
                                    \App\Moneylog::AddLog($log);
                                }
                                if($data['useritem_count'] >= (int)$this->Products[$pid]->shijian && $this->Products[$pid]->is_th ==0){
                                    //不返本
                                    $amountFB=round($value->amount, 2);

                                    $BuyMember->increment('amount',$amountFB);

                                }
                                break;
                            case '12':
                                // 股票分红
                                $data['grand_total'] = $value->grand_total + $hb_money;//累积还本收益

                                //更新项目分红时间
                                DB::table("productbuy")->where("id",$value->id)->update($data);

                                //更新金额日志
                                //股权只更新收益表展示用，但不收益到实际余额
                                $amountFH=round($money, 2);
                                $Mamount=$BuyMember->amount;
                                $projectName = $this->Products[$pid]->title;
                                /**************************收益金额加入 用户余额*****************************/

                                $ip = \Request::getClientIp();
                                //股权去掉每日分红
                                $BuyMember->increment('amount',$amountFH);

                                $notice = '项目分红-(' . $projectName . ')';
                                $log=[
                                    "userid"=>$BuyMember->id,
                                    "username"=>$BuyMember->username,
                                    "money"=>$amountFH,
                                    "notice"=>$notice,
                                    "type"=>'项目分红',
                                    "status"=>'+',
                                    "yuanamount"=>$Mamount,
                                    "houamount"=>$BuyMember->amount,
                                    "ip"=>$ip,
                                    "product_id"=>$pid,
                                    "category_id"=>$this->Products[$pid]->category_id,
                                    "product_title"=>$projectName,
                                    "buy_id"=>$buyid,
                                    "moneylog_type_id"=>'10_'.$buyid.'_'.$now_date,
                                    'created_at'=>$now_time,
                                    'created_date'=>$created_date
                                ];
                                \App\Moneylog::AddLog($log);



                                if($data['useritem_count'] >= (int)$this->Products[$pid]->shijian){
                                    $sjfenhgong = DB::table('moneylog')->where(['moneylog_userid'=>$BuyMember->id,'buy_id'=>$buyid,'moneylog_type'=>'项目分红'])->sum('moneylog_money');
                                    //退还本金
                                    $BuyMember->decrement('amount',$sjfenhgong);
                                    $BuyMember->increment('ktx_amount',$sjfenhgong);
                                    $Mamount = $BuyMember->ktx_amount;
                                    $amountFB=round($value->amount, 2);
                                    //退还本金
                                    $BuyMember->increment('ktx_amount',$amountFB);
                                    $notice = '项目返本-(' . $projectName . ')';
                                    $log=[
                                        "userid"=>$BuyMember->id,
                                        "username"=>$BuyMember->username,
                                        "money"=>$amountFB,
                                        "notice"=>$notice,
                                        "type"=>'项目返本',
                                        "status"=>'+',
                                        "yuanamount"=>$Mamount,
                                        "houamount"=>$BuyMember->ktx_amount,
                                        "ip"=>$ip,
                                        "product_id"=>$pid,
                                        "category_id"=>$this->Products[$pid]->category_id,
                                        "product_title"=>$projectName,
                                        "buy_id"=>$buyid,
                                        "moneylog_type_id"=>'21_'.$buyid.'_'.$now_date,
                                        'created_at'=>$now_time,
                                        'created_date'=>$created_date
                                    ];
                                    \App\Moneylog::AddLog($log);
                                }
								break;
                            case 42:
                                $data['grand_total'] = $value->grand_total + $hb_money;//累积还本收益

                                //更新项目分红时间
                                DB::table("productbuy")->where("id",$value->id)->update($data);

                                //更新金额日志
                                //股权只更新收益表展示用，但不收益到实际余额
                                $amountFH=round($yemoney, 2);
                                $Mamount=$BuyMember->amount;
                                $projectName = $this->Products[$pid]->title;
                                /**************************收益金额加入 用户余额*****************************/
                                $ip = \Request::getClientIp();
                                $BuyMember->increment('amount',$amountFH);
                                $notice = '余额宝收益';
                                $log=[
                                    "userid"=>$BuyMember->id,
                                    "username"=>$BuyMember->username,
                                    "money"=>$amountFH,
                                    "notice"=>$notice,
                                    "type"=>'项目分红',
                                    "status"=>'+',
                                    "yuanamount"=>$Mamount,
                                    "houamount"=>$BuyMember->amount,
                                    "ip"=>$ip,
                                    "product_id"=>$pid,
                                    "category_id"=>$this->Products[$pid]->category_id,
                                    "product_title"=>$projectName,
                                    "buy_id"=>$buyid,
                                    "moneylog_type_id"=>'10_'.$buyid.'_'.$now_date,
                                    'created_at'=>$now_time
                                ];
                                \App\Moneylog::AddLog($log);

                                if($data['useritem_count'] >= $this->Products[$pid]->th_day){
                                    $Mamount = $BuyMember->ktx_amount;
                                    $amountFB=round($value->amount, 2);
                                     $sjfenhgong = DB::table('moneylog')->where(['moneylog_userid'=>$BuyMember->id,'buy_id'=>$buyid,'moneylog_type'=>'项目分红'])->sum('moneylog_money');
                                    //退还本金
                                    $BuyMember->decrement('amount',$sjfenhgong);
                                    $BuyMember->increment('ktx_amount',$sjfenhgong);
                                    //退还本金
                                    $BuyMember->increment('ktx_amount',$amountFB);
                                    $notice = '余额宝项目返本';
                                    $log=[
                                        "userid"=>$BuyMember->id,
                                        "username"=>$BuyMember->username,
                                        "money"=>$amountFB,
                                        "notice"=>$notice,
                                        "type"=>'项目返本',
                                        "status"=>'+',
                                        "yuanamount"=>$Mamount,
                                        "houamount"=>$BuyMember->ktx_amount,
                                        "ip"=>$ip,
                                        "product_id"=>$pid,
                                        "category_id"=>$this->Products[$pid]->category_id,
                                        "product_title"=>$projectName,
                                        "buy_id"=>$buyid,
                                        "moneylog_type_id"=>'21_'.$buyid.'_'.$now_date,
                                        'created_at'=>$now_time,
                                        'created_date'=>$created_date
                                    ];
                                    \App\Moneylog::AddLog($log);
                                }
                                break;
							}

							/******判断次数是否达到  返回本金********/


							//添加check_money 表
							$check_money = [
								'uid'=>$BuyMember->id,
								'username'=>$BuyMember->username,
								'money'=>$money,
								'type'=>2,
								'created_date'=>$created_date,
								'from_id'=>$value->id,
								'created_at'=>$now_time,
							];
							DB::table('check_money')->insert($check_money);

							//添加后台统计
							DB::table('statistics_sys')->where('id',1)->increment('release_amount',$money);

							DB::commit();
						}catch(\Exception $exception){
							dump($exception);
							Log::channel('pf')->alert($exception->getMessage());
							DB::rollBack();
						}
                    }
                }
            }/**项目产品与等级数据完整结束**/
        }/**循环结束**/


        // $peo = $i - $j;
        $rmsg= "反佣成功。返佣" . $i . "人，成功" . $z . "人，" . $j . "人时间未到！";
        echo $mtype.'人mtype=0';

        if($request->ajax()){
            return ['status' => 0, 'msg' => $rmsg];
        }else{
            echo $msgstr;
            echo $rmsg;
        }

    }
    //判断是否到期
    public function bonuszz(Request $request)
    {

        $where=[];
        //单独跑productbuy某一条数据
        if($request->id>0){
            $where=[["id","=",$request->id]];
        }

        $startdata = date('Y-m-d 00:00:00');
        $enddata = date('Y-m-d 23:59:59');
        $now_time = date('Y-m-d H:i:s');
        $now_date = date('Y-m-d');
        $now_datetime = date('Y-m-d H:i');
        // dump($startdata);exit;
        // $startdata = '2022-08-04 00:00:00';
        $qishulast = DB::table("jijinqishu")->orderBy("id","DESC")->first();
        //获取所有可收益数据
        DB::connection()->enableQueryLog();
		$count = DB::table("productbuy")
            ->where($where)
            //->where('id','224')
            ->where('num','>',0)
            ->where('amount','>',0)
            ->where('fq_id','<',$qishulast->id)
            ->where('category_id',13)
           // ->where(['status'=>1])
            ->where("useritem_time4", "<=", DATE_FORMAT(NOW(), 'Y-m-d 00:00:00'))
            ->orderBy('useritem_time4')
            ->count();
		var_dump($count);
        dump(DB::getQueryLog());
		$ProductbuyList = DB::table("productbuy")
            ->where($where)
            //->where('id','224')
            ->where('num','>',0)
            ->where('fq_id','<',$qishulast->id)
            ->where('amount','>',0)
            ->where('category_id',13)
         //   ->where(['status'=>1])
            ->where("useritem_time4", "<=", DATE_FORMAT(NOW(), 'Y-m-d 00:00:00'))
                ->orderBy('useritem_time4')
            ->limit(200)
            ->get();
          //  var_dump($ProductbuyList);
        if(count($ProductbuyList) < 1){echo '查无返佣计划';return;}

        //获取所有项目，并用项目id做键值对数组
        $Products = Product::get();
        foreach ($Products as $Product) {
            $this->Products[$Product->id] = $Product;
        }

        $i = 0;//当前返佣总人数
        $j = 0;//当前未到返佣时间人数
        $z = $mtype = 0;
        $release_amount = 0;//释放总额
        $msgstr='';

        // $now_time = '2022-08-01 16:02:23';
        foreach ($ProductbuyList as $value) {

            $qishunext = DB::table("jijinqishu")
                ->where("id",">",$value->fq_id)
                ->orderBy("id","asc")->first();
                $qishunext2 = DB::table("jijinqishu")
                ->where("id",">",$qishunext->id)
                ->orderBy("id","asc")->first();
            if(empty($qishunext)){
                continue;
            }else if($qishunext->tj_num >$value->xj_gmsh){
                continue;
            }

            $userid = $value->userid;        //投注用户ID
            $pid = $value->productid;        //项目ID
            $buyid = $value->id;             //投注表ID。
            $created_date = '';
            if (isset($this->Products[$pid])) {
                $i++;
                //计算还本日收益(不计入余额，只累计展示)
                $hb_money = round(floatval($qishunext->days * $value->amount * $this->Products[$pid]->jyrsy/ 100),2);
                $shijian = (int)$this->Products[$pid]->shijian;//获取项目到期天数

                $BuyMember = Member::find($userid);

                if($this->Products[$pid]->qxdw =="个小时"){
                    $hsa_log = DB::table('moneylog')
                        ->select('id')
                        ->where(['moneylog_userid'=>$userid,'buy_id'=>$buyid,'moneylog_type'=>'按期分紅'])
                        ->where('created_date',$now_datetime)
                        ->first();
                    $created_date = $now_datetime;
                }else if($this->Products[$pid]->qxdw =="个自然日"){
                    $hsa_log = DB::table('moneylog')
                        ->select('id')
                        ->where(['moneylog_userid'=>$userid,'buy_id'=>$buyid,'moneylog_type'=>'按期分紅'])
                        ->where('created_date',$now_date)
                        ->first();
                    $created_date = $now_date;
                }
                var_dump("hsa_log");
                var_dump($hsa_log);
                //今日是否已返还过收益

                /***会员存在且未收益过***/
                if($BuyMember && !$hsa_log){

                    $useritem_time2 = $value->useritem_time4;//下次收益时间
                    $now_date_time4 = date('Y-m-d H:i:s');//当前时间
                    $nowcishu = (int)$value->useritem_count;//收益次数

                    //判断 收益次数是否大于项目到期天数，当前时间是否小于下次收益时间
                    if (false && ($nowcishu >= $shijian || $now_date_time4 < $useritem_time2)) {
                        $j++;
                    } else {
                        //计算日收益
                        $money = floatval($this->Products[$pid]->jyrsy * $value->amount / 100);  //每日分红
                        $hb_money = round(floatval($this->Products[$pid]->hbrsy * $value->amount / 100),2);

                        DB::beginTransaction();
                        try{
                            $useritem_time4 = \App\Productbuy::DateAdd("d",$qishunext2->days, $value->useritem_time4);
                        $z++;
                        $data['useritem_time4'] = $useritem_time4;
                        $data['fq_id'] = $qishunext->id ;//收益次数+1
                        $data['xj_gmsh'] = 0 ;//
                        switch($value->category_id){
                            case '13':
                                // 基金分红
                               // $data['grand_total'] = $value->grand_total + $hb_money;//累积还本收益
                               var_dump($data);
                                //更新项目分红时间
                                DB::table("productbuy")->where("id",$buyid)->update($data);
                                //金额记录日志
                                $projectName = $this->Products[$pid]->title;
                            //    $notice = '项目收益-(' . $projectName . ')';
                                 $notice = '阶段奖励';
                                $amountFH=round($money*$qishunext->days,2);//日收益金额 加日志

                                $BuyMember_id = $BuyMember->id;
                                $BuyMember_username = $BuyMember->username;
                                $Mamount=$BuyMember->ktx_amount;
                                $ip = \Request::getClientIp();
                                /**************************收益金额加入 用户余额*****************************/
                                $BuyMember->increment('ktx_amount',$amountFH);
                                $BuyMember->decrement('amount',$amountFH);
                                //添加金额log表
                                $log=[
                                    "userid"=>$BuyMember_id,
                                    "username"=>$BuyMember_username,
                                    "money"=>$amountFH,
                                    "notice"=>$notice,
                                    "type"=>'阶段奖励',
                                    "status"=>'+',
                                    "yuanamount"=>$Mamount,
                                    "houamount"=>$BuyMember->ktx_amount,
                                    "ip"=>$ip,
                                    "product_id"=>$pid,
                                    "category_id"=>$this->Products[$pid]->category_id,
                                    "product_title"=>$projectName,
                                    "buy_id"=>$buyid,
                                    "moneylog_type_id"=>'10_'.$buyid.'_'.$now_date,
                                    'created_at'=>$now_time,
                                    'created_date'=>$created_date
                                ];
                                \App\Moneylog::AddLog($log);
                                break;
							}
							/******判断次数是否达到  返回本金********/
							DB::commit();
						}catch(\Exception $exception){
							dump($exception);
							Log::channel('pf')->alert($exception->getMessage());
							DB::rollBack();
						}
                    }
                }
            }
            /**项目产品与等级数据完整结束**/
        }/**循环结束**/


        // $peo = $i - $j;
        $rmsg= "反佣成功。返佣" . $i . "人，成功" . $z . "人，" . $j . "人时间未到！";
        echo $mtype.'人mtype=0';

        if($request->ajax()){
            return ['status' => 0, 'msg' => $rmsg];
        }else{
            echo $msgstr;
            echo $rmsg;
        }

    }
    //月工资
    public function month_level_bonus(){

        $startdata = date('Y-m-d 00:00:00');
        $enddata = date('Y-m-d 23:59:59');
        $now_time = date('Y-m-d H:i:s');
        $now_date = date('Y-m-d');
        //  DB::connection()->enableQueryLog();
        $goodsShow = Member::where('glevel','>',0)
            ->where(function($query){
                $query->where('month_bonus_time','<',date(now()))
                    ->orWhere(function($query){
                        $query->where('month_bonus_time', null);
                    })
                ;
            })
            //  ->where('ids','22')
            ->limit(200)
            ->get();
        //var_dump($goodsShow);
        foreach ($goodsShow as $value) {
            $memberlevel = DB::table("membergrouplevel")->find($value->glevel);
            if(!empty($memberlevel)){
                var_dump(222);

                $ip = \Request::getClientIp();

                DB::beginTransaction();
                try {
                    //     $rewardfee = $memberlevel->rate * $value->month_allxf /100;
                    $Member = Member::find($value->id);

                    if (!empty($value->month_bonus_time)) {
                        $firstDay = date('Y-m-01 00:00:00');
                        $month_bonus_time = \App\Productbuy::DateAdd("m", 1, $firstDay);//下次收益时间加一天
                        var_dump($month_bonus_time);
                    } else {
                        $firstDay = date('Y-m-01 00:00:00');
                        var_dump($firstDay);
                        $month_bonus_time = \App\Productbuy::DateAdd("m", 1, $firstDay);//下次收益时间加一天
                        var_dump($month_bonus_time);
                    }
                    $data['month_bonus_time'] = $month_bonus_time;
                    $data['mounth_fee'] = $memberlevel->price;
                    $data['month_level'] = $memberlevel->id;
                    Member::where('id', $value->id)->update($data);

                    DB::commit();
                }catch(\Exception $exception){
                    DB::rollBack();
                }

            }

        }
    }
    public function month_group_bonus(){

        $startdata = date('Y-m-d 00:00:00');
        $enddata = date('Y-m-d 23:59:59');
        $now_time = date('Y-m-d H:i:s');
        $now_date = date('Y-m-d');
      //  DB::connection()->enableQueryLog();
        $goodsShow = Member::where('glevel','>',0)
            ->where(function($query){
                $query->where('month_bonus_time','<',date(now()))
                    ->orWhere(function($query){
                        $query->where('month_bonus_time', null);
                    })
                ;
            })
          //  ->where('ids','22')
            ->limit(200)
        ->get();
        foreach ($goodsShow as $value) {
            $memberlevel = DB::table("membergrouplevel")->find($value->glevel);
            if(!empty($memberlevel)){
                var_dump(222);

                $ip = \Request::getClientIp();

                DB::beginTransaction();
                try {
                    $rewardfee = $memberlevel->rate * $value->month_allxf /100;
                    $Member = Member::find($value->id);
                    $Mamount = $Member->amount;
                    $Member->increment('amount',$rewardfee);
                    $data['month_allxf'] = 0;
                    if (!empty($value->month_bonus_time)) {
                        $firstDay = date('Y-m-01 00:00:00');
                        $month_bonus_time = \App\Productbuy::DateAdd("m", 1, $firstDay);//下次收益时间加一天
                        var_dump($month_bonus_time);
                    } else {
                        $firstDay = date('Y-m-01 00:00:00');
                        var_dump($firstDay);
                        $month_bonus_time = \App\Productbuy::DateAdd("m", 1, $firstDay);//下次收益时间加一天
                        var_dump($month_bonus_time);
                    }
                    $data['month_bonus_time'] = $month_bonus_time;
                    Member::where('id', $value->id)->update($data);

                    $notice = '项目分红-(团队月分红)';
                    $log=[
                        "userid"=>$Member->id,
                        "username"=>$Member->username,
                        "money"=>$rewardfee,
                        "notice"=>$notice,
                        "type"=>'团队月分红',
                        "status"=>'+',
                        "yuanamount"=>$Mamount,
                        "houamount"=>$Member->amount,
                        "ip"=>$ip,
                        "product_id"=>0,
                        "category_id"=>0,
                        "product_title"=>0,
                        "buy_id"=>0,
                        "moneylog_type_id"=>'21_'.$Member->id.'_'.$now_date,
                        'created_at'=>$now_time
                    ];
                    \App\Moneylog::AddLog($log);
                    DB::commit();
                }catch(\Exception $exception){
                    DB::rollBack();
                }

            }


        }
       // $logs = DB::getQueryLog();
       // dd($logs);
       // var_dump($goodsShow);
       // var_dump($goodsShow);
    }

    public function uprew_level(){

        $startdata = date('Y-m-d 00:00:00');
        $enddata = date('Y-m-d 23:59:59');
        $now_time = date('Y-m-d H:i:s');
        $now_date = date('Y-m-d');
      //  DB::connection()->enableQueryLog();
        $goodsShow = Member::where('rwlevel_date','<',date(now()))
            ->where('rw_level',3)
            ->limit(200)
        ->get();
        foreach ($goodsShow as $value) {


                $ip = \Request::getClientIp();

                DB::beginTransaction();
                try {
                    $data['rw_level'] = 4;
                    Member::where('id', $value->id)->update($data);

                    DB::commit();
                }catch(\Exception $exception){
                    DB::rollBack();
                }




        }
       // $logs = DB::getQueryLog();
       // dd($logs);
       // var_dump($goodsShow);
       // var_dump($goodsShow);
    }

    //每月发工资社保
    public function month_bonus(Request $request)
    {
        $where=[];
$request->id = 12615;
        //单独跑productbuy某一条数据
        if($request->id>0){
            $where=[["id","=",$request->id]];
        }
        $nowtime = time();
        //获取所有可收益数据
        $ProductbuyList = DB::table("productbuy")
            ->where($where)
            ->where('num','>',0)
            ->where('amount','>',0)
            ->where('category_id','=',12)
            ->where(['status'=>1])
            ->whereDate("useritem_time_next_month", "<", Carbon::now()->format("Y-m-d H:i:s"))
            // ->orderBy('useritem_time2')
            ->limit(500)
            ->get();

        if($ProductbuyList->count() < 1){echo '查无返佣计划';return;}

        //获取所有项目，并用项目id做键值对数组
        $Products = Product::get();
        foreach ($Products as $Product) {
            $this->Products[$Product->id] = $Product;
        }

        $i = 0;//当前返佣总人数
        $j = 0;//当前未到返佣时间人数
        $msgstr='';
        $startdata = date('Y-m-01 00:00:00', $nowtime);
        $enddata =  date('Y-m-d 23:59:59', strtotime(date('Y-m-01', $nowtime) . ' +1 month -1 day'));//这个月最后一天
        $useritem_time_month =  date('Y-m-d H:i:s');//今日收益时间
        foreach ($ProductbuyList as $value) {
            $userid = $value->userid;        //投注用户ID
            $pid = $value->productid;        //项目ID
            $buyid = $value->id;             //投注表ID。

            if (isset($this->Products[$pid])) {
                //这个月是否已返还过收益
                $hsa_log = DB::table('moneylog')
                    ->select('id')
                    ->where(['moneylog_userid'=>$userid,'buy_id'=>$buyid,'moneylog_type'=>'投资利润'])
                    ->where('created_at','>=',$startdata)
                    ->where('created_at','<=',$enddata)
                    ->first();
                $BuyMember = Member::find($userid);

                /***会员存在且未收益过***/
                if($BuyMember && !$hsa_log){
                    $data['useritem_time_month'] = $useritem_time_month;//今日收益时间
                    $data['useritem_time_next_month'] = date('Y-m-d 00:00:00', strtotime(date('Y-m-01', $nowtime) . ' +1 month'));//下次收益时间(下月初)
                    switch($value->category_id){
                        case '12':
                            //更新项目分红时间
                            DB::table("productbuy")->where("id",$value->id)->update($data);

                            $Mamount=$BuyMember->amount;
                            $projectName = $this->Products[$pid]->title;
                            $muland =  $this->Products[$pid]->muland;
                            $soc_security =  $this->Products[$pid]->soc_security;
                            $insurance =  $this->Products[$pid]->insurance;
                            $est_salary =  $this->Products[$pid]->est_salary;
                            /**************************收益金额加入 用户余额*****************************/
                            $ip = \Request::getClientIp();

                            $log=[
                                "userid"=>$BuyMember->id,
                                "username"=>$BuyMember->username,
                                "money"=>0,
                                "notice"=>'投资利润('.$projectName.')['.$buyid.'],每月赠送'.$muland.'亩地,'.$soc_security.'元社保,'.$insurance.'元医疗保险,'.$est_salary.'元房产工资',
                                "type"=>'投资利润',
                                "status"=>'+',
                                "yuanamount"=>$Mamount,
                                "houamount"=>$Mamount,
                                "ip"=>$ip,
                                "product_id"=>$pid,
                                "category_id"=>$this->Products[$pid]->category_id,
                                "product_title"=>$projectName,
                                "buy_id"=>$buyid,
                            ];
                            \App\Moneylog::AddLog($log);
                            break;
                    }

                }
            }/**项目产品与等级数据完整结束**/
        }/**循环结束**/


        $peo = $i - $j;
        $rmsg= "反佣成功。返佣" . $i . "人，成功" . $peo . "人，" . $j . "人时间未到！";


        if($request->ajax()){
            return ['status' => 0, 'msg' => $rmsg];
        }else{
            echo $msgstr;
            echo $rmsg;
        }

    }


    public function extra_bonus(Request $request){

        $useritem_time = date('Y-m-d 00:00:00');
        $updated_at = date('Y-m-d H:i:s');
        $extra_bonus_list = DB::table("extra_bonus")
            ->where('useritem_time','=',$useritem_time)
            ->take(300)
            ->get();

    //  dump($extra_bonus_list);
    //  exit;
        if(count($extra_bonus_list) <1){echo '查无返佣计划';return;}

        //获取所有项目，并用项目id做键值对数组

        $i = $j = 0;//当前返佣总人数
        $startdata = date('Y-m-d');
        $ip = \Request::getClientIp();
        foreach ($extra_bonus_list as $value) {
            $uid = $value->uid;        //用户ID
            $usernmae = $value->username;
            $money =  $value->money;;//返利收益


            $BuyMember = Member::select('id','username','amount')->find($uid);

            // 查看是否收益
            $hsa_log = DB::table('moneylog')
                ->select('id')
                ->where(['moneylog_userid'=>$uid,'moneylog_type'=>'额外分红','created_date'=>$startdata])
                // ->where('created_at','>=',$startdata)
                ->first();
                // dump($hsa_log);
                // exit();
            if($BuyMember && !$hsa_log){
                $j++;
                DB::beginTransaction();
                try{

                    $next_time = \App\Productbuy::DateAdd("d", 1, $value->useritem_time);
                    DB::table('extra_bonus')->where('id',$value->id)->update(['last_useritem_time'=>$updated_at,'useritem_time'=>$next_time]);

                    //金额记录日志
                    $notice = '额外分红';

                    $BuyMember_id = $BuyMember->id;
                    $BuyMember_username = $BuyMember->username;
                    $Mamount = $BuyMember->amount;

                    /**************************收益金额加入 用户余额*****************************/
                    $BuyMember->increment('amount',$money);

                    //添加金额log表
                    $log=[
                        "userid"=>$BuyMember_id,
                        "username"=>$BuyMember_username,
                        "money"=>$money,
                        "notice"=>$notice,
                        "type"=>'额外分红',
                        "status"=>'+',
                        "yuanamount"=>$Mamount,
                        "houamount"=>$BuyMember->amount,
                        "ip"=>$ip,
                        "moneylog_type_id"=>'13',
                    ];
                    \App\Moneylog::AddLog($log);

                   //添加check_money 表
                    $check_money = [
                        'uid'=>$BuyMember->id,
                        'username'=>$BuyMember->username,
                        'money'=>$money,
                        'type'=>1,
                     //   'created_date'=>$created_date,
                        'created_date'=>$startdata,
                        'from_id'=>$value->id,
                        'created_at'=>$updated_at,
                    ];
                    DB::table('check_money')->insert($check_money);

                    DB::commit();
                }catch(\Exception $exception){
                    Log::channel('pf')->alert('[额外分红]'.$exception->getMessage());
                    DB::rollBack();
                }
            }



        }/**循环结束**/

        $rmsg= "反利成功。返利" . $i . "人，成功".$j.'人' ;


        if($request->ajax()){
            return ['status' => 0, 'msg' => $rmsg];
        }else{
            // echo $msgstr;
            echo $rmsg;
        }


    }

    //运维激活后未返利的用户
    public function yunwei_yikatong(Request $request){
        // $data = DB::table('productbuy')->where(['status'=>1])->where('category_id','<>','12')->groupBy('username')->get();
        // // $username = $data->groupBy('username');
        // // dump($username);exit;

        // $username_arr = $data->pluck('username');
        // //  dump($username_arr);exit;
        // foreach ($username_arr as $v){
        //     // dump($v);
        //     $sta = DB::table('member')->where(['id'=>$v,'activation'=>0])->first();
        //     if($sta){
        //         echo $v."<br>";
        //     }
        // }
        //----已购买未激活-------

        //已激活未购买
        $member = DB::table('member')->select('username')->where('activation',1)->get();
        $username_arr = $member->pluck('username');
        foreach ($username_arr as $v){
            $has = DB::table('productbuy')->where(['status'=>1,'username'=>$v])->where('category_id','<>','12')->first();
            if(!$has){
                echo $v."<br>";
            }
        }
        echo '结束';
        //已激活未购买end

        // $data = DB::table('member')
        // // ->where('id','22432')
        //     ->where('integral','>',0)
        //     ->where(['state'=>1,'activation'=>1])
        //     ->get();
        // // dump($data);exit;
        // foreach ($data as $v){
        //     $activation_yuanamount = $v->amount;
        //     $add_amount = $v->integral;
        //     DB::table('member')->where('id',$v->id)->increment('amount',$add_amount);
        //     $acc_log=[
        //         "userid"=>$v->id,
        //         "username"=>$v->username,
        //         "money"=>$add_amount,
        //         "notice"=>'激活账号,释放补贴',
        //         "type"=>"激活账号释放补贴",
        //         "status"=>"+",
        //         "yuanamount"=>$activation_yuanamount,
        //         "houamount"=>$activation_yuanamount + $add_amount,
        //         "ip"=>\Request::getClientIp(),
        //         "category_id"=>'0',
        //         "product_id"=>'0',
        //         "product_title"=>'0',
        //         'num'=>'0',
        //     ];
        //     \App\Moneylog::AddLog($acc_log);
        //     DB::table('member')->where('id',$v->id)->decrement('integral',$add_amount);
        // }
    }

    //每天统计数据(后台首页)
    public function statistics_sys(Request $request){

        $today_date = date('Y-m-d');
        $yesterday_date = date('Y-m-d',strtotime("-1 day"));

        $yesterday_user_num  = DB::table('member')->where('created_date',$yesterday_date)->count();//昨日新增会员
        $yesterday_release_amount = DB::table('moneylog')->where(['moneylog_type'=>'项目分红','created_date'=>$yesterday_date])->sum('moneylog_money');//昨日释放金额
        $yesterday_withdrawal_amount  = DB::table('memberwithdrawal')->where(['created_date'=>$yesterday_date,'status'=>1])->sum('amount');//昨日提现已审核金额
        $yesterday_buy_amount = DB::table('productbuy')->where(['status'=>1,'created_date'=>$yesterday_date])->where('pay_type','<>','0')->sum('amount');//昨日充值金额
        // $user_num = DB::table('member')->count();//会员总数
        // $release_amount = DB::table('moneylog')->where(['moneylog_type'=>'项目分红'])->sum('moneylog_money');//释放总额
        // $withdrawal_amount = DB::table('memberwithdrawal')->where(['status'=>1])->sum('amount');//提现总额
        // $buy_amount = DB::table('productbuy')->where(['status'=>1])->where('pay_type','<>','0')->sum('status');//充值总额

        // $today_user_num = DB::table('member')->where('created_date',$today_date)->count();//今日新增会员
        // $today_release_amount = DB::table('moneylog')->where(['moneylog_type'=>'项目分红','created_date'=>$today_date])->sum('moneylog_money');//今日释放金额
        // $today_withdrawal_amount = DB::table('memberwithdrawal')->where(['created_date'=>$today_date,'status'=>1])->sum('amount');//今日提现已审核金额
        // $today_buy_amount = DB::table('productbuy')->where(['status'=>1,'created_date'=>$today_date])->where('pay_type','<>','0')->sum('status');//今日充值金额


        $data = [
            'yesterday_user_num'=>$yesterday_user_num,
            'yesterday_release_amount'=>$yesterday_release_amount,
            'yesterday_withdrawal_amount'=>$yesterday_withdrawal_amount,
            'yesterday_buy_amount'=>$yesterday_buy_amount,
            // 'user_num'=>$user_num,
            // 'release_amount'=>$release_amount,
            // 'withdrawal_amount'=>$withdrawal_amount,
            // 'buy_amount'=>$buy_amount,
            // 'today_user_num'=>$today_user_num,
            // 'today_release_amount'=>$today_release_amount,
            // 'today_withdrawal_amount'=>$today_withdrawal_amount,
            // 'today_buy_amount'=>$today_buy_amount,
            'created_at' => date('Y-m-d H:i:s'),//统计时间
            ];
        DB::table('statistics_sys')->where('id',1)->update($data);
        echo '统计完成';
    }


    //团队激励奖励
    // public function teamrewards(){
    //     $now_time = date('Y-m-d H:i:s');
    //     $now_data = date('Y-m-d');

    //     $data = DB::table('teamrewards_log')
    //     ->where('next_reward_time','<',$now_time)
    //     ->limit(500)
    //     ->get();
    // //   dump($data);exit;
    //     if(count($data)<0){
    //       echo '查无激励数据';
    //       return;
    //     }
    //     $teamrewards = DB::table('teamrewards')->get();
    //     $teamrewards_arr = [];
    //     foreach ($teamrewards as $a) {
    //         $teamrewards_arr[$a->id] = $a;
    //     }

    //     foreach ($data as $v){
    //         $userid = $v->uid;
    //         $username = $v->username;
    //         $team_id = $v->team_id;//要奖励的等级


    //         if($v->pid > 0 ){
    //             $reg_give_product_info = DB::table("products")
    //                 ->select('id','title','category_id','qtje','isft','tzzt','hkfs','shijian','zgje','qxdw','zsje','zsje_type','jyrsy','qtsl','zscp_id')
    //                 ->where(['id'=>$v->pid])
    //                 ->first();

    //             if($reg_give_product_info){
    //                 DB::beginTransaction();
    //                 try{
    //                     if($v->reward_equ_num >0 && $v->pid > 0){
    //                         //赠送数量
    //                         $reg_give_product_pcount = $v->reward_equ_num;
    //                         //赠送总金额
    //                         $reg_give_product_money = $reg_give_product_pcount * $reg_give_product_info->qtje;
    //                         $reg_give_product_id = $v->pid;
    //                         //判断下一次领取时间
    //                         $hkfs = $reg_give_product_info->hkfs;
    //                         $zhouqi    = trim($reg_give_product_info->shijian);//周期
    //                         $sendDay_count = $hkfs == 1?1:$zhouqi;

    //                         $useritem_time2 = \App\Productbuy::DateAdd("d",1, date('Y-m-d 0:0:0',time()));

    //                         $NewProductbuy= new Productbuy();
    //                         $NewProductbuy->userid = $userid;
    //                         $NewProductbuy->username = $username;
    //                         $NewProductbuy->productid = $reg_give_product_id;
    //                         $NewProductbuy->category_id=$reg_give_product_info->category_id;
    //                         $NewProductbuy->amount= $reg_give_product_money;
    //                         // $NewProductbuy->ip= \Request::getClientIp();
    //                         $NewProductbuy->useritem_time = $now_time;
    //                         $NewProductbuy->useritem_time2=$useritem_time2;
    //                         $NewProductbuy->reason = "团队奖励赠送产品(".$reg_give_product_info->title.")";
    //                         $NewProductbuy->sendDay_count=$sendDay_count;
    //                         $NewProductbuy->num = $reg_give_product_pcount;//赠送数量
    //                         $NewProductbuy->unit_price = $reg_give_product_info->qtje;//赠送时单价
    //                         $NewProductbuy->zsje=0;//赠送金额
    //                         $NewProductbuy->zscp_id=0;//
    //                         // $NewProductbuy->gq_order = 'Y'.$reg_give_product_id.($RegMember->id+555);
    //                         // $NewProductbuy->order = substr((date('YmdHis').$RegMember->id.$this->get_random_code(6)),0,25);
    //                         $res = $NewProductbuy->save();
    //                         //站内消息
    //                         $msg=[
    //                             "userid"=>$userid,
    //                             "username"=>$username,
    //                             "title"=>"团队奖励赠送产品",
    //                             "content"=>"成功加入项目(".$reg_give_product_info->title.")",
    //                             "from_name"=>"系统通知",
    //                             "types"=>"加入项目",
    //                         ];
    //                         \App\Membermsg::Send($msg);
    //                         //
    //                         $give_log=[
    //                             "userid"=>$userid,
    //                             "username"=>$username,
    //                             "money"=> $reg_give_product_money,
    //                             "notice"=>"团队奖励产品(".$reg_give_product_info->title.")[".$NewProductbuy->id."]",
    //                             "type"=>"团队奖励项目",
    //                             "status"=>"+",
    //                             "yuanamount"=>0,
    //                             "houamount"=>0,
    //                             "ip"=>\Request::getClientIp(),
    //                             "category_id"=>$reg_give_product_info->category_id,
    //                             "product_id"=>$reg_give_product_info->id,
    //                             "product_title"=>$reg_give_product_info->title,
    //                         ];
    //                         \App\Moneylog::AddLog($give_log);

    //                         DB::table('statistics')->where(['user_id'=>$userid])->increment('team_capital_flow', $reg_give_product_money);
    //                         $next_reward_time = date('Y-m-d 00:00:00', strtotime(date('Y-m-01', time()) . ' +1 month'));//下次收时间(下月初)
    //                         DB::table('teamrewards_log')->where('uid',$userid)->update(['reward_time'=>$now_time,'next_reward_time'=>$next_reward_time]);
    //                     }

    //                     //赠送金额
    //                     if($v->reward_money > 0){
    //                         $member_info = Member::select('amount')->find($uid);
    //                         $yuanamount = $member_info->amount;
    //                         $member_info->increment('amount',$v->reward_money);
    //                         $give_amount_log=[
    //                             "userid"=>$userid,
    //                             "username"=>$username,
    //                             "money"=> $v->reward_money,
    //                             "notice"=>"团队奖励赠送金额(".$v->reward_money.")",
    //                             "type"=>"团队奖励赠送金额",
    //                             "status"=>"+",
    //                             "yuanamount"=>$yuanamount,
    //                             "houamount"=>$member_info->amount,
    //                             "ip"=>\Request::getClientIp(),
    //                             "category_id"=>0,
    //                             "product_id"=>0,
    //                             "product_title"=>0,
    //                         ];
    //                         \App\Moneylog::AddLog($give_amount_log);
    //                     }


    //                     DB::commit();
    //                 }catch(\Exception $exception){
    //                     echo "执行失败";
    //                     Log::channel('automatic')->alert($exception->getMessage());
    //                     DB::rollBack();
    //                 }
    //             }
    //         }
    //     }
    //     echo "执行成功";
    // }

    //分红功能
    // public function bonus(Request $request)
    // {

    //     $msgstr='';
    //      $rmsg='';

    //     $Products = Product::get();
    //     foreach ($Products as $Product) {
    //         $this->Products[$Product->id] = $Product;
    //     }

    //     $Memberlevels = Memberlevel::get();

    //     foreach ($Memberlevels as $Memberlevel) {
    //         $this->Memberlevels[$Memberlevel->id] = $Memberlevel;
    //     }

    //     $where=[];

    //     if($request->id>0){
    //         $where=[["id","=",$request->id]];
    //     }

    //     $ProductbuyList = DB::table("productbuy")->where("productbuy.status", "1")
    //         ->where($where)
    //         ->whereDate("productbuy.useritem_time2", "<", Carbon::now()->format("Y-m-d H:i:s"))
    //         ->where('category_id','<>',11)
    //         ->take(500)
    //         ->orderBy('productbuy.useritem_time2','asc')
    //         ->get();

    //     $i = 0;
    //     $j = 0;
    //     $msgstr='';
    //     foreach ($ProductbuyList as $value) {



    //         $userid = $value->userid;        //投注用户ID
    //         $username = $value->username;    //投注用户帐号
    //         $pid = $value->productid;        //项目ID
    //         $buyid = $value->id;             //投注表ID。
    //         $buylevel = $value->level;       //投注表ID。


    //         // if (isset($this->Products[$pid]) && isset($this->Memberlevels[$buylevel])) {
    //         if (isset($this->Products[$pid])) {

    //             //$msg= '项目名称:' . $this->Products[$pid]->title . '<br/>';


    //             // if ($this->Products[$pid]->hkfs == 0 || $this->Products[$pid]->hkfs == 2 || $this->Products[$pid]->hkfs == 3) {
    //                 //还款方式
    //                 $money = floatval($this->Products[$pid]->jyrsy * $value->amount / 100);
    //                 // $elmoney = floatval($this->Memberlevels[$buylevel]->rate * $value->amount / 100);
    //                 $elmoney = 0;
    //             // } else {
    //             //     $money = floatval($this->Products[$pid]->jyrsy * $value->amount / 100 * $this->Products[$pid]->shijian);
    //             //     // $elmoney = floatval($this->Memberlevels[$buylevel]->rate * $value->amount / 100 * $this->Products[$pid]->shijian);
    //             //     $elmoney = 0;
    //             // }


    //             // $msgstr.=  '项目名称:' . $this->Products[$pid]->title . '<br/>项目反利:' . $money . '元 vip[' . $buylevel . '] 会员等级返利:' . $elmoney . '(' . $this->Memberlevels[$buylevel]->rate . '*' . $value->amount / 100 . '*' . $this->Products[$pid]->shijian . ')<br/>';
    //             $msgstr.=  '会员id:'.$value->userid.'BUY表中id:'.$value->id.'项目名称:' . $this->Products[$pid]->title . '<\n>项目反利:' . $money . '元<\n>';
    //           // echo '反利:' . $this->Memberlevels[$buylevel]->rate . '*' . $value->amount / 100 . '*' . $this->Products[$pid]->shijian . '<br/>';

    //             /***结束开始***/

    //             $shijian = (int)$this->Products[$pid]->shijian;
    //             $qxdw = $this->Products[$pid]->qxdw;
    //             $user_id = $value->userid;
    //             $i++;
    //             $BuyMember = Member::find($value->userid);

    //             /**会员存在***/
    //             if($BuyMember){

    //                 $useritem_time = $value->useritem_time;
    //                 $useritem_time2 = $value->useritem_time2;
    //                 $useritem_time4 = date('Y-m-d H:i:s', time());
    //                 $nowcishu = (int)$value->useritem_count;
    //                 if ($nowcishu >= $shijian || $useritem_time4 < $useritem_time2) {
    //                     $j++;
    //                 } else {
    //                     $data['useritem_time1'] = $useritem_time2;
    //                     if ($qxdw == '个交易日') {
    //                         $zq = \App\Productbuy::weekname(date('w', $useritem_time2));
    //                         switch ($zq) {
    //                             case '星期一':
    //                                 $data['useritem_time2'] = \App\Productbuy::DateAdd("d", 1, $useritem_time2);
    //                                 break;
    //                             case '星期二':
    //                                 $data['useritem_time2'] = \App\Productbuy::DateAdd("d", 1, $useritem_time2);
    //                                 break;
    //                             case '星期三':
    //                                 $data['useritem_time2'] = \App\Productbuy::DateAdd("d", 1, $useritem_time2);
    //                                 break;
    //                             case '星期四':
    //                                 $data['useritem_time2'] = \App\Productbuy::DateAdd("d", 1, $useritem_time2);
    //                                 break;
    //                             case '星期五':
    //                                 $data['useritem_time2'] = \App\Productbuy::DateAdd("d", 3, $useritem_time2);
    //                                 break;
    //                             case '星期六':
    //                                 $data['useritem_time2'] = \App\Productbuy::DateAdd("d", 2, $useritem_time2);
    //                                 break;
    //                             case '星期日':
    //                                 $data['useritem_time2'] = \App\Productbuy::DateAdd("d", 1, $useritem_time2);
    //                                 break;
    //                             default:
    //                                 break;
    //                         }
    //                     } else if($qxdw == '个自然日'){
    //                         $data['useritem_time2'] = \App\Productbuy::DateAdd("d", 1, $useritem_time2);
    //                     }else if($qxdw == '个小时'){
    //                         $data['useritem_time2'] = \App\Productbuy::DateAdd("h", 1, $useritem_time2);
    //                     }
    //                     $data['useritem_count'] = $nowcishu + 1;

    //                     if ($this->Products[$pid]->hkfs == 0 || $this->Products[$pid]->hkfs == 1 || $this->Products[$pid]->hkfs == 2|| $this->Products[$pid]->hkfs == 3){

    //                         //更新项目分红时间
    //                         DB::table("productbuy")->where("id",$value->id)->update($data);

    //                         // if($this->Products[$pid]->category_id != 12){
    //                             //更新金额日志


    //                             //金额记录日志 $this->Products[$pid]->
    //                             $ip = $request->getClientIp();
    //                             $projectName = $this->Products[$pid]->title;
    //                             $notice = "项目分红-(" . $projectName . ")(+)";

    //                             $amountFH=round($money + $elmoney, 2);

    //                             //meoneyLog($user_id, $money + $elmoney, $notice, '+');

    //                             //站内消息
    //                             $msg=[
    //                                 "userid"=>$BuyMember->id,
    //                                 "username"=>$BuyMember->username,
    //                                 "title"=>"项目分红",
    //                                 "content"=>"项目分红(".$projectName.")(".$amountFH.")",
    //                                 "from_name"=>"系统通知",
    //                                 "types"=>"项目分红",
    //                             ];
    //                             \App\Membermsg::Send($msg);

    //                             $Mamount=$BuyMember->amount;

    //                             $BuyMember->increment('amount',$amountFH);
    //                             $log=[
    //                                 "userid"=>$BuyMember->id,
    //                                 "username"=>$BuyMember->username,
    //                                 "money"=>$amountFH,
    //                                 "notice"=>$notice,
    //                                 "type"=>"项目分红",
    //                                 "status"=>"+",
    //                                 "yuanamount"=>$Mamount,
    //                                 "houamount"=>$BuyMember->amount,
    //                                 "ip"=>\Request::getClientIp(),
    //                                 "product_id"=>$pid,
    //                                 "category_id"=>$this->Products[$pid]->category_id,
    //                                 "product_title"=>$projectName,
    //                             ];

    //                             \App\Moneylog::AddLog($log);
    //                         // }else{
    //                         //     //更新股权日志


    //                         //     //股权记录日志 $this->Products[$pid]->
    //                         //     $ip = $request->getClientIp();
    //                         //     $projectName = $this->Products[$pid]->title;
    //                         //     $notice = "股权增值-(" . $projectName . ")(+)";

    //                         //     $amountFH=round($money + $elmoney, 2);

    //                         //     //meoneyLog($user_id, $money + $elmoney, $notice, '+');

    //                         //     //站内消息
    //                         //     $msg=[
    //                         //         "userid"=>$BuyMember->id,
    //                         //         "username"=>$BuyMember->username,
    //                         //         "title"=>"股权增值",
    //                         //         "content"=>"股权增值(".$projectName.")(".$amountFH.")",
    //                         //         "from_name"=>"系统通知",
    //                         //         "types"=>"股权增值",
    //                         //     ];
    //                         //     \App\Membermsg::Send($msg);

    //                         //     DB::table("productbuy")->where("id",$value->id)->increment('amount',$amountFH);
    //                         // }

    //                     }else{

    //                         $amountFH=round($money + $elmoney, 2);

    //                         $data['grand_total'] = $value->grand_total + $amountFH;

    //                         //更新项目分红时间
    //                         DB::table("productbuy")->where("id",$value->id)->update($data);

    //                         //更新金额日志
    //                         //股权只更新收益表展示用，但不收益到实际余额
    //                         $amountFH=round($money + $elmoney, 2);
    //                         $Mamount=$BuyMember->amount;
    //                         $projectName = $this->Products[$pid]->title;
    //                         $notice = "股权增值-(" . $projectName . ")(+)";
    //                         $log=[
    //                             "userid"=>$BuyMember->id,
    //                             "username"=>$BuyMember->username,
    //                             "money"=>$amountFH,
    //                             "notice"=>$notice,
    //                             "type"=>"项目分红",
    //                             "status"=>"+",
    //                             "yuanamount"=>$Mamount,
    //                             "houamount"=>$Mamount,
    //                             "ip"=>\Request::getClientIp(),
    //                             "product_id"=>$pid,
    //                             "category_id"=>$this->Products[$pid]->category_id,
    //                             "product_title"=>$projectName,
    //                         ];

    //                         \App\Moneylog::AddLog($log);

    //                         //金额记录日志 $this->Products[$pid]->
    //                         $ip = $request->getClientIp();
    //                         $projectName = $this->Products[$pid]->title;
    //                         $notice = "项目分红-(" . $projectName . ")(+)";

    //                         //meoneyLog($user_id, $money + $elmoney, $notice, '+');

    //                         //站内消息
    //                         $msg=[
    //                             "userid"=>$BuyMember->id,
    //                             "username"=>$BuyMember->username,
    //                             "title"=>"项目累计分红",
    //                             "content"=>"项目累计分红(".$projectName.")(".$data['grand_total'].")",
    //                             "from_name"=>"系统通知",
    //                             "types"=>"项目分红",
    //                         ];
    //                         \App\Membermsg::Send($msg);

    //                     }

    //                     //次数达到返回本金
    //                     if ($this->Products[$pid]->hkfs == 0 || $this->Products[$pid]->hkfs == 1 || $this->Products[$pid]->hkfs == 2){

    //                         if ((int)$value->sendday_count == $nowcishu + 1) {

    //                             //标记项目结束状态

    //                             $dates['status'] = 0; //结束
    //                             DB::table("productbuy")->where("id",$value->id)->update($dates);

    //                             //返回金额,benjin

    //                             $projectName = $this->Products[$pid]->title;
    //                             $notice = "项目本金返款-(" . $projectName . ")(+)";
    //                             $nowMoney = round($value->amount ? $value->amount : 0, 2);
    //                             //站内消息
    //                             $msg=[
    //                                 "userid"=>$BuyMember->id,
    //                                 "username"=>$BuyMember->username,
    //                                 "title"=>"项目本金返款",
    //                                 "content"=>"项目本金返款(".$projectName.")",
    //                                 "from_name"=>"系统通知",
    //                                 "types"=>"项目本金返款",
    //                             ];
    //                             \App\Membermsg::Send($msg);

    //                             $Mamount=$BuyMember->amount;

    //                             $BuyMember->increment('amount',$nowMoney);
    //                             $log=[
    //                                 "userid"=>$BuyMember->id,
    //                                 "username"=>$BuyMember->username,
    //                                 "money"=>$nowMoney,
    //                                 "notice"=>$notice,
    //                                 "type"=>"项目本金返款",
    //                                 "status"=>"+",
    //                                 "yuanamount"=>$Mamount,
    //                                 "houamount"=>$BuyMember->amount,
    //                                 "ip"=>\Request::getClientIp(),
    //                                 "product_title"=>$projectName,
    //                             ];

    //                             \App\Moneylog::AddLog($log);

    //                         }
    //                     }else if ($this->Products[$pid]->hkfs == 3){

    //                         //日平均还金额

    //                         $projectName = $this->Products[$pid]->title;

    //                         $notice = "项目本金返款(等额本息)-(" . $projectName . ")(+)";
    //                         $nowMoney = round($value->amount/$shijian ? $value->amount/$shijian : 0, 2);
    //                         $nowMoneys = round($nowMoney, 2);

    //                         //站内消息
    //                         $msg=[
    //                             "userid"=>$BuyMember->id,
    //                             "username"=>$BuyMember->username,
    //                             "title"=>"项目本金返款",
    //                             "content"=>$notice,
    //                             "from_name"=>"系统通知",
    //                             "types"=>"项目本金返款",
    //                         ];
    //                         \App\Membermsg::Send($msg);

    //                         $Mamount=$BuyMember->amount;

    //                         $BuyMember->increment('amount',$nowMoneys);
    //                         $log=[
    //                             "userid"=>$BuyMember->id,
    //                             "username"=>$BuyMember->username,
    //                             "money"=>$nowMoneys,
    //                             "notice"=>$notice,
    //                             "type"=>"项目本金返款",
    //                             "status"=>"+",
    //                             "yuanamount"=>$Mamount,
    //                             "houamount"=>$BuyMember->amount,
    //                             "ip"=>\Request::getClientIp(),

    //                             "product_title"=>$projectName,
    //                         ];

    //                         \App\Moneylog::AddLog($log);

    //                         if ((int)$value->sendday_count == $nowcishu + 1) {
    //                             //标记项目结束状态
    //                             $dates['status'] = 0; //结束
    //                             DB::table("productbuy")->where("id",$value->id)->update($dates);
    //                         }
    //                     }else if($this->Products[$pid]->hkfs == 4 || $this->Products[$pid]->hkfs == 5 || $this->Products[$pid]->hkfs == 6){

    //                         if ((int)$value->sendday_count == $nowcishu + 1) {

    //                             //标记项目结束状态

    //                             $dates['status'] = 0; //结束
    //                             DB::table("productbuy")->where("id",$value->id)->update($dates);

    //                             //返回金额,benjin

    //                             $projectName = $this->Products[$pid]->title;
    //                             $notice = "项目本金及分红返款-(" . $projectName . ")(+)";
    //                             $nowMoney = $data['grand_total'] + round($value->amount ? $value->amount : 0, 2);
    //                             //站内消息
    //                             $msg=[
    //                                 "userid"=>$BuyMember->id,
    //                                 "username"=>$BuyMember->username,
    //                                 "title"=>"项目本金及分红返款",
    //                                 "content"=>"项目本金及分红返款(".$projectName.")",
    //                                 "from_name"=>"系统通知",
    //                                 "types"=>"项目本金及分红返款",
    //                             ];
    //                             \App\Membermsg::Send($msg);

    //                             $Mamount=$BuyMember->amount;

    //                             $BuyMember->increment('amount',$nowMoney);
    //                             $log=[
    //                                 "userid"=>$BuyMember->id,
    //                                 "username"=>$BuyMember->username,
    //                                 "money"=>$nowMoney,
    //                                 "notice"=>$notice,
    //                                 "type"=>"项目本金及分红返款",
    //                                 "status"=>"+",
    //                                 "yuanamount"=>$Mamount,
    //                                 "houamount"=>$BuyMember->amount,
    //                                 "ip"=>\Request::getClientIp(),
    //                                 "product_title"=>$projectName,
    //                             ];

    //                             \App\Moneylog::AddLog($log);

    //                         }

    //                     }

    //                 }
    //             /**会员结束***/
    //             }


    //             /***结束结束***/

    //         }
    //         /**项目产品与等级数据完整结束**/

    //     }

    //     /**循环结束**/

    //     $peo = $i - $j;
    //     $rmsg= "反佣成功。返佣" . $i . "人，成功" . $peo . "人，" . $j . "人时间未到！";

    //     if($request->ajax()){
    //         return ['status' => 0, 'msg' => $rmsg];
    //     }else{
    //         echo $msgstr;
    //         echo $rmsg;
    //     }

    // }


    //货币收益涨幅
    public function increase(Request $request)
    {

        $yunbi_info = DB::table('products')->where(['title'=>'云币'])->first();//云币固定0.1不进行涨幅
        $ProductsList = DB::table("products")
            ->where("products.category_id", 11)
            ->where('id','<>',$yunbi_info->id)
            ->get();

        echo count($ProductsList);

        $i = 0;
        $j = 0;
        $msgstr='';
        foreach ($ProductsList as $value) {

            $i++;
            $increase = $value->increase;
			$last_increase_at = strtotime($value->last_increase_at);
			$today_at = strtotime(date("Y-m-d 0:0:0"));

			if($last_increase_at<$today_at){
			    $data['qtje'] = $value->qtje + ($value->qtje * 0.01 * $value->increase);
				// $data['jyrsy'] = $value->jyrsy + ($value->increase);
				$data['last_increase_at'] = Carbon::now();
				$data['updated_at'] = Carbon::now();
				DB::table("products")->where("id",$value->id)->update($data);
				$rand_num1 = rand(-1,3);
                $rand_num2 = rand(0,2);
				$currlineData = [
				    'product_id'=>$value->id,
				    'old_price'=> $value->qtje,
				    'price'=>$data['qtje'],
				    'increase_price'=>$value->qtje * 0.01 * $value->increase,
				    'increase'=>$value->increase,
				    'created_at'=>Carbon::now(),
				    // 'highest_price' => rand($value->price,$value->price+$rand_num1),
        //             'lowest_price' => rand($value->price-$rand_num2,$value->price),
				];

				DB::table('currencysline')->where("product_id",$value->id)->insert($currlineData);
				$j++;
			}
        }

        /**循环结束**/

        $rmsg= "货币涨幅成功。涨幅" . $i . "个,成功".$j."个";


        if($request->ajax()){
            return ['status' => 0, 'msg' => $rmsg];
        }else{
            echo $rmsg;
        }


    }


    //更新k线
    public function updateline(Request $request){

        $product_arr = DB::table('products')->where(['category_id'=>11])->get(); //货币ID

        foreach ($product_arr as $k=>$v){
            $has_c = DB::table('currencysline')->where('product_id',$v->id)->orderBy('created_at','desc')->first();
            if($has_c){
                $b = substr($has_c->created_at,0,10);
                $c = date('Y-m-d');
                if($b == $c){
                    echo $v->id.'已是最新,无需更新<br>';
                    continue;
                }
                $stimestamp = strtotime($has_c->created_at);
                $etimestamp = strtotime(Carbon::now());
                // 计算日期段内有多少天
                $days =intval(($etimestamp-$stimestamp)/86400);
                $old_price = $has_c->price;
                $data_array = [];
                $increase = 0;
                for ($i=1;$i<=$days;$i++) {
                    $record = [];
                    $increase = rand(-4,5);
                    $increase_price = $old_price * $increase * 0.01;
                    $rand_num1 = rand(-1,3);
                    $rand_num2 = rand(0,2);
                    $record['product_id'] = $v->id;
                    $record['old_price'] = $old_price;
                    $record['price'] = $old_price + $increase_price;
                    $record['increase'] = $increase;
                    $record['increase_price'] = $increase_price;
                    $record['created_at'] =  $this->DateAdd("d",$i, $has_c->created_at);
                    $record['highest_price'] = rand($record['price'],$record['price']+$rand_num1);
                    $record['lowest_price'] = rand($record['price']-$rand_num2,$record['price']);

                    $old_price = $record['price'];
                    $data_array[] = $record;
                }
                DB::table('currencysline')->insert($data_array);

                echo $v->id.'成功更新'.($i-1).'条,日期'.$has_c->created_at.'<br>';
//                return response()->json(["status"=>0,"msg"=>"该货币数据已存在"]);
            }else{
                $stimestamp = strtotime('2019-08-08 00:00:01');
                $etimestamp = strtotime(Carbon::now());
                // 计算日期段内有多少天
                $days =intval(($etimestamp-$stimestamp)/86400+1);
                $old_price = 1;
                // $end_price = 90.325;
                $increase = 0;
                $data_array = [];
                for ($i=0;$i<$days;$i++) {
                    $record = [];
                    // if($end_price - $old_price > 50){
                    //     $increase = rand(-2,3);
                    //     echo $i."<br>";
                    // }else{
                    //     $increase = rand(-1,2);
                    // }
                    $increase = rand(-4,5);
                    $increase_price = $old_price * $increase * 0.01;
                    $rand_num1 = rand(-1,3);
                    $rand_num2 = rand(0,2);
                    $record['product_id'] = $v->id;
                    $record['old_price'] = $old_price;
                    $record['price'] = $old_price + $increase_price;
                    $record['increase'] = $increase;
                    $record['increase_price'] = $increase_price;
                    $record['created_at'] =  $this->DateAdd("d",$i, '2019-08-08 00:00:01');
                    $record['highest_price'] = rand($record['price'],$record['price']+$rand_num1);
                    $record['lowest_price'] = rand($record['price']-$rand_num2,$record['price']);

                    $old_price = $record['price'];

                    $data_array[] = $record;

                }
                DB::table('currencysline')->insert($data_array);

                echo $v->id.'成功插入'.$i."<br>";
            }
        }

    }

    protected function DateAdd($part, $number, $date){
        $date_array = getdate(strtotime($date));
        $hor = $date_array["hours"];
        $min = $date_array["minutes"];
        $sec = $date_array["seconds"];
        $mon = $date_array["mon"];
        $day = $date_array["mday"];
        $yar = $date_array["year"];
        switch($part){
            case "y": $yar += $number; break;
            case "q": $mon += ($number * 3); break;
            case "m": $mon += $number; break;
            case "w": $day += ($number * 7); break;
            case "d": $day += $number; break;
            case "h": $hor += $number; break;
            case "n": $min += $number; break;
            case "s": $sec += $number; break;
        }
        $FengHongDateFormat='Y-m-d H:i:s';
        return date($FengHongDateFormat, mktime($hor, $min, $sec, $mon, $day, $yar));
    }


    public function update_invicode(Request $request){
        for($i=0;$i<300;$i++){
            $member_no_code = DB::table('member')->select('id')->whereNull('invicode')->first();
            if($member_no_code){
                  $invicode = $this->get_random_code(7);
                    while(DB::table('member')->where('invicode',$invicode)->first()){
                        $invicode = $this->get_random_code(7);
                    }
                    DB::table('member')->where('id',$member_no_code->id)->update(['invicode'=>$invicode]);
                    DB::table('code_log')->insert(['uid'=>$member_no_code->id,'g_time'=>date("Y-m-d H:i:s")]);
                    echo '更新uid:'.$member_no_code->id."<br>";
            }else{
                echo "查无数据";
            }
        }
    }

    function get_random_code($num)
    {
        // $codeSeeds = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
        // $codeSeeds .= "abcdefghijklmnpqrstuvwxyz";
        // $codeSeeds .= "0123456789_";
        $codeSeeds = "1234567890";
        $len = strlen($codeSeeds);
        $code = "";
        for ($i = 0; $i < $num; $i++) {
            $rand = rand(0, $len - 1);
            $code .= $codeSeeds[$rand];
        }
        return $code;
    }
}
