<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;

class Memberticheng extends Model
{
    protected $table="memberticheng";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['name','percent'];





}
