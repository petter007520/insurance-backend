<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Moneylog extends Model
{
    protected $table="moneylog";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = [
        'moneylog_userid',
        'moneylog_user',
        'moneylog_money',
        'moneylog_ip',
        'category_id',
        'moneylog_status',
        'moneylog_type',
        'moneylog_notice',
        'moneylog_houamount',
        'moneylog_yuanamount',
        'currlog_id'
    ];


    protected function AddLog($data){

        $Model = new Moneylog();
        $created_date = date('Y-m-d');
        if(isset($data['created_date'])){
            if(!empty($data['created_date'])){
                $created_date = $data['created_date'];
            }
        }
        $Model->moneylog_userid=$data['userid'];
        $Model->moneylog_user=$data['username'];
        $Model->moneylog_money=$data['money'];
        $Model->moneylog_ip=$data['ip'];
        $Model->product_id=isset($data['product_id'])?$data['product_id']:0;
        $Model->category_id=isset($data['category_id'])?$data['category_id']:0;
        $Model->moneylog_status=$data['status'];
        $Model->moneylog_type=$data['type'];
        $Model->moneylog_notice=$data['notice'];
        $Model->moneylog_yuanamount=$data['yuanamount'];
        $Model->moneylog_houamount=$data['houamount'];
        $Model->currlog_id=isset($data['currlog_id'])?$data['currlog_id']:0;
        $Model->product_title=isset($data['product_title'])?$data['product_title']:'';
        $Model->buy_id=isset($data['buy_id'])?$data['buy_id']:0;
        // $Model->bank_id=isset($data['bank_id'])?$data['bank_id']:0;
        $Model->from_uid=isset($data['from_uid'])?$data['from_uid']:0;
        $Model->recharge_id=isset($data['recharge_id'])?$data['recharge_id']:0;
        $Model->from_uid_buy_id=isset($data['from_uid_buy_id'])?$data['from_uid_buy_id']:0;
      //  $Model->created_date = date('Y-m-d');
        $Model->created_date = $created_date;
        $Model->moneylog_num = isset($data['num'])?$data['num']:0;
        $Model->withdrawal_id = isset($data['withdrawal_id'])?$data['withdrawal_id']:0;
        $Model->moneylog_type_id = isset($data['moneylog_type_id'])?$data['moneylog_type_id']:0;
        $Model->created_at = isset($data['created_at'])?$data['created_at']:date('Y-m-d H:i:s');
        $Model->save();
        return ["status"=>0];

    }
}
