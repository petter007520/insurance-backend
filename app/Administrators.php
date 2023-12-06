<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;
use DB;


class Administrators extends Model
{
    protected $table="admins";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['username','password','email','authid','name','phone','clubid','consume','offdate'];
    protected $dates = ['created_at', 'updated_at', 'disabled_at'];

    public function getAdmin(){

        $adminAuthID =\Request::session()->get('adminAuthID');
        $adminID =\Request::session()->get('adminID');

        $Where=[];
        if($adminAuthID>1){
            $Where=["adminid"=>$adminID];
            return Administrators::where($Where)->orwhere("id",$adminID)->get();
        }else{
            return Administrators::where($Where)->get();
        }


    }
}
