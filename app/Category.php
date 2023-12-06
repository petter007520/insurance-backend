<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Illuminate\Http\Request;

class Category extends Model
{
    protected $table="category";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['name','parent','sort','siteid','thumb_url','model','color','ctitle','ckeywords','cdescription','ccontent','atindex','click_count','atfoot','ismenus','classname','links'];
    protected $tree;

    public function tree($parentid=0,$skey='')
    {

        $model=config('model');
        $modelname=[];
        foreach ($model as $v){
            $modelname[$v['key']]=$v['name'];
        }

        $adminAuthID =\Request::session()->get('adminAuthID');
        $adminID =\Request::session()->get('adminID');




            $categoryD = Category::where('parent', $parentid)
                ->where(function ($query) use ($skey) {
                    $s_key_name = [];
                    if ($skey != '') {
                        $s_key_name[] = [$this->table . ".name", "like", "%" . $skey . "%"];
                    }

                    $query->orwhere($s_key_name);
                });



            $category = $categoryD->get();





        //第一次做的时候get()后面加了toArray()，页面遍历数据时报错遍历的不是对象，去掉后可行

        $arr = array();
        if (sizeof($category) !=0){
            foreach ($category as $k =>$datum) {
                $datum['list'] = $this->tree($datum['id'],$skey);
                $datum->modename=  isset($modelname[$datum->model])?$modelname[$datum->model]:'';

                $arr[]=$datum;
            }
        }
        return $arr;

    }


    public function tree_option($parentid=0,$cj=0,$selected=0,$current=0,$model='',$whereArr=[])
    {

        //echo $parentid.":".$cj.':'.$selected.':'.$current.':'.$model;


        $Where=[];

        if($model!=''){
            $category =Category::where('parent',$parentid)->where('model',$model)->where($whereArr)->orderBy("sort","desc")->get();
        }else{
            $category =Category::where('parent',$parentid)->orderBy("sort","desc")->get();
        }

//        var_dump($category);


        //第一次做的时候get()后面加了toArray()，页面遍历数据时报错遍历的不是对象，去掉后可行
        $cjstr='';
        $arr = '';
        if (sizeof($category) !=0){
            $cj++;
            for($i=1;$i<$cj;$i++){
                $cjstr.=$i==1?'|-':'-';
            }
            foreach ($category as $k =>$datum) {
                    $selectedstr='';

                   if($datum['id']==$selected){
                       $selectedstr='selected="selected"';
                   }
                   if($datum['id']!=$current){
                       $arr.='<option value="'.$datum['id'].'" '.$selectedstr.'>'.$cjstr.$datum['name'].'</option>';
                   }


                $arr.=$this->tree_option($datum['id'],$cj,$selected,$current,$model);

            }

        }
        return $arr;

    }


    public function subor($parentid=0)
    {


        $adminAuthID =\Request::session()->get('adminAuthID');
        $adminID =\Request::session()->get('adminID');





        $category =Category::where('parent',$parentid)->get();





        //第一次做的时候get()后面加了toArray()，页面遍历数据时报错遍历的不是对象，去掉后可行

        if (sizeof($category) !=0){

            foreach ($category as $k =>$datum) {
                       $this->tree=$this->subor($datum['id']);
                       $this->tree[]=$datum['id'];
            }

        }
        return array_unique($this->tree);

    }


}
