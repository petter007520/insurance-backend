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
                <col width="110">
                <!--<col width="110">-->
                <!--<col width="110">-->
                <col width="110">
                <col width="80">
                <col width="80">

            </colgroup>

            <thead>

            <tr>
                <th>ID</th>
                <th>团队长等级名称</th>
                   <th>工资</th>
               <th>下级购买金额</th>
                <th>直接推荐人数</th>
              <!--  <th>团队累计消费</th>
                <th>一次性升级奖励</th>-->
                <th>头像</th>
                <!--<th>团队直属等级ID</th>
                <th>人数</th>-->
                <!--  <th>团队累计消费</th>-->
                <!--<th>每日玩大转盘数</th>-->
                <!--<th>发展同级下线数</th>-->
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


  <td><% item.price %></td>

     <td><% item.level_fee %></td>
          <!--  <td><% item.rate %>%</td>-->
            <td><% item.tj_num %></td>
            <td><img src=<% item.headurl %> width="20"></td>
       
        

            <!--<td><% item.inte %></td>
            <td><% item.wheels %></td>
            <td><% item.offlines %></td> -->

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



