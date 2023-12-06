<?php
/**
 * Created by PhpStorm.
 * User: Xu
 * Date: 2022/11/2
 * Time: 22:09
 */

namespace App\Http\Controllers\Admin;


use App\Member;
use DB;
use Illuminate\Http\Request;
use Session;
use Cache;

class ActRewardsLogController extends BaseController
{
    private $table = "act_rewards_log";

    public function index(Request $request)
    {
        return redirect(route($this->RouteController . ".lists"));
    }

    public function lists(Request $request)
    {
        $pagesize = 10;
		
		isset($_REQUEST['s_key'])?$s_key=$_REQUEST['s_key']:$s_key='';
		
		$list = DB::table($this->table)
			->join('member', 'member.id', '=', 'act_rewards_log.user_id')
			->where(function ($query) {
                $s_key_content=[];
                if(isset($_REQUEST['s_key'])){
                    $s_key_content[]=["act_rewards_log.reward_name","like","%".$_REQUEST['s_key']."%"];
                }
                $query->where($s_key_content);
            })
			->where(['act_rewards_log.pre' => 0])
			->where('act_rewards_log.reward_id', '>', 0)
			->select('member.username', 'act_rewards_log.*')
			->orderBy('act_rewards_log.id', 'desc')
			->paginate($pagesize);
			
        if ($request->ajax()) {
            if ($list) {
                return ["status" => 0, "list" => $list, "pagesize" => $pagesize];
            }
        } else {
            return $this->ShowTemplate(["list" => $list, "pagesize" => $pagesize]);
        }
    }


    public function store(Request $request)
    {
		$rewards_lists = DB::table('act_rewards')
			->where(['disabled' => 0])
			->select('id', 'name', 'img')
			->get();

		view()->share("rewards_lists", $rewards_lists);
			
			
        if ($request->isMethod("post")) {
            $data = $request->all();
			
			if ($data['mobile']) {
				$user_info = DB::table('member')
					->where(['username' => $data['mobile']])
					->first();
				$user_id = $user_info->id;
				$tmp_arr = [];
				foreach($rewards_lists as $v) {
					$tmp_arr[$v->id] = $v->name;
				}
				
				$res = DB::table($this->table)->insert(['user_id' => $user_id, 'reward_id' => $data['reward_id'], 'reward_name' => $tmp_arr[$data['reward_id']], 'pre' => 1]);
				if ($request->ajax()) {
					return response()->json([
						"msg" => "添加成功", "status" => 0
					]);
				}
			} else {
				return redirect(route($this->RouteController . '.store'))->with(["msg" => "添加成功", "status" => 0]);
			}
        } else {
            return $this->ShowTemplate();
        }
    }
	
	public function delete(Request $request)
    {

        if ($request->isMethod("post")) {
            $data = $request->all();
            $id = $data['id'];
            DB::table($this->table)->where("id", $id)->delete();
            if ($request->ajax()) {
                return response()->json([
                    "msg" => "删除成功", "status" => 0
                ]);
            } else {
                return redirect(route($this->RouteController . '.delete', ["id" => $request->input("id")]))->with(["msg" => "修改成功", "status" => 0]);
            }
        }
    }

}