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
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>ID</th>
                <th>支付标识</th>
                <th>支付名称</th>
                <th>支付类型</th>
                <th>银行名称</th>
                <th>持卡人姓名</th>
                <th>支行</th>
                <th>银行卡号</th>
                <th>图标</th>
                <th>渠道类型</th>
                <th>说明</th>
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
            <td><% item.id %></td>
            <td><% item.pay_code %></td>
            <td><% item.pay_name %></td>
            <td>
                <%# if(item.type== 'online'){ %>
                在线支付
                <%# }else if(item.type== 'offline'){ %>
                银联支付
                <%# }else if(item.type== 'wallet'){ %>
                余额支付
                <%# }else if(item.type== 'chain'){ %>
                USDT支付
                <%# }%>
            </td>
            <td><% item.bankname?item.bankname:'' %></td>
            <td><% item.bankrealname?item.bankrealname:'' %></td>
            <td><% item.bank_type?item.bank_type:'' %></td>
            <td><% item.bankcode?item.bankcode:'' %></td>
            <td><% item.pay_pic?'<img src="'+item.pay_pic+'" />':'' %></td>
            <td>
                <%# if(item.pay_type== 1){ %>
                余额
                <%# }else if(item.pay_type== 2){ %>
                银行卡
                <%# }else if(item.pay_type== 3){ %>
                支付宝
                <%# }else if(item.pay_type== 4){ %>
                微信
                <%# }else if(item.pay_type== 5){ %>
                USDT
                <%# }%>
            </td>
            <td><% item.pay_desc %></td>
            <td><% item.enabled?'启用':'禁用' %></td>
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
