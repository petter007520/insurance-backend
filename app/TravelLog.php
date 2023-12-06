<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Crypt;
use DB;
use Cache;
use App\Membercurrencys;

class TravelLog extends Model
{
    protected $table="travellog";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = [
        'userid',
        'username',
        'travel_id',
        'travel_name',
        'status',
        ];
    protected $dates = ['created_at', 'updated_at'];


}
