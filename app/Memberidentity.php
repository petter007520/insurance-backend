<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;

class Memberidentity extends Model
{
    protected $table="memberidentity";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['userid','realname','idnumber','facade_img','revoit_img','status'];
    
}
