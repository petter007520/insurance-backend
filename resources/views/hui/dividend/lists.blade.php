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

        

        <table class="layui-table x-admin layui-form">

            <thead>
            <tr>
                <th>
                    <!--<div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>-->
                    ID
                </th>
                <th>收益周期</th>
                <th>周期天数</th>
                <th>比例%</th>
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
                    <% item.id %>

                    <!-- <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div> -->
                </td>
                <td><% item.type_name%></td>
                <td><% item.dividend_day%></td>
                <td><% item.dividend_ratio%></td>
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

