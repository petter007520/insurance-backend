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
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>
            <!--<button class="layui-btn" onclick="store()">-->
            <!--    <i class="layui-icon download">&#xe654;</i>-->
            <!--    添加</button>-->

        </xblock>


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
                <col width="200">
                <col width="100">
            </colgroup>
            <thead>


            <tr>
                <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                <th>联系名称</th>
                <th>联系方式</th>
                <th>身份证号</th>
                <th>证书编号</th>
                <th>收货地址</th>
                <th>说明</th>
                <th>状态</th>
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
            <td><% item.mobile %></td>
            <td><% item.idcard %></td>
            <td><% item.gqorder %></td>
            <td><% item.address %></td>
            <td><% item.explain %></td>
            <td>
                <%# if(item.status==0){ %>
                <input type="checkbox"   lay-skin="switch" lay-filter="switchTest-setstatus" lay-text="已确认|未确认" id="<% item.id %>">
                <%# }else{ %>
                <input type="checkbox"  checked  lay-skin="switch" lay-filter="switchTest-setstatus" lay-text="已确认|未确认" id="<% item.id %>">
                <%# } %>
            </td>
            <td><% item.created_at %></td>

            <td class="td-manage">

                <!-- <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe642;</i>
                </a>



                <a title="删除" onclick="del(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i> -->
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

            form.on('switch(switchTest-setstatus)', function(data){
            var id=data.elem.id;
            var setstatus= data.elem.checked?1:0;
            var load;
            $.ajax({
                url: "{{ route($RouteController.'.setstatus') }}",
                type:"post",     //请求类型
                data:{
                    id:id,status:setstatus,
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

