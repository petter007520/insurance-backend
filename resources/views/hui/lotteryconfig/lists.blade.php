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

                <col width="50">
                <col width="100">
                <col width="100">
                <col width="100">
                <col width="300">
                <col width="110">
                <col width="110">

            </colgroup>

            <thead>

            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>奖金</th>
                <th>图片</th>
                <th>说明</th>
                <th>中奖率</th>
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
    {{--//'pay_code','pay_name','pay_bank','pay_pic','pay_desc','enabled'--}}
    <script id="demo" type="text/html">


        <%#  layui.each(d.data, function(index, item){ %>

        <tr>
            <td>
                <% item.id %>
            </td>



            <td><% item.name %></td>

            <td><% item.moneys %></td>
            <td><% item.img?'<img src="'+item.img+'" width="100"/>':'' %></td>
            <td><% item.prize %></td>
            <td><% item.winningrate %></td>

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

        });



    </script>

@endsection



