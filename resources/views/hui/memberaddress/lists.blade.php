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
                <input type="text" name="s_key"  placeholder="请输入用户名" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">
                <div class="layui-input-inline">

                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>

                </div>
            </form>
        </div>

        <table class="layui-table x-admin layui-form">

            <thead>
            <tr>
                <th>
                    <!--<div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>-->
                    ID
                </th>
                <th>会员ID</th>
                <th>用户名</th>
                <th>收货人</th>
                <th>收货号码</th>
                <th>收货地址</th>
                
                
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

                    <!-- <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div> -->
                </td>
                <td><% item.userid %></td>
                <td><% item.username %></td>
                <td><% item.receiver %></td>
                <td><% item.mobile %></td>
                <td><% item.area%>
                <% item.address%>
                </td>
                
                
                



            <%#  }); %>
            <%#  if(d.length === 0){ %>
            无数据
            <%#  } %>

    </script>

    <script>


function showpassword(pwd,paypwd){
    var msg='登录密码:'+pwd+'\r\n'+'支付密码:'+paypwd;
    layer.alert(msg,{title:'密码信息'});
}


    layui.use(['form','laydate'], function(){
        var form = layui.form;
        form.on('select(s_categoryid)', function(data){
            lists(1,{s_categoryid:data.value});
        });

        form.on('select(s_mtype)', function(data){
            lists(1,{s_mtype:data.value});
        });


        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#date_s' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#date_e' //指定元素
        });

    });




    </script>
@endsection

