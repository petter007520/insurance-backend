<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Advertisementdata extends Model
{
    //advertisementdatas
    protected $table="advertisementdatas";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['name','thumb_url','url','sort','storeid','title','description','code'];



}
