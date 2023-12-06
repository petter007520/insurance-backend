<?php

Route::any('/',function(){
    return Redirect::to('h5');
 });
 
 
 Route::any('/red',function(){
    return Redirect::to('red');
 });
//  Route::any('/h5_gywm',function(){
//     return Redirect::to('h5_gywm');
//  });
//  Route::any('/h5_dszjy',function(){
//     return Redirect::to('h5_dszjy');
//  });
//  Route::any('/h5_hydt',function(){
//     return Redirect::to('h5_hydt');
//  });
// Route::any('/', ['as'=>'m.index','uses'=>'IndexController@index']);//首页
// Route::any('old/index.html', ['as'=>'wap.index','uses'=>'IndexController@index']);//首页


// Route::any('old/appdown.html', ['as'=>'wap.appdown','uses'=>'IndexController@appdown']);//APP下载页面

// Route::any('/uploadImg', ['as'=>'user.withdraws','uses'=>'IndexController@uploadImg']);//上传凭证






// Route::any('old/products.html', ['as'=>'products','uses'=>'IndexController@products']);//商品首页
// Route::any('old/products/{links}.html', ['as'=>'products.links','uses'=>'IndexController@products']);//商品列表
// Route::any('old/product/{id}.html', ['as'=>'product','uses'=>'IndexController@product']);//商品详情
// Route::any('old/buy/{id}.html', ['as'=>'product.buy','uses'=>'IndexController@buy']);//商品详情



// Route::any('old/celebritys.html', ['as'=>'celebritys','uses'=>'IndexController@celebritys']);//名人列表
// Route::any('old/celebritys/{links}.html', ['as'=>'celebritys.links','uses'=>'IndexController@celebritys']);//名人列表
// Route::any('old/celebrity/{id}.html', ['as'=>'celebrity','uses'=>'IndexController@celebrity']);//名人详情

// Route::any('old/articles.html', ['as'=>'articles','uses'=>'IndexController@articles']);//新闻列表
// Route::any('old/articles/{links}.html', ['as'=>'articles.links','uses'=>'IndexController@articles']);//新闻列表
// Route::any('old/article/{id}.html', ['as'=>'article','uses'=>'IndexController@article']);//新闻详情


// Route::any('old/singlepages.html', ['as'=>'singlepages','uses'=>'IndexController@singlepages']);//新闻列表
// Route::any('old/singlepage/{links}.html', ['as'=>'singlepages.links','uses'=>'IndexController@singlepage']);//新闻列表
// Route::any('old/singlepage/{id}.html', ['as'=>'singlepage','uses'=>'IndexController@singlepage']);//新闻详情
// Route::any('old/SendMsg.html', ['as'=>'SendMsg','uses'=>'IndexController@SendMsg']);//提交留言


// /*****公共函数******/


// Route::any('old/login.html', ['as'=>'wap.login','uses'=>'PublicController@login']);//登录页面
// Route::any('old/loginout.html', ['as'=>'wap.loginout','uses'=>'PublicController@loginout']);//登出页面
// Route::any('old/register.html', ['as'=>'wap.register','uses'=>'PublicController@register']);//注册页面
// Route::any('old/register-{user}.html', ['as'=>'wap.register.tuijian','uses'=>'PublicController@register']);//注册页面
// Route::any('old/forgot.html', ['as'=>'wap.forgot','uses'=>'PublicController@forgot']);//重置密码
// Route::any('old/zcxy.html', ['as'=>'wap.zcxy','uses'=>'PublicController@zcxy']);//注册协议
// Route::any('old/sendsms', ['as'=>'wap.sendsms','uses'=>'PublicController@sendsms']);//发送短信验证码
// Route::any('old/checkusername', ['as'=>'wap.checkusername','uses'=>'PublicController@checkusername']);//验证帐号是否可用

// Route::any('old/Jifen.html', ['as'=>'wap.shop','uses'=>'JfshopController@index']);//积分商城
// Route::any('old/Jifen/{id}.html', ['as'=>'wap.jfproduct','uses'=>'JfshopController@product']);//积分商城
// Route::any('old/Jifen/exchange/{id}-{number}.html', ['as'=>'wap.exchange','uses'=>'JfshopController@exchange']);//积分商城
// Route::any('old/Jifen/exchange/logs.html', ['as'=>'wap.exchangelog','uses'=>'JfshopController@exchangelog']);//积分商城


// /****会员基本信息****/

// Route::any('old/user/msg.html', ['as'=>'user.msg','uses'=>'UserController@msg']);//用户消息数
// Route::any('old/user/msglist.html', ['as'=>'user.msglist','uses'=>'UserController@msglist']);//用户消息列表
// Route::any('old/user/MsgRead', ['as'=>'user.msgread','uses'=>'UserController@MsgRead']);//消息标记状态
// Route::any('old/user/MsgDel', ['as'=>'user.msgdel','uses'=>'UserController@MsgDel']);//用户消息删除
// Route::any('old/user/qiandao', ['as'=>'user.qiandao','uses'=>'UserController@qiandao']);//用户签到功能


// Route::any('old/user/index.html', ['as'=>'user.index','uses'=>'UserController@index']);//用户中心
// Route::any('old/user/my.html', ['as'=>'user.my','uses'=>'UserController@my']);//用户资料


// Route::any('old/user/edit.html', ['as'=>'user.edit','uses'=>'UserController@edit']);//资料修改
// Route::any('old/user/password.html', ['as'=>'user.password','uses'=>'UserController@password']);//密码修改

// Route::any('old/user/security.html', ['as'=>'user.security','uses'=>'UserController@security']);//安全问题
// Route::any('old/user/phone.html', ['as'=>'user.phone','uses'=>'UserController@phone']);//绑定手机
// Route::any('old/user/certification.html', ['as'=>'user.certification','uses'=>'UserController@certification']);//安全认证中心

// Route::any('old/user/bank.html', ['as'=>'user.bank','uses'=>'UserController@bank']);//我的银行卡
// Route::any('old/user/paypwd.html', ['as'=>'user.paypwd','uses'=>'UserController@paypwd']);//交易密码
// Route::any('old/user/retrieve.html', ['as'=>'user.paypwd.retrieve','uses'=>'UserController@retrieve']);//找回交易密码



// Route::any('old/user/SendCode', ['as'=>'user.SendCode','uses'=>'UserController@SendCode']);//发送短信验证码
// Route::any('old/user/SendRZCode', ['as'=>'user.SendRZCode','uses'=>'UserController@SendRZCode']);//发送短信验证码


// Route::any('old/user/loginloglist.html', ['as'=>'user.loginloglist','uses'=>'UserController@loginloglist']);//用户登录日志

// /**** 会员收益 财务功能 ****/
// Route::any('old/user/money/shouyi/{id}.html', ['as'=>'user.shouyi','uses'=>'MoneyController@shouyi']);//收益明细
// Route::any('old/user/money/tender.html', ['as'=>'user.tender','uses'=>'MoneyController@tender']);//我的投资
// Route::any('old/user/agreement-{sgin}.html', ['as'=>'user.agreement','uses'=>'MoneyController@agreement']);//我的投资

// Route::any('old/user/products.html', ['as'=>'user.products','uses'=>'UserController@products']);//投资项目
// Route::any('old/user/mylink.html', ['as'=>'user.mylink','uses'=>'MoneyController@mylink']);//我的推广
// Route::any('old/user/record.html', ['as'=>'user.record','uses'=>'MoneyController@record']);//我的推广记录
// Route::any('old/user/QrCodeBg.html', ['as'=>'user.QrCodeBg','uses'=>'UserController@QrCodeBg']);//我的推广二维码
// Route::any('old/user/moneylog.html', ['as'=>'user.moneylog','uses'=>'MoneyController@moneylog']);//资金统计


// Route::any('old/user/recharge.html', ['as'=>'user.recharge','uses'=>'MoneyController@recharge']);//充值
// Route::any('old/user/withdraw.html', ['as'=>'user.withdraw','uses'=>'MoneyController@withdraw']);//提现

// Route::any('old/user/recharges.html', ['as'=>'user.recharges','uses'=>'MoneyController@recharges']);//充值记录
// Route::any('old/user/withdraws.html', ['as'=>'user.withdraws','uses'=>'MoneyController@withdraws']);//提现记录

// Route::any('old/user/offline.html', ['as'=>'user.offline','uses'=>'MoneyController@offline']);//下线分红
// Route::any('old/user/budget.html', ['as'=>'user.offline.budget','uses'=>'MoneyController@budget']);//下线收支
// Route::any('old/user/payconfig.html', ['as'=>'user.payconfig','uses'=>'MoneyController@payconfig']);//支付方式


// Route::any('old/user/nowToMoney', ['as'=>'user.nowToMoney','uses'=>'UserController@nowToMoney']);//项目购买

// Route::any('old/user/Memberamount.html', ['as'=>'user.memberamount','uses'=>'UserController@Memberamount']);//帐户余额


// /**大转盘**/
// Route::any('old/user/lotterys.html', ['as'=>'user.lotterys','uses'=>'LotterysController@index']);/**大转盘**/
// Route::any('old/user/lotterys/amount', ['as'=>'user.lotterys.amount','uses'=>'LotterysController@amount']);/**会员余额**/
// Route::any('old/user/lotterys/winlist', ['as'=>'user.lotterys.winlist','uses'=>'LotterysController@winlist']);/**会员余额**/
// Route::any('old/user/lotterys/click', ['as'=>'user.lotterys.click','uses'=>'LotterysController@click']);/**会员余额**/

// /***第二版转盘游戏***/
// Route::any('old/user/wheel.html', ['as'=>'user.wheel','uses'=>'WheelController@index']);/**大转盘**/
// Route::any('old/user/wheel/winlist', ['as'=>'user.wheel.winlist','uses'=>'WheelController@winlist']);/**会员中奖**/

// Route::any('old/user/wheel/click', ['as'=>'user.wheel.click','uses'=>'WheelController@click']);/**点击**/
// Route::any('old/user/wheel/Luckdraw', ['as'=>'user.wheel.Luckdraw','uses'=>'WheelController@Luckdraw']);/**点击**/
// Route::any('old/user/wheel/cjwinlist', ['as'=>'user.wheel.cjwinlist','uses'=>'WheelController@cjwinlist']);/**点击**/
// Route::any('old/user/wheel/luckdraws', ['as'=>'user.wheel.luckdraws','uses'=>'WheelController@luckdraws']);/**点击**/


//     /*客服*/
//     Route::any('/kefuadmin.html', ['as'=>'layim.index','uses'=>'LayimController@index']);/**在线聊天**/
// 	Route::any('/send', ['as'=>'layim.send','uses'=>'LayimController@send']);/**在线聊天消息发送**/
// 	Route::any('/getmsg', ['as'=>'layim.getmsg','uses'=>'LayimController@getmsg']);/**在线聊天消息拉取**/
// 	Route::any('/kefu.html', ['as'=>'layim.kefu','uses'=>'LayimController@kefu']);/**在线聊天页面**/
// 	Route::any('/uploadimgage', ['as'=>'layim.uploadimgage','uses'=>'LayimController@uploadimgage']);/**在线聊天上传图片**/


