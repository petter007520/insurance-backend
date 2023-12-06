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

                    <input type="text" name="s_key"  placeholder="请输入名称" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">

                </div>

                <div class="layui-input-inline">

                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>

                </div>

            </form>
        </div>
        <xblock>
            <!--<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>-->
            <!--<button class="layui-btn" onclick="store()">-->
            <!--    <i class="layui-icon download">&#xe654;</i>-->
            <!--    添加</button>-->

        </xblock>


        <table class="layui-table x-admin layui-form">
            <colgroup>
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="500">
                <col width="200">
            </colgroup>
            <thead>


            <tr>
                <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                <th>签到奖励名称</th>
                <th>签到奖励数量</th>
                <th>奖励类型</th>
                <th>连签天数</th>
                <th>详情</th>
                <th>创建时间</th>
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
                <span style=""><% item.id %></span>
            </td>
            <td class="title_<% item.id %>"><% item.name %></td>
            <td><% item.num %></video></td>
            <td><% item.type==1?'积分':'现金' %></td>
            <td><% item.days %></td>
            <td><% item.detail %></td>

            <td><% item.created_at %></td>

            <td class="td-manage">

                <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe642;</i>
                </a>



                <!-- <a title="删除" onclick="del(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i>
                </a> -->
            </td>
        </tr>



        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>

    </script>

@endsection

