<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Cache;

class Checklimit
{   
     public function handle($request, Closure $next)
    {

         $UserId =$request->session()->get('UserId');

         if($UserId > 0){
             if(Cache::has($UserId.'/'.$request->path())){
                return response()->json(['status'=>0,'msg'=>"点太快了."]);
             }else{
                Cache::put($UserId.'/'.$request->path(), '5', 3); 
             }
         }
        return $next($request);
        // $response = $next($request);
       
        // $a = json_encode($response);
        
        // if($request->data['status'] == '1'){
        //     $UserId =$request->session()->get('UserId');
        //     if($UserId > 0){
        //         if(Cache::has($UserId.'/'.$request->path())){
        //             return response()->json(['status'=>0,'msg'=>"点太快了."]);
        //         }else{
        //             Cache::put($UserId.'/'.$request->path(), '5', 6); 
        //         }
        //     }
        // }
        // return $response;
    }

    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
