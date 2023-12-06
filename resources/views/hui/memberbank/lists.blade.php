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
                    ID
                </th>
                <th>会员ID</th>
                <th>用户名</th>
                <th>类型</th>
                <th>银行名称</th>
                <th>开户姓名</th>
                <th>银行卡号</th>
                <th>支行名称</th>
                <th>提现地址</th>
                <th>状态</th>
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
                <td><% item.userid %></td>
                <td><% item.username %></td>
                <td>
                    <%# if(item.type==1){ %>
                    银行卡
                    <%# }else if(item.type==2){ %>
                    支付宝
                    <%# }else if(item.type==3){ %>
                    USDT-Trc20
                    <%# }%>
                </td>
                <td><% item.bankname?item.bankname:'' %></td>
                <td><% item.bankrealname?item.bankrealname:'' %></td>
                <td><% item.bankcode?item.bankcode:'' %></td>
                <td><% item.bankaddress?item.bankaddress:'' %></td>
                <td><% item.address?item.address:'' %></td>
                <td><% item.status==0?'禁用':'启用' %></td>
                <td class="td-manage">
                    <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon" >&#xe642;</i>
                    </a>
                </td>
            </tr>
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

