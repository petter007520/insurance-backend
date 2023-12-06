<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;

class statisticsdate extends Model
{
    protected $table="statistics_date";
    protected $primaryKey="user_id";
    public $timestamps=false;
    protected $guarded=[];
    protected $fillable = ['username','user_id','capital_flow','balance','today_recharge','today_withdrawal','one_order_commission','two_order_commission','statistics_date','top_one_uid','top_two_uid'];
    

    public function adddate($data){
        
    }
    
    public function editdate($data){
        
    }





}