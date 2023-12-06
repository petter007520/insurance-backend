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
            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                添加</button>
        </xblock>
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
                <th width="5%">编号</th>
                <th width="10%">奖品名称</th>
                <th width="10%">中奖人id</th>
                <th width="10%">账号</th>
                <th width="10%">中奖时间</th>
                <th>地址</th>
                <th>姓名</th>
                <th>手机号</th>
                <th width="10%">是否预设</th>
                <th width="5%">操作</th>
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
            <td class="<% item.id %>"><% item.id %></td>
            <td class="title_<% item.id %>"><% item.reward_name %></td>
            <td><% item.user_id %></td>
            <td><% item.username %></td>
            <td><% item.reward_date %></td>
            <td><% item.address ?? '' %></td>
            <td><% item.realname ?? ''  %></td>
            <td><% item.mobile ?? ''  %></td>
            <td><% item.pre == 1 ? "预设" : "" %></td>
            <td class="td-manage">
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
    
@endsection