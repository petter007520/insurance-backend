<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cache;

class Stproductbuy extends Model
{
    protected $table="stproductbuy";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['userid','username','stproductid', 'stnum','fee','signfee','mobile','provence','city','area','address','stpname','issh',
        'delivery',
        'deliverysno',
        'express',
        'shnote',
        'deliverytime',
        'shtime'
    ];


}
