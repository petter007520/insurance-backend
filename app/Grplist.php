<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grplist extends Model
{
    protected $table="grplist";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['name', 'value', 'rate', 'type', 'detail','status', 'weight', 'stock'];


}
