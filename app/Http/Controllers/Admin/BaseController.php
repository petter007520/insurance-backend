<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use DB;
use Cookie;
use Session;
use Illuminate\Support\Facades\Cache;
use App\Admin;
use App\Site;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use App\Productclassify;
use App\Product;
use App\Seting;



class BaseController extends Controller
{
    public function __construct(Request $request)
    {

        $this->DefaultTemplate=env('Template');
        $this->cachetime=1;

        $this->RouteName=  \Request::route()->getName();


        $RouteNames=  explode('.',$this->RouteName);

        $this->RouteController=$RouteNames[0].'.'.$RouteNames[1];
        $this->RouteAction=$RouteNames[2];

        $this->middleware(function($request, $next) {
            $Admin =$request->session()->get('Admin');
            $this->Admin = $Admin;

            return $next($request);
        });

    }

    function ShowTemplate($data=[]){

        $TemplateFile=  explode('.',$this->RouteName);

        if (view()->exists($this->DefaultTemplate."." . $TemplateFile[1]."." . $TemplateFile[2])) {

            return view($this->DefaultTemplate."." . $TemplateFile[1]."." . $TemplateFile[2],$data);
        }else{

            return view("hui.error",["msg"=>"系统错误 '".$this->DefaultTemplate."." . $TemplateFile[1]."." . $TemplateFile[2]."' 模板未找到","icon"=>"layui-icon-404"]);
            //dd($this->DefaultTemplate."." . $TemplateFile[1]."." . $TemplateFile[2]."-模板不存在");
        }

    }
}
