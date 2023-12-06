<?php

namespace App\Http\Controllers\Wap;
use App\Auth;
use App\Category;
use App\Channel;
use App\Http\Controllers\Controller;
use App\Member;
use App\Memberlevel;
use App\Order;
use App\Product;
use Carbon\Carbon;
use DB;
use App\Admin;
use App\Ad;
use App\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Session;

class IndexController extends Controller
{
    public $cachetime=600;
    public $Template='wap';
    public function __construct(Request $request)
    {
    
        $this->Template=env("WapTemplate");

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



        //验证访问权限是否开启

        $this->middleware(function ($request, $next) {

            if(Cache::get('AccessPrivileges')!='开启'){
                $UserId =$request->session()->get('UserId');

                if($UserId<1){
                    return redirect()->route("wap.login");
                }

                $Member= Member::find($UserId);
                if($Member){
                    $this->Member=$Member;
                    view()->share("Member",$Member);
                }



                view()->share("Member",$this->Member);
            }else{

                $UserId =$request->session()->get('UserId');


                $Member= Member::find($UserId);
                if($Member){
                    $this->Member=$Member;
                    view()->share("Member",$Member);
                }
                

            }



            return $next($request);
        });



        /**广告数据**/
        $Ad=new Ad();

        if(Cache::has("wap.ad")){
            $wapad= Cache::get("wap.ad");
            view()->share("wapad",$wapad );
        }else{
            $wapad['banner']=$Ad->GetAd('手机首页幻灯');
            $wapad['hongbao']=$Ad->GetAd('首页邀请好友红包');
            //$wapad['scg']=$Ad->GetAd('手机端收藏阁广告图');
            Cache::put("wap.ad",$wapad,$this->cachetime);
            view()->share("wapad", $wapad);
        }



        /**菜单导航栏**/
        if(Cache::has('wap.category')){
            $footcategory=Cache::get('wap.category');
        }else{
            $footcategory= DB::table('category')->where("atfoot","1")->orderBy("sort","desc")->limit(5)->get();
            Cache::put('wap.category',$footcategory,$this->cachetime);
        }
        view()->share("footcategory",$footcategory);
        /**菜单导航栏 END **/

        /**项目分类菜单导航栏**/
        if(Cache::has('wap.ProductsCategory')){
            $ProductsCategory=Cache::get('wap.ProductsCategory');
        }else{
            $ProductsCategory= Category::where("model","products")->where("atindex","1")->where("ismenus","1")->orderBy("sort","desc")->get();

            foreach ($ProductsCategory as $item) {
                $item->Products=DB::table("products")->where("category_id",$item->id)->where("issy","1")->where("tzzt", '<', 2)->orderBy("sort","desc")->get();
            }

            Cache::put('wap.ProductsCategory',$ProductsCategory,$this->cachetime);
        }


        view()->share("ProductsCategory",$ProductsCategory);
        /**项目分类菜单导航栏 END **/


        /**项目分类列表**/
        if(Cache::has('wap.ProductsCategoryList')){
            $ProductsCategoryList=Cache::get('wap.ProductsCategoryList');
        }else{
            $ProductsCategoryList= Category::where("model","products")->where("ismenus","1")->orderBy("sort","desc")->get();

            Cache::put('wap.ProductsCategoryList',$ProductsCategoryList,$this->cachetime);
        }



        view()->share("ProductsCategoryList",$ProductsCategoryList);
        /**项目分类菜单导航栏 END **/



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

        view()->share("memberlevel",$memberlevelName);




    }


    public function index(Request $request){


        // $data = DB::table("member")->orderBy('id','asc')->first();
        // return response()->json([
        //     "msg"=>"请求成功",
        //     "data"=>$data,
        //     "status"=>0
        // ]);

       // $newmess=   \App\Formatting::Format(Cache::get('newmess'));//UserName

        return view($this->Template.".index");
    }
public function uploadImg(Request $request,$type = null)
    {

        $file = $request->file('payimg'); // 获取上传的文件

        if ($file==null) {
            return $this->error('还未上传文件');
        }
        // 获取文件后缀
        $temp = explode(".", $_FILES["payimg"]["name"]);
        $extension = end($temp);
        // 判断文件是否合法
        if(!in_array($extension, array("gif","GIF","jpg","JPG","jpeg","JPEG","png","PNG","bmp","BMP"))){
            return response()->json(["status"=>0,"msg"=>"上传图片不合法"]);
        }
        if($type==null){
            if($_FILES['payimg']['size']>5*1024*1024){
                return response()->json(["status"=>0,"msg"=>"上传图片大小不能超过5M"]);
            }
        }

        $time = date("Ymd",time());

        $path_origin = 'files/'.$time.'';

        $res = Storage::disk('uploads')->put($path_origin, $file);

        return response()->json(["status"=>1,"msg"=>"上传凭证成功","data"=>"uploads/".$res]);

    }

    /**商品**/
    public function products(Request $request)
    {


          view()->share("request",$request);
          if($request->links!=''){
              $category = DB::table("category")->where("model", "products")->where("links", $request->links)->first();
              if ($category) {
                  view()->share("category", $category);
                  view()->share("title", $category->name);


                $productsLists=  DB::table("products")->where("category_id", $category->id)
                    ->where("tzzt", '<', 2)
                    //->orderBy("tzzt", "asc")
                    //->orderBy("qtje", "asc")
                    ->orderBy("sort", "desc")
                    ->get();
                  view()->share("productsLists", $productsLists);
                  return view($this->Template.".productlist");
              }
          }else{


              $category = DB::table("category")->where("model", "products")->orderBy("sort","desc")->first();

              $AllProducts=DB::table("products")
                  ->where("category_id", $category->id)
                  ->orderBy("sort","desc")
                  ->get();





            view()->share("AllProducts",$AllProducts);

              return view($this->Template.".products");
          }



    }

    public function product(Request $request)
    {

        DB::table("products")->where("id",$request->id)->increment('click_count',1);

        if( 0 && Cache::has('wap.product.'.$request->id)){
            $product=Cache::get('wap.product.'.$request->id);
        }else{
            $product=DB::table("products")->where("id",$request->id)->first();

            $product->links= DB::table("category")->where("id",$product->category_id)->value('links');
            $product->thumb_url= DB::table("category")->where("id",$product->category_id)->value('thumb_url');
			$product -> djst = ( strtotime( $product->djs_at ) - time() ) * 1000 ;
			$product -> created_at2 =  date("Y-m-d H:i", strtotime( $product->created_at ) )  ;
			$product -> djs_at2 =  date("Y-m-d H:i", strtotime( $product->djs_at ) )  ;
            Cache::put('wap.product.'.$request->id,$product,$this->cachetime);
        }
        view()->share("title",$product->category_name.'-'.$product->title);
        view()->share("productview",$product);


        view()->share("productid",$request->id);

        $Memberamount=0;
        $UserId = $request->session()->get('UserId');
        if($UserId>0){
            $Member= Member::find($UserId);
            $Memberamount=$Member->amount;
        }
        view()->share("Memberamount",$Memberamount);

        return view($this->Template.".product");

    }

    /**项目投资**/
    public function buy(Request $request)
    {



        if(Cache::has('wap.product.'.$request->id)){
            $product=Cache::get('wap.product.'.$request->id);
        }else{
            $product=DB::table("products")->where("id",$request->id)->first();

            $product->links= DB::table("category")->where("id",$product->category_id)->value('links');
            $product->thumb_url= DB::table("category")->where("id",$product->category_id)->value('thumb_url');

            Cache::put('wap.product.'.$request->id,$product,$this->cachetime);
        }
        view()->share("title",$product->category_name.'-'.$product->title);
        view()->share("productview",$product);


        view()->share("productid",$request->id);

        $Memberamount=0;
        $UserId = $request->session()->get('UserId');
        if($UserId>0){
            $Member= Member::find($UserId);
            $Memberamount=$Member->amount;
        }
        view()->share("Memberamount",$Memberamount);

        return view($this->Template.".buy");

    }



    /**新闻**/


    public function articles(Request $request)
    {


        if($request->ajax()){

            $pagesize=10;
            $pagesize=Cache::get("pcpagesize");
            $where=[];

            if(Cache::has('wap.ArticlesList.'.$request->page.'.'.$request->links)){
                $articles=Cache::get('wap.ArticlesList.'.$request->page.'.'.$request->links);
            }else{

                if($request->links){
                    $category = DB::table("category")->where("model", "articles")->where("links", $request->links)->first();
                    $where=["category_id"=> $category->id];
                }


                $articles = DB::table("articles")
                    ->where($where)
                    ->where("status", 2)
                    ->orderBy("sort","desc")
                    ->orderBy("id","desc")
                    ->paginate($pagesize);


                foreach($articles as $article){
                    $article->url=\route("article",["id"=>$article->id]);
                    $article->title=\App\Formatting::Format($article->title);
                }

                Cache::put('wap.ArticlesList.'.$request->page.'.'.$request->links,$articles,$this->cachetime);
            }

            return ["status"=>0,"list"=>$articles,"pagesize"=>$pagesize];
        }else {
            //分类信息

            if($request->links) {
                $category = DB::table("category")->where("model", "articles")->where("links", $request->links)->first();

                if ($category) {








                    view()->share("title", $category->name );
                    view()->share("category", $category);






                    return view($this->Template.".articles");

                }
            }else{

                /**所有文章**/




                view()->share("title",  '新闻资讯' );





                return view($this->Template.".articles");


            }
        }




    }

    public function article(Request $request)
    {

        DB::table("articles")->where("id",$request->id)->increment('click_count',1);

        if(Cache::has('wap.article.'.$request->id)){
            $article=Cache::get('wap.article.'.$request->id);
        }else{
            $article=DB::table("articles")->where("id",$request->id)->first();

            $article->links= DB::table("category")->where("id",$article->category_id)->value('links');
            $article->thumb_url= DB::table("category")->where("id",$article->category_id)->value('thumb_url');

            Cache::put('wap.article.'.$request->id,$article,$this->cachetime);
        }
        view()->share("title",$article->category_name);
        view()->share("article",$article);
        return view($this->Template.".article");


    }

    /**单页**/
    public function singlepages(Request $request)
    {



        view()->share("title",  '帮助说明');



        /**菜单导航栏**/
        if (Cache::has('wap.singlepagescategory')) {
            $articlescategory = Cache::get('wap.singlepagescategory');
        } else {
            $articlescategory = DB::table('category')->where("model", "singlepages")->orderBy("sort", "desc")->get();

            Cache::put('wap.singlepagescategory', $articlescategory, $this->cachetime);
        }
        view()->share("articlescategory", $articlescategory);
        /**菜单导航栏 END **/


        return view($this->Template.".singlepages");


    }

    public function singlepage(Request $request)
    {

        if($request->links){
            DB::table("category")->where("links",$request->links)->increment('click_count',1);

            if(Cache::has('wap.singlepage.'.$request->links)){
                $singlepage=Cache::get('wap.singlepage.'.$request->links);
            }else{
                $singlepage=DB::table("category")->where("links",$request->links)->first();
                Cache::put('wap.singlepage.'.$request->links,$singlepage,$this->cachetime);
            }


        }else if($request->id){
            DB::table("category")->where("id",$request->id)->increment('click_count',1);

            if(Cache::has('wap.singlepage.'.$request->id)){
                $singlepage=Cache::get('wap.singlepage.'.$request->id);
            }else{
                $singlepage=DB::table("category")->where("id",$request->id)->first();
                Cache::put('wap.singlepage.'.$request->id,$singlepage,$this->cachetime);
            }


        }


        view()->share("title",$singlepage->name);
        view()->share("article",$singlepage);
        return view($this->Template.".singlepage");

    }


    /** 在线留言 **/
    public function SendMsg(Request $request)
    {

        $Post= $request->all();


        $data=[];
        if($Post['Type']=='QQ'){
            $data['msg']=$Post['Type'].'-'.$Post['InquiryType'];
            $data['qq']=$Post['TxtValue'];

        }else if($Post['Type']=='手机'){
            $data['msg']=$Post['Type'].'-'.$Post['InquiryType'];
            $data['phone']=$Post['TxtValue'];
        }else if($Post['Type']=='Tel'){
            $data['msg']=$Post['Type'].'-'.$Post['InquiryType'];
            $data['phone']=$Post['TxtValue'];
        }
        $data['tip']=$request->Tip."-".$request->productname;
        $data['pid']=$request->ProductID;
        $data['wx']=$request->WebChart;
        $data['name']=$request->Name;
        $data['sex']=$request->sex;
        $data['adddate']=Carbon::now();

        $msg=  DB::table("onlinemsg")->insert($data);

        return response()->json([
            "msg"=>"请求成功",
            "status"=>0
        ]);

    }


    public function appdown(Request $request){
        $app="Android";
        $links="Android.apk";

        if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
            $app="IOS";
            $links="IOS.ipa";
        }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
            $app="Android";
            $links="Android.apk";
        }


        view()->share("app",$app);
        view()->share("links",$links);


        return view($this->Template.".appdown");
    }



}


?>
