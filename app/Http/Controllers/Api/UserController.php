<?php

namespace App\Http\Controllers\Api;

use App\Bigtree;
use App\Channel;
use App\Http\Controllers\Controller;
use App\Jobs\CollisionReward;
use App\Jobs\SendEmail;
use App\Jobs\UserBindAllot;
use App\Member;
use App\Membercurrencys;
use App\Memberlevel;
use App\Membermsg;
use App\Memberphone;
use App\membersubsidy;
use App\Memberticheng;
use App\Order;
use App\Product;
use App\Productbuy;
use App\TreeProduct;
use App\TreeProductbuy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Log as LogLog;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    public $cachetime = 600;
    public $Template = 'wap';
    private $Member = [];

    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            //请求预检返回 200
            if($request->method() == 'OPTIONS'){
                return response()->json(["status" => 1, "msg" => "ok"]);
            }
            $lastsession = $request->header('lastsession');
            if ($lastsession) {
                $Member = Member::where("lastsession", $lastsession)->first();
                if (!$Member) {
                    return response()->json(["status" => -1, "msg" => "请先登录！"],401);
                } else {
                    $request->session()->put('UserId', $Member->id, 120);
                    $request->session()->put('UserName', $Member->username, 120);
                    $request->session()->put('Member', $Member, 120);
                }
            }
            $UserId = $request->session()->get('UserId');
            if ($UserId < 1) {
                return response()->json(["status" => -1, "msg" => "请先登录!"],401);
            } else {
                $this->Member = Member::find($UserId);
                if (!$this->Member) {
                    return response()->json(["status" => -1, "msg" => "请先登录!"],401);
                }
                if ($this->Member->state == -1) {
                    return response()->json(["status" => 0, "msg" => "帐号禁用中"]);
                }
            }
            return $next($request);
        });
        /**网站缓存功能生成**/
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

        $this->cachetime = Cache::get('cachetime');
        if (Cache::has('memberlevel.list')) {
            $memberlevel = Cache::get('memberlevel.list');
        } else {
            $memberlevel = DB::table("memberlevel")->orderBy("id", "asc")->get();
            Cache::get('memberlevel.list', $memberlevel, Cache::get("cachetime"));
        }
        $memberlevelName = [];
        foreach ($memberlevel as $item) {
            $memberlevelName[$item->id] = $item->name;
        }
        $this->memberlevelName = $memberlevelName;
        $Products = Product::get();
        foreach ($Products as $Product) {
            $this->Products[$Product->id] = $Product;
        }
    }

    /***会员中心***/
    public function index(Request $request)
    {
        $data['total_amount'] = $this->Member->ktx_amount; //总资产
        $data['balance'] = number_format($this->Member->collision_amount - $this->Member->collision_amount_finsh,2);//剩余出局额度
        $data['hold'] = DB::table('productbuy')->where(['userid'=>$this->Member->id,'status'=>1])->value('amount');
        $data['total_achievement'] = number_format($this->Member->left_amount_show+$this->Member->right_amount_show, '2');//左右区业绩
        $data['left_achievement'] = $this->Member->left_amount_show;//左区业绩
        $data['right_achievement'] = $this->Member->right_amount_show;//右区业绩
        $data['level'] = $this->Member->level;//右区业绩
        $data['type'] = $this->Member->type;//账号类型
        $main = [];
        $left = [];
        $right = [];
        $user_id = 0;
        //主账号登录
        if($this->Member->type == 0){
            $user_id = $this->Member->id;
           $main = ['id'=>$this->Member->id,'username'=>$this->Member->username,'status'=>$this->Member->status,'region'=>$this->Member->region];
           $left = DB::table('member')->where(['type'=>1,'pid'=>$this->Member->id,'region'=>1])->first(['id','username','status','region']);
           $right = DB::table('member')->where(['type'=>1,'pid'=>$this->Member->id,'region'=>2])->first(['id','username','status','region']);
        }
        //左区子账号登录
        if($this->Member->type == 1 && $this->Member->region==1){
            $user_id = $this->Member->pid;
            $main = DB::table('member')->where(['id'=>$user_id])->first(['id','username','status','region']);
//            $left = ['id'=>$this->Member->id,'username'=>$this->Member->username,'status'=>$this->Member->status,'region'=>$this->Member->region];
            $left = ['left_login'=>1];
            $right = DB::table('member')->where(['type'=>1,'pid'=>$user_id,'region'=>2])->first(['id','username','status','region']);
        }
        //右区子账号登录
        if($this->Member->type == 1 && $this->Member->region==2){
            $user_id = $this->Member->pid;
            $main = DB::table('member')->where(['id'=>$user_id])->first(['id','username','status','region']);
            $left = DB::table('member')->where(['type'=>1,'pid'=>$user_id,'region'=>1])->first(['id','username','status','region']);
//            $right = ['id'=>$this->Member->id,'username'=>$this->Member->username,'status'=>$this->Member->status,'region'=>$this->Member->region];
            $right = ['right_login'=>1];
        }
        //主账户
        $data['child']['main'] = $main;
        //子账号
        //左区
        $data['child']['left'] = $left;
        //右区
        $data['child']['right'] = $right;
        $member_identity = DB::table("memberidentity")
            ->select('status')
            ->where(['userid' => $user_id])->first();
        if ($member_identity) {//-1:未认证  0：审核中   1：已认证
            $data['real_status'] = $member_identity->status;
        } else {
            $data['real_status'] = -1;
        }
        $date = date('Y-m-d');
        $dayTime = [$date.' 00:00:00',$date.' 23:59:59'];
        //资产
        $data['income_day'] = DB::table('moneylog')->where(['moneylog_userid'=>$this->Member->id])
            ->whereIn('moneylog_type',['直推返佣','对碰奖励','星级奖励','股东奖励','静态收益'])
            ->whereBetween('created_at',$dayTime)->sum('moneylog_money');
        $data['income_all'] = DB::table('moneylog')->where(['moneylog_userid'=>$this->Member->id])
            ->whereIn('moneylog_type',['直推返佣','对碰奖励','星级奖励','股东奖励','静态收益'])->sum('moneylog_money');
        return response()->json(['status' => 1, 'data' => $data]);
    }

    /**
     * 子账户开通
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function registerChild(Request $request){
        $user_id = $this->Member->id;
        $password = $request->post('password','');
        $pay_password = $request->post('pay_password','');
        $area = $request->post('area',0);
        $platform = $request->header('platform','H5');//注册来源
        if(empty($password) || empty($pay_password) || empty($area)){
            return response()->json(['status' => 0, 'msg' => '必填项为空']);
        }
        $auth = DB::table("memberidentity")->select('status')->where(['userid' => $this->Member->id])->first();
        if(!$auth || $auth->status !== 1){
            return response()->json(['status' => 0, 'msg' => '主账户必须完成实名认证']);
        }
        $child = DB::table('member')->where(['pid'=>$user_id])->get();
        if($child){
            $has_area = false;
            foreach($child as $val){
                if($val->region == $area){
                    $has_area = true;
                }
            }
            if($has_area){
                return response()->json(['status' => 0, 'msg' => '您在当前分区已有子账号']);
            }
        }
        $username = '';
        switch (count($child)){
            case 0:
                $username = $this->Member->username.'@1';
                break;
            case 1:
                $username = $this->Member->username.'@2';
                break;
        }
        if(empty($username)){
            return response()->json(['status' => 0, 'msg' => '未分配到子账户，请联系客服']);
        }
        $RegMember = new Member();
        DB::beginTransaction();
        try {
            $now_date = date('Y-m-d');
            $RegMember->username = $username;
            $RegMember->nickname = $this->generateNickname();
            $RegMember->password = \App\Member::EncryptPassWord($password);
            $RegMember->paypwd = \App\Member::EncryptPassWord($pay_password);
            $RegMember->mobile = \App\Member::EncryptPassWord($this->Member->username);
            $RegMember->pid = $this->Member->id;//主账户ID
            $RegMember->type = 1;//账户类型 0-主账户；1-子账户
            $RegMember->inviter = $this->Member->invicode;
            $RegMember->invite_uid = $this->Member->id;
            $RegMember->picImg = 20;
            $RegMember->gender = 1;
            $RegMember->ip =
            $RegMember->reg_from = $platform;
            $RegMember->amount = 0;
            $RegMember->created_date = $now_date;
            $RegMember->region = $area;
            $RegMember->save();

            //添加统计表
            $my_statistics['user_id'] = $RegMember->id;
            $my_statistics['username'] = $username;
            $my_statistics['top_one_uid'] = $this->Member->id;
            $my_statistics['created_at'] = Carbon::now();
            $my_statistics['register_date'] = date('Y-m-d');
            DB::table('statistics')->insert($my_statistics);

            //后台统计
            DB::table('statistics_sys')->where('id', 1)->increment('user_num', 1);
            //统计表end

            if ($RegMember) {
                //获取自增ID，用以插入用户的推荐码
                $invicode = $this->get_random_code(7);
                while (DB::table('member')->where('invicode', $invicode)->first()) {
                    $invicode = $this->get_random_code(7);
                }
                $RegMember->invicode = $invicode;
                $RegMember->family_ids = $this->Member->family_ids.','.$this->Member->id;
                $RegMember->save();
                //关系树绑定
                dispatch(new UserBindAllot($RegMember->id))->onQueue('userTreeBind');
                DB::commit();
                return array('msg' => "子账号开通成功", 'status' => 1, 'data' => '');
            } else {
                DB::rollBack();
                return array('msg' => "子账号开通失败", 'status' => 0);
            }
        } catch (\Exception $exception) {
            LogLog::channel('reg')->alert($exception->getMessage());
            DB::rollBack();
            return ['status' => 0, 'msg' => '提交失败，请重试'];
        }
    }

    public function changeAccount(Request $request){
        $username = $request->post('username','');
        if(empty($username)){
            return response()->json(['status' => 0,'msg'=>'切换用户名必须']);
        }
        DB::beginTransaction();
        try {
            $Member = Member::where(['username'=>$username,'state'=>1])->first();
            if(!$Member){
                /**登录日志**/
                $data['userid'] = $Member->id;
                $data['username'] = $Member->username;
                $data['memo'] = "帐号禁用中";
                $data['status'] = 0;
                $data['ip'] = $request->getClientIp();
                $data['created_at'] = $data['updated_at'] = Carbon::now();
                DB::table('memberlogs')->insert($data);
                return response()->json(['status' => 0,'msg'=>'账户不存在或已被禁用']);
            }
            //登录
            $request->session()->put('UserId', $Member->id, 120);
            $request->session()->put('UserName', $Member->username, 120);
            $request->session()->put('Member', $Member, 120);
            if ($request->is_red != 1) {
                $Member->lastsession = \App\Member::EncryptPassWord(Carbon::now() . $Member->id);
            }
            $Member->logintime = Carbon::now();
            $today_date = date('Y-m-d 00:00:00', time());
            $yesterday_date = date('Y-m-d 00:00:00', time() - 86400);
            $login_yesterday = DB::table('memberlogs')
                ->where(['userid' => $Member->id])
                ->where('status', '=', 1)
                ->whereBetween('created_at', [$yesterday_date, $today_date])
                ->first();
            $login_today = DB::table('memberlogs')
                ->where(['userid' => $Member->id])
                ->where('status', '=', 1)
                ->where('created_at', '>', $today_date)
                ->first();
            if (!$login_today) {
                if ($login_yesterday) {
                    $Member->login_times = $Member->login_times + 1;
                } else {
                    $Member->login_times = 1;
                }
            }
            $Member->save();
            /**登录日志**/
            $data['userid'] = $Member->id;
            $data['username'] = $Member->username;
            $data['memo'] = "登录成功";
            $data['status'] = 1;
            $data['ip'] = $request->getClientIp();
            $data['created_at'] = $data['updated_at'] = Carbon::now();
            DB::table('memberlogs')->insert($data);
            $res_data['token'] = $Member->lastsession;
            DB::commit();
            return response()->json(["status" => 1, "msg" => "登录成功", "data" => $res_data]);
        } catch (\Exception $exception) {
            LogLog::channel('reg')->alert('login:' . $exception->getMessage());
            DB::rollBack();
            return ['status' => 0, 'msg' => '提交失败，请重试'];
        }
    }

    private function generateNickname(): string
    {
        // 生成一个3-4个汉字的昵称
        $length = mt_rand(3, 4);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= chr((mt_rand(0xB0,0xD0))).chr((mt_rand(0xA1, 0xF0)));
        }
        return iconv('GB2312','UTF-8',$str);
    }

    /**
     * 会员信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userInfo(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = [$this->Member->id];
        if($this->Member->type == 1){
            $user_id = [$this->Member->id,$this->Member->pid];
        }
        $bank_card = DB::table("memberbank")->whereIn('userid',$user_id)->where(['type'=>1])->first(['id','bankname']);
        $userData = [
            'nickname' => $this->Member->nickname,
            'realname' => $this->Member->realname,
            'is_auth'  => $this->Member->is_auth,
            'invite_code' => $this->Member->invicode,
            'mobile'   => substr_replace($this->Member->username, '****', 3, 5),
            'bank'     => $bank_card
        ];
        return response()->json(['status' => 1,'msg'=>'ok','data' => $userData]);
    }

    /**
     * 实名信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authInfo(Request $request){
        $user_id = $this->Member->id;
        if($this->Member->type == 1){
            $user_id = $this->Member->pid;
        }
        $info = DB::table("memberidentity")->where(['userid' => $user_id])->first(['realname','idnumber','facade_img','revolt_img','status']);
        $data = [
            'name'=>$info->realname ?? '',
            'id_card'=>isset($info->idnumber) ? substr_replace($info->idnumber, '**************', 2, 14) : '',
            'card_up'=>$info->facade_img ?? '',
            'card_down'=>$info->revolt_img ?? '',
            'status'=>$info->status ?? -1
        ];
        return response()->json(['status' => 1,'msg'=>'ok','data' => $data]);
    }

    /**
     * 公用设置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommonSetting(Request $request){
        if (Cache::has('invite_link')) {
            $invite_link = Cache::get('invite_link');
        } else {
            $invite_link = DB::table("setings")->where(['keyname'=>'invite_link'])->value('value');
            Cache::get('invite_link', $invite_link);
        }
        $data['invite_link'] = $invite_link;

        if (Cache::has('download_link')) {
            $download_link = Cache::get('download_link');
        } else {
            $download_link = DB::table("setings")->where(['keyname'=>'HotAppDownloadUrl'])->value('value');
            Cache::get('download_link', $download_link);
        }
        $data['download_link'] = $invite_link;
        return response()->json(['status' => 1,'msg'=>'ok','data' => $data]);
    }

    public function set_travel_read(Request $request){
        $UserId = $request->session()->get('UserId');
        $id = $request->get('id','');
        DB::table("travellog")->where(['userid'=>$UserId,'id'=>$id])->update(['is_read'=>1]);
        return response()->json(['status' => 1, 'msg' =>'ok','data'=>'']);
    }

    /****我的资料***/
    public function my(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $data = DB::table("member")->find($UserId, ['id', 'nickname', 'picImg', 'gender', 'mobile']);
        $data->mobile = \App\Member::DecryptPassWord($data->mobile);
        $data->version = '1.0.1';
        return response()->json(['status' => 1, 'data' => $data]);
    }

    /***我的资料修改***/
    public function myEdit(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $EditMember = Member::where("id", $UserId)->first();
        $type = $request->post('type','');
        if ($EditMember && !empty($type)) {
            if($type == 'nickname' && !empty($request->nickname)) {
                $EditMember->nickname = trim($request->nickname);
                if ($EditMember->save()) {
                    return response()->json(["status" => 1, "msg" => "修改成功"]);
                } else {
                    return response()->json(["status" => 0, "msg" => "修改失败"]);
                }
            }
            if($type == 'password') {
                if(empty($request->old_pwd)){
                    return response()->json(["status" => 0, "msg" => "旧登录密码必须"]);
                }
                if(empty($request->password)){
                    return response()->json(["status" => 0, "msg" => "新登录密码必须"]);
                }
                if(empty($request->rePassword)){
                    return response()->json(["status" => 0, "msg" => "确认密码必须"]);
                }
                if($request->password!=$request->rePassword){
                    return response()->json(["status" => 0, "msg" => "两次密码不一样"]);
                }
                if($request->old_pwd != \App\Member::DecryptPassWord($EditMember->password)){
                    return response()->json(["status" => 0, "msg" => "旧登录密码错误"]);
                }
                $EditMember->password = \App\Member::EncryptPassWord(trim($request->password));
                $EditMember->lastsession = '';
                if ($EditMember->save()) {
                    return response()->json(["status" => 1, "msg" => "修改成功"]);
                } else {
                    return response()->json(["status" => 0, "msg" => "修改失败"]);
                }
            }
            if($type == 'pay_password') {
                if(empty($request->old_pay_pwd)){
                    return response()->json(["status" => 0, "msg" => "旧支付密码必须"]);
                }
                if(empty($request->pay_pwd)){
                    return response()->json(["status" => 0, "msg" => "新支付密码必须"]);
                }
                if(empty($request->re_pay_pwd)){
                    return response()->json(["status" => 0, "msg" => "确认支付密码必须"]);
                }
                if($request->pay_pwd!=$request->re_pay_pwd){
                    return response()->json(["status" => 0, "msg" => "两次支付密码不一样"]);
                }
                $old_pay_pwd = \App\Member::EncryptPassWord(trim($request->old_pay_pwd));
                if($old_pay_pwd != $EditMember->paypwd){
                    return response()->json(["status" => 0, "msg" => "旧支付密码错误"]);
                }
                $EditMember->password = $old_pay_pwd;
                if ($EditMember->save()) {
                    return response()->json(["status" => 1, "msg" => "修改成功"]);
                } else {
                    return response()->json(["status" => 0, "msg" => "修改失败"]);
                }
            }
        }
        return response()->json(["status" => 0, "msg" => "修改失败，用户信息错误！"]);
    }

    /***会员交易密码修改***/
    public function paypwd(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();

        if ($EditMember) {

            $mobile = \App\Member::DecryptPassWord($EditMember->mobile);


            $paypwd = \App\Member::DecryptPassWord($EditMember->paypwd);

            if ($request->newpass == '') {
                return ["status" => 1, "msg" => "请输入密码"];
            }

            if ($request->pass != $paypwd) {
                return ["status" => 1, "msg" => "输入旧密码错误"];
            }

            //   if ($request->telcode=='') {
            //       return array('msg'=>"请输入短信验证码",'status'=>"1");
            //   }

            // if ($request->telcode!=Cache::get("mobile.code.".$mobile)) {
            //     return array('msg'=>"你输入的短信验证码错误",'status'=>"1");
            // }

            $EditMember->paypwd = \App\Member::EncryptPassWord($request->newpass);
            if ($EditMember->save()) {
                return ["status" => 1, "msg" => "交易密码修改成功"];
            } else {
                return ["status" => 0, "msg" => "交易密码修改失败"];
            }

        }

    }

    /***会员手机认证***/
    public function mobile(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();

        if ($EditMember) {

            $password = \App\Member::DecryptPassWord($EditMember->password);

            $mobile = $request->mobile;

            $isPhones = Memberphone::IsUpdate($mobile, $UserId);

            if ($request->password != $password && $request->password != '') {
                return response()->json(["status" => 0, "msg" => "密码不正确"]);
            }

            //   if ($request->telcode=='') {
            //       return response()->json(["status"=>0,"msg"=>"请输入短信验证码"]);
            //   }

            if (strlen($mobile) != 11) {
                return response()->json(['status' => 0, 'msg' => "您输入的手机位数不对"]);
            }

            if ($isPhones) {
                return response()->json(["status" => 0, "msg" => "该手机号已存在"]);
            }

            // if ($request->telcode!=Cache::get("mobile.code.".$mobile)) {
            //     return array('msg'=>"你输入的短信验证码错误",'status'=>"1");
            // }
            // $check_time = strtotime("-10 minute");
            //  $sms_code = DB::table('membersms')
            //      ->where(['mobile'=>$mobile,'sms_status'=>1])
            //      ->where('create_time','<=',time())
            //     ->where('create_time','>=',$check_time)
            //      ->orderBy('create_time','desc')
            //      ->first();
            //  if(!$sms_code || $sms_code->code != $request->telcode){
            //      return array('msg'=>"短信验证码错误，请重新输入",'status'=>"0");
            //  }

            $EditMember->ismobile = 1;
            $EditMember->mobile = \App\Member::EncryptPassWord($request->mobile);
            $EditMember->save();
            return response()->json(["status" => 1, "msg" => "手机绑定成功"]);

        }

    }

    /***会员银行信息***/
    public function banks(Request $request)
    {
        $user_id = [$this->Member->id];
        if($this->Member->type == 1){
            $user_id = [$this->Member->id,$this->Member->pid];
        }
        $list = DB::table("memberbank")->whereIn("userid", $user_id)->get(['id','bankrealname','bankname','bankcode','type','address']);
        foreach ($list as $val){
            if($val->type == 1){
                $val->bankrealname = str_repeat("*", mb_strlen($val->bankrealname, 'utf-8') - 1)  . mb_substr($val->bankrealname, -1, 1, 'utf-8');
                $val->bankcode = substr_replace($val->bankcode, ' **** **** ', 4, 9);
                $val->bank_type = '储蓄卡';
            }
            if($val->type == 3){
                $val->address = substr_replace($val->address, ' **** **** ', 4, 24);
                $val->bankname = 'USDT';
                $val->bank_type = 'TRC20';
            }
        }
        return response()->json(['status' => 1, 'data' => $list]);
    }

    /***会员添加银行卡***/
    public function bankAdd(Request $request)
    {
        $UserId = $this->Member->id;
        $type = $request->post('type',1);
        $name = $request->post('name','');
        $bank_name = $request->post('bank_name','');
        $address = $request->post('address','');
        $card_no = $request->post('card_no','');
        $usdt_address = $request->post('usdt_address','');
        if(!in_array($type,[1,3])){
            return response()->json(["status" => 0, "msg" => "不支持的提现方式"]);
        }
        //只有一张银行卡
        $memberBanks_count = DB::table("memberbank")->where(['userid' => $UserId,'type'=>$type])->count();
        if ($memberBanks_count >= 1 && $type == 1) {
            return response()->json(["status" => 0, "msg" => "最多只能添加一张银行卡，请联系客服"]);
        }
        if ($memberBanks_count >= 1 && $type == 3) {
            return response()->json(["status" => 0, "msg" => "最多只能添加一个提现地址，请联系客服"]);
        }
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        $data['type'] = $type;
        $data['userid'] = $UserId;
        if ($data['type'] == 1) { //银行
            if(empty($name)){
                return response()->json(["status" => 0, "msg" => "持卡人姓名必须"]);
            }
            if(empty($bank_name)){
                return response()->json(["status" => 0, "msg" => "银行名称必须"]);
            }
            if(empty($card_no)){
                return response()->json(["status" => 0, "msg" => "银行卡号必须"]);
            }
            $data['bankname'] = trim(urldecode($bank_name));
            $data['bankrealname'] = trim(urldecode($name));
            $data['bankcode'] = trim(urldecode($card_no));
            $data['bankaddress'] = trim(urldecode($address));
            $data['status'] = 1;
        }
        if ($data['type'] == 3) { //USDT
            if(empty($usdt_address) || strlen($usdt_address) != 34 || substr($usdt_address,0,1) !='T'){
                return response()->json(["status" => 0, "msg" => "提现地址错误"]);
            }
            $data['address'] = trim(urldecode($usdt_address));
            $data['status'] = 1;
        }

        $res = DB::table("memberbank")->insertGetId($data);
        if ($res > 0) {
            return response()->json(["status" => 1, "msg" => "添加成功",'data' => $res]);
        } else {
            return response()->json(["status" => 0, "msg" => "添加失败"]);
        }
    }

    /**
     * 提现配置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdrawConfig(Request $request){
        $data = [];
        $list = DB::table("setings")->whereIn('keyname',['withdra_fee','usdt_rate'])->get(['keyname','value']);
        foreach ($list as $value){
            if($value->keyname == 'withdra_fee'){
                $data['withdraw'] = $value->value;
            }
            if($value->keyname == 'usdt_rate'){
                $data['usdt_rate'] = $value->value;
            }
        }
        return response()->json(["status" => 1, "msg" => "ok",'data' => $data]);
    }

    /**
     * 数据脱敏
     * @param string $string 需要脱敏值
     * @param int $start 开始
     * @param int $length 结束
     * @param string $re 脱敏替代符号
     * @return bool|string
     * 例子:
     * dataDesensitization('18811113683', 3, 4); //188****3683
     * dataDesensitization('乐杨俊', 0, -1); //**俊
     */
    public function dataDesensitization($string, $start = 0, $length = 0, $re = '*')
    {
        if (empty($string)){
            return false;
        }
        $strarr = array();
        $mb_strlen = mb_strlen($string);
        while ($mb_strlen) {//循环把字符串变为数组
            $strarr[] = mb_substr($string, 0, 1, 'utf8');
            $string = mb_substr($string, 1, $mb_strlen, 'utf8');
            $mb_strlen = mb_strlen($string);
        }
        $strlen = count($strarr);
        $begin = $start >= 0 ? $start : ($strlen - abs($start));
        $end = $last = $strlen - 1;
        if ($length > 0) {
            $end = $begin + $length - 1;
        } elseif ($length < 0) {
            $end -= abs($length);
        }
        for ($i = $begin; $i <= $end; $i++) {
            $strarr[$i] = $re;
        }
        if ($begin >= $end || $begin >= $last || $end > $last) return false;
        return implode('', $strarr);
    }

    //编辑银行卡信息
    public function bankEdit(Request $request)
    {
        return response()->json(['status' => 0, 'msg' => '如需修改银行卡，请联系客服']);
        $UserId = $request->session()->get('UserId');
        //多银行卡修改
        // $bank_id = $request->get('id');
        // if($bank_id == '' || !is_numeric($bank_id)){
        //     return response()->json(["status"=>0,"msg"=>"参数错误"]);
        // }
        $memberbank_info = DB::table('memberbank')->where(['userid' => $UserId])->first();

        if ($request->telcode == '') {
            return response()->json(["status" => 0, "msg" => "请输入短信验证码"]);
        }
        $mobile = DB::table("member")->where(['id' => $UserId])->value('username');
        $check_time = strtotime("-10 minute");
        $sms_code = DB::table('membersms')
            ->where(['mobile' => $mobile, 'sms_status' => 1])
            ->where('create_time', '<=', time())
            ->where('create_time', '>=', $check_time)
            ->orderBy('create_time', 'desc')
            ->first();
        // if($request->telcode != 8597 && (!$sms_code || $sms_code->code != $request->telcode)){
        if (!$sms_code || $sms_code->code != $request->telcode) {
            return response()->json(["status" => 0, "msg" => "短信验证码错误，请重新输入"]);

        }


        if ($request->bankname != '') {
            $data['bankname'] = trim($request->bankname);
        } else {
            return response()->json(["status" => 0, "msg" => "银行卡名称不能为空"]);
        }

        if ($request->bankrealname != '') {
            $data['bankrealname'] = trim($request->bankrealname);
        } else {
            return response()->json(["status" => 0, "msg" => "开户人不能为空"]);
        }

        if ($request->bankcode != '') {
            $data['bankcode'] = trim($request->bankcode);
        } else {
            return response()->json(["status" => 0, "msg" => "银行卡号不能为空"]);
        }

        // if($request->bankaddress!=''){
        //     $data['bankaddress'] = trim($request->bankaddress);
        // }
        if (!$memberbank_info) {
            $data['created_at'] = Carbon::now();
            $data['userid'] = $UserId;
            $res = DB::table("memberbank")->insertGetId($data);
            unset($data['userid']);
        } else {
            $data['updated_at'] = Carbon::now();
            $res = DB::table("memberbank")->where(['userid' => $UserId])->update($data);
        }

        if ($res) {
            return response()->json(["status" => 1, "msg" => "修改成功", 'data' => $data]);
        } else {
            return response()->json(["status" => 0, "msg" => "修改失败", 'data' => $data]);
        }
    }

    /**银行卡删除**/
    public function bankDel(Request $request)
    {
        return response()->json(['status' => 0, 'msg' => '如需修改银行卡，请联系客服']);
        $UserId = $request->session()->get('UserId');

        if (!$request->id) {
            return response()->json(['status' => 0, 'msg' => '参数错误']);
        }
        $bank_info = DB::table("memberbank")->where(["userid" => $UserId, "id" => $request->id, 'status' => 1])->first();
        $res = DB::table("memberbank")
            ->where("userid", $UserId)
            ->where("id", $request->id)
            ->delete();

        if ($bank_info) {
            DB::table("memberbank")->where("userid", $UserId)->limit(1)->update(['status' => 1]);
        }
        if ($res) {
            return response()->json(['status' => 1, 'msg' => '删除成功']);
        } else {
            return response()->json(['status' => 0, 'msg' => '删除失败']);
        }

    }

    /***收货地址列表***/
    public function addresses(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $MemberAddresses = DB::table("memberaddress")->where("userid", $UserId)->get();

        return response()->json(['status' => 1, 'data' => $MemberAddresses]);

    }

    /***收货地址修改***/
    public function addressEdit(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $addressId = trim($request->id);
        $EditAddress = DB::table('memberaddress')->where(["id" => $addressId, "userid" => $UserId])->first();

        if ($EditAddress) {

            $data['status'] = trim($request->status);

            if ($request->status == 1) {
                $memberBanks = DB::table("memberaddress")->where(['userid' => $UserId, 'status' => 1])->update(['status' => 0]);
            }

            if ($request->receiver != '') {
                $data['receiver'] = trim($request->receiver);
            } else {
                return response()->json(["status" => 0, "msg" => "收件人不能为空"]);
            }

            if ($request->area != '') {
                $data['area'] = trim($request->area);
            } else {
                return response()->json(["status" => 0, "msg" => "地区不能为空"]);
            }

            if ($request->address != '') {
                $data['address'] = trim($request->address);
            } else {
                return response()->json(["status" => 0, "msg" => "详细地址不能为空"]);
            }

            if ($request->mobile == '' || strlen($request->mobile) != 11) {
                return response()->json(["status" => 0, "msg" => "请输入正确的电话号码"]);
            } else {
                $data['mobile'] = trim($request->mobile);
            }
            $data['updated_at'] = Carbon::now();

            $res = DB::table("memberaddress")->where(['userid' => $UserId, "id" => $addressId])->update($data);
            if ($res) {
                return response()->json(["status" => 1, "msg" => "修改成功"]);
            } else {
                return response()->json(["status" => 0, "msg" => "修改失败"]);
            }
        }
    }

    /***收货地址删除***/
    public function addressDel(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $addressId = trim($request->id);
        $EditAddress = DB::table('memberaddress')->where(["id" => $addressId, "userid" => $UserId])->first();

        if ($EditAddress) {

            $res = DB::table('memberaddress')->where(["id" => $addressId, "userid" => $UserId])->delete();

            if ($res) {
                return response()->json(["status" => 1, "msg" => "删除成功"]);
            } else {
                return response()->json(["status" => 0, "msg" => "删除失败"]);
            }

        } else {

            return response()->json(["status" => 0, "data" => '该地址不存在']);

        }

    }

    /***收货地址添加***/
    public function addressAdd(Request $request)
    {

        $UserId = $request->session()->get('UserId');


        $data['created_at'] = $data['updated_at'] = Carbon::now();

        $data['userid'] = $UserId;

        if ($request->status == 1) {
            $memberBanks = DB::table("memberaddress")->where(['userid' => $UserId, 'status' => 1])->update(['status' => 0]);
        }

        $address_count = DB::table("memberaddress")->where(['userid' => $UserId])->count();
        if ($address_count > 3) {
            return response()->json(["status" => 0, "msg" => "您已添加多个地址"]);
        }

        if ($request->receiver != '') {
            $data['receiver'] = trim($request->receiver);
        } else {
            return response()->json(["status" => 0, "msg" => "收件人不能为空"]);
        }

        if ($request->area != '') {
            $data['area'] = trim($request->area);
        } else {
            return response()->json(["status" => 0, "msg" => "地区不能为空"]);
        }

        if ($request->mobile == '' || strlen($request->mobile) != 11) {
            return response()->json(["status" => 0, "msg" => "请输入正确的电话号码"]);
        } else {
            $data['mobile'] = trim($request->mobile);
        }

        if ($request->address != '') {
            $data['address'] = trim($request->address);
        } else {
            return response()->json(["status" => 0, "msg" => "详细地址不能为空"]);
        }

        // if($request->status!=''){
        //         $address_count = DB::table("memberaddress")->where(['userid'=>$UserId])->count();


        //             if($address_count > 0  && $request->status ==1 ){
        //               DB::table("memberaddress")->where(['userid'=>$UserId])->update(['status'=>0]);
        //             }else if($address_count == 0 ){
        //               $request->status = 1;

        //             }

        //       $data['status'] = trim($request->status);
        //   }

        $data['created_at'] = Carbon::now();

        $res = DB::table("memberaddress")->insert($data);

        if ($res) {
            return response()->json(["status" => 1, "msg" => "添加地址成功"]);
        } else {
            return response()->json(["status" => 0, "msg" => "添加地址失败"]);
        }


    }

    /***默认修改***/
    public function statusEdit(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $type = trim($request->type);
        $id = trim($request->id);
        if ($type == 'bank') {
            $EditType = DB::table('memberbank')->where(["id" => $id, "userid" => $UserId])->first();
            if ($EditType) {
                $memberBanks = DB::table("memberbank")->where(['userid' => $UserId, 'status' => 1])->update(['status' => 0]);

                if (DB::table("memberbank")->where(['userid' => $UserId, 'id' => $id])->update(['status' => 1])) {
                    return response()->json(["status" => 1, "msg" => "修改成功"]);
                } else {
                    return response()->json(["status" => 0, "msg" => "修改失败"]);
                }
            } else {
                return response()->json(["status" => 0, "msg" => '该银行卡不存在']);
            }
        } else {
            $EditType = DB::table('memberaddress')->where(["id" => $id, "userid" => $UserId])->first();
            if ($EditType) {
                $memberAddresses = DB::table("memberaddress")->where(['userid' => $UserId, 'status' => 1])->update(['status' => 0]);
                if (DB::table("memberaddress")->where(['userid' => $UserId, 'id' => $id])->update(['status' => 1])) {
                    return response()->json(["status" => 1, "msg" => "修改成功"]);
                } else {
                    return response()->json(["status" => 0, "msg" => "修改失败"]);
                }
            } else {
                return response()->json(["status" => 0, "msg" => '该地址不存在']);
            }
        }

    }

    /****我的明细***/
    public function myDetail(Request $request)
    {
        $UserId = $this->Member->id;
        $pageSize = $request->get('pageSize', 99);
        $type = $request->post('type','');
        $time = $request->post('time','day');
        switch ($time) {
            case 'day':
                $date = date('Y-m-d');
                $time = [$date.' 00:00:00',$date.' 23:59:59'];
                break;
            case 'month':
                $start_data = date('Y-m-01 00:00:00',time());
                $end_data = date('Y-m-t 23:59:59',time());
                $time = [$start_data,$end_data];
                break;
        }
        $data = DB::table("moneylog")
            ->select('id', 'moneylog_money', 'product_title', 'moneylog_status', 'moneylog_type', 'moneylog_notice', 'bank_id', 'withdrawal_id', 'created_at', 'product_title', 'created_date', 'moneylog_num')
            ->where('moneylog_userid', $UserId)
            ->when($type == 'withdraw', function ($query) {
                $query->where('moneylog_type', 'like', '提款%');
            })
            ->when($type == 'recharge', function ($query) {
                $query->where('moneylog_type', '=', '充值');
            })
            ->when($type == 'income', function ($query) {
                $query->whereIn('moneylog_type', ['直推返佣','对碰奖励','星级奖励','股东奖励','静态收益']);

            })
            ->when($type == 'consumption', function ($query) {
                $query->whereIn('moneylog_type', ['参与项目,银行卡付款', '参与项目,余额付款']);

            })
            ->whereBetween('created_at',$time)
            ->orderBy("id", "desc")
            ->paginate($pageSize);
        //先查6~12月的统计数据
        foreach ($data as $v) {
            switch ($v->moneylog_type) {
                case '参与项目,余额付款':
                    $v->type = '买入';
                    $v->type_status = 1;
                    $v->pay_type = '余额';
                    break;
                case '参与项目,银行卡付款':
                    $v->type = '买入';
                    $v->type_status = 2;
                    $v->pay_type = '银行卡';
                    break;
                case '静态收益':
                    $v->type = '收益';
                    $v->type_status = 3;
                    break;
                case '对碰奖励':
                    $v->type = '对碰奖';
                    $v->type_status = 4;
                    break;
                case '直推返佣':
                    $result = array();
                    $v->type = '推荐奖';
                    $v->type_status = 5;
                    break;
                case '提款':
                    $v->type = '提款失败';
                    $v->type_status = 6;
                    if ($v->moneylog_status == '-') {
                        $v->type = '提款申请';
                        $v->moneylog_money = '-' . $v->moneylog_money;
                        $bank = DB::table("memberwithdrawal")
                            ->select('bankid')
                            ->where("userid", $UserId)
                            ->where("id", $v->withdrawal_id)
                            ->first();
                        if ($bank) {
                            $v->bankInfo = DB::table('memberbank')->select('bankname', 'bankrealname', 'bankcode', 'bankaddress', 'type')->where('id', $bank->bankid)->first();
                        }
                    }
                    break;
                case '提款成功':
                    $v->type = '提款成功';
                    $v->type_status = 7;
                    $bank = DB::table("memberwithdrawal")
                        ->select('bankid')
                        ->where("userid", $UserId)
                        ->where("id", $v->withdrawal_id)
                        ->first();
                    if ($bank) {
                        $v->bankInfo = DB::table('memberbank')->select('bankname', 'bankrealname', 'bankcode', 'bankaddress', 'type')->where('id', $bank->bankid)->first();
                    }
                    break;
                case '充值':
                    $v->type = '充值';
                    $v->type_status = 8;
                default:
            }
        }
        return response()->json(['status' => 1, 'data' => $data]);
    }

    /****我的明细***/
    public function myProductDetail(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $pageSize = $request->get('pageSize', 10);
        $category_id = $request->category_id;
        if (!$category_id) {
            return response()->json(["status" => 0, "msg" => "参数不能为空"]);
        }


        $data = DB::table("moneylog as ml")
            ->leftjoin('products as p', 'p.id', '=', 'ml.product_id')
            ->select('ml.id', 'ml.moneylog_money', 'ml.product_title', 'ml.moneylog_status', 'ml.moneylog_type',
                'ml.moneylog_notice', 'ml.bank_id', 'ml.withdrawal_id', 'ml.created_at', 'ml.product_title', 'p.title')
            ->where('ml.moneylog_userid', $UserId)
            ->where('ml.category_id', $category_id)
            ->where('ml.moneylog_type', '<>', '积分奖励')
            ->where('ml.moneylog_type', '<>', '商品购买')
            ->orderBy("ml.id", "desc")->paginate($pageSize);


        foreach ($data as $v) {
            if ($v->moneylog_type == '加入项目,余额付款') {
                $v->type = '买入';
                $v->type_status = 1;
                $v->pay_type = '余额';
            } else if ($v->moneylog_type == '加入项目,银行卡付款') {
                $v->type = '买入';
                $v->type_status = 1;
                $v->pay_type = '银行卡';
            } else if ($v->moneylog_type == '项目分红') {
                $v->type = '收益';
                $v->type_status = 2;
            } else if ($v->moneylog_type == '下线购买分成') {
                $v->type = '收益';
                $v->type_status = 2;
            } else if ($v->moneylog_type == '项目本金返款') {
                $v->type = '赎回';
                $v->type_status = 3;
            } else if ($v->moneylog_type == '项目本金及分红返款') {
                $v->type = '收益赎回';
                $v->type_status = 3;
            } else if ($v->moneylog_type == '货币转出') {
                $v->type = '转出';
                $v->type_status = 6;
                $v->moneylog_money = '-' . $v->moneylog_money;
            } else if ($v->moneylog_type == '货币转入') {
                $v->type = '转入';
                $v->type_status = 7;
            }
        }

        return response()->json(['status' => 1, 'data' => $data]);

    }

    /***我的团队  未完成***/
    public function myteam(Request $request)
    {

        // $UserId =$request->session()->get('UserId');
        // $my_code = DB::table("member")->where("id",$UserId)->value('invicode');//我的邀请码
        // $pagesize = 8;
        // $top_list1 = DB::table("member")->select('id','nickname','mobile','picImg','invicode','created_at')->where("top_uid",$UserId)->where("state","1")->paginate($pagesize);

        // foreach($top_list1 as $v){
        //     $v->mobile = \App\Member::DecryptPassWord($v->mobile);
        // }
        $UserId = $request->session()->get('UserId');
        $pageSize = $request->get('pageSize', 10);
        $level = $request->get('level', 1);

        if ($level == 1) {
            $where = ['top_uid' => $UserId];
        } else {
            $where = ['ttop_uid' => $UserId];
        }
        $top_list = DB::table("member")
            ->select('id', 'nickname', 'mobile', 'picImg', 'invicode', 'created_at')
            ->where($where)->where("state", "1")
            ->paginate($pageSize);

        foreach ($top_list as $v) {
            $v->mobile = substr_replace(\App\Member::DecryptPassWord($v->mobile), '****', 3, 4);
            $v->created_at = date('Y.m.d', strtotime($v->created_at));
        }
        $lv1_count = DB::table("member")->where(['top_uid' => $UserId, 'state' => 1])->count();
        $lv2_count = DB::table("member")->where(['ttop_uid' => $UserId, 'state' => 1])->count();

        $data['list'] = $top_list;
        $data['lv1_count'] = $lv1_count;
        $data['lv2_count'] = $lv2_count;

        // $data = [
        //         //   'myteam_count'=>count($top_list1),
        //           'top_list1'=>$top_list1,
        //         ];

        // $UserId =$request->session()->get('UserId');
        // $my_code = DB::table("member")->where("id",$UserId)->value('invicode');//我的邀请码

        // $myteam_count = 0;
        // //我的一级团队
        // $top_list1 = DB::table("member")->select('id','nickname','invicode')->where("inviter",$my_code)->where("state","1")->get();
        // foreach ($top_list1 as $k => $v) {
        //     // $top_list1[$k]->team_count = DB::table("member")->where("top_uid",$v->id)->orWhere("ttop_uid",$v->id)->where("state","1")->count();
        //     if(!$v->nickname){
        //       $top_list1[$k]->nickname = $v->id;
        //     }
        //     $top_list1[$k]->team_count = DB::table("member")->where("inviter",$v->invicode)->where("state","1")->count();
        //     $top_list1[$k]->order_count = DB::table("productbuy")->where("userid",$v->id)->where("status","1")->count();
        //     $myteam_count++;
        // }


        // $top_list2 = DB::table("member")->select('id','nickname','invicode')->where("inviter",$my_code)->where("state","1")->get();
        // foreach ($top_list2 as $k => $v) {
        //     // $top_list2[$k]->team_count = DB::table("member")->where("top_uid",$v->id)->orWhere("ttop_uid",$v->id)->where("state","1")->count();
        //     if(!$v->nickname){
        //       $top_list2[$k]->nickname = $v->id;
        //     }
        //     $top_list2[$k]->team_count = DB::table("member")->where("inviter",$v->invicode)->where("state","1")->count();
        //     $top_list2[$k]->order_count = DB::table("productbuy")->where("userid",$v->id)->where("status","1")->count();
        // }

        // $data = [
        //   'myteam_count'=>$myteam_count,
        //   'top_list1'=>$top_list1,
        //   'top_list2'=>$top_list2,
        //   'money'=> sprintf("%.2f",$this->Member->amount),
        // ];

        return response()->json(["status" => 1, "data" => $data]);

    }

    /**
     * 社区
     * @param Request $request
     */
    public function community(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $username = $request->get('op','');
        if(!empty($username)){
            $op_member = Member::where(['username'=>$username])->first(['id']);
            if($op_member){
                $UserId = $op_member->id;
            }
        }
        $Member = Member::find($UserId);
        //总业绩
        $data['total_amount'] = number_format($Member->left_amount+$Member->right_amount,2);
        //用户左区业绩
        $data['left_amount'] = number_format($Member->left_amount,2);
        //用户左区业绩
        $data['right_amount'] = number_format($Member->right_amount,2);
        //新增直推
        $today = date('Y-m-d',time());
        $level_1_list = Member::where(['top_uid'=>$UserId])->get(['id','created_date','region','left_amount','right_amount']);
        $left_count = 0;
        $right_count = 0;
        $left_count_today = 0;
        $right_count_today = 0;
        foreach ($level_1_list as $value){
            if($value->region == 1){
                $left_count++;
                if($value->created_date == $today){
                    $left_count_today++;
                }
            }
            if($value->region == 2){
                $right_count++;
                if($value->created_date == $today){
                    $right_count_today++;
                }
            }
        }
        $data['new_user_count'] = $left_count_today + $right_count_today;
        //左区新增
        $data['left_count_today'] = $left_count_today;
        //右区新增
        $data['right_count_today'] = $right_count_today;
        //直推总人数
        $data['total_level_1_count'] = count($level_1_list);
        //左区直推人数
        $data['left_count'] = $left_count;
        //右区直推人数
        $data['right_count'] = $right_count;
        return response()->json(['status' => 1, 'data' => $data]);
    }

    public function community_detail(Request $request){
        $UserId = $request->session()->get('UserId');
        $type = $request->get('type',1);
        $pageSize = $request->get('pageSize', 10);
        $username = $request->get('op','');
        if(!empty($username)){
            $op_member = Member::where(['username'=>$username])->first(['id']);
            if($op_member){
                $UserId = $op_member->id;
            }
        }
        $Member = Member::find($UserId);
        //新增直推
        $today = date('Y-m-d',time());
        $level_1_list = Member::where(['top_uid'=>$UserId])->get(['id','created_date','region','left_amount','right_amount']);
        $left_count = 0;
        $right_count = 0;
        $left_count_today = 0;
        $right_count_today = 0;
        $left_ids = [];
        $right_ids = [];
        foreach ($level_1_list as $value){
            if($value->region == 1){
                $left_count++;
                $left_ids[] = $value->id;
                if($value->created_date == $today){
                    $left_count_today++;
                }
            }
            if($value->region == 2){
                $right_count++;
                $right_ids[] = $value->id;
                if($value->created_date == $today){
                    $right_count_today++;
                }
            }
        }
        $data = [];
        $level_id_arr = [];
        if($type == 1){
            //左区
            $data['total_amount'] = number_format($Member->left_amount,2);
            //直推
            $data['total_level_1_count'] = $left_count;
            //今日新增直推
            $data['new_user_count'] = $left_count_today;
            $level_id_arr = $left_ids;
        }
        if($type == 2){
            //右区
            $data['total_amount'] = number_format($Member->right_amount,2);
            //直推
            $data['total_level_1_count'] = $right_count;
            //今日新增直推
            $data['new_user_count'] = $right_count_today;
            $level_id_arr = $right_ids;
        }
        $level_info = Member::select('id', 'nickname', 'username', 'picImg', 'created_at')->whereIn('id', $level_id_arr)->paginate($pageSize);
        foreach ($level_info as $v) {
            $v->op = $v->username;
            $v->username = substr_replace($v->username, '****', 3, 4);
            $v->created_data = substr($v->created_at, 0, 10);
            if (preg_match("/[\x7f-\xff]/", $v->nickname)) {
                $v->nickname = mb_substr($v->nickname, 0, 1, 'utf-8') . '****';
            } else {
                $v->nickname = substr_replace($v->nickname, '****', 3);
            }
        }
        $data['list'] = $level_info;
        return response()->json(['status' => 1, 'data' => $data]);
    }

    //团队业绩
    public function teamReport(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $Member = Member::find($UserId);
        $pageSize = $request->get('pageSize', 10);
        $level_type = $request->get('level', 1);
        $gleveinfo = Db::table('membergrouplevel')->find($Member->glevel);
        $glevelinfo1 = '普通会员';
        $grate = 0;
        if (!empty($gleveinfo)) {
            $glevelinfo1 = $gleveinfo->name;
            $grate = $gleveinfo->rate;
        }
        $where = $level_one = $level_two = $level_three = $level_four = $level_five = [];
        $first_charge_count = $new_user_count = 0;

        //团队余额
        $team_balance = 0;
        // $team_balance = Member::whereIn('id',$all_level_uid)->sum('amount');
        //总推荐奖励
        $total_reward_amount = DB::table('moneylog')->where(['moneylog_userid' => $UserId, 'moneylog_type' => '下线购买分成'])->sum('moneylog_money');

        //团队流水(购买过的产品总金额)
        $my_statistics = DB::table("statistics")
            ->where('top_one_uid', $UserId)
            ->orwhere('top_two_uid', $UserId)
            ->get();

        $today_data = date("Y-m-d");
        foreach ($my_statistics as $ms) {
            if ($ms->top_one_uid == $UserId) {
                $level_one[] = $ms->user_id;
            } else {
                $level_two[] = $ms->user_id;
            }
            if ($ms->team_balance > 0) {
                $first_charge_count += 1;
            }
            if ($ms->register_date == $today_data) {
                $new_user_count += 1;
            }
        }

        $all_level_uid = $my_statistics->pluck('user_id');
        $team_capital_flow = $my_statistics->sum('capital_flow');
        //团队总充值
        // $team_total_recharge = $my_statistics->sum('team_total_recharge');
        $team_total_recharge = $team_capital_flow;
        // $team_total_recharge = $team_capital_flow + $team_total_recharge;//要求：充值，应该是包含购买产品的金额

        $team_total_withdrawal = $my_statistics->sum('team_total_withdrawal');

        //一级团队   昵*称  手机***号  推荐人数  总充值  总提现  注册时间
        switch ($level_type) {
            case 1:
                $level_id_arr = $level_one;
                break;
            case 2:
                $level_id_arr = $level_two;
                break;
        }
        $level_info = Member::select('id', 'nickname', 'username', 'picImg', 'created_at')->where($where)->whereIn('id', $level_id_arr)->paginate($pageSize);

        foreach ($level_info as $v) {
            $v->username = substr_replace($v->username, '****', 3, 4);
            $v->created_data = substr($v->created_at, 0, 10);
            if (preg_match("/[\x7f-\xff]/", $v->nickname)) {
                $v->nickname = mb_substr($v->nickname, 0, 1, 'utf-8') . '****';
            } else {
                $v->nickname = substr_replace($v->nickname, '****', 3);
            }
        }

        //直推人数
        $direct_push_count = count($level_one);
        //团队人数
        $teams_count = count($all_level_uid);
        // 团队总投资
        $team_ids = array_merge($level_one, $level_two);
        //总充值
        $member_data = [];
        $member_data['allrecharge'] = DB::table('productbuy')->where(['pay_type' => 2])->whereIn('status', [0, 1])->whereIn('userid', $team_ids)->sum('amount');
        //总提现
        $member_data['alltixian'] = $team_total_withdrawal;
        $active_user_count = $first_charge_count;

        $data['team_balance'] = sprintf("%.2f", $team_balance);//团队余额
        $data['team_capital_flow'] = sprintf("%.2f", $team_capital_flow);//团队流水
        $data['team_total_recharge'] = sprintf("%.2f", $team_total_recharge);//团队总充值
        $data['team_total_withdrawal'] = sprintf("%.2f", $team_total_withdrawal);//团队总提现
        $data['team_order_commission'] = sprintf("%.2f", $total_reward_amount);
        $data['first_charge_count'] = $first_charge_count;
        $data['direct_push_count'] = $direct_push_count;
        $data['teams_count'] = $teams_count;
        $data['new_user_count'] = $new_user_count;
        $data['active_user_count'] = $active_user_count;


        $data['level_info'] = $level_info;
        $data['glevelinfo1'] = $glevelinfo1;
        $data['member'] = $member_data;
        $data['grate'] = $grate;
        return response()->json(['status' => 1, 'data' => $data]);
    }

    /**站内消息管理**/

    /***消息列表***/
    public function msglist(Request $request)
    {

        $UserId = $request->session()->get('UserId');
        $pagesize = 6;
        $pagesize = Cache::get("pcpagesize");
        $where = [];

        $list = DB::table("membermsg")
            ->select('username', 'title', 'content', 'status', 'types', 'from_name', 'created_at')
            ->where("userid", $UserId)
            ->orderBy("id", "desc")
            ->paginate($pagesize);

        foreach ($list as $item) {
            $item->date = date("Y.m.d H:i", strtotime($item->created_at));
        }

        return response()->json(["status" => 1, "data" => $list]);
    }

    /***消息未读个数***/
    public function msg(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $layims = DB::table("layims")->where("touid", $UserId)->where("status", 0)->count();

        if (Cache::has("msgs." . $UserId)) {
            $msgcount = Cache::get("msgs." . $UserId);
            //$msgcount =$msgcount +$layims;
            return response()->json(["status" => 1, "playSound" => 1, "msgs" => $msgcount, "layims" => $layims]);

        } else {
            $msgcount = Membermsg::where("userid", $UserId)->where("status", "0")->count();
            //$msgcount =$msgcount +$layims;
            Cache::put("msgs." . $UserId, $msgcount, 60);
            return response()->json(["status" => 1, "playSound" => 1, "msgs" => $msgcount, "layims" => $layims]);
        }
    }

    /**站内消息标记已读**/
    public function MsgRead(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        DB::table("membermsg")
            ->where("userid", $UserId)
            ->where("id", $request->id)
            ->update(["status" => 1]);
        return response()->json(["status" => 1, "msg" => "已读"]);
    }

    /**站内消息删除**/
    public function MsgDel(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        DB::table("membermsg")
            ->where("userid", $UserId)
            ->where("id", $request->id)
            ->delete();
        return response()->json(["status" => 1, "msg" => "已删除"]);
    }



    /***站内消息结束***/


    /***会员登录日志***/
    public function loginloglist(Request $request)
    {

        if ($request->ajax()) {
            $UserId = $request->session()->get('UserId');
            $pagesize = 6;
            $pagesize = Cache::get("pcpagesize");
            $where = [];

            $list = DB::table("memberlogs")
                ->where("userid", $UserId)
                ->orderBy("id", "desc")
                ->paginate($pagesize);
            foreach ($list as $item) {
                $item->date = date("m-d H:i", strtotime($item->created_at));
            }

            return ["status" => 0, "list" => $list, "pagesize" => $pagesize];
        } else {

            return view($this->Template . ".user.memberlogs");
        }


    }


    /***会员认证中心***/
    public function certification(Request $request)
    {


        return view($this->Template . ".user.certification");


    }

    /***会员手机认证***/
    public function security(Request $request)
    {


        if ($request->ajax()) {
            $UserId = $request->session()->get('UserId');

            $EditMember = Member::where("id", $UserId)->first();

            if ($EditMember) {

                $EditMember->question = $request->question;
                $EditMember->answer = $request->answer;
                $EditMember->isquestion = 1;
                //$EditMember->mobile=\App\Member::EncryptPassWord($request->mobile);
                $EditMember->save();


                return ["status" => 0, "msg" => "密保设置成功"];

            }
        } else {

            return view($this->Template . ".user.security");
        }


    }


    /***会员密码修改***/
    public function password(Request $request)
    {


        if ($request->ajax()) {
            $UserId = $request->session()->get('UserId');

            $EditMember = Member::where("id", $UserId)->first();

            if ($EditMember) {


                $password = \App\Member::DecryptPassWord($EditMember->password);

                if ($request->pass != $password) {
                    return ["status" => 1, "msg" => "输入旧密码错误"];
                }

                if ($request->newpass != $request->renewpass) {
                    return ["status" => 1, "msg" => "输入两次密码不至"];
                }

                $EditMember->password = \App\Member::EncryptPassWord($request->newpass);
                $EditMember->save();


                return ["status" => 0, "msg" => "登录密码修改成功"];


            }
        } else {

            return view($this->Template . ".user.password");
        }


    }


    /***会员重置交易密码修改***/
    public function retrieve(Request $request)
    {

        if ($request->ajax()) {
            $UserId = $request->session()->get('UserId');

            $EditMember = Member::where("id", $UserId)->first();

            if ($EditMember) {

                $mobile = \App\Member::DecryptPassWord($EditMember->mobile);

                if ($request->telcode == '') {
                    return array('msg' => "请输入短信验证码", 'status' => "1");
                }
                if ($request->telcode != Cache::get("mobile.code." . $mobile)) {
                    return array('msg' => "你输入的短信验证码错误", 'status' => "1");
                }
                if ($request->newpass == '' || $request->renewpass == '') {
                    return ["status" => 1, "msg" => "请输入密码"];
                }
                if ($request->newpass != $request->renewpass) {
                    return ["status" => 1, "msg" => "输入两次密码不至"];
                }
                $EditMember->paypwd = \App\Member::EncryptPassWord($request->newpass);
                $EditMember->save();

                return ["status" => 0, "msg" => "交易密码修改成功"];
            }
        } else {
            return view($this->Template . ".user.retrieve");
        }
    }


    /***会员短信验证码发送***/
    public function SendCode(Request $request)
    {


        if ($request->ajax()) {
            $UserId = $request->session()->get('UserId');

            $EditMember = Member::where("id", $UserId)->first();

            $mobile = \App\Member::DecryptPassWord($EditMember->mobile);

            \App\Sendmobile::SendPhone($mobile, $request->action, '');//短信通知

            if ($request->ajax()) {
                return response()->json([
                    "msg" => "短信验证码发送成功", "status" => 0
                ]);
            }
        }
    }


    /***会员认证短信验证码发送***/
    public function SendRZCode(Request $request)
    {

        if ($request->ajax()) {
            $UserId = $request->session()->get('UserId');

            $EditMember = Member::where("id", $UserId)->first();

            // $mobile= \App\Member::DecryptPassWord($EditMember->mobile);

            \App\Sendmobile::SendPhone($request->mobile, $request->action, '');//短信通知

            if ($request->ajax()) {
                return response()->json([
                    "msg" => "短信验证码发送成功", "status" => 0
                ]);
            }
        }
    }


    /***投资产品***/
    public function products(Request $request)
    {


        if ($request->ajax()) {
            $UserId = $request->session()->get('UserId');

            $pagesize = 6;
            $pagesize = Cache::get("pcpagesize");
            $where = [];

            $list = DB::table("products")
                ->orderBy("sort", "desc")
                ->paginate($pagesize);
            foreach ($list as $item) {
                $item->date = date("m-d H:i", strtotime($item->created_at));
                $item->url = route("product", ["id" => $item->id]);
            }

            return ["status" => 0, "list" => $list, "pagesize" => $pagesize];
        } else {

            return view($this->Template . ".user.products");
        }
    }

    /**
     * 购买产品
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function create_order(Request $request){
        //支付方式
        $pay_type = $request->post('payment_type',0);
        $pay_id = $request->post('payment_id',0);
        //用户ID
        $UserId = $this->Member->id;
        //购买数量
        $num = $request->post('num',1);
        //购买产品ID
        $product_id = $request->post('product_id',0);
        //线下购买付款凭证
        $pay_img = $request->post('payment_certificate','');
        $pay_pwd = $request->post('password','');
        if($this->Member->state != 1){
            return response()->json(["status" => 0, "msg" => "会员关系树未处理完成，请售后购买财富计划！"]);
        }
        if (!in_array($pay_type,[1,2,3,4,5])) {
            return response()->json(["status" => 0, "msg" => "付款方式不支持"]);
        }
        if($pay_type == 2 && empty($pay_img)){
            return response()->json(["status" => 0, "msg" => "付款凭证不能为空！！"]);
        }
        //购买产品
        $product = DB::table("products")
            ->where(['id' => $product_id,'status'=>1])
            ->first();
        if (!$product) {
            return response()->json(["status" => 0, "msg" => "财富计划不存在或已下架！"]);
        }
        $Member = Member::where(['state'=>1])->find($UserId);
//        //判断用户是否可以购买
//        if($Member->status == 1){
//            return response()->json(["status" => 0, "msg" => "您还未出局，无法再次购买"]);
//        }
        $type_title = '';
        $order_type = 1;
        $before_order_id = 0;
        $real_amount = $integrals = $product->price * $num;
        //判断用户是否可以购买
        $user_buy_product = DB::table('productbuy')->where(['userid'=>$UserId,'status' =>1])->first(['id','amount','level']);
        if($this->Member->status == 1 && $user_buy_product){
            //最高等级财富计划
            $levelTop = DB::table('products')->where(['status' => 1])->orderBy('level','desc')->first(['id','price','level']);
            if($levelTop->level ==  $user_buy_product->level){
                return response()->json(["status"=>0,"msg"=>"已购买最高等级财富计划，请出局后再购买"]);
            }
            if($product->price <= $user_buy_product->amount || $product->level <= $user_buy_product->level){
                return response()->json(["status"=>0,"msg"=>"该等级财富计划已购买，请选择更高等级财富计划"]);
            }
            if($product->level > $user_buy_product->level){
                $real_amount = $product->price - $user_buy_product->amount;
                $type_title = '(升级)';
                $order_type = 2;
                $before_order_id = $user_buy_product->id;
            }
        }
        DB::beginTransaction();
        try {
            $ip = $request->getClientIp();
            $notice = "参与财富(" . $product->title . ")".$type_title;
            //余额支付
            if($pay_type == 1){
                if($pay_pwd != \App\Member::DecryptPassWord($Member->paypwd)){
                    return response()->json(["status" => 0, "msg" => "支付密码错误！"]);
                }
                if ($real_amount > $Member->ktx_amount) {
                    return response()->json(["status" => 0, "msg" => "余额不足,请充值,当前余额：" . $Member->ktx_amount]);
                }
                $Member = Member::where(['state'=>1])->lockForUpdate()->find($UserId);
                if (($Member->ktx_amount - $real_amount) < 0 ) {
                    DB::rollBack();
                    return response()->json(["status" => 0, "msg" => "余额不足,请充值"]);
                }
                $yuanamount = $Member->ktx_amount;
                $Member->decrement('ktx_amount', $real_amount);
                $log = [
                    "userid" => $Member->id,
                    "username" => $Member->username,
                    "money" => $real_amount,
                    "notice" => $notice,
                    "type" => "购买财富,余额付款",
                    "status" => "-",
                    "yuanamount" => $yuanamount,
                    "houamount" => $Member->ktx_amount,
                    "ip" => $ip,
                    "category_id" => $product->category_id,
                    "product_id" => $product->id,
                    "product_title" => $product->title,
                    'num' => $num,
                    'moneylog_type_id' => '1',
                ];
                \App\Moneylog::AddLog($log);
                $msg = [
                    "userid" => $Member->id,
                    "username" => $Member->username,
                    "title" => "购买财富",
                    "content" => "成功购买财富(" . $product->title . ")",
                    "from_name" => "系统通知",
                    "types" => "购买财富",
                ];
                \App\Membermsg::Send($msg);
                //增送积分(有需求后续加)
                if($integrals > 0 && $Member->id < 0){
                    $user_id = $Member->id;
                    $score = $integrals;
                    $type = 1;
                    $source_type = 5;
                    $act = APP::make(\App\Http\Controllers\Api\ActController::class);
                    App::call([$act, 'change_score_by_user_id'], [$user_id, $score, $type, $source_type]);
                }
            }
            // 创建订单
            $payment_info = DB::table('payment')->where(['id'=>$pay_id])->first(['bankcode','bank_type']);

            //判断下一次领取时间
            $useritem_time = \App\Productbuy::DateAdd("d", 1, date('Y-m-d 0:0:0', time()));
            $order_data = [
                'userid' => $Member->id,
                'username' => $Member->username,
                'level' => $product->level,
                'productid' => $product->id,
                'category_id' => $product->category_id,
                'type' => $order_type,
                'before_order_id' => $before_order_id,
                'amount' => $integrals,
                'real_amount' => $real_amount,
                'ip' => $ip,
                'useritem_time' => $useritem_time,
                'status' => $pay_type == 1 ? 1 : 2,//1-余额支付直接标记为收益中，其他支付标记为待审核
                'payimg' =>  in_array($pay_type,[2,5]) ? $pay_img : '',
                'pay_type' => $pay_type,
                'num' => $num,
                'unit_price' => $product->price,//购买时单价
                'usdt_remark' =>  $pay_type == 5 ? '收款USDT'.round($real_amount/$payment_info->bank_type,1).'|收款地址：'.$payment_info->bankcode.'|汇率：'.$payment_info->bank_type:'',//USDT支付备注
                'order' => 'WX' . date('YmdHis') . $this->get_random_code(7),
                'created_at' => Carbon::now(),
                'created_date' => date('Y-m-d')
            ];
            $res = DB::table('productbuy')->insertGetId($order_data);
            if ($res <= 0) {
                return response()->json(["status" => 0, "msg" => "购买失败，请重新操作"]);
            }
            //余额支付完成
            if ($pay_type == 1) {
                $ret = (new PayOrderController())->third_pay_finish_payment($res);
               if($ret['status'] == 0){
                   DB::rollBack();
                   Log::channel('pay')->warning('支付未完成-'.$ret['msg']);
                   return ['status' => 0, 'msg' => '提交失败，请重试1'];
               }
            }
            DB::commit();
            //USDT | 银联汇款支付
            if(in_array($pay_type,[1,2,5])){
                return response()->json(["status" => 1, "msg" => "购买成功"]);
            }
            //线上支付
            return (new PaymentController())->thirdToPay($res);
        }catch (\Exception $e){
            Log::channel('buy')->alert($e);
            DB::rollBack();
            return ['status' => 0, 'msg' => '提交失败，请重试2'];
        }
    }

    /**
     * 财富购买检查
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkOrder(Request $request){
        //只检查已激活用户
        $order = DB::table('productbuy')->where(['userid'=>$this->Member->id,'status' =>1])->first(['id','amount','level']);
        $data = ['price'=>0,'real_auth'=>$this->Member->is_auth];
        if($this->Member->status == 1 && $order){
            $id = $request->get('id',0);
            //最高等级财富计划
            $levelTop = DB::table('products')->where(['status' => 1])->orderBy('level','desc')->first(['id','price','level']);
            if($levelTop->level ==  $order->level){
                return response()->json(["status"=>2,"msg"=>"已购买最高等级财富计划，请出局后再购买"]);
            }
            $product = DB::table('products')->where(['id' => $id])->first(['id','price','level','status']);
            if($product->price <= $order->amount || $product->level <= $order->level){
                return response()->json(["status"=>2,"msg"=>"该等级财富计划已购买，请选择更高等级财富计划"]);
            }
            if($product->level > $order->level){
                $data['price'] = $product->price - $order->amount;
            }
        }
        return response()->json(["status"=>1,"msg"=>"ok", "data"=>$data]);
    }

    /**
     * 客服留言
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function message(Request $request){
        $content = $request->post('content','');
        if(empty($content)){
            return response()->json(["status" => 0, "msg" => "留言内容必须"]);
        }
        if(strlen($content) > 200 || strlen($content) < 5){
            return response()->json(["status" => 0, "msg" => "留言内容长度为5-200字内"]);
        }
        $data = [
            'user_id' =>$this->Member->id,
            'username' =>$this->Member->username,
            'content' =>$content,
            'created_at' =>Carbon::now()
        ];
        $res = DB::table('message')->insert($data);
        if(!$res){
            return response()->json(["status" => 0, "msg" => "提交失败"]);
        }
        return response()->json(["status" => 1, "msg" => "提交成功"]);
    }

    /**
     * 支付方式
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change_pay(Request $request){
        $list = DB::table('payment')->select('id','pay_name','pay_pic','bankname','bankrealname','bankcode','is_default','pay_type','type','bank_type')->where(['enabled'=>1])->get();
        $payment = [];
        $offlineCount = 0;
        $chainCount = 0;
        $bankList = [];
        $chainList = [];
        $tabs = [];
        foreach ($list as $val){
            if ($val->type=='online' && $val->pay_type==3){
                $payment[] = ['id'=>$val->id, 'name'=>$val->pay_name,'icon'=>'/static/images/assets/pay/alipay.png','type'=>$val->type,'pay_type'=>$val->pay_type];
            }
            if ($val->type=='online' && $val->pay_type==4){
                $payment[] = ['id'=>$val->id, 'name'=>$val->pay_name,'icon'=>'/static/images/assets/pay/wechat.png','type'=>$val->type,'pay_type'=>$val->pay_type];
            }
            if ($val->type=='wallet' && $val->pay_type==1){
                $payment[] = ['id'=>$val->id, 'name'=>$val->pay_name,'icon'=>'/static/images/assets/pay/wallet_icon.jpg','type'=>$val->type,'pay_type'=>$val->pay_type];
            }
            if($val->type=='offline' && $val->pay_type==2){
                ++$offlineCount;
                $bankList[] = ['id'=>$val->id, 'bank_name'=>$val->bankname,'card_id'=>$val->bankcode,'bank_type'=>$val->bank_type];
                $tabs['bank'][] = ['name'=>$val->pay_name];
            }
            if($val->type=='chain' && $val->pay_type==5){
                ++$chainCount;
                $chainList[] = ['id'=>$val->id, 'card_id'=>$val->bankcode,'bank_type'=>$val->bank_type];
                $tabs['chain'][] = ['name'=>$val->pay_name];
            }
        }
        if($offlineCount > 0){
            $payment = array_merge($payment,[['id'=>count($payment)+1,'name'=>'银联支付','icon'=>'/static/images/assets/pay/bank.png','type'=>'offline','pay_type'=>2]]);
        }
        if($chainCount > 0){
            $payment = array_merge($payment,[['id'=>count($payment)+2,'name'=>'USDT支付','icon'=>'/static/images/assets/pay/usdt.png','type'=>'chain','pay_type'=>5]]);
        }
        return response()->json(["status"=>1,"msg"=>"返回成功！","data"=>['payment'=>$payment,'bank_list'=>$bankList,'tabs'=>$tabs,'chain_list'=>$chainList]]);
    }

    public function huicenter(Request $request)
    {
        // return 1;
        //购买项目
        $UserId = $request->session()->get('UserId');
        // $Member = Member::find($UserId);
        $Member = DB::table("member")->find($UserId);
        $Memberlevel = DB::table("memberlevel")->find($Member->level);
        $weight = 0;
        // $Memberlevel =Memberlevel::find($Member->level);
        if (!empty($Memberlevel)) {
            $weight = $Memberlevel->weight;
        }
        $lastlevel = DB::table("memberlevel")->where("weight", ">", $weight)->orderBy("weight", "ASC")->first();

        if (!empty($lastlevel)) {
            $nextlevel = $lastlevel;
        } else {
            $nextlevel = $Memberlevel;
        }
        return response()->json(["status" => 1, "msg" => "获取成功", 'nextlevel' => $nextlevel, 'Memberlevel' => $Memberlevel, 'Member' => $Member]);
    }

    //加入货币
    public function insert_hb($data)
    {
        //如果是货币，添加到会员货币表
        $now_time = Carbon::now();
        $currencys = new Membercurrencys();
        $total_num = 0;
        $userid = $data['userid'];
        $productid = $data['productid'];
        $num = $data['num'];
        $user_currencys_info = $currencys::where(['userid' => $userid, 'productid' => $productid])->orderBy('created_at', 'desc')->first();
        if ($user_currencys_info) {
            // $update_currencys['num'] = $user_currencys_info->num + $request->number;
            // $update_currencys['total_num'] = $user_currencys_info->total_num + $request->number;
            $update_currencys['updated_at'] = $now_time;
            $currencys::where(['userid' => $userid, 'productid' => $productid])->increment('num', $num);
            $currencys::where(['userid' => $userid, 'productid' => $productid])->increment('total_num', $num);
        } else {
            $currencys->userid = $userid;
            $currencys->productid = $productid;
            $currencys->num = $num;
            $currencys->total_num = $num;
            $currencys->created_at = $now_time;
            $currencys->updated_at = $now_time;
            $currencys_res = $currencys->save();
        }
        return true;
    }

    public function thirdToMoney(Request $request)
    {

        $pay_type = $request->pay_type;//付款方式
        $pay_amount = $request->pay_amount;//付款金额
        //购买项目
        $UserId = $request->session()->get('UserId');
        if ($UserId < 1) {
            return response()->json(["status" => -1, "msg" => "请先登录！"]);
        }

        if ($pay_type != 3) {
            return response()->json(["status" => 0, "msg" => "该支付方式当前不可用"]);
        }
        // $checkSM = DB::table("member")->select('realname','card')->where(['id'=>$UserId])->first();
        // if(empty($checkSM->realname) || empty($checkSM->card)){
        //     return response()->json(["status"=>0,"msg"=>"请先完成实名后进行购买"]);
        // }
        if (!$pay_amount || $pay_amount <= 0) {
            return response()->json(["status" => 0, "msg" => "参数错误！！"]);
        }
        if (!$request->productid || !is_numeric($request->productid)) {
            return response()->json(["status" => 0, "msg" => "项目不存在或已下架！！"]);
        }
        if ($request->number < 1 || !is_numeric($request->number)) {
            return response()->json(["status" => 0, "msg" => "购买项目数量错误！"]);
        }

        $product = DB::table("products")
            ->select('id', 'title', 'category_id', 'qtje', 'isft', 'tzzt', 'hkfs', 'shijian', 'zgje', 'qxdw', 'zsje', 'zsje_type', 'jyrsy', 'qtsl', 'zscp_id')
            ->where(['id' => $request->productid])
            ->first();

        if (!$product) {
            return response()->json(["status" => 0, "msg" => "项目不存在或已下架！"]);
        }

        if ((int)$request->number < (int)$product->qtsl) {
            return response()->json(["status" => 0, "msg" => "低于项目最低起投数量"]);
        }

        $Member = Member::select('id', 'username', 'amount', 'paypwd', 'state', 'realname', 'mobile', 'level')->where('state', 1)->find($UserId);

        $integrals = $product->qtje * $request->number;//用户购买总金额

        $hkfs = trim($product->hkfs);  //还款方式
        $zhouqi = trim($product->shijian);//周期
        // if($product->category_id == 12){
        //     $hkfs = 4;
        // }
        //判断项目是否停止
        if ($product->tzzt != 0) {
            return response()->json(["status" => 0, "msg" => "该项目已售罄"]);
        }
        //判断起投数量
        if ($product->qtje > $integrals) {
            return response()->json(["status" => 0, "msg" => "您购买项目起投金额为" . $product->qtje]);
        }
        //判断最高投
        if ((int)$product->zgje !== 0) {
            if ($integrals > $product->zgje) {
                return response()->json(["status" => 0, "msg" => "您购买项目最高投入金额为" . $product->zgje]);
            }
        }
        //判断投资是否投过
        if ($product->isft == 0) {
            $Productbuy = Productbuy::where("productid", $request->productid)->where("userid", $this->Member->id)->where('status', '<>', 3)->first();
            if ($Productbuy) {
                return response()->json(["status" => 0, "msg" => "抱歉，该项目只允许投一次"]);
            }
        }
        //购买金额是否正确
        if ($integrals != $pay_amount) {
            return response()->json(["status" => 0, "msg" => "参数错误！"]);
        }


        //判断下一次领取时间
        $useritem_time2 = \App\Productbuy::DateAdd("d", 1, date('Y-m-d 0:0:0', time()));

        //放回调里
        $ip = $request->getClientIp();
        $notice = "加入项目(" . $product->title . ")(-)";

        $log = [
            "userid" => $this->Member->id,
            "username" => $this->Member->username,
            "money" => $integrals,
            "notice" => $notice,
            "type" => "加入项目,第三方付款(支付宝)",
            "status" => "-",
            "yuanamount" => $Member->amount,
            "houamount" => $Member->amount,
            "ip" => $ip,
            "category_id" => $product->category_id,
            "product_id" => $product->id,
            "product_title" => $product->title,
        ];
        \App\Moneylog::AddLog($log);


        $sendDay_count = $hkfs == 1 ? 1 : $zhouqi;
        $NewProductbuy = new Productbuy();

        //赠送金额
        // if($product->zsje_type == 2 ){
        //     $product->zsje = intval($integrals * (zsje * 0.01));
        // }

        $have_productbuy = Productbuy::where("productid", $request->productid)->where("userid", $this->Member->id)->first();

        $NewProductbuy->userid = $Member->id;
        $NewProductbuy->username = $Member->username;
        $NewProductbuy->level = $Member->level;
        $NewProductbuy->productid = $request->productid;
        $NewProductbuy->category_id = $product->category_id;
        $NewProductbuy->amount = $integrals;
        $NewProductbuy->ip = $ip;
        $NewProductbuy->useritem_time = Carbon::now();
        $NewProductbuy->useritem_time2 = $useritem_time2;
        $NewProductbuy->sendday_count = $sendDay_count;
        $NewProductbuy->status = 2;                 //未审核
        $NewProductbuy->num = $request->number;     //购买数量
        $NewProductbuy->unit_price = $product->qtje;//购买时单价
        $NewProductbuy->zsje = $product->zsje;
        $NewProductbuy->zscp_id = $product->zscp_id ? $product->zscp_id : 0;
        $NewProductbuy->order = substr((date('YmdHis') . $Member->id . $this->get_random_code(15)), 0, 25);
        $NewProductbuy->gq_order = 'Y' . $request->productid . ($Member->id + 555);

        $NewProductbuy->pay_type = 3;           //支付宝支付
        $NewProductbuy->pay_status = 0;
        // if($product->category_id == 12 ){
        // $NewProductbuy->gq_order = 'Y'.$this->get_random_code(9);
        // while(DB::table('productbuy')->where('gq_order',$NewProductbuy->gq_order)->first()){
        // $NewProductbuy->gq_order = 'Y'.$this->get_random_code(9);
        // }
        // }
        $res = $NewProductbuy->save();

        if (!$res) {
            return response()->json(["status" => 0, "msg" => "投资失败，请重新操作"]);
        } else {
            $data = [
                'merchantNum' => 'SUNNY',                  //商户号(商户号，由平台提供)
                'orderNo' => $NewProductbuy->order,               //商户订单号(仅允许字母或数字类型,建议不超过32个字符，不要有中文)
                'amount' => $NewProductbuy->amount,                          //支付金额(请求的价格(单位：元) 可以0.01元)
                'notifyUrl' => 'http://kline.kmzgb.com/api/papanr',   //异步通知地址(异步接收支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数。)
                'returnUrl' => 'https://www.baidu.com/',            //同步通知地址(支付成功后跳转到的地址，不参与签名。)
                'payType' => 'llzfb',                             //请求支付类型
                'payFrom' => 'xxx',
                'ip' => '12.12.12.12',
            ];
            //签名【md5(商户号+商户订单号+支付金额+异步通知地址+商户秘钥)】
            $data['sign'] = md5($data['merchantNum'] . $data['orderNo'] . $data['amount'] . $data['notifyUrl'] . '58f3b702b4621e52cec01df4ece52537');
            $res = $this->curl('https://api.dnxbpay.com/api/startOrder', $data);
            // dump($res);
            $res_arr = json_encode($res, JSON_UNESCAPED_UNICODE);
            // dump($res_arr);
            exit();
            if ($res->code != 200) {
                Log::channel('pay')->warning($res_arr);
                return response()->json(["status" => 0, "msg" => $res->msg]);
            }
            Log::channel('pay')->info($res_arr);
            DB::table('productbuy')
                ->where(['id' => $NewProductbuy->id])
                ->update(['third_party_order' => $res->data->id]);

            return response()->json(["status" => 1, "msg" => "跳转支付", 'payUrl' => $res->data->payUrl, 'data' => $res]);
        }

    }

    public function curl($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'TEST');
        $result = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        return json_decode($result);
    }

    public function uploadImg(Request $request)
    {

        $file = $request->file('file'); // 获取上传的文件
        $type = $request->type;
        if ($file == null) {
            return response()->json(["msg" => "还未上传文件", "status" => 0]);
        }
        if (!in_array($type, [1, 2, 3])) {
            return response()->json(["msg" => "上传类型错误", "status" => 0]);
        }
        // 获取文件后缀
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);
        // 判断文件是否合法
        if (!in_array($extension, array("gif", "GIF", "jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "bmp", "BMP"))) {
            return response()->json(["status" => 0, "msg" => "上传图片不合法"]);
        }
        if ($_FILES['file']['size'] > 5 * 1024 * 1024) {
            return response()->json(["status" => 0, "msg" => "上传图片大小不能超过5M"]);
        }
        $time = date("Ymd", time());
        if ($type == 1) {
            $path_origin = 'files/' . $time . '/' . $this->Member->id;
        } else if ($type == 2) {
            $path_origin = 'idcard/' . $time . '/' . $this->Member->id;
        } else {
            $path_origin = 'recharge/' . $time . '/' . $this->Member->id;
        }
        $res = Storage::disk('uploads')->put($path_origin, $file);
        return response()->json(["status" => 1, "msg" => "上传凭证成功", "data" => "/uploads/" . $res]);
    }

    public function Memberamount(Request $request)
    {

        $UserId = $request->session()->get('UserId');
        $Member = Member::find($UserId);
        echo $Member->amount;
    }


    public function xj_qiandao111(Request $request)
    {

        $UserId = $request->session()->get('UserId');
        $EditMember = DB::table("member")->where("id", $UserId)->first();
        $Member = Member::find($UserId);
        if ($EditMember) {
            if ($EditMember->qd_count != 7) {

                if ($EditMember->lastqiandao >= Carbon::today()->toDateTimeString()) {
                    return response()->json(["status" => 0, "msg" => '今日已经签到过了']);
                }
                //判断是否连续签到
                if ($EditMember->nextqiandao == Carbon::today()->toDateString()) { //是否连续签到

                    $newqd_count = $EditMember->qd_count + 1; //连续签到+1
                    $lx_qd = $EditMember->lx_qd + 1; //连续签到+1

                } else {  //断签重置
                    $newqd_count = 1; //断签,重置签到第一天
                    $lx_qd = 1;

                }

                //////////////////////////////////////////////////
                $data = [
                    "qd_count" => $newqd_count,
                    "lx_qd" => $lx_qd,
                    "lastqiandao" => Carbon::now(),
                    "nextqiandao" => Carbon::tomorrow()->toDateString(),  //第二天签到时间
                ];


                $res = DB::table("member")->where(['id' => $UserId])->update($data);

                if ($res) {
                    $signin_data = DB::table("signinlist")->select('id', 'num', 'type', 'detail')->where("id", $newqd_count)->first();
                    $msg_str = $signin_data->detail;
                }
                if ($signin_data->type == 2) {
                    $yuan = $Member->amount;
                    $Member->increment('amount', (int)$signin_data->num);
                    $huo = $Member->amount;
                    $log = [
                        "userid" => $UserId,
                        "username" => $Member->username,
                        "money" => $signin_data->num,
                        "notice" => $msg_str,
                        "type" => "每日签到",
                        "status" => "+",
                        "yuanamount" => $yuan,
                        "houamount" => $huo,
                        "ip" => \Request::getClientIp(),
                    ];

                    \App\Moneylog::AddLog($log);
                } else {
                    $yuan = $Member->amount;
                    $Member->increment('score', (int)$signin_data->num);
                    $huo = $Member->amount;
                    $log = [
                        "userid" => $UserId,
                        "username" => $Member->username,
                        "money" => $signin_data->num,
                        "notice" => $msg_str,
                        "type" => "每日签到",
                        "status" => "+",
                        "yuanamount" => $yuan,
                        "houamount" => $huo,
                        "ip" => \Request::getClientIp(),
                    ];

                    \App\Moneylog::AddLog($log);
                }


                return response()->json(["status" => 1, "msg" => $msg_str]);

            } else {
                $newqd_count = 1; //七天后重置到第一天
                if (Carbon::now()->toDateString() == $EditMember->nextqiandao) {
                    $lx_qd = $EditMember->lx_qd + 1; //连续签到+1
                } else {
                    $lx_qd = 1;
                }

                $data = [
                    "qd_count" => $newqd_count,
                    "lx_qd" => $lx_qd,
                    "lastqiandao" => Carbon::now(),
                    "nextqiandao" => Carbon::tomorrow()->toDateString(),  //第二天签到时间
                ];
                $res = DB::table("member")->where(['id' => $UserId])->update($data);

                if ($res) {
                    $signin_data = DB::table("signinlist")->select('id', 'num', 'type', 'detail')->where("id", $newqd_count)->first();
                    $msg_str = $signin_data->detail;
                }
                if ($signin_data->type == 2) {
                    $yuan = $Member->amount;
                    $Member->increment('amount', (int)$signin_data->num);
                    $huo = $Member->amount;

                    $log = [
                        "userid" => $UserId,
                        "username" => $Member->username,
                        "money" => $signin_data->num,
                        "notice" => $msg_str,
                        "type" => "每日签到",
                        "status" => "+",
                        "yuanamount" => $yuan,
                        "houamount" => $huo,
                        "ip" => \Request::getClientIp(),
                    ];

                    \App\Moneylog::AddLog($log);
                } else {
                    $yuan = $Member->amount;
                    $Member->increment('score', (int)$signin_data->num);
                    $huo = $Member->amount;

                    $log = [
                        "userid" => $UserId,
                        "username" => $Member->username,
                        "money" => $signin_data->num,
                        "notice" => $msg_str,
                        "type" => "每日签到",
                        "status" => "+",
                        "yuanamount" => $yuan,
                        "houamount" => $huo,
                        "ip" => \Request::getClientIp(),
                    ];

                    \App\Moneylog::AddLog($log);
                }


            }

            return ["status" => 0, "msg" => '活动已结束'];


        }


    }


    public function xj_qiandao(Request $request)
    {

        $UserId = $request->session()->get('UserId');
        $EditMember = DB::table("member")->where("id", $UserId)->first();
        $Member = Member::find($UserId);
        $lx_qdset = DB::table("setings")->where('keyname', 'lx_qd')->value('value');

        if ($EditMember) {
            $glevelinfo = DB::table("membergrouplevel")->find($Member->glevel);

            if ($EditMember->qd_count != 7) {

                if ($EditMember->lastqiandao >= Carbon::today()->toDateTimeString()) {
                    return response()->json(["status" => 0, "msg" => '今日已经签到过了']);
                }
                /* if(!empty($glevelinfo)){
                        $huo11 = $Member->amount;
                        $Member->increment('amount',(int)$glevelinfo->sign_coin);

                        $log=[
                            "userid"=>$UserId,
                            "username"=>$Member->username,
                            "money"=>$glevelinfo->sign_coin,
                            "notice"=>$glevelinfo->name."团队等级签到",
                            "type"=>"团队每日签到",
                            "status"=>"+",
                            "yuanamount"=>$huo11,
                            "houamount"=>$Member->amount,
                            "ip"=>\Request::getClientIp(),
                        ];

                        \App\Moneylog::AddLog($log);
                    }*/
                //判断是否连续签到
                /*if($EditMember->nextqiandao == Carbon::today()->toDateString()){ //是否连续签到

                        $newqd_count = $EditMember->qd_count + 1; //连续签到+1

                    }else{  //断签重置
                        $newqd_count = 1; //断签,重置签到第一天

                    }*/
                //判断是否连续签到
                if ($EditMember->nextqiandao == Carbon::today()->toDateString()) { //是否连续签到

                    $newqd_count = $EditMember->qd_count + 1; //连续签到+1
                    $lx_qd = $EditMember->lx_qd + 1; //连续签到+1

                } else {  //断签重置
                    $newqd_count = 1; //断签,重置签到第一天
                    $lx_qd = 1;

                }

                $data = [
                    "qd_count" => $newqd_count,
                    "lx_qd" => $lx_qd,
                    "lastqiandao" => Carbon::now(),
                    "nextqiandao" => Carbon::tomorrow()->toDateString(),  //第二天签到时间
                ];

                $res = DB::table("member")->where(['id' => $UserId])->update($data);

                if ($res) {
                    $signin_data = DB::table("signinlist")->select('id', 'num', 'type', 'detail')->where("id", $newqd_count)->first();
                    $msg_str = $signin_data->detail;
                }
                if ($signin_data->type == 2) {
                    $yuan = $Member->amount;
                    $Member->increment('amount', (int)$signin_data->num);
                    $huo = $Member->amount;
                    $log = [
                        "userid" => $UserId,
                        "username" => $Member->username,
                        "money" => $signin_data->num,
                        "notice" => $msg_str,
                        "type" => "每日签到",
                        "status" => "+",
                        "yuanamount" => $yuan,
                        "houamount" => $huo,
                        "ip" => \Request::getClientIp(),
                    ];

                    \App\Moneylog::AddLog($log);
                } else {
                    $yuan = $Member->score;
                    $Member->increment('score', (int)$signin_data->num);
                    $huo = $Member->score;
                    $log = [
                        "userid" => $UserId,
                        "username" => $Member->username,
                        "money" => $signin_data->num,
                        "notice" => $msg_str,
                        "type" => "每日签到",
                        "status" => "+",
                        "yuanamount" => $yuan,
                        "houamount" => $huo,
                        "ip" => \Request::getClientIp(),
                    ];

                    \App\Moneylog::AddLog($log);
                }
                $Member1 = Member::find($UserId);
                if ($Member1->lx_qd >= (int)$lx_qdset) {
                    $Member1->increment("lqtree_num", 1);
                    $Member1->decrement("lx_qd", (int)$lx_qdset);
                }
                return response()->json(["status" => 1, "msg" => $msg_str]);

            } else {
                if ($EditMember->lastqiandao >= Carbon::today()->toDateTimeString()) {
                    return response()->json(["status" => 0, "msg" => '今日已经签到过了']);
                }
                /* if(!empty($glevelinfo)){
                        $huo11 = $Member->amount;
                        $Member->increment('amount',(int)$glevelinfo->sign_coin);

                        $log=[
                            "userid"=>$UserId,
                            "username"=>$Member->username,
                            "money"=>$glevelinfo->sign_coin,
                            "notice"=>$glevelinfo->name."团队等级签到",
                            "type"=>"团队每日签到",
                            "status"=>"+",
                            "yuanamount"=>$huo11,
                            "houamount"=>$Member->amount,
                            "ip"=>\Request::getClientIp(),
                        ];

                        \App\Moneylog::AddLog($log);
                    }*/
                $newqd_count = 1; //七天后重置到第一天
                if (Carbon::now()->toDateString() == $EditMember->nextqiandao) {
                    $lx_qd = $EditMember->lx_qd + 1; //连续签到+1
                } else {
                    $lx_qd = 1;
                }
                $data = [
                    "qd_count" => $newqd_count,
                    "lx_qd" => $lx_qd,
                    "lastqiandao" => Carbon::now(),
                    "nextqiandao" => Carbon::tomorrow()->toDateString(),  //第二天签到时间
                ];

                $res = DB::table("member")->where(['id' => $UserId])->update($data);

                if ($res) {
                    $signin_data = DB::table("signinlist")->select('id', 'num', 'type', 'detail')->where("id", $newqd_count)->first();
                    $msg_str = $signin_data->detail;
                }
                if ($signin_data->type == 2) {
                    $yuan = $Member->amount;
                    $Member->increment('amount', (int)$signin_data->num);
                    $huo = $Member->amount;

                    $log = [
                        "userid" => $UserId,
                        "username" => $Member->username,
                        "money" => $signin_data->num,
                        "notice" => $msg_str,
                        "type" => "每日签到",
                        "status" => "+",
                        "yuanamount" => $yuan,
                        "houamount" => $huo,
                        "ip" => \Request::getClientIp(),
                    ];

                    \App\Moneylog::AddLog($log);
                } else {
                    $yuan = $Member->amount;
                    $Member->increment('score', (int)$signin_data->num);
                    $huo = $Member->amount;

                    $log = [
                        "userid" => $UserId,
                        "username" => $Member->username,
                        "money" => $signin_data->num,
                        "notice" => $msg_str,
                        "type" => "每日签到",
                        "status" => "+",
                        "yuanamount" => $yuan,
                        "houamount" => $huo,
                        "ip" => \Request::getClientIp(),
                    ];

                    \App\Moneylog::AddLog($log);
                }
                $Member1 = Member::find($UserId);
                if ($Member1->lx_qd >= (int)$lx_qdset) {
                    $Member1->increment("lqtree_num", 1);
                    $Member1->decrement("lx_qd", (int)$lx_qdset);
                }
                return response()->json(["status" => 1, "msg" => $msg_str]);
            }

            return ["status" => 0, "msg" => '活动已结束'];


        }


    }

    public function newqiandao(Request $request)
    {


        $UserId = $request->session()->get('UserId');
        $sum = DB::table('member')->where(['top_uid' => $UserId])->count();
        $sum1 = DB::table('productbuy')->where(['userid' => $UserId, 'reason' => ''])->count();
        if ($sum > 4 || $sum1 > 0) {
            $data['id'] = $UserId;
            $data['qd_count'] = $this->Member->qd_count;
            // $count = $this->Member->qd_count;
            // $data['data'] = DB::table("signinlist")->select('id','num')->get();
            return response()->json(['status' => 1, 'data' => $data]);
        } else {
            return response()->json(["status" => 0, "msg" => '未开启签到功能，请邀请5人或购买任何项目开启签到功能']);
        }

    }


    /***会员签到***/
    public function qiandao(Request $request)
    {

        // if($this->Member->activation==0){
        //     return ["status"=>1,"msg"=>"帐号尚未激活,请先充值激活帐号"];
        // }
        $UserId = $request->session()->get('UserId');
        $moneys = Cache::get("qiandao");
        //$moneys= Cache::get("QianDaoBfb");
        $content = $notice = "今日已签到";

        $qiandaotime = strtotime($this->Member->lastqiandao);
        if ($qiandaotime < strtotime(date("Y-m-d", time()))) {
            //云币
            $yunbi_info = DB::table('products')->where('title', '云币')->first();
            $yunbi_id = $yunbi_info->id;
            //签到赠送的云币
            $qiandao_yunbi = DB::table('setings')->where(['keyname' => 'qiandao'])->value('value');


            $content = $notice = "签到获得" . $qiandao_yunbi . "个云币";
            //站内消息
            $msg = [
                "userid" => $this->Member->id,
                "username" => $this->Member->username,
                "title" => "今日签到",
                "content" => $content,
                "from_name" => "系统通知",
                "types" => "每日签到",
            ];
            \App\Membermsg::Send($msg);


            //   $MOamount=$this->Member->amount;

            $this->Member->lastqiandao = Carbon::now();
            $this->Member->save();

            $user_currencys_info = DB::table('membercurrencys')->where(['userid' => $UserId, 'productid' => $yunbi_id])->first();
            if ($user_currencys_info) {

                $update_currencys['updated_at'] = Carbon::now();
                DB::table('membercurrencys')->where(['userid' => $UserId, 'productid' => $yunbi_id])->increment('num', $qiandao_yunbi);
                DB::table('membercurrencys')->where(['userid' => $UserId, 'productid' => $yunbi_id])->increment('total_num', $qiandao_yunbi);
                $yuanamount = $user_currencys_info->num;
                $houamount = $user_currencys_info->num + $qiandao_yunbi;
            } else {
                $currencys['userid'] = $UserId;
                $currencys['productid'] = $yunbi_id;
                $currencys['num'] = $qiandao_yunbi;
                $currencys['total_num'] = $qiandao_yunbi;
                $currencys['created_at'] = Carbon::now();
                $currencys['updated_at'] = Carbon::now();
                $currencys_res = DB::table('membercurrencys')->insert($currencys);
                $yuanamount = 0;
                $houamount = $qiandao_yunbi;
            }

            $log = [
                "userid" => $UserId,
                "username" => $this->Member->username,
                "money" => $qiandao_yunbi,
                "notice" => $notice,
                "type" => "每日签到",
                "status" => "+",

                "yuanamount" => $yuanamount,
                "houamount" => $houamount,
                "ip" => \Request::getClientIp(),
            ];

            \App\Moneylog::AddLog($log);
            return ["status" => 1, "msg" => '签到成功'];
        }

        return ["status" => 0, "msg" => '今日已经签到过了'];
        //return view($this->Template.".user.memberlogs");


    }


    public function QrCodeBg(Request $request)
    {

        header("Content-type: image/jpeg");
        $logo = public_path('uploads/' . Cache::get("erweimalogo"));
        $QrCode = QrCode::encoding('UTF-8')->format('png')
            ->size(500)
            ->margin(1)
            ->errorCorrection('H')
            ->merge($logo, .3, true)
            ->generate(Cache::get('AppDownloadUrl'), public_path('uploads/ewm.png'));

        $file = public_path('uploads/' . Cache::get("APPErwmbj"));

        $file = 'uploads/' . Cache::get("APPErwmbj");

        $img = Image::make($file)
            ->insert(public_path('uploads/ewm.png'), 'bottom-right', 115, 160)
            ->resize(750, 1200);


        $title = Cache::get("codetitle");
        $img->text($title, 100, 430, function ($font) {
            $font->file(public_path('uploads/font/PingFang.ttc'));
            $font->size(60);
            $font->color('#ff0000');
        });

        $invicode = "推广ID:" . $this->Member->invicode;
        $img->text($invicode, 260, 1150, function ($font) {
            $font->file(public_path('uploads/font/msyhbd.ttf'));
            $font->size(40);
            $font->color('#ff0000');
        });


        return $img->response('jpg');


    }


    /***大转盘游戏***/
    public function lotterys(Request $request)
    {


        if ($request->ajax()) {
            $UserId = $request->session()->get('UserId');


            $pagesize = 6;
            $pagesize = Cache::get("pcpagesize");
            $where = [];

            $list = DB::table("products")
                ->orderBy("sort", "desc")
                ->paginate($pagesize);
            foreach ($list as $item) {
                $item->date = date("m-d H:i", strtotime($item->created_at));
                $item->url = route("product", ["id" => $item->id]);
            }

            return ["status" => 0, "list" => $list, "pagesize" => $pagesize];
        } else {

            return view($this->Template . ".user.lotterys");
        }


    }

    //提交身份认证
    public function authentication(Request $request)
    {
        $EditMember = Member::where("id", $this->Member->id)->first();
        $card_up = $request->post('card_up','');
        $card_down = $request->post('card_down','');
        $id_card = $request->post('id_card','');
        $name = $request->post('name','');
        if ($EditMember) {
            $data = [];
            $checkSM = DB::table("memberidentity")->select('realname', 'idnumber', 'status')->where(['userid' => $EditMember->id])->first();
            if ($checkSM && $checkSM->status == 0) {
                return response()->json(["status" => 0, "msg" => "信息正在审核中"]);

            }

            if ($checkSM && $checkSM->status == 1) {
                return response()->json(["status" => 0, "msg" => "已通过认证，如有疑问请联系客服人员"]);
            }

            if (empty($card_up) || empty($card_down) || empty($id_card) || empty($name)) {
                return response()->json(["status" => 0, "msg" => "信息不能为空"]);
            }
            $data['userid'] = $EditMember->id;
            $data['status'] = 1;
            $data['created_at'] = Carbon::now();

            if (strlen(trim($name)) < 1 || strlen(trim($name)) >= 10) {
                return response()->json(["status" => 0, "msg" => "姓名长度错误"]);
            }
            $data['realname'] = urldecode($name);

            if (strlen(trim($id_card)) < 5 || strlen(trim($id_card)) >= 20) {
                return response()->json(["status" => 0, "msg" => "身份证输入错误"]);
            }
            $data['idnumber'] = $id_card;
            $data['facade_img'] = urldecode($card_up);
            $data['revolt_img'] = urldecode($card_down);
            if (count($data) > 0) {
                $res = DB::table("memberidentity")->insert($data);
                if ($res) {
                    $EditMember->realname = urldecode($name);
                    $EditMember->card = $id_card;
                    $EditMember->is_auth = 1;
                    $EditMember->save();
                    return response()->json(["status" => 1, "msg" => "提交成功"]);
                } else {
                    return response()->json(["status" => 0, "msg" => "提交失败"]);
                }
            } else {
                return response()->json(["status" => 0, "msg" => "当前无修改项"]);
            }

        }
    }

    //是否身份认证过
    public function is_check(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();

        if ($EditMember) {
            $data = '';
            if ($EditMember->realname != '' && $EditMember->card != '' && $EditMember->address != '') {
                $data = [
                    "realname" => $EditMember->realname,
                    "card" => $EditMember->card,
                    "address" => $EditMember->address,
                ];
            }
            return response()->json(["status" => 1, "msg" => "返回成功", "data" => $data]);
        }
    }

    //云商户页面
    public function cloud_merchants(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $pagesize = 10;

        $user_info = Member::select('id as cloudchat', 'nickname', 'picImg', 'yshlevel', 'cloudchat as id', 'introduction')->where("id", $UserId)->first()->toArray();

        if ($user_info) {
            $data = Member::select('id as cloudchat', 'nickname', 'picImg', 'yshlevel', 'cloudchat as id', 'introduction')
                ->where(['is_ysh' => 1])->where('id', '<>', $UserId)
                ->orderBy("yshlevel", "desc")
                // ->orderBy("id","desc")
                ->paginate($pagesize)->toArray();
            // dump($data);
            // if($request->page == 1){
            //     array_unshift($data['data'],$user_info);
            // }


            return response()->json(["status" => 1, "msg" => "返回成功", "data" => $data]);
        }
    }

    //投诉
    public function complaint(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $msg = $request->msg;//原因
        $img = $request->img;//举报图
        $mobile = $request->mobile;//手机号

        if ($msg == '') {
            return response()->json(["status" => 0, "msg" => "内容不能为空"]);
        }
        if ($img != '') {
            $img_list = explode(',', $img);
            foreach ($img_list as $v) {
                $temp = explode(".", $v);
                $extension = end($temp);
                // 判断文件是否合法
                if (!in_array($extension, array("gif", "GIF", "jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "bmp", "BMP"))) {
                    return response()->json(["status" => 0, "msg" => "上传图片不合法"]);
                }
            }
            if (count($img_list) > 3) {
                return response()->json(["status" => 0, "msg" => "上传的图片不能超过三张"]);
            }
        }

        $insert['uid'] = $UserId;
        $insert['msg'] = $msg;
        $insert['img'] = $img;
        $insert['mobile'] = $mobile;
        $insert['created_at'] = $insert['updated_at'] = Carbon::now();
        $res = DB::table('onlinemsg')->insert($insert);
        if ($res) {
            return response()->json(["status" => 1, "msg" => "提交成功"]);
        } else {
            return response()->json(["status" => 0, "msg" => "提交失败"]);
        }
    }

    //云聊添加步骤
    public function yltjbz(Request $request)
    {
        $res['yltjbz1'] = DB::table('setings')->where(['keyname' => 'yltjbz1'])->first();
        $res['yltjbz2'] = DB::table('setings')->where(['keyname' => 'yltjbz2'])->first();
        $res['yltjbz3'] = DB::table('setings')->where(['keyname' => 'yltjbz3'])->first();
        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $res]);
    }

    //云聊管理员ID
    public function get_ylglyid(Request $request)
    {
        $res['ylglyid'] = DB::table('setings')->where(['keyname' => 'ylglyid'])->first();
        // $res['picImg'] = DB::table('member')->where('id',$res['ylglyid']->id)->value('picImg');
        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $res]);
    }

    //云商简介
    public function ysjj()
    {
        $res['ysjj'] = DB::table('setings')->where(['keyname' => 'ysjj'])->first();
        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $res]);
    }

    //云货币互转-我的货币
    public function ybhz(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $membercurrencys = DB::table('membercurrencys as m')
            ->join('products as p', 'p.id', '=', 'm.productid')
            ->select('m.userid', 'm.productid', 'm.num', 'p.title', 'p.increase', 'p.market_value', 'p.qtje')
            ->where(['m.userid' => $UserId])
            ->orderBy('m.created_at', 'desc')
            ->get();
        foreach ($membercurrencys as $v) {
            // $v->sum_market_value = number_format($v->num * $v->market_value, 2);
            $v->sum_market_value = number_format($v->num * $v->qtje, 2);
        }
        $fee = DB::table('setings')->where('keyname', 'yunbi_fee')->value('value');//云币手续费
        return response()->json(["status" => 1, "msg" => "返回成功", "data" => ['list' => $membercurrencys, 'fee' => $fee]]);
    }

    //货币-收款列表
    public function collection_list(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        // $membercurrencys = DB::table('products as p')
        //     ->join('membercurrencys as m', 'p.id', '=', 'm.productid')
        //     ->select('m.userid','m.productid','m.num','p.title','p.increase','p.market_value','p.qtje')
        //     ->orwhere(['p.category_id'=>11])
        //     // ->orderBy('m.created_at','desc')
        //     ->get();

        $membercurrencys = DB::table('products as p')
            // ->leftjoin('membercurrencys as m',['p.id'=>'m.productid'] )
            // ->select('m.userid','m.productid','m.num','p.title','p.increase','p.market_value','p.qtje')
            ->select('p.id', 'p.title', 'p.increase', 'p.market_value', 'p.qtje')
            ->where(['p.category_id' => 11])
            ->get();

        $totalAmount = 0; //总资产


        foreach ($membercurrencys as $v) {

            $randstr = $this->get_random_code(20);
            $rand_arr = explode('O', $randstr);

            $product_num = str_split($v->id, 1);//每个数字
            $user_num = str_split($UserId, 1);//每个数字
            $rand = rand(0, 3);
            foreach ($product_num as $a => $b) {
                $rand_arr[0] = substr_replace($rand_arr[0], $b, $a + $rand + ($a * 2), 1);
            }

            foreach ($user_num as $a => $b) {
                $rand_arr[1] = substr_replace($rand_arr[1], $b, $a + $rand + ($a * 2), 1);
            }
            $v->curr_address = $rand_arr[0] . 'O' . $rand_arr[1];   //收币地址  明文版
            $has_currencys = DB::table('membercurrencys')->where(['userid' => $UserId, 'productid' => $v->id])->first();
            if ($has_currencys) {
                $v->num = $has_currencys->num;
            } else {
                $v->num = 0;
            }
            // $v->num = $v->num?$v->num:0;
            $totalAmount += $v->num * $v->qtje;
        }

        return response()->json(["status" => 1, "msg" => "返回成功", "data" => ['list' => $membercurrencys, 'totalAmount' => sprintf("%.2f", $totalAmount)]]);
    }

    //货币转出
    public function transfer_out(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        if (!isset($request->link) || !isset($request->num) || !isset($request->productid) || !isset($request->fee)) {
            return response()->json(["status" => 0, "msg" => "参数不能为空"]);
        }
        $link = $request->link;//链接
        $num = $request->num;//数量
        $link_info = explode('O', $link);
        $huobi_id = $this->findNum($link_info[0]);//货币id
        $to_userid = $this->findNum($link_info[1]);//转入人

        $fee_num = DB::table('setings')->where('keyname', 'yunbi_fee')->value('value');//云币手续费
        $yunbi_info = DB::table('products')->where(['title' => '云币'])->first();//云货币信息
        $yunbi_id = $yunbi_info->id;

        if ($UserId == $to_userid) {
            return response()->json(["status" => 0, "msg" => "不可转给自己"]);
        }
        if ($request->productid != $huobi_id) {
            return response()->json(["status" => 0, "msg" => "币种不同,不可互转"]);
        }
        if ($request->fee != $fee_num) {
            return response()->json(["status" => 0, "msg" => "手续费有误"]);
        }
        //货币信息
        $products_info = DB::table('products')->where(['id' => $huobi_id])->first();
        if (!$products_info) {
            return response()->json(["status" => 0, "msg" => "货币信息异常"]);
        }
        $my_info = DB::table('member')->where(['id' => $UserId, 'state' => 1])->first();
        if (!$my_info) {
            return response()->json(["status" => 0, "msg" => "不可转币给此云商户"]);
        }

        //我是否有该货币
        $currencys_info = DB::table('membercurrencys')->where(['userid' => $UserId, 'productid' => $huobi_id])->orderBy('created_at', 'desc')->first();
        if (!$currencys_info) {
            return response()->json(["status" => 0, "msg" => "您没有该货币信息"]);
        }
        if ($currencys_info->num < $num || $num < 0) {
            return response()->json(["status" => 0, "msg" => "您货币个数不足"]);
        }
        //我的云币信息
        $yunbi_currencys_info = DB::table('membercurrencys')->where(['userid' => $UserId, 'productid' => $yunbi_id])->orderBy('created_at', 'desc')->first();
        if (!$yunbi_currencys_info) {
            return response()->json(["status" => 0, "msg" => "您还没有云币，请先去购买"]);
        }
        if ($yunbi_currencys_info->num < $fee_num) {
            return response()->json(["status" => 0, "msg" => "交易需要手续费" . $fee_num . "个云币，您云币个数不足，请先购买"]);
        }
        //如果是云币转币
        if ($huobi_id == $yunbi_id && $yunbi_currencys_info->num < ($fee_num + $num)) {
            return response()->json(["status" => 0, "msg" => "交易需要手续费" . $fee_num . "个云币，您云币个数不足，请先购买"]);
        }


        $yuanamount = $yunbi_currencys_info->num;
        //转入人信息
        $to_userid_info = DB::table('member')->where(['id' => $to_userid, 'state' => 1])->first();
        if (!$to_userid_info) {
            return response()->json(["status" => 0, "msg" => "转入人信息错误"]);
        }

        //我的货币
        DB::table('membercurrencys')->where(['userid' => $UserId, 'productid' => $huobi_id])->decrement('num', $num);
        DB::table('membercurrencys')->where(['userid' => $UserId, 'productid' => $yunbi_id])->decrement('num', $fee_num);
        $yunbi_houamount = $yuanamount - $fee_num;
        $huobi_houamount = $currencys_info->num - $num;
        //转入人
        $to_userid_huobi_info = DB::table('membercurrencys')->where(['userid' => $to_userid, 'productid' => $huobi_id])->first();

        if ($to_userid_huobi_info) {
            DB::table('membercurrencys')->where(['userid' => $to_userid, 'productid' => $huobi_id])->increment('num', $num);
            DB::table('membercurrencys')->where(['userid' => $to_userid, 'productid' => $huobi_id])->increment('total_num', $num);
            $to_userid_huobi_yuanamount = $to_userid_huobi_info->num;
            $to_userid_huobi_houamount = $to_userid_huobi_info->num + $num;
        } else {
            $membercurrencys['userid'] = $to_userid;
            $membercurrencys['productid'] = $huobi_id;
            $membercurrencys['num'] = $num;
            $membercurrencys['total_num'] = $num;
            $membercurrencys['created_at'] = $membercurrencys['updated_at'] = Carbon::now();
            DB::table('membercurrencys')->insert($membercurrencys);
            $to_userid_huobi_yuanamount = $num;
            $to_userid_huobi_houamount = $num;
        }

        //转出 - log表
        $from_currencyslog['order'] = time();
        $from_currencyslog['from_userid'] = $UserId;
        $from_currencyslog['to_userid'] = $to_userid;
        $from_currencyslog['productid'] = $huobi_id;
        $from_currencyslog['num'] = $num;
        $from_currencyslog['fee'] = $fee_num;
        $from_currencyslog['price'] = $products_info->qtje;
        $from_currencyslog['fee_price'] = $yunbi_info->qtje;//当时云币价格
        $from_currencyslog['created_at'] = $currencyslog['updated_at'] = Carbon::now();
        $currlog_id = DB::table('currencyslog')->insertGetId($from_currencyslog);
        //货币转出log
        $zhuanchu_log = [
            "userid" => $UserId,
            "username" => $my_info->username,
            "money" => $num,
            "notice" => '货币转出',
            "type" => "货币转出",
            "status" => "-",
            "yuanamount" => $currencys_info->num,
            "houamount" => $huobi_houamount,
            "ip" => \Request::getClientIp(),
            "currlog_id" => $currlog_id,
            "product_id" => $huobi_id,
        ];
        \App\Moneylog::AddLog($zhuanchu_log);

        //手续费log
        $fee_log = [
            "userid" => $UserId,
            "username" => $my_info->username,
            "money" => $fee_num,
            "notice" => '云币转出手续费',
            "type" => "手续费",
            "status" => "-",
            "yuanamount" => $yuanamount,
            "houamount" => $yunbi_houamount,
            "ip" => \Request::getClientIp(),
            "currlog_id" => $currlog_id,
            "product_id" => $yunbi_id,
        ];
        \App\Moneylog::AddLog($fee_log);

        //货币转入log
        $zhuanru_log = [
            "userid" => $to_userid,
            "username" => $to_userid_info->username,
            "money" => $num,
            "notice" => '货币转入',
            "type" => "货币转入",
            "status" => "+",
            "yuanamount" => $to_userid_huobi_yuanamount,
            "houamount" => $to_userid_huobi_houamount,
            "ip" => \Request::getClientIp(),
            "currlog_id" => $currlog_id,
            "product_id" => $huobi_id,
        ];
        \App\Moneylog::AddLog($zhuanru_log);

        return response()->json(["status" => 1, "msg" => "转出成功"]);
    }


    function get_random_code($num)
    {
        // $codeSeeds = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
        // $codeSeeds .= "abcdefghijklmnpqrstuvwxyz";
        // $codeSeeds .= "0123456789_";
        $codeSeeds = "1234567890";
        $len = strlen($codeSeeds);
        $ban_num = ($num / 2) - 3;
        $code = "";
        for ($i = 0; $i < $num; $i++) {
            $rand = rand(0, $len - 1);
            if ($i == $ban_num) {
                $code .= 'O';
            } else {
                $code .= $codeSeeds[$rand];
            }
        }
        return $code;
    }

    public function findNum($str = '')
    {
        $str = trim($str);
        if (empty($str)) {
            return '';
        }
        $result = '';

        for ($i = 0; $i < strlen($str); $i++) {
            if (is_numeric($str[$i])) {
                $result .= $str[$i];
            }
        }
        return $result;
    }

    //系统收款账户
    public function getSystemAccount(Request $request)
    {
        $type = $request->get('type', 'ChinaPay');
        $res = DB::table('payment')->where(['pay_code' => $type])->first();
        if ($res) {
            return response()->json(["status" => 1, "msg" => "返回成功", "data" => $res]);
        }
    }

    //转账
    public function transfer_accounts(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $payee = $request->get('payee');//收款人
        $amount = $request->get('amount');//转账金额
        $paypwd = $request->get('paypwd');//
        if (!is_numeric($payee) || $amount == '' || $amount <= 0) {
            return response()->json(["status" => 1, "msg" => "参数错误"]);
        }

        $payee_info = DB::table('member')->where(['id' => $payee, 'state' => 1])->first();
        if (!$payee_info) {
            return response()->json(["status" => 0, "msg" => "收款人信息错误"]);
        }
        $my_info = DB::table('member')->where(['id' => $UserId, 'state' => 1])->first();
        if (!$my_info) {
            return response()->json(["status" => 0, "msg" => "当前账号异常，无法转账"]);
        }
        $Member_paypwd = \App\Member::DecryptPassWord($my_info->paypwd);
        if ($Member_paypwd != $paypwd) {
            return response()->json(["status" => 0, "msg" => "支付密码错误！"]);
        }
        if ($my_info->amount < $amount) {
            return response()->json(["status" => 0, "msg" => "当前账户余额不足，无法转账"]);
        }

        //两边moneylog
        $my_yuanamount = $my_info->amount;
        DB::table('member')->where(['id' => $UserId])->decrement('amount', $amount);
        $log = [
            "userid" => $UserId,
            "username" => $my_info->username,
            "money" => $amount,
            "notice" => '扫码转出' . $amount . "(" . $payee_info->username . ")" . "(" . $payee . ")",
            "type" => "余额互转",
            "status" => "-",
            "yuanamount" => $my_yuanamount,
            "houamount" => DB::table('member')->where(['id' => $UserId])->value('amount'),
            "ip" => \Request::getClientIp(),
            "category_id" => '',
            "product_id" => '',
            "product_title" => '',
        ];
        \App\Moneylog::AddLog($log);

        $payee_yuanamount = $payee_info->amount;
        DB::table('member')->where(['id' => $payee])->increment('amount', $amount);
        $log = [
            "userid" => $payee,
            "username" => $payee_info->username,
            "money" => $amount,
            "notice" => '二维码收款' . $amount . "(" . $my_info->username . ")" . "(" . $UserId . ")",
            "type" => "余额互转",
            "status" => "+",
            "yuanamount" => $payee_yuanamount,
            "houamount" => DB::table('member')->where(['id' => $payee])->value('amount'),
            "ip" => \Request::getClientIp(),
            "category_id" => '',
            "product_id" => '',
            "product_title" => '',
        ];
        \App\Moneylog::AddLog($log);

        return response()->json(["status" => 1, "msg" => "支付成功"]);
    }

    //转账明细
    public function transfer_details(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $pageSize = $request->get('pageSize', 10);

        $res = DB::table('moneylog')->where(['moneylog_userid' => $UserId, 'moneylog_type' => '余额互转'])->paginate($pageSize);
        return response()->json(["status" => 1, "msg" => "返回成功", 'data' => $res]);
    }

    //我的收款码
    public function my_collection_code(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $ret = '';
//        $userid = isset($_POST['userid']) ? intval($_POST['userid']) : 0;
        if ($UserId) {
            $filename = '/upload/qrcode/' . $UserId . '.jpg';
            if (!file_exists($filename)) {
                $qrCode = new QrCode(ENV('FILE_URL') . '/h5/pages/login/register?fromid=' . $UserId);
                $qrCode->setSize(300);
                $qrCode->setWriterByName('png');
                $qrCode->setEncoding('UTF-8');
                $qrCode->writeFile(ltrim($filename, '/'));
//                $token = getToken();
//                $body = '{"action_name":"QR_LIMIT_STR_SCENE","action_info":{"scene":{"scene_str":"'. $userid . '"}}}';
//                $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
//                $res = curl($url,$body);
//                $res = json_decode($res,true);
//                $ticket = $res['ticket'];
//                $url = $res['url'];
//                $qrCode = new QrCode($url);
//                $qrCode->setSize(300);
//                $qrCode->setWriterByName('png');
//                $qrCode->setEncoding('UTF-8');
//                $qrCode->writeFile(ltrim($filename, '/'));
            }
            return ENV('FILE_URL') . $filename;
        }
    }

    //获取馈赠股权信息
    public function getGiftEquity()
    {
        $data['gift_equity_lv1'] = DB::table('setings')->where(['keyname' => 'gift_equity_lv1'])->first();
        $data['gift_equity_lv2'] = DB::table('setings')->where(['keyname' => 'gift_equity_lv2'])->first();
        $data['gift_equity_lv3'] = DB::table('setings')->where(['keyname' => 'gift_equity_lv3'])->first();

        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $data]);
    }

    //我的购买记录
    public function buyVipRecord(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $pageSize = $request->get('pageSize', 10);
        $data = DB::table('memberrecharge')
            ->select('ordernumber', 'username', 'amount', 'paymentid', 'status', 'created_at', 'payimg', 'type', 'memo')
            ->where("userid", $UserId)
            //  ->where('type','like','购买等级%')
            ->orderBy("id", "desc")
            ->paginate($pageSize)->toArray();
        // dump($data['data'][0]->ordernumber);exit;
        //0,未处理 1，成功 -1.失败
        foreach ($data['data'] as $k => $v) {
            // $data['data'][$k]->created_at = date('Y-m-d H:i:s',$v->created_at);
            switch ($v->status) {
                case 0:
                    $data['data'][$k]->status_name = '待审核';
                    break;
                case 1:
                    $data['data'][$k]->status_name = '成功';
                    break;
                case -1:
                    $data['data'][$k]->status_name = '失败';
                    break;
            }
        }
        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $data]);
    }

    //联系客服
    // public function customer_service(Request $request){
    //     $UserId =$request->session()->get('UserId');

    //     $res = DB::table('contact')->select('name','value','thumb_url')->where(['status'=>1])->orderBy('sort','asc')->orderBy('created_at','desc')->get();
    //     return response()->json(["status"=>1,"msg"=>"返回成功","data"=>$res]);
    // }

    //全平台 团队排行
    public function tamRanking(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $start_time = DB::table('setings')->where('keyname', 'rank_start_data')->value('value');
        $end_time = DB::table('setings')->where('keyname', 'rank_end_data')->value('value');
        $productbuy_info = DB::table('productbuy')
            ->select('id', 'userid')
            ->where(['status' => 1])
            ->where("updated_at", '>=', $start_time)
            ->where("updated_at", '<=', $end_time)
            ->groupBy('userid')
            ->get()->toArray();
        $user_id_arr = array_column($productbuy_info, 'userid');

        $level_one = DB::table('member')
            ->select(DB::raw('top_uid,count(top_uid) as count1'))
            ->where(['state' => 1])
            ->where('top_uid', '<>', 0)
            ->whereIn('id', $user_id_arr)
            ->groupBy('top_uid')
            ->get()->toArray();

        $level_two = DB::table('member')
            ->select(DB::raw('ttop_uid,count(ttop_uid) as count2'))
            ->where(['state' => 1])
            ->where('ttop_uid', '<>', 0)
            ->whereIn('id', $user_id_arr)
            ->groupBy('ttop_uid')
            ->get()->toArray();
        // dump($level_one);
        // dump($level_two);
        //将二级的数组重组用uid做键值
        $new_level_two = [];
        foreach ($level_two as $k => $v) {
            $new_level_two[$v->ttop_uid]['ttop_uid'] = $v->ttop_uid;
            $new_level_two[$v->ttop_uid]['count2'] = $v->count2;
        }
        // dump($new_level_two);
        //将二级的数量加到一级上
        foreach ($level_one as $k => $v) {
            if (isset($new_level_two[$v->top_uid])) {
                $level_one[$k]->buy_count = $new_level_two[$v->top_uid]['count2'] + $v->count1;
            } else {
                $level_one[$k]->buy_count = $v->count1;
            }
        }
        // dump($level_one);

        //取前10
        // dump(array_column($level_one,'buy_count'));
        $sort_by_buy_count = $this->arraySort($level_one, 'buy_count', SORT_DESC);
        //  dump($a);
        $top_ten = array_slice($sort_by_buy_count, 0, 10, true);

        //我的排名
        $top_ten_uid_arr = array_column($top_ten, 'top_uid');
        $my_info = DB::table('member')->select('id', 'picImg', 'nickname')->where(['id' => $UserId, 'state' => 1])->first();
        $my_info->is_there_me = in_array($UserId, $top_ten_uid_arr) ? 1 : 0;//1有我 0;无我
        $my_info->team_count = DB::table('member')
            ->where(['state' => 1])
            ->where(function ($query) use ($UserId) {
                $query->where('top_uid', $UserId)
                    ->orwhere('ttop_uid', $UserId);
            })->count();
        $one = DB::table('member')
            ->select('id')
            ->where(['state' => 1, 'top_uid' => $UserId])
            ->whereIn('id', $user_id_arr)
            ->count();
        $two = DB::table('member')
            ->select('id')
            ->where(['state' => 1, 'ttop_uid' => $UserId])
            ->whereIn('id', $user_id_arr)
            ->count();
        $my_info->buy_count = $one + $two;//我的有效人数
        if ($my_info->is_there_me == 1) {
            $my_info->rank = array_search($UserId, $top_ten_uid_arr) + 1;
        }

        //获取前10信息 头像  团队人数  有效人数
        foreach ($top_ten as $k => $v) {
            $top_info = DB::table('member')->select('id', 'picImg', 'nickname')->where(['id' => $v->top_uid, 'state' => 1])->first();
            $top_ten[$k]->picImg = $top_info->picImg;
            $top_ten[$k]->nickname = $top_info->nickname;//substr($top_info->nickname,0,1)."...";
            $t_uid = $v->top_uid;
            $top_ten[$k]->team_count = DB::table('member')->where(['state' => 1])->where(function ($query) use ($t_uid) {
                $query->where('top_uid', $t_uid)
                    ->orwhere('ttop_uid', $t_uid);
            })->count();
        }
        // dump($b);

        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $top_ten, 'myinfo' => $my_info]);
    }

    /**
     * 二维数组根据某个字段排序
     * @param array $array 要排序的数组
     * @param string $keys 要排序的键字段
     * @param string $sort 排序类型  SORT_ASC     SORT_DESC
     * @return array 排序后的数组
     */
    public function arraySort($array, $keys, $sort = SORT_DESC)
    {
        $keysValue = [];
        foreach ($array as $k => $v) {

            $keysValue[$k] = $v->$keys;
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }


    //新更新个人资料   realname  card  address  telcode   newpwd1  newpwd2 newpaypwd1  newpaypwd2
    public function set_myinfo(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        if ($request->realname == '' || $request->card == '' || $request->address == '') {
            return response()->json(["status" => 0, "msg" => "个人信息不能为空"]);
        }

        if (strlen(trim($request->card)) < 6 || strlen(trim($request->card)) >= 20) {
            return response()->json(["status" => 0, "msg" => "身份证输入错误"]);
        }

        $checkSM = DB::table("member")->select('realname', 'card')->where(['id' => $UserId])->first();
        if (!empty($checkSM->realname) && !empty($checkSM->card)) {
            if (($checkSM->realname != $request->realname) || ($checkSM->card != $request->card)) {
                return response()->json(["status" => 0, "msg" => "如需更改实名信息请联系客服"]);
            }
        }

        $data = [
            "realname" => $request->realname,
            "card" => $request->card,
            "address" => $request->address,
        ];

        if ($request->telcode == '') {
            return response()->json(["status" => 0, "msg" => "请输入短信验证码"]);
        }
        $mobile = DB::table("member")->where(['id' => $UserId])->value('username');
        $check_time = strtotime("-10 minute");
        $sms_code = DB::table('membersms')
            ->where(['mobile' => $mobile, 'sms_status' => 1, 'type' => 3])
            ->where('create_time', '<=', time())
            ->where('create_time', '>=', $check_time)
            ->orderBy('create_time', 'desc')
            ->first();
        // if($request->telcode != 8597 && (!$sms_code || $sms_code->code != $request->telcode)){
        if (!$sms_code || $sms_code->code != $request->telcode) {
            return response()->json(["status" => 0, "msg" => "短信验证码错误，请重新输入"]);
        }

        if (!empty($request->newpwd1)) {
            if (trim($request->newpwd1) == trim($request->newpwd2)) {
                $data["password"] = \App\Member::EncryptPassWord($request->newpwd1);
                $data["lastsession"] = 0; //修改密码 重新登录
            } else {
                return response()->json(["status" => 0, "msg" => "新登录密码两次不一致"]);
            }
        }

        if (!empty($request->newpaypwd1)) {
            if (trim($request->newpaypwd1) == trim($request->newpaypwd2)) {
                $data["paypwd"] = \App\Member::EncryptPassWord($request->newpaypwd1);
            } else {
                return response()->json(["status" => 0, "msg" => "新支付密码两次不一致"]);
            }
        }


        $res = DB::table("member")->where(['id' => $UserId])->update($data);

        if ($res) {
            return response()->json(["status" => 1, "msg" => "提交成功"]);
        } else {
            return response()->json(["status" => 0, "msg" => "提交失败"]);
        }
    }

    //领取登记  name  mobile  idcard  编号gqorder  address  说明explain
    public function receive(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        if ($request->name == '' || $request->mobile == '' || $request->idcard == '' || $request->gqorder == '' || $request->address == '') {
            return response()->json(["status" => 0, "msg" => "信息内容不能为空"]);
        }

        if (strlen(trim($request->mobile)) != 11) {
            return response()->json(["status" => 0, "msg" => "手机号输入错误"]);
        }

        if (strlen(trim($request->idcard)) < 18 || strlen(trim($request->idcard)) >= 20) {
            return response()->json(["status" => 0, "msg" => "身份证输入错误"]);
        }

        // $rec_info = DB::table('receivelist')->where(['gqorder'=>$request->gqorder,'userid'=>$UserId])->first();
        // if(!empty($rec_info)){
        //     return response()->json(["status"=>0,"msg"=>"该证书编号已登记，请勿重复登记"]);
        // }

        $pro_info = DB::table('productbuy')->select('id', 'userid', 'gq_order')->where(['category_id' => 12, 'status' => 1, 'gq_order' => $request->gqorder, 'userid' => $UserId])->first();
        if (!$pro_info || $pro_info->userid != $UserId) {
            return response()->json(["status" => 0, "msg" => "该证书编号不可用"]);
        }

        $receive_count = DB::table("setings")->where("keyname", 'receive_count')->value('value');//判断是否达到登记要求
        $buy_count = DB::table("productbuy")->where(['category_id' => 12, 'status' => 1, 'gq_order' => $request->gqorder])->sum('num');
        $receive_count = floor($buy_count / $receive_count);//去除小数点
        if ($receive_count > 0) {
            $is_count = DB::table('receivelist')->where(['userid' => $UserId])->count();
            $receive_count = $receive_count - $is_count;
            if ($receive_count <= 0) {
                return response()->json(["status" => 0, "msg" => "当前未达到登记要求"]);
            }
        } else {
            return response()->json(["status" => 0, "msg" => "当前未达到登记要求"]);
        }

        $data = [
            "userid" => $UserId,
            "probuy_id" => $pro_info->userid,
            "name" => $request->name,
            "mobile" => $request->mobile,
            "idcard" => $request->idcard,
            "address" => $request->address,
            "gqorder" => $request->gqorder,
            "explain" => $request->explain,
            "created_at" => Carbon::now(),
        ];

        $res = DB::table("receivelist")->insertGetId($data);

        if ($res) {
            return response()->json(["status" => 1, "msg" => "提交成功"]);
        } else {
            return response()->json(["status" => 0, "msg" => "提交失败"]);
        }
    }

    //领取登记记录
    public function receive_list(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $rec_list = DB::table('receivelist')->where(['userid' => $UserId])->get();

        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $rec_list]);
    }

    //连续签到页面
    public function qd_index(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $EditMember = DB::table("member")->select('id', 'integral', 'qd_count', 'lastqiandao', 'nextqiandao')->where("id", $UserId)->first();

        if ($EditMember) {

            $data['my_integral'] = $EditMember->integral;
            if ($EditMember->qd_count == 7) {
                if ($EditMember->nextqiandao <= Carbon::today()->toDateString()) {
                    $data['now_count'] = 0;   //七天签完后,第二天
                } else {
                    $data['now_count'] = $EditMember->qd_count;  //当前签到次数
                }
            } else {
                if ($EditMember->nextqiandao < Carbon::today()->toDateString()) {
                    $data['now_count'] = 0;   //七天签完后,第二天
                } else {
                    $data['now_count'] = $EditMember->qd_count;  //当前签到次数
                }
            }

            $receive_count = DB::table("setings")->where("keyname", 'receive_count')->value('value');//判断是否达到登记要求
            $buy_count = DB::table("productbuy")->where(['category_id' => 12, 'status' => 1, 'userid' => $UserId])->where('pay_type', '<>', 0)->sum('num');
            $receive_count = floor($buy_count / $receive_count);//去除小数点
            if ($receive_count > 0) {
                $is_count = DB::table('receivelist')->where(['userid' => $UserId])->count();
                $receive_count = $receive_count - $is_count;
            }
            $data['receive_count'] = $receive_count >= 0 ? $receive_count : 0; //可登记次数


            $signin_data = DB::table("signinlist")->select('id', 'num', 'type', 'days')->get();
            foreach ($signin_data as $k => $v) {
                if ($k == 6) {
                    $v->typename = '神秘大礼包';
                } else {
                    $v->typename = $v->num . ($v->type == 1 ? '积分' : '元现金');
                }
                //  unset($v->num);
            }
            $data['signin_list'] = $signin_data;

            return response()->json(["status" => 1, "data" => $data]);
        }
    }

    //新连续签到
    public function lxqd(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $EditMember = DB::table("member")->select('id', 'qd_count', 'lastqiandao', 'nextqiandao', 'luckdraws', 'activation')->where("id", $UserId)->first();
        $activation = $EditMember->activation;
        if ($activation == 0) {
            return response()->json(["status" => 0, "msg" => '未开启签到功能，请邀请5人或购买任何项目开启签到功能']);
        }
        $a = 0;
        if ($EditMember) {

            if ($EditMember->lastqiandao >= Carbon::today()->toDateTimeString()) {
                return response()->json(["status" => 0, "msg" => "今日已签过到了"]);
            }
            $luckdrawsed = $EditMember->luckdraws;
            if ($EditMember->nextqiandao == Carbon::today()->toDateString()) { //是否连续签到

                $newqd_count = $EditMember->qd_count + 1; //连续签到+1
                if ($newqd_count % 3 == 0) {
                    $luckdraws = $luckdrawsed + 1; //已连续签到7天,重置签到第一天
                    $a = 1;
                }

            } else {  //断签重置
                $newqd_count = 1; //断签,重置签到第一天

            }


            $data = [
                "qd_count" => $newqd_count,
                "luckdraws" => isset($luckdraws) ? $luckdraws : $luckdrawsed,
                "lastqiandao" => Carbon::now(),
                "nextqiandao" => Carbon::tomorrow()->toDateString(),  //第二天签到时间
            ];

            $res = DB::table("member")->where(['id' => $UserId])->update($data);

            if ($res) {
                //发放签到奖励

                if ($a == 1) {
                    $msg_str = '连续签到' . $newqd_count . '天' . '成功，获得一张抽奖卷';
                } else {
                    $msg_str = '签到成功';
                }

                $Member = Member::find($UserId);
                $yuan = $Member->amount;
                $hou = $Member->amount;

                $log = [
                    "userid" => $Member->id,
                    "username" => $Member->username,
                    "type" => "签到奖励",
                    "money" => 0,
                    "notice" => $msg_str,
                    "status" => "+",
                    "yuanamount" => $yuan,
                    "houamount" => $hou,
                    "ip" => \Request::getClientIp(),
                ];
                \App\Moneylog::AddLog($log);


                return response()->json(["status" => 1, "msg" => $msg_str]);
            } else {
                return response()->json(["status" => 0, "msg" => "签到失败"]);
            }
        }
    }

    //我的股权倒计时
    public function my_count_down(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        // $username = $request->session()->get('UserName');
        // $mobile = $request->get('mobile');
        // if($mobile != $username){
        //     return response()->json(["status"=>0,"msg"=>"您没有该权限查询其他股东分红！"]);
        // }

        // $list = DB::table('moneylog')
        // ->select('product_id','moneylog_userid','updated_at','moneylog_type')
        // ->where(['moneylog_userid'=>$UserId,'category_id'=>12])
        // ->where(function($query){
        //          $query->where('moneylog_type','加入项目,银行卡付款')
        //         ->orwhere('moneylog_type','加入项目,余额付款');
        // })
        // ->get();
        $list = DB::table('productbuy')->select('userid', 'username', 'productid', 'category_id', 'status', 'updated_at', 'useritem_time', 'reason')->where(['category_id' => 12, 'status' => 1, 'userid' => $UserId])->orderBy('updated_at', 'desc')->get();
        if (count($list) < 1) {
            return response()->json(["status" => 0, "msg" => "您还没有购买股权产品，请先购买！"]);
        }
        //
        $products_list = DB::table('products')->where(['tzzt' => 0, 'category_id' => 12])->get();
        foreach ($products_list as $Product) {
            $this->Products[$Product->id] = $Product;
        }
        $second2 = time();

        foreach ($list as $v) {
            //剩余倒计时
            $second1 = strtotime($v->useritem_time);
            $hold_day = round(($second2 - $second1) / 86400);//购买到现在的天数
            $diff_day = $this->Products[$v->productid]->shijian - $hold_day;
            $v->surplus_day = $diff_day > 0 ? $diff_day : 1;//剩余释放天数
            $v->product_name = $this->Products[$v->productid]->title;
        }

        return response()->json(["status" => 1, "data" => $list]);
    }

    //一卡通
    public function one_card_receive(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        // $time = date('Y-m');
        // $nexttime = date('Y-m-d 00:00:00', strtotime(date('Y-m-01', strtotime($time)) . ' +1 month'));
        $membersubsidy = DB::table("membersubsidy")->select('id', 'uid', 'subsidy', 'username', 'issuing_time')->where(["uid" => $UserId, 'issuing_time' => Carbon::today()->toDateTimeString()])->first();
        // dump($membersubsidy);
        // exit;
        $one_card = DB::table('setings')->where('keyname', 'one_card')->value('value');
        $username = DB::table('member')->where('id', $UserId)->value('username');


        if ($membersubsidy) {
            return response()->json(["status" => 0, "msg" => '今日已领取，可在资金明细查看领取详情']);
        }

        $Membersubsidy = new membersubsidy();
        $Membersubsidy->uid = $UserId;
        $Membersubsidy->username = $username;
        $Membersubsidy->subsidy = $one_card;
        $Membersubsidy->created_at = Carbon::now();
        $Membersubsidy->updated_at = Carbon::now();
        $Membersubsidy->issuing_time = Carbon::today()->toDateTimeString();
        $Membersubsidy->status = 1;
        $res = $Membersubsidy->save();

        if ($res) {
            $log = [
                "userid" => $UserId,
                "username" => $username,
                "type" => "签到奖励(+)",
                "money" => $one_card,
                "notice" => '一卡通签到奖励',
                "status" => "+",
                "yuanamount" => 0,
                "houamount" => 0,
                "ip" => \Request::getClientIp(),
            ];
            \App\Moneylog::AddLog($log);
            return response()->json(["status" => 1, "msg" => "领取成功"]);
        }
        return response()->json(["status" => 0, "msg" => "领取失败"]);
    }

    // $data = [
    //     "subsidy" => $integral,
    //     "issuing_time = " => Carbon::now(),
    // ];

    //     $signin_data = DB::table("signinlist")->select('id','num','type','detail')->where("id",$qd_count)->first();
    // dump($signin_data->num);
    // exit();
    // $res = DB::table("membersubsidy") ->where(['uid'=>$UserId,"created_at" =>$time])->update($data);


    //股权分红列表
    public function dividend_type(Request $request)
    {
        $data['dividend_type'] = DB::table('dividend_type')->get();
        $data['equity_reminder'] = DB::table('setings')->where('keyname', 'equity_reminder')->value('value');
        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $data]);
    }

    //用户选择股权类型
    public function check_dividend_type(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $type = $request->get('type');
        if (empty($type) || !in_array($type, [1, 2, 3, 4])) {
            return response()->json(["status" => 0, "msg" => "参数不能为空"]);
        }
        $mtype = $this->Member->mtype;
        if ($mtype != 0) {
            return response()->json(["status" => 0, "msg" => "您已选择过分红方式，无法重复选择"]);
        }
        DB::beginTransaction();
        try {
            DB::table('member')->where('id', $UserId)->update(['mtype' => $type]);
            //更新表中的未选择订单
            $list = DB::table('productbuy')->where(['userid' => $UserId, 'gq_order' => '-1', 'status' => 1])->get();
            if ($list) {
                $dividend_type = DB::table('dividend_type')->where('id', $type)->first();
                foreach ($list as $v) {
                    $useritem_time2 = date('Y-m-d 00:00:00', strtotime("+" . $dividend_type->dividend_day . " day", strtotime($v->useritem_time)));
                    DB::table('productbuy')->where('id', $v->id)->update(['gq_order' => 1, 'useritem_time2' => $useritem_time2, 'mtype' => $type]);
                }
            }
            DB::commit();
        } catch (\Exception $exception) {

            DB::rollBack();
            return response()->json(["status" => 1, "msg" => "选择失败，请联系管理员"]);
        }

        return response()->json(["status" => 1, "msg" => "选择成功"]);
    }

    //股权证书
    public function equity_book(Request $request)
    {

        $UserId = $request->session()->get('UserId');
        $memberidentity = DB::table('memberidentity')->select('realname', 'idnumber')->where(['userid' => $UserId])->first();
        $order = $UserId + +6666666;
        $data['order'] = 'N' . $order;//编号
        $data['idnumber'] = $memberidentity ? \App\Member::half_replace($memberidentity->idnumber) : '';//身份证
        $data['realname'] = $memberidentity ? $memberidentity->realname : '';//姓名
        $data['type_name'] = DB::table('dividend_type')->where('id', $this->Member->mtype)->value('type_name');//周期
        //证书不包含赠送的
        $data['num'] = DB::table('productbuy')->where('productid', '<>', 168)->where(['status' => 1, 'userid' => $UserId, 'category_id' => 12])->sum('num');//数量
        $data['legal_person'] = DB::table('setings')->where('keyname', 'legal_person')->value('value');//法人
        $useritem_time = DB::table('productbuy')->select('useritem_time')->where(['status' => 1, 'userid' => $UserId])->first();//日期
        $data['useritem_time'] = $useritem_time ? date('Y年m月d日', strtotime($useritem_time->useritem_time)) : '';

        return response()->json(["status" => 1, "msg" => "返回成功", 'data' => $data]);
    }

    //是否实名认证
    public function is_check_id(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $memberidentity = DB::table("memberidentity")->select('status')->where(['userid' => $UserId])->first();
        if ($memberidentity) {//-1:未认证  0：审核中   1：已认证
            $data['status'] = $memberidentity->status;
        } else {
            $data['status'] = -1;
        }
        return response()->json(["status" => 1, "msg" => "返回成功", 'data' => $data]);
    }

    //领取任务奖金
    public function lqrwjijin(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $Member = Member::find($UserId);

        if ($Member->rw_level == 8) {

            return ["status" => 0, "msg" => '已经领取'];
        } else {
            if ($Member->rw_level < 7) {
                return ["status" => 0, "msg" => '任务未完成'];
            }
        }
        $rwfee = DB::table('setings')->where('keyname', 'rw_fee')->value('value');
        DB::beginTransaction();
        //    try{
        $yuan = $Member->rw_amount;
        //  $Member->increment('rw_amount',(float)$rwfee);
        $Member->increment('rw_level', 1);

        if ($rwfee > 0) {
            /* $log=[
                    "userid"=>$UserId,
                    "username"=>$Member->username,
                    "money"=>$rwfee,
                    "notice"=>"完成任务领取奖金",
                    "type"=>"完成任务领取奖金",
                    "status"=>"+",
                    "yuanamount"=>$yuan,
                    "houamount"=>$Member->rw_amount,

                    "ip"=>\Request::getClientIp(),
                ];*/

            // \App\Moneylog::AddLog($log);
            DB::commit();
            return ["status" => 1, "msg" => '领取成功'];
        } else {
            return ["status" => 1, "msg" => '不需要领取'];
        }
        try {
        } catch (\Exception $exception) {

            DB::rollBack();
            return ['status' => 0, 'msg' => '领取失败，请重试'];
        }
    }

    /**
     * 会员等级描述
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function monthlog(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        $Member = DB::table('member')->where(['id'=>$UserId])->first(['left_amount','right_amount','level']);
        $memberlevel = DB::table("memberlevel")->find($Member->level);

        if (empty($memberlevel)) {
            $levelname = '普通会员';
        } else {
            $levelname = $memberlevel->name;
        }
        $list = Memberlevel::orderBy("id", "ASC")->get();
        //小区业绩
        $amount = min($Member->left_amount, $Member->right_amount);
        return response()->json(["status" => 1, "msg" => "返回成功", 'data' => $list, 'amount' => $amount, 'levelname' => $levelname]);
    }

    //领取月工资
    public function lqmounth(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $Member = Member::find($UserId);
        $memberlevel = DB::table("membergrouplevel")->find($Member->month_level);
        /*if(empty($memberlevel)){
            $data['levelname'] = '普通会员';
        }else{
            $data['levelname'] = $memberlevel->name;
        }*/
        if (empty($memberlevel)) {

            $level_name = '普通会员';

            $levle_tiaojian = '购买0元丨直推0人';
        } else {
            //  $data['levelpic'] = $memberlevel->pic;
            $level_name = $memberlevel->name;

            $levle_tiaojian = '购买' . $memberlevel->level_fee . '元丨直推' . $memberlevel->tj_num . '人';
        }
        $money = $Member->mounth_fee;
        $yuan = $Member->ktx_amount;
        DB::beginTransaction();
        try {
            $Member->increment('ktx_amount', (float)$money);
            $Member->decrement('mounth_fee', (float)$money);
            if ($money > 0) {
                $log = [
                    "userid" => $UserId,
                    "username" => $Member->username,
                    "money" => $money,
                    "notice" => "领取上月工资",
                    "type" => "领取月工资",
                    "status" => "+",
                    "yuanamount" => $yuan,
                    "houamount" => $Member->ktx_amount,
                    "level_name" => $level_name,
                    "levle_tiaojian" => $levle_tiaojian,
                    "ip" => \Request::getClientIp(),
                ];

                \App\Moneylog::AddLog($log);
                DB::commit();
                return ["status" => 1, "msg" => '领取成功'];
            } else {
                return ["status" => 0, "msg" => '不需要领取'];
            }

        } catch (\Exception $exception) {

            DB::rollBack();
            return ['status' => 0, 'msg' => '领取失败，请重试'];
        }
    }

    /***领取小树盘注册***/
    public function getzctree(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();

        if ($EditMember) {
            DB::beginTransaction();
            //     try{
            $zctreeid = DB::table('setings')->where('keyname', 'xj_treeid')->value('value');  //注册赠送小树
            $counttree = TreeProductbuy::where("productid", $zctreeid)->where("userid", $UserId)->where("status", ">", "0")->count();
            $zcnum = DB::table('setings')->where('keyname', 'zc_num')->value('value');  //每注册多少需要
            if ($counttree > 0) {
                return response()->json(["status" => 0, "msg" => "存在未浇完的树木！"]);
            }
            if ((int)$zcnum > $EditMember->tree_zc) {
                return response()->json(["status" => 0, "msg" => "领取次数不足！"]);
            } else {

                $EditMember->decrement('tree_zc', $zcnum);
                if ($EditMember->tree_zc < 0) {
                    return response()->json(["status" => 0, "msg" => "领取次数不足！"]);
                    DB::rollBack();
                } else {
                    $treeinfo = TreeProduct::find($zctreeid);
                    if (!empty($treeinfo)) {
                        $useritem_time2 = \App\Productbuy::DateAdd("d", 0, date('Y-m-d 0:0:0', time()));
                        $NewProductbuy = new TreeProductbuy();
                        $NewProductbuy->userid = $EditMember->id;
                        $NewProductbuy->username = $EditMember->username;
                        $NewProductbuy->level = $EditMember->level;
                        $NewProductbuy->productid = $treeinfo->id;

                        $NewProductbuy->category_id = $treeinfo->category_id;

                        $NewProductbuy->useritem_time = Carbon::now();
                        $NewProductbuy->useritem_time2 = $useritem_time2;
                        $NewProductbuy->grand_total = $treeinfo->qtsl;
                        $NewProductbuy->zsje = $treeinfo->zgje;
                        $NewProductbuy->useritem_count = 0;

                        $NewProductbuy->sendday_count = 0;
                        $NewProductbuy->status = 1;

                        $NewProductbuy->num = 1;//购买数量
                        $NewProductbuy->order = 'TREE' . date('YmdHis') . $this->get_random_code(7);
                        $NewProductbuy->gq_order = 'TREE' . $this->get_random_code(8);
                        $NewProductbuy->created_date = date('Y-m-d');
                        $res = $NewProductbuy->save();
                    }
                }
            }
            DB::commit();
            return ['status' => 1, 'msg' => '领取成功'];
            try {
            } catch (\Exception $exception) {
                DB::rollBack();
                return ['status' => 0, 'msg' => '领取失败，请重试'];
            }
        } else {
            return response()->json(["status" => 0, "msg" => "请登录后领取"]);
        }

    }

    /***购买兑换小树***/
    public function getsumfeetree(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();

        if ($EditMember) {
            DB::beginTransaction();
            //     try{
            $zctreeid = DB::table('setings')->where('keyname', 'gm_treeid')->value('value');  //注册赠送小树
            $counttree = TreeProductbuy::where("productid", $zctreeid)->where("userid", $UserId)->where("status", ">", "0")->count();
            $zcnum = DB::table('setings')->where('keyname', 'ontree_fee')->value('value');  //每注册多少需要
            if ($counttree > 0) {
                return response()->json(["status" => 0, "msg" => "存在未浇完的树木！"]);
            }
            if ((int)$zcnum > $EditMember->dh_sumfee) {
                return response()->json(["status" => 0, "msg" => "领取次数不足！"]);
            } else {

                $EditMember->decrement('dh_sumfee', $zcnum);
                if ($EditMember->dh_sumfee < 0) {
                    return response()->json(["status" => 0, "msg" => "领取次数不足！"]);
                    DB::rollBack();
                } else {
                    $treeinfo = TreeProduct::find($zctreeid);
                    if (!empty($treeinfo)) {
                        $useritem_time2 = \App\Productbuy::DateAdd("d", 0, date('Y-m-d 0:0:0', time()));
                        $NewProductbuy = new TreeProductbuy();
                        $NewProductbuy->userid = $EditMember->id;
                        $NewProductbuy->username = $EditMember->username;
                        $NewProductbuy->level = $EditMember->level;
                        $NewProductbuy->productid = $treeinfo->id;

                        $NewProductbuy->category_id = $treeinfo->category_id;

                        $NewProductbuy->useritem_time = Carbon::now();
                        $NewProductbuy->useritem_time2 = $useritem_time2;
                        $NewProductbuy->grand_total = $treeinfo->qtsl;
                        $NewProductbuy->zsje = $treeinfo->zgje;
                        $NewProductbuy->useritem_count = 0;

                        $NewProductbuy->sendday_count = 0;
                        $NewProductbuy->status = 1;

                        $NewProductbuy->num = 1;//购买数量
                        $NewProductbuy->order = 'TREE' . date('YmdHis') . $this->get_random_code(7);
                        $NewProductbuy->gq_order = 'TREE' . $this->get_random_code(8);
                        $NewProductbuy->created_date = date('Y-m-d');
                        $res = $NewProductbuy->save();
                    }
                }
            }
            DB::commit();
            return ['status' => 1, 'msg' => '领取成功'];
            try {
            } catch (\Exception $exception) {
                DB::rollBack();
                return ['status' => 0, 'msg' => '领取失败，请重试'];
            }
        } else {
            return response()->json(["status" => 0, "msg" => "请登录后领取"]);
        }

    }

    /***连续签到兑换小树***/
    public function getlxtree(Request $request)
    {

        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();

        if ($EditMember) {
            DB::beginTransaction();
            //     try{
            $zctreeid = DB::table('setings')->where('keyname', 'lxqdtreeid')->value('value');  //注册赠送小树
            $counttree = TreeProductbuy::where("productid", $zctreeid)->where("userid", $UserId)->where("status", ">", "0")->count();
            $zcnum = DB::table('setings')->where('keyname', 'ontree_fee')->value('value');  //每注册多少需要
            if ($counttree > 0) {
                return response()->json(["status" => 0, "msg" => "存在未浇完的树木！"]);
            }
            if ($EditMember->lqtree_num <= 0) {
                return response()->json(["status" => 0, "msg" => "领取次数不足！"]);
            } else {

                $EditMember->decrement('lqtree_num', 1);
                if ($EditMember->lqtree_num < 0) {
                    return response()->json(["status" => 0, "msg" => "领取次数不足！"]);
                    DB::rollBack();
                } else {
                    $treeinfo = TreeProduct::find($zctreeid);
                    if (!empty($treeinfo)) {
                        $useritem_time2 = \App\Productbuy::DateAdd("d", 0, date('Y-m-d 0:0:0', time()));
                        $NewProductbuy = new TreeProductbuy();
                        $NewProductbuy->userid = $EditMember->id;
                        $NewProductbuy->username = $EditMember->username;
                        $NewProductbuy->level = $EditMember->level;
                        $NewProductbuy->productid = $treeinfo->id;

                        $NewProductbuy->category_id = $treeinfo->category_id;

                        $NewProductbuy->useritem_time = Carbon::now();
                        $NewProductbuy->useritem_time2 = $useritem_time2;
                        $NewProductbuy->grand_total = $treeinfo->qtsl;
                        $NewProductbuy->zsje = $treeinfo->zgje;
                        $NewProductbuy->useritem_count = 0;

                        $NewProductbuy->sendday_count = 0;
                        $NewProductbuy->status = 1;

                        $NewProductbuy->num = 1;//购买数量
                        $NewProductbuy->order = 'TREE' . date('YmdHis') . $this->get_random_code(7);
                        $NewProductbuy->gq_order = 'TREE' . $this->get_random_code(8);
                        $NewProductbuy->created_date = date('Y-m-d');
                        $res = $NewProductbuy->save();
                    }
                }
            }
            DB::commit();
            return ['status' => 1, 'msg' => '领取成功'];
            try {
            } catch (\Exception $exception) {
                DB::rollBack();
                return ['status' => 0, 'msg' => '领取失败，请重试'];
            }
        } else {
            return response()->json(["status" => 0, "msg" => "请登录后领取"]);
        }

    }

    /***小树盘浇水***/
    public function treejs(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();
        $count = Member::where("id", $UserId)->where(function ($query) {
            $query->where('tree_bonus_time', '<', date(now()))
                ->orWhere(function ($query) {
                    $query->where('tree_bonus_time', null);
                });
        })->count();

        $memberlevel = DB::table("memberlevel")->find($EditMember->level);
        $nlnum = 0;
        if (empty($memberlevel)) {
            $nlnum = rand(1, 2);
        } else {
            $nlnum = rand($memberlevel->min_nl, $memberlevel->max_nl);
        }

        if ($EditMember) {
            if ($count == 0) {
                return response()->json(["status" => 0, "msg" => "今天已经浇过水！"]);
            }
            $pinfo = TreeProductbuy::where('status', 1)
                ->where("id", $request->id)->where("userid", $UserId)
                ->where("useritem_time2", "<=", DATE_FORMAT(NOW(), 'Y-m-d H:i:s'))
                ->first();
            if (empty($pinfo)) {
                return response()->json(["status" => 0, "msg" => "今天已经浇过水"]);
            } else {
                $numsy = $pinfo->grand_total - $pinfo->sendday_count;
                $product = TreeProduct::find($pinfo->productid);
                if ($numsy < $nlnum) {
                    $nlnum = $numsy;
                }
                $useritem_time2 = \App\Productbuy::DateAdd("d", 1, date('Y-m-d 0:0:0', time()));
                $pinfo->useritem_time2 = $useritem_time2;

                $pinfo->sendday_count = $pinfo->sendday_count + $nlnum;
                $pinfo->save();
                $EditMember->tree_bonus_time = $useritem_time2;
                $EditMember->save();

                $notice = "小树盘浇水" . $product->title;
                $log = [
                    "userid" => $EditMember->id,
                    "username" => $EditMember->username,
                    "money" => $nlnum,
                    "notice" => $notice,
                    "type" => "小树盘浇水",
                    "status" => "+",
                    "ip" => \Request::getClientIp(),
                    "category_id" => $product->category_id,
                    "product_id" => $product->id,
                    "from_uid" => $UserId,
                    "from_uid_buy_id" => $pinfo->id,
                    'moneylog_type_id' => '1',
                ];
                \App\Treelog::AddLog($log);
                return response()->json(["status" => 1, "msg" => "+" . $nlnum, "num" => $nlnum]);
            }
        } else {
            return response()->json(["status" => 0, "msg" => "请登录后浇水"]);
        }
    }

    /***大树盘浇水***/
    public function bigtreejs(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();
        $count = Member::where("id", $UserId)
            ->where(function ($query) {
                $query->where('bigtree_time', '<', date(now()))
                    ->orWhere(function ($query) {
                        $query->where('bigtree_time', null);
                    });
            })
            ->count();

        $memberlevel = DB::table("memberlevel")->find($EditMember->level);
        $nlnum = 0;
        if (empty($memberlevel)) {
            $nlnum = rand(1, 5);
        } else {
            $nlnum = rand($memberlevel->min_nl, $memberlevel->max_nl);
        }
        $nlnum = $nlnum * 5;
        if ($EditMember) {
            if ($count == 0) {
                return response()->json(["status" => 0, "msg" => "今天已经浇过水！"]);
            }

            $useritem_time2 = \App\Productbuy::DateAdd("d", 1, date('Y-m-d 0:0:0', time()));

            $EditMember->bigtree_nl = $EditMember->bigtree_nl + $nlnum;
            $EditMember->bigtree_time = $useritem_time2;
            $EditMember->save();
            $bigtree = Bigtree::find(1);
            $bigtree->increment("nl", $nlnum);
            $notice = "大树盘浇水";
            $log = [
                "userid" => $EditMember->id,
                "username" => $EditMember->username,
                "money" => $nlnum,
                "notice" => $notice,
                "type" => "大树盘浇水",
                "status" => "+",
                "ip" => \Request::getClientIp(),
                "from_uid" => $UserId,

                'moneylog_type_id' => '2',
            ];
            \App\Treelog::AddLog($log);

            /////////////////////////////////////
            //邀请注册赠送能量金

            if ((int)$nlnum > 0) {
                $yuannl1 = $EditMember->nl_fee;
                $EditMember->increment("nl_fee", (int)$nlnum);
                $notice = "大树浇水获取希望资金";

                $log = [
                    "userid" => $EditMember->id,
                    "username" => $EditMember->username,
                    "money" => $nlnum,
                    "notice" => $notice,
                    "type" => "大树浇水获取希望资金",
                    "status" => "+",
                    "yuanamount" => $yuannl1,
                    "houamount" => $EditMember->nl_fee,
                    "ip" => \Request::getClientIp(),
                    "category_id" => 0,
                    "product_id" => 0,
                    "from_uid" => 0,
                    "from_uid_buy_id" => 0,
                    'moneylog_type_id' => '33',
                ];
                \App\Moneylog::AddLog($log);
            }
            /////////////////////////////////////
            return response()->json(["status" => 1, "msg" => "+" . $nlnum, "num" => $nlnum]);
        } else {
            return response()->json(["status" => 0, "msg" => "请登录后浇水"]);
        }
    }

    /***小树盘领取奖励***/
    public function treeaword(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();

        $memberlevel = DB::table("memberlevel")->find($EditMember->level);

        if ($EditMember) {

            $pinfo = TreeProductbuy::where(['status' => 1])
                ->where("id", $request->id)->where("userid", $UserId)
                ->where("sendday_count", ">=", "grand_total")
                ->first();
            if (empty($pinfo)) {
                return response()->json(["status" => 0, "msg" => "未达到要求"]);
            } else {

                $product = TreeProduct::find($pinfo->productid);
                if (empty($product)) {
                    return response()->json(["status" => 0, "msg" => "项目不存在！"]);
                }

                $pinfo->status = 0;
                $pinfo->save();
                $rewardMoney = $product->zgje;
                $MOamount = $EditMember->ktx_amount;
                $EditMember->increment('ktx_amount', $rewardMoney);
                $notice = "领取希望资金";
                $log = [
                    "userid" => $EditMember->id,
                    "username" => $EditMember->username,
                    "money" => $rewardMoney,
                    "notice" => $notice,
                    "type" => "领取希望资金",
                    "status" => "+",
                    "yuanamount" => $MOamount,
                    "houamount" => $EditMember->ktx_amount,
                    "ip" => \Request::getClientIp(),
                    "category_id" => $product->category_id,
                    "product_id" => $product->id,
                    "from_uid" => $UserId,
                    "from_uid_buy_id" => $pinfo->id,
                    'moneylog_type_id' => '5',
                ];
                \App\Moneylog::AddLog($log);
                return response()->json(["status" => 0, "msg" => "领取成功！"]);
            }
        } else {
            return response()->json(["status" => 0, "msg" => "请登录后领取"]);
        }
    }

    /***大树盘基本设置***/
    public function bigtreeinfo(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();


        if ($EditMember) {
            $dashu_nl = DB::table("setings")->where("keyname", 'dashu_nl')->value('value');//判断是否达到登记要求
            $num = DB::table("bigtree")->find(1);//判断是否达到登记要求
            $data["allnum"] = (int)$dashu_nl + $num->nl;
            return response()->json(["status" => 1, "data" => $data]);
        } else {
            return response()->json(["status" => 0, "msg" => "请登录后"]);
        }
    }

    /***树木任务***/
    public function treetask(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();


        if ($EditMember) {

            $dashu_nl = DB::table("setings")->where("keyname", 'dashu_nl')->value('value');//判断是否达到登记要求
            $data["lx_qd"] = DB::table("setings")->where("keyname", 'lx_qd')->value('value');//连续次数
            $data["lxqdtreeid"] = DB::table("setings")->where("keyname", 'lxqdtreeid')->value('value');//连续次数
            $lxqdinfo = TreeProduct::find($data["lxqdtreeid"]);
            if (!empty($lxqdinfo)) {
                $data["lxqdtreename"] = $lxqdinfo->title;
            } else {
                $data["lxqdtreename"] = "";
            }
            $data["gm_treeid"] = DB::table("setings")->where("keyname", 'gm_treeid')->value('value');//连续次数
            $gminfo = TreeProduct::find($data["gm_treeid"]);
            if (!empty($gminfo)) {
                $data["gmtreename"] = $gminfo->title;
            } else {
                $data["gmtreename"] = "";
            }
            $data["xj_treeid"] = DB::table("setings")->where("keyname", 'xj_treeid')->value('value');//连续次数
            $xjinfo = TreeProduct::find($data["xj_treeid"]);
            if (!empty($xjinfo)) {
                $data["xjtreename"] = $xjinfo->title;
            } else {
                $data["xjtreename"] = "";
            }
            $data["dh_sumfee"] = $EditMember->dh_sumfee;//连续次数
            $data["ontree_fee"] = DB::table("setings")->where("keyname", 'ontree_fee')->value('value');//连续次数
            $data["ulx_qd"] = $EditMember->lx_qd;//连续次数
            $data["lqtree_num"] = $EditMember->lqtree_num;//连续次数
            $num = DB::table("bigtree")->find(1);//判断是否达到登记要求
            $data["allnum"] = (int)$dashu_nl + $num->nl;
            return response()->json(["status" => 1, "data" => $data]);
        } else {
            return response()->json(["status" => 0, "msg" => "请登录后"]);
        }
    }

    /***余额宝信息***/
    public function yuebao(Request $request)
    {
        $UserId = $request->session()->get('UserId');

        $EditMember = Member::where("id", $UserId)->first();

        $pinfo = Product::where("category_id", 42)->first();

        return response()->json(["status" => 1, "data" => $pinfo]);

    }
}
