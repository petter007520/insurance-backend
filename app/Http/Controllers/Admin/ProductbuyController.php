<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Api\PayOrderController;
use App\Memberlevel;
use App\Product;
use App\Productbuy;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class ProductbuyController extends BaseController
{
    private $table="productbuy";

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Productbuy();
        $Products= Product::get();
        foreach ($Products as $Product){
            $this->Products[$Product->id]=$Product;
        }
        $this->CategoryModel=new Category();
        $category_id=$request->s_categoryid;
        view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$category_id,0,'products'));
        $Memberlevels= Memberlevel::get();

        foreach ($Memberlevels as $Memberlevel){
            $this->Memberlevels[$Memberlevel->id]=$Memberlevel;
        }
				$totalAmount = DB::table($this->table)
                    ->where(['status'=>1])
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
                    ->sum('amount');

        view()->share("today_amount_ok",$today_amount_ok);
    }

    public function index(Request $request){
        return redirect(route($this->RouteController.".lists"));
    }

    public function lists(Request $request){
        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }
        if($request->ajax()){
            $listDB = DB::table($this->table)
				->leftJoin('member', 'member.id', '=', 'productbuy.userid')
                ->select($this->table.'.*')
            ->where(function ($query) {
                $s_siteid=[];
                if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                    $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                }
                $query->where($s_siteid);
            })
			->where(function ($query) {
                $top_uid = 0;
                if(isset($_REQUEST['top_uid']) && $_REQUEST['top_uid']!=''){
                    $user_info = DB::table('member')
						->where(['username' => $_REQUEST['top_uid']])
						->first();
					$top_uid = $user_info->id;
                }
				if ($top_uid > 0) {
					$query->where('member.top_uid', '=', $top_uid);
				}
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
               ->where(function ($query) {
                $s_categoryid=[];
                if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']>0){
                    $s_categoryid[]=[$this->table.".category_id","=",$_REQUEST['s_categoryid']];
                }
                $query->where($s_categoryid);
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
            });
            $list=$listDB->orderBy($this->table.".id","desc")->paginate($pagesize);
            if($list){
                foreach ($list as $item){
                    $item->product=  isset($this->Products[$item->productid])?$this->Products[$item->productid]->title:'0';
                    if(isset($this->Products[$item->productid])){
                        $moneyCount = $this->Products[$item->productid]->jyrsy * $item->amount/100;
                        $item->moneyCount= sprintf("%.2f",$moneyCount);
                    }else{
                        $item->moneyCount=0;
                    }
                    if($item->useritem_time<=Carbon::now()){
                        $item->fh=1;
                    }else{
                        $item->fh=0;
                    }
                    $item->timenow=Carbon::now()->format("Y-m-d H:i:s");
                }
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{
            return $this->ShowTemplate([]);
        }
    }

    public function store(Request $request){

    }

    public function update(Request $request)
    {
        if($request->isMethod("post")){
            $Model = $this->Model::find($request->get('id'));
            if($request->status!='8' && $request->status!='9'){
                //待审核
                if($Model->status==2){
                    if($request->status=='1'){
                        $Model->status = 1;
                        $Model->save();
                        $ret = (new PayOrderController())->third_pay_finish_payment($Model->id);
                        if($ret['status'] == 0){
                            Log::channel('pay')->warning('支付未完成-'.$ret['msg']);
                            $data =  ['status' => 0, 'msg' => '提交失败，请重试'];
                        }
                        if($ret['status'] == 1){
                            $data = ["status"=>0,"msg"=>"确认通过成功"];
                        }
                    }
                    if($request->status=='3'){
                        $data = ["status"=>0,"msg"=>"确认未通过成功"];
                        $Model->status = $request->status;
                        $Model->reason = $request->reason;
                        $Model->save();
                    }
                    if($request->ajax()){
                       return response()->json($data);
                    }
                }
            }else{
                if($Model->ft_amount!=0){
                    if($request->status=='8'){
                        $Model->amount = $Model->amount + $Model->ft_amount;
                        $Model->ft_amount = 0;
                        $data = ["status"=>0,"msg"=>"确认复投通过成功"];
                    }else if($request->status=='9'){
                        $Model->ft_reason = $request->ft_reason;
                        $data = ["status"=>0,"msg"=>"确认未通过成功"];
                    }
                    $Model->save();

                    if($request->ajax()){
                       return response()->json($data);
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
