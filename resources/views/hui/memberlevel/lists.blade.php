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
{{--               <col width="50">--}}
            </colgroup>
            <thead>
            <tr>
                <th>ID</th>
                <th>等级</th>
                <th>永久分红比例(小区)</th>
                <th>业绩要求(小区)</th>
                <th>所需伞下星级数量</th>
                <th>所需伞下星级等级</th>
                <th>福利</th>
                <th>图标</th>
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
            <td><% item.id %></td>
            <td><% item.name %></td>
            <td><% item.rate %>%</td>
            <td><% item.need_amount %></td>
             <td><% item.need_star_num %></td>
             <td><% item.need_star_level %></td>
             <td><% item.welfare %></td>
            <td><img src=<% item.headurl %> width="20"></td>
            <td class="td-manage">
                <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe642;</i>
                </a>
            </td>
        </tr>
        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>
    </script>
    <script>
       layui.use(['form'], function(){
        var form = layui.form;

        });
    </script>
@endsection
