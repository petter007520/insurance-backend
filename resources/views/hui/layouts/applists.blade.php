<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>{{  Cache::get('sitename') }}- @yield('title')</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
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


<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="javascript:void(0)">首页</a>
        {{--<a >{{$title}}</a>--}}
        <a href="{{ route($RouteController.".lists") }}">
          <cite>{{$title}}</cite>
        </a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">&#xe666;</i></a>
</div>

@yield('formbody')
@section("layermsg")
    <script>


        layui.use(['laypage','layer','form'], function(){
            var $ = layui.jquery;
            var layer=layui.layer;
            var form = layui.form;
            var laypage = layui.laypage;


            @if(session("msg"))
            @if(Cache::has("msgshowtime"))
            layer.msg("{{ session("msgshowtime") }}", {time: "{{Cache::get("msgshowtime")}}"}, function () {
                parent.layer.closeAll();
            });
            @else
            layer.msg("{{ session("msg") }}", {time: '1000'}, function () {
                parent.layer.closeAll();
            });
            @endif
            @endif

            @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
            layer.alert("{{ $error }}");
            @endforeach
            @endif




            lists();


            $(".layerdel").click(function(){
                var id=$(this).attr("data-id");
                del(id);

            });

            $(".layerupdate").click(function(){

                var id=$(this).attr("data-id");
                update(id);
            });


            $(".layerstore").click(function(){
                store();
            });


        });




        function pageshow(page_count,pagesize,page){
            layui.use('laypage', function() {
                var laypage = layui.laypage;
                var op = {

                    s_categoryid: $("[name='s_categoryid']").val(),
                    s_category_id: $("[name='s_category_id']").val(),
                    s_model: $("[name='s_model']").val(),
                    s_status: $("[name='s_status']").val(),
                    s_mtype: $("[name='s_mtype']").val(),
                    s_type: $("[name='s_type']").val(),
                    s_key: $("[name='s_key']").val(),
                    date_s: $("[name='date_s']").val(),
                    date_e: $("[name='date_e']").val(),
                }


                laypage.render({
                    elem: 'layer_pages'
                    , count: page_count //数据总数，从服务端得到
                    ,curr:page
                    ,limit:pagesize
                    , jump: function (obj, first) {

                        //首次不执行
                        if (!first) {
                            lists(obj.curr, op);
                        }
                    }
                });
            });

        }

        function lists(page=1,op2={}){

            var op1={
                page:page,
                s_storeid:$("[name='s_storeid']").val(),
            @if($_REQUEST)
            @foreach($_REQUEST as $keys=>$vals)
            @if($keys !='page' && $keys !='laravel_session' && $keys !='XSRF-TOKEN')
            {{$keys}}:"{{$vals}}",
                @endif
                    @endforeach
                    @endif
                    _token:"{{ csrf_token() }}",

                s_categoryid: $("[name='s_categoryid']").val(),
                s_category_id: $("[name='s_category_id']").val(),
                s_model: $("[name='s_model']").val(),
                s_status: $("[name='s_status']").val(),
                s_mtype: $("[name='s_mtype']").val(),
                s_type: $("[name='s_type']").val(),
                s_key: $("[name='s_key']").val(),
                date_s: $("[name='date_s']").val(),
                date_e: $("[name='date_e']").val(),


        };



            var obj = Object.assign(op1, op2);

            $.ajax({
                url: "{{ route($RouteController.'.'.$RouteAction) }}",
                type:"post",     //请求类型
                data:obj,  //请求的数据
                dataType:"json",  //数据类型
                beforeSend: function () {
                    // 禁用按钮防止重复提交，发送前响应
                    var index = layer.load();

                },
                success: function(data){
                    //laravel返回的数据是不经过这里的
                    if(data.status==0){
                        var list=data.list;

                        if(data.tree){
                            $("#view").html('');
                            pagelist_tree(list.data,1);
                        }else{
                            pagelist(list);
                        }


                        //pageshow(data.list.last_page,page);
                        pageshow(data.list.total,data.pagesize,page,obj);



                    }else{
                        layer.msg(data.msg,{icon: 5},function(){

                        });
                    }
                },
                complete: function () {//完成响应
                    layer.closeAll();
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


        function pagelist(list){


            layui.use(['laytpl','form'], function(){
                var laytpl = layui.laytpl;
                var form = layui.form;
                laytpl.config({
                    open: '<%',
                    close: '%>'
                });

                var getTpl = demo.innerHTML
                    ,view = document.getElementById('view');
                laytpl(getTpl).render(list, function(html){
                    view.innerHTML = html;
                });



                form.render(); //更新全部

            });



        }







        function store(){
            @if($store==1)

            var index=   layer.open({
                title:'{{$title}}',
                type: 2,
                fixed: false,
                maxmin: true,
                area: ['95%', '95%'],
                btn:['新增','取消'],
                yes:function(index,layero){
                    var ifname="layui-layer-iframe"+index;
                    var Ifame=window.frames[ifname];
                    var FormBtn=eval(Ifame.document.getElementById("layui-btn"));
                    FormBtn.click();
                },
                content: ['{{ route($RouteController.".store")}}','yes'],
                end: function () {
                    lists(1);

                },
                    error: function(msg) {
                        var json=JSON.parse(msg.responseText);
                        var errormsg='';
                        $.each(json,function(i,v){
                            errormsg+=' <br/>'+ v.toString();
                        } );
                        layer.alert(errormsg);

                    }
            });
            @else
            layer.alert('您没有权限访问12'+{{$store}});
            @endif

            // layer.full(index);
        }


        function update(id,page){
                @if($update==1)
            var index=   layer.open({
                title:'{{$title}}',
                type: 2,
                fixed: false,
                maxmin: true,
                area: ['95%', '95%'],
                btn:['更新','取消'],
                yes:function(index,layero){


                    var ifname="layui-layer-iframe"+index;
                    var Ifame=window.frames[ifname];
                    var FormBtn=eval(Ifame.document.getElementById("layui-btn"));
                    FormBtn.click();
                },
                content: ['{{ route($RouteController.".update")}}?id='+id,'yes'],
                    error: function(msg) {
                        var json=JSON.parse(msg.responseText);
                        var errormsg='';
                        $.each(json,function(i,v){

                           errormsg+=' <br/>'+ v.toString();

                        });

                        layer.alert(errormsg);

                    },
                end: function () {
                    if(page>0){
                        lists(page);
                    }else{
                        //location.reload(true);
                    }
                }
            });
            @else
            layer.alert('您没有权限访问');
            @endif

            // layer.full(index);
        }





        function delAll (argument) {


            @if($delete==1)
            var data = tableCheck.getData();

            if(data.length<1){
                layer.alert('请选择要删除数据');
                return false;
            }

            layer.confirm('确认要删除选中 '+data.length+' 条吗？',function(index){
                //捉到所有被选中的，发异步进行删除
               // layer.msg('删除成功', {icon: 1});
               // $(".layui-form-checked").not('.header').parents('tr').remove();



                $.post("{{ route($RouteController.".delete") }}",{
                    _token:"{{ csrf_token() }}",
                    ids:data
                },function(data){


                    @if(Cache::has("msgshowtime"))
                    if(data.status==0){
                        layer.msg(data.msg,{time:"{{Cache::get("msgshowtime")}}" },function(){

                            lists(1);


                        });
                    }else{
                        layer.msg(data.msg,{icon:5,time:"{{Cache::get("msgshowtime")}}"});
                    }
                    @else
                    if(data.status==0){
                        layer.msg(data.msg,{},function(){
                            $(".lists_"+id).remove();
                            if(page>0){
                                lists(page);
                            }
                        });
                    }else{
                        layer.msg(data.msg,{icon:5});
                    }
                    @endif


                });



            });
            @else
            layer.alert('您没有权限访问');
            @endif
        }


        function del(id,page){
            @if($delete==1)
            layer.confirm('确定要删除'+$(".title_"+id).text()+'?', {icon: 3, title:'提示'}, function(index){


                $.post("{{ route($RouteController.".delete") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id
                },function(data){


                    @if(Cache::has("msgshowtime"))
                    if(data.status==0){
                        layer.msg(data.msg,{time:"{{Cache::get("msgshowtime")}}" },function(){
                            $(".lists_"+id).remove();

                            if(page>0){
                                lists(page);
                            }

                        });
                    }else{
                        layer.msg(data.msg,{icon:5,time:"{{Cache::get("msgshowtime")}}"});
                    }
                    @else
                    if(data.status==0){
                        layer.msg(data.msg,{},function(){
                            $(".lists_"+id).remove();
                            if(page>0){
                                lists(page);
                            }
                        });
                    }else{
                        layer.msg(data.msg,{icon:5});
                    }
                    @endif


                });

                layer.close(index);
            });
            @else
            layer.alert('您没有权限访问');

            @endif
        }








    </script>
@show

@section('form')
    <script>


    </script>

@show

</body>

</html>
