<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;

class statistics extends Model
{
    protected $table="statistics";
    protected $primaryKey="user_id";
    public $timestamps=false;
    protected $guarded=[];
    protected $fillable = ['username','user_id','capital_flow','team_balance','team_capital_flow','team_total_recharge','team_total_withdrawal','team_order_commission','first_charge_count','direct_push_count','teams_count','new_user_count','active_user_count','muland','soc_security','insurance','est_salary'];
    

    public function adddate($data){
        
    }
    
    public function editdate($data){
        
    }





}
