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

                    <input type="text" name="s_key"  placeholder="请输入会员帐号" autocomplete="off" class="layui-input s_key" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">

                </div>

				<div class="layui-input-inline">

                    <input type="text" name="top_uid"  placeholder="上级会员帐号" autocomplete="off" class="layui-input top_uid" value="@if(isset($_REQUEST['top_uid'])){{$_REQUEST['top_uid']}}@endif">

                </div>

                <div class="layui-input-inline">

                    <select name="s_categoryid" lay-filter="category_id">

                            <option value="">项目分类栏目</option>
                        {!! $tree_option !!}

                    </select>

                </div>

                <div class="layui-form layui-input-inline">
                    <select name="s_status" lay-search lay-filter="s_status" lay-search>
                        <option value="" >投资状态</option>
                        <option value="3" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='3')selected="selected" @endif>已结束</option>
                        <option value="2" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='2')selected="selected" @endif>收益中</option>
                        <option value="0" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='0')selected="selected" @endif>待确认</option>
                        <option value="4" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='4')selected="selected" @endif>未通过</option>
                    </select>
                </div>
				<div class="layui-form layui-input-inline">
                    <select name="s_pay_type" lay-search lay-filter="s_pay_type" lay-search>
                        <option value="" >支付方式</option>
                        <option value="1" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='1')selected="selected" @endif>余额</option>
                        <option value="2" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='2')selected="selected" @endif>线下支付</option>
                        <option value="3" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='3')selected="selected" @endif>第三方支付(支付宝)</option>
                        <option value="4" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='4')selected="selected" @endif>第三方支付(微信)</option>
                        <option value="5" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='5')selected="selected" @endif>第三方支付(云闪付)</option>
                        <option value="6" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='6')selected="selected" @endif>第三方支付(快捷支付)</option>
                    </select>
                </div>
                 <div class="layui-form layui-input-inline">
                        <input class="layui-input"  autocomplete="off" placeholder="开始日" name="date_s" id="date_s" value="@if(isset($_REQUEST['date_s'])){{$_REQUEST['date_s']}}@endif">
                        <input class="layui-input"  autocomplete="off" placeholder="截止日" name="date_e" id="date_e" value="@if(isset($_REQUEST['date_e'])){{$_REQUEST['date_e']}}@endif">
                    </div>
                <div class="layui-input-inline">
                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>
                </div>
                <div class="layui-input-inline">
                </div>
            </form>
        </div>

        <xblock>
            <button class="layui-btn" >总确认订单金额:{{ $totalAmount }}元</button>
             <button class="layui-btn" >今日订单金额 :{{ $today_amount }}元</button>
            <button class="layui-btn" >今日确认订单金额:{{ $today_amount_ok }}元</button>

        </xblock>
        <xblock>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>
        </xblock>
             <table class="layui-table x-admin layui-form">
            <thead>
            <tr>
               <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>ID</th>
                <th>会员帐号</th>
                <th>产品名称</th>
                <th>订单号</th>
                <th>购买价格</th>
                <th>创建时间</th>
                <th>支付方式</th>
                <th>支付凭证 / 订单号</th>
                <th>状态</th>
                <th>备注</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="view">
            </tbody>
        </table>
        <div id="layer_pages"></div>
    </div>
<input name="s_categoryid" type="hidden" class="s_categoryid">
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
                <% item.id %>
            </td>
            <td onclick=""><a href="javascript:void(0);" onclick="$('.s_key').val('<% item.username %>');lists(1,{s_key:'<% item.username %>'})"> <% item.username %></a></td>
            <td>
                <a href="javascript:void(0);" onclick="$('.s_categoryid').val('<% item.productid %>');lists(1,{s_categoryid:'<% item.productid %>'})"> <% item.product %></a>
            </td>

            <td><% item.order %></td>
            <td><% item.amount %></td>
            <td><% item.useritem_time %></td>
            <td>
                <%# if(item.pay_type==1){ %>
                    余额
                <%# }else if(item.pay_type==2){ %>
                    线下支付
                <%# }else if(item.pay_type==3){ %>
                    第三方支付(支付宝)
                <%# }else if(item.pay_type==4){ %>
                    第三方支付(微信)
                <%# }else if(item.pay_type==5){ %>
                    USDT支付
                <%# }else if(item.pay_type==6){ %>
                    第三方支付(快捷支付)
                <%# }%>
            </td>
            <td width="180" id="photo-front-<% item.id %>">
                <%# if(item.pay_type==2 || item.pay_type==5){ %>
                <img style="max-width: 100px;" src="<% item.payimg  %>"  width="15" onclick="openFrontPhotos(<% item.id %>)">
                <%# }else if(item.pay_type==3 || item.pay_type==4 || item.pay_type==6){ %>
                    <% item.third_party_order?item.third_party_order:'' %>
                <%# }%>
            </td>
            <td>
                <%# if(item.status == 0){ %>
                    已结束
                <%# }else if(item.status==2){ %>
                    收益中
                <%# }else if(item.status==0 && (item.pay_type==3 || item.pay_type==4)){ %>
                    待支付
                <%# }else if(item.status==0 && (item.pay_type==2 || item.pay_type==5)){ %>
                    待确认购买
                <%# }else if(item.status==3){ %>
                    购买未通过
                <%# }%>
            </td>
            <td style="width:150px;"><% item.reason?item.reason:'' %></td>
            <td class="td-manage" width="100">
                <%# if(item.status==2 && (item.pay_type==2 || item.pay_type== 5)){ %>
                <a title="确认已购买"  onclick="ConfirmRec(<% item.id %>,'1',<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: green;font-size: 18px;">&#x1005;</i>
                </a>

                <a title="购买未通过"  onclick="ConfirmRec(<% item.id %>,'3',<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: red;font-size: 18px;">&#x1007;</i>
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

        function ConfirmFenHong(id,status,page){

                $.post("{{ route("bonus") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
                },function(data){
                        layer.msg(data.msg,{},function(){
                            if(data.status==0) {
                                lists(page);
                            }
                        });
                });
        }


        function ConfirmFenHongAll(){

                $.post("{{ route("bonus") }}",{
                    _token:"{{ csrf_token() }}",
                },function(data){
                        layer.msg(data.msg,{},function(){
                            if(data.status==0) {
                                lists(1);
                            }
                        });
                });
        }


        function ConfirmRec(id,status,page){
            @if($update==1)
                var msg=status==1?'是否确认购买成功?':'是否确认购买未通过?</br>请输入原因:</br><input name="reason">';
            layer.confirm(msg, {icon: 3, title:'提示'}, function(index){

                var reason = $("[name='reason']").val();

                $.post("{{ route($RouteController.".update") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
                    status:status,
                    reason:reason,
                },function(data){

                    @if(Cache::has("msgshowtime"))
                    if(data.status==0){
                        layer.msg(data.msg,{time:"{{Cache::get("msgshowtime")}}" },function(){
                            $(".lists_"+id).remove();

                            if(page>0){
                                lists(page);
                            }

                        });
                    }else{
                        layer.msg(data.msg,{icon:5,time:"{{Cache::get("msgshowtime")}}"});
                    }
                    @else
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
                    @endif


                });

                layer.close(index);
            });
            @else
            layer.alert('您没有权限访问');

            @endif
        }

        layui.use(['form','laydate'], function(){

            var form = layui.form;
            // form.on('select(category_id)', function(data){

            //     var obj={
            //         s_categoryid:data.value,
            //         s_status:$("[name='moneylog_status']").val(),
            //         s_key:$("[name='s_key']").val(),
            //     };
            //     lists(1,obj);
            // });

            // form.on('select(s_status)', function(data){

            //     var obj={
            //         s_status:data.value,
            //         s_categoryid:$("[name='category_id']").val(),
            //         s_key:$("[name='s_key']").val(),
            //     };
            //     lists(1,obj);
            // });

             var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#date_s' //指定元素
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#date_e' //指定元素
                ,mark: {
                '2022-05-15': '' //具体日期
                ,'2022-06-20': '' //如果为空字符，则默认显示数字+徽章
                ,'2022-05-21': ''
              }

            });

        });

    function openFrontPhotos(id){
        layer.photos({
            photos: '#photo-front-'+id
            ,shift: 0
            // ,full: true
        });
    }

    </script>

@endsection



