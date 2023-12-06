<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stcate extends Model
{
    protected $table="stcate";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['name', 'sort'];



}
