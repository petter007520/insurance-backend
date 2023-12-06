<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Celebrity extends Model
{

    protected $table="celebritys";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['category_id', 'category_name', 'title', 'author', 'keyinfo','descr', 'image', 'content', 'status', 'click_count', 'top_status', 'top_time','sort','CelebrityType'];


}
