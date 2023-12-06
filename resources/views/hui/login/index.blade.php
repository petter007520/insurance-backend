<!doctype html>
<html  class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>后台登录-{{  Cache::get('sitename') }}</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{ asset("admin/css/font.css")}}">
    <link rel="stylesheet" href="{{ asset("admin/css/xadmin.css")}}">
    <script type="text/javascript" src="{{ asset("admin/js/jquery.min.js")}}"></script>
    <script src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset("admin/js/xadmin.js")}}"></script>
    <script type="text/javascript" src="{{ asset("admin/js/cookie.js")}}"></script>

</head>
<body class="login-bg">

<div class="login layui-anim layui-anim-up">
    <div class="message">{{  Cache::get('sitename') }}-管理登录</div>
    <div id="darkbannerwrap"></div>

    <form  class="m-t layui-form" role="form" action="" method="post">
        {{ csrf_field() }}
        <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
        @if(Cache::get("LoginCode")=='on')
        <hr class="hr15">
        <input name="captcha" lay-verify="required" placeholder="验证码"  type="text" class="layui-input" >
        <hr class="hr15">
        <img src="{!! captcha_src('inverse') !!}"
             alt="验证码"  style="cursor:pointer;"
             onclick="this.src='{!! captcha_src('inverse') !!}'+Math.random()" />
        @endif

        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>
</div>

<script>
    $(function  () {
        layui.use('form', function(){
            var form = layui.form;
            //监听提交
            form.on('submit(login)', function(data){

                $.ajax({

                    url: "{{ route('login') }}",

                    type:"post",     //请求类型

                    data:{

                        username:$("input[name='username']").val(),

                        password:$("input[name='password']").val(),

                        captcha:$("input[name='captcha']").val(),

                        _token:"{{ csrf_token() }}",

                    },  //请求的数据

                    dataType:"json",  //数据类型

                    success: function(data){

//laravel返回的数据是不经过这里的

                        if(data.status==0){

                            layer.msg(data.msg,{icon: 6},function(){

                                location.href="{{ route('admin.index.index') }}";

                            });

                        }else{

                            layer.msg(data.msg,{icon: 5},function(){

                            });

                        }

                    },

                    error: function(msg) {

                        var json=JSON.parse(msg.responseText);

                        var errormsg='';

                        $.each(json,function(i,v){

                            errormsg+=' <br/>'+ v.toString();

                        } );

                        layer.alert(errormsg);


                    },


                });




                return false;


        });


    });

    });


</script>

</body>
</html>
