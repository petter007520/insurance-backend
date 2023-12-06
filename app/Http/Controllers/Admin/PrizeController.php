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

class PrizeController extends BaseController
{
    private $table = "award_prizes";

    public function index(Request $request)
    {
        return redirect(route($this->RouteController . ".lists"));
    }

    public function lists(Request $request)
    {
        $pagesize = 10;//默认分页数
        if (Cache::has('pagesize')) {
            $pagesize = Cache::get('pagesize');
        }
        isset($_REQUEST['s_key']) ? $s_key = $_REQUEST['s_key'] : $s_key = '';
        $where = [];
        if ($s_key)
            $where[] = [$this->table . ".title", 'like', "%{$s_key}%", 'and'];
        DB::enableQueryLog();
        $listDB = DB::table($this->table)
            ->where($where)
            ->select($this->table . '.*');
        $list = $listDB->orderBy($this->table . ".id", "desc")
            ->paginate($pagesize);
        if ($request->ajax()) {
            if ($list) {
                return ["status" => 0, "list" => $list, "pagesize" => $pagesize];
            }
        } else {
            return $this->ShowTemplate(["list" => $list, "pagesize" => $pagesize]);
        }
    }

    public function update(Request $request)
    {
        if ($request->isMethod("post")) {
            $data = $request->all();
            $id = $data['id'];
            unset($data['_token']);
            unset($data['id']);
            unset($data['thumb']);
            unset($data['file']);
            unset($data['s']);
            $data['title'] = \App\Formatting::ToFormat($data['title']);
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

    public function store(Request $request)
    {
        if ($request->isMethod("post")) {
            $data = $request->all();
            unset($data['_token']);
            unset($data['thumb']);
            unset($data['file']);
            unset($data['s']);
            $data['title'] = \App\Formatting::ToFormat($data['title']);
            $res = DB::table($this->table)->insertGetId($data);
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

    public function settop(Request $request)
    {
        if ($request->isMethod("post")) {

            $Model = $this->table->find($request->input('id'));
            $Model->issy = $request->input('top_status');
            $Model->save();
            if ($request->ajax()) {
                return response()->json([
                    "msg" => "操作成功", "status" => 0
                ]);
            }
        }
    }
}