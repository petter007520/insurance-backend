<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TreeProduct extends Model
{
    protected $table="tree_products";
    protected $primaryKey="id";
    public $timestamps=true;
    protected $guarded=[];
    protected $fillable = ['category_id', 'category_name', 'title', 'bljg', 'xmgm','xmjd', 'qtje', 'content', 'zgje', 'tzrs', 'ktje', 'jyrsy', 'tqsyyj','pic','shijian','qxdw','hkfs','cishu','tzzt','isft','level','issy','sort','click_count','muland','soc_security','insurance','est_salary'];



}
