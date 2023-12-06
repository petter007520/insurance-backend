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
                    <li ><a href="{{route("user.phone")}}">手机验证</a></li>
                    <li class="current"><a href="{{route("user.security")}}">安全问题</a></li>

                </ul>
                <div class="hite"> <span id="account"></span> </div>
            </div>
        </div>


        <div class="myinfo" style="padding: 20px; margin-bottom: 15px;background:#fff;font-size: 12px;">
            <p style="margin:15px;">通过设置安全问题，能够为您的账号提供更安全的保障</p>
            <p style="color:#999999;margin:15px;">尊敬的{{Cache::get('CompanyShort')}}会员，在绑定手机并通过验证之后，您可以通过手机重置密码，获取{{Cache::get('CompanyShort')}}最新动态等相关服务。</p>




            <table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0" style="margin-top:10px;" height="90">
                <tbody><tr>
                    <td width="221"><img src="{{asset("mobile/public/Front/user/wenhao.jpg")}}"></td>
                    <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px;border-top:#e6e6e6 solid 1px; color:#ff9600; padding-left:10px;font-size: 12px;">
                        <?php  if($Member->isquestion==1){ echo "温馨提示：您好，你已经设置了安全问题，如需修改填写以下信息完成修改。"; }else{	echo '温馨提示：你尚未设置安全问题，填写以下信息完成验证。';} ?>
                    </td>
                </tr>
                </tbody></table>
            <p style="margin:35px 15px 15px 15px;"><img src="{{asset("mobile/public/Front/user/xtp.png")}}" width="16"> 请在下面的下拉列表中选择问题，并在答案部分予以回答。</p>
            <p style="margin:15px; padding-left:22px; padding-bottom:15px; border-bottom:#009900 solid 2px;">注意：回答安全问题是您修改手机号码，变更邮箱，找回密码和修改银行账号的必备验证程序，请妥善保存您的安全问题以及答案。谢谢。</p>
            <form action="" method="post">
                <table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0" style="margin-top:10px;" height="90">

                <tbody><tr height="40">
                    <td width="197" align="right" style="background:#F9F9F9;border-top:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;">安全问题：</td>
                    <td width="528" style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px;border-top:#e6e6e6 solid 1px; padding-left:5px;">
                        <select name="question">
                            <option value="您母亲的生日是？" @if($Member->question=='您母亲的生日是？') selected="selected" @endif>您母亲的生日是？</option>
                            <option value="您母亲的姓名是？" @if($Member->question=='您母亲的姓名是？') selected="selected" @endif>您母亲的姓名是？</option>
                            <option value="您父亲的生日是？" @if($Member->question=='您父亲的生日是？') selected="selected" @endif>您父亲的生日是？</option>
                            <option value="您父亲的姓名是？" @if($Member->question=='您父亲的姓名是？') selected="selected" @endif>您父亲的姓名是？</option>
                            <option value="您孩子的生日是？" @if($Member->question=='您孩子的生日是？') selected="selected" @endif>您孩子的生日是？</option>
                            <option value="您孩子的姓名是？" @if($Member->question=='您孩子的姓名是？') selected="selected" @endif>您孩子的姓名是？</option>
                            <option value="您配偶的名字是？" @if($Member->question=='您配偶的名字是？') selected="selected" @endif>您配偶的名字是？</option>
                            <option value="您配偶的生日是？" @if($Member->question=='您配偶的生日是？') selected="selected" @endif>您配偶的生日是？</option>
                            <option value="您的出生地是哪里？" @if($Member->question=='您的出生地是哪里？') selected="selected" @endif>您的出生地是哪里？</option>
                            <option value="您最喜欢什么颜色？" @if($Member->question=='您最喜欢什么颜色？') selected="selected" @endif>您最喜欢什么颜色？</option>
                            <option value="您是什么学历？" @if($Member->question=='您是什么学历？') selected="selected" @endif>您是什么学历？</option>
                            <option value="您的属相是什么的？" @if($Member->question=='您的属相是什么的？') selected="selected" @endif>您的属相是什么的？</option>
                            <option value="您小学就读的是哪所学校？" @if($Member->question=='您小学就读的是哪所学校？') selected="selected" @endif>您小学就读的是哪所学校？</option>
                            <option value="您最崇拜谁？" @if($Member->question=='您最崇拜谁？') selected="selected" @endif>您最崇拜谁？</option>
                            <option value="您打字经常用什么输入法？" @if($Member->question=='您打字经常用什么输入法？') selected="selected" @endif>您打字经常用什么输入法？</option>
                            <option value="您是什么时间参加工作的？" @if($Member->question=='您是什么时间参加工作的？') selected="selected" @endif>您是什么时间参加工作的？</option>
                        </select>
                    </td>
                </tr>
                <tr height="60">
                    <td style="background:#F9F9F9;border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;" align="right">问题答案：</td>
                    <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px; padding-left:5px;"><input type="text" name="answer" id="answer" style="width:200px; height:40px; line-height:40px; border-radius: 15px; border:#CCCCCC solid 1px; padding:0px 8px;" value="{{$Member->answer}}"></td>
                </tr>
                <tr height="50">
                    <td style="border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px;border-right:#e6e6e6 solid 1px;" align="right"></td>
                    <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px; color:#ff9600; padding-left:3px;">  <input type="button" onclick="SubForm()" value="提交更新" class="btnsubupdate"> <input type="button" value="&nbsp;&nbsp;取消&nbsp;&nbsp;" class="btncancel" onclick="location.href=location.href;" id="btn_cancel"></td>
                </tr>
                </tbody>
                </table>

                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            </form>
        </div>


    </div>
    </div>
    </div>

    <script>


        function SubForm(id) {


            $.ajax({
                url: '{{route("user.security")}}',
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

