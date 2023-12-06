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



        



        <form class="layui-form layui-form-pane1" action="{{ route($RouteController.".lists") }}" method="get">



            <div class="layui-form-item" pane>



            <div class="layui-input-inline">

                <input type="text" name="s_key"  placeholder="请输入名称" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">

            </div>



            <div class="layui-input-inline">

                <button class="layui-btn" lay-submit lay-filter="go">查询</button>

            </div>



        </div>



    </form>

        <xblock>

            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                添加</button>

        </xblock>

             <table class="layui-table x-admin layui-form">

            <colgroup>

                <col width="50">

                <col width="150">

                <col width="110">

                <col>

                <col>

                <col>
                <col>

                <col width="100">

            </colgroup>

            <thead>

            <tr>

                <th>ID</th>

                <th>名称</th>

                <th>LOGO</th>

                <th>域名</th>

                <th>模板</th>

                <th>排序</th>
                <th>站长</th>

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

            <td><% item.logo?'<img src="'+item.logo+'" width="100"/>':'' %></td>

            <td><% item.domain %></td>

            <td><% item.template %></td>

            <td><% item.sort %></td>
            <td><% item.AdminName?item.AdminName:'' %></td>




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



       layui.use(['form'], function(){

        var form = layui.form;

            form.on('select(s_siteid)', function(data){
                    var op={
                       s_siteid :data.value
                    }

                    lists(1,op);

                    });

            });



    </script>

@endsection



