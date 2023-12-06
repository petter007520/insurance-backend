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



        <xblock>

            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                添加</button>

        </xblock>

             <table class="layui-table x-admin layui-form">

            <colgroup>

                <col width="50">
                <col width="100">
                <col width="100">
                <col width="110">
                <!--<col width="110">-->
                <!--<col width="110">-->
                <col width="110">
                <col width="80">
                <col width="80">

            </colgroup>

            <thead>

            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>返还比率</th>
                <th>保证金比率</th>
                <th>产品</th>
                <th>是否开启</th>
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
    {{--//'pay_code','pay_name','pay_bank','pay_pic','pay_desc','enabled'--}}
    <script id="demo" type="text/html">


        <%#  layui.each(d.data, function(index, item){ %>

        <tr>
            <td>
                <% item.id %>
            </td>
            <td><% item.name %></td>
            <td><% item.rate %>%</td>
              <td><% item.bzj_rate %>%</td>
            <td><% item.title %></td>
            <td>
                <%#  if(item.is_kq == 1){ %>
                <input type="checkbox"  checked lay-skin="switch" lay-filter="switchTest-ismenus" lay-text="开启|关闭" id="<% item.id %>"></td>
                <%#  } %>
                <%#   if(item.is_kq == 0){ %>
                <input type="checkbox"      lay-skin="switch" lay-filter="switchTest-ismenus" lay-text="开启|关闭" id="<% item.id %>'">
                <%#  } %>

            <!--<td><% item.pname %></td>-->
            <td class="td-manage">

                <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe642;</i>
                </a>

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



       layui.use(['form'], function(){

        var form = layui.form;
           form.on('switch(switchTest-ismenus)', function(data){
               var id=data.elem.id;
               var atindex= data.elem.checked?1:0;
               var load;
               $.ajax({
                   url: "{{ route($RouteController.'.iskq') }}",
                   type:"post",     //请求类型
                   data:{
                       id:id,is_kq:atindex,

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



    </script>

@endsection



