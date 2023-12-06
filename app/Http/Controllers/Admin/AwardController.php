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

class AwardController extends BaseController
{
    private $table = "awards";
    private $prize_table = "award_prizes";

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
        if($s_key)
            $where[] = [$this->prize_table . ".title", 'like', "%{$s_key}%", 'and'];
//        DB::enableQueryLog();
        $listDB = DB::table($this->table)
            ->join($this->prize_table, function ($join) {
                $join->on($this->table . ".prize_id", "=", $this->prize_table . ".id");
            })
            ->where($where)
            ->select($this->table . '.*', $this->prize_table . '.*');
        $list = $listDB->orderBy($this->table . ".id", "desc")
            ->paginate($pagesize);
        /*->each(function ($v,$i){
                $prize_id = json_decode($v->prize_id, true);
                $v->image = DB::table($this->prize_table)->where("id", $prize_id)->value("image");
                return $v;
            })*/
        $list->transform(function ($m) {
            $prize_id = json_decode($m->prize_id, true);
            $user_id = json_decode($m->user_id, true);
            $time = json_decode($m->time, true);
            $m->user_title = (new Member())->where("id", $user_id)->value("nickname");
            $m->user_name = (new Member())->where("id", $user_id)->value("username");
            $m->prize_title = DB::table($this->prize_table)->where("id", $prize_id)->value("title");
            $m->image = DB::table($this->prize_table)->where("id", $prize_id)->value("image");
            $m->time = date("Y-m-d H:i:s", $time);
            return $m;
        });
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
            unset($data['file']);
            unset($data['productimage']);
            unset($data['editormd-image-file']);
            unset($data['s']);
            $data['hkfs'] = 0;
            $data['title'] = \App\Formatting::ToFormat($data['title']);
            if (!empty($data['content'])) {
                $data['content'] = \App\Formatting::ToFormat($data['content']);
            }
            $data['category_name'] = $this->CategoryModel->where("id", $data['category_id'])->value('name');
            $data['updated_at'] = Carbon::now();
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