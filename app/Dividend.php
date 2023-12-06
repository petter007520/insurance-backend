<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dividend extends Model
{
    protected $table="dividend_type";
    protected $primaryKey="id";
    public $timestamps=false;
    protected $guarded=[];
    protected $fillable = ['type_name'];


}