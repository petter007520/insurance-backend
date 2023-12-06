<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Extrabonustype extends Model
{
    protected $table="extra_bonus_type";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    // protected $fillable = ['grp_id', 'user_id', 'value', 'grp_name', 'activity_id','status'];


}
