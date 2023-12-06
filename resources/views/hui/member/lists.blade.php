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
               <input class="layui-input"  autocomplete="off" placeholder="注册开始日" name="date_s" id="date_s" value="@if(isset($_REQUEST['date_s'])){{$_REQUEST['date_s']}}@endif">
                <input class="layui-input"  autocomplete="off" placeholder="注册截止日" name="date_e" id="date_e" value="@if(isset($_REQUEST['date_e'])){{$_REQUEST['date_e']}}@endif">
                <input type="text" name="s_key"  placeholder="请输入用户名" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">
                <!--<div class="layui-input-inline">-->

                <!--    <select name="s_categoryid" lay-filter="s_categoryid">-->
                <!--        <option value="">会员等级</option>-->
                <!--        @if($memberlevel)-->
                <!--            @foreach($memberlevel as $level)-->
                <!--                <option value="{{$level->id}}" @if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']==$level->id) selected="selected" @endif>{{$level->name}}</option>-->


                <!--            @endforeach-->
                <!--        @endif-->
                <!--    </select>-->

                <!--</div>-->

                <div class="layui-input-inline">

                    <select name="s_mtype" lay-filter="s_mtype">

                        <option value="" @if(isset($_REQUEST['s_mtype']) && $_REQUEST['s_mtype']=='') selected="selected" @endif>会员身份</option>
                        <option value="0" @if(isset($_REQUEST['s_mtype']) && $_REQUEST['s_mtype'] !='' && $_REQUEST['s_mtype']==0) selected="selected" @endif>普通会员</option>
                        <option value="1" @if(isset($_REQUEST['s_mtype']) && $_REQUEST['s_mtype']==1) selected="selected" @endif>代理会员</option>

                    </select>

                </div>

                <!--<div class="layui-input-inline">-->

                <!--    <select name="s_is_ysh" lay-filter="s_is_ysh">-->

                <!--        <option value="" @if(isset($_REQUEST['s_is_ysh']) && $_REQUEST['s_is_ysh']=='') selected="selected" @endif>云商户</option>-->
                <!--        <option value="0" @if(isset($_REQUEST['s_is_ysh']) && $_REQUEST['s_is_ysh'] !='' && $_REQUEST['s_is_ysh']==0) selected="selected" @endif>普通会员</option>-->
                <!--        <option value="1" @if(isset($_REQUEST['s_is_ysh']) && $_REQUEST['s_is_ysh']==1) selected="selected" @endif>云商队长</option>-->
                <!--    </select>-->

                <!--</div>-->

                <div class="layui-input-inline">

                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>

                </div>





            </form>
        </div>
        <xblock>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>
            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                添加</button>

        </xblock>


            <table class="layui-table x-admin layui-form">
                <colgroup>
                    <col>
                    <col>
                    <!--<col width="200">-->
                    <!--<col width="100">-->
                    <!--<col width="150">-->
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <!--<col width="150">-->
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col >
                </colgroup>
                <thead>
                <tr>
                    <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>ID</th>
                    <th>推荐号</th>
                    <th>账号</th>
                    <!--<th>云商户</th>-->
                    <!--<th>云聊号</th>-->
                    <!--<th>云商户星级 levelName</th>-->
                    <th>姓名</th>
                    <th>手机</th>
                    <th>收益余额</th>
                    <th>可提现余额</th>
                    <th>冻结金额</th>
                    <th>会员等级</th>
                    <th>团队等级</th>

                    <!--<th>云币</th>-->
                    <th>上级</th>
                    <th>状态</th>
                    <th>最后登录时间</th>

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
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div>
                    <% item.id %>
                </td>
                <td ><% item.invicode?item.invicode:'' %></td>
                <td class="title_<% item.id %>"><% item.username %></td>
                <!-- <td <% item.activation==1?'style="color:green"':'style="color:red"'%> ><% item.levelName %></td>
                <td>
                    <%# if(item.is_ysh==0){ %>
                        否
                    <%# }else if(item.is_ysh==1){ %>
                        是
                    <%# }%>
                </td>
                <td ><% item.cloudchat?item.cloudchat:'' %></td>
                <td <% item.activation==1?'style="color:green"':'style="color:red"'%> >⭐<% item.yshlevel %></td> -->
                <td><% item.realname?item.realname:'' %></td>
                <td><% item.Showmobile?item.Showmobile:'' %></td>
                <td><% item.amount?item.amount:'0' %></td>
                <td><% item.ktx_amount?item.ktx_amount:'0' %></td>
                <td><% item.integral?item.is_dongjie:'0' %></td>
                <td><% item.levelName %></td>
                <td><% item.levelgroupName %></td>

                <!-- <td><% item.integral?item.cloudcoin:'0' %></td> -->
                <td><% item.inviter?item.inviter:'0' %>（<% item.inviterName?item.inviterName:'0' %>）</td>
                <td><% item.state?'正常':'禁用' %></td>
                <td id='<% item.id %>' onclick='sign_log(<% item.id %>,["2022-05-1","2022-05-2","2022-05-4"])'><% item.logintime?item.logintime:'-' %></td>

                <td class="td-manage" style="width:200px;">

                    <!-- <%# if(item.is_ysh==1){ %>
                    <a  title="云商户申请" onclick="set_ysh(this,<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon" style="color:green;">&#xe672;</i>
                    </a>
                    <%# } %>   -->

                    <a title="查看详情"  onclick="userdetail('<% item.inviter %>','<% item.inviterName %>','<% item.tuiguangrens %>','<% item.recharges %>','<% item.withdrawals %>','<% item.moneys %>','<% item.allxf_fee %>')" href="javascript:;">
                        <i class="layui-icon" style="color:red;">&#xe702;</i>
                    </a>

                    <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon" >&#xe642;</i>
                    </a>

                    <a title="资金操作"  onclick="moneys(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon" style="color:green;">&#xe65e;</i>
                    </a>

                    <a title="冻结解冻"  onclick="frozen(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon" style="color: red;">&#xe6b1;</i>
                    </a>

                    <a onclick="member_stop(this,<% item.id %>,<% d.current_page %>)" href="javascript:;"  title="<% item.state==0?'启用':'禁用' %>">

                            <%# if(item.state==1){ %>
                        <i class="layui-icon" style="color:green;">&#xe62f;</i>
                            <%# }else{%>
                        <i class="layui-icon" style="color:red;">&#xe601;</i>
                            <%# } %>

                    </a>

                    <!-- <a title="赠送抽奖券"  onclick="luckdraws(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon" style="color: red;">&#xe66e;</i>
                    </a>

                    <a title="查看密码"  onclick="showpassword('<% item.Showpassword %>','<% item.Showpaypwd %>')" href="javascript:;">
                        <i class="layui-icon" style="color:red;">&#xe615;</i>
                    </a> -->

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


function showpassword(pwd,paypwd){
    var msg='登录密码:'+pwd+'\r\n'+'支付密码:'+paypwd;
    layer.alert(msg,{title:'密码信息'});
}

function userdetail(inviter,inviterName,tuiguangrens,recharges,withdrawals,moneys,allxf_fee){
    var msg='推荐人ID:'+ inviter + '<br/>' +
            '推荐人账号:'+ inviterName +'<br/>' +
            '推广人数:'+ tuiguangrens +'<br/>' +
            '充值总额:'+ recharges +'<br/>' +
            '提现总额:'+ withdrawals +'<br/>' +
            '收益总额:'+ moneys+'<br/>' +
            '团队总业绩:'+ allxf_fee +'<br/>' ;

    layer.alert(msg,{title:'用户详情'});
}


    layui.use(['form','laydate'], function(){
        var form = layui.form;
        form.on('select(s_categoryid)', function(data){
            lists(1,{s_categoryid:data.value});
        });

        form.on('select(s_mtype)', function(data){
            lists(1,{s_mtype:data.value});
        });

        form.on('select(s_is_ysh)', function(data){
            lists(1,{s_is_ysh:data.value});
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

    /*用户-停用*/
    function member_stop(obj,id,page){
        layer.confirm('确认要'+$(obj).attr('title')+'吗？',function(index){
            var index;
            $.ajax({
                url: "{{ route($RouteController.".switchonoff") }}",
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
                    }else{
                        layer.alert(data.msg,{time: 2000});

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
        });
        // layer.confirm('确认要'+$(obj).attr('title')+'吗？',function(index){

        //     if($(obj).attr('title')=='启用'){

        //         //发异步把用户状态进行更改
        //         $(obj).attr('title','停用')
        //         $(obj).find('i').html('&#xe62f;');

        //         $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
        //         layer.msg('已停用!',{icon: 5,time:1000});

        //     }else{
        //         $(obj).attr('title','启用')
        //         $(obj).find('i').html('&#xe601;');

        //         $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
        //         layer.msg('已启用!',{icon: 5,time:1000});
        //     }

        //     switchings(id,obj,page);

        // });
    }


    function switchings(id,obj,page){

        var index;
        $.ajax({
            url: "{{ route($RouteController.".switch") }}",
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


    function moneys(id,obj,page){


        var index=   layer.open({
            title:'{{$title}}',
            type: 2,
            fixed: false,
            maxmin: true,
            area: ['70%', '70%'],
            btn:['提交','取消'],
            yes:function(index,layero){
                var ifname="layui-layer-iframe"+index;
                var Ifame=window.frames[ifname];
                var FormBtn=eval(Ifame.document.getElementById("layui-btn"));
                FormBtn.click();
            },
            content: ['{{ route($RouteController.".moneys")}}?id='+id,'yes'],
            end: function () {
                lists(1);

            },
            error: function(msg) {
                var json=JSON.parse(msg.responseText);
                var errormsg='';
                $.each(json,function(i,v){
                    errormsg+=' <br/>'+ v.toString();
                } );
                layer.alert(errormsg);

            }
        });


    }


    function frozen(id,page){


        var index=   layer.open({
            title:'{{$title}}',
            type: 2,
            fixed: false,
            maxmin: true,
            area: ['70%', '70%'],
            btn:['提交','取消'],
            yes:function(index,layero){
                var ifname="layui-layer-iframe"+index;
                var Ifame=window.frames[ifname];
                var FormBtn=eval(Ifame.document.getElementById("layui-btn"));
                FormBtn.click();
            },
            content: ['{{ route($RouteController.".frozen")}}?id='+id,'yes'],
            end: function () {
                lists(1);

            },
            error: function(msg) {
                var json=JSON.parse(msg.responseText);
                var errormsg='';
                $.each(json,function(i,v){
                    errormsg+=' <br/>'+ v.toString();
                } );
                layer.alert(errormsg);

            }
        });


    }

    function luckdraws(id,page){


        layer.prompt({
            formType: 3,
            value: 1,
            title: '赠送抽奖券数量',

        }, function(value, index, elem){

            $.post("{{ route($RouteController.".luckdraws") }}",{
                _token:"{{ csrf_token() }}",
                id:id,
                luckdraws:value,
            },function(data){



                    layer.msg(data.msg,{time:"{{Cache::get("msgshowtime")}}" },function(){

                        if(page>0){
                            lists(page);
                        }

                    });



            });


            layer.close(index);
        });

    }

    /*授权云商户*/
    function set_ysh(obj,id,page){
        layer.confirm('确认要授权该'+$(obj).attr('title')+'吗？',function(index){
            var index;
            $.ajax({
                url: "{{ route($RouteController.".set_ysh") }}",
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
        });
    }

    function sign_log(id,a){
        layer.open({
          type: 1,
          skin: 'layui-layer-rim', //加上边框
          area: ['420px', '240px'], //宽高
          content: a
        });
        // layer.tips(a, id, {
        //     time: 5*1000,
        //   tips: [1, '#0FA6D8'] //还可配置颜色
        // });

    }

    </script>
@endsection

