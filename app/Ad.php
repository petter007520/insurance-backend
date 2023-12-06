<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Advertisement;
use App\Advertisementdata;
use Cache;
use Illuminate\Contracts\Session\Session;
use DB;


class Ad extends Model
{

    public function GetAd($name){

        $Adver=new Advertisement();
        $AdData=new Advertisementdata();
        $Advertis= $Adver->where("name",$name)->first();



        if($Advertis){

            return  $Data=$AdData->where("adverid",$Advertis->id)->orderBy("sort","desc")->limit($Advertis->maxnum)->get();

            /*if (view()->exists($Advertis->modelname)) {
                return $adv = view($Advertis->modelname, ["adlist" => $Data]);
            }*/
        }

    }










}
