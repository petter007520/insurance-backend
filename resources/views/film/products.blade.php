@extends(env('WapTemplate').'.wap')

@section("header")

    <header>
        <a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/back.png")}}" class="left backImg"></a>
        <span class="headerTitle">我要投资</span>
        <a href="{{route("user.index")}}" class="headerRight"><img src="{{asset("mobile/film/images/touxiang.png")}}" height="33" style="float:right;vertical-align: middle; margin-top:5px; padding-left:5px;"></a>
    </header>

@endsection

@section("js")
    @parent
    <script src="{{asset("mobile/film/js/flexible.js")}}"></script>
    <script src="{{asset("mobile/film/js/iscroll.js")}}"></script>
    <script src="{{asset("mobile/film/js/navbarscroll.js")}}"></script>

@endsection

@section("css")

    @parent


@endsection

@section("onlinemsg")
    @parent
@endsection

@section('body')
    


     <div class="max">
        <div class="wrapper wrapper02" id="wrapper02">
            <div class="scroller">
                <ul class="clearfix">
                    {{--<li class="cur" style="margin-left: 0px; margin-right: 0px;"><a href="{{route("products")}}">全部项目</a></li>--}}

                    @if($ProductsCategoryList)
                        @foreach($ProductsCategoryList as $Ck=> $CategoryList)


                            @if($CategoryList->model=='links')

                                <li style="margin-left: 0px; margin-right: 0px;"><a href="{{$CategoryList->links}}">{{$CategoryList->name}}</a></li>

                            @else

                                <li @if($Ck==0)class="cur"@endif style="margin-left: 0px; margin-right: 0px;"><a href="{{route($CategoryList->model.".links",["links"=>$CategoryList->links])}}">{{$CategoryList->name}}</a></li>
                            @endif


                        @endforeach
                    @endif


                </ul>
            </div>
        </div>

	<div class="nav_img" style="margin-top: 10px;">
      <img src="http://licai.sweetd.top/Public/xin_mobile/static/picture/icon_sercuity_bg.png" width="100%" style="display:block">
      



        <div class="platformData">

            @foreach($AllProducts as $Pro)
     <a href="{{route("product",["id"=>$Pro->id])}}">
                            <div class="itemList">
                                <div class="project-cont" @if($Pro->tzzt==1)style="background: url(/wap/image/js.png) no-repeat 210px top;background-size: 130px;"@endif>
                                    </div>
								<div style="font-weight:bold;font-size:18px;"><li>
                                    <em><img src="http://licai.sweetd.top/Public/mobile/img/mortgage.gif" width="8%">{{$Pro->title}}</em>
                                    </li></div>
								<hr width=100% size=1 color=#9e9898 style="FILTER: alpha(opacity=100,finishopacity=0,style=1)">
                                    <div class="project-list" ><center>
                                        <div class="investInfo">
										<ul>

                                            <li><em>{{$Pro->qtje}}元</em><br> 起投金额</li>
                                            <li><em>{{$Pro->jyrsy}}%</em><br>每{{$Pro->qxdw=='个小时'?'时':'日'}}收益</li>
                                            <li><em>{{$Pro->shijian}}{{$Pro->qxdw=='个小时'?'小时':'天'}}</em><br>投资期限</li>
                                            <div class="clear"></div>
                                        </ul></center>
                                    </div>
								
									
									
									
									
									
<style> 
.div-a{ float:left;width:49%;border:1px} 
.div-b{ float:left;width:49%;border:1px} 
</style> 
<style> 
.className{ 
 	line-height:34px;
	height:34px;
	width:156px;
	color:#ffffff;
	background-color:#ededed;
	font-size:15px;
	font-weight:normal;
	font-family:Arial;
	background:-webkit-gradient(linear, left top, left bottom, color-start(0.05, #3babd4), color-stop(1, #177dab));
	background:-moz-linear-gradient(top, #3babd4 5%, #177dab 100%);
	background:-o-linear-gradient(top, #3babd4 5%, #177dab 100%);
	background:-ms-linear-gradient(top, #3babd4 5%, #177dab 100%);
	background:linear-gradient(to bottom, #3babd4 5%, #177dab 100%);
	background:-webkit-linear-gradient(top, #3babd4 5%, #177dab 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#3babd4', endColorstr='#177dab',GradientType=0);
	border:0px solid #333029;
	-webkit-border-top-left-radius:15px;
	-moz-border-radius-topleft:15px;
	border-top-left-radius:15px;
	-webkit-border-top-right-radius:15px;
	-moz-border-radius-topright:15px;
	border-top-right-radius:15px;
	-webkit-border-bottom-left-radius:15px;
	-moz-border-radius-bottomleft:15px;
	border-bottom-left-radius:15px;
	-webkit-border-bottom-right-radius:15px;
	-moz-border-radius-bottomright:15px;
	border-bottom-right-radius:15px;
	-moz-box-shadow: inset 0px 0px 0px 0px #1c1b18;
	-webkit-box-shadow: inset 0px 0px 0px 0px #1c1b18;
	box-shadow: inset 0px 0px 0px 0px #1c1b18;
	text-align:center;
	display:inline-block;
	text-decoration:none;
}
.className:hover {
	background-color:#f5f5f5;
	background:-webkit-gradient(linear, left top, left bottom, color-start(0.05, #177dab), color-stop(1, #3babd4));
	background:-moz-linear-gradient(top, #177dab 5%, #3babd4 100%);
	background:-o-linear-gradient(top, #177dab 5%, #3babd4 100%);
	background:-ms-linear-gradient(top, #177dab 5%, #3babd4 100%);
	background:linear-gradient(to bottom, #177dab 5%, #3babd4 100%);
	background:-webkit-linear-gradient(top, #177dab 5%, #3babd4 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#177dab', endColorstr='#3babd4',GradientType=0);
}
</style> 
<div class="div-a"><em>
                                                    <span class="jdt1">&nbsp;&nbsp;</span>
                                                    <span  class="jdt"><i style="width:<?php 
            
            //总投资额
            $buyamounts=  \App\Productbuy::where("productid",$Pro->id)->sum("amount");
            
            $jindu = $buyamounts / ($Pro->xmgm *100) + $Pro->xmjd;
            
            if ($jindu < 100) {
            	echo intval($jindu);
            }else{
            	echo 100;
            }
            ?>%"></i></span>
            <span class="jdt3"><?php  if ($jindu < 100) {
            	echo intval($jindu);
            }else{
            	echo 100;
            } 
            ?>%</span>
												
                                                </em></div> 
<div class="div-b"><div class='className'>立即投资</div></div> 
                                    <div class="clear"  ></div>
                                                                <div class="clear"></div></div>
                            
                        </a>

            @endforeach



    </div>

    <script type="text/javascript">
        $(function(){
            //demo示例一到四 通过lass调取，一句可以搞定，用于页面中可能有多个导航的情况
            $('.wrapper').navbarscroll({
                defaultSelect:0
            });
        });
    </script>
@endsection
@section('footcategoryactive')
    <script type="text/javascript">
        $("#menu1").addClass("active");
    </script>
@endsection

@section('footcategory')
    @parent
@endsection

@section("footer")
    @parent
@endsection

