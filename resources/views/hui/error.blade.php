<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>{{$msg}}</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{ asset("admin/css/font.css")}}">
    <link rel="stylesheet" href="{{ asset("admin/css/xadmin.css")}}">
    <script type="text/javascript" src="{{ asset("admin/js/3.2.1/jquery.min.js")}}"></script>
    <script type="text/javascript" src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
</head>
<body>
<div class="layui-container">
    <div class="fly-panel">
        <div class="fly-none">
            <h2><i class="layui-icon {{$icon}}" ></i></h2>
            <p style="color:red;">{{$msg}}</p>
        </div>
    </div>
</div>
<script>


    layui.use(['laypage','layer'], function() {

        var layer = layui.layer;

        layer.alert('{{$msg}}',{title:'页面提示'},function(index){

            layer.close(index);
            @if(isset($url))
                var url='{{$url}}';
            if(url!=''){
                window.location.href=url;
            }
            @endif
        });
    });
</script>
</body>
</html>
