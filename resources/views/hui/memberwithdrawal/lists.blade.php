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
                <div class="layui-input-inline">
                    <input type="text" name="s_card"  placeholder="银行卡号" intocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_card'])){{$_REQUEST['s_card']}}@endif">
                </div>
				<div class="layui-input-inline">
                    <input type="text" name="s_realname"  placeholder="姓名" intocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_realname'])){{$_REQUEST['s_realname']}}@endif">
                </div>
                <div class="layui-form layui-input-inline">
                    <select name="s_status" lay-search lay-filter="s_status" lay-search>
                        <option value="" >提款状态</option>
                        <option value="0" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='0')selected="selected" @endif>未处理</option>
                        <option value="1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='1')selected="selected" @endif>已处理</option>
                        <option value="-1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='-1')selected="selected" @endif>失败</option>
                    </select>
                </div>
                <div class="layui-form layui-input-inline">
                    <input class="layui-input"  autocomplete="off" placeholder="开始日" name="date_s" id="date_s" value="@if(isset($_REQUEST['date_s'])){{$_REQUEST['date_s']}}@endif">
                    <input class="layui-input"  autocomplete="off" placeholder="截止日" name="date_e" id="date_e" value="@if(isset($_REQUEST['date_e'])){{$_REQUEST['date_e']}}@endif">
                </div>

                <div class="layui-input-inline">
                    <input type="text" name="s_min_price"  placeholder="请输入最小金额" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_min_price'])){{$_REQUEST['s_min_price']}}@endif">
                </div>

                <div class="layui-input-inline">
                    <input type="text" name="s_max_price"  placeholder="请输入最大金额" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_max_price'])){{$_REQUEST['s_max_price']}}@endif">
                </div>

                <div class="layui-input-inline">

                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>

                </div>
            </form>
            <form class="layui-form layui-col-md12 x-so" action="{{ route($RouteController.".export_excel") }}" method="get">
                <div class="layui-form layui-input-inline">
                    <input class="layui-input"  autocomplete="off" placeholder="开始日" name="exp_date_s" id="exp_date_s" value="">
                    <input class="layui-input"  autocomplete="off" placeholder="截止日" name="exp_date_e" id="exp_date_e" value="">
                </div>
                <div class="layui-form layui-input-inline">
                    <select name="exp__status" lay-search lay-filter="exp__status" lay-search>
                        <option value="0" select>未处理</option>
                        <option value="-1">失败</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <button class="layui-btn" lay-submit lay-filter="go">导出</button>
                </div>
            </form>
        </div>

        <xblock>
            <button class="layui-btn" >总提现:{{ $totalWithdrawal }}元</button>
             <button class="layui-btn" >今日总提现额 :{{ $today_withdrawal }}元</button>
            <button class="layui-btn" >今日确认总提现:{{ $today_withdrawal_ok }}元</button>
        </xblock>
             <table class="layui-table x-admin layui-form">
            <thead>
            <tr>
                <th>ID</th>
                <th>会员帐号</th>
                <th>实名信息</th>
                <th>银行账户</th>
                <th>提现金额</th>
                <th>手续费</th>
                <th>到账金额</th>
                <th>到账USDT</th>
                <th>USDT汇率</th>
                <th>状态</th>
                <th>申请日期</th>
                <th>备注</th>
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

            <td><% item.username %></td>
            <td>
                真实姓名：<% item.realname %> <br>
                身份证号：<% item.card %><br>
            </td>
            <td>
                <%# if(item.type==1){ %>
                    银行名称：<% item.bankName %> <br>
                    卡/帐号：<% item.bankcode %><br>
                    银行户名：<% item.bankrealname %><br>
                <%# }else if(item.type==3){ %>
                    提现方式：USDT-Trc20<br>
                    提现地址：<% item.address %> <br>
                费率：<em style="color: red"><% item.ext %></em><br>
                <%# }%>
            </td>
            <td><% item.amount %></td>
            <td><% item.fee %></td>
            <td>
                <% item.after_amount %>
            </td>
            <td><% item.after_usdt %></td>
            <td><% item.usdt_rate %></td>
            <td>
                <%# if(item.status==0){ %>
                    未处理
                <%# }else if(item.status==1){ %>
                    已处理
                <%# }else if(item.status==-1){ %>
                    已取消
                <%# }%>
            </td>
            <td><% item.created_at?item.created_at:'' %></td>
            <td><% item.memo?item.memo:'' %></td>
            <td class="td-manage">
                <%# if(item.status==0){ %>
                <a title="确认充值"  onclick="ConfirmRec(<% item.id %>,'1',<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: green;font-size: 16px;">&#x1005;</i>
                </a>

                <a title="取消充值"  onclick="ConfirmRec(<% item.id %>,'-1',<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: red;font-size: 16px;">&#x1007;</i>
                </a>
                <%# }%>
                <a title="删除" onclick="del(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="font-size: 16px;">&#xe640;</i>
                </a>
            </td>
        </tr>


        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>

    </script>




    <script>


        function ConfirmRec(id,status,page){
            @if($update==1)
                var msg=status==1?'确认提现成功':'确认取消提现';
            layer.confirm('确定要'+msg+'?', {icon: 3, title:'提示'}, function(index){


                $.post("{{ route($RouteController.".update") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
                    status:status,
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


		function ConfirmRecThird (id,status,page){
            @if($update==1)
                var msg=status==1?'确认发起代付':'确认取消提现';
            layer.confirm('确定要'+msg+'?', {icon: 3, title:'提示'}, function(index){


                $.post("{{ route($RouteController.".update_third") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
                    status:status,
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


        function sendsms(id,page){


            layer.confirm('确定要发送短信通知?', {icon: 3, title:'提示'}, function(index){


                $.post("{{ route($RouteController.".sendsms") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
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
            });

            laydate.render({
                elem: '#exp_date_s'//指定元素
                ,value: new Date()
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#exp_date_e' //指定元素
                ,value: new Date()
            });
        });


    </script>

@endsection



