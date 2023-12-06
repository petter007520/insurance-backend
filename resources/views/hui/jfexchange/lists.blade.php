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





                </div>

                <div class="layui-form layui-input-inline">
                    <select name="s_status" lay-search lay-filter="s_status" lay-search>
                        <option value="" >状态</option>
                        <option value="0" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='0')selected="selected" @endif>未处理</option>
                        <option value="1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='1')selected="selected" @endif>已发货</option>
                        <option value="-1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='-1')selected="selected" @endif>失败</option>

                    </select>



                </div>

                <div class="layui-input-inline">

                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>

                </div>
            </form>
        </div>



       {{--

        <xblock>

            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                充值</button>

        </xblock>

        --}}

             <table class="layui-table x-admin layui-form">



            <thead>

            <tr>
                <th>ID</th>

                <th>会员帐号</th>
                <th>商品</th>
                <th>收货信息</th>
                <th>消费积分</th>
                <th>支付凭证</th>
                <th>状态</th>
                <th>兑换日期</th>
                <th>发货消息</th>
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





            <td><% item.username %></td>
            <td><% item.productname %></td>

            <td>
                名称：<% item.name %> <br>
                电话：<% item.phone %><br>
                收货地址：<% item.shouhuodizhi %>

            </td>

            <td><% item.integral %></td>
            <td><% item.payimg?'<img style="max-width: 200px;" src="'+item.payimg+'" width="100" onmouseover="this.width=200" onmouseout="this.width=100"/>':'' %></td>
            <td>
                <%# if(item.status==0){ %>
                    未处理
                <%# }else if(item.status==1){ %>
                    已发货
                <%# }else if(item.status==-1){ %>
                    已取消
                <%# }%>
            </td>
            <td><% item.created_at?item.created_at:'' %></td>
            <td><% item.memo?item.memo:'' %></td>
            <td class="td-manage">
                <%# if(item.status==0){ %>
                <a title="确认发货"  onclick="ConfirmRec(<% item.id %>,'1',<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: green;font-size: 16px;">&#x1005;</i>
                </a>

                <a title="取消兑换"  onclick="ConfirmRec(<% item.id %>,'-1',<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: red;font-size: 16px;">&#x1007;</i>
                </a>
                <%# }%>
                <%# if(item.status==1){ %>
                <%# if(item.sendsms==0){ %>
                <a title="发送短信"  onclick="sendsms(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: green;font-size: 16px;">&#xe609;</i>
                </a>
                <%# }else if(item.status==1){ %>
                <a title="重新发送短信"  onclick="sendsms(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: red;font-size: 16px;">&#xe609;</i>
                </a>
                <%# }%>
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
                var msg=status==1?'确认兑换发货':'确认取消兑换';
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


        function sendsms(id,page){


            layer.confirm('确定要发送短信通知?', {icon: 3, title:'提示'}, function(index2){


                layer.prompt({
                    formType: 2,
                    value: '您在积分商城兑换的商品已发货,快递:,单号:',
                    title: '请输入发货信息,将以站内消息通知',
                    area: ['800px', '350px'] //自定义文本域宽高
                }, function(value, index, elem){
                    //alert(value); //得到value
                    layer.close(index);


                $.post("{{ route($RouteController.".sendsms") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
                    value:value,
                },function(data){


                    @if(Cache::has("msgshowtime"))
                    if(data.status==0){
                        layer.msg(data.msg,{time:"{{Cache::get("msgshowtime")}}" },function(){

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

                            if(page>0){
                                lists(page);
                            }
                        });
                    }else{
                        layer.msg(data.msg,{icon:5});
                    }
                    @endif


                });

                layer.close(index2);
            });

            });

        }


        layui.use(['form'], function(){

        var form = layui.form;
            form.on('select(category_id)', function(data){

                var obj={
                    s_categoryid:data.value,
                    s_status:$("[name='moneylog_status']").val(),
                    s_key:$("[name='s_key']").val(),
                };
                lists(1,obj);
            });

            form.on('select(s_status)', function(data){

                var obj={
                    s_status:data.value,
                    s_categoryid:$("[name='category_id']").val(),
                    s_key:$("[name='s_key']").val(),
                };
                lists(1,obj);
            });

        });



    </script>

@endsection



