<!doctype html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>{{Cache::get('CompanyShort')}}APP下载</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0,minimal-ui" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta content="yes" name="apple-mobile-web-app-capable"/>

    <meta content="yes" name="apple-touch-fullscreen"/>

    <meta content="telephone=no" name="format-detection"/>

    <meta content="email=no" name="format-detection" />

    <meta content="black" name="apple-mobile-web-app-status-bar-style" />



    <link rel="stylesheet" href="{{asset("mobile/app/css/appstyle.css")}}">

    <script>

        !(function(doc, win) {
            var docEle = doc.documentElement,//获取html元素
                event = "onorientationchange" in window ? "orientationchange" : "resize",//判断是屏幕旋转还是resize;
                fn = function() {
                    var width = docEle.clientWidth;
                    width && (docEle.style.fontSize = 100  * (width / 750) + "px");//设置html的fontSize，随着event的改变而改变。
                };

            win.addEventListener(event, fn, false);
            doc.addEventListener("DOMContentLoaded", fn, false);

        }(document, window));
    </script>

</head>

<body>

<div id="weixin_ios" style="display:none">
    <div class="click_opacity"></div>
    <div class="to_btn">
        <span class="span1">
            <img src="{{asset("mobile/app/click_btn.png")}}">
        </span>
        <span class="span2">
            <em>1</em> 点击右上角
            <img src="{{asset("mobile/app/menu.png")}}">
            打开菜单
        </span>
        <span class="span2">
            <em>2</em> 选择
            <img src="{{asset("mobile/app/safari.png")}}">
            用Safari打开下载
        </span>
    </div>
</div>

<div id="weixin_android" style="display: none">
    <div class="click_opacity"></div>
    <div class="to_btn"><span class="span1">
            <img src="{{asset("mobile/app/click_btn.png")}}">
        </span>
        <span class="span2">
            <em>1</em> 点击右上角
            <img src="{{asset("mobile/app/menu_android.png")}}">
            打开菜单
        </span>
        <span class="span2 android_open">
            <em>2</em> 选择
            <img src="{{asset("mobile/app/android_b.png")}}">
        </span>
    </div>
</div>


<script type="text/javascript">

    var u = navigator.userAgent;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

    var is_weixin = (function () {
        var ua = navigator.userAgent.toLowerCase();

        if (ua.match(/MicroMessenger/i) == "micromessenger") {//微信中打开网页
            return true;
        } else if (ua.match(/QQ/i) == "qq") {//qq里打开网页
            return true;
        } else {
            return false;
        }
    })();
    window.onload = function () {

        var android = document.getElementById('weixin_android');
        var ios = document.getElementById('weixin_ios');
        if (is_weixin) {
            if(isiOS){
                ios.style.display = 'block';
            }

            if(isAndroid){
                android.style.display = 'block';
            }

            return false;
        }
    }




</script>


<style>
    body{
        background-image: url('{{asset("mobile/app/bg.png")}}');
        height:100%;
        width:100%;
        overflow: hidden;
        background-size:cover;
        background-size:100%;
        margin: 0;
    }

    .app-download .down-link dt{font-size:.32rem;text-align:center;margin:1.5rem .6rem .8rem;color:#fff;height:.4rem;line-height: .4rem;position:relative;}
    .app-download .down-link dd{background-color:#81be0c;font-size:.4rem;color:#fff;height:.9rem;line-height:.9rem;text-align:center;border-radius:.5rem;margin:auto;width:5rem;}

    .app-download .down-link dd a{display:block;color:#fff;}

</style>
<section class="main" style="">
    <div class="app-download">
        <div class="down-banner" style="height: 200px;text-align: center;margin-top: 100px;">
            <img src="\uploads\{{\Cache::get('waplogo').'?t='.time()}}" alt="{{Cache::get('CompanyShort')}}" style="width: 20%;margin: 0 auto;"/>
            <span style="color: yellow;font-size: 30px;width: 100%;float:left;text-align: center;margin: 5px;">{{Cache::get('CompanyShort')}}</span>

            </div>
        <div class="down-link">
            <dl>
                <dt>
                    <span style="color: yellow;font-size: 20px;width: 100%;float:left;text-align: center;margin: 5px;">邀请好友,现金奖励天天有！</span>

                    <span style="color: yellow;font-size: 20px;width: 100%;float:left;text-align: center;margin: 5px;">快快下载APP赚钱吧！</span>
                </dt>
                <dd><a href="{{$links}}" style="text-decoration:none;"><img src="{{asset("mobile/app/".$app.".png")}}" style="width: 16px;" /> 点击下载</a></dd>
            </dl>
        </div>
    </div>
</section>



</body>

</html>