@extends(env('Template').'.layouts.applists')

@section('title', $title)
@section('here')

@endsection
@section('addcss')
    @parent

@endsection
@section('addjs')
    @parent

@endsection

@section('formbody')


    <div class="x-body">

        <div class="layui-row">
            <form class="layui-form layui-col-md12 x-so" action="{{ route($RouteController.".lists") }}" method="get">
                <input class="layui-input"  autocomplete="off" placeholder="开始日" name="start" id="start">
                <input class="layui-input"  autocomplete="off" placeholder="截止日" name="end" id="end">
                <input type="text" name="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">
                <button class="layui-btn"  lay-submit="" lay-filter="go"><i class="layui-icon">&#xe615;</i></button>

                <div class="layui-form-item layui-form" pane>
                    <label class="col-sm-1 layui-form-label">角色</label>
                    <div class="col-sm-11 s_source">
                        <input type="radio" name="authid" value="" lay-filter="authid" title="全部"  checked="checked">
                        @if($ahths)
                            @foreach($ahths as $ahth)
                                <input type="radio" name="authid" lay-filter="authid" value="{{$ahth->id}}" title="{{$ahth->name}}">
                            @endforeach
                        @endif
                    </div>
                </div>


            </form>
        </div>
        <xblock>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>
            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                添加</button>

        </xblock>


            <table class="layui-table x-admin layui-form">
                <colgroup>
                    <col width="150">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                    <th>用户名</th>
                    <th>状态</th>
                    <th>手机号</th>
                    <th>所属上级</th>
                    <th>角色</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="view">

                </tbody>
            </table>



        <div id="layer_pages"></div>




    </div>
@endsection
@section("layermsg")
    @parent
@endsection

@section('form')

    <script id="demo" type="text/html">


            <%#  layui.each(d.data, function(index, item){ %>

            <tr>
                <td>


                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div>
                </td>
                <td class="title_<% item.id %>"><% item.username %></td>
                <td><% item.disabled==0?'启用':'禁用' %></td>
                <td><% item.phone?item.phone:'' %></td>
                <td><% item.padminname %></td>
                <td><% item.authname %></td>

                <td class="td-manage">
                    <a onclick="member_stop(this,<% item.id %>,<% d.current_page %>)" href="javascript:;"  title="<% item.disabled==1?'启用':'禁用' %>">

                            <%# if(item.disabled==0){ %>
                        <i class="layui-icon" style="color:green;">&#xe601;</i>
                            <%# }else{%>
                        <i class="layui-icon" style="color:red;">&#xe62f;</i>
                            <%# } %>


                    </a>
                    <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon">&#xe642;</i>
                    </a>



                    <a title="删除" onclick="del(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon">&#xe640;</i>
                    </a>
                </td>
            </tr>



            <%#  }); %>
            <%#  if(d.length === 0){ %>
            无数据
            <%#  } %>

    </script>

    <script>





    layui.use('form', function(){
        var form = layui.form;
        form.on('radio(authid)', function(data){
            lists(1,{authid:data.value});
        });

    });

    /*用户-停用*/
    function member_stop(obj,id,page){
        layer.confirm('确认要'+$(obj).attr('title')+'吗？',function(index){

            if($(obj).attr('title')=='启用'){

                //发异步把用户状态进行更改
                $(obj).attr('title','停用')
                $(obj).find('i').html('&#xe62f;');

                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!',{icon: 5,time:1000});

            }else{
                $(obj).attr('title','启用')
                $(obj).find('i').html('&#xe601;');

                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!',{icon: 5,time:1000});
            }

            switchings(id,obj,page);

        });
    }


    function switchings(id,obj,page){

        var index;
        $.ajax({
            url: "{{ route($RouteController.".switch") }}",
            type:"post",     //请求类型
            data:{
                id:id,
                _token:"{{ csrf_token() }}"
            },  //请求的数据
            dataType:"json",  //数据类型
            beforeSend: function () {
                // 禁用按钮防止重复提交，发送前响应
                 index = layer.load();

            },
            success: function(data){
                //laravel返回的数据是不经过这里的
                if(data.status==0){
                    layer.close(index);
                    lists(page);
                }
            },
            complete: function () {//完成响应

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
@endsection

