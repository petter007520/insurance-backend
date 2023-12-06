<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;

class Signlog extends Model
{
    protected $table="signlog";
    protected $primaryKey="user_id";
    public $timestamps=false;
    protected $guarded=[];
    protected $fillable = ['user_id','username','lastqiandao','sign_year','sign_day','qd_count'];
    
        protected $dates = ['created_at'];
    
    
    public function adddate($data){
        
    }
    
    public function editdate($data){
        
        
    }
    
    
}