<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stproduct extends Model
{
    protected $table="stproduct";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['name', 'content', 'brief', 'fee', 'store','firstlevel', 'secondlevel',"category_id","category_name"];



}
