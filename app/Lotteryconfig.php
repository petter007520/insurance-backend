<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Session\Session;
use Cache;
use DB;


class Lotteryconfig extends Model
{
    protected $table="lotteryconfig";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['name','moneys','prize','winningrate','img'];






}
