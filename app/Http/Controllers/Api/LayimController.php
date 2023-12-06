<?php

namespace App\Http\Controllers\Wap;
use App\Auth;
use App\Category;
use App\Channel;
use App\Http\Controllers\Controller;
use App\Member;
use App\Memberlevel;
use App\Membermsg;
use App\Memberphone;
use App\Memberticheng;
use App\Order;
use App\Product;
use App\Productbuy;
use App\Seting;
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
use Storage;


class LayimController extends Controller
{
    public $cachetime=600;
    public function __construct(Request $request)
    {

        $this->Template=env("WapTemplate");
        $this->middleware(function ($request, $next) {

            $lastsession = $request->header('lastsession');
            if(!$lastsession){
                $request->session()->put('UserId',502, 120);
            }else{
                $Member = Member::where("lastsession",$lastsession)->first();
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
                return redirect()->route("wap.login");
            }
           $this->Member= Member::find($UserId);
            view()->share("Member",$this->Member);
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
        /**菜单导航栏**/
        if(Cache::has('wap.category')){
            $footcategory=Cache::get('wap.category');
        }else{
            $footcategory= DB::table('category')->where("atfoot","1")->orderBy("sort","desc")->limit(5)->get();
            Cache::put('wap.category',$footcategory,$this->cachetime);
        }
        view()->share("footcategory",$footcategory);
        /**菜单导航栏 END **/

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

        view()->share("memberlevel",$memberlevel);
        view()->share("memberlevelName",$memberlevelName);
    }

    /***会员中心***/
    public function index(Request $request){
        return view($this->Template.".layim.kefuadmin");
    }

    /***消息拉取***/
    public function getmsg(Request $request){
        $UserId =$request->session()->get('UserId');
        $layims= DB::table("layims")->where("touid",$UserId)->where("status",0)->orderBy("id","asc")->first();
        if($layims){
            DB::table("layims")->where("id",$layims->id)->update(["status"=>1]);
            $msg['username']=$layims->fusername;
            $msg['id']=$layims->fromuid;
            $msg['type']=$layims->type;
            $msg['content']=$layims->content;
            //$msg['avatar']=asset("layim/images/avatar/".($layims->touid%10).".jpg");
            $msg['avatar']=asset("layim/images/avatar/kf.png");
            return $msg;
        }
    }

    /***消息发送***/
    public function send(Request $request){


        $UserId =$request->session()->get('UserId');
       // $msg= $request->all();

        $data['fusername'] =  $request->fusername;
        $data['tousername'] =  $request->username;
        $data['fromuid'] =  $request->fid;
        $data['touid'] =  $request->id;
        $data['type'] =  $request->type;
//        $data['content'] =  $request->content;
        $data['created_at']=Carbon::now();
        $data['updated_at']=Carbon::now();
       DB::table("layims")->insertGetId($data);
    }

    /***在线客服***/
    public function kefu(Request $request){


        $UserId =$request->session()->get('UserId');
        return view($this->Template.".layim.kefu");
       // return view("wap.layim.kefu");


    }


    //上传图片
    public function uploadimgage(Request $request)
    {

        if ($request->isMethod('post')) {


            $file = $request->file('file');

            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();     // image/jpeg

                // 上传文件

                $path = "layim/".date ( 'Ymd'); // 接收文件目录

                if($request->get("filename")!=''){
                    $filename = $request->get("filename");
                }else{
                    $filename = time(). uniqid() . '.' . $ext;
                }


                $filepath=Seting::where("keyname","=","filepath")->first();

                $file_path=$filepath->value.'/'.$path;
                if (! file_exists ( "uploads/".$file_path )) {

                    Storage::disk("uploads")->makeDirectory($file_path);
                }

                $img = Image::make($file);


                $img->save("uploads/".$file_path."/".$filename);
                $imgurl="/uploads/".$file_path."/".$filename;


                return ["code"=>0 ,"msg"=>"上传成功","data"=>["src"=>$imgurl]];


            }

        }

    }




}


?>
