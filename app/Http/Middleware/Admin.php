<?php
namespace App\Http\Middleware;

use Carbon\Carbon;

use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Cookie;
use Illuminate\Support\Facades\DB;
use Cache;
use Closure;
use Storage;
use App\Administrators;
use App\Log;
use App\Authorized;
use Illuminate\Support\Facades\Log as loglog;

use Illuminate\Support\Facades\Crypt;


class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**网站缓存功能生成**/

        if(!Cache::has('setings')){
            $setings=DB::table("setings")->get();

            if($setings){
                $seting_cachetime=DB::table("setings")->where("keyname","=","cachetime")->first();

                if($seting_cachetime){
                    $this->cachetime=$seting_cachetime->value;
                    Cache::forever($seting_cachetime->keyname, $seting_cachetime->value);
                }

                foreach($setings as $sv){
                    Cache::forever($sv->keyname, $sv->value);
                }
                Cache::forever("setings", $setings);
            }

        }

        /**A网站缓存功能生成 end**/


        /**管理员登录验证**/


        $adminID =$request->session()->get('adminID');
        $Admin =$request->session()->get('Admin');



        if($adminID<1 || $adminID!=$Admin->id){
            return redirect()->route("login");
        }

        $adminAuthID =$request->session()->get('adminAuthID');
        $adminName =$request->session()->get('adminName');
        $adminUserName =$request->session()->get('adminUserName');


        view()->share('Admin',$Admin);
        view()->share('adminID',$adminID);
        view()->share('adminAuthID',$adminAuthID);
        view()->share('adminName',$adminName);
        view()->share('adminUserName',$adminUserName);




        /**管理员登录验证 end**/


        /**权限验证功能**/

        $RouteName=  \Request::route()->getName();/**路由名称**/

        $RouteNames=  explode('.',$RouteName);

        view()->share('ThisRouteName',$RouteName);
        view()->share('RouteController',$RouteNames[0].'.'.$RouteNames[1]);
        view()->share('RouteAction',$RouteNames[2]);




        /**菜单路由缓存数据**/
        if(Cache::has('RouteList')){
            $RouteList=Cache::get('RouteList');
        }else {
            $RouteList = [];
            $gmenus = DB::table("menus")->get();
            foreach ($gmenus as $r) {
                $RouteList[] = $r->model_name . '.' . $r->contr_name . '.' . $r->action_name;
            }

            Cache::forever('RouteList',$RouteList);
        }


        /**菜单路由缓存数据**/
        if(Cache::has('RouteListName')){
            $RouteListName=Cache::get('RouteListName');
        }else {
            $RouteListName = [];
            $gmenus = DB::table("menus")->get();
            foreach ($gmenus as $r) {
                $RouteListName[$r->model_name . '.' . $r->contr_name . '.' . $r->action_name] =$r->name ;
            }

            Cache::forever('RouteListName',$RouteListName);
        }



        $menus=[];
        $menus_top=[];
        $Auth_Route=[];

        if($Admin->authid){

            /**权限路由缓存数据**/
            if(Cache::has('RouteListAuth_'.$Admin->authid)){
                $Auth=Cache::get('RouteListAuth_'.$Admin->authid);
            }else {
                $Auth=DB::table("auth")->where([["id","=",$Admin->authid]])->first();

                Cache::forever('RouteListAuth_'.$Admin->authid,$Auth);
            }
            $Auth=DB::table("auth")->where([["id","=",$Admin->authid]])->first();

            Cache::forever('RouteListAuth_'.$Admin->authid,$Auth);

            if(!empty($Auth)){
                $Auth_Route=$Auth_admin=json_decode($Auth->authority,true);

                $Menu_parent=DB::table("menus")->where([["parent","=","0"],["disabled","=","0"],["ismenuleft","=","1"]])->orderBy("sort","desc")->get();
                if($Menu_parent){
                    foreach($Menu_parent as  $Menu){

                        if(count($Auth_admin)>0 && in_array($Menu->model_name.'.'.$Menu->contr_name.'.'.$Menu->action_name,$Auth_admin)) {


                            $menus[$Menu->id]['id'] = $Menu->id;
                            $menus[$Menu->id]['name'] = $Menu->name;
                            $menus[$Menu->id]['icon'] = $Menu->icon;
                            $menus[$Menu->id]['action_name'] = $Menu->action_name;
                            $menus[$Menu->id]['route'] = route($Menu->model_name . '.' . $Menu->contr_name . '.' . $Menu->action_name);
                            $MenuC = DB::table("menus")->where([["parent", "=", $Menu->id], ["disabled", "=", "0"], ["ismenuleft", "=", "1"]])->orderBy("sort", "desc")->get();
                            foreach ($MenuC as $mu) {
                                if (in_array($mu->model_name . '.' . $mu->contr_name . '.' . $mu->action_name, $Auth_admin)) {
                                    if ($mu->action_name != 'store' && $mu->action_name != 'update' && $mu->action_name != 'delete') {
                                        $menus[$Menu->id]['list'][$mu->id]['id'] = $mu->id;
                                        $menus[$Menu->id]['list'][$mu->id]['name'] = $mu->name;
                                        $menus[$Menu->id]['list'][$mu->id]['sort'] = $mu->sort;
                                        $menus[$Menu->id]['list'][$mu->id]['icon'] = $mu->icon;
                                        $menus[$Menu->id]['list'][$mu->id]['action_name'] = $mu->action_name;

                                        $menus[$Menu->id]['list'][$mu->id]['route'] = route($mu->model_name . '.' . $mu->contr_name . '.' . $mu->action_name);

                                    }

                                }
                            }
                        }
                        //

                    }

                }



                /*顶部*/


                $Menu_parent_top=DB::table("menus")->where([["parent","=","0"],["disabled","=","0"]])->orderBy("sort","desc")->get();
                if($Menu_parent_top){
                    foreach($Menu_parent_top as  $Menu){

                        if(count($Auth_admin)>0 && in_array($Menu->model_name.'.'.$Menu->contr_name.'.'.$Menu->action_name,$Auth_admin)) {


                            $menus_top[$Menu->id]['id'] = $Menu->id;
                            $menus_top[$Menu->id]['ismenutop'] = $Menu->ismenutop;
                            $menus_top[$Menu->id]['name'] = $Menu->name;
                            $menus_top[$Menu->id]['icon'] = $Menu->icon;
                            $menus_top[$Menu->id]['action_name'] = $Menu->action_name;
                            $menus_top[$Menu->id]['route'] = route($Menu->model_name . '.' . $Menu->contr_name . '.' . $Menu->action_name);
                            $MenuC = DB::table("menus")->where([["parent", "=", $Menu->id], ["disabled", "=", "0"], ["ismenutop", "=", "1"]])->orderBy("sort", "desc")->get();
                            foreach ($MenuC as $mu) {
                                if (in_array($mu->model_name . '.' . $mu->contr_name . '.' . $mu->action_name, $Auth_admin)) {
                                    if ($mu->action_name != 'update' && $mu->action_name != 'delete') {
                                        $menus_top[$Menu->id]['list'][$mu->id]['id'] = $mu->id;
                                        $menus_top[$Menu->id]['list'][$mu->id]['name'] = $mu->name;
                                        $menus_top[$Menu->id]['list'][$mu->id]['sort'] = $mu->sort;
                                        $menus_top[$Menu->id]['list'][$mu->id]['icon'] = $mu->icon;
                                        $menus_top[$Menu->id]['list'][$mu->id]['action_name'] = $mu->action_name;

                                        $menus_top[$Menu->id]['list'][$mu->id]['route'] = route($mu->model_name . '.' . $mu->contr_name . '.' . $mu->action_name);

                                    }

                                }
                            }
                        }
                        //

                    }

                }

            }


        }

        $title='后台管理';
        if(isset($RouteListName[$RouteName])){
            $title=  $RouteListName[$RouteName];
        }

        view()->share('menus_top',$menus_top);
        view()->share('menus',$menus);
        view()->share('title',$title);



        //下线会员ID
        $Admin=new Administrators();
        $Admins= $Admin->getAdmin();

        view()->share("Admins",$Admins);



        $Where=[];
        if($adminAuthID>1){
            $Where=["adminid"=>$adminID];
        }


        if(in_array($RouteNames[0].'.'.$RouteNames[1].'.store',$Auth_Route)){
            view()->share('store',1);
        }else{

            view()->share('store',0);
        }

        if(in_array($RouteNames[0].'.'.$RouteNames[1].'.update',$Auth_Route)){
            view()->share('update',1);
        }else{
            view()->share('update',0);
        }

        if(in_array($RouteNames[0].'.'.$RouteNames[1].'.delete',$Auth_Route)){
            view()->share('delete',1);
        }else{
            view()->share('delete',0);
        }


        if(in_array($RouteName,$RouteList) && !in_array($RouteName,$Auth_Route)){
            if($request->ajax()){
                $repo = ['status'=>1,'msg'=>'您没有权限访问该页面'];
                  return response()->json($repo);

            }
            return response()->view("hui.error",["msg"=>"您没有权限访问该页面 '".route($RouteName)."'","icon"=>"layui-icon-404"]);
        }

        $datas=$request->all();
        unset($datas['_token']);

        if($request->isMethod('post') && $RouteNames[2]!='lists' && count($datas)>0){

            if($RouteNames[2]=='delete' && in_array($RouteNames[1],['memberwithdrawal','member','productbuy'])){
                if(isset($datas['ids'])){
                     $table_name = $RouteNames[1];
                   $datas = DB::table($table_name)
                    // ->select('productbuy'.'.*')
                    ->whereIn('id',$datas['ids'])
                    ->get();
                }else{
                     $table_name = $RouteNames[1];
                   $datas = DB::table($table_name)
                    // ->select('productbuy'.'.*')
                    ->where(['id'=>$datas])
                    ->first();
                }
            //   $table_name = $RouteNames[1];
            //   $datas = DB::table($table_name)
            //     // ->select('productbuy'.'.*')
            //     ->where(['id'=>$datas])
            //     ->first();
            }

            $Log=new Log();
            $Log->title=$title;
            $Log->ip=$request->getClientIp();
            $Log->url=$request->url();
            $Log->username=$adminUserName;
            $Log->type="admin";
            $Log->datas=json_encode($datas,JSON_UNESCAPED_UNICODE);
            $Log->save();

            loglog::channel('adminlog')->warning('['.$adminUserName.']['.$title.']'.$Log->datas);
        }


        return $next($request);
    }



}
