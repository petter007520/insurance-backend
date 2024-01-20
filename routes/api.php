<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('cors')->group(function () {
    // 三方支付
    Route::any('/online_pay/pay', 'Api\PaymentController@thirdToMoney');//发起支付
    Route::any('/online_pay_notify/notify', 'Api\PaymentController@thirdPayNotify');//异步接口
    Route::any('/online_pay/zfb', 'Api\PaymentzfbController@thirdToMoney');//发起支付
    Route::any('/online_pay_not/zfb', 'Api\PaymentzfbController@notify_res');//异步接口
	Route::any('/online_pay/ymd', 'Api\PaymentymdController@thirdToMoney');//发起支付
    Route::any('/online_pay_not/ymd', 'Api\PaymentymdController@notify_res');//异步接口
    Route::any('/online_df_not/ymd', 'Api\PaymentymdController@notify_df_res');//异步接口

	/****注册登录模块****/
	Route::any('/login', 'Api\PublicController@login');//登录页面
	Route::any('/logout', 'Api\PublicController@loginout');//退出登录
	Route::any('/register', 'Api\PublicController@register');//注册页面
    Route::any('/forget','Api\PublicController@forget')->middleware('throttle:20,1');//忘记界面
	Route::any('/captcha_img', 'Api\PublicController@captcha');//图文验证码
	Route::any('/new_sendSms', 'Api\PublicController@new_sendMsm');//发送短信
	Route::any('/user/forget', 'Api\PublicController@forget');//忘记密码

    /* 公用设置 */
    Route::any('/getapilink','Api\PublicController@getApiList');//地址列表
    Route::any('/getCommonSetting','Api\UserController@getCommonSetting');//公用设置
    Route::any('/payment', 'Api\UserController@change_pay');//支付方式
    Route::any('/uploadImg', 'Api\UserController@uploadImg');//上传凭证
    Route::any('/version', 'Api\PublicController@version');//上传凭证

    Route::any('/user/wealthList', 'Api\CommonController@wealthList');//用户新购信息

    Route::any('/order/check', 'Api\UserController@checkOrder');//订单检查
    Route::any('/user/message', 'Api\UserController@message');//订单检查

    Route::any('/act/sign', 'Api\ActController@sign');
    Route::any('/act/score', 'Api\ActController@scoreLog');
    Route::any('/act/rewards', 'Api\ActController@rewardList');
    Route::any('/act/lottory', 'Api\ActController@lottory');
    Route::any('/act/luckey', 'Api\ActController@lottoryLog');
    Route::any('/act/luckeymine', 'Api\ActController@MyLottoryLog');
    Route::any('/act/userscore', 'Api\ActController@getuserscore');
    Route::any('/act/address', 'Api\ActController@updateUserAddress');


	/****首页模块****/
	Route::any('index','Api\CommonController@index');//首页
	Route::any('/user/userAssets','Api\UserController@index');//用户资产
	Route::any('/user/changeAccount','Api\UserController@changeAccount');//切换登录
	Route::any('productList','Api\CommonController@productList');//产品列表
	Route::any('categoryList','Api\CommonController@categoryList');//分类列表
	Route::any('product_detail','Api\CommonController@product_detail');//产品列表
	Route::any('/treeprojects/{type}','Api\IndexController@treeprojects');//小树盘列表
	Route::any('/project/{id}','Api\IndexController@project');//基金详情
	Route::any('/share','Api\IndexController@share');//分享海报

    Route::any('/create_order', 'Api\UserController@create_order')->middleware(['checklimit']);//产品购买
    Route::any('/order_detail', 'Api\UserController@order_detail');//订单详情
    Route::any('/order_pay', 'Api\UserController@order_pay');//订单支付
    Route::any('/user/agreement', 'Api\MoneyController@agreement');//协议
    Route::any('/user/contract', 'Api\MoneyController@contract');//合同
    Route::any('/contact', 'Api\IndexController@contact');//客服列表
    Route::any('/order/index', 'Api\UserController@order_list');//保单列表
    Route::any('/order/userOrderDetail', 'Api\UserController@userOrderDetail');//已支付订单详情
    Route::any('/applyClaims', 'Api\UserController@applyClaims');//报销申请
    Route::any('/apply_health', 'Api\UserController@apply_health');//大额健康金申请
    Route::any('/claimsOrder', 'Api\UserController@claimsOrder');//报销订单
    Route::any('/getClaimsDetail', 'Api\UserController@getClaimsDetail');//报销订单详情
    Route::any('/questionList', 'Api\UserController@question');//常见问题

	Route::any('/video/index', 'Api\VideosController@index');//视频列表
	Route::any('/video/detail', 'Api\VideosController@detail');//视频详情
	Route::any('/video/like', 'Api\VideosController@like');//视频点赞
	Route::any('/articles/index', 'Api\ArticlesController@index');//新闻列表
	Route::any('/articles/detail', 'Api\ArticlesController@detail');//资讯详情
	Route::any('/article/detail', 'Api\ArticlesController@detailWithType');//文章详情

	Route::any('/user/registerChild', 'Api\UserController@registerChild');//注册子账户

	Route::any('/user/index','Api\UserController@index');//用户中心
	Route::any('/get_invite_link','Api\IndexController@get_link');//获取邀请链接域名
	Route::any('/user/my', 'Api\UserController@my');//用户资料
	Route::any('/user/myEdit','Api\UserController@myedit');//资料修改
	Route::any('/user/banks','Api\UserController@banks');//银行卡包
	Route::any('/user/bankAdd','Api\UserController@bankAdd');//添加银行卡
	Route::any('/user/bankDel','Api\UserController@bankDel');//删除银行卡
	Route::any('/user/addresses','Api\UserController@addresses');//我的收货地址列表
	Route::any('/user/addressEdit','Api\UserController@addressEdit');//收货地址修改
	Route::any('/user/addressAdd','Api\UserController@addressAdd');//收货地址添加
	Route::any('/user/addressDel','Api\UserController@addressDel');
	Route::any('/user/statusEdit','Api\UserController@statusEdit');//默认地址
	Route::any('/user/paypwd','Api\UserController@paypwd');//交易密码修改
	Route::any('/user/mobile', 'Api\UserController@mobile');//绑定手机

	/****会员模块 —— 个人消息****/
	Route::any('/user/info', 'Api\UserController@userInfo');//用户信息
	Route::any('/user/authInfo', 'Api\UserController@authInfo');//实名信息
	Route::any('/user/msg', 'Api\UserController@msg');//用户消息数
	Route::any('/user/msglist', 'Api\UserController@msglist');//用户消息列表
	Route::any('/user/MsgRead', 'Api\UserController@MsgRead');//消息标记状态
	Route::any('/user/MsgDel', 'Api\UserController@MsgDel');//用户消息删除
    Route::any('/user/myDetail', 'Api\UserController@myDetail');//资金统计
    Route::any('/user/receive','Api\UserController@one_card_receive');//一卡通领取

    Route::any('/user/withdrawConfig','Api\UserController@withdrawConfig');//提现配置
	/*
	/user/msg   用户消息未读数
	/user/msglist   用户消息列表
	/user/MsgRead   消息标记已读状态   id
	/user/MsgDel    用户消息删除   id
	*/
	/****会员模块 —— 个人记录****/
	Route::any('/user/myteam', 'Api\UserController@myteam');//我的团队
	Route::any('/user/authentication', 'Api\UserController@authentication');//提交个人认证
    Route::any('/user/is_check', 'Api\UserController@is_check');

	Route::any('/user/withdraw', 'Api\MoneyController@withdraw')->middleware(['checklimit']);//提现
    Route::any('/user/withdraws', 'Api\MoneyController@withdraws');//提现记录
	Route::any('/user/recharge', 'Api\MoneyController@recharge');//充值
    Route::any('/user/recharges', 'Api\MoneyController@recharges');//充值记录


	Route::any('/search', 'Api\IndexController@search');//搜索

	/*im推广说明*/
	Route::any('/extension', 'Api\PublicController@extension');//推广说明
    Route::any('/update_download', 'Api\PublicController@update_download');//更新app接口
    Route::any('/user/myProductDetail', 'Api\UserController@myProductDetail');//项目详情
    Route::any('/user/withdra_reminder', 'Api\MoneyController@withdra_reminder');//提现温馨提示
    Route::any('/user/bankEdit', 'Api\UserController@bankEdit');//修改我的银行卡信息

    // Route::any('/user/tamRanking','Api\UserController@tamRanking');//全服排名
    Route::any('/user/queryLevelCode','Api\MoneyController@queryLevelCode');
    Route::any('/checkApiLink','Api\PublicController@checkApiLink');//前端检测域名
    /***新接口***/
    Route::any('/user/teamReport', 'Api\UserController@teamReport');//团队业绩
    Route::any('/user/set_myinfo', 'Api\UserController@set_myinfo');//新更新个人资料
    Route::any('/user/team', 'Api\UserController@team');//我的团队
    Route::any('/user/teamList', 'Api\UserController@teamList');//直推成员
    Route::any('/index/outer_chain', 'Api\IndexController@outer_chain');//外链地址
    Route::any('/user/getImLink', ['as'=>'user.SendCode','uses'=>'Api\UserController@getImLink']);//客服入口
    Route::any('/equity_reminder', ['as'=>'user.SendCode','uses'=>'Api\MoneyController@equity_reminder']);//客服入口
    Route::any('/user/community', 'Api\UserController@community');//社区
    Route::any('/user/community_detail', 'Api\UserController@community_detail');//社区详情



     Route::any('/user/xj_qiandao', ['as'=>'user.xj_qiandao','uses'=>'Api\UserController@xj_qiandao']);//用户签到功能
     Route::any('/user/sign_health', ['as'=>'user.sign_health','uses'=>'Api\UserController@sign_health']);//签到领取健康金
     Route::any('/user/my_health', ['as'=>'user.sign_health','uses'=>'Api\UserController@my_health']);//健康金
    Route::any('/user/sign_log', ['as'=>'user.sign_log','uses'=>'Api\PublicController@sign_log']);//
    Route::any('/user/equity_book', ['as'=>'user.sign_log','uses'=>'Api\UserController@equity_book']);//证书
    Route::any('/user/is_check_id', ['as'=>'user.is_check_id','uses'=>'Api\UserController@is_check_id']);//是否实名认证
    Route::any('/user/buyVipRecord', 'Api\UserController@buyVipRecord');//我够买的等级记录
    Route::any('/currline', ['as'=>'user.withdraws','uses'=>'Api\IndexController@currline']);//货币K线
    Route::any('/update_currline', ['as'=>'user.withdraws','uses'=>'Api\PublicController@update_currline']);//手动更新货币K线   startkey  pid

    Route::any('/user/qd_index', 'Api\UserController@qd_index');//签到页面
    Route::any('/user/huicenter', 'Api\UserController@huicenter');//签到页面

    //首页商品管理
    Route::any('/stproducts/{type}','Api\IndexController@stproduct');//实体商品管理
    Route::any('/stproductinfo/{id}','Api\IndexController@stproductinfo');//首页商品详情
    Route::any('/stproductbuy/list','Api\IndexController@stproductbuy');//首页商品详情 stproductbuyinfo
    Route::any('/stproductbuy/stproductbuyinfo','Api\IndexController@stproductbuyinfo');//订单详情
    Route::any('/app/getversion','Api\IndexController@getappversion');//获取app版本
    Route::any('/check_level', 'Api\PublicController@checklevel');//域名通畅测试
    Route::any('/online_pay/proymd', 'Api\PaymentymdController@prothirdToMoney');//发起支付
    Route::any('/act/memeber/address', 'Api\ActController@updateAddres');  //地址修改
    Route::any('/act/memeber/addressinfo', 'Api\ActController@Addresinfo');  //地址详情

    Route::any('/user/yeindex', ['as'=>'user.yeindex','uses'=>'Api\UserController@yeindex']);  //余额宝详情

    Route::any('/user/huicenter', 'Api\UserController@huicenter');  //余额宝详情
    Route::any('/user/set_read', 'Api\UserController@set_travel_read');  //余额宝详情

    Route::any('/index/getarealist', 'Api\IndexController@getarealist');//获取地区列表
    Route::any('/index/tgfulilist', 'Api\IndexController@tgfulilist');//推广福利列表
    Route::any('/index/gmfulilist', 'Api\IndexController@gmfulilist');//购买福利列表
    Route::any('/index/hzpp', 'Api\IndexController@hzpp');//购买福利列表
    Route::any('/user/lqrwjijin', 'Api\UserController@lqrwjijin');//修改我的银行卡信息
    Route::any('/user/lqmounth', 'Api\UserController@lqmounth');  //领取月工资
    Route::any('/user/monthlog', 'Api\UserController@monthlog');  //会员等级
    Route::any('/user/getzctree', 'Api\UserController@getzctree');  //登陆任务领取小树苗
    Route::any('/user/treejs', 'Api\UserController@treejs');  //浇水
    Route::any('/user/treeaword', 'Api\UserController@treeaword');  //领取将近
    Route::any('/user/bigtreejs', 'Api\UserController@bigtreejs');  //大树浇水
    Route::any('/user/bigtreeinfo', 'Api\UserController@bigtreeinfo');  //大树基本信息
    Route::any('/user/treetask', 'Api\UserController@treetask');  //树木任务
    Route::any('/user/getsumfeetree', 'Api\UserController@getsumfeetree');  //购买总数领取树木
    Route::any('/user/getlxtree', 'Api\UserController@getlxtree');  //连续签到
    Route::any('/user/yuebao', 'Api\UserController@yuebao');  //连续签到
});



