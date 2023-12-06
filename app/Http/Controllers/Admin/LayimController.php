<?php

namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use DB;
use App\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Cache;
use App\Seting;
use Intervention\Image\Facades\Image;
use Storage;

class LayimController extends BaseController
{

    public $table='layims';

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }



    /***消息拉取***/
    public function getmsg(Request $request){


        $UserId ="-1";


        $layims= DB::table("layims")->where("touid",$UserId)->where("status",0)->orderBy("id","asc")->first();
        if($layims){
            DB::table("layims")->where("id",$layims->id)->update(["status"=>1]);

            $msg['username']=$layims->fusername;
            $msg['id']=$layims->fromuid;
            $msg['type']=$layims->type;
            $msg['type']=$layims->type;
            $msg['content']=$layims->content;
            $msg['avatar']=asset("layim/images/avatar/".($layims->fromuid%10).".jpg");
           // $msg['avatar']=asset("layim/images/avatar/kf.png");
            return $msg;
        }


    }

    /***消息发送***/
    public function send(Request $request){

        $data['fusername'] =  $request->fusername;
        $data['tousername'] =  $request->username;
        $data['fromuid'] =  $request->fid;
        $data['touid'] =  $request->id;
        $data['type'] =  $request->type;
        $data['content'] =  $request->content;
        $data['created_at']=Carbon::now();
        $data['updated_at']=Carbon::now();





        $id= DB::table("layims")->insertGetId($data);


    }


    public function chatlog(Request $request){

        $UserId =$request->id;
        $type =$request->type;


        $layimdatas= DB::table("layims")->orwhere("fromuid",$UserId)->orwhere("touid",$UserId)->where("type",$type)->orderBy("id","asc")->get();
        if($layimdatas){
            $msg=[];
            foreach($layimdatas as $k=>$layims){
                $msg[$k]['username']=$layims->fusername;
                $msg[$k]['id']=$layims->fromuid;
                $msg[$k]['type']=$layims->type;
                $msg[$k]['created_at']=$layims->created_at;
                $msg[$k]['content']=$layims->content;
                if($UserId==$layims->fromuid>0){
                    $msg[$k]['avatar']=asset("layim/images/avatar/".($layims->fromuid%10).".jpg");
                }else{
                    $msg[$k]['avatar']=asset("layim/images/avatar/kf.png");
                }

            }



            return $this->ShowTemplate(["msg"=>$msg]);
        }


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
