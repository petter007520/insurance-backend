    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
        <title>现金转不停</title>
        <link href="{{asset("mobile/film/wheel/css/mui.min.css")}}" rel="stylesheet">
        <link href="{{asset("mobile/film/wheel/css/component.css")}}" rel="stylesheet" type="text/css">
        <link href="{{asset("mobile/film/wheel/css/award.css")}}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="{{asset("mobile/film/wheel/css/animate.min.css")}}">
        </head>
<body class="mui-android mui-android-4 mui-android-4-3">
<header class="award-nav">
    <ul>
        <li class="active"><a href="javascript:void(0);">幸运大转盘</a></li>
        <!--<li><a href="javascript:void(0);">幸运抽奖</a></li>-->
        <div class="clear"></div>
    </ul>
</header>
<section class="section">
    <input type="hidden" name="gameState" id="gameState" value="0">
    <input type="hidden" name="gamed" id="gamed" value="0">
    <input type="hidden" name="userid" id="userid" value="{{$UserId}}">
    <!-------------抽奖页面-------------->
    <div class="ml-main" id="ml-main">
        <img class="main_back" src="{{asset("mobile/film/wheel/img/back.png")}}">
        <img class="animated zoomIn img_2_1" src="{{asset("mobile/film/wheel/img/img_1.png")}}">
        <img class="animated bounceIn img_2_2" src="{{asset("mobile/film/wheel/img/img_2.png")}}">
        <div class="kePublic">
            <!--转盘效果开始-->
            <div style="margin:0 auto">
                <div class="banner">
                    <div class="turnplate" style="">
                        <canvas class="item" id="wheelcanvas" width="516" height="516"></canvas>
                        <img id="tupBtn" class="pointer" src="{{asset("mobile/film/wheel/img/turnplate-pointer_2.png")}}">
                    </div>
                </div>
            </div>
            <!--转盘效果结束-->
            <div class="clear"></div>
        </div>
        <img class="bottom_shadow" src="{{asset("mobile/film/wheel/img/bottom_shadow.png")}}">
        <img class="animated zoomIn kePublic_back" src="{{asset("mobile/film/wheel/img/back1.png")}}">

        <!--------------滚动中奖纪录---------------->
        <div class="record_line" id="Marquee">


        </div>


        <!-------------底部声明-------------->
        <img class="rule_title" src="{{asset("mobile/film/wheel/img/rule_title.png")}}">
        <div class="rule_text">
            普通会员不能进行转盘抽奖,
            @if($memberlevel)
                @foreach($memberlevel as $level)
                    @if($level->wheels>0)
                {{$level->name}}每日可以抽奖{{$level->wheels}}次,
                    @endif
                @endforeach
            @endif
            抽中的奖金会第一时间返还您的账户。<br>
            <br>

        </div>
    </div>

    <!-------------中奖弹窗页面-------------->
    <div class="zj-main" id="zj-main">
        <div class="txzl">
            <div class="zj_text">
                中奖啦<br>恭喜获得<span id="jiangpin"></span>一份
            </div>
            <div class="close_zj">关闭</div>
        </div>
    </div>

    <!-------------谢谢参与弹窗-------------->
    <div class="xxcy-main" id="xxcy-main">
        <div class="xxcy">
            <div class="xxcy_text">
                很遗憾<br>没有抽中礼品
            </div>
            <div class="close_xxcy">关闭</div>
        </div>
    </div>
</section>
<section class="section awardbg" style="display: none; height: 640px;">
    <div class="award-btn">
        <a href="javascript:void(0)">立即抽奖</a>
        <div class="sy-num" style="text-align: center;color:#fff;margin-top:10px;">
            剩余奖券<font id="award_num">0</font>张!
        </div>
    </div>
    <div class="award-info-nav">
        <ul>
            <li class="active"><a href="javascript:void(0);">抽奖说明</a></li>
            <li onclick="cjlist();"><a href="javascript:void(0);" >中奖名单</a></li>
            <div class="clear">

            </div>
        </ul>
    </div>
    <div class="award-info" style="max-height:300px;overflow-y: scroll;">
        <div class="cont">
            尊敬的{{Cache::get('CompanyShort')}}会员：<br>
            &nbsp; &nbsp; &nbsp; &nbsp;感谢您对{{Cache::get('CompanyShort')}}的认可与支持，为答谢所有会员朋友，平台设定每周的周末为会员狂欢日，所有符合以下任意一个条件的会员朋友即可参与平台的幸运抽奖活动，具体规则如下;<br>
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;一,当周推荐新会员注册超过20人以上的<br>
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;二,当周累计投资超过1万元的<br>
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;三,当周累计提现超过2万元的<br>
            符合条件的会员朋友请及时联系客服领取幸运奖券，奖品为平台积分商城内的随机商品，中奖名单统一会在每周一的上午10点公布，感谢各位会员对{{Cache::get('CompanyShort')}}的支持及推广，{{Cache::get('CompanyShort')}}祝所有会员朋友投资愉快，幸运中大奖！<br>
            &nbsp; &nbsp;<br>
        </div>
        <div class="cont" style="display:none">
            <ul style="padding:0;" id="cjwinlist">

            </ul>
        </div>
    </div>
</section>

<script src="{{asset("mobile/film/wheel/js/jquery-2.1.4.min.js")}}"></script>
<script src="{{asset("mobile/film/wheel/js/mui.min.js")}}"></script>
<script type="text/javascript" src="{{asset("mobile/film/wheel/js/awardRotate.js")}}"></script>
<script type="text/javascript" src="{{asset("mobile/film/wheel/js/award.js")}}"></script>
<script src="{{asset("js/layer/layer.js")}}"></script>

<script>
    function cjlist() {
        $.post("{{route("user.wheel.cjwinlist")}}",{"_token":"{{ csrf_token() }}"},function(e){
            $('#cjwinlist').html(unescape(e))
        },'html');
    }
    $(function(){

        $.post("{{route("user.wheel.winlist")}}",{"_token":"{{ csrf_token() }}"},function(e){
            $('#Marquee').html(unescape(e))
        },'html');

        cjlist();

        $.post("{{route("user.wheel.luckdraws")}}",{"_token":"{{ csrf_token() }}"},function(e){
            $('#award_num').html(unescape(e))
        },'html');



        var peizhi=[];
        var peizhicolor=[];
        @if(isset($LotteryPeizhi))
                @foreach($LotteryPeizhi as $pk=> $pz)
                peizhi.push("{{ str_replace(",","\\n",$pz->name)}}");

                @if($pk%2==0)
                peizhicolor.push("#FAEBD7");
                @else
                peizhicolor.push("#FFFFFF");
                @endif

                @endforeach
         @endif
        turnplate.restaraunts = peizhi;
        turnplate.colors = peizhicolor;
        $("img").on("click",function(){
            return false;
        });
        document.addEventListener("WeixinJSBridgeReady",function(){
            WeixinJSBridge.call('hideOptionMenu');
        });
        $(".award-nav ul li").click(function () {
            var index = $(this).index();
            $(this).addClass('active').siblings("li").removeClass("active");
            $(".section").eq(index).show().siblings(".section").hide();
        });
        $(".award-info-nav ul li").click(function () {
            var index = $(this).index();
            $(this).addClass('active').siblings("li").removeClass("active");
            $(".award-info .cont").eq(index).show().siblings(".cont").hide();
        });
        $(".award-btn").click(function(){
            $.ajax({
                type:"get",
                url:"{{route("user.wheel.Luckdraw")}}",
                data:"",
                dataType:"json",
                success:function(data){
                    //console.log(data);
                    if(data.luckdraws){
                        $('#award_num').html(data.luckdraws);
                    }else{
                        $.post("{{route("user.wheel.luckdraws")}}",{"_token":"{{ csrf_token() }}"},function(e){
                            $('#award_num').html(unescape(e))
                        },'html');
                    }
                    if(data.state){

                        layer.alert(data.jiangping,{title:data.msg});
                        cjlist();
                    }else{

                        layer.msg(data.msg,function () {
                            if(data.msg == '请先登录'){
                                location.href='/login.html';
                            }
                        });
                        return;
                    }
                },
                error:function(data){
                    console.log("error:"+data);
                    return;
                }
            });
        });
        var height = document.body.offsetHeight;
        $(".awardbg").css("height",height);

/*        $.ajax({
            type:"get",
            url:"user-public_check_member",
            data:"",
            dataType:"json",
            success:function(data){
                $("#award_num").html(parseInt(data.award_num));
            },
            error:function(data){
                $("#award_num").html(0);
                console.log("error:"+data);
                return;
            }
        });
        */
    });
</script>
</body>
</html>