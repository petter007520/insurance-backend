@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <a href="javascript:history.go(-1);">
            <img src="{{asset("mobile/film/images/back.png")}}" class="left backImg">
        </a>
        <span class="headerTitle">找回密码</span>
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
                <img src="{{asset("mobile/film/images/userName.png")}}" class="userImg"/>
                <input type="text" id="mobile" name="mobile" autocomplete="off" value="" placeholder="请输入手机" class="username inputText left">
            </div>

            <div class="formItemBorder userLogin" style="background:#FFF">
                <img src="{{asset("mobile/film/images/userPwd.png")}}" class="userImg"/>
                <input type="text" id="code" name="code" autocomplete="off" value="" placeholder="请输入验证码"
                       class="username inputText left" style="width: 60%">

                <img src="{!! captcha_src('flat') !!}"
                     alt="验证码"  style="cursor:pointer;height: 40px;margin: 2px;"
                     onclick="this.src='{!! captcha_src('flat') !!}'+Math.random()" id="codeImg" />
            </div>

            <div class="formItemBorder">
                <button type="button" id="login" class="finishReg" style="background:#ff0c00; border:1px #f8b62c solid; color:#FFF">找回密码</button>
            </div>
        </form>



    </div>




    <script type="text/javascript">
        $('.finishReg').click(function(){
            var username = $("#username").val();
            var mobile   = $("#mobile").val();
            var code     = $("#code").val();
            if(!username){
                layer.msg("请填写用户名");
                return ;
            }
            if(!mobile){
                layer.msg("请填写手机号码");
                return ;
            }
            if(!code){
                layer.msg("请填写验证码");
                return ;
            }

            $.ajax({
                type : "POST",
                url : "{{route("wap.forgot")}}",
                dataType : "json",
                data : 'username=' + username + '&mobile=' + mobile  + '&captcha=' + code+'&_token={{ csrf_token() }}',
                success : function (data) {
                    if(data.status == "0"){

                        layer.msg(data.msg,function(){

                                if(data.url){
                                    window.location.href=data.url;
                                }else{
                                    window.location.href='{{route('user.index')}}';
                                }



                        });


                    }else{
                        retCode();
                        layer.msg(data.msg);
                    }
                }
            });

        });
        function retCode() {

            $("#codeImg").attr("src", '{!! captcha_src('flat') !!}'+Math.random());
        }
    </script>

@endsection


@section("footcategory")
    @parent
@endsection

@section("footer")
    @parent
@endsection
@section("playSound")

@endsection

