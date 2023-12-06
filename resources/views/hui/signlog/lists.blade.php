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
        


            <table class="layui-table x-admin layui-form">
                <colgroup>
                    <col width="150">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">

                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                    <th>用户ID</th>
                    <th>用户姓名</th>
                    <th>签到年份</th>
                    <th>签到月份</th>
                    <th>总签到天数</th>
                    <th>上一次签到</th>
                    <th>首次签到时间</th>
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


                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div><% item. id%>
                    <span style=""></span>
                </td>
                <td><% item. user_id%></td>
                <td><% item.username%></td>
                <td><% item.sign_year%></td>
                <td><% item.sign_month %></td>
                <td class="text-l"><% item.qd_count %> <a style="float:right;" class="text-c" onclick='OpenBox([<% item.sign_day %>])'>查看数据</a></td>
                <td><% item.lastqiandao%></td>

                <td><% item.created_at %></td>

            </tr>



            <%#  }); %>
            <%#  if(d.length === 0){ %>
            无数据
            <%#  } %>

    </script>
    
    
 <script>

    function OpenBox(text) {

        var str=JSON.stringify(text);

        layer.alert(str);

    }





        layui.use('form', function(){
            var form = layui.form;


            form.on('select(adver)', function(data){
                //console.log(data.elem); //得到select原始DOM对象
                //console.log(data.value); //得到被选中的值
                // console.log(data.othis); //得到美化后的DOM对象
                var obj={
                    s_key:$("[name='s_key']").val(),
                    // s_categoryid:data.value,
                    //s_status:$("[name='s_status']").val(),
                };
                lists(1,obj);
            });
            });
  




    </script>
   
@endsection

