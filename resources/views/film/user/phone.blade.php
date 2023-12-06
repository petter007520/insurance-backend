@extends('wap.wap')

@section("header")

    <div class="top" id="top" >
        <div class="kf">
            <p><a class="sb-back" href="javascript:history.back(-1)" title="返回"
                  style=" display: block; width: 40px;    height: 40px;
                          margin: auto; background: url('{{asset("mobile/images/arrow_left.png")}}') no-repeat 15px center;float: left;
                          background-size: auto 16px;font-weight:bold;">
                </a>
            </p>
            <div style="display: block;width:100%; position: absolute;top: 0;
     left: 0;text-align: center;  height: 40px; line-height: 40px; ">
                <a href="javascript:;" style="text-align: center;  font-size: 16px; ">{{Cache::get('CompanyLong')}}</a>
            </div>

        </div>
    </div>

    <link rel="stylesheet" href="{{asset("mobile/public/Front/css/common.css")}}" />

    <link rel="stylesheet" type="text/css" href="{{asset("mobile/public/style/css/style.css")}}"/>
    <link href="{{asset("mobile/public/Front/user/user.css")}}" type="text/css" rel="stylesheet">
    <script type="text/javascript" charset="utf-8" src="{{asset("mobile/public/Front/user/user.js").'?t='.time()}}"></script>

@section("js")
    @parent

     <script type="text/javascript" src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset("admin/lib/layui/css/layui.css")}}"/>
@endsection

@section("css")

    @parent


@endsection

@section("onlinemsg")

@endsection

@section('body')
    <div class="main_top_1">
        <div class="mt" style="position:relative;">
    <div class="user_zx_right" >
        <div class="box" >
            <div class="tagMenu">
                <ul class="menu">


                    <li ><a href="{{route("user.certification")}}">认证中心</a></li>
                    <li class="current"><a href="{{route("user.phone")}}">手机验证</a></li>
                    <li><a href="{{route("user.security")}}">安全问题</a></li>

                </ul>
                <div class="hite"> <span id="account"></span> </div>
            </div>
        </div>


        <div class="myinfo" style="padding: 20px; margin-bottom: 15px;background:#fff;">

            <p style="margin:15px;">您可以通过经常性修改密码更好的保护您的账号安全，以避免您受到意外损失</p>
            <p style="color:#999999;margin:15px;">尊敬的{{Cache::get('CompanyShort')}}会员，在绑定手机之后，能够提高您的账号安全性，获取相关短信通知。</p>

            <table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0" style="margin-top:10px;margin:15px 1px;" height="90">
                <tbody><tr>
                    <td width="221"><img src="{{asset("mobile/public/Front/user/phone.jpg")}}"></td>
                    <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px;border-top:#e6e6e6 solid 1px; color:#ff9600; padding-left:10px;font-size: 12px;">
                        <?php  if($Member->ismobile==1){
                            $mobile = \App\Member::half_replace(\App\Member::DecryptPassWord($Member->mobile));
                            echo "温馨提示：您已经绑定手机号码(".$mobile."),如需更换手机号码请直接提交更新。"; }else{	echo '温馨提示：你尚未绑定手机号码，填写以下信息完成绑定。';} ?></td>
                </tr>
                </tbody></table>
            <table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0" style="margin-top:10px;margin:1px;" height="90">
                <form action="" method="post">
                    <tbody><tr height="30">
                        <td width="197" colspan="2" align="center" style="background:#F9F9F9;border-top:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;">更换手机号码</td>
                    </tr>
                    <tr height="60">
                        <td width="197" align="right" style="border-top:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;">新手机号码：</td>
                        <td width="528" style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px;border-top:#e6e6e6 solid 1px; padding-left:5px;">
                            <input type="text" name="mobile" id="mobile" style="width:200px; height:40px; line-height:40px; border-radius: 15px; border:#CCCCCC solid 1px; padding:0px 8px;" maxlength="11">
                            <input type="hidden"  value="<?php echo \App\Member::DecryptPassWord($Member->mobile);?>" name="signtel" id="signtel" style="width:200px; height:40px; line-height:40px; border-radius: 15px; border:#CCCCCC solid 1px; padding:0px 8px;" maxlength="11">
                            <input type="hidden"  value="certification" name="sessionAction" id="sessionAction" ></td>
                    </tr>
                    <tr height="60">
                        <td style="border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;" align="right">手机验证码：</td>
                        <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px; padding-left:5px;">
                            <input type="hidden" name="seesionType" id="seesionType" value="findcode">
                            <input type="text" name="telcode" id="telcode" style="width:60px; height:40px; line-height:40px; border-radius: 15px; border:#CCCCCC solid 1px; padding:0px 8px;" maxlength="6"> <a style="background:#458ed0;padding:14px; color:#FFFFFF; font-size:14px;" id="hqcode" href="javascript:fdhqcode()">获取旧手机验证码</a> <span id="signtelCode"></span></td>
                    </tr>
                    <tr height="50">
                        <td style="border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;" align="right"></td>
                        <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px; color:#ff9600; padding-left:3px;">  <input type="button" onclick="SubForm()" value="提交更新" class="btnsubupdate"> </td></tr></tbody>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                </form></table>
        </div>


    </div>
    </div>
    </div>

    <script>


        function SubForm(id) {


            $.ajax({
                url: '{{route("user.phone")}}',
                type: 'post',
                data: $("form").serialize(),
                dataType: 'json',
                error: function () {
                },
                success: function (data) {
                    layer.open({
                        content: data.msg,
                        btn: '确定',
                        shadeClose: false,
                        yes: function(index){
                            if(data.status==0){
                                history.go(-1);
                            }

                            layer.close(index);
                        }
                    });
                }
            });
        }

    </script>


@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

