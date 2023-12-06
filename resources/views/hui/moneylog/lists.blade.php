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

            <select name="s_category_id" lay-search lay-filter="category_id">
                 <option value="" >类型</option>
                <option value="提款" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='提款')selected="selected" @endif>提款</option>
                <option value="提款成功" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='提款成功')selected="selected" @endif>提款成功</option>
                <option value="项目分红" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='项目分红')selected="selected" @endif>项目分红</option>
                
                <option value="加入项目,银行卡付款" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='加入项目,银行卡付款')selected="selected" @endif>加入项目,银行卡付款</option>
                <option value="加入项目,余额付款" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='加入项目,余额付款')selected="selected" @endif>加入项目,余额付款</option>
                <option value="下线购买分成" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='下线购买分成')selected="selected" @endif>下线购买分成</option>
                <option value="赠送项目" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='赠送项目')selected="selected" @endif>赠送项目</option>
                <option value="注册赠送项目" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='注册赠送项目')selected="selected" @endif>注册赠送项目</option>
                <option value="注册赠送金额" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='注册赠送金额')selected="selected" @endif>注册赠送金额</option>
                <option value="签到奖励(+)" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='签到奖励(+)')selected="selected" @endif>一卡通签到奖励</option>
                <option value="一卡通返利" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='一卡通返利')selected="selected" @endif>一卡通返利</option>
                <option value="冻结资金" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='冻结资金')selected="selected" @endif>冻结资金</option>
                <option value="扣款" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']=='扣款')selected="selected" @endif>扣款</option>
                <!--@if(\Cache::has('webmsgtype'))-->
                <!--    <option value="" >类型</option>-->
                <!--    @foreach(explode("|", \Cache::get('webmsgtype')) as $itme)-->
                <!--        <option value="{{$itme}}" @if(isset($_REQUEST['s_category_id']) && $_REQUEST['s_category_id']==$itme)selected="selected" @endif>{{$itme}}  </option>-->
                <!--    @endforeach-->
                <!--@endif-->

            </select>

        </div>

        <div class="layui-form layui-input-inline">

            <select name="s_status"  lay-search lay-filter="s_status">


                <option value="" >流水方向</option>

                <option value="+" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='+')selected="selected" @endif>收入</option>
                <option value="-" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='-')selected="selected" @endif>支出</option>



            </select>

        </div>

        <div class="layui-input-inline">

            <button class="layui-btn" lay-submit lay-filter="go">查询</button>

        </div>
    </form>
    </div>
    <xblock>
        <!--<button class="layui-btn layui-btn-danger" onclick="delAll()" style="margin-right: 50px;">-->
        <!--    <i class="layui-icon">&#xe640;</i>-->
        <!--    批量删除-->
        <!--</button>-->


    </xblock>
        <table class="layui-table x-admin layui-form">

            <thead>
            <tr>
                <th class="layui-form text-center" ><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                <th>用户名</th>
                <th>金额</th>
                <th>类型</th>
                <th>原有金额</th>
                <th>现有金额</th>
                <th>时间</th>
                <th>说明</th>
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
            <td class="title_<% item.id %>"><% item.moneylog_user %></td>
            <td <% item.moneylog_status=='+'?'style="color: green"':'style="color: red"' %> ><% item.moneylog_status %><% item.moneylog_money %></td>
            <td><% item.moneylog_type %></td>
            <td><% item.moneylog_yuanamount?item.moneylog_yuanamount:'0' %></td>
            <td><% item.moneylog_houamount?item.moneylog_houamount:'0' %></td>
            <td><% item.created_at %></td>
            <td><% item.moneylog_notice %></td>
        </tr>



        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>

    </script>

    <script>





        layui.use('form', function(){
            var form = layui.form;


            // form.on('select(category_id)', function(data){

            //     var obj={
            //         s_category_id:data.value,
            //         s_status:$("[name='s_status']").val(),
            //         s_key:$("[name='s_key']").val(),
            //     };
            //     lists(1,obj);
            // });

            // form.on('select(s_status)', function(data){

            //     var obj={
            //         s_status:data.value,
            //         s_category_id:$("[name='s_category_id']").val(),
            //         s_key:$("[name='s_key']").val(),
            //     };
            //     lists(1,obj);
            // });







        });

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


    </script>
@endsection

