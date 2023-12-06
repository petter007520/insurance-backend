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
            <form class="layui-form layui-col-md12 x-so" action="{{ route($RouteController.".buyviplist") }}" method="get">
                <div class="layui-input-inline">

                    <input type="text" name="s_key"  placeholder="请输入会员帐号" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">

                </div>

                <div class="layui-form layui-input-inline">


                    

                </div>

                <div class="layui-form layui-input-inline">
                    <select name="s_type" lay-search lay-filter="s_type" lay-search>
                        <option value="" >购买等级</option>
                        <option value="购买等级LV1" @if(isset($_REQUEST['s_type']) && $_REQUEST['s_type']==$itme)selected="selected" @endif>购买等级LV1</option>
                        <option value="购买等级LV2" @if(isset($_REQUEST['s_type']) && $_REQUEST['s_type']==$itme)selected="selected" @endif>购买等级LV2</option>
                        <option value="购买等级LV3" @if(isset($_REQUEST['s_type']) && $_REQUEST['s_type']==$itme)selected="selected" @endif>购买等级LV3</option>
                    </select>



                </div>
                <div class="layui-form layui-input-inline">
                    <select name="s_status" lay-search lay-filter="s_status" lay-search>
                        <option value="" >充值状态</option>
                        <option value="0" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='0')selected="selected" @endif>未处理</option>
                        <option value="1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='1')selected="selected" @endif>已处理</option>
                        <option value="-1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='-1')selected="selected" @endif>失败</option>

                    </select>



                </div>

                <div class="layui-input-inline">

                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>

                </div>
            </form>
        </div>



             <table class="layui-table x-admin layui-form">



            <thead>

            <tr>
                <th>ID</th>
                <th>订单号</th>
                <th>会员</th>
                <th>VIP编号</th>
                <th>购买方式</th>
                <th>购买类型</th>
                <th>金额</th>
                <th>状态</th>
                <th>支付凭证</th>
                <th>支付时间</th>
                <th>备注</th>
                <th>充值时间</th>
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



            <td><% item.ordernumber %></td>

            <td><% item.username %></td>
            <td><% item.vip_no %></td>
            <td><% item.paymentname?item.paymentname:'' %></td>
            <td><% item.type?item.type:'' %></td>
            <td><% item.amount %></td>

            <td>
                <%# if(item.status==0){ %>
                    未处理
                <%# }else if(item.status==1){ %>
                    已通过
                <%# }else if(item.status==-1){ %>
                    已拒绝
                <%# }%>
            </td>
            <!-- <td><% item.payimg?'<img style="max-width: 200px;" src="'+item.payimg+'" width="100" onmouseover="this.width=200" onmouseout="this.width=100"/>':'' %></td> -->
            <td  id="photo-front-<% item.id %>"><img style="max-width: 100px;" src=<% item.payimg?item.payimg:'' %>  width="20"  onclick="openFrontPhotos(<% item.id %>)"></td>
            <td><% item.paytime?item.paytime:'' %></td>
            <td><% item.memo %></td>
            <td><% item.created_at %></td>
            <td class="td-manage">
                <%# if(item.status==0){ %>
                <a title="确认"  onclick="ConfirmRec(<% item.id %>,'1',<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: green;font-size: 18px;">&#x1005;</i>
                </a>

                <a title="取消"  onclick="ConfirmRec(<% item.id %>,'-1',<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: red;font-size: 18px;">&#x1007;</i>
                </a>
                <%# }%>
                <%# if(item.status==1){ %>
                <%# if(item.sendsms==0){ %>
                <a title="发送短信"  onclick="sendsms(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: green;font-size: 18px;">&#xe609;</i>
                </a>
                <%# }else if(item.status==1){ %>
                <a title="重新发送短信"  onclick="sendsms(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon" style="color: red;font-size: 18px;">&#xe609;</i>
                </a>
                <%# }%>
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

        function ConfirmRec(id,status,page){
            @if($update==1)
                var msg=status==1?'确认购买成功':'取消购买';
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



        layui.use(['form'], function(){

        var form = layui.form;
            form.on('select(category_id)', function(data){

                var obj={
                    s_category_id:data.value,
                    s_status:$("[name='s_status']").val(),
                    s_type:$("[name='s_type']").val(),
                    s_key:$("[name='s_key']").val(),
                };
                lists(1,obj);
            });

            form.on('select(s_status)', function(data){

                var obj={
                    s_status:data.value,
                    s_category_id:$("[name='s_category_id']").val(),
                    s_type:$("[name='s_type']").val(),
                    s_key:$("[name='s_key']").val(),
                };
                lists(1,obj);
            });

            form.on('select(s_type)', function(data){

                var obj={
                    s_type:data.value,
                    s_status:$("[name='s_status']").val(),
                    s_category_id:$("[name='s_category_id']").val(),
                    s_key:$("[name='s_key']").val(),
                };
                lists(1,obj);
            });

        });

     function openFrontPhotos(id){
        layer.photos({
            photos: '#photo-front-'+id
            ,shift: 0
        });
    }

    </script>

@endsection



