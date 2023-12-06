@extends(env('WapTemplate').'.wap')

@section("header")
@endsection

@section("js")
    @parent
@endsection

@section("css")
    @parent
    <link rel="stylesheet" href="{{asset("mobile/film/css/user.css")}}"/>
    <style>
        body {
            padding-top: 0px;
            background: #F1F1F1;
        }
        .global-fenhong{
            padding:10px 20px;
            background: #fff;
            margin-bottom:10px;
        }
        .global-fenhong b{
            color:red;
        }
        .wenhao{
            width: 20px;
            height: 20px;
            border: 1px solid #000;
            display: inline-block;
            text-align: center;
            line-height: 16px;
            border-radius: 50%;
        }
    </style>

@endsection

@section("onlinemsg")
    @parent
@endsection

@section('body')
    <?php
    //总投资额
    $buyamounts=  \App\Productbuy::where("userid",$Member->id)->sum("amount");
    //已投项目
    $buycounts=  \App\Productbuy::where("userid",$Member->id)->count();
    //投资收益
    $moneylog_moneys=  \App\Moneylog::where("moneylog_userid",$Member->id)->where("moneylog_type","项目分红")->sum("moneylog_money");
    //结束项目
    $buyjscounts=  \App\Productbuy::where("userid",$Member->id)->where("status","0")->count();
    $xiaxians=  count(\App\Member::treeuid($Member->invicode));
    //本金回收
    $buyjsamounts=  \App\Productbuy::where("userid",$Member->id)->where("status","1")->sum("amount");
    $withdrawals= \App\Memberwithdrawal::where("userid",$Member->id)->where("status","0")->sum("amount");
    $recharges= \App\Memberrecharge::where("userid",$Member->id)->where("status","0")->sum("amount");

    $fenhongs= \App\Moneylog::where("moneylog_userid",$Member->id)->where("moneylog_status","+")->where("moneylog_type","项目分红")->sum("moneylog_money");
    ?>
    <div class="userinfo nx-userinfo2">
        <img src="{{asset("mobile/film/images/give_gold_gray.png")}}" style="width: 48px;left: 7px;position: absolute;top: 14px;">
        <div class="nx-suer-top">
            <div>
                <div class="trj-account-user-img">

                </div>
                <span style="padding-top: 0.8rem;padding-bottom: 0.5rem;color:#f7f7f7;font-weight:bold;font-size: 17px;" class="ng-binding">
				  <font style="font-family:'Bebas';"><?php  echo \App\Member::half_replace(\App\Member::DecryptPassWord($Member->mobile));?></font><font style="font-size:14px; color:#c7c7c7;"></font></span><font style="font-size:14px; color:#c7c7c7;">

                </font></div>
            <font style="font-size:14px; color:#c7c7c7;">
            </font></div>
        <font style="font-size:14px; color:#c7c7c7;">

        <span style="width:195px;   left: 60px;position: absolute;top: 38px;">
            <b class="grade-cont grade-1"><?php echo $memberlevelName[$Member->level]; ?></b>
                    </span>
            <a href="{{route("user.msglist")}}"><img src="{{asset("mobile/film/images/home_message_icon.png")}}" style="position: absolute;z-index: 9;right: 5px;top: 60px;width: 11%;"></a>

            <div class="f_r" style="height: 29px;right: 0.8rem;position: absolute;top: 13px;">
                <a href="javascript:void(0)" id="qiandao"><img src="{{asset("mobile/film/images/qiandao.png")}}" style="height:100%;" onclick="qiandao()">
                    <img src="{{asset("mobile/film/images/login_in.png")}}" style="height:100%;" onclick="qiandao()"></a>

            </div>

            <div class="trj-xilan" ng-if="accountInfo.source?true:false">
                <div>
                </div>
            </div>
            <h1 class="nx-balaner"><span ng-if="visible" ui-sref="app.accountNewDetails" class="ng-binding" href="#">
            <font style="font-family:'Bebas';"> <?php echo sprintf("%.2f",$Member->amount+$Member->is_dongjie); ?></font></span>
                <p><font style="font-family:'Bebas';">我的资产（元）</font></p>
            </h1>
            <h1 class="nx-balaner"><span ng-if="visible" ui-sref="app.accountNewDetails" class="ng-binding" href="#">
            <font style="font-family:'Bebas';"> <?php echo sprintf("%.2f",$moneylog_moneys); ?></font></span>
                <p><font style="font-family:'Bebas';">累计收益(元)</font></p>
            </h1>
            <div class="details nx-details">
                <ul class="details_l">
                    <li class="details-top">
                        <span class="trj-detais_l-num ng-binding"><font style="font-family:'Bebas';"><?php echo sprintf("%.2f",$Member->amount); ?></font></span>
                        <p>
                            <font style="font-family:'Bebas';">可用余额(元)</font>
                        </p>
                    </li>
                </ul>
                <ul class="details_r">
                    <li class="details-top"><span class="trj-detais_l-num ng-binding">
                    <font style="font-family:'Bebas';">
                      <?php echo sprintf("%.2f",$buyjsamounts); ?>                    </font>
                <p>
                    <font style="font-family:'Bebas';">等待回收(元)</font>
                </p>
            </span></li>
                </ul>
            </div>
        </font>
    </div>



    <div class="global-fenhong">
        <span>预计平台今日全球分红奖励为<b>{{rand(265,909)}}</b>元/份 <a href="javascript:void(0)" class="wenhao">?</a></span>
    </div>

    <a href="{{route("user.mylink")}}">
        <img src="{{asset("mobile/film/images/qms_fx_yqhyou_img2.png")}}" alt="" style="width: 100%;height: 80px;margin-bottom:-4px;">
    </a>

    <div class="mod" style="position:relative; top:-13px;">
        <div class="all">
            <a href="{{route("user.recharge")}}">
                <div><img src="{{asset("mobile/film/images/tab3_1_008_1.png")}}" style="width:40px;"></div>
                <p class="ng-binding"></p><h5>充值</h5>
                <p></p>
            </a>
        </div>
        <div class="all">
            <a href="{{route("products")}}">

                <div><img src="{{asset("mobile/film/images/tab3_1_005_1.png")}}" style="width:40px;"></div>
                <p class="ng-binding"></p><h5>投资</h5>
                <p></p>
            </a>

        </div>
        <div class="all" style="  border-right:11px solid #FFFFFF;">
            <a href="{{route("user.withdraw")}}">
                <div><img src="{{asset("mobile/film/images/tab3_1_004_1.png")}}" style="width:40px;"></div>
                <p class="ng-binding"></p><h5>提现</h5>
                <p></p>
            </a>

        </div>
    </div>




    <div class="hui-list" style="background:#FFFFFF; margin-top:-28px;">

        <ul>
            <li>
                <a href="{{route("user.my")}}">
                    <div class="hui-list-icons">
                        <img src="{{asset("mobile/film/images/tab_four_select.png")}}">
                    </div>
                    <div class="hui-list-text">
                        账户概览
                        <div class="hui-list-info">
                            @if($Member->card!='')
                            <span class="setting_status"> 已实名认证</span>
                            @endif
                            <span class="hui-icons hui-icons-right"></span>
                        </div>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{route("user.tender")}}">
                    <div class="hui-list-icons">
                        <img src="{{asset("mobile/film/images/touzi_icon.png")}}">
                    </div>
                    <div class="hui-list-text">
                        我的投资
                        <div class="hui-list-info" style="">
                            累积完成投资<font style="color:#f95a5a;font-size: 13px; ">{{$buyjscounts}}</font>笔 <span class="hui-icons hui-icons-right"></span>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{route("user.shouyi",["id"=>1])}}">
                    <div class="hui-list-icons">
                        <img src="{{asset("mobile/film/images/tab_two_select.png")}}">
                    </div>
                    <div class="hui-list-text">
                        付息还本记录
                    </div>
                </a>
            </li>
            <li>
                <a href="{{route("user.shouyi",["id"=>"all"])}}">
                    <div class="hui-list-icons">
                        <img src="{{asset("mobile/film/images/home_novice_time_icon.png")}}">
                    </div>
                    <div class="hui-list-text">
                        资金明细
                        <div class="hui-list-info">
                            已赚取收益<font style="color:#f95a5a;font-size: 13px; "><?php echo sprintf("%.2f",$fenhongs); ?></font>元 <span class="hui-icons hui-icons-right"></span>
                        </div>
                    </div>
                </a>
            </li>
            <div class="clear"></div>
        </ul>


    </div>


    <div class="hui-list" style="margin-top:10px;">

        <ul>
            <li>
                <a href="{{route("user.mylink")}}">
                    <div class="hui-list-icons">
                        <img src="{{asset("mobile/film/images/vip_icon.png")}}">
                    </div>
                    <div class="hui-list-text">
                        邀请好友
                        <div class="hui-list-info">
                            邀请好友越多,福利越多 <span class="hui-icons hui-icons-right"></span>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{route("user.certification")}}">
                    <div class="hui-list-icons">
                        <img src="{{asset("mobile/film/images/mine_collect_icon.png")}}">
                    </div>
                    <div class="hui-list-text">
                        安全认证
                        <div class="hui-list-info">
                            <font style="color:#f95a5a;font-size: 13px; ">{{$Member->realname}}</font>
                            <span class="hui-icons hui-icons-right"></span>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{route("user.edit")}}">
                    <div class="hui-list-icons">
                        <img src="{{asset("mobile/film/images/dxyzma_icon.png")}}">
                    </div>
                    <div class="hui-list-text">
                        资料修改
                        <div class="hui-list-info">
                            @if($Member->ismobile==1)已绑定 @endif
                            <font style="color:#f95a5a;font-size: 13px; "></font> <span class="hui-icons hui-icons-right"></span>
                        </div>
                    </div>
                </a>
            </li>
            <div class="clear"></div>
        </ul>


    </div>



    <div class="hui-list" style="margin-top:10px;">

        <ul>
            <li>
                <a href="{{route("user.bank")}}">
                    <div class="hui-list-icons">
                        <img src="{{asset("mobile/film/images/friends_icon.png")}}">
                    </div>
                    <div class="hui-list-text">
                        我的银行卡
                        <div class="hui-list-info">
                            <span class="hui-icons hui-icons-right"></span>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{route("user.recharge")}}">
                    <div class="hui-list-icons">
                        <img src="{{asset("mobile/film/images/tab_three_select.png")}}">
                    </div>
                    <div class="hui-list-text">
                        我要充值
                        <div class="hui-list-info">
                            <span class="hui-icons hui-icons-right"></span>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{route("user.withdraw")}}">
                    <div class="hui-list-icons">
                        <img src="{{asset("mobile/film/images/detail_icon.png")}}">
                    </div>
                    <div class="hui-list-text">
                        我要提现
                        <div class="hui-list-info">
                            <span class="hui-icons hui-icons-right"></span>
                        </div>
                    </div>
                </a>
            </li>
            <div class="clear"></div>
        </ul>
    </div>



    <div style="padding:10px 20px;margin-bottom:10px;" id="btnList2">
        <a href="{{route("wap.loginout")}}">
            <button type="button" class="hui-button hui-button-large" style="margin-top:25px;">安全退出</button>
        </a>
    </div>



    <script>

        function qiandao() {
            var _token = "{{ csrf_token() }}";
            $.ajax({
                url: '{{route('user.qiandao')}}',
                type: 'post',
                data: {_token:_token},
                dataType: 'json',
                error: function () {
                },
                success: function (data) {
                    layer.open({
                        content: data.msg,
                        time:2000,
                        shadeClose: false,

                    });
                }
            });
        }

    </script>
    <script type="text/javascript">

        $(document).ready(function (e) {

            $(".wenhao").click(function () {
                var msg = '全球分红奖励：全球分红奖励为每天直推3个有效会员可以领取全球分红一次，6个为2次，以此类推（有效会员为：当天注册并且当天充值投资的用户，投资的金额不限，但一定是要成功投资购买项目了的会员）';
                layer.alert(msg, {
                    skin: 'layui-layer-molv'
                    ,closeBtn: 0
                });
            })
        });
    </script>

    <!-----old---->




   {{--
    <div class="navdownb">
        <div class="clear" style="height:10px;"></div>
        <div class="btn btn_orange" style="background:#FFF">
            <a href="{{route("user.msglist")}}"> 收件箱(<font id="top_msg"></font>)<font id="top_playSound"></font></a>
        </div>
        <div class="btn btn_hui">
            <a href="{{route("articles")}}">平台公告</a>
        </div>
    </div>
    --}}




    @if(isset($Member))
        <script type="text/javascript">
            //播放提示音
            function playSound(name,str){
                $("#"+name+"").html('<embed width="0" height="0"  src="/mobile/wap/public/Front/sound/'+str+'" autostart="true" loop="false">');

                if(document.getElementById("'"+name+"'")){
                    document.getElementById("'"+name+"'").Play();
                }
            }

            function total() {
                $.get("{{route('user.msg')}}",function(data){

                    //top_msg = parseInt($("#top_msg").text()); //统计未读短信

                    //赋值到模板
                    $("#top_msg").html(data.msgs); //统计未读短信

                    @if(Cache::get('UserMsgSound')=='开启')
                    //未读站内短信提示
                    if (data.playSound > 0 && data.msgs > 0) {
                        playSound('top_playSound','msg.mp3');
                    }else if (data.layims > 0) {
                        playSound('top_playSound','default.mp3');
                    }

                    @endif
                },'json');
            }
            total();
            setInterval("total()",15000);


        </script>
    @endif

@endsection

@section('footcategoryactive')
    <script type="text/javascript">
        $("#menu2").addClass("active");
    </script>
@endsection
@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
    <br/>
    <br/>

    <font id="top_playSound"></font>
@endsection

