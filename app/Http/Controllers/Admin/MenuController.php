<?php
namespace App\Http\Controllers\Admin;
use DB;
use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Cache;

class MenuController extends BaseController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request){
        return redirect(route($this->RouteController.".lists"));
    }

    public function lists(Request $request){


        $pagesize=10;//默认分页数
        if(Cache::has('pagesize')){
            $pagesize=Cache::get('pagesize');
        }

        $list = DB::table('menus')
            ->where("parent","=","0")
            ->orderBy("sort","desc")
            ->paginate($pagesize);

        if($request->ajax()){

            foreach($list as $mv){
                $mv->menus= DB::table('menus')->where("parent","=",$mv->id) ->orderBy("sort","desc")->get();
            }
            if($list){
                return ["status"=>0,"list"=>$list,"pagesize"=>$pagesize];
            }

        }else{

            foreach($list as $mv){
                $mv->menus= DB::table('menus')->where("parent","=",$mv->id) ->orderBy("sort","desc")->get();
            }

            return $this->ShowTemplate(["list"=>$list,"pagesize"=>$pagesize]);
        }

    }

    public function store(Request $request){


        if($request->isMethod("post")){

            $messages = [
                'name.required' => '名称不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
            ], $messages);


            $Models = new Menu();
            $Models->name = $request->input('name');
            $Models->model_name = $request->input('model_name');
            $Models->contr_name = $request->input('contr_name');
            $Models->action_name = $request->input('action_name');
            $Models->parent = $request->input('parent');
            $Models->ismenuleft =1;//
            $Models->disabled = $request->input('disabled')=='on'?0:1;
            $Models->sort = $request->input('sort');
            $Models->save();

            if($request->ajax()){
                return response()->json([
                    "msg"=>"添加成功","status"=>0
                ]);
            }else{
                return redirect(route('menu_store'))->with(
                    [
                        "msg"=>"添加成功",
                        "parent"=>$request->input('parent'),
                        "contrname"=>$request->input('contr_name')
                        ,"status"=>0
                    ]
                );
            }



        }else{


                if( Cache::has("Menu_Parent_List")) {
                    $menu=Cache::get("Menu_Parent_List");
                }else {
                    $menu=DB::table("menus")->where("parent","=","0")->orderBy("sort","desc")->get();
                    Cache::put("Menu_Parent_List",$menu, $this->cachetime);
                }
            return $this->ShowTemplate(["menu"=>$menu,"status"=>0]);
        }

    }




    public function update(Request $request)
    {

        if($request->isMethod("post")){


            $messages = [
                'name.required' => '名称不能为空!',
            ];

            $result = $this->validate($request, [
                "name"=>"required",
            ], $messages);


            $Models = Menu::find($request->input('id'));
            $Models->name = $request->input('name');
            $Models->model_name = $request->input('model_name');
            $Models->contr_name = $request->input('contr_name');
            $Models->action_name = $request->input('action_name');
            $Models->parent = $request->input('parent');
            $Models->disabled = $request->input('disabled')=='on'?0:1;
            $Models->sort = $request->input('sort');
            $Models->save();

            if($request->ajax()){
                return response()->json([
                    "msg"=>"修改成功","status"=>0
                ]);
            }else{
                return redirect(route('menu_update',["id"=>$request->input("id")]))->with(["msg"=>"修改成功","status"=>0]);
            }



        }else{
            if( Cache::has("Menu_Parent_List")) {
                $menu=Cache::get("Menu_Parent_List");
            }else {
                $menu=DB::table("menus")->where("parent","=","0")->orderBy("sort","desc")->get();
                Cache::put("Menu_Parent_List",$menu, $this->cachetime);
            }

            $Models = Menu::find($request->get('id'));
            return $this->ShowTemplate(["edit"=>$Models,"menu"=>$menu,"status"=>0]);
        }

    }




    public function updatedisabled(Request $request)
    {



        if($request->isMethod("post")){


            $Models = Menu::find($request->input('id'));

            if($request->input('keys')=='disabled'){
                $Models->disabled = $request->input('disabled');
            }
            if($request->input('keys')=='left'){
                $Models->ismenuleft = $request->input('ismenuleft');
            }
            if($request->input('keys')=='top'){
                $Models->ismenutop = $request->input('ismenutop');
            }


            $Models->save();

            return ["msg"=>"修改成功","status"=>0];

        }

    }

    public function updates(Request $request)
    {



        if($request->isMethod("post")){


            $Models = Menu::find($request->input('id'));

            if( $request->input('keys')=='name'){
                $Models->name = $request->input('value');
            }
            if( $request->input('keys')=='sort'){
                $Models->sort = $request->input('value');
            }

            if( $request->input('keys')=='icon'){
                $Models->icon = $request->input('value');
            }
            if( $request->input('keys')=='iconclass'){
                $Models->iconclass = $request->input('value');
            }

            if( $request->input('keys')=='contr_name'){
                $Models->contr_name = $request->input('value');

            }
            if( $request->input('keys')=='action_name'){
                $Models->action_name = $request->input('value');

            }


            $Models->save();

            return ["msg"=>"修改成功","status"=>0];

        }

    }

    public function copy(Request $request)
    {

        if($request->isMethod("post")){

            $Menu = new Menu();

            $Models = Menu::find($request->id);
            $Menu->name =  $Models->name;
            $Menu->model_name =  $Models->model_name;
            $Menu->contr_name =  $request->value ;
            $Menu->action_name =  $Models->action_name;
            $Menu->parent =  0 ;
            $Menu->disabled =  $Models->disabled ;
            $Menu->sort =  $Models->sort ;
            $Menu->save();


            $Modelcs = Menu::where("parent",$Models->id)->get();

            if($Modelcs){

                foreach($Modelcs as $item){

                    $Menu2 = new Menu();
                    $Menu2->name =  $item->name;
                    $Menu2->model_name =  $item->model_name;
                    $Menu2->contr_name =  $request->value ;
                    $Menu2->action_name =  $item->action_name;
                    $Menu2->parent = $Menu->id ;
                    $Menu2->disabled =  $item->disabled ;
                    $Menu2->sort =  $item->sort ;
                    $Menu2->save();
                }

            }

            if($request->ajax()){
                return response()->json([
                    "msg"=>"复制成功","status"=>0
                ]);
            }



        }

    }

    public function delete(Request $request){


        if($request->ajax()) {
            if($request->input("id")){

                $auth = DB::table('menus')
                    ->where(['id' => $request->input("id")])
                    ->first();
                if($auth){
                    if($auth->id>1) {
                        $delete = DB::table('menus')->where('id', '=', $request->input("id"))->delete();
                         DB::table('menus')->where('parent', '=', $request->input("id"))->delete();
                        if ($delete) {
                            return ["status" => 0, "msg" => "删除成功"];
                        } else {
                            return ["status" => 1, "msg" => "删除失败"];
                        }
                    }else{
                        return ["status" => 1, "msg" => "系统组不允许删除"];
                    }

                }


            }


        }else{
            return ["status"=>1,"msg"=>"非法操作"];
        }

    }
}
