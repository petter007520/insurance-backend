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
    <div class="layui-row">
    <form class="layui-form layui-col-md12 x-so" action="{{ route($RouteController.".lists") }}" method="get">
        <div class="layui-input-inline">
            <input type="text" name="s_key"  placeholder="请输入会员帐号" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">
        </div>
        <div class="layui-form layui-input-inline">
            <select name="s_status"  lay-search lay-filter="s_status">
                <option value="" >状态</option>
                <option value="0" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='0')selected="selected" @endif>审核中</option>
                <option value="1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='1')selected="selected" @endif>审核通过</option>
                <option value="2" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='2')selected="selected" @endif>汇款中</option>
                <option value="3" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='3')selected="selected" @endif>完成</option>
                <option value="4" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='4')selected="selected" @endif>驳回</option>
            </select>
        </div>
        <div class="layui-input-inline">
            <button class="layui-btn" lay-submit lay-filter="go">查询</button>
        </div>
    </form>
    </div>
    <xblock>
    </xblock>
        <table class="layui-table x-admin layui-form">
            <thead>
            <tr>
                <th class="layui-form text-center" ><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                <th>用户名</th>
                <th>流水订单号</th>
                <th>申请理由</th>
                <th>申请人姓名</th>
                <th>申请人身份证</th>
                <th>年龄</th>
                <th>申请人手机号</th>
                <th>身份证正面</th>
                <th>身份证反面</th>
                <th>申请金额</th>
                <th>医院名称</th>
                <th>医院地址</th>
{{--                <th>医院发票</th>--}}
{{--                <th>医保报销凭证</th>--}}
{{--                <th>医院病例</th>--}}
{{--                <th>医院其他收据</th>--}}
                <th>状态</th>
                <th>时间</th>
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
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon" >&#xe605;</i></div>
            </td>
            <td class="title_<% item.id %>"><% item.username %></td>
            <td><% item.order_sn %></td>
            <td><% item.reason %></td>
            <td><% item.name %></td>
            <td><% item.card_no %></td>
            <td><% item.age %></td>
            <td><% item.mobile %></td>
            <td id="photo-front-<% item.id %>">
                <img style="max-width: 100px;" src="<% item.card_up %>"  width="15" onclick="openFrontPhotos(<% item.id %>)">
            </td>
            <td id="photo-front-<% item.id %>">
                <img style="max-width: 100px;" src="<% item.card_down %>"  width="15" onclick="openFrontPhotos(<% item.id %>)">
            </td>
            <td><% item.amount %></td>
            <td><% item.hospital_name %></td>
            <td><% item.hospital_address %></td>
{{--            <td id="photo-front-<% item.id %>">--}}
{{--                <%#  layui.each(item.hospital_bill_img,function(index,v){ %>--}}
{{--                <img style="max-width: 100px;" src=<% v  %>  width="15" onclick="openFrontPhotos(<% item.id %>)">--}}
{{--                <%# });%>--}}
{{--            </td>--}}
{{--            <td id="photo-front-<% item.id %>">--}}
{{--                <%#  layui.each(item.medical_certificate_img,function(index,v){ %>--}}
{{--                <img style="max-width: 100px;" src=<% v  %>  width="15" onclick="openFrontPhotos(<% item.id %>)">--}}
{{--                <%# });%>--}}
{{--            </td>--}}
{{--            <td id="photo-front-<% item.id %>">--}}
{{--                <%#  layui.each(item.hospital_cases_img,function(index,v){ %>--}}
{{--                <img style="max-width: 100px;" src=<% v  %>  width="15" onclick="openFrontPhotos(<% item.id %>)">--}}
{{--                <%# });%>--}}
{{--            </td>--}}
{{--            <td id="photo-front-<% item.id %>">--}}
{{--                <%#  layui.each(item.hospital_receipt_img,function(index,v){ %>--}}
{{--                <img style="max-width: 100px;" src=<% v  %>  width="15" onclick="openFrontPhotos(<% item.id %>)">--}}
{{--                <%# });%>--}}
{{--            </td>--}}
            <td>
                <%# if(item.status == 0){ %>
                    待审核
                <%# }else if(item.status==1){ %>
                    审核通过
                <%# }else if(item.status==2){ %>
                    汇款中
                <%# }else if(item.status==3){ %>
                    完成
                <%# }else if(item.status==4){ %>
                     驳回
                <%# }%>
            </td>
            <td><% item.created_at %></td>
            <td class="td-manage">
                <%# if(item.status==0){ %>
                <a title="通知"  onclick="sendNotice(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: green;font-size: 18px;">&#xe609;</i>
                </a>
                <%# }%>
                <a title="删除" onclick="del(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="font-size: 18px;">&#xe640;</i>
                </a>
            </td>
        </tr>
        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>

    </script>

    <script>
        function sendNotice(id,page){
            layer.confirm('确定要标记为已通知?', {icon: 3, title:'提示'}, function(index){
                $.post("{{ route($RouteController.".set_notice") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
                },function(data){
                    if(data.status==0){
                        layer.msg(data.msg,{},function(){
                            $(".lists_"+id).remove();
                            if(page>0){
                                lists(page);
                            }
                        });
                    }else{
                        layer.msg(data.msg,{icon:5});
                    }
                });
                layer.close(index);
            });
        }
        function openFrontPhotos(id){
            layer.photos({
                photos: '#photo-front-'+id
                ,shift: 0
                // ,full: true
            });
        }
    </script>
@endsection
