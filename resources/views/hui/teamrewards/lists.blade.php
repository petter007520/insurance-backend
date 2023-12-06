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
                   
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                    <th>团队认购人数</th>
                    <th>认购总额</th>
                    <th>奖励金额</th>
                    <th>奖励产品名</th>
                    <th>奖励产品数量</th>
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
                    <span style=""></span>
                </td>
                <td><% item.team_num %></td>
                <td><% item.team_amount %></td>
                <td><% item.reward_amount %></td>
                <td><% item.reward_equ %></td>
                

                <td><% item.reward_equ_num %></td>

                <td class="td-manage">

                    <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon">&#xe642;</i>
                    </a>

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


            form.on('select(adver)', function(data){
                //console.log(data.elem); //得到select原始DOM对象
                //console.log(data.value); //得到被选中的值
                // console.log(data.othis); //得到美化后的DOM对象
                var obj={
                    s_key:$("[name='s_key']").val(),
                };
                lists(1,obj);
            });
            
            
            



    </script>
@endsection

