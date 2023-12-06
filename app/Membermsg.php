<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Membermsg extends Model
{
    protected $table="membermsg";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['userid','username','title','content','status','from_name','types'];


    protected function Send($data){

        $Model = new Membermsg();
        $Model->userid=$data['userid'];
        $Model->username=$data['username'];
        $Model->title=$data['title'];
        $Model->content=$data['content'];
        $Model->status=0;
        $Model->from_name=$data['from_name'];
        $Model->types=$data['types'];
        $Model->save();
        return ["status"=>0];

    }
}
