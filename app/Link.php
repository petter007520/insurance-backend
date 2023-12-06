<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    //Link
    protected $table="links";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['name','thumb_url','url','sort'];
}
