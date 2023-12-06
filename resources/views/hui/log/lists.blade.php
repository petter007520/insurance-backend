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

                <select name="s_categoryid" lay-filter="category_id">

                    <option value="">分类</option>
                    <option value="admin">后台操作</option>
                    <option value="user">会员操作</option>


                </select>

            </div>


            <div class="layui-input-inline">

                <button class="layui-btn" lay-submit lay-filter="go">查询</button>

            </div>

        </form>
    </div>
    <!--<xblock>-->
    <!--    <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>-->


    <!--</xblock>-->


        <table class="layui-table x-admin layui-form">

            <thead>
            <tr class="text-c">
                <th class="layui-form text-center" ><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                <th>操作名称</th>
                <th>用户名</th>
                <th>IP</th>
                <th>URL</th>
                <th>操作时间</th>
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

    <script>
    function OpenBox(text) {

        var str=JSON.stringify(text);

        layer.alert(str);

    }
    </script>
    <script id="demo" type="text/html">

        <%#  $('.r strong').text(d.total) %>
        <%#  layui.each(d.data, function(index, item){ %>

        <tr class="text-c">
            <td>


                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td class="title_<% item.id %>"><% item.title %></td>
            <td><% item.username %></td>
            <td><% item.ip %></td>
            <td class="text-l"><% item.url %> <a style="float:right;" class="text-c" onclick='OpenBox(<% item.datas %>)'>查看数据</a></td>
            <td><% item.created_at %></td>

        </tr>



        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>

    </script>
    <script>

        layui.use('form', function(){
            var form = layui.form;


            form.on('select(category_id)', function(data){
                //console.log(data.elem); //得到select原始DOM对象
                //console.log(data.value); //得到被选中的值
                // console.log(data.othis); //得到美化后的DOM对象
                var obj={
                    s_key:$("[name='s_key']").val(),
                    s_categoryid:data.value,
                    //s_status:$("[name='s_status']").val(),
                };
                lists(1,obj);
            });
            });
    </script>


@endsection

