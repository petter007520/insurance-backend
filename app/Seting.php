<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seting extends Model
{
    protected $table = "setings";
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $guarded = [];
    protected $fillable = ['name', 'value', 'valuelist', 'type', 'sort', 'keyname'];
}