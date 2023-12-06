<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>{{  Cache::get('sitename') }}- @yield('title')</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="stylesheet" href="{{ asset("admin/css/font.css")}}">
    <link rel="stylesheet" href="{{ asset("admin/css/xadmin.css")}}">
    <script type="text/javascript" src="{{ asset("admin/js/3.2.1/jquery.min.js")}}"></script>
    <script type="text/javascript" src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset("admin/js/xadmin.js")}}"></script>
    <script type="text/javascript" src="{{ asset("admin/js/cookie.js")}}"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @section('addcss')

    @show

    @section('addjs')

    @show

</head>




<body>


@section("mainbody")
    <div class="x-body">
        {!! Form::open(['route' =>$RouteController.'.update','class'=>'layui-form','id'=>'form_update']) !!}
        {!! Form::hidden("id",$edit->id,[]) !!}
        <div class="layui-form-item">

        </div>
        @yield('formbody')

        <div class="layui-form-item" style="display: none;">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="go" id="layui-btn">保存</button>
                <a type="reset" class="layui-btn layui-btn-primary" onclick=" parent.layer.closeAll();">取消</a>
            </div>
        </div>

        {!! Form::close() !!}

</div>


@show
@section("layermsg")



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

                @if(Cache::has("closelayer") && Cache::get('closelayer')=='开启')
                parent.layer.closeAll();
                @endif
                @endif
            });
            @else
            layer.msg("{{ session("msg") }}", {time: '500'}, function () {
                @if(!session("status"))
                @if(Cache::has("closelayer") && Cache::get('closelayer')=='开启')
                parent.layer.closeAll();
                @endif
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


            //监听提交
            form.on('submit(go)', function(data){
                params = data.field;
                submit($,params);
                return false;


            });

        });

        function submit($,params){

            $.ajax({
                url: "{{ route($RouteController.'.update') }}",
                type:"post",     //请求类型
                data:params,  //请求的数据
                dataType:"json",  //数据类型
                beforeSend: function () {
                    // 禁用按钮防止重复提交，发送前响应
                    index = layer.load();

                },
                success: function(data){
                    //laravel返回的数据是不经过这里的
                    if(data.status==0){
                        layer.msg(data.msg,{time:"{{Cache::get("msgshowtime")}}" },function(){
                            @if(Cache::has("closelayer") && Cache::get('closelayer')=='开启')
                            parent.layer.closeAll();
                            @endif
                        });
                    }else{
                        layer.msg(data.msg,{icon: 5},function(){

                        });
                    }
                },
                complete: function () {//完成响应
                    // layer.closeAll();
                    layer.close(index);
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

        }

    </script>
@show

@section('form')
    <script>


    </script>

@show

</body>

</html>



