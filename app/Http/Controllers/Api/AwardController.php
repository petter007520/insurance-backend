<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Memberaddress;
use App\Seting;
use DB;
use Session;
use App\Member;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    private $table = "awards";
    private $prize_table = "award_prizes";
    public $cachetime = 600;
    public $Template = 'wap';

    public function test()
    {
        echo time();
        die;
        echo "<a onclick=''></a>";
        var_dump($this->get_lianxu(26027));
    }

    private function get_lianxu($uid, $current = 0)
    {
        $lianxu_sql = "SELECT d.day, COALESCE(m.is_login, 0) as is_login
FROM(SELECT @cdate:= date_add(@cdate, interval - 1 day) as day FROM(SELECT @cdate:= date_add(CURDATE(), interval + 1 day) from memberlogs) tmp1 WHERE @cdate > CONCAT(DATE_FORMAT(CURDATE(),'%Y'),'-',DATE_FORMAT(CURDATE(),'%m') - {$current},'-01')) d
LEFT JOIN(SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as day, count(*) as is_login FROM memberlogs WHERE userid = {$uid} GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')) m
on d.day = m.day ORDER BY `d`.`day` DESC";
        $lianxu = DB::select($lianxu_sql);
        $lianxu_num = 0;
        if ($lianxu) {
            foreach ($lianxu as $k => $v) {
                $is_login = json_decode($v->is_login, true);
                if ($is_login > 0)
                    $lianxu_num++;
                else
                    break;
            }
        }
        return $lianxu_num;
    }

    //TODO 要验证就找我
    private function auth($request)
    {
        $lastsession = $request["lastsession"];
        $Member = $lastsession ? Member::where("lastsession", $request["lastsession"])->first() : [];
        $UserId = $Member ? $Member->id : false;
        if (empty($UserId) || $UserId < 1)
            return response()->json(["status" => -1, "msg" => "请先登录!"]);
        else {
            $this->Member = Member::find($UserId);
            if (!$this->Member) {
                return response()->json(["status" => -1, "msg" => "请先登录!"]);
            }
            if ($this->Member->state == '0' || $this->Member->state == '-1') {
                return response()->json(["status" => 0, "msg" => "帐号禁用中"]);
            }
        }
        return $UserId;
    }

    public function get_user(Request $request)
    {
        $uid = $this->auth($request);
        return is_numeric($uid) ? $this->get_user_info($uid) : $uid;
    }

    public function get_address(Request $request)
    {
        $uid = $this->auth($request);
        return is_numeric($uid) ? $this->get_user_address($uid) : $uid;
    }

    public function add_address(Request $request)
    {
        $uid = $this->auth($request);
        if ($uid) {
            $insert = [
                "userid" => $uid,
                "area" => $request["area"],
                "address" => $request["address"],
                "receiver" => $request["receiver"],
                "mobile" => $request["mobile"],
                "status" => intval($request["status"]),
            ];
            return $this->add_user_address($insert);
        }
    }

    private function get_user_address($uid)
    {
        $address = Memberaddress::where("userid", "=", $uid)->get();
        return response()->json(["status" => 1, "data" => $address]);
    }

    private function add_user_address($data, $id = false)
    {
        Memberaddress::insert($data);
        return response()->json(["status" => 1, "msg" => "添加地址成功"]);
    }

    //TODO 获取用户个人信息
    private function get_user_info($uid)
    {
        $data = [];
        $member = Member::where("id", "=", $uid)->select(["username", "nickname", "luckdraws"])->first();
        if ($member)
            $member = $member->toArray();
        $address = Memberaddress::where("userid", "=", $uid)->where("status", "=", 1)->value("address");
        if (empty($address))
            $address = Memberaddress::where("userid", "=", $uid)->value("address");
        $data["user_nick"] = $member["nickname"];
        $data["user_name"] = $member["username"];
        $data["user_luckdraws"] = $member["luckdraws"];
        $data["user_address"] = $address;
        $data["user_count"] = floor($member["luckdraws"] / 100);
        $data["user_point"] = $this->get_lianxu($uid);
        $in_num = Seting::where("keyname", "=", "open_award_time")->value("value");
        $data["open_time"] = $in_num + Member::count();//参与人数
        $images = DB::table($this->prize_table)->select(["image"])->get();
        $data["award_list"] = $images;
        return response()->json(['status' => 1, 'data' => $data]);
    }

    //TODO 获取全部当日抽奖
    public function index()
    {
        return $this->get_award_list();
    }

    //TODO 获取自己当日抽奖
    public function get_award(Request $request)
    {
        $uid = $this->auth($request);
        return is_numeric($uid) ? $this->get_award_list($uid) : $uid;
    }

    //TODO 获取自己历史抽奖
    public function get_history(Request $request)
    {
        $uid = $this->auth($request);
        return is_numeric($uid) ? $this->get_award_list($uid) : $uid;
    }

    //TODO 获取全部历史抽奖
    public function history()
    {
        return $this->get_award_list(true);
    }

    //TODO 全局获取抽奖信息函数
    private function get_award_list($is_history = false, $user_id = false)
    {
        $where = [];
        $start_time = strtotime("midnight");
        $end_time = strtotime("next day midnight") - 1;
        /*if ($is_history) {
            $where[] = [$this->table . ".time", '<', $start_time, 'and'];
            $where[] = [$this->table . ".time", '>', $end_time, 'and'];
        }*/
        if ($user_id)
            $where[] = [$this->table . ".user_id", '=', $user_id, 'and'];
//        DB::enableQueryLog();
        $list = DB::table($this->table)
            ->join($this->prize_table, function ($join) {
                $join->on($this->table . ".prize_id", "=", $this->prize_table . ".id");
            })
            ->where($where)
            ->select($this->table . '.*', $this->prize_table . '.*')->orderBy($this->table . ".id", "desc")
            ->get();
        $list->transform(function ($m) {
            $time = json_decode($m->time, true);
            $user_id = json_decode($m->user_id, true);
            $username = Member::where("id", "=", $user_id)->value("username");
            $username[2] = "*";
            $username[3] = "*";
            $username[4] = "*";
            $username[5] = "*";
            $username[6] = "*";
            $username[7] = "*";
            $username[8] = "*";
            $m->user_name = $username;
            $m->image = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . $m->image;
            $m->time = date("Y-m-d H:i:s", $time);
            return $m;
        });
//        var_dump(DB::getQueryLog());
        $data['list'] = $list;
        return response()->json(['status' => 1, 'data' => $data]);
    }

    //TODO 抽奖逻辑
    public function give_award(Request $request)
    {
//        $uid = 26025;
        $uid = $this->auth($request);
//        return response()->json(['status' => 1, 'msg' => $uid]);
        return is_numeric($uid) ? $this->suck_award($uid) : $uid;
        //TODO 提取随机100位用户
        //TODO 在100位里提取指定数量的有效用户
        //TODO 随机安排有效用户中奖，奖品随机
        /*$time = Seting::where("keyname", "=", "open_award_time")->value("value");
        $now = date("H:i");
        if ($now == $time) {
            $award_num = Seting::where("keyname", "=", "award_num")->value("value");
            echo " -e " . date("Y-m-d H:i:s") . " 开奖开始" . PHP_EOL;
            $list = DB::table("member")
                ->where("luckdraws", ">", 99.99)
                ->orderBy(DB::raw('RAND()'))
                ->limit($award_num)
                ->get();
            if ($list)
                $list = $list->toArray();
            if ($list) {
                $award_active_num = Seting::where("keyname", "=", "open_award_time")->value("value");
                $ids = array_keys($list);
                $active = array_rand($ids, $award_active_num);
                foreach ($list as $k => $v) {
                    $insert["prize_id"] = in_array($v["id"], $active) ? DB::table("award_prizes")->orderBy(DB::raw('RAND()'))->limit(1)->value("id") : 0;
                    $insert["user_id"] = $v["id"];
                    $insert["time"] = time();
                    $insert["give_time"] = time();
                    Member::where("id", "=", $v["id"])->decrement("luckdraws", 100);
                    DB::table("$insert")->insert($insert);
                }
            }
            echo " -e " . date("Y-m-d H:i:s") . " 开奖结束" . PHP_EOL;
        }
        exit(" -e " . date("Y-m-d H:i:s") . " 当前不是开奖时间" . PHP_EOL);*/
    }

    private function suck_award($uid)
    {
        //TODO 获取有库存的奖品信息
        $prizes = DB::table($this->prize_table)->where("stock", ">", 0)->orderBy($this->prize_table . ".odds", "desc")->get();
        $res = ["status" => 2, "msg" => "未中奖"];
        $prize = Member::where("id", "=", $uid)->value("luckdraws");
        $now_time = time();
        $last_award_time = Member::where("id", "=", $uid)->value("award_time");
        $timed = $now_time - $last_award_time;
        if ($timed < 3)
            $res["msg"] = "操作过于频繁,请{$timed}秒后再试";
        else {
            $state = Member::where("id", "=", $uid)->update(["award_time" => time()]);
            if ($prize < 100)
                $res["msg"] = "抽奖积分不足";
            else {
                $award_num = rand(1, 9999);
                if ($prizes) {
                    $prizes = $prizes->toArray();
                    foreach ($prizes as $k => $v) {
                        if ($v->odds > 0) {
                            if (($k == count($prizes) && $v->odds > $award_num) || ($k != count($prizes) && $v->odds > $award_num && $award_num > $prizes[$k + 1]->odds)) {
                                $v->image = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . $v->image;
                                $res["status"] = 1;
                                $res["msg"] = "已中奖";
                                $res["data"] = $v;
                                $insert["prize_id"] = $v->id;
                                $insert["user_id"] = $uid;
                                $insert["time"] = time();
                                $insert["give_time"] = time();
                                DB::table($this->prize_table)->where("id", "=", $v->id)->decrement("stock", 1);//TODO 减少奖品库存
                                DB::table($this->table)->insert($insert);//TODO 添加中奖记录
                                break;
                            }
                        }
                    }
                }
                Member::where("id", "=", $uid)->decrement("luckdraws", 100);//TODO 减少用户抽奖积分
            }
        }
        /*echo "当前中奖数是： " . $award_num . "<br />";
        echo "当前中奖状态： " . $msg . "<br />";
        var_dump($data);
        die;*/
        return response()->json($res);
    }

    //TODO 充值赠送抽奖积分逻辑
    public function buy_give_award($uid, $price)
    {
        //TODO 获取购物信息
        //TODO 获取购物赠送抽奖积分比例
        //TODO 根据购物金额按比例赠送抽奖积分
        $points = Seting::where("keyname", "=", "buy_points")->value("value");
        Member::where("id", "=", $uid)->increment("luckdraws", $price * $points);
    }

    //TODO 登录赠送抽奖积分逻辑(单日和连续登录)
    public function login_give_award($uid)
    {
        //TODO 每日首次登录即送每日登录抽奖积分
        //TODO 判断是否连续登录连续
        //TODO 如果是连续登录获取已连续天数
        //TODO 根据已连续天数和连续登录赠送抽奖积分比例赠送抽奖积分
        $where = [];
        $start_time = strtotime("midnight");
        $end_time = strtotime("next day midnight") - 1;
        $where[] = ["UNIX_TIMESTAMP(`created_at`)", '<', $start_time, 'and'];
        $where[] = ["UNIX_TIMESTAMP(`created_at`)", '>', $end_time, 'and'];
//        DB::enableQueryLog();
        /*UNIX_TIMESTAMP(created_at)
        DATE_FORMAT(created_at,"%Y-%m-%d")
        FROM_UNIXTIME
        FROM_UNIXTIME
        luckdraws
        increment
        decrement*/
        $member = Member::where("id", "=", $uid)->select(["give_lianxu", "give_login"])->first();
        if ($member)
            $member = $member->toArray();
        if (date("Ymd", $member["give_lianxu"]) < date("Ymd")) {
            $points = Seting::where("keyname", "=", "login_running_points")->value("value");
            if ($this->get_lianxu($uid)) {
                Member::where("id", "=", $uid)->increment("luckdraws", $this->get_lianxu($uid) * $points);
                Member::where("id", "=", $uid)->update(["give_lianxu" => time()]);
            }
        }
        if (date("Ymd", $member["give_login"]) < date("Ymd")) {
            $points = Seting::where("keyname", "=", "login_points")->value("value");
            $sql = "SELECT * FROM `memberlogs` WHERE UNIX_TIMESTAMP(created_at) > {$start_time} AND UNIX_TIMESTAMP(created_at) < {$end_time} ORDER BY `memberlogs`.`created_at` DESC";
            $login = DB::select($sql);
            if ($login) {
                Member::where("id", "=", $uid)->increment("luckdraws", $points);
                Member::where("id", "=", $uid)->update(["give_login" => time()]);
            }
        }
//        var_dump(DB::getQueryLog());
    }

    //TODO 邀请用户注册赠送抽奖积分逻辑
    public function invite_give_award($uid)
    {
        //TODO 邀请的用户注册赠送邀请人抽奖积分
        $points = Seting::where("keyname", "=", "invite_points")->value("value");
        Member::where("id", "=", $uid)->increment("luckdraws", $points);
        Member::where("id", "=", $uid)->update(["give_login" => time()]);
    }
}

?>