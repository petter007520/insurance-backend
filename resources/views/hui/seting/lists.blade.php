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
        <xblock>
            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                添加</button>
        </xblock>
            <table class="layui-table x-admin layui-form">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>键名</th>
                <th>值</th>
                <th>值列</th>
                <th>排序</th>
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
                <% item.id %>
            </td>
            <td class="title_<% item.id %>"><% item.name %></td>
            <td><% item.keyname %></td>
            <td><% item.value %></td>
            <td><% item.valuelist %></td>
            <td><% item.sort %></td>
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
    </script>
@endsection
