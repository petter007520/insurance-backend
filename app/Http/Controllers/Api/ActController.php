<?php

namespace App\Http\Controllers\Api;
use App\Advertisement;
use App\Advertisementdata;
use App\Auth;
use App\Category;
use App\Channel;
use App\Http\Controllers\Controller;
use App\Member;
use App\Memberlevel;
use App\Order;
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

class ActController extends Controller
{
    private $userInfo = false;
    private $user_id = false;

    public function __construct(Request $request)
    {
		$lastsession = $request->lastsession;
        if(!$lastsession){
            return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
        }else{
            $Member = Member::where("lastsession",$request->lastsession)->first();
            if (!$Member) {
                return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
            } else {
                $this->userInfo = $Member;
				$this->user_id = $Member->id;
            }
        }
	}

	//签到操作
	public function sign(Request $request){
		if (!$this->user_id) {
			return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
		}

		//查找签到记录，获取今天是否签到
		$sign_info_today = DB::table('act_sign')
						->orderBy('sign_time', 'desc')
						->whereDate('sign_date', '=', date('Y-m-d'))
						->where(['user_id' => $this->user_id])
						->first();
		if($sign_info_today) {
			return response()->json(["status"=>-1,"msg"=>"今天已经签到了"]);
		}

		//查找昨天是否签到
		$sign_info_yesterday = DB::table('act_sign')
						->orderBy('sign_time', 'desc')
						->whereDate('sign_date', '=', date('Y-m-d', strtotime("-1 day")))
						->where(['user_id' => $this->user_id])
						->first();

		if(!$sign_info_yesterday) {
			$days = 1;
			//$reward = $days * 10;
			$reward = 100;
			$data = [
				'user_id' => $this->user_id,
				'sign_date' => date('Y-m-d'),
				'sign_time' => time(),
				'sign_days' => $days,
				'reward' => $reward,
			];

		} else {
			$days = $sign_info_yesterday->sign_days + 1;
			//$reward = $days * 10;
			$reward = 100;
			$data = [
				'user_id' => $this->user_id,
				'sign_date' => date('Y-m-d'),
				'sign_time' => time(),
				'sign_days' => $days,
				'reward' => $reward,
			];
		}
		DB::table('act_sign')->insert($data);
		$this->change_score_by_user_id($this->user_id, $reward, 1, 1);
        return response()->json(["status"=>0,"msg"=>"签到成功", 'score' => $reward]);
    }

	//获取积分记录
	public function scoreLog() {
		if (!$this->user_id) {
			return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
		}
		$sign_logs = DB::table('act_score_log')
			->where(['user_id' => $this->user_id])
			->limit(100)
			->orderBy('id', 'desc')
			->get();

		foreach ($sign_logs as $sign_log){
			if ($sign_log->source_type == 1) {
				$sign_log->source_type_text = '签到';
			} else if ($sign_log->source_type == 2) {
				$sign_log->source_type_text = '抽奖';
			} else if ($sign_log->source_type == 3) {
				$sign_log->source_type_text = '登录';
			} else if ($sign_log->source_type == 4) {
				$sign_log->source_type_text = '连续登录';
			} else if ($sign_log->source_type == 5) {
				$sign_log->source_type_text = '购买产品';
			} else if ($sign_log->source_type == 6) {
				$sign_log->source_type_text = '邀请回归';
			} else {
				$sign_log->source_type_text = '未知，ID: ' . $sign_log->source_type;
			}
		}



		return response()->json(["status"=>0,"msg"=>"", 'score' => $sign_logs]);
	}


	//用户积分变化
	public function change_score_by_user_id($user_id, $score , $type, $source_type) {
		if ($type == 1) {
			Member::where("id", "=", $user_id)->increment("score", $score);
		} else if ($type == 2) {
			Member::where("id", "=", $user_id)->decrement("score", $score);
		} else {
			return false;
		}

		$data = [
			'user_id' => $user_id,
			'amount' => $score,
			'type' => $type,
			'source_type' => $source_type,
			'time' => time(),
		];
		DB::table('act_score_log')->insert($data);
		return true;
	}

	//获取奖品列表
	public function rewardList() {
		if (!$this->user_id) {
			return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
		}
		$rewards_lists = DB::table('act_rewards')
			->where(['disabled' => 0])
			->select('id', 'name', 'img')
			->get();

//		$domain = DB::table("setings")->where('keyname','invite_link')->value("value");
        $domain = env('FILE_URL');
		foreach($rewards_lists as $rewards_list) {
			$rewards_list->img = $domain . $rewards_list->img;
		}

		return response()->json(["status"=>0,"msg"=>"", 'rewards' => $rewards_lists]);
	}

	//抽奖
	public function lottory(Request $request) {
		if (!$this->user_id) {
			return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
		}
         $Member1 = Member::find($this->user_id);

		$lottory_score = 1000;

		if ($this->userInfo->score < $lottory_score) {
			return response()->json(["status"=>-1,"msg"=>"积分不足，1000积分才可以抽奖！"]);
		}
        $nowdate = date('Y-m-d');
//        $loginfo = DB::table('act_rewards_log')
//        ->where('user_id', $this->user_id)
//            ->where('reward_date', $nowdate)
//            ->first();
//        if(!empty($loginfo)){
//            return response()->json(["status"=>-1,"msg"=>"今天已经抽过奖！"]);
//        }
		//查询是否有设置的中奖记录
		$rewards_pre = DB::table('act_rewards_log')
			->where(['pre' => 1])
			->where(['user_id' => $this->user_id])
			->first();

		if ($rewards_pre) {
			$data = [
				'id' => $rewards_pre->id,
				'user_id' => $this->user_id,
				'reward_id' => $rewards_pre->reward_id,
				'reward_name' => $rewards_pre->reward_name,
				'reward_time' => time(),
				'reward_date' => date('Y-m-d'),
				'pre' => 0,
			];
			DB::table('act_rewards_log')
			->where('id', $rewards_pre->id)
			->update($data);

			unset($data['pre']);
			//扣除积分
			$this->change_score_by_user_id($this->user_id, $lottory_score, 2, 2);
			return response()->json(["status"=>0,"msg"=>"", 'rewards' => $data]);
		} else {
			$rewards_lists = DB::table('act_rewards')
				->where(['disabled' => 0])
				->where('stock', '>', 0)
				->select('id', 'name', 'img', 'ratio', 'money', 'score')
				->get();
			$rewards_lists = json_decode(json_encode($rewards_lists), true);
			if (count($rewards_lists) == 0) {
				return response()->json(["status"=>-1,"msg"=>"活动暂停"]);
			}

			$rid = 0;
			$name = '';
			$money = 0;
			$score = 0;
			$weight = 0;
			foreach ($rewards_lists as $val) {
				$weight += $val['ratio']; //概率数组的总概率精度
			}

			if ($weight == 0) {
				return response()->json(["status"=>-1,"msg"=>"活动暂停"]);
			}


			shuffle($rewards_lists);
			foreach ($rewards_lists as $key => $value) {
				$randNum = mt_rand(1, $weight);
				if ($randNum <= $value['ratio']) {
					$rid = $value['id'];
					$name = $value['name'];
					$money = $value['money'];
					$score = $value['score'];
					break;
				} else {
					$weight -= $value['ratio'];
				}
			}

			$data = [
				'user_id' => $this->user_id,
				'reward_id' => $rid,
				'reward_name' => $name,
				'reward_time' => time(),
				'reward_date' => date('Y-m-d'),
			];

			$data['virtual'] = ($money > 0 || $score > 0) ? true : false;
			DB::table('act_rewards')->where(['id' => $rid])->decrement('stock', 1);;
			$res = DB::table('act_rewards_log')->insertGetId($data);
			$data['id'] = $res;

			if ($money > 0) {
				$Member = Member::find($this->user_id);
				$amount = $Member->ktx_amount;
				$Member->increment('ktx_amount', $money);
				$Member->save();
				$log = [
					"userid" => $this->user_id,
					"username" => $this->userInfo->username,
					"money" => $money,
					"notice" => "抽奖获得",
					"type" => "抽奖",
					"status" => "+",
					"yuanamount" => $amount,
					"houamount" =>$Member->ktx_amount,
					"ip" => \Request::getClientIp(),
					"recharge_id"=>0,
				];
				\App\Moneylog::AddLog($log);
			}

			if ($score > 0) {
				$this->change_score_by_user_id($this->user_id, $score, 1, 2);
			}
            $Member = Member::find($this->user_id);
             if($Member->rw_level>=4){
                 $Member->increment('cj_num', 1);
            }

            $cj_num = DB::table("setings")->where('keyname','cj_num')->value('value');//抽奖次数

            if($Member->rw_level ==4 && $Member->cj_num >=$cj_num){
                $Member= Member::where('state',1)->find($this->user_id);
                $Member->rw_level =5;
                $Member->save();
                $lx_qd = DB::table("setings")->where('keyname','lx_qd')->value('value');//联系签到
                 if($Member->rw_level ==5 && $Member->lx_qd >=$lx_qd){
                     $Member->rw_level = 6;
                     $Member->save();
                 }
            }
			//扣除积分
			$this->change_score_by_user_id($this->user_id, $lottory_score, 2, 2);
			return response()->json(["status"=>0,"msg"=>"", 'rewards' => $data]);
		}




	}


	//获取中奖列表
	public function lottoryLog() {
		if (!$this->user_id) {
			return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
		}
		$lottory_logs = DB::table('act_rewards_log')
			->join('member', 'member.id', '=', 'act_rewards_log.user_id')
			->where(['act_rewards_log.pre' => 0])
			->where('act_rewards_log.reward_id', '>', 0)
			->select('member.username', 'act_rewards_log.reward_name', 'act_rewards_log.reward_time')
			->limit(50)
			->orderBy('act_rewards_log.id', 'desc')
			->get();

		$data = [];
		foreach($lottory_logs as $k => $v) {
			$v->username = substr_replace($v->username, '****', 3, 4);
			$v->reward_time = date('Y-m-d H:i:s', $v->reward_time);
		}
		return response()->json(["status"=>0,"msg"=>"", 'data' => $lottory_logs]);
	}
	//我的中奖列表
	public function MyLottoryLog() {
		if (!$this->user_id) {
			return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
		}
		$lottory_logs = DB::table('act_rewards_log')
			->join('member', 'member.id', '=', 'act_rewards_log.user_id')
			->where(['act_rewards_log.pre' => 0])
			->where('act_rewards_log.reward_id', '>', 0)
			->where(['act_rewards_log.user_id' => $this->user_id])
			->select('member.username', 'act_rewards_log.id', 'act_rewards_log.reward_id', 'act_rewards_log.reward_name', 'act_rewards_log.reward_time', 'act_rewards_log.virtual', 'act_rewards_log.address', 'act_rewards_log.realname', 'act_rewards_log.mobile')
			->limit(50)
			->orderBy('act_rewards_log.id', 'desc')
			->get();

		$data = [];
		foreach($lottory_logs as $k => $v) {
			$v->username = substr_replace($v->username, '****', 3, 4);
			$v->reward_time = date('Y-m-d H:i:s', $v->reward_time);
			$v->virtual = $v->virtual == 0 ? false : true;
		}
		return response()->json(["status"=>0,"msg"=>"", 'data' => $lottory_logs]);
	}

	public function getuserscore(){
		if (!$this->user_id) {
			return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
		}
		$nowdate = date('Y-m-d');
        /*$loginfo = DB::table('act_rewards_log')
        ->where('user_id', $this->user_id)
            ->where('reward_date', $nowdate)
            ->first();
            $cjnum = 1;
        if(!empty($loginfo)){
            $cjnum=0;
        }*/
		$Member = Member::find($this->user_id);
//		$cjnum = $Member->cj_num;
        $cjnum = intval($Member->score / 1000);
		$cjgtfee = DB::table('setings')->where(['keyname'=>'cj_gtfee'])->value('value');//注册赠送金额
		return response()->json(["status"=>0,"msg"=>"", 'data' => ['mobile' => $this->userInfo->username,'nickname' => $this->userInfo->nickname,'user_id' => $this->user_id,'score' => $this->userInfo->score,'cjnum'=>$cjnum,'cjgtfee'=>$cjgtfee]]);
	}

	public function updateUserAddress(Request $request){
		if (!$this->user_id) {
			return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
		}
		if (!$request->id || !$request->realname || !$request->mobile || !$request->address) {
			return response()->json(["status"=>-1,"msg"=>"缺少参数！"]);
		}

		$reward_log = DB::table('act_rewards_log')
			->where('id', $request->id)
			->where(['pre' => 0])
			->where('reward_id', '>', 0)
			->first();
		if (!$reward_log) {
			return response()->json(["status"=>-1,"msg"=>"没中奖不能填写"]);
		}

		$data = [
			'id' => $request->id,
			'realname' => strip_tags($request->realname),
			'mobile' => strip_tags($request->mobile),
			'address' => strip_tags($request->address),
		];
		DB::table('act_rewards_log')
			->where('id', $request->id)
			->update($data);

		return response()->json(["status"=>0,"msg"=>"成功"]);
	}

	public function updateAddres(Request $request){
		if (!$this->user_id) {
			return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
		}
		if (!$request->realname || !$request->mobile || !$request->address) {
			return response()->json(["status"=>-1,"msg"=>"缺少参数！"]);
		}

		$reward_log = DB::table('memberaddress')
			->where('userid', $this->user_id)
			->first();
		if (!$reward_log) {
			$data["userid"] =  $this->user_id;
			$data["receiver"] =  $request->realname;
			$data["mobile"] =  $request->mobile;
			$data["address"] =  $request->address;
			$res = DB::table('memberaddress')->insert($data);
			if($res){
				return response()->json(["status"=>0,"msg"=>"成功"]);
			}else{
				return response()->json(["status"=>-1,"msg"=>"添加失败！"]);
			}

		}else{
		    return response()->json(["status"=>-1,"msg"=>"请联系管理员修改！"]);
			$data["userid"] =  $this->user_id;
			$data["receiver"] =  $request->realname;
			$data["mobile"] =  $request->mobile;
			$data["address"] =  $request->address;
			$res = DB::table('memberaddress')
				->where('userid', $this->user_id)
				->update($data);
			if($res){
				return response()->json(["status"=>0,"msg"=>"成功"]);
			}else{
				return response()->json(["status"=>-1,"msg"=>"修改失败！"]);
			}
		}
		//return response()->json(["status"=>0,"msg"=>"成功"]);
	}

	public function Addresinfo(Request $request){
		if (!$this->user_id) {
			return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
		}
		$reward_log = DB::table('memberaddress')
			->where('userid', $this->user_id)
			->first();
		return response()->json(["status"=>0,"msg"=>"", 'data' => $reward_log]);
	}
}
?>
