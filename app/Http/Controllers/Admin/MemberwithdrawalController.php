<?php
namespace App\Http\Controllers\Admin;
use App\Memberwithdrawal;
use App\statistics;
use App\statisticsdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Excel as ExcelType;

class MemberwithdrawalController extends BaseController
{
    private $table="memberwithdrawal";
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Memberwithdrawal();
        $totalWithdrawal = DB::table($this->table)
                            ->where(['status'=>1])
                            ->where(function ($query) {
                                $s_siteid=[];
                                if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                                    $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                                }

                                $query->where($s_siteid);
                            })
                                ->where(function ($query) {
                                $s_status=[];
                                if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                                    $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                                }

                                $query->where($s_status);
                            })
                            ->where(function ($query) {
                                    $date_s=[];
                                    if(isset($_REQUEST['date_s']) && $_REQUEST['date_s']!=''){

                                        $query->whereDate("created_at",">=",$_REQUEST['date_s']." 00:00:00");
                                    }
                                })
                            ->where(function ($query) {
                                    $date_s=[];
                                    if(isset($_REQUEST['date_e']) && $_REQUEST['date_e']!=''){

                                        $query->whereDate("created_at","<=",$_REQUEST['date_e']." 23:59:59");
                                    }
                                })
                            ->sum('amount');

        view()->share("totalWithdrawal",$totalWithdrawal);
        $today_withdrawal= DB::table("memberwithdrawal")
                    ->where(function ($query) {
                        if(!isset($_REQUEST['date_s'])){
                             $query->where('updated_at','>=',date('Y-m-d 00:00:00', time()))
                             ->where('updated_at','<=',date('Y-m-d 23:59:59', time()));
                        }

                    })
                    ->where(function ($query) {
                        $s_siteid=[];
                        if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                            $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                        }
                        $query->where($s_siteid);
                    })
                        ->where(function ($query) {
                        $s_status=[];
                        if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                            $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                        }
                        $query->where($s_status);
                    })
                    ->where(function ($query) {
                            $date_s=[];
                            if(isset($_REQUEST['date_s']) && $_REQUEST['date_s']!=''){

                                $query->whereDate("created_at",">=",$_REQUEST['date_s']." 00:00:00");
                            }
                        })
                    ->where(function ($query) {
                            $date_s=[];
                            if(isset($_REQUEST['date_e']) && $_REQUEST['date_e']!=''){

                                $query->whereDate("created_at","<=",$_REQUEST['date_e']." 23:59:59");
                            }
                        })
                    ->where(function ($query) {
                        $s_siteid=[];
                        if(isset($_REQUEST['s_min_price']) && $_REQUEST['s_min_price']!=''){
                            $s_siteid[]=[$this->table.".amount",">=",$_REQUEST['s_min_price']];
                        }

                        $query->where($s_siteid);
                    })
                    ->where(function ($query) {
                        $s_siteid=[];
                        if(isset($_REQUEST['s_max_price']) && $_REQUEST['s_max_price']!=''){
                            $s_siteid[]=[$this->table.".amount","<=",$_REQUEST['s_max_price']];
                        }

                        $query->where($s_siteid);
                    })
                    ->sum('amount');

        view()->share("today_withdrawal",$today_withdrawal);

        $today_withdrawal_ok= DB::table("memberwithdrawal")
                    ->where(['status'=>1])
                    ->where(function ($query) {
                        if(!isset($_REQUEST['date_s'])){
                             $query->where('updated_at','>=',date('Y-m-d 00:00:00', time()))
                             ->where('updated_at','<=',date('Y-m-d 23:59:59', time()));
                        }

                    })
                    ->where(function ($query) {
                        $s_siteid=[];
                        if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                            $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                        }

                        $query->where($s_siteid);
                    })
                        ->where(function ($query) {
                        $s_status=[];
                        if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                            $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                        }

                        $query->where($s_status);
                    })
                    ->where(function ($query) {
                            $date_s=[];
                            if(isset($_REQUEST['date_s']) && $_REQUEST['date_s']!=''){

                                $query->whereDate("created_at",">=",$_REQUEST['date_s']." 00:00:00");
                            }
                        })
                    ->where(function ($query) {
                            $date_s=[];
                            if(isset($_REQUEST['date_e']) && $_REQUEST['date_e']!=''){

                                $query->whereDate("created_at","<=",$_REQUEST['date_e']." 23:59:59");
                            }
                        })
                    ->where(function ($query) {
                        $s_siteid=[];
                        if(isset($_REQUEST['s_min_price']) && $_REQUEST['s_min_price']!=''){
                            $s_siteid[]=[$this->table.".amount",">=",$_REQUEST['s_min_price']];
                        }

                        $query->where($s_siteid);
                    })
                    ->where(function ($query) {
                        $s_siteid=[];
                        if(isset($_REQUEST['s_max_price']) && $_REQUEST['s_max_price']!=''){
                            $s_siteid[]=[$this->table.".amount","<=",$_REQUEST['s_max_price']];
                        }

                        $query->where($s_siteid);
                    })
                    ->where('status',1)
                    ->sum('amount');

        view()->share("today_withdrawal_ok",$today_withdrawal_ok);

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
            ->leftJoin('memberidentity as mi', 'mi.userid', '=', $this->table.'.userid')
                ->select($this->table.'.*','mi.realname','mi.idnumber')
            ->where(function ($query) {
                $s_siteid=[];
                if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                    $s_siteid[]=[$this->table.".username","=",$_REQUEST['s_key']];
                }

                $query->where($s_siteid);
            })
            ->where(function ($query) {
                $s_card=[];
                if(isset($_REQUEST['s_card']) && $_REQUEST['s_card']!=''){
                    $s_card[]=[$this->table.".bankcode","=",$_REQUEST['s_card']];
                }

                $query->where($s_card);
            })
            ->where(function ($query) {
                $amount_type=[];
                if(isset($_REQUEST['amount_type']) && $_REQUEST['amount_type']!=''){
                    $amount_type[]=[$this->table.".amount_type","=",$_REQUEST['amount_type']];
                }
                $query->where($amount_type);
            })

			->where(function ($query) {
                $s_realname=[];
                if(isset($_REQUEST['s_realname']) && $_REQUEST['s_realname']!=''){
                    $s_realname[]=[$this->table.".bankrealname","=",$_REQUEST['s_realname']];
                }

                $query->where($s_realname);
            })

                ->where(function ($query) {
                $s_status=[];
                if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                    $s_status[]=[$this->table.".status","=",$_REQUEST['s_status']];
                }

                $query->where($s_status);
            })
            ->where(function ($query) {
                    $date_s=[];
                    if(isset($_REQUEST['date_s']) && $_REQUEST['date_s']!=''){

                        $query->whereDate("memberwithdrawal.created_at",">=",$_REQUEST['date_s']." 00:00:00");
                    }
                })
            ->where(function ($query) {
                    $date_s=[];
                    if(isset($_REQUEST['date_e']) && $_REQUEST['date_e']!=''){

                        $query->whereDate("memberwithdrawal.created_at","<=",$_REQUEST['date_e']." 23:59:59");
                    }
                })
            ->where(function ($query) {
                $s_siteid=[];
                if(isset($_REQUEST['s_min_price']) && $_REQUEST['s_min_price']!=''){
                    $s_siteid[]=[$this->table.".amount",">=",$_REQUEST['s_min_price']];
                }
                $query->where($s_siteid);
            })
            ->where(function ($query) {
                $s_siteid=[];
                if(isset($_REQUEST['s_max_price']) && $_REQUEST['s_max_price']!=''){
                    $s_siteid[]=[$this->table.".amount","<=",$_REQUEST['s_max_price']];
                }
                $query->where($s_siteid);
            });

            $list=$listDB->orderBy($this->table.".id","desc")
                ->paginate($pagesize);

            if($list){
                //优先显示当时提现的账号
                foreach ($list as $item){

                    if($item->bankname == '' || $item->bankrealname == '' || $item->bankrealname == ''){
                        $baknInfo = DB::table('memberbank')->find($item->bankid);
                        if($baknInfo){
                            $item->userBank =  $baknInfo->bankname?$baknInfo->bankname:'';
                            $item->bankName =  $baknInfo->type==1?$baknInfo->bankname:'支付宝';
                            $item->bankrealname =  $baknInfo->bankrealname?$baknInfo->bankrealname:'';
                            $item->bankcode =  $baknInfo->bankcode?$baknInfo->bankcode:'';
                        }else{
                            $item->userBank =  '';
                            $item->bankName =  '';
                            $item->bankrealname =  '';
                            $item->bankcode =  '';
                            $item->bankaddress =  '';
                        }
                    }else{
                        $item->bankName =  $item->bankname;
                        $item->bankrealname =  $item->bankrealname;
                        $item->bankcode =  $item->bankcode;
                    }

                    $item->card =  isset($item->idnumber)?$item->idnumber:'';
                    $item->realname =  isset($item->realname)?$item->realname:'';
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
            if($Model->status==0){
                if($request->status=='1'){
                   $data= \App\Memberwithdrawal::ConfirmWithdrawal($Model->id);
                }else if($request->status=='-1'){
                    $data= \App\Memberwithdrawal::CancelWithdrawal($Model->id);
                }
                if($request->ajax()){
                    return response()->json($data);
                }
            }
        }else{
            $Model = $this->Model::find($request->get('id'));
            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
        }
    }

	public function updateThird(Request $request)
    {
        if($request->isMethod("post")){

            $Model = $this->Model::find($request->get('id'));
            if($Model->status==0){
                if($request->status=='1'){
                   $data= \App\Memberwithdrawal::ConfirmWithdrawalThird($Model->id);
                }else if($request->status=='-1'){
                    $data= \App\Memberwithdrawal::CancelWithdrawalThird($Model->id);
                }
                if($request->ajax()){
                    return response()->json($data->original);
                }
            }

        }else{
            $Model = $this->Model::find($request->get('id'));
            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
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
            if($request->input("id")){

                $member = DB::table($this->table)
                    ->select('userid','amount','created_at')
                    ->where(['id' => $request->input("id")])
                    ->first();

                if($member){

                    $withdrawal = $member->amount;
                    $today_withdrawal = $member->amount;

                    $str = $member->created_at;
                    $first=explode(' ',$str);



                      $delete = DB::table($this->table)->where('id', '=', $request->input("id"))->delete();
                        if ($delete) {

                            $statistics = statistics::select('user_id','team_total_withdrawal')->find($member->userid);
                            $statistics->update([
                                    	 	'team_total_withdrawal'   =>  DB::raw('team_total_withdrawal -'.$withdrawal),
                                    	]);



                            $statistics_date = statisticsdate::select('user_id','today_withdrawal','statistics_date')->where('statistics_date',$first[0])->find($member->userid);

                            $statistics_date->update([
                                    	 	'today_withdrawal'   =>  DB::raw('today_withdrawal -'.$today_withdrawal),
                                    	]);



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

    public function daochu11(Request $request){
        //调用
        $list=[
            ['10001','AAAAA','99','1',time()],
            ];
        $arr=['订单编号','订单金额','买方用户名','订单状态','订单生成时间'];
        // array_unshift($list,$arr); //插入表头
        $title=date('Y-m-d',time()).'订单数据表';
        $width=array('A'=>30,'B'=>15,'C'=>15,'D'=>15,'E'=>20);
        $list = collect($list)->all();
        return Excel::download($list, 'users.xls');
        // $this->exportExcel($title,$list,$width);


    }

    function exportExcel($title,$list,$width){
        return Excel::download($list, 'users.xlsx');
        // Excel::download(iconv('UTF-8', 'GBK', $title),function($excel) use ($list,$width){
        //     $excel->sheet('score', function($sheet) use ($list,$width){
        //         $sheet->rows($list);
        //         $sheet->setWidth($width);
        //     });
        // })->export('xls');
    }

    public function export_excel(Request $request){
        $status = $request->get('exp__status');
        $start_time = $request->get('exp_date_s');
        $end_time = $request->get('exp_date_e');
        $where = [];
        if(!is_null($status)){
            $where['status'] = $status;
        }
        if(!is_null($start_time) && !is_null($end_time)){
            $where[] =
                ['created_at','>',$start_time.' 00:00:00'];
                ['created_at','<',$end_time.' 59:59:59'];
        }
        // dump($where);exit;
        $data = DB::table('memberwithdrawal')->select('bankid','username','amount','bankcode','bankrealname','status','created_at')->where($where)->get();
        foreach ($data as $v){
            if($v->bankcode == '' || $v->bankrealname == ''){
                $memberbank = DB::table('memberbank')->select('bankrealname','bankcode')->find($v->bankid);
                if($memberbank){
                    $v->bankcode = $memberbank->bankcode;
                    $v->bankrealname = $memberbank->bankrealname;
                }else{
                    $v->bankcode = '';
                    $v->bankrealname = '';
                }
            }
            $v->status = $v->status == -1?'拒绝':'未审核';
            // $v->bankcode = '\t'.$v->bankcode;
            // $v->bankcode = html_entity_decode("&iuml;&raquo;&iquest;".$v->bankcode);
            $v->bankcode = '>'.$v->bankcode;
            // $v->bankcode = $v->bankcode;
        }
        $data = $data->toArray();
        $title = ['银行ID','手机号','金额','卡号','开户名','订单状态','订单时间'];
        array_unshift($data,$title);
        return  \Excel::download(new class($data) implements FromArray{
            public function __construct($array)
            {
                $this->array = $array;
            }
            public function array(): array
            {
                return $this->array;
            }


        },date('Y-m-d',time()).'.xlsx', ExcelType::XLSX);
    }

}
