@extends('hui.layouts.applists')
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
            <input type="text" name="s_key"  placeholder="请输入会员帐号" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">
        </div>
        <div class="layui-form layui-input-inline">
            <select name="s_status"  lay-search lay-filter="s_status">
                <option value="" >通知状态</option>
                <option value="0" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='0')selected="selected" @endif>待通知</option>
                <option value="1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='-')selected="selected" @endif>已通知</option>
            </select>
        </div>
        <div class="layui-input-inline">
            <button class="layui-btn" lay-submit lay-filter="go">查询</button>
        </div>
    </form>
    </div>
    <xblock>
    </xblock>
        <table class="layui-table x-admin layui-form">
            <thead>
            <tr>
                <th class="layui-form text-center" ><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                <th>用户名</th>
                <th>名称</th>
                <th>状态</th>
                <th>发放时间</th>
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
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon" >&#xe605;</i></div>
            </td>
            <td class="title_<% item.id %>"><% item.username %></td>
            <td><% item.travel_name %></td>
            <td>
                <%# if(item.status==0){ %>
                待通知
                <%# }else if(item.status==1){ %>
                <span style="color: blue">已通知</span>
                <%# }%>
            </td>
            <td><% item.created_at %></td>
            <td class="td-manage">
                <%# if(item.status==0){ %>
                <a title="通知"  onclick="sendNotice(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: green;font-size: 18px;">&#xe609;</i>
                </a>
                <%# }%>
                <a title="删除" onclick="del(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="font-size: 18px;">&#xe640;</i>
                </a>
            </td>
        </tr>
        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>

    </script>

    <script>
        function sendNotice(id,page){
            layer.confirm('确定要标记为已通知?', {icon: 3, title:'提示'}, function(index){
                $.post("{{ route($RouteController.".set_notice") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
                },function(data){
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
                });
                layer.close(index);
            });
        }
    </script>
@endsection
