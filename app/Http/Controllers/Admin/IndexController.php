<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Article;
use Cache;
use DB;


class IndexController extends BaseController
{
   // protected $DOM;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        //$this->DOM = new Article();
    }




    //GET 首页
    public function index(Request $request){

      //echo   Smstmp::GetMsg(["type"=>"regcode","code"=>"8866"]);
      //dd(Payment::GetPayment());
       //echo \App\Formatting::Format('aadslfkasasdf老家撒地方{CompanyShort}艺术硕士艺术硕士');
        return $this->ShowTemplate();

    }

    //GET 欢迎页
    public function main(Request $request){

        $laravel = app();
        $today_date = date('Y-m-d');
        // $yesterday_date = date('Y-m-d',strtotime("-1 day"));
        
        $statistics_sys = DB::table('statistics_sys')->first();
        $today_members= DB::table('member')->where('created_date',$today_date)->count();
        $yesterday_members= $statistics_sys->yesterday_user_num;
        $members= $statistics_sys->user_num;//会员总数
        $amount_released_today =  DB::table('moneylog')->where(['moneylog_type'=>'项目分红','created_date'=>$today_date])->sum('moneylog_money');//今日释放金额
        $amount_released_yesterday = $statistics_sys->yesterday_release_amount;//昨日释放金额
        $total_release = $statistics_sys->release_amount;//释放总额
        $amount_withdrawn_approved_today = DB::table('memberwithdrawal')->where(['created_date'=>$today_date,'status'=>1])->sum('amount');//今日提现已审核金额
        $amount_withdrawn_yesterday = $statistics_sys->yesterday_withdrawal_amount;//昨日提现金额
        $total_withdrawal = $statistics_sys->withdrawal_amount;//提现总额
        $recharge_amount_today = DB::table('productbuy')->where(['created_date'=>$today_date,'status'=>1])->sum('amount');//今日充值金额
        $yesterday_recharg_amount = $statistics_sys->yesterday_buy_amount;//昨日充值金额
        $total_recharge = $statistics_sys->buy_amount;//充值总额

		$today = date("Y-m-d");
		
		
		
        $total_11_cnt = DB::table('productbuy')
			->where(['category_id'=> 11])
			->where(['status'=> 1])
			->where(['created_date'=> $today])
			->get();
		$total_11_user = [];
		foreach ($total_11_cnt as $v) {
			if (!isset($total_11_user[$v->userid])) {
				$has_record = DB::table('productbuy')
					->where(['category_id'=> 11])
					->where(['status'=> 1])
					->where('created_at', '<', $today)
					->where(['userid'=> $v->userid])
					->first();
				if (!$has_record) {
					$total_11_user[$v->userid] = 1;
				}
			}
		}
		

		$total_12_cnt = DB::table('productbuy')
			->where(['category_id'=> 12])
			->where(['status'=> 1])
			->where(['created_date'=> $today])
			->get();
		$total_12_user = [];
		foreach ($total_12_cnt as $v) {
			if (!isset($total_12_user[$v->userid])) {
				$has_record = DB::table('productbuy')
					->where(['category_id'=> 12])
					->where(['status'=> 1])
					->where('created_at', '<', $today)
					->where(['userid'=> $v->userid])
					->first();
				if (!$has_record) {
					$total_12_user[$v->userid] = 1;
				}
			}
		}
		
		
		$total_13_cnt = DB::table('productbuy')
			->where(['category_id'=> 13])
			->where(['status'=> 1])
			->where(['created_date'=> $today])
			->get();
		$total_13_user = [];
		foreach ($total_13_cnt as $v) {
			if (!isset($total_13_user[$v->userid])) {
				$has_record = DB::table('productbuy')
					->where(['category_id'=> 13])
					->where(['status'=> 1])
					->where('created_at', '<', $today)
					->where(['userid'=> $v->userid])
					->first();
				if (!$has_record) {
					$total_13_user[$v->userid] = 1;
				}
			}
		}
		
		
		$total_all_cnt = DB::table('productbuy')
			->where(['status'=> 1])
			->where(['created_date'=> $today])
			->get();
		$total_all_user = [];
		foreach ($total_all_cnt as $v) {
			if (!isset($total_all_user[$v->userid])) {
				$has_record = DB::table('productbuy')
					->where(['status'=> 1])
					->where('created_at', '<', $today)
					->where(['userid'=> $v->userid])
					->first();
				if (!$has_record) {
					$total_all_user[$v->userid] = 1;
				}
			}
		}
        

        return $this->ShowTemplate([
            "today_members"=>$today_members,
            "members"=>$members,
            "yesterday_members"=>$yesterday_members,
            "amount_released_today"=>sprintf("%.2f",$amount_released_today),
            "amount_released_yesterday"=>sprintf("%.2f",$amount_released_yesterday),
            "total_release"=>sprintf("%.2f",$total_release),
            "amount_withdrawn_approved_today"=>sprintf("%.2f",$amount_withdrawn_approved_today),
            "amount_withdrawn_yesterday"=>sprintf("%.2f",$amount_withdrawn_yesterday),
            "total_withdrawal"=>sprintf("%.2f",$total_withdrawal),
            "recharge_amount_today"=>sprintf("%.2f",$recharge_amount_today),
            "yesterday_recharg_amount"=>sprintf("%.2f",$yesterday_recharg_amount),
            "total_recharge"=>sprintf("%.2f",$total_recharge),
            "laravel"=>$laravel,
            "now"=>Carbon::now(),
            'memberlevel'=>'',
            "total_11_cnt"=>count($total_11_user),
            "total_12_cnt"=>count($total_12_user),
            "total_13_cnt"=>count($total_13_user),
            "total_all_cnt"=>count($total_all_user)
        ]);

    }

    //清空缓存
    public function CacheFlush(){
        Cache::flush();
        return ["status" => 0, "msg" => "缓存清空"];
    }


    public function msgconut(Request $request){

        $msgconuts= DB::table("onlinemsg")->where("status","0")->count();
        $rechargeconuts= DB::table("memberrecharge")->where("status","0")->count();
        $withdrawalconuts= DB::table("memberwithdrawal")->where("status","0")->count();
        $productbuyconuts= DB::table("productbuy")->where("status","2")->count();


        return response()->json([
            "msgconuts"=>$msgconuts,
            "msginfo"=>"未读投诉建议({$msgconuts})",

            "rs"=>$rechargeconuts,
            "rsinfo"=>"未处理充值({$rechargeconuts})",

            "ws"=>$withdrawalconuts,
            "wsinfo"=>"未处理提现({$withdrawalconuts})",
            "conuts"=>($msgconuts+$withdrawalconuts+$rechargeconuts),

            "ps"=>$productbuyconuts,
            "psinfo"=>"未处理订单({$productbuyconuts})",

            "playSound"=>Cache::get('playSound')
        ]);

    }

}
