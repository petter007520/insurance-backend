<?php

namespace App\Jobs;

use App\Member;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 用户上下级关系分配
 */
class UserBindAllot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $member_id = 0;
    private $member = 0;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($member_id)
    {
        $this->member_id = $member_id;
        $this->onQueue('userTreeBind');
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $member = Member::find($this->member_id);
        $field = '';
        if($member->region == 1){
            $field = 'child_left_uid';
        }
        if($member->region == 2){
            $field = 'child_right_uid';
        }
        $top_uid = $member->invite_uid;//注册用户直推上级ID
        Log::channel('bind_member_tree')->info(now() . $member->username . '注册成功，开始计算坐落位置'.'直推邀请人ID'.$top_uid);
        $child_id = Member::where(['id'=>$top_uid])->value($field);
        echo $child_id."\n";
        //对应区域有下级就继续查
        if($child_id > 0){
            do {
                $top_uid = $child_id;
                $child_id = Member::where(['id'=>$child_id])->value($field);
            }
            while ($child_id >0);
        }
        echo '关系树ID'.$top_uid."---------\n";
        $tree_top_member = Member::where(['id'=>$top_uid])->value('tree_ids');
        //注册用户绑定关系树上级ID
        $member->top_uid = $top_uid;
        $member->tree_ids = empty($tree_top_member) ? $top_uid:$tree_top_member.','.$top_uid;
        $member->state = 1;
        $member->save();
        //注册用户绑定到关系树上级对应区域的直属下级ID
        DB::table('member')->where(['id'=>$top_uid])->update([$field=>$member->id]);
        Log::channel('bind_member_tree')->info(now() . $member->username . '已绑定为ID-' . $top_uid . '的关系树下级');
    }

    public function fail($exception = null)
    {
        Log::channel('bind_member_tree')->error($exception);
    }
}
