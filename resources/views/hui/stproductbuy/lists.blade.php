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
                        <div class="layui-input-inline">

                            <input type="text" name="username"  placeholder="请输入会员帐号" autocomplete="off" class="layui-input s_key" value="@if(isset($_REQUEST['username'])){{$_REQUEST['username']}}@endif">

                        </div>

        			<!--	<div class="layui-input-inline">

                            <input type="text" name="top_uid"  placeholder="上级会员帐号" autocomplete="off" class="layui-input top_uid" value="@if(isset($_REQUEST['top_uid'])){{$_REQUEST['top_uid']}}@endif">

                        </div>-->
        				<div class="layui-form layui-input-inline">
                            <select name="pay_type" lay-search lay-filter="pay_type" lay-search>
                                <option value="" >支付方式</option>
                                <option value="1" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='1')selected="selected" @endif>余额</option>
                                <option value="2" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='2')selected="selected" @endif>线下支付</option>
                                <option value="3" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='3')selected="selected" @endif>第三方支付(支付宝)</option>
                                <option value="4" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='4')selected="selected" @endif>第三方支付(微信)</option>
                                <option value="5" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='5')selected="selected" @endif>第三方支付(云闪付)</option>
                                <option value="6" @if(isset($_REQUEST['s_pay_type']) && $_REQUEST['s_pay_type']=='6')selected="selected" @endif>第三方支付(快捷支付)</option>

                            </select>

                        </div>

                        <!-- <div class="layui-form layui-input-inline">
                                <input class="layui-input"  autocomplete="off" placeholder="开始日" name="date_s" id="date_s" value="@if(isset($_REQUEST['date_s'])){{$_REQUEST['date_s']}}@endif">
                                <input class="layui-input"  autocomplete="off" placeholder="截止日" name="date_e" id="date_e" value="@if(isset($_REQUEST['date_e'])){{$_REQUEST['date_e']}}@endif">
                            </div>-->

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
                <colgroup>
                  <!--  <col width="150">
                    <col width="200">
                    <col width="120">
                    <col width="100">
                    <col width="100">
                    <col width="200">
                    <col width="200">
                    <col width="250">
                    <col width="200">
                    <col width="200">
                    <col width="200">-->
                </colgroup>
                <thead>
                <tr>
                    <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                    <th>会员帐号</th>
                    <th>产品名称</th>
                    <th>购买价格</th>
                    <th>购买数量</th>
                    <th>创建时间</th>
                    <th>支付方式</th>
                    <th>支付凭证</th>
                    <th> 订单号</th>
                    <th>状态</th>
                    <th>快递公司</th>
                    <th>快递号</th>
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
    {{--编号	分类	项目标题	保理机构	项目规模	投资进度	起投金额	交易收益	项目期限	投资状态	是否首页展示	添加时间	排序	操作--}}
    <script id="demo" type="text/html">


            <%#  layui.each(d.data, function(index, item){ %>

            <tr>
                <td>
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div>
                    <span style=""><% item.id %></span>
                </td>
                <td><% item.username %></td>
                <td><% item.stpname %> </td>
                <td><% item.fee %></td>
                <td><% item.stnum %></td>
                <td><% item.created_at %></td>
                <td> <%# if(item.pay_type==1){ %>
                    余额
                <%# }else if(item.pay_type==2){ %>
                    线下支付
                <%# }else if(item.pay_type==3){ %>
                    第三方支付(支付宝)
                <%# }else if(item.pay_type==4){ %>
                    第三方支付(微信)
                <%# }else if(item.pay_type==5){ %>
                    第三方支付(云闪付)
                <%# }else if(item.pay_type==6){ %>
                    第三方支付(快捷支付)
                <%# }%></td>

                <td>
                <%# if(item.payimg){ %>

                        <!-- <a href="<% item.payimg  %>"><img style="max-width: 100px;" src=<% item.payimg  %>  width="15" ></a> -->
                        <img style="max-width: 100px;" src=<% item.payimg  %>  width="15" onclick="openFrontPhotos(<% item.id %>)">

                <%# }else if(item.pay_type==3 || item.pay_type==4 || item.pay_type==5 || item.pay_type==6){ %>
                    <% item.third_party_order?item.third_party_order:'' %>
                <%# }%>
                </td>
                <td><% item.order %></td>
                <td> <%# if(item.status==0){ %>
                   未发货
                <%# }else if(item.status==1){ %>
                   已发货
                <%# }else if(item.status==2 ){ %>
                    待审核

                <%# }else if(item.status==3){ %>
                    已拒绝
                <%# }%>
                </td>
                <td><% item.express %></td>
                <td><% item.deliverysno %></td>
                <td><% item.reason %></td>

                <td class="td-manage">
                <%# if(item.status==2){ %>
                    <a  onclick="ConfirmRec1(<% item.id %>,'0',<% d.current_page %>)" href="javascript:;">
                        <span style="color:green;margin-right:10px;">同意<span>
                    </a>

                    <a   onclick="ConfirmRec1(<% item.id %>,'3',<% d.current_page %>)" href="javascript:;">
                        <span style="color:red">拒绝</span>
                    </a>
                    <%# }%>
                    <%# if(item.status==0){ %>
                    <a  onclick="ConfirmRec(<% item.id %>,'1',<% d.current_page %>)" href="javascript:;">
                        <span style="color:green;margin-right:10px;">发货<span>
                    </a>

                    <%# }%>
                </td>
            </tr>

            <%#  }); %>
            <%#  if(d.length === 0){ %>
            无数据
            <%#  } %>

    </script>

    <script>





    layui.use('form', function(){
        var form = layui.form;


        form.on('switch(switchTest-settop)', function(data){
            var id=data.elem.id;
            var top_status= data.elem.checked?1:0;
            var load;
            $.ajax({
                url: "",
                type:"post",     //请求类型
                data:{
                    id:id,top_status:top_status,

                    _token:"{{ csrf_token() }}"
                },  //请求的数据
                dataType:"json",  //数据类型
                beforeSend: function () {
                    // 禁用按钮防止重复提交，发送前响应
                    load = layer.load();

                },
                success: function(data){
                    //laravel返回的数据是不经过这里的
                    //layer.closeAll();
                    if(data.status==0){

                        layer.msg(data.msg,{time: "{{Cache::get("msgshowtime")}}"},function(){
                            layer.closeAll();
                        });

                    }else{
                        layer.msg(data.msg,{icon: 5},function(){

                        });
                    }
                },
                complete: function () {//完成响应
                    layer.close(load);
                },
                error: function(msg) {
                    var json=JSON.parse(msg.responseText);
                    var errormsg='';
                    $.each(json,function(i,v){
                        errormsg+=' <br/>'+ v.toString();
                    } );
                    layer.alert(errormsg);

                },

            });

        });


    });

    function ConfirmRec1(id,status,page){
    @if($update==1)
        var msg=status==0?'是否同意?':'是否确认拒绝?</br>请输入原因:</br><input name="reason">';
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
    var danhao ="222222"
    var kuaidi ="222222"
    function ConfirmRec(id,status,page){

    @if($update==1)
        var msg=status==1?'确认发货':'确认取消兑换';
        var vmsg=status==1?'快递:':'取消原因';
        var tmsg=status==1?'物流信息':'取消原因';
        layer.confirm('确定要'+msg+'?', {icon: 3, title:'提示'}, function(index){
            layer.prompt({
                formType: 3,
                value: vmsg,
                placeholder: '输入注销原因',
                title: tmsg,
                area: ['800px', '50px'] //自定义文本域宽高
            }, function(value, index, elem){

                //  alert($('#danhao').val()); //得到value
                console.log(danhao)
                layer.close(index);


                $.post("{{ route($RouteController.".update") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
                    status:status,
                    express:value,
                    deliverysno:danhao
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
            $(".layui-layer-content").append("<br/><input type=\"text\"  id= \"danhao\" oninput=\"danhao = this.value\"   class=\"layui-input\" placeholder=\"快递单号\"/>")

            // $(".layui-layer-content").append("<br/><input type=\"text\"  id= \"kuaidi\" oninput=\"kuaidi = this.value\"   class=\"layui-input\" placeholder=\"快递公司\"/>")
        });
    @else
        layer.alert('您没有权限访问');

    @endif
    }

    /*用户-停用*/
    function member_stop(obj,id,page){
        layer.confirm('确认要停用吗？',function(index){

            if($(obj).attr('title')=='启用'){

                //发异步把用户状态进行更改
                $(obj).attr('title','停用')
                $(obj).find('i').html('&#xe62f;');

                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!',{icon: 5,time:1000});

            }else{
                $(obj).attr('title','启用')
                $(obj).find('i').html('&#xe601;');

                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!',{icon: 5,time:1000});
            }

            switchings(id,obj,page);

        });
    }


    function switchings(id,obj,page){

        var index;
        $.ajax({
            url: "{{ route($RouteController.".lists") }}",
            type:"post",     //请求类型
            data:{
                id:id,
                _token:"{{ csrf_token() }}"
            },  //请求的数据
            dataType:"json",  //数据类型
            beforeSend: function () {
                // 禁用按钮防止重复提交，发送前响应
                 index = layer.load();

            },
            success: function(data){
                //laravel返回的数据是不经过这里的
                if(data.status==0){
                    layer.close(index);
                    lists(page);
                }
            },
            complete: function () {//完成响应

            },
            error: function(msg) {
                var json=JSON.parse(msg.responseText);
                var errormsg='';
                $.each(json,function(i,v){
                    errormsg+=' <br/>'+ v.toString();
                } );
                layer.alert(errormsg);

            },

        });


    }

    function update_currline(id){
        var index;
        $.ajax({
            url: "",
            type:"post",     //请求类型
            data:{
                pid:id,
                _token:"{{ csrf_token() }}"
            },  //请求的数据
            dataType:"json",  //数据类型

            success: function(data){
                //laravel返回的数据是不经过这里的
                if(data.status==0){
                   layer.msg(data.msg,{icon: 1,time:1000});
                }else{
                    layer.msg(data.msg,{icon: 5,time:1000});
                }
            },
            complete: function () {//完成响应

            },
            error: function(msg) {
                var json=JSON.parse(msg.responseText);
                var errormsg='';
                $.each(json,function(i,v){
                    errormsg+=' <br/>'+ v.toString();
                } );
                layer.alert(errormsg);

            },

        });
    }
function openFrontPhotos(id){
    //alert(111)
        layer.photos({
            photos: '#photo-front-'+id
            ,shift: 0
            // ,full: true
        });
    }

    </script>
@endsection

