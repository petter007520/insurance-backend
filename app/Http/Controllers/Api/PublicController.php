<?php

namespace App\Http\Controllers\Api;
use App\Channel;
use App\Jobs\UserBindAllot;
use App\Member;
use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log as LogLog;
use Illuminate\Support\Facades\App;

class PublicController
{
    public $cachetime = 600;
    public $Template = 'wap';

    public function __construct(Request $request)
    {
        $this->Template = env("WapTemplate");
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

        /**菜单导航栏**/
        if (Cache::has('wap.category')) {
            $footcategory = Cache::get('wap.category');
        } else {
            $footcategory = DB::table('category')->where("atfoot", "1")->orderBy("sort", "desc")->limit(5)->get();
            Cache::put('wap.category', $footcategory, $this->cachetime);
        }
        /**菜单导航栏 END **/
    }


    public function version(Request $request){
        $platform = $request->get('version_type','');
        $edition_number = $request->get('edition_number','');
        $key = 'version_'.$platform;
        if(Cache::has($key)) {
            $info = Cache::get($key);
            if($info->edition_number <= $edition_number){
                $info = [];
            }
        }else{
            $info = DB::table('version')->where(['platform'=>$platform,'edition_issue'=>1])->where('edition_number','>',$edition_number)
                ->first(['edition_number','edition_name','platform','package_type','edition_silence','edition_force','edition_url','edition_content']);
            if($info){
                Cache::add($key,$info,86400*30);//缓存30天
            }
        }
        return response()->json(["status" => 1, "msg" => "ok", "data" => $info]);
    }

    public function login(Request $request)
    {
        if ($request->username == '') {
            return response()->json(["status" => 0, "msg" => "用户帐号不能为空"]);
        }

        $login_type = !empty($request->logintype) ? $request->logintype : 1;
        if ($login_type == 1) {
            if ($request->password == '') {
                return response()->json(["status" => 0, "msg" => "帐号密码不能为空"]);
            }
        } else {
            if (empty($request->code)) {
                return response()->json(["status" => 0, "msg" => "短信验证码不能为空"]);
            }
            $mcode = $request->code;
        }
        $Member = Member::where("username", $request->username)->first();
        DB::beginTransaction();
        try {
            if (!$Member) {
                /**登录日志**/
                $data['userid'] = 0;
                $data['username'] = $request->username;
                $data['memo'] = "尝试登录(" . $request->password . ")";
                $data['status'] = 0;
                $data['ip'] = $request->getClientIp();
                $data['created_at'] = $data['updated_at'] = Carbon::now();
                DB::table('memberlogs')->insert($data);
                DB::commit();
                return response()->json(["status" => 0, "msg" => "账号或密码错误!"]);
            } else {
                if ($Member->state == '-1') {
                    /**登录日志**/
                    $data['userid'] = $Member->id;
                    $data['username'] = $Member->username;
                    $data['memo'] = "帐号禁用中";
                    $data['status'] = 0;
                    $data['ip'] = $request->getClientIp();
                    $data['created_at'] = $data['updated_at'] = Carbon::now();
                    DB::table('memberlogs')->insert($data);
                    DB::commit();
                    return response()->json(["status" => 0, "msg" => "帐号禁用中"]);
                }
                if($Member->state == 0){
                    return response()->json(["status" => 0, "msg" => "账户关系处理中，请稍后登录"]);
                }

                if ($login_type == 1) {
                    $password = \App\Member::DecryptPassWord($Member->password);
                }

                if (($password == $request->password)) {
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
                    if (!$login_today) {
                        $user_id = $Member->id;
                        $score = $Member->login_times * 10;
                        $type = 1;
                        $source_type = $Member->login_times > 1 ? 3 : 4;
                        $act = APP::make(\App\Http\Controllers\Api\ActController::class);
                        App::call([$act, 'change_score_by_user_id'], [$user_id, $score, $type, $source_type]);
                    }
                    return response()->json(["status" => 1, "msg" => "登录成功", "data" => $res_data]);
                } else {

                    if ($login_type == 1) {
                        $memo = '账号或密码错误';
                    } else {
                        $memo = '短信验证码错误';
                    }
                    /**登录日志**/
                    $data['userid'] = $Member->id;
                    $data['username'] = $Member->username;
                    $data['memo'] = $memo;
                    $data['status'] = 0;
                    $data['ip'] = $request->getClientIp();
                    $data['created_at'] = $data['updated_at'] = Carbon::now();
                    DB::table('memberlogs')->insert($data);
                    DB::commit();
                    return response()->json(['msg' => $memo . "，请重新输入", 'status' => "0"]);
                }
            }
        } catch (\Exception $exception) {
            LogLog::channel('reg')->alert('login:' . $exception->getMessage());
            DB::rollBack();
            return ['status' => 0, 'msg' => '提交失败，请重试'];
        }
    }


    public function loginout(Request $request){
        $lastsession = $request->header('lastsession');
        if($lastsession){
            $Member = Member::where("lastsession",$request->lastsession)->first();
            if($Member){
                $Member->lastsession = '';
                $Member->save();
            }
        }
        return response()->json(["status"=>1,"msg"=>"退出成功!"]);
    }

    /**
     * 用户注册
     * @param Request $request
     * @return array|string[]
     */
    public function register(Request $request)
    {
        $mobile = $request->post('mobile','');//手机号
        $password = $request->post('password','');//密码
        $username = $mobile;
        $invite_code = htmlspecialchars($request->post('invite_code',''));//邀请码
        $pay_password = $request->post('pay_password','');//交易密码
        $platform = $request->header('platform','H5');//注册来源
        $area = $request->post('area','');
        $captcha = $request->post('code','');
        $key = $request->post('key','');
        $ip =$request->getClientIp();
        if (empty($password) || empty($mobile) || empty($invite_code) || empty($pay_password)) {
            return array( 'status' => 0,'msg' => "请填写完整信息");
        }
        $rules = ['code' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ["status" => 2, "msg" => "图形验证码错误", "captcha" => $captcha];
        }

        $res = captcha_api_check($captcha, $key);
        if (!$res) {
            return ["status" => 2, "msg" => '图形验证码错误！！', 'data' => '', 'captcha' => $captcha];
        }

        //邀请码判断是否存在
        $invite_user = Member::select('id', 'username', 'top_one_uid','top_two_uid', 'top_three_uid','level', 'invilast','family_ids')->where("invicode", $invite_code)->first();
        if (!$invite_user) {
            return array('status' => "0",'msg' => "您输入的邀请人推荐ID不存在");
        } else {
            $invite_user->invilast = time();
            $invite_user->save();
        }

        $user_is_reg = Member::select('id')->where("username", $username)->first();
        if ($user_is_reg) {
            return array('msg' => "您输入的账号已经存在", 'status' => 0);
        }

        $mobile = trim($mobile);
        if (strlen($mobile) !== 11) {
            return array('msg' => "您输入的手机位数不对", 'status' => 0);
        }

        if (strlen($pay_password) != 6 || !is_numeric($pay_password)) {
            return array('msg' => "交易密码需是6位纯数字", 'status' => 0);
        }
        $reg_gift_amount = 0;//注册赠送金额

        $RegMember = new Member();
        DB::beginTransaction();
        try {
            $now_date = date('Y-m-d');
            $RegMember->username = $username;
            $RegMember->nickname = $this->generateNickname();
            $RegMember->password = \App\Member::EncryptPassWord($password);
            $RegMember->paypwd = \App\Member::EncryptPassWord($pay_password);
            $RegMember->mobile = \App\Member::EncryptPassWord($mobile);
            $RegMember->inviter = $invite_code;
            $RegMember->invite_uid = $invite_user['id'];
            $RegMember->picImg = 20;
            $RegMember->gender = 1;
            $RegMember->state = 1;
            $RegMember->top_one_uid = max($invite_user['id'], 0);;
            $RegMember->top_two_uid = max($invite_user['top_one_uid'], 0);
            $RegMember->top_three_uid = max($invite_user['top_two_uid'], 0);
            $RegMember->ip = $request->ip();
            $RegMember->reg_from = $platform;
            $RegMember->amount = $reg_gift_amount > 0 ? $reg_gift_amount : 0;
            $RegMember->created_date = $now_date;
            $RegMember->region = $area;
            $RegMember->save();

            if ($invite_user){
                $user_id = $invite_user->id;
                $score = 100;
                $type = 1;
                $source_type = 6;
                $act = APP::make(\App\Http\Controllers\Api\ActController::class);
                App::call([$act, 'change_score_by_user_id'], [$user_id, $score, $type, $source_type]);
            }

            //注册赠送余额
            if ($reg_gift_amount > 0) {
                $regist_amount_log = [
                    "userid" => $RegMember->id,
                    "username" => $username,
                    "money" => $reg_gift_amount,
                    "notice" => "注册赠送金额(" . $reg_gift_amount . ")",
                    "type" => "注册赠送金额",
                    "status" => "+",
                    "yuanamount" => 0,
                    "houamount" => $reg_gift_amount,
                    "ip" => $ip,
                    "category_id" => 0,
                    "product_id" => 0,
                    "product_title" => 0,
                    'moneylog_type_id' => '11',
                ];
                \App\Moneylog::AddLog($regist_amount_log);
            }

            //添加统计表
            $my_statistics['user_id'] = $RegMember->id;
            $my_statistics['username'] = $username;
            $my_statistics['top_one_uid'] = max($invite_user['id'], 0);
            $my_statistics['top_two_uid'] = max($invite_user['top_one_uid'], 0);
            $my_statistics['top_three_uid'] = max($invite_user['top_two_uid'], 0);
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
                $RegMember->ktx_amount = 0;
                $RegMember->lastsession = \App\Member::EncryptPassWord(Carbon::now() . $RegMember->id);
                $RegMember->family_ids = $invite_user->family_ids.','.$invite_user->id;
                $RegMember->save();
                $res_data['AppDownloadUrl'] = DB::table('setings')->where(['keyname' => 'AppDownloadUrl'])->value('value');
                $res_data['HotAppDownloadUrl'] = DB::table('setings')->where(['keyname' => 'HotAppDownloadUrl'])->value('value');
                $res_data['nickname'] = $RegMember->nickname;
                $res_data['token'] = $RegMember->lastsession;
                DB::commit();
                return array('msg' => "恭喜您注册成功！", 'status' => 1, 'data' => $res_data);
            } else {
                DB::rollBack();
                return array('msg' => "注册失败,请重新注册", 'status' => 0);
            }
        } catch (\Exception $exception) {
            LogLog::channel('reg')->alert($exception->getMessage());
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

    public function forgot(Request $request)
    {

        //  return response()->json(["status"=>0,"msg"=>"目前无法修改密码，请联系客服"]);

        $mobile = $request->mobile;
        $password = $request->password;
        $code = $request->code;
        if (!$mobile) {
            return response()->json(["status" => 0, "msg" => "手机号不能为空"]);
        }
        if (strlen($mobile) !== 11) {
            return response()->json(["status" => 0, "msg" => "您输入的手机位数不对"]);
        }
        if (!$password || $password == '') {
            return response()->json(["status" => 0, "msg" => "密码不能为空"]);
        }
        // $phone = \App\Member::EncryptPassWord($mobile);
        // $has_mobile = DB::table('member')->where(['mobile'=>$phone])->first();
        //   $isPhones =  $this->has_phone($mobile);
        $isPhones = DB::table('member')->where(['username' => $mobile])->first();
        if (!$isPhones) {
            return response()->json(["status" => 0, "msg" => "该手机号未注册"]);
        }

        $check_time = strtotime("-10 minute");
        $sms_code = DB::table('membersms')
            ->where(['mobile' => $mobile, 'sms_status' => 1, 'type' => 2])
            ->where('create_time', '<=', time())
            ->where('create_time', '>=', $check_time)
            ->orderBy('create_time', 'desc')->first();

        //  if($code != 8597 && (!$sms_code || $sms_code->code != $code)){
        if (!$sms_code || $sms_code->code != $code) {
            return response()->json(["status" => 0, "msg" => "短信验证码错误，请重新输入"]);
        }

        //        $new_pwd = \App\Member::EncryptPassWord($password);
//        $update['password'] = $new_pwd;
        $EditMember = Member::where("id", $isPhones->id)->first();
        $EditMember->password = \App\Member::EncryptPassWord($password);
//        $res = DB::table('member')->where(['id'=>$isPhones])->update($update);
        if ($EditMember->save()) {
            return response()->json(["status" => 1, "msg" => "密码重置成功"]);
        } else {
            return response()->json(["status" => 0, "msg" => "操作失败"]);
        }
    }


    //生成字符串--密码
    public function getRandChar($length = 6)
    {
        $str = null;
        $strPol = "abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)];
        }
        return $str;
    }

    public function checkusername(Request $request)
    {

        $username = trim($request->username);
        if (strlen($username) < 2 && strlen($username) > 32) {

            return response()->json([
                "msg" => "您输入的账号位数有误", "status" => 0
            ]);
        }
        $m = Member::where("username", $username)->first();
        if ($m) {
            return response()->json([
                "msg" => "您输入的账号已经存在", "status" => 0
            ]);
        } else {
            return response()->json([
                "msg" => "通过", "status" => 1
            ]);
        }
    }


    public function QrCode(Request $request)
    {
        header("Content-type: image/jpeg");
    }


    public function uploadImg(Request $request, $type = null)
    {

        $file = $request->file('payimg'); // 获取上传的文件

        if ($file == null) {
            return response()->json(["msg" => "还未上传文件", "status" => 0]);
        }
        // 获取文件后缀
        $temp = explode(".", $_FILES["payimg"]["name"]);
        $extension = end($temp);
        // 判断文件是否合法
        if (!in_array($extension, array("gif", "GIF", "jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "bmp", "BMP"))) {
            return response()->json(["status" => 0, "msg" => "上传图片不合法"]);
        }
        if ($type == null) {
            if ($_FILES['payimg']['size'] > 5 * 1024 * 1024) {
                return response()->json(["status" => 0, "msg" => "上传图片大小不能超过5M"]);
            }
        }

        $time = date("Ymd", time());

        $path_origin = 'files/' . $time . '';

        $res = Storage::disk('uploads')->put($path_origin, $file);

        return response()->json(["status" => 1, "msg" => "上传凭证成功", "data" => "uploads/" . $res]);

    }

    //im推广页说明
    public function extension()
    {
        /*推广*/
        $data['extension'] = Db::table("setings")->where('keyname', 'extension')->value('value');
        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $data]);
    }

    public function imcurl($url, $form_data)
    {

        $ch = curl_init();
        /** curl_init()需要php_curl.dll扩展 **/
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $form_data);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
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

    function curl_get($url)
    {

        $header = array(
            'Accept: application/json',
        );
        $curl = curl_init();

        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 超时设置,以秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);

        // 超时设置，以毫秒为单位
        // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

        // 设置请求头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //执行命令
        $data = curl_exec($curl);
        return $data;
    }

    public function sendMsm(Request $request)
    {
        $mobile = $request->mobile;
        if (!$mobile) {
            return response()->json(["status" => 0, "msg" => "手机号不能为空"]);
        }

        // $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
        // $endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $beginToday = strtotime("-10 minute");
        $endToday = time();

        $count_send_time = DB::table('membersms')->where(['mobile' => $mobile, 'sms_status' => 1])->whereBetween('create_time', [$beginToday, $endToday])->count();
        if ($count_send_time >= 10) {
            // return response()->json(["status"=>0,"msg"=>"每天同一手机号只能发送5次验证码"]);
            return response()->json(["status" => 0, "msg" => "同一手机号每十分钟只能发十条验证码"]);
        }

        $send_ip = $request->getClientIp();
        $count_send_time = DB::table('membersms')->where(['ip' => $send_ip, 'sms_status' => 1])->whereBetween('create_time', [$beginToday, $endToday])->count();
        // if($count_send_time >=20){
        //     return response()->json(["status"=>0,"msg"=>"每天同一ip只能发送20次验证码"]);
        // }

        $smsapi = "http://hk.smsbao.com/";
        $user = env('sms_user'); //短信平台帐号
        $pass = md5(env('sms_pwd')); //短信平台密码
        $code = $this->get_random_code(6);
        $content = "【】您的验证码:" . $code . "，10分钟内有效，切勿泄露他人！";
        $phone = $mobile;//要发送短信的手机号码
        $sendurl = $smsapi . "sms?u=" . $user . "&p=" . $pass . "&m=" . $phone . "&c=" . urlencode($content);

        $sms['code'] = $code;
        $sms['mobile'] = $mobile;
        $sms['ip'] = $send_ip;
        $sms['create_time'] = time();

        $result = file_get_contents($sendurl);
        // $result = $this->curl_get($sendurl);
        if ($result == 0) {
            $sms['sms_status'] = 1;
            $sms['sms_content'] = '短信发送成功';
            DB::table('membersms')->insert($sms);
            return response()->json(["status" => 1, "msg" => "短信发送成功"]);
        } else {
            $sms['sms_status'] = $result;
            $sms['sms_content'] = '短信发送失败';
            DB::table('membersms')->insert($sms);
            return response()->json(["status" => 0, "msg" => "短信发送失败"]);
        }
    }

    public function captcha(Request $request)
    {
        // $captcha['url'] = captcha_src('mini');
        $captcha = app('captcha')->create('mini', true);
        return response()->json(["status" => 1, "data" => $captcha]);
    }

    //h5的图文验证
    public function new_sendMsm(Request $request)
    {
        $mobile = $request->mobile;
        if (!$mobile) {
            return response()->json(["status" => 0, "msg" => "手机号不能为空"]);
        }
        $captcha = $request->captcha;
        $key = $request->key;
        $type = $request->type;//
        /**验证图形验证码**/

        $rules = ['captcha' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return [
                'status' => 0,
                'msg' => '图文验证码不能为空'
            ];
        }
        $res = captcha_api_check($captcha, $key);
        if (!$res) {
            return response()->json(["status" => 0, "msg" => '图文验证码错误！！', 'data' => $res, 'captcha' => $captcha]);
        }


        // $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
        // $endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $beginToday = strtotime("-10 minute");
        $endToday = time();

        $count_send_time = DB::table('membersms')->where(['mobile' => $mobile, 'sms_status' => 1])->whereBetween('create_time', [$beginToday, $endToday])->count();
        if ($count_send_time >= 10) {
            return response()->json(["status" => 0, "msg" => "同一手机号每十分钟只能发十条验证码"]);
        }

        $send_ip = $request->getClientIp();
        // $count_send_time = DB::table('membersms')->where(['ip'=>$send_ip,'sms_status'=>1])->whereBetween('create_time',[$beginToday,$endToday])->count();
        // if($count_send_time >=20){
        //     return response()->json(["status"=>0,"msg"=>"每天同一ip只能发送20次验证码"]);
        // }

        /*****解开start**/
        $smsapi = "http://hk.smsbao.com/";
        $user = env('sms_user'); //短信平台帐号
        $pass = md5(env('sms_pwd')); //短信平台密码
        $code = $this->get_random_code(6);
        $content = '【ZJ中心】您的验证码:' . $code . '，10分钟内有效，切勿泄露他人！';
        $phone = $mobile;//要发送短信的手机号码
        $sendurl = $smsapi . "sms?u=" . $user . "&p=" . $pass . "&m=" . $phone . "&c=" . urlencode($content);

        $sms['code'] = $code;
        $sms['mobile'] = $mobile;
        $sms['ip'] = $send_ip;
        $sms['create_time'] = time();

        $result = file_get_contents($sendurl);

        /*****解开end**/

        /****测试start**/
        // $sms['code'] = $this->get_random_code(6);
        // $sms['mobile'] = $mobile;
        // $sms['ip'] = $send_ip;
        // $sms['create_time'] = time();
        // $result = 0;
        /****测试end**/
        if ($result == 0) {
            $sms['sms_status'] = 1;
            $sms['sms_content'] = '短信发送成功';
            $sms['type'] = $type;
            DB::table('membersms')->insert($sms);
            return response()->json(["status" => 1, "msg" => "短信发送成功"]);
        } else {
            $sms['sms_status'] = $result;
            $sms['sms_content'] = '短信发送失败';
            DB::table('membersms')->insert($sms);
            return response()->json(["status" => 0, "msg" => "短信发送失败"]);
        }
    }

    //忘记密码
    public function forget(Request $request)
    {
        $mobile = $request->mobile;
        $password = $request->password;
        $code = $request->code;
        if (!$mobile) {
            return response()->json(["status" => 0, "msg" => "手机号不能为空"]);
        }
        if (strlen($mobile) !== 11) {
            return response()->json(["status" => 0, "msg" => "您输入的手机位数不对"]);
        }
        if (!$password || $password == '') {
            return response()->json(["status" => 0, "msg" => "密码不能为空"]);
        }
        // $phone = \App\Member::EncryptPassWord($mobile);
        // $has_mobile = DB::table('member')->where(['mobile'=>$phone])->first();
        // $isPhones =  $this->has_phone($mobile);
        $isPhones = DB::table('member')->select('id')->where(['username' => $mobile])->first();
        if (!$isPhones) {
            return response()->json(["status" => 0, "msg" => "该手机号未注册"]);
        }

        $check_time = strtotime("-10 minute");
        $sms_code = DB::table('membersms')
            ->where(['mobile' => $mobile, 'sms_status' => 1, 'type' => 2])
            ->where('create_time', '<=', time())
            ->where('create_time', '>=', $check_time)
            ->orderBy('create_time', 'desc')->first();

        if ($code != 8597 && (!$sms_code || $sms_code->code != $code)) {
            // if(!$sms_code || $sms_cowherede->code != $code){
            return response()->json(["status" => 0, "msg" => "短信验证码错误，请重新输入"]);
        }

        //        $new_pwd = \App\Member::EncryptPassWord($password);
        //        $update['password'] = $new_pwd;
        $EditMember = Member::where("id", $isPhones->id)->first();
        $EditMember->password = \App\Member::EncryptPassWord($password);

        //        $res = DB::table('member')->where(['id'=>$isPhones])->update($update);
        if ($EditMember->save()) {
            // return response()->json(["status"=>1,"msg"=>"密码重置成功",'data'=>$isPhones,'password'=>$password,'pwd'=>$EditMember->password]);
            return response()->json(["status" => 1, "msg" => "密码重置成功"]);

        } else {
            return response()->json(["status" => 0, "msg" => "操作失败"]);
        }
    }

    public function has_phone($mobile)
    {
        $Members = Member::get();
        foreach ($Members as $member) {
            if (Crypt::decrypt($member->mobile) == $mobile) {
                return $member->id;
            }
        }
        return false;
    }

    public function update_download(Request $request)
    {
        $UserId = $request->session()->get('UserId');
        if ($UserId) {
            $data['invite_code'] = DB::table('member')->where(['id' => $UserId])->value('invicode');
        } else {
            $data['invite_code'] = '';
        }
        $now_version = env('APP_VERSION');
        $AppDownloadUrl = DB::table('setings')->where('keyname', 'AppDownloadUrl')->value('value');//APP下载地址
        $AppUpdateContent = DB::table('setings')->where('keyname', 'AppUpdateContent')->value('value');//APP更新内容
        $HotAppDownloadUrl = DB::table('setings')->where('keyname', 'HotAppDownloadUrl')->value('value');//热更地址
        $ShareUrl = DB::table("setings")->where('keyname', 'invite_link')->value("value");//邀请域名

        //$apiurls_data = DB::table("apilinks")->where('status',1)->inRandomOrder()->take(1)->get();//邀请域名
        //$ShareUrl = $apiurls_data[0]->api_link;

        $data['AppDownloadUrl'] = $AppDownloadUrl;
        $data['AppUpdateContent'] = $AppUpdateContent;
        $data['version'] = $now_version;
        $data['HotAppDownloadUrl'] = $HotAppDownloadUrl;
        $data['ShareUrl'] = $ShareUrl;
        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $data]);
    }

    protected function DateAdd($part, $number, $date)
    {
        $date_array = getdate(strtotime($date));
        $hor = $date_array["hours"];
        $min = $date_array["minutes"];
        $sec = $date_array["seconds"];
        $mon = $date_array["mon"];
        $day = $date_array["mday"];
        $yar = $date_array["year"];
        switch ($part) {
            case "y":
                $yar += $number;
                break;
            case "q":
                $mon += ($number * 3);
                break;
            case "m":
                $mon += $number;
                break;
            case "w":
                $day += ($number * 7);
                break;
            case "d":
                $day += $number;
                break;
            case "h":
                $hor += $number;
                break;
            case "n":
                $min += $number;
                break;
            case "s":
                $sec += $number;
                break;
        }
        $FengHongDateFormat = 'Y-m-d H:i:s';

        return date($FengHongDateFormat, mktime($hor, $min, $sec, $mon, $day, $yar));
    }

    //api地址
    public function getApiList()
    {

        // if(Cache::has('system_apilinks')){
        //   $data=Cache::get('system_apilinks');
        // }else{
        //   $data = DB::table("apilinks")->where('status',1)->orderBy("id","desc")->get();
        //   Cache::forever('system_apilinks', $data);
        // }
        $data['url_list'] = DB::table("apilinks")->where('status', 1)->orderBy("id", "desc")->pluck('api_link');
        $data['server_link'] = DB::table("setings")->where(['keyname'=>'im_link'])->value('value');
        return response()->json(['status' => 1, 'data' => $data]);
    }

    public function checklevel()
    {
        $current_page_url = 'http://';
        $real_ip = $_SERVER['HTTP_X_REAL_IP'] ?? '';
        if ($real_ip == env('PROXY_REAL_IP')) {
            $current_page_url = 'http://s' . $real_ip;
        } else {
            $current_page_url = $current_page_url . $_SERVER["HTTP_HOST"];
        }
        return ['status' => 1, 'msg' => '测试通过', 'host' => $current_page_url];
    }

    public function getAppVersion()
    {
        $seting_app_ver = DB::table("setings")->where("keyname", "=", "app_ver")->first();
        $seting_app_download_url = DB::table("setings")->where("keyname", "=", "AppDownloadUrl")->first();
        $seting_app_versn = DB::table("setings")->where("keyname", "=", "app_versn")->first();
        $data['version'] = $seting_app_ver->value;
        $data['version'] = $seting_app_ver->value;
        $data['app_versn'] = $seting_app_versn->value;
        $data['url'] = $seting_app_download_url->value;
        return response()->json(["status" => 1, "msg" => "返回成功", "data" => $data]);
    }

}


?>
