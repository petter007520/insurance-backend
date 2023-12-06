<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ArticlesController extends Controller
{

    /***新闻列表***/
    public function index(Request $request){
        $page = $request->get('page',"1");
        $pageSize = $request->get('pageSize',"10");
        $category_id = $request->get('category_id',6);
        $res =  DB::table("articles")->select('id','title','image','updated_at','content','click_count','key','descr')->where(['status'=>2,'category_id'=>$category_id])->whereIn('key',['new','video-new','video-trend','new-list','trend-list','about'])->orderBy("top_status","desc")
            ->paginate($pageSize, ['*'], 'page', $page);
        foreach($res as $v){
            $v->updated_at = substr($v->updated_at,0,16);
        }
        $data['list'] = $res;
        //banner
        if(Cache::has("articles_detail_new_banner")){
            $img = Cache::get("articles_detail_new_banner");
        }else{
            $img = DB::table('setings')->where(['keyname'=>'news_list_img'])->value('value');
            Cache::forever("articles_detail_new_banner",$img);
        }
        $data['banner_img'] = $img;
        return response()->json(['status'=>1,'data'=>$data]);
    }

    /***资新闻详情***/
    public function detail(Request $request){
        $articleId = $request->get('id',0);
        if(Cache::has("articles_detail_".$articleId)){
            $datas = Cache::get("articles_detail_".$articleId);
        }else{
            $datas =  DB::table("articles")->select('category_id','content','image','title','keyinfo','updated_at','descr','click_count','video_url')->where(['id'=>$articleId,'status'=>2])->first();
            $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
           // $datas->content = preg_replace($pregRule, '<img src="' . ENV('FILE_URL') . '${1}" style="width:100%">', $datas->content);
//            $url = "http://".$_SERVER ['HTTP_HOST'];
//            $datas->content = str_replace("<img src=\"/","<img src=\"".$url."/",$datas->content);
//            $datas->content = str_replace("<img src=\"h","<img src=\"h",$datas->content);
            $datas->updated_at = substr($datas->updated_at,0,16);
            $datas->video_url = !empty($datas->video_url) ? explode('|',$datas->video_url):[];
            Cache::forever("articles_detail_".$articleId,$datas);
        }
        return response()->json(['status'=>1,'data'=>$datas]);
    }

    public function detailWithType(Request $request){
        $type = $request->get('type',0);
        $data =  DB::table("articles")->select('category_id','content','image','title','keyinfo','updated_at','descr','click_count')->where(['key'=>$type,'status'=>2])->first();
        $data->updated_at = substr($data->updated_at,0,16);
        return response()->json(['status'=>1,'data'=>$data]);
    }



}
