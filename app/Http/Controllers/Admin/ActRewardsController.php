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

class ActRewardsController extends BaseController
{
    private $table = "act_rewards";

    public function index(Request $request)
    {
        return redirect(route($this->RouteController . ".lists"));
    }

    public function lists(Request $request)
    {
        $pagesize = 10;//默认分页数
        
        $list = DB::table($this->table)
			->orderBy("id", "desc")
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
        if ($request->isMethod("post")) {
            $data = $request->all();
			unset($data['_token']);
			unset($data['thumb']);
            $res = DB::table($this->table)->insert($data);
            if ($request->ajax()) {
                return response()->json([
                    "msg" => "添加成功", "status" => 0
                ]);
            } else {
                return redirect(route($this->RouteController . '.store'))->with(["msg" => "添加成功", "status" => 0]);
            }
        } else {
            return $this->ShowTemplate();
        }
    }
	
	
	public function update(Request $request)
    {
        if ($request->isMethod("post")) {
            $data = $request->all();
			unset($data['_token']);
			unset($data['thumb']);
            $id = $data['id'];
            DB::table($this->table)->where("id", $id)->update($data);
            if ($request->ajax()) {
                return response()->json([
                    "msg" => "修改成功", "status" => 0
                ]);
            } else {
                return redirect(route($this->RouteController . '.update', ["id" => $request->input("id")]))->with(["msg" => "修改成功", "status" => 0]);
            }
        } else {
            $Model = DB::table($this->table)->find($request->get('id'));
            return $this->ShowTemplate(["edit" => $Model, "status" => 0]);
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