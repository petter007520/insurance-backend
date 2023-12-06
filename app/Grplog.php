<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grplog extends Model
{
    protected $table="grplog";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['grp_id', 'user_id', 'value', 'grp_name', 'activity_id','status'];


}
