@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <img src="\uploads\{{\Cache::get('waplogo').'?t='.time()}}" height="95%">
        @if(!isset($Member))
            <a href="{{route("wap.login")}}" class="headerRight">登录/注册</a>
        @endif
    </header>
@endsection

@section("js")
    <script type="text/javascript" src="{{asset("js/jquery.js")}}"></script>
    <script src="{{asset("js/layui/layui.js")}}"></script>
    <script src="{{asset("mobile/film/js/swiper.min.js")}}"></script>
@endsection

@section("css")
    @parent
    <link rel="stylesheet" href="{{asset("js/layui/css/layui.css")}}"/>
    <link rel="stylesheet" href="{{asset("mobile/film/css/swiper.min.css")}}"/>
@endsection



@section('body')



    {{-- 手机首页幻灯片 --}}

    <div id="banner" class="swiper-container-horizontal" style="margin-top: 50px;">
        <div class="swiper-wrapper">

            @if($wapad['banner'])
                @foreach($wapad['banner'] as $ad)
                    <div class="swiper-slide"><img src="{{$ad->thumb_url}}"></div>
                @endforeach
            @endif

        </div>

    </div>



    <script type="text/javascript">
        window.onload = function () {
            var mySwiper1 = new Swiper('#banner', {
                autoplay: 5000,
                visibilityFullFit: true,
                autoplayDisableOnInteraction: false,
                loop: true,
                pagination: '.pagination',
            });
        }
    </script>

    {{-- 手机首页幻灯片end  --}}



    <script>

        @if(isset($_GET["gg"]) && $_GET["gg"]=='1')
        layui.use('layer', function () {
            var layer = layui.layer;

            //公告信息框
            layer.open({
                title: '系统公告',
                content: '{{\App\Formatting::Format(\Cache::get('gg'))}}'
                , btn: '关闭公告'
            });
        });
        @endif


    </script>




    <div class="index-nav-wrap" style="margin-top:20px; padding-top:10px;">
        <div class="index-nav">
            <ul class="clear">
                 <li><a href="{{route("articles")}}">
                                               <em><img src="{{asset("mobile/film/images/index-hlf-icon5.png")}}"></em>
                        <p>新闻资讯</p>
                    </a></li>
                <li><a href="{{route("products")}}">
                        <em><img src="{{asset("mobile/film/images/index-hlf-icon2.png")}}"></em>
                        <p>理财项目</p>
                    </a></li>
                <li><a href="{{route("user.wheel")}}">
                        <em><img src="{{asset("mobile/film/images/index-hlf-icon7.png")}}"></em>
                        <p>幸运大转盘</p>
                    </a></li>
                <li><a href="{{route("wap.shop")}}">
                        <em><img src="{{asset("mobile/film/images/index-hlf-icon6.png")}}"></em>
                        <p>积分商城</p>
                    </a></li>
                <li><a href="{{route("wap.shop")}}">
                        <em><img src="{{asset("mobile/film/images/userphone.jpg")}}"></em>
                        <p>app 下载</p>
                    </a></li>
                <li><a href="{{route("user.withdraw")}}">
                        <em><img src="{{asset("mobile/film/images/index-hlf-icon3.png")}}"></em>
                        <p>我要提现</p>
                    </a></li>
                <li><a href="{{route("user.recharge")}}">
                        <em><img src="{{asset("mobile/film/images/index-hlf-icon8.png")}}"></em>
                        <p>我要充值</p>
                    </a></li>
                <li><a href="{{route("user.index")}}">
                        <em><img src="{{asset("mobile/film/images/index-hlf-icon1.png")}}"></em>
                        <p>全部</p>
                    </a></li>
            </ul>
        </div>
        <div class="index-nav" style="margin-top:60px;">
                

       
    </div>
    <div class="index-nav-wrap"></div>


    <a href="/" class="divider notice">
        <div id="fabiaoContent">
            <marquee scrolldelay="200" onmouseout="this.start()" onmouseover="this.stop()">
                <span style="font-size:18px;">
                    <div><span style="font-size:12px;"><span style="color:#daa520;">
                                <strong>
                                    {{\App\Formatting::Format(\Cache::get('gg'))}}
                                </strong></span></span></div></span>
            </marquee>
        </div>
    </a>
    <div class="clear"></div>



{{--

    <div class="zs_lianjie" style="margin-top:50px;">
        @if($wapad['hongbao'])
            @foreach($wapad['hongbao'] as $ad)
                <a href="{{$ad->url}}"><img src="{{$ad->thumb_url}}"></a>
            @endforeach
        @endif

    </div>
--}}

    {{--视频播放--}}
    @if(\Cache::get('videoopen')=='开启')

        <script src="/mobile/public/html5media.min.js"></script>
        <video id="shakeVideo" autoplay="autoplay" controls="controls" webkit-playsinline="true" playsinline="true"
               controlslist="nodownload" src="\uploads\{{\Cache::get('videourl')}}" width="100%" hight="200px"></video>

        <script>
            var video = document.getElementById("shakeVideo");
            video.play();
        </script>

    @endif
    {{--视频播放 end--}}




    {{--项目推荐列表--}}



        <div class="max">

        @if($ProductsCategory)
            @foreach($ProductsCategory as $PCategory)
                @if(count($PCategory->Products)>0)



                        <div class="newtit">
                           <div class="x_gg_l"><b>{{$PCategory->name}}&nbsp;<img src="{{$PCategory->thumb_url}}" height="50" "position:absolute; left:120px; top:320px; "></div>
                            @if($PCategory->model=='links')
                                <a class="x_gg_r" href="{{$PCategory->links}}">更多<i class="fa fa-angle-right" aria-hidden="true"></i></a>
                            @else
                                <a class="x_gg_r" href="{{route($PCategory->model.".links",["links"=>$PCategory->links])}}">更多<i class="fa fa-angle-right" aria-hidden="true"></i></a>
                            @endif

                        </div>

                        <div class="list_outer">
                         



                        @foreach($PCategory->Products as $Pro)

<style>
    .all {
        width:97%;
        height:100%;
        margin-left:1%;
        margin-top:1%;
        border-radius:3px;
        box-shadow:rgb(141 238 238) 0px 0px 10px inset;
    }
</style>
<style>
.className{ 
 	line-height:36px;
	height:36px;
	width:100%;
	color:#ffffff;
	background-color:#ededed;
	font-size:18px;
	font-weight:bold;
	font-family:Arial;
	background:-webkit-gradient(linear, left top, left bottom, color-start(0.05, #4a8cd6), color-stop(1, #4ea7d8));
	background:-moz-linear-gradient(top, #4a8cd6 5%, #4ea7d8 100%);
	background:-o-linear-gradient(top, #4a8cd6 5%, #4ea7d8 100%);
	background:-ms-linear-gradient(top, #4a8cd6 5%, #4ea7d8 100%);
	background:linear-gradient(to bottom, #4a8cd6 5%, #4ea7d8 100%);
	background:-webkit-linear-gradient(top, #4a8cd6 5%, #4ea7d8 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#4a8cd6', endColorstr='#4ea7d8',GradientType=0);
	border:0px solid #dcdcdc;
	-webkit-border-top-left-radius:8px;
	-moz-border-radius-topleft:8px;
	border-top-left-radius:8px;
	-webkit-border-top-right-radius:8px;
	-moz-border-radius-topright:8px;
	border-top-right-radius:8px;
	-webkit-border-bottom-left-radius:8px;
	-moz-border-radius-bottomleft:8px;
	border-bottom-left-radius:8px;
	-webkit-border-bottom-right-radius:8px;
	-moz-border-radius-bottomright:8px;
	border-bottom-right-radius:8px;
	-moz-box-shadow:0px 10px 14px -7px #276873;
	-webkit-box-shadow:0px 10px 14px -7px #276873;
	box-shadow:0px 10px 14px -7px #276873;
	text-align:center;
	display:inline-block;
	text-decoration:none;
}
.className:hover {
	background-color:#f5f5f5;
	background:-webkit-gradient(linear, left top, left bottom, color-start(0.05, #4ea7d8), color-stop(1, #4a8cd6));
	background:-moz-linear-gradient(top, #4ea7d8 5%, #4a8cd6 100%);
	background:-o-linear-gradient(top, #4ea7d8 5%, #4a8cd6 100%);
	background:-ms-linear-gradient(top, #4ea7d8 5%, #4a8cd6 100%);
	background:linear-gradient(to bottom, #4ea7d8 5%, #4a8cd6 100%);
	background:-webkit-linear-gradient(top, #4ea7d8 5%, #4a8cd6 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#4ea7d8', endColorstr='#4a8cd6',GradientType=0);
}
</style>



                       <a href="{{route("product",["id"=>$Pro->id])}}">
                            <div class="all">
                                <div class="project-cont" @if($Pro->tzzt==1)style="background: url(/wap/image/js.png) no-repeat 210px top;background-size: 130px;"@endif>
                                    </div>

                                    <div class="project-list" ><center>
                                        <div class="investInfo">
										<ul>

                                            <li><em>{{$Pro->qtje}}元</em><br> 起投金额</li>
                                            <li><em><font size="6">{{$Pro->jyrsy}}</font>%</em><br>日{{$Pro->qxdw=='个小时'?'时':'化'}}收益率</li>
                                            <li><em>{{$Pro->shijian}}{{$Pro->qxdw=='个小时'?'小时':'天'}}</em><br>投资期限</li>
										
                                            <div class="clear"></div>
                                        </ul></center>
                                    </div>
									<div class='className'>立即投资</div>
									<li></li>
                                    <div class="clear"  ></div>
								 <div class="clear"></div></div>
								
                            
                        </a>



                        @endforeach

                        </div>
                @endif

            @endforeach
        @endif

        </div>



    {{--项目推荐列表 end--}}

<div style="text-align:center">
    <pre id="line1">
        <span>
Copyright<span class="entity"><span>©</span></span>2020
	</span>
    </pre>
</div>
<br />

@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
    <p><br/></p>
@endsection


@section('footcategoryactive')
    <script type="text/javascript">
        $("#menu0").addClass("active");
    </script>
@endsection

