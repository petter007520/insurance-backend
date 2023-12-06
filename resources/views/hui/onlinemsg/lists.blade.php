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


        </xblock>


            <table class="layui-table x-admin layui-form">
                <colgroup>
                    <col width="150">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="100">
                    <col width="200">
                    <col width="200">

                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>
                        <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        ID
                    </th>
                    <th>会员</th>
                    <th>联系方式</th>
                    <th>凭证</th>
                    <th>内容</th>
                    <th>状态</th>
                    <th>日期</th>
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
                <td class="title_<% item.id %>"><% item.name?item.name:'' %></td>
                <td><% item.mobile?item.mobile:'' %></td>
                <!-- <td><a href=<% item.img?item.img:'' %> ><img style="max-width: 100px;" src=<% item.img?item.img:'' %>  width="20"></a></td>
                <td  id="photo-front-<% item.id %>"><img style="max-width: 100px;" src=<% item.img?item.img:'' %>  width="20"  onclick="openFrontPhotos(<% item.id %>)"></td>-->
                
                <td width="180" id="photo-front-<% item.id %>"> <%# if(item.imgs){ %>
                <%#  layui.each(item.imgs,function(index,v){ %>
                    <img style="max-width: 100px;" src=<% v  %>  width="20" onclick="openFrontPhotos(<% item.id %>)">
                    <%# });%>
                <%# }%></td>
                <td><% item.msg?item.msg:'' %></td>
                <td>
                    <%# if(item.status==0){ %>
                    <input type="checkbox"   lay-skin="switch" lay-filter="switchTest-settop" lay-text="已读|未读" id="<% item.id %>">
                    <%# }else{ %>
                    <input type="checkbox"    checked  lay-skin="switch" lay-filter="switchTest-settop" lay-text="已读|未读" id="<% item.id %>">
                    <%# } %>
                </td>
                <td><% item.created_at %></td>

                <td class="td-manage">


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





    layui.use('form', function(){
        var form = layui.form;
/*        form.on('radio(authid)', function(data){
            lists(1,{authid:data.value});
        });*/



        form.on('switch(switchTest-settop)', function(data){
            var id=data.elem.id;
            var top_status= data.elem.checked?1:0;
            var load;
            $.ajax({
                url: "{{ route($RouteController.'.settop') }}",
                type:"post",     //请求类型
                data:{
                    id:id,status:top_status,

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
    
    function openFrontPhotos(id){
        layer.photos({
            photos: '#photo-front-'+id
            ,shift: 0
        });
    }


    </script>
@endsection

