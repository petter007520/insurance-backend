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
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>


    </xblock>
        <table class="layui-table x-admin layui-form">

            <thead>
            <tr>
                <th class="layui-form text-center" ><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                <th>用户名</th>
                <th>IP</th>
                <th>登录时间</th>
                <th>状态</th>
                <th>说明</th>
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
            <td class="title_<% item.id %>"><% item.AdminName %></td>
            <td><% item.ip %></td>
            <td><% item.logintime %></td>
            <td><% item.status?'成功':'失败' %></td>
            <td><% item.info %></td>

        </tr>



        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>

    </script>


@endsection

