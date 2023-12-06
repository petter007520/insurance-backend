<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table="logs";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['title','ip','time','datas','url','username','type'];
}
