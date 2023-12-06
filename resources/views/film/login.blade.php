@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <a href="javascript:history.go(-1);">
            <img src="{{asset("mobile/film/images/back.png")}}" class="left backImg">
        </a>
        <span class="headerTitle">用户登录</span>
    </header>
@endsection

@section("js")
    @parent

@endsection

@section("css")
    @parent

@endsection



@section('body')
    <div class="register">
        <form id="loginForm">
        <div class="formItemBorder userLogin" style="background:#FFF">
            <img src="{{asset("mobile/film/images/userName.png")}}" class="userImg"/>
            <input type="text" id="username" name="username" autocomplete="off" value="" placeholder="请输入用户名" class="username inputText left">
        </div>
        <div class="formItemBorder userLogin" style="background:#FFF">
            <img src="{{asset("mobile/film/images/userPwd.png")}}" class="userImg"/>
            <input type="password" id="password" name="password" autocomplete="off" value="" placeholder="请输入密码"
                   class="username inputText left">
        </div>

        <div class="formItemBorder">
            <button type="button" id="login" class="finishReg">立即登录</button>
        </div>
        </form>

        <div class="formItemBorder">
            <a href="{{route("wap.forgot")}}" class="finishReg borderBtn"
               style="background:#ff0c00; border:1px #f8b62c solid; color:#FFF">忘记密码</a>
        </div>


        <div class="formItemBorder">
            <a href="{{route("wap.register")}}" class="finishReg borderBtn"
               style="background:#f8b62c; border:1px #f8b62c solid; color:#FFF">立即注册即送现金礼包</a>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(
            function() {
                $("body").keydown(function(e) {
                    var curKey = e.which;
                    if (curKey == 13) {
                        $("#login").click();
                        return false;
                    }
                });
                $("#login").click(
                    function(e) {
                        if ($("#username").val() == "" || $("#username").val() == "账号名称") {

                            layer.msg("请输入用户名");
                            return false;
                        }
                        if ($("#password").val() == "" || $("#password").val() == "输入密码") {
                            layer.msg("请输入密码");
                            return false;
                        }

                        var josnObj = $("#loginForm").serialize();
                        var isCanLogin = false;
                        var returnVal = $.ajax({
                            url: "{{route("wap.login")}}",

                            data:{
                                _token:"{{ csrf_token() }}",
                                username:$("#username").val(),
                                password:$("#password").val()
                            },
                            type: "POST",
                            async: false,
                            cache: 'false',
                            success: function(data) {

                                if (data.status) {
                                    layer.msg(data.msg);
                                }else {

                                    layer.open({
                                        title:'登录',
                                        content: data.msg,
                                        btn: '确定',
                                        shadeClose: false,
                                        yes: function (index) {
                                            if (data.status==0) {
                                                if (data.url != '') {
                                                    window.location.href = data.url;
                                                } else {
                                                    window.location.href = '{{route('user.index')}}';
                                                }

                                            }

                                            layer.close(index);
                                        }
                                    });
                                }

                            },
                            error: function(msg) {
                                $("#errs").html('网络异常，请重新提交');
                                $("#errs").show();
                            }
                        },'josn');
                        return false;
                    })
            });

        function fullScreen(url) {
            location.href = url;
        }


    </script>

<div style="text-align:center">
    <pre id="line1">
        <span>
Copyright<span class="entity"><span>©</span></span>2020
<img src="https://www.432115.com/zixing2019/0591115001569292670.png"/><img src="https://www.432115.com/zixing2019/0976668001569292576.png"/><img src="https://www.432115.com/zixing2019/0113145001569292117.jpg"/>
	</span>
    </pre>
</div>

@endsection


@section("footcategory")
@parent
@endsection

@section("footer")
    @parent
@endsection
@section("playSound")

@endsection

