<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $table="auth";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['name','sort','disabled'];

}
