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

                    <li ><a href="{{route("user.edit")}}">资料修改</a></li>
                    <li ><a href="{{route("user.password")}}">修改登录密码</a></li>
                    <li ><a href="{{route("user.paypwd")}}">修改交易密码</a></li>
                    <li class="current"><a href="{{route("user.paypwd.retrieve")}}">找回交易密码</a></li>
                    <li ><a href="{{route("user.bank")}}">银行卡绑定</a></li>


                </ul>
                <div class="hite"> <span id="account"></span> </div>
            </div>
        </div>


        <div class="myinfo" style="padding:5px  10px; margin-bottom: 15px;background:#fff;">
            <p style="margin:5px;">尊敬的{{Cache::get('CompanyShort')}}用户。</p>

            <p style="margin-:15px;">您可以通过经常性修改交易密码更好的保护您的账号安全，以避免您受到意外损失</p>
            <p style="margin-:15px;">1、初始的交易密码与您的登录密码相同，为了您的资金安全，请修改您的交易密码</p>
            <p style="margin-:15px;">2、经常性修改交易密码能有效的保护您的帐号安全</p>
            <p style="margin-:15px;">3、涉及到您的资金安全，请勿设置简单的交易密码，不要设置和登录密码相同的交易密码</p>

            <form action="/mobile/action/user.php?action=bank" method="post" class="layui-form">
                <table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0" style="margin-top:10px;" height="90">
                    <tbody><tr height="40">
                        <td width="197" align="right" style="background:#F9F9F9;border-top:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;">会员账号：</td>
                        <td width="528" style="border-right:#e6e6e6 solid 1px;border-top:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px; padding-left:5px;"><?php echo $Member->username; ?></td>
                    </tr>
                    <tr height="60">
                        <td style="border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;" align="right">手机验证码：</td>
                        <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px; padding-left:5px;">

                            <input type="text" name="telcode" id="telcode" style="width:60px; height:30px; line-height:30px; border-radius: 15px; border:#CCCCCC solid 1px; padding:0px 8px;" maxlength="6">
                            <a style="background:#3579f7;padding:5px; color:#FFFFFF; font-size:14px;" id="hqcode" href="javascript:hqcode()">获取手机验证码</a> <span id="signtelCode"></span></td>
                    </tr>
                    <tr height="60">
                        <td style="background:#F9F9F9;border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;" align="right">新交易密码：</td>
                        <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px; padding-left:5px;"><input type="password" name="newpass" id="newpass" style="width:200px; height:30px; line-height:30px; border-radius: 15px; border:#CCCCCC solid 1px; padding:0px 8px;"> (输入您的新密码)</td>
                    </tr>
                    <tr height="60">
                        <td style="background:#F9F9F9;border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;" align="right">请再输入新交易密码：</td>
                        <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px; padding-left:5px;"><input type="password" name="renewpass" id="renewpass" style="width:200px; height:30px; line-height:30px; border-radius: 15px; border:#CCCCCC solid 1px; padding:0px 8px;"> (再输入一次您的新密码)</td>
                    </tr>





                    <tr height="50">
                        <td style="border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;" align="right"></td>
                        <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px; color:#ff9600; padding-left:3px;">  <input type="button" value="提交更新" class="btnsubupdate" onclick="SubForm()"> <input type="button" value="&nbsp;&nbsp;取消&nbsp;&nbsp;" class="btncancel" onClick="location.href=location.href;" id="btn_cancel"></td>
                    </tr>

                    </tbody></table>

                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            </form>
        </div>


    </div>
    </div>
    </div>

    <script>


        function SubForm(id) {


            $.ajax({
                url: '{{route("user.paypwd.retrieve")}}',
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

