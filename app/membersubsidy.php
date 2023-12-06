<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;

class membersubsidy extends Model
{
    protected $table="membersubsidy";
    protected $primaryKey="uid";
    public $timestamps=false;
    protected $guarded=[];
    protected $fillable = ['id','uid','subsidy','username','Issuing_time','created_at','next_Issuing_time'];
    protected $treeuid=[];
    protected $treelv=[];






}
