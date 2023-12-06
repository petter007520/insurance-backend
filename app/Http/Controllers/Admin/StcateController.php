<?php


namespace App\Http\Controllers\Admin;
    use App\Product;
    use App\Site;
    use Carbon\Carbon;
    use DB;
    use App\Category;
    use DemeterChain\C;
    use Illuminate\Http\Request;
    use Session;
    use Cache;
    use App\Stproduct;

class StcateController extends BaseController
{

    private $table="stproduct";


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->Model=new Product();
        $modellist= config('model');
        view()->share("modellist",$modellist);
        $this->CategoryModel=new Category();
        $category_id=$request->s_categoryid;

        view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$category_id,0,$this->table));


        // if(\Illuminate\Support\Facades\Cache::has('product.list')){
        //     $productlist=Cache::get('product.list');
        // }else{
        //     $productlist= DB::table("products")->select('id','title')->where(['tzzt'=>0,'category_id'=>12])->get();
        //     Cache::get('product.list',$productlist,Cache::get("cachetime"));
        // }
        $productlist= DB::table("products")->select('id','title')
        // ->where(['category_id'=>12])
        ->get();
        view()->share("productlist",$productlist);


        if(\Illuminate\Support\Facades\Cache::has('memberlevel.list')){
            $memberlevel=Cache::get('memberlevel.list');
        }else{
            $memberlevel= DB::table("memberlevel")->orderBy("id","asc")->get();
            Cache::get('memberlevel.list',$memberlevel,Cache::get("cachetime"));
        }

        view()->share("memberlevel",$memberlevel);
    }



    public function index(Request $request){

        return redirect(route($this->RouteController.".lists"));

    }




    public function lists(Request $request){

        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }



        isset($_REQUEST['s_categoryid'])?$s_categoryid=$_REQUEST['s_categoryid']:$s_categoryid=0;
        isset($_REQUEST['s_key'])?$s_key=$_REQUEST['s_key']:$s_key='';



        $listDB = DB::table($this->table)
            ->select($this->table.'.*')
           ->where(function ($query) {
                $s_key_name=[];
                $s_key_bljg=[];
                $s_key_content=[];
                if(isset($_REQUEST['s_key'])){
                    $s_key_name[]=[$this->table.".name","like","%".$_REQUEST['s_key']."%"];
                  //  $s_key_bljg[]=[$this->table.".bljg","like","%".$_REQUEST['s_key']."%"];
                //    $s_key_content[]=[$this->table.".content","like","%".$_REQUEST['s_key']."%"];
                }

                $query->orwhere($s_key_name)->orwhere($s_key_bljg)->orwhere($s_key_content);
            })
            ->where(function ($query) {
                $s_siteid=[];
                if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']>0){
                    $s_siteid[]=[$this->table.".category_id","=",$_REQUEST['s_categoryid']];
                }

                $query->where($s_siteid);
            })->where(function ($query) {
                $s_status=[];
                if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']!=''){
                    $s_status[]=[$this->table.".tzzt","=",$_REQUEST['s_status']];
                }

                $query->where($s_status);
            });


            $list=$listDB->orderBy($this->table.".sort","desc")
                ->orderBy($this->table.".id","desc")
                ->paginate($pagesize);





        if($request->ajax()){
            if($list){
                $model=config('model');
                $modelname=[];
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }
        }else{

           // echo "122222222222";
           // var_dump($list);

            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }

    public function store(Request $request){

        if($request->isMethod("post")){

            $data=$request->all();
            //$photos=isset($data['productimage'])?json_encode($data['productimage']):'';
            //$data['photos']=$photos;
            unset($data['_token']);
            unset($data['thumb']);
            unset($data['file']);
            unset($data['productimage']);
            unset($data['editormd-image-file']);
            unset($data['s']);


            // unset($data['muland']);
            // unset($data['insurance']);
            // unset($data['soc_security']);
            // unset($data['est_salary']);

            $data['hkfs']=0;
            // $data['shijian'] = 99999;

            // $data['fxj']=$data['qtje']; //发行价
            $data['title']=\App\Formatting::ToFormat($data['title']);

            if(!empty($data['content'])){
                $data['content']=\App\Formatting::ToFormat($data['content']);
            }

            $data['category_name']=$this->CategoryModel->where("id",$data['category_id'])->value('name');

           // $data['created_at']=
            $data['updated_at']=Carbon::now();

            $res = DB::table($this->table)->insertGetId($data);

            if($request->ajax()){
                return response()->json([
                    "msg"=>"添加成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.store'))->with(["msg"=>"添加成功","status"=>0]);
            }
        }else{
            return $this->ShowTemplate();
        }

    }






    public function update(Request $request)
    {
        if($request->isMethod("post")){


            $data=$request->all();
            //$photos=isset($data['productimage'])?json_encode($data['productimage']):'';
            $id= $data['id'];
            //$data['photos']=$photos;
            unset($data['_token']);
            unset($data['id']);
            unset($data['thumb']);
            unset($data['file']);
            unset($data['productimage']);
            unset($data['editormd-image-file']);
            unset($data['s']);


            $data['title']=\App\Formatting::ToFormat($data['title']);
            if(!empty($data['content'])){
                $data['content']=\App\Formatting::ToFormat($data['content']);
            }

            // $old_info = DB::table($this->table)->where("id",$id)->first();
            // if($old_info->increase != $data['increase'] && $old_info->category_id == 11 ){
            //     $data['qtje'] = $old_info->qtje + ($old_info->qtje * 0.01 * $data['increase']);
            //     DB::table('currencysline')->where("product_id",$id)->orderBy('created_at','desc')->take(1)->update(['price'=>$data['qtje'],'increase'=>$data['increase'],'created_at'=>Carbon::now()]);
            // }
            // if($old_info->qtje != $data['qtje'] && $old_info->category_id == 11 ){
            //     DB::table('currencysline')->where("product_id",$id)->orderBy('created_at','desc')->take(1)->update(['price'=>$data['qtje'],'created_at'=>Carbon::now()]);
            // }


            $data['category_name']=$this->CategoryModel->where("id",$data['category_id'])->value('name');
            $data['updated_at']=Carbon::now();

            // $data['shijian'] = 99999;

            $data['hkfs']=0;//

            DB::table($this->table)->where("id",$id)->update($data);
             Cache::forget("index_projects_".$data['category_id']);
            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route($this->RouteController.'.update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }

        }else{
            $Model = $this->Model::find($request->get('id'));

            view()->share("tree_option",$this->CategoryModel->tree_option(0,0,$Model->category_id,0,$this->table));
            view()->share("photos",json_decode($Model->photos));

            return $this->ShowTemplate(["edit"=>$Model,"status"=>0]);
        }

    }

    public function settop(Request $request)
    {
        if($request->isMethod("post")){

            $Model = $this->Model::find($request->input('id'));

            $Model->issy = $request->input('top_status');

            $Model->save();


            if($request->ajax()){
                return response()->json([
                    "msg"=>"操作成功","status"=>0
                ]);
            }
        }
    }



    public function delete(Request $request){


          if($request->ajax()) {
            if($request->input("id")){

                $member = DB::table($this->table)
                    ->where(['id' => $request->input("id")])
                    ->first();
                if($member){

                       $delete = DB::table($this->table)->where('id', '=', $request->input("id"))->delete();
                        if ($delete) {
                            return ["status" => 0, "msg" => "删除成功"];
                        } else {
                            return ["status" => 1, "msg" => "删除失败"];
                        }


                }else{
                    return ["status"=>1,"msg"=>"您没有权限删除操作"];
                }


            }else if($request->input("ids")){

                $delete = DB::table($this->table)->whereIn('id',  $request->input("ids"))->delete();
                if ($delete) {
                    return ["status" => 0, "msg" => "删除成功"];
                } else {
                    return ["status" => 1, "msg" => "删除失败"];
                }
            }


        }else{
            return ["status"=>1,"msg"=>"非法操作"];
        }

    }

    public function xunhuan(){
        for($i=0;$i<50;$i++){
            $a= $this->new_update_currline();
            if($a){

            }
        }
    }

     public function new_update_currline(){
        // $product_id = $request->pid; //货币ID
        // $startkey = $request->startkey; //执行key

        $product_id = 141;
        $check_huobi = DB::table('products')->where('id',$product_id)->first(); //查看是否存在该货币
        if( !$check_huobi){
            return ["status" => 1, "msg" => "参数错误"];
        }

        $has_c = DB::table('currencysline')->where('product_id',$product_id)->first();
        if($has_c){
            return ["status" => 1, "msg" => "该货币数据已存在"];
        }

        $stimestamp = strtotime('2020-06-06 00:00:01');
        $etimestamp = strtotime(Carbon::now());
        // 计算日期段内有多少天
        $days =intval(($etimestamp-$stimestamp)/86400+1);

        $old_price = 1.8;

        $data_array = [];
        for ($i=0;$i<$days;$i++) {
            $record = [];
            $increase = rand(-4,6);

            if($increase >= 0){
                $increase += (float)('0.'.$increase);
            }

            $increase_price = $old_price * $increase * 0.01;
            $rand_num1 = rand(-1,3);
            $rand_num2 = rand(0,2);
            $record['product_id'] = $product_id;
            $record['old_price'] = $old_price;
            $record['price'] = $old_price + $increase_price;
            $record['increase'] = $increase;
            $record['increase_price'] = $increase_price;
            $record['created_at'] =  $this->DateAdd("d",$i, '2020-06-06 00:00:01');
            $record['highest_price'] = rand($record['price'],$record['price']+$rand_num1);
            $record['lowest_price'] = rand($record['price']-$rand_num2,$record['price']);

            $old_price = $record['price'];

            $data_array[] = $record;

        }

        return $record['price'];
        // else{
        //     // $this->update_currline($request);
        //     return ["status" => 1, "msg" => $check_huobi->qtje."最终值".$record['price'].',请重试'];
        // }

    }

    public function update_currline(Request $request){
        // $product_id = $request->pid; //货币ID
        // $startkey = $request->startkey; //执行key
        for($p=0;$p<400;$p++){
            $product_id = 144;
            $check_huobi = DB::table('products')->where('id',$product_id)->first(); //查看是否存在该货币
            if( !$check_huobi){
                return ["status" => 1, "msg" => "参数错误"];
            }

            $has_c = DB::table('currencysline')->where('product_id',$product_id)->first();
            if($has_c){
                return ["status" => 1, "msg" => "该货币数据已存在"];
            }

            $stimestamp = strtotime('2020-06-06 00:00:01');
            $etimestamp = strtotime(Carbon::now());
            // 计算日期段内有多少天
            $days =intval(($etimestamp-$stimestamp)/86400+1);

            $old_price = 2.9;

            $data_array = [];
            for ($i=0;$i<$days;$i++) {
                $record = [];
                $increase = rand(-3,5);

                if($increase >= 0){
                    $increase += (float)('0.'.$increase);
                }

                $increase_price = $old_price * $increase * 0.01;
                $rand_num1 = rand(-1,3);
                $rand_num2 = rand(0,2);
                $record['product_id'] = $product_id;
                $record['old_price'] = $old_price;
                $record['price'] = $old_price + $increase_price;
                $record['increase'] = $increase;
                $record['increase_price'] = $increase_price;
                $record['created_at'] =  $this->DateAdd("d",$i, '2020-06-06 00:00:01');
                $record['highest_price'] = rand($record['price'],$record['price']+$rand_num1);
                $record['lowest_price'] = rand($record['price']-$rand_num2,$record['price']);

                $old_price = $record['price'];

                $data_array[] = $record;

            }

            if($record['price'] >= ($check_huobi->qtje-1) && $record['price'] <= ($check_huobi->qtje+1)){
                DB::table('currencysline')->insert($data_array);
                return ["status" => 0, "msg" => $p."成功插入".$i];
                break;
            }
            else{
                // $this->update_currline($request);
                // return ["status" => 1, "msg" => $check_huobi->qtje."最终值".$record['price'].',请重试'];
                // continue;
            }


        }
        return ["status" => 1, "msg" => $check_huobi->qtje."最终值".$record['price'].',请重试'];
    }

     protected function DateAdd($part, $number, $date){
        $date_array = getdate(strtotime($date));
        $hor = $date_array["hours"];
        $min = $date_array["minutes"];
        $sec = $date_array["seconds"];
        $mon = $date_array["mon"];
        $day = $date_array["mday"];
        $yar = $date_array["year"];
        switch($part){
            case "y": $yar += $number; break;
            case "q": $mon += ($number * 3); break;
            case "m": $mon += $number; break;
            case "w": $day += ($number * 7); break;
            case "d": $day += $number; break;
            case "h": $hor += $number; break;
            case "n": $min += $number; break;
            case "s": $sec += $number; break;
        }
        $FengHongDateFormat='Y-m-d H:i:s';
//        if(Cache::has('FengHongDateFormat')){
//            $FengHongDateFormat=Cache::get('FengHongDateFormat');
//        }
        return date($FengHongDateFormat, mktime($hor, $min, $sec, $mon, $day, $yar));
    }


}
