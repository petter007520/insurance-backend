<?php


namespace App\Http\Controllers\Admin;
    use App\Member;
    use App\Memberlevel;
    use App\Product;
    use App\statistics;
    use App\Stproductbuy;
    use App\Category;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Http\Request;
    use Session;
    use Cache;
    use App\Membercurrencys;
    use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\App;


class StproductbuyController extends BaseController
{

    private $table="stproductbuy";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Stproductbuy();

       /* $Products= Product::get();
        foreach ($Products as $Product){
            $this->Products[$Product->id]=$Product;
        }

        $this->CategoryModel=new Category();
        $category_id=$request->s_categoryid;
        view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$category_id,0,'products'));
*/
      /*$Memberlevels= Memberlevel::get();

        foreach ($Memberlevels as $Memberlevel){
            $this->Memberlevels[$Memberlevel->id]=$Memberlevel;
        }

				$totalAmount = DB::table($this->table)
                    ->where(['status'=>1])
                    // ->where(['category_id'=>13,'status'=>1])
                    ->where(function ($query) {
                        $s_siteid=[];
                        if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                            $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                        }

                        $query->where($s_siteid);
                    })
                    ->where(function ($query) {
                        $date_s=[];
                        if(isset($_REQUEST['date_s']) && $_REQUEST['date_s']!=''){

                            $query->whereDate("useritem_time",">=",$_REQUEST['date_s']." 00:00:00");
                        }
                    })
                    ->where(function ($query) {
                        $date_s=[];
                        if(isset($_REQUEST['date_e']) && $_REQUEST['date_e']!=''){

                            $query->whereDate("useritem_time","<=",$_REQUEST['date_e']." 23:59:59");
                        }
                    })
                    ->where(function ($query) {
                        $s_categoryid=[];
                        if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']>0){
                            $s_categoryid[]=[$this->table.".category_id","=",$_REQUEST['s_categoryid']];
                        }

                        $query->where($s_categoryid);
                    })
                    ->where(function ($query) {
                        $s_status=[];
                        if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                            $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                        }

                        $query->where($s_status);
                    })
                    ->sum('amount');

        view()->share("totalAmount",$totalAmount);

        $startdata = date('Y-m-d 00:00:00', time());
        $enddata = date('Y-m-d 23:59:59', time());


        $today_amount= DB::table($this->table)
                    // ->where(['category_id'=>13])
                    ->where(function ($query) {
                        if(!isset($_REQUEST['date_s'])){
                             $query->where('updated_at','>=',date('Y-m-d 00:00:00', time()))
                             ->where('updated_at','<=',date('Y-m-d 23:59:59', time()));
                        }

                    })
                    // ->where('updated_at','>=',$startdata)
                    // ->where('updated_at','<=',$enddata)
                    ->where(function ($query) {
                        $s_siteid=[];
                        if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                            $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                        }

                        $query->where($s_siteid);
                    })
                    ->where(function ($query) {
                        $date_s=[];
                        if(isset($_REQUEST['date_s']) && $_REQUEST['date_s']!=''){

                            $query->whereDate("useritem_time",">=",$_REQUEST['date_s']." 00:00:00");
                        }
                    })
                    ->where(function ($query) {
                        $date_s=[];
                        if(isset($_REQUEST['date_e']) && $_REQUEST['date_e']!=''){

                            $query->whereDate("useritem_time","<=",$_REQUEST['date_e']." 23:59:59");
                        }
                    })
                    ->where(function ($query) {
                        $s_categoryid=[];
                        if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']>0){
                            $s_categoryid[]=[$this->table.".category_id","=",$_REQUEST['s_categoryid']];
                        }

                        $query->where($s_categoryid);
                    })
                    ->where(function ($query) {
                        $s_status=[];
                        if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                            $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                        }

                        $query->where($s_status);
                    })
					->where(function ($query) {
						$s_pay_type=[];
						if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']!=''){
							$s_pay_type[]=[$this->table.".pay_type","=",$_REQUEST['s_pay_type']];
						}

						$query->where($s_pay_type);
					})
                    ->sum('amount');

        view()->share("today_amount",$today_amount);

        $today_amount_ok= DB::table($this->table)
                    // ->where(['category_id'=>13,'status'=>1])
                    ->where(['status'=>1])
                    ->where(function ($query) {
                        if(!isset($_REQUEST['date_s'])){
                             $query->where('updated_at','>=',date('Y-m-d 00:00:00', time()))
                             ->where('updated_at','<=',date('Y-m-d 23:59:59', time()));
                        }

                    })
                    // ->where('updated_at','>=',$startdata)
                    // ->where('updated_at','<=',$enddata)
                    ->where(function ($query) {
                        $s_siteid=[];
                        if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                            $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                        }

                        $query->where($s_siteid);
                    })
                    ->where(function ($query) {
                        $date_s=[];
                        if(isset($_REQUEST['date_s']) && $_REQUEST['date_s']!=''){

                            $query->whereDate("useritem_time",">=",$_REQUEST['date_s']." 00:00:00");
                        }
                    })
                    ->where(function ($query) {
                        $date_s=[];
                        if(isset($_REQUEST['date_e']) && $_REQUEST['date_e']!=''){

                            $query->whereDate("useritem_time","<=",$_REQUEST['date_e']." 23:59:59");
                        }
                    })
                    ->where(function ($query) {
                        $s_categoryid=[];
                        if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']>0){
                            $s_categoryid[]=[$this->table.".category_id","=",$_REQUEST['s_categoryid']];
                        }

                        $query->where($s_categoryid);
                    })
                    ->where(function ($query) {
                        $s_status=[];
                        if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                            $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                        }

                        $query->where($s_status);
                    })
					->where(function ($query) {
						$s_pay_type=[];
						if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']!=''){
							$s_pay_type[]=[$this->table.".pay_type","=",$_REQUEST['s_pay_type']];
						}

						$query->where($s_pay_type);
					})
                    ->sum('amount');*/

      //  view()->share("today_amount_ok",$today_amount_ok);
    }



    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }




    public function lists(Request $request){


     $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }







        $listDB = DB::table($this->table)
            ->select($this->table.'.*')
          ->where(function ($query) {
                $s_status=[];
                if(isset($_REQUEST['username']) && $_REQUEST['username']!=''){
                    $s_status[]=[$this->table.".username","=",$_REQUEST['username']];
                }

                $query->where($s_status);
            })
            ->where(function ($query) {
                $s_status=[];
                if(isset($_REQUEST['pay_type']) && $_REQUEST['pay_type']!=''){
                    $s_status[]=[$this->table.".pay_type","=",$_REQUEST['pay_type']];
                }

                $query->where($s_status);
            })
            ;

            $list=$listDB->orderBy($this->table.".id","desc")
            ->paginate($pagesize);





        if($request->ajax()){
            if($list){
                $model=config('model');
                $modelname=[];
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{

           // echo "122222222222";
           // var_dump($list);

            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }
    }

    public function store(Request $request){



    }






    public function update(Request $request)
    {
        if($request->isMethod("post")){
           // echo $request->get('id');
            $Model = $this->Model::find($request->get('id'));
           // var_dump($Model->stproductid);
            $products_info = DB::table("stproduct")->where(['id'=>$Model->stproductid])->first();
            $products_title = $products_info->name;
            $Member= Member::where('state',1)->find($Model->userid);
            $now_time = Carbon::now();
            if($request->status!='8' && $request->status!='9'){
                if($Model->status==2){
                    DB::beginTransaction();
                //    try{
                        if($request->status=='0'){

                            //当前产品信息
                            $buy_product_info = DB::table("stproduct")
                                    ->where(['id'=>$Model->stproductid])
                                    ->first();
                                    $product = $buy_product_info;
                          //  var_dump($buy_product_info);
                            $has_zs = DB::table('stproductbuy')->where(['id'=>$Model->id])->first();

                            //当前统计时间
                            $now_statistics_date = date('Y-m-d',time());
                            $capital_flow = $Model->fee;
                            $repeat = 0;
                            $UserId = $Model->userid;
                            //赠送产品
                           $integrals=$buy_product_info->fee;


                            //添加统计表&是否满足额外分红

                            // DB::table('statistics')->where('user_id',$UserId)->increment('capital_flow',$capital_flow);
                            //添加后台统计
                            DB::table('statistics_sys')->where('id',1)->increment('buy_amount',$capital_flow);
                            //统计表end

							$is_return = true;


							if ($is_return) {//上级 是否满足团队奖励
                                // $shangji_id = DB::table('membergrade')->where(['uid'=>$Member->id,'level'=>1])->value('pid');
                                $shangji_id = $Member->top_uid;
                                $sshangji_id = $Member->ttop_uid;
                                //var_dump($Member);
                              //  var_dump($Member->top_uid);
                                $shangji_info = DB::table('member')->select('level','mtype','username','activation','amount','integral')->where('id',$shangji_id)->first();
                                $sshangji_info = DB::table('member')->select('level','mtype','username','activation','amount','integral')->where('id',$sshangji_id)->first();



                                ///////////////////////////////////////////////////////////////////////////
                                $Member->username = substr_replace($Member->username, '****', 3,5);
                                $ShangjiaMember= Member::where("id",$shangji_id)->first();  //上级名称
                                $SShangjiaMember= Member::where("id",$sshangji_id)->first();  //上上级信息
                             //   var_dump($ShangjiaMember);
                                $buyman = $Member->username;
                                //分成钱数
                            //    $rewardMoney = intval($integrals * $recent->percent * $checkBayong / 100);
                                $rewardMoney = $product->firstlevel * $Model->stnum;  //上级分成
                                $rrewardMoney = $product->secondlevel* $Model->stnum;  //上上级分成
                               // var_dump($rewardMoney);
                                $shangjia = $ShangjiaMember->username;
                                $sshangjia = $SShangjiaMember->username;
                                {
                                    $title = "尊敬的{$shangjia}会员您好！您的商品分成已到账";
                                    $content = "您的下线{$buyman}购买项目成功,{$rewardMoney}元已赠送到您的账号";
                                    //站内消息
                                    $msg=[
                                        "userid"=>$ShangjiaMember->id,
                                        "username"=>$ShangjiaMember->username,
                                        "title"=>$title,
                                        "content"=>$content,
                                        "from_name"=>"系统通知",
                                        "types"=>"下线购买分成",
                                    ];
                                    \App\Membermsg::Send($msg);


                                    $MOamount=$ShangjiaMember->amount;

                                    $ShangjiaMember->increment('amount',$rewardMoney);

                                    $notice = "下线(".$Member->username.")购买(".$product->name.")产品分成";

                                    $log=[
                                        "userid"=>$ShangjiaMember->id,
                                        "username"=>$ShangjiaMember->username,
                                        "money"=>$rewardMoney,
                                        "notice"=>$notice,
                                        "type"=>"下线购买分成",
                                        "status"=>"+",
                                        "yuanamount"=>$MOamount,
                                        "houamount"=>$ShangjiaMember->amount,
                                        "ip"=>\Request::getClientIp(),
                                        "category_id"=>$product->category_id,
                                        "product_id"=>$product->id,
                                        "from_uid"=>$UserId,
                                        "from_uid_buy_id"=>$Model->id,
                                        'moneylog_type_id'=>'5',
                                    ];
                                    \App\Moneylog::AddLog($log);

                                    $data=[
                                        "userid"=>$ShangjiaMember->id,
                                        "username"=>$ShangjiaMember->username,
                                        "xxuserid"=>$Member->id,
                                        "xxusername"=>$Member->username,
                                        "amount"=>$integrals,
                                        "preamount"=>$rewardMoney,
                                        "type"=>"下线分成",
                                        "status"=>"1",
                                        // "xxcenter"=>$recent->name,
                                        "created_at"=>$now_time,
                                        "updated_at"=>$now_time,
                                    ];
                                    DB::table("membercashback")->insert($data);
                                }
                                {
                                    $title = "尊敬的{$sshangjia}会员您好！您的商品分成已到账";
                                    $content = "您的下线{$buyman}购买项目成功,{$rewardMoney}元已赠送到您的账号";
                                    //站内消息
                                    $msg=[
                                        "userid"=>$SShangjiaMember->id,
                                        "username"=>$SShangjiaMember->username,
                                        "title"=>$title,
                                        "content"=>$content,
                                        "from_name"=>"系统通知",
                                        "types"=>"下线购买分成",
                                    ];
                                    \App\Membermsg::Send($msg);


                                    $MOamount=$SShangjiaMember->amount;

                                    $SShangjiaMember->increment('amount',$rrewardMoney);

                                    $notice = "下线(".$Member->username.")购买(".$product->name.")产品分成";

                                    $log=[
                                        "userid"=>$SShangjiaMember->id,
                                        "username"=>$SShangjiaMember->username,
                                        "money"=>$rrewardMoney,
                                        "notice"=>$notice,
                                        "type"=>"下线购买分成",
                                        "status"=>"+",
                                        "yuanamount"=>$MOamount,
                                        "houamount"=>$SShangjiaMember->amount,
                                        "ip"=>\Request::getClientIp(),
                                        "category_id"=>$product->category_id,
                                        "product_id"=>$product->id,
                                        "from_uid"=>$UserId,
                                        "from_uid_buy_id"=>$Model->id,
                                        'moneylog_type_id'=>'5',
                                    ];
                                    \App\Moneylog::AddLog($log);

                                    $data=[
                                        "userid"=>$SShangjiaMember->id,
                                        "username"=>$SShangjiaMember->username,
                                        "xxuserid"=>$Member->id,
                                        "xxusername"=>$Member->username,
                                        "amount"=>$integrals,
                                        "preamount"=>$rrewardMoney,
                                        "type"=>"下线分成",
                                        "status"=>"1",
                                        // "xxcenter"=>$recent->name,
                                        "created_at"=>$now_time,
                                        "updated_at"=>$now_time,
                                    ];
                                    DB::table("membercashback")->insert($data);
                                }

                        ///////////////////////////////////////////////////////////////////////////
							}


							$user_id = $Model->userid;
							$score = $capital_flow;
							$type = 1;
							$source_type = 5;

							$act = APP::make(\App\Http\Controllers\Api\ActController::class);
							App::call([$act, 'change_score_by_user_id'], [$user_id, $score, $type, $source_type]);

                            if($Member->rw_level ==1){
                                $Member= Member::where('state',1)->find($Model->userid);
                                $Member->rw_level = 2;
                                $Member->save();
                            }

                            $data = ["status"=>0,"msg"=>"确认通过成功"];
                        }else if($request->status=='3'){
                            // $integral = DB::table('productbuy')->where(['id'=>$Model->id])->value('integral');
                            // if($integral > 0){
                            // $Member->increment('integral',$integral);

                            // }
                            $data = ["status"=>0,"msg"=>"确认未通过成功"];
                            $Model->reason = $request->reason;
                        }
                        $Model->status = $request->status;
                        $Model->save();
                    DB::commit();
                    try{
                    }catch(\Exception $exception){
                        Log::channel('buy')->alert($exception);
                        DB::rollBack();
                        return ['status'=>0,'msg'=>'提交失败，请稍后重试'];
                    }
                    if($request->ajax()){
                       return response()->json($data);
                    }

                }else if($Model->status==0){
                    if($request->status=='1'){
                        $Model->express = $request->express;
                        $Model->deliverysno = $request->deliverysno;
                        $Model->status = $request->status;
                        $Model->save();
                        return ['status'=>0,'msg'=>'修改成功'];
                    }

                }
            }




        }

    }


    public function sendsms(Request $request)
    {
        if($request->isMethod("post")){



            $Model = $this->Model::find($request->get('id'));

            if($Model->sendsms==0){
                $Model->sendsms=1;
                $Model->save();
            }

            \App\Sendmobile::SendUid($Model->userid,'txcg');//短信通知




            if($request->ajax()){
                return response()->json([
                    "msg"=>"操作成功","status"=>0
                ]);
            }


        }

    }





    public function delete(Request $request){

          if($request->ajax()) {
              if(is_array($request->input("ids"))){
                if(count($request->input("ids"))>0){

                    // $admins = DB::table($this->table)
                    //     ->whereIn('id',  $request->input("ids"))
                    //     ->count();
                    // if($admins>0){
                    //     return ["status" => 1, "msg" => "系统用户组不允许删除"];
                    // }

                    $delete = DB::table($this->table)->whereIn('id', $request->input("ids"))->delete();
                    if ($delete) {
                        return ["status" => 0, "msg" => "批量删除成功"];
                    } else {
                        return ["status" => 1, "msg" => "批量删除失败"];
                    }
                }

              }
            if($request->input("id")){


                $Model = $this->Model::find($request->get('id'));
                $member = DB::table($this->table)
                    ->where(['id' => $request->input("id")])
                    ->first();
                    // dump($member);
                    // exit();


                if($member){

                      $delete = DB::table($this->table)->where('id', '=', $request->input("id"))->delete();
                        if ($delete) {





                            return ["status" => 0, "msg" => "删除成功"];
                        } else {
                            return ["status" => 1, "msg" => "删除失败"];
                        }


                }else{
                    return ["status"=>1,"msg"=>"您没有权限删除操作"];
                }


            }


        }else{
            return ["status"=>1,"msg"=>"非法操作"];
        }

    }

    function get_random_code($num)
    {
        // $codeSeeds = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
        // $codeSeeds .= "abcdefghijklmnpqrstuvwxyz";
        // $codeSeeds .= "0123456789_";
        $codeSeeds = "1234567890";
        $len = strlen($codeSeeds);
        $ban_num = ($num/2)-3;
        $code = "";
        for ($i = 0; $i < $num; $i++) {
            $rand = rand(0, $len - 1);
            if($i == $ban_num){
                $code .= 'O';
            }else{
                $code .= $codeSeeds[$rand];
            }
        }
        return $code;
    }

}
