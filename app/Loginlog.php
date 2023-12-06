<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loginlog extends Model
{
    protected $table="loginlogs";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['ip','logintime','adminid'];
}
