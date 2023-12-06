<?php

/*
|--------------------------------------------------------------------------
| 路由
|--------------------------------------------------------------------------
*/
//首页路由设置
use Illuminate\Support\Facades\Route;
$Openmode=\Cache::get('Openmode');
    if($Openmode=='pc'){
        /*Route::group(['namespace'=>'Pc'], function () {
            require(__DIR__ . '/pc/route.php');//路由设置
        });*/
    }else if($Openmode=='wap'){
        Route::group(['namespace'=>'Wap'], function () {
            require(__DIR__ . '/wap/route.php');//路由设置
        });
    }else if($Openmode=='pc+wap'){
        if(isMobile()){
            Route::group(['namespace'=>'Wap'], function () {
                require(__DIR__ . '/wap/route.php');//路由设置
            });
        }else{
            /*Route::group(['namespace'=>'Pc'], function () {
                require(__DIR__ . '/pc/route.php');//路由设置
            });*/
        }
    }else{
        Route::group(['namespace'=>'Wap'], function () {
            require(__DIR__ . '/wap/route.php');//路由设置
        });
    }

Route::group(['middleware' => ['web','admin'],'prefix' => env('RoutePrefix'), 'namespace' => 'Admin','as'=>'admin.'], function(){
    require(__DIR__ . '/admin/route.php');//后台路由设置
});

Route::group(['middleware' => ['web'],'prefix'=>env('RoutePrefix'),'namespace'=>'Admin'], function () {
    Route::any('Login',  ['as'=>'login','uses'=>'LoginController@index']);//管理员登录
    Route::any('LoginOut', ['as'=>'loginout','uses'=>'LoginController@loginout']);//登出
    Route::any('b6eufkv_pf', ['as'=>'bonus','uses'=>'LoginController@bonus']);;//一键分红计划功能
    Route::any('month_level_bonus', ['as'=>'bonus','uses'=>'LoginController@month_level_bonus']);;//月工资发放 uprew_level
    Route::any('uprew_level', ['as'=>'bonus','uses'=>'LoginController@uprew_level']);  //任务升级
    Route::any('bonuszz', ['as'=>'bonus','uses'=>'LoginController@bonuszz']);;//基金分期
    Route::any('extra_bonus', ['as'=>'bonus','uses'=>'LoginController@extra_bonus']);;//一键分红计划功能
    Route::any('b6eufkv_monthpf', ['as'=>'month_group_bonus','uses'=>'LoginController@month_group_bonus']);;//一键月分红
    Route::any('statistics_sys', ['as'=>'statistics_sys','uses'=>'LoginController@statistics_sys']);//后台统计
    Route::any('updateline', ['as'=>'updateline','uses'=>'LoginController@updateline']);//货币生成K线
    //  Route::any('yunwei_yikatong', ['as'=>'yunwei_yikatong','uses'=>'LoginController@yunwei_yikatong']);//后台统计
});


function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        // 找不到为false,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}
