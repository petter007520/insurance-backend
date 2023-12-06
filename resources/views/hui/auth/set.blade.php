<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{  Cache::get('sitename') }} 权限设置</title>
    <link rel="stylesheet" href="{{ asset("admin/css/font.css")}}">
    <link rel="stylesheet" href="{{ asset("admin/css/xadmin.css")}}">
    <script type="text/javascript" src="{{ asset("admin/js/jquery.min.js")}}"></script>
    <script type="text/javascript" src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset("admin/js/xadmin.js")}}"></script>
    <script type="text/javascript" src="{{ asset("admin/js/cookie.js")}}"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->





</head>

<body>


    
    <div class="x-body">
    

        {!! Form::open(['route' => $RouteController.'.set','class'=>'layui-form layui-form-pane1','id'=>'auth_set']) !!}

        {!! Form::hidden("id",$id) !!}
        @if($list)
            @foreach ($list as $d)
                <div class="layui-form-item" pane>

                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">

                        <a class="layui-btn layui-btn-sm layui-btn-danger" >{{ $d->name }}</a>

                        @if($d->menus)
                            @foreach ($d->menus as $dd)
						       <input lay-skin="primary" type="checkbox" name="setid[]" title="{{ $dd->name }}" value="{{ $dd->model_name.'.'.$dd->contr_name.'.'.$dd->action_name }}" @if(!empty($ids)) @if(in_array($dd->model_name.'.'.$dd->contr_name.'.'.$dd->action_name,$ids)) checked="checked" @endif @endif>
                            @endforeach
                            @endif
                    </div>
                </div>
            @endforeach
        @endif


        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="*" id="layui-btn">保存设置</button>
            </div>
        </div>

        {!! Form::close() !!}



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

        layui.use('form', function() {
            var form = layui.form;

            //监听提交
            form.on('submit(*)', function(data){
                  return true;

            });

        });




    </script>





</body>

</html>
