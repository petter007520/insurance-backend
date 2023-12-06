<?php


Route::any('/', ['as'=>'index','uses'=>'IndexController@index']);//首页
Route::any('/index.html', ['as'=>'index','uses'=>'IndexController@index']);//首页

/*

Route::any('/products.{page}.html', ['as'=>'products.page','uses'=>'IndexController@products']);//商品列表
Route::any('/products-{keyword}.html', ['as'=>'products.keyword.page','uses'=>'IndexController@products']);//商品搜索列表
Route::any('/products/{author}/index.{page}.html', ['as'=>'products.author.page','uses'=>'IndexController@products']);//商品搜索列表
Route::any('/products/{links}.{page}.html', ['as'=>'products.links.page','uses'=>'IndexController@products']);//商品搜索列表
Route::any('/products/search/{keys}/{values}.{page}.html', ['as'=>'products.search.page','uses'=>'IndexController@products']);//商品搜索列表

Route::any('/products.html', ['as'=>'products','uses'=>'IndexController@products']);//商品列表
Route::any('/products-{keyword}.html', ['as'=>'products.keyword','uses'=>'IndexController@products']);//商品搜索列表
Route::any('/products/{author}/index.html', ['as'=>'products.author','uses'=>'IndexController@products']);//商品搜索列表
Route::any('/products/{links}.html', ['as'=>'products.links','uses'=>'IndexController@products']);//商品搜索列表
Route::any('/products/search/{keys}/{values}.html', ['as'=>'products.search','uses'=>'IndexController@products']);//商品搜索列表
Route::any('/product/{id}.html', ['as'=>'product','uses'=>'IndexController@product']);//商品详情

Route::any('/celebritys.html', ['as'=>'celebritys','uses'=>'IndexController@celebritys']);//名人列表
Route::any('/celebritys/{links}.html', ['as'=>'celebritys.links','uses'=>'IndexController@celebritys']);//名人列表
Route::any('/celebrity/{id}.html', ['as'=>'celebrity','uses'=>'IndexController@celebrity']);//名人详情

Route::any('/articles.html', ['as'=>'articles','uses'=>'IndexController@articles']);//新闻列表
Route::any('/articles/{links}.html', ['as'=>'articles.links','uses'=>'IndexController@articles']);//新闻列表
Route::any('/article/{id}.html', ['as'=>'article','uses'=>'IndexController@article']);//新闻详情

Route::any('/singlepages.html', ['as'=>'singlepages','uses'=>'IndexController@singlepages']);//新闻列表
Route::any('/singlepages/{links}.html', ['as'=>'singlepages.links','uses'=>'IndexController@singlepage']);//新闻列表
Route::any('/singlepage/{id}.html', ['as'=>'singlepage','uses'=>'IndexController@singlepage']);//新闻详情

Route::any('/SendMsg.html', ['as'=>'SendMsg','uses'=>'IndexController@SendMsg']);//提交留言
Route::any('/ajax/Telephone', ['as'=>'Telephone','uses'=>'IndexController@Telephone']);//获取手机号*/


