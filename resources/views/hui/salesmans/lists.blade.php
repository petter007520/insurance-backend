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
               <input class="layui-input"  autocomplete="off" placeholder="开始日" name="date_s" id="date_s" value="@if(isset($_REQUEST['date_s'])){{$_REQUEST['date_s']}}@endif">
                <input class="layui-input"  autocomplete="off" placeholder="截止日" name="date_e" id="date_e" value="@if(isset($_REQUEST['date_e'])){{$_REQUEST['date_e']}}@endif">
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

                <div class="layui-input-inline">

                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>

                </div>





            </form>
        </div>
        <xblock>
            <!--<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>-->
            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                添加</button>

        </xblock>


            <table class="layui-table x-admin layui-form">

                <thead>
                <tr>
                    <th>
                        <!--<div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>-->
                        会员ID
                    </th>
                    <th>推荐号</th>
                    <th>账号</th>
                    <!--<th>用户级别</th>-->
                    <th>姓名</th>
                    <th>推广下线数</th>
                    <th>下线累计投资金额</th>
                    <th>下线累计充值金额</th>
                    <th>下线累计提现金额</th>
                    <th>推荐人</th>

                    <!--<th>操作</th>-->
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

                    <!-- <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div> -->
                </td>
                <td class="title_<% item.id %>"><% item.invicode?item.invicode:'' %></td>
                <td ><% item.username %></td>
                <!-- <td><% item.levelName %></td> -->
                <td><% item.realname?item.realname:'' %></td>
                <td><% item.xxtuiguangrens %></td>
                <td><% item.xxbuys?item.xxbuys.toFixed(2):'0' %></td>
                <td><% item.xxrecharges?item.xxrecharges.toFixed(2):'0' %></td>
                <td><% item.xxwithdrawals?item.xxwithdrawals.toFixed(2):'0' %></td>

                <td><% item.inviter?item.inviter:'' %><% item.inviterName?' | '+item.inviterName:'' %></td>



                {{--<td class="td-manage">
                    <a title="查看密码" onclick="x_admin_show('会员购买项目','<% item.url %>')" href="javascript:void(0)">
                        <i class="layui-icon" style="color:red;">&#xe615;</i>
                    </a>
                </td>--}}
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


    layui.use(['form','laydate'], function(){
        var form = layui.form;
        form.on('select(s_categoryid)', function(data){
            lists(1,{s_categoryid:data.value});
        });

        form.on('select(s_mtype)', function(data){
            lists(1,{s_mtype:data.value});
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




    </script>
@endsection

