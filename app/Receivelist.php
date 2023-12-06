<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Receivelist extends Model
{
    //contact
    protected $table="receivelist";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['userid','probuy_id','name','mobile','idcard','gqorder','address','status','explain'];


}
