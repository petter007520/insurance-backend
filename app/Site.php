<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use DB;
class Site extends Model
{
    protected $table="sites";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = [
        'name',
        'domain',
        'logo',
        'seotitle',
        'template',
        'keywords',
        'description',
        'sort',
        'adminid',
        'disabled'
    ];


    public function getSiteIds(){
        $adminAuthID =\Request::session()->get('adminAuthID');
        $adminID =\Request::session()->get('adminID');

        $Where=[];
        $siteids=[];
        if($adminAuthID>1){
            $Where=["adminid"=>$adminID];
        }


       return $sites=  Site::where($Where)->get();
    }
    /*public function GetStoreList(){

        $sessions =Session::get('admin');
        $Admin= unserialize($sessions);
        if($Admin->authid==1){
            $storelist=DB::table("stores")
                ->where("parent",0)
               ->orderBy(
                    "parent","asc"
                )
                ->orderBy(
                    "sort","desc"
                )->get();

            if($storelist){
                foreach($storelist as $v){
                    $v->Children=DB::table("stores")
                        ->where("parent",$v->id)
                        ->orderBy(
                            "parent","asc"
                        )
                        ->orderBy(
                            "sort","desc"
                        )->get();
                }
            }
        }else{
            $storelist=DB::table("stores")
                ->where("parent",0)

                ->orderBy(
                    "parent","asc"
                )
                ->orderBy(
                    "sort","desc"
                )->get();


            if($storelist){
                foreach($storelist as $v){
                    $v->Children=DB::table("stores")
                        ->where("parent",$v->id)

                        ->orderBy(
                            "parent","asc"
                        )
                        ->orderBy(
                            "sort","desc"
                        )->get();
                }
            }

        }


        return $storelist;

    }


    public function GetAuthStoreList(){

        $sessions =Session::get('admin');
        $Admin= unserialize($sessions);
        if($Admin->authid==1){
            $storelist=DB::table("stores")
                ->orderBy(
                    "parent","asc"
                )
                ->orderBy(
                    "sort","desc"
                )->get();

        }else{
            $storelist=DB::table("stores")

                ->orderBy(
                    "parent","asc"
                )
                ->orderBy(
                    "sort","desc"
                )->get();
        }

        $store_auth=[];
        $store_auth[]=0;
        if($storelist){
            foreach($storelist as $v){
                $store_auth[]=$v->id;
            }
        }


        return $store_auth;

    }*/
}
