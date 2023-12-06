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
                <div class="layui-input-inline">
                    <input type="text" name="s_key"  placeholder="请输入名称" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">
                </div>
                <div class="layui-input-inline">
                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>
                </div>
            </form>
        </div>
        <table class="layui-table x-admin layui-form">
            <colgroup>
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
                <th>编号</th>
                <th>奖品名称</th>
                <th>奖品图片</th>
                <th>中奖人昵称</th>
                <th>中奖人用户名</th>
                <th>中奖时间</th>
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
    {{--编号	分类	项目标题	保理机构	项目规模	投资进度	起投金额	交易收益	项目期限	投资状态	是否首页展示	添加时间	排序	操作--}}
    <script id="demo" type="text/html">
        <%#  layui.each(d.data, function(index, item){ %>
        <tr>
            <td class="<% item.id %>"><% item.id %></td>
            <td class="title_<% item.id %>"><% item.prize_title %></td>
            <td><img src=<% item.image %> width="20"></td>
            <td><% item.user_title %></td>
            <td><% item.user_name %></td>
            <td><% item.time %></td>
            <td class="td-manage">
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
            form.on('switch(switchTest-settop)', function(data){
                var id=data.elem.id;
                var top_status= data.elem.checked?1:0;
                var load;
                $.ajax({
                    url: "{{ route($RouteController.'.settop') }}",
                    type:"post",     //请求类型
                    data:{
                        id:id,top_status:top_status,
                        _token:"{{ csrf_token() }}"
                    },  //请求的数据
                    dataType:"json",  //数据类型
                    beforeSend: function () {
                        // 禁用按钮防止重复提交，发送前响应
                        load = layer.load();
                    },
                    success: function(data){
                        //laravel返回的数据是不经过这里的
                        //layer.closeAll();
                        if(data.status==0){
                            layer.msg(data.msg,{time: "{{Cache::get("msgshowtime")}}"},function(){
                                layer.closeAll();
                            });
                        }else{
                            layer.msg(data.msg,{icon: 5},function(){

                            });
                        }
                    },
                    complete: function () {//完成响应
                        layer.close(load);
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

            });


        });

        /*用户-停用*/
        function member_stop(obj,id,page){
            layer.confirm('确认要停用吗？',function(index){

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
                url: "{{ route($RouteController.".lists") }}",
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