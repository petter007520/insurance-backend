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
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                    <th>奖品名称</th>
                    <th>奖品图片</th>
                    <th>兑换积分</th>
                    <th>库存</th>
                    <th>中奖概率</th>
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
            <td>
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div>
                <span style=""><% item.id %></span>
            </td>
            <td class="title_<% item.id %>"><% item.title %></td>
            <td><img src=<% item.image %> width="80"></td>
            <td><% item.point %></td>
            <td><% item.stock %></td>
            <td><% item.odds %></td>
            <td class="td-manage">
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
    </script>
@endsection

