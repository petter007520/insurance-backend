<?php


namespace App\Http\Controllers\Admin;
    use App\Celebrity;
    use App\Member;
    use App\Productbuy;
    use App\Site;
    use App\TreeProduct;
    use App\TreeProductbuy;
    use Carbon\Carbon;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Session;
    use Cache;
    use App\Bigtree;

class MemberidentityController extends BaseController
{
    private $table="memberidentity";

    public function __construct(Request $request)
    {
        parent::__construct($request);

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
            ->leftjoin('member as me' ,'me.id','=',$this->table.'.userid')
            ->select($this->table.'.*','me.username')
            ->where(function ($query) {
                if(isset($_REQUEST['s_key']) && $_REQUEST['s_key']!=''){
                    $query->where("me.username","=",$_REQUEST['s_key']);
                }
            })
            ->where(function ($query) {
                if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                    $query->where("status","=",$_REQUEST['s_status']);
                }
            });

            $list=$listDB->orderBy($this->table.".id","desc")->paginate($pagesize);
        if($request->ajax()){
            if($list){
                $model=config('model');
                $modelname=[];
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{

            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }
    }

    public function store(Request $request){

    }

    public function update(Request $request)
    {

    }

    public function settop(Request $request)
    {
        if($request->isMethod("post")){
          $identityInfo =  DB::table($this->table)
              ->where(['id' => $request->input("id")])->first();
            $member = Member::find($identityInfo->userid);
            $data['status'] = $identityInfo->status==1?0:1;
            $data['updated_at'] = Carbon::now();
            DB::table($this->table)->where(['id' => $request->input("id")])->update($data);

            $check = DB::table('member')->where(['id' => $identityInfo->userid])->value('is_auth');
            if($data['status'] == 1 && !$check){
                $userData['realname'] = $identityInfo->realname;
                $userData['card'] = $identityInfo->idnumber;
                $userData['is_auth'] = 1;
                DB::table('member')->where(['id' => $identityInfo->userid])->update($userData);

                $regist_amount = DB::table('setings')->where(['keyname'=>'regist_gift'])->value('value');//注册赠送金额
                if($regist_amount>0){
                    $member->increment('ktx_amount',$regist_amount);
                    $notice = "实名认证奖励".$regist_amount."（元）";
                    $log=[
                        "userid"=>$member->id,
                        "username"=>$member->username,
                        "money"=>$regist_amount,
                        "notice"=>$notice,
                        "type"=>"实名认证奖励",
                        "status"=>"+",
                        "yuanamount"=>$member->amount,
                        "houamount"=>$member->amount,
                        "ip"=>\Request::getClientIp(),
                        "category_id"=>0,
                        "product_id"=>0,
                        "from_uid"=>$identityInfo->id,
                        "from_uid_buy_id"=>0,
                        'moneylog_type_id'=>'23',
                    ];
                    \App\Moneylog::AddLog($log);
                }
            }else{
                $userData['realname'] = '';
                $userData['card'] = '';
                DB::table('member')->where(['id' => $identityInfo->userid])->update($userData);
            }

            if($request->ajax()){
                return response()->json([
                    "msg"=>"操作成功","status"=>0
                ]);
            }
        }
    }

    function get_random_code($num)
    {
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

    public function delete(Request $request){
          if($request->ajax()) {
            if($request->input("id")){
                $member = DB::table($this->table)
                    ->where(['id' => $request->input("id")])
                    ->first();
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
            }else if($request->input("ids")){
                $delete = DB::table($this->table)->whereIn('id',  $request->input("ids"))->delete();
                if ($delete) {
                    return ["status" => 0, "msg" => "删除成功"];
                } else {
                    return ["status" => 1, "msg" => "删除失败"];
                }
            }
        }else{
            return ["status"=>1,"msg"=>"非法操作"];
        }
    }
}
