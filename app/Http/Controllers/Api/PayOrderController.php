<?php

namespace App\Http\Controllers\Api;

use App\Jobs\CollisionReward;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Memberticheng;
use App\Member;

class PayOrderController extends Controller
{
    /**
     * 订单支付完成(余额|银联|三方)
     * @param $order_id
     */
    public function third_pay_finish_payment($order_id): array
    {
        DB::beginTransaction();
        try {
            $pro_buy_data = DB::table("productbuy")
                ->select('id', 'userid', 'username', 'productid', 'status', 'num', 'amount','real_amount', 'pay_status', 'category_id', 'ip','pay_type','type','before_order_id','created_at')
                ->where(['id' => $order_id])->first();
            if (!$pro_buy_data) {
                Log::channel('pay')->warning('查无订单(' . $order_id . ')');
                return ["status" => 0, "msg" => "查无订单!"];
            }
            if( $pro_buy_data->status!=1){
                Log::channel('pay')->warning('该订单还未完成支付('.$order_id.')');
                return ["status"=>0,"msg"=>"该订单还未完成支付"];
            }
            $Member = Member::where(['id' => $pro_buy_data->userid])->first(['id', 'username','nickname', 'amount', 'level','status','invite_uid']);
            $product = DB::table("products")
                ->select('id', 'title', 'category_id','rebate_type', 'collision_times','is_rebate','wealth_rate')
                ->where(['id' => $pro_buy_data->productid])
                ->first();

            // 可获得对碰奖励金额  | 购买产品,用户激活
            DB::table("member")->where(['id' => $Member->id])->update(['status'=>1,'collision_amount'=>$pro_buy_data->amount * $product->wealth_rate]);

            //流水统计金额
            $capital_flow = $pro_buy_data->real_amount;
            //添加个人统计
            DB::table('statistics')->where('user_id', $Member->id)->increment('capital_flow', $capital_flow);
            //添加后台统计
            DB::table('statistics_sys')->where('id', 1)->increment('buy_amount', $capital_flow);

            $is_return = false;
            if ($product->is_rebate ==1 && (($pro_buy_data->pay_type == 1 && in_array($product->rebate_type, [1, 3])) || (in_array($pro_buy_data->pay_type, [2, 3, 4]) && in_array($product->rebate_type, [1, 2])))) {
                $is_return = true;
            }
            //日志记录
            if(in_array($pro_buy_data->pay_type,[2,3,4])){
                $type_title = '';
                if($pro_buy_data->pay_type == 2){
                    $type_title = '购买财富,银联付款';
                }
                if($pro_buy_data->pay_type == 5){
                    $type_title = '购买财富,USDT付款';
                }
                if(in_array($pro_buy_data->pay_type,[3,4])){
                    $type_title = '购买财富,线上支付';
                }
                //购买产品日志
                $log=[
                    "userid"=>$Member->id,
                    "username"=>$Member->username,
                    "money"=>$pro_buy_data->real_amount,
                    "notice"=>"购买财富(".$product->title.")",
                    "type"=>$type_title,
                    "status"=>"-",
                    "yuanamount"=>$Member->amount,
                    "houamount"=>$Member->amount,
                    "ip"=>$pro_buy_data->ip,
                    "category_id"=>$product->category_id,
                    "product_id"=>$product->id,
                    "product_title"=>$product->title,
                    'num'=>$pro_buy_data->num,
                    'moneylog_type_id'=>'2',
                ];
                \App\Moneylog::AddLog($log);
            }
            if ($is_return) {
                //当前用户上家
                $Tichengs = Memberticheng::orderBy("id", "asc")->get();//percent提成比例
                $buyman = $Member->username;
                $now_time = Carbon::now();
                $Member->username = substr_replace($Member->username, '****', 3, 5);
                $invite_uid = $Member->invite_uid;
                foreach ($Tichengs as $recent) {
                    $ShangjiaMember = Member::where(['id'=>$invite_uid])->first();//直推上级
                    if ($ShangjiaMember) {
                        $has_log = DB::table('moneylog')->select('id')->where(['moneylog_userid' => $ShangjiaMember->id, 'from_uid' => $Member->id, 'from_uid_buy_id' => $order_id])->first();
                        if ($has_log) {
                            break;
                        }
                        //分成钱数
                        $rateMoney = intval($pro_buy_data->real_amount * $recent->percent / 100);
                        // 计算本次分成真实金额
                        $rewardMoney = self::get_real_amount($ShangjiaMember->id, $rateMoney);
                        if($rewardMoney > 0) {
                            $title = "尊敬的{$ShangjiaMember->username}会员您好！您的{$recent->name}分成已到账";
                            $content = "直推下线{$buyman}购买项目成功,{$rewardMoney}元已赠送到您的账号,当前的提成比例为" . $recent->percent . "%";
                            //站内消息
                            $msg = [
                                "userid" => $ShangjiaMember->id,
                                "username" => $ShangjiaMember->username,
                                "title" => $title,
                                "content" => $content,
                                "from_name" => "系统通知",
                                "types" => "下线购买分成",
                            ];
                            \App\Membermsg::Send($msg);

                            $MOamount = $ShangjiaMember->ktx_amount;

                            $ShangjiaMember->increment('ktx_amount', $rewardMoney);
                            $notice = "下线(" . $Member->username . ")购买(" . $product->title . ")返佣";
                            $log = [
                                "userid" => $ShangjiaMember->id,
                                "username" => $ShangjiaMember->username,
                                "money" => $rewardMoney,
                                "notice" => $notice,
                                "type" => "直推返佣",
                                "status" => "+",
                                "yuanamount" => $MOamount,
                                "houamount" => $ShangjiaMember->ktx_amount,
                                "ip" => $pro_buy_data->ip,
                                "category_id" => $product->category_id,
                                "product_id" => $product->id,
                                "from_uid" => $Member->id,
                                "from_uid_buy_id" => $order_id,
                                'moneylog_type_id' => '5',
                            ];
                            \App\Moneylog::AddLog($log);

                            $data = [
                                "userid" => $ShangjiaMember->id,
                                "username" => $ShangjiaMember->username,
                                "xxuserid" => $Member->id,
                                "xxusername" => $Member->username,
                                "amount" => $pro_buy_data->amount,
                                "preamount" => $rewardMoney,
                                "type" => "直推返佣",
                                "status" => "1",
                                "xxcenter" => $recent->name,
                                "created_at" => $now_time,
                                "updated_at" => $now_time,
                            ];
                            DB::table("membercashback")->insert($data);
                        }
                        $invite_uid = $ShangjiaMember->invite_uid;
                    }
                }
            }

            //购买累计进入总金额
            $Nowmember = Member::find($Member->id);
            // 异步处理双区对碰奖励
            dispatch(new CollisionReward($Nowmember, $pro_buy_data->real_amount, $product))->onQueue('collisionReward');
            //原订单结束
            if($pro_buy_data->type == 2 && $pro_buy_data->before_order_id > 0){
                DB::table('productbuy')->where(['id'=>$pro_buy_data->before_order_id])->update(['status'=>0,'reason'=>'财富等级升级，本订单关闭，新订单ID-'.$order_id]);
            }
            $wealth_data = [
                'name'=>$Member->nickname,
                'user_id'=>$Member->id,
                'time'=>$pro_buy_data->created_at,
                'order_id'=>$pro_buy_data->id
            ];
            DB::table('product_wealth')->insert($wealth_data);
            DB::commit();
            return ['status' => 1, 'msg' => '订单完成'];
        }catch (\Exception $e){
            Log::channel('buy')->alert($e->getMessage());
            DB::rollBack();
            return ['status' => 0, 'msg' => $e->getMessage()];
        }
    }

    public static function get_real_amount($user_id,$amount){
        $user_is_out = false;
        // 查看当前用户本次应得返佣(已激活用户才能获得)
        $user = DB::table('member')->where(['id'=>$user_id,'status'=>1])->first(['id','status','username','collision_amount','collision_amount_finsh']);
        if(!$user){
           return 0;
        }
        // 已获得金额大于或者等于应得金额，直接标记出局
        if($user->collision_amount <= $user->collision_amount_finsh){
            //消息内容
            $content = '您已拿满本局奖励，本轮已出局';
            //站内消息
            $msg = [
                "userid" => $user->id,
                "username" => $user->username,
                "title" => '出局通知',
                "content" => $content,
                "from_name" => "系统通知",
                "types" => "下线购买分成",
            ];
            \App\Membermsg::Send($msg);
            // 用户出局，所有订单标记已结束
            self::finish_order($user_id);
            return 0;
        }
        //应得金额小于还可获得金额。用户获得本次应得金额
        if(($user->collision_amount - $user->collision_amount_finsh) > $amount){
            DB::table('member')->where(['id'=>$user_id])->increment('collision_amount_finsh',$amount);
            return $amount;
        }
        //应得金额等于还可获得金额，拿完本次，用户出局
        if(($user->collision_amount - $user->collision_amount_finsh) == $amount){
            //消息内容
            $content = '您已拿满本局奖励，本轮已出局';
            //站内消息
            $msg = [
                "userid" => $user->id,
                "username" => $user->username,
                "title" => '出局通知',
                "content" => $content,
                "from_name" => "系统通知",
                "types" => "下线购买分成",
            ];
            \App\Membermsg::Send($msg);
            // 用户出局，所有订单标记已结束
            self::finish_order($user_id);
            return $amount;
        }
        // 应得金额大于还可获得金额，只给用户可获得金额
        if(($user->collision_amount - $user->collision_amount_finsh) < $amount){
            $money = $user->collision_amount - $user->collision_amount_finsh;
            //消息内容
            $content = '您已拿满本局奖励，本轮已出局(本次应获得'.$amount.'元,实得'.$money.'元)';
            //站内消息
            $msg = [
                "userid" => $user->id,
                "username" => $user->username,
                "title" => '出局通知',
                "content" => $content,
                "from_name" => "系统通知",
                "types" => "下线购买分成",
            ];
            \App\Membermsg::Send($msg);
            // 用户出局，所有订单标记已结束
            self::finish_order($user_id);
            return $money;
        }
        return 0;
    }

    private static function finish_order($user_id){
        //收益订单结束
        DB::table('productbuy')->where(['userid'=>$user_id])->update(['status'=>0,'reason'=>'用户出局，订单结束']);
        //标记出局
        DB::table('member')->where(['id'=>$user_id])->update(['status'=>2,'collision_amount'=>0,'collision_amount_finsh'=>0]);
    }

    private function get_random_code($num)
    {
        $code_seed = "1234567890";
        $len = strlen($code_seed);
        $ban_num = ($num/2)-3;
        $code = "";
        for ($i = 0; $i < $num; $i++) {
            $rand = rand(0, $len - 1);
            if($i == $ban_num){
                $code .= 'O';
            }else{
                $code .= $code_seed[$rand];
            }
        }
        return $code;
    }
}
