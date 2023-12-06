<?php

namespace App\Http\Controllers\Admin;
use App\Position;
use DB;
use App\Seting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Cache;
use Form;
use Storage;
use Image;
use zgldh\QiniuStorage\QiniuStorage;
use GuzzleHttp\Exception\GuzzleException;

class UploadsController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }


    //上传缩略图
    public function uploadimg(Request $request)
    {

        if ($request->isMethod('post')) {


            if($request->file('file')){
                $file = $request->file('file');
            }else if($request->file('thumb')){
                $file = $request->file('thumb');
            }
            //$file = $request->file('thumb');

            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();     // image/jpeg

                // 上传文件

                $path = date ( 'Ymd'); // 接收文件目录

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

               /* $config=Seting::where("keyname","=","thumbsize")->first();
                $imgconfig=explode("*",$config->value);



                if($img->width()> $img->height()){
                      $img->widen( $imgconfig[0], function($constraint){       // 阻止可能的尺寸变化(保持图像大小)
                            $constraint->aspectRatio();
                            $constraint->upsize();
                      });
                }else if($img->width() < $img->height()){

                    $img->heighten( $imgconfig[1], function($constraint){       // 阻止可能的尺寸变化(保持图像大小)
                          $constraint->aspectRatio();
                          $constraint->upsize();
                   });


                }else{
                    $img->resize($imgconfig[0],$imgconfig[1]);
                }*/

                $img->save("uploads/".$file_path."/".$filename);
               // $img->crop($imgconfig[0],$imgconfig[1]);
               // $img->save("uploads/".$file_path."/thumb_".$filename);
               // $imgurl=$this->saveqiniu("/uploads/".$file_path."/thumb_".$filename);
                $imgurl=$this->saveqiniu("/uploads/".$file_path."/".$filename);
                return ["status"=>0 ,"msg"=>"上传成功","src"=>$imgurl];

            }

        }

    }


    //上传推荐位的图片缩略图
    public function uploadposdataimg(Request $request)
    {

        if ($request->isMethod('post')) {



          $file = $request->file('thumb');



            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();     // image/jpeg

                // 上传文件

                $path = date ( 'Ymd'); // 接收文件目录

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

/*
                $config =Position::where("id",$request->input("posid"))->first();


                if(!$config){
                    return ["status"=>1 ,"msg"=>"推荐位不存在"];
                }

                $imgconfig=explode("*",$config->thumb_size);

                if(count($imgconfig)<2){
                    return ["status"=>1 ,"msg"=>"上传失败"];
                }
*/




                $img = Image::make($file);


/*                if($img->width()> $img->height()){
                    $img->widen( $imgconfig[0], function($constraint){       // 阻止可能的尺寸变化(保持图像大小)
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }else if($img->width() < $img->height()){

                    $img->heighten( $imgconfig[1], function($constraint){       // 阻止可能的尺寸变化(保持图像大小)
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });


                }else{
                    $img->resize($imgconfig[0],$imgconfig[1]);
                }*/

                $img->save("uploads/".$file_path."/thumb_".$filename);

                $imgurl=$this->saveqiniu("/uploads/".$file_path."/thumb_".$filename);
                return ["status"=>0 ,"msg"=>"上传成功","src"=>$imgurl];

            }

        }

    }


    // 使用我们新建的uploads本地存储空间（目录）
    //  $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));

    /*
    //$img = Image::make($realPath)->resize(200)->insert($realPath, 'bottom-right', 15, 10)->save("uploads/aa.jpg");
    $img = Image::make($realPath)->resize(500)->save("uploads/aa.jpg");
    $img = Image::make($realPath);

    $img->resize(300, null, function($constraint){       // 调整图像的宽到300，并约束宽高比(高自动)
        $constraint->aspectRatio();
    });
    $img ->save("uploads/aaccdd1.jpg");

    $img = Image::make($realPath);
    $img->resize(null, 200, function($constraint){       // 调整图像的高到200，并约束宽高比(宽自动)
        $constraint->aspectRatio();
    });
    $img ->save("uploads/aaccdd2.jpg");
    $img = Image::make($realPath);
    $img->resize(null, 400, function($constraint){       // 阻止可能的尺寸变化(保持图像大小)
        $constraint->aspectRatio();
        $constraint->upsize();
    });
    $img ->save("uploads/aaccdd3.jpg");



    $img = Image::make($realPath);
    $img->widen( 480, function($constraint){       // 阻止可能的尺寸变化(保持图像大小)
        $constraint->aspectRatio();
        $constraint->upsize();
    });
    $img ->save("uploads/aaccdd3-w.jpg");

    $img = Image::make($realPath);
    $img->heighten( 490, function($constraint){       // 阻止可能的尺寸变化(保持图像大小)
        $constraint->aspectRatio();
        $constraint->upsize();
    });
    $img ->save("uploads/aaccdd3-h.jpg");


    $img = Image::make($realPath);

    $img = Image::make($realPath)->resizeCanvas(200, 200, 'bottom-right')->save("uploads/aabb.jpg");

    */


//laredit
    public function uploadeditorimg(Request $request)
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

                $path = date ( 'Ymd'); // 接收文件目录

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

                $config=Seting::where("keyname","=","thumbsize")->first();
                $water_config=Seting::where("keyname","=","water")->first();


                $imgconfig=explode("*",$config->value);
                $img = Image::make($file);

                if($water_config->value=='开启' || $water_config->value=='on'){

                    $watermark_config=Seting::where("keyname","=","watermark")->first();
                    $waterpos_config=Seting::where("keyname","=","waterposition")->first();
                    $img->insert("uploads/".$watermark_config->value, $waterpos_config->value, 10, 10)->save("uploads/".$file_path."/".$filename);

                }else{
                    $img->save("uploads/".$file_path."/".$filename);
                }


                $img->save("uploads/".$file_path."/".$filename);

                $imgurl=$this->saveqiniu("/uploads/".$file_path."/".$filename);

                return ["code"=>0 ,"msg"=>"上传成功","data"=>["src"=>$imgurl,"title"=>$originalName]];


            }

        }

    }


//上传栏目图片
    public function uploadclassifyimgage(Request $request)
    {

        if ($request->isMethod('post')) {


            $file = $request->file('thumb');

            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();     // image/jpeg

                // 上传文件

                $path = date ( 'Ymd'); // 接收文件目录

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

                $imgurl=$this->saveqiniu("/uploads/".$file_path."/".$filename);
                //return ["status"=>0 ,"msg"=>"上传成功","src"=>$imgurl];
                return ["status"=>0 ,"msg"=>"上传成功","src"=>$imgurl,"title"=>$originalName];


            }

        }

    }

//上传视频文件
    public function uploadfile(Request $request)
    {

        if ($request->isMethod('post')) {


            $file = $request->file('files');

            // 文件是否上传成功
            if ($file->isValid()) {

                // 获取文件相关信息
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();     // image/jpeg

                // 上传文件

                $path = date ( 'Ymd'); // 接收文件目录


                $filename = time(). uniqid() . '.' . $ext;


                $file->move("./uploads/video/",$filename);
                $url= url("/uploads/video/".$filename);

                return ["status"=>0 ,"msg"=>"上传成功","src"=>$url,"title"=>$originalName];


            }

        }

    }


    public function saveqiniu($imgurl){

        return $imgurl;

/*        $file='/adimg/'.$imgurl;
        $url= url($imgurl);

        try {
            $client = new \GuzzleHttp\Client();
            $data = $client->request('get',$url)->getBody()->getContents();
            $disk =QiniuStorage::disk('qiniu');
            $disk->put($file, $data);
            $imgurls=$disk->downloadUrl($file);
            return $imgurls;

        } catch (\GuzzleHttp\RequestException $e) {
            // echo 'fetch fail';
        }*/

    }

}
