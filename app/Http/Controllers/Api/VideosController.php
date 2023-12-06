<?php

namespace App\Http\Controllers\Api;
use App\Auth;
use App\Category;
use App\Channel;
use App\Http\Controllers\Controller;
use App\Member;
use Carbon\Carbon;
use DB;
use App\Admin;
use App\Ad;
use App\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VideosController extends Controller
{
    public $cachetime=600;
    public $Template='wap';
    public function __construct(Request $request)
    {

        $this->Template=env("WapTemplate");
        $this->middleware(function ($request, $next) {
            //dd($request->session()->all());
            //$request->session()->put('UserId',502, 120);
           $lastsession = $request->lastsession;
            if(!$lastsession){
                return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
            }else{
                $Member = Member::where("lastsession",$request->lastsession)->first();
                if(!$Member){
                    return response()->json(["status"=>-1,"msg"=>"请先登录！"]);
                }else{
                    $request->session()->put('UserId',$Member->id, 120);
                    $request->session()->put('UserName',$Member->username, 120);
                    $request->session()->put('Member',$Member, 120);
                }
            }

            $UserId =$request->session()->get('UserId');

            if($UserId<1){
                return response()->json(["status"=>-1,"msg"=>"请先登录!"]);
            }else{
              $this->Member = Member::find($UserId);
              if(!$this->Member){
                 return response()->json(["status"=>-1,"msg"=>"请先登录!"]);
              }
            }


            return $next($request);
        });


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

        $this->cachetime=Cache::get('cachetime');

        if(Cache::has('memberlevel.list')){
            $memberlevel=Cache::get('memberlevel.list');
        }else{
            $memberlevel= DB::table("memberlevel")->orderBy("id","asc")->get();
            Cache::get('memberlevel.list',$memberlevel,Cache::get("cachetime"));
        }

        $memberlevelName=[];
        foreach($memberlevel as $item){
            $memberlevelName[$item->id]=$item->name;
        }

        $this->memberlevelName=$memberlevelName;

    }

    /***视频列表***/
    public function index(Request $request){

        $UserId = $request->session()->get('UserId');
        
        $pagesize=6;
        $pagesize=Cache::get("pcpagesize");

        $data['list'] =  DB::table("videos as v")
        ->leftjoin('category as c','c.id','=','v.video_category')
        ->select('v.*','c.name')
        ->where('status',1)
        ->orderBy("id","desc")
        ->paginate($pagesize);
        
        foreach ($data['list'] as $key => $value) {
          $value->likes = DB::table("videoslog")->where('videoid',$value->id)->count();
          $mylike = DB::table("videoslog")->where(['videoid'=>$value->id,'userid'=>$UserId])->count();
          $value->mylike = $mylike > 0 ? true : false ;
        }

        return response()->json(['status'=>1,'data'=>$data]);
    }

    /***视频详情***/
    public function detail(Request $request){

        $UserId = $request->session()->get('UserId');

        $videoid = $request->id;

        if(!$videoid){
          return response()->json(['status'=>0,'msg'=>'参数错误']);
        }

        $data =  DB::table("videos")->where(['id'=>$videoid,'status'=>1])->get();

        $data['likes'] = DB::table("videoslog")->where('videoid',$videoid)->count();

        $mylike = DB::table("videoslog")->where(['videoid'=>$videoid,'userid'=>$UserId])->count();

        $data['mylike'] = $mylike > 0 ? true : false ;

        $data['picImg'] = rand(1,8);

        DB::table("videos")->where(['id'=>$videoid,'status'=>1])->increment('read');

        return response()->json(['status'=>1,'data'=>$data]);
    }

    /***视频点赞***/
    public function like(Request $request){

        $UserId = $request->session()->get('UserId');

        $videoid = $request->id;

        if(!$videoid){
          return response()->json(['status'=>0,'msg'=>'参数错误']);
        }

        $data = [
           'userid' => $UserId,
           'videoid' => $videoid
        ];

        $check = DB::table("videoslog")->where($data)->first();

        if(!$check){
          if(DB::table("videoslog")->insert($data)){
             return response()->json(['status'=>1,'msg'=>'点赞成功']);
          }else{
             return response()->json(['status'=>0,'msg'=>'点赞失败']);
          }
        }else{
          if(DB::table("videoslog")->where($data)->delete()){
             return response()->json(['status'=>1,'msg'=>'取消点赞成功']);
          }else{
             return response()->json(['status'=>0,'msg'=>'取消点赞失败']);
          }
        }
    }
}


?>
