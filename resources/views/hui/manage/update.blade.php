<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{  Cache::get('sitename') }} 后台首页</title>

    <link rel="stylesheet" href="{{ asset("admin/css/font.css")}}">
    <link rel="stylesheet" href="{{ asset("admin/css/xadmin.css")}}">
    <script type="text/javascript" src="{{ asset("admin/js/3.2.1/jquery.min.js")}}"></script>
    <script src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset("admin/js/xadmin.js")}}"></script>
    <script type="text/javascript" src="{{ asset("admin/js/cookie.js")}}"></script>

</head>


<body>


<div class="x-body">
    
    <form method="POST" action="{{route($RouteController.'.update')}}" accept-charset="UTF-8" class="layui-form layui-form-pane1">
        {!! csrf_field() !!}


        <div class="layui-form-item">
    <label class="layui-form-label">用户名</label>

    <div class="layui-input-inline">
        <input type="text" name="username" lay-verify="required|username" required placeholder="请输登录用户名" autocomplete="off" class="layui-input" value="{{ $edit->username }}" disabled="disabled">
    </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">姓名</label>
    <div class="layui-input-inline">
        <input type="text" name="name" lay-verify="required|username" autocomplete="off" class="layui-input" placeholder="用户姓名" value="{{ $edit->name }}">
    </div>
</div>


<div class="layui-form-item">
    <label class="layui-form-label">手机</label>
    <div class="layui-input-inline">
        <input type="tel" name="phone" lay-verify="phone" autocomplete="off" class="layui-input" placeholder="手机号码" value="{{ $edit->phone }}">
    </div>
</div>


<div class="layui-form-item">
    <label class="layui-form-label">旧密码</label>
    <div class="layui-input-inline">
        <input type="password" name="oldpassword" lay-verify="oldpassword" placeholder="请输入旧密码" autocomplete="off" class="layui-input" value="">
    </div>
    <div class="layui-form-mid layui-word-aux">长度（6-12位）</div>
</div>

        <div class="layui-form-item">
    <label class="layui-form-label">密码</label>
    <div class="layui-input-inline">
        <input type="password" name="password" lay-verify="password" placeholder="请输入密码" autocomplete="off" class="layui-input" value="">
    </div>
    <div class="layui-form-mid layui-word-aux">长度（6-12位）</div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">确认密码</label>
    <div class="layui-input-inline">
        <input type="password" name="password2" lay-verify="password2" placeholder="请确认密码" autocomplete="off" class="layui-input" value="">
    </div>
    <div class="layui-form-mid layui-word-aux">长度（6-12位）</div>
</div>

<div class="layui-form-item">
    <div class="layui-inline">
        <label class="layui-form-label">用户分组</label>
        <div class="layui-input-block">
            <select name="authid" disabled="disabled">
                @if($authlist)
                    @foreach($authlist as $auth)
                        <option value="{{$auth->id}}" @if($auth->disabled) disabled @endif @if($edit->authid == $auth->id) selected="selected" @endif>{{ $auth->name }}</option>
                    @endforeach
                @endif

            </select>
        </div>
    </div>

</div>




<div class="layui-form-item layui-form-text">
    <label class="layui-form-label">请填写描述</label>
    <div class="layui-input-block">
        <textarea placeholder="请输入内容" class="layui-textarea" name="remarks">{{ $edit->remarks }}</textarea>
    </div>
</div>
<div class="layui-form-item">
    <div class="layui-input-block">
        <button class="layui-btn" lay-submit lay-filter="go">立即提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
</div>
        </form>
</div>
</div>
<script>

    layui.use(['laypage','layer','form'], function() {
        var $ = layui.jquery;
        var laypage = layui.laypage;
        var layer = layui.layer;
        var form = layui.form;


        @if(session("msg"))
            @if(Cache::has("msgshowtime"))
                 layer.msg("{{ session("msg") }}", {time: '{{Cache::get('msgshowtime')}}'}, function () {
            @if(!session("status"))
                parent.layer.closeAll();
            @endif
        });
        @else
            layer.msg("{{ session("msg") }}", {time: '500'}, function () {
            @if(!session("status"))
                parent.layer.closeAll();
            @endif
        });
                @endif
                @endif


                @if (count($errors) > 0)
        var alert_msg = '';
        @foreach ($errors->all() as $error)
                alert_msg += "{{ $error }} <br/> ";
        @endforeach
           layer.alert(alert_msg);
        @endif
    });

</script>

<script>

    layui.use('form', function(){
        var form = layui.form();

        //各种基于事件的操作，下面会有进一步介绍

        //自定义验证规则
        form.verify({

            password: function(value){
                if(value != '' && value.length<6){
                    return '密码不能小于6位';
                }
            },
            oldpassword: function(value){
                if(value != '' && value.length<6){
                    return '密码不能小于6位';
                }
            }
            ,password2: function(value){
                if(value != $("input[name='password']").val() && $("input[name='password']").val()!=''){
                    return '两次输入的密码不一致';
                }
            }
            ,phone: function(value){
                if(value != '' && !/^1[3|4|5|7|8]\d{9}$/.test(value)){
                    return '手机必须11位，只能是数字！';
                }
            }

            ,email: function(value){
                if(value !='' && !/^[a-z0-9._%-]+@([a-z0-9-]+\.)+[a-z]{2,4}$|^1[3|4|5|7|8]\d{9}$/.test(value)){
                    return '邮箱格式不对';
                }
            }

        });



    });


</script>

</body>

</html>
