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
                        <option value="" >完成状态</option>
                        <option value="0" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='0')selected="selected" @endif>已结束</option>
                        <option value="1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='1')selected="selected" @endif>进行中</option>
                        <option value="2" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='2')selected="selected" @endif>待确认</option>


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

                    <!--<a class="layui-btn" onclick="ConfirmFenHongAll()">一键反佣</a>-->

                </div>
            </form>
        </div>
        

<xblock>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>


        </xblock>

             <table class="layui-table x-admin layui-form">



            <thead>

            <tr>
               <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>ID</th>
                <!--<th>ID</th>-->

                <th>会员帐号</th>
                <th>树木名称</th>
                <th>奖励金额</th>
                <th>当前能量</th>
                <th>总能量</th>

                <th>当前状态</th>
                <th>创建时间</th>

                

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
    {{--//'pay_code','pay_name','pay_bank','pay_pic','pay_desc','enabled'--}}
    <script id="demo" type="text/html">


        
        <%#  layui.each(d.data, function(index, item){ %>

        <tr>
           <td>
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div>
                    <% item.id %>
                </td>
            <!--<td>
                <% item.id %>
            </td>-->

            <td onclick=""><a href="javascript:void(0);" onclick="$('.s_key').val('<% item.username %>');lists(1,{s_key:'<% item.username %>'})"> <% item.username %></a></td>
            <td>
                <a href="javascript:void(0);" onclick="$('.s_categoryid').val('<% item.productid %>');lists(1,{s_categoryid:'<% item.productid %>'})"> <% item.product %></a>
            </td>

            <td><% item.zsje %></td>
            <td><% item.sendday_count %></td>
            <td><% item.grand_total %></td>
            <td><% item.created_at %></td>



            <td>
                <%# if(item.useritem_count>=item.sendday_count){ %>
                    已结束
                <%# }else if(item.status==1){ %>
                    收益中
                <%# }else if(item.status==2 && item.pay_type>2){ %>
                    待支付
                <%# }else if(item.status==2 && item.pay_type==2){ %>
                    待确认购买
                <%# }else if(item.status==3){ %>
                    购买未通过
                <%# }%>
            </td>

            <td class="td-manage" width="100">


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



