<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teamrewards extends Model
{
    protected $table="teamrewards";
    protected $primaryKey="id";
    public $timestamps=false;
    protected $guarded=[];
    protected $fillable = ['team_num', 'team_amount', 'reward_amount', 'reward_equ', 'reward_equ_pid','reward_equ_num'];


}