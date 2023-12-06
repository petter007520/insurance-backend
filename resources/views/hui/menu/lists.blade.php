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
                <col width="350">
                <col width="120">
                <col width="220">
                <col width="220">
                <col width="120">
                <col width="120">               
                <col width="100">
                <col width="100">
                <col width="100">
                <col width="10">

            </colgroup>
            <thead>
            <tr>
                <th>ID</th>
                <th>名称</th>
                <th>模型名</th>
                <th>控制器</th>
                <th>操作名</th>
                <th>图标样式</th>

                <th>排序</th>
                <th>启用菜单</th>
                <th>左部菜单</th>
                <th>顶部菜单</th>
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
    <script>
        {{--@parent--}}

        var form;


        layui.use(['form'], function() {

            form = layui.form;

            form.on('switch(switchTest)', function(data){
                var id=data.elem.id;
                var disabled= data.elem.checked?0:1;

                $.ajax({
                    url: "{{ route($RouteController.'.updatedisabled') }}",
                    type:"post",     //请求类型
                    data:{  id:id,disabled:disabled,keys:'disabled',

                                _token:"{{ csrf_token() }}"
            },  //请求的数据
                dataType:"json",  //数据类型
                        beforeSend: function () {
                    // 禁用按钮防止重复提交，发送前响应
                    var index = layer.load();

                },
                success: function(data){
                    //laravel返回的数据是不经过这里的
                    if(data.status==0){
                        layer.msg(data.msg,{},function(){

                        });

                    }else{
                        layer.msg(data.msg,{icon: 5},function(){

                        });
                    }
                },
                complete: function () {//完成响应
                    layer.closeAll();
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



            form.on('switch(switchTest-top)', function(data){
                var id=data.elem.id;
                var ismenutop= data.elem.checked?1:0;

                $.ajax({
                    url: "{{ route($RouteController.'.updatedisabled') }}",
                    type:"post",     //请求类型
                    data:{  id:id,ismenutop:ismenutop,keys:'top',

                                _token:"{{ csrf_token() }}"
            },  //请求的数据
                dataType:"json",  //数据类型
                        beforeSend: function () {
                    // 禁用按钮防止重复提交，发送前响应
                    var index = layer.load();

                },
                success: function(data){
                    //laravel返回的数据是不经过这里的
                    if(data.status==0){
                        layer.msg(data.msg,{},function(){

                        });

                    }else{
                        layer.msg(data.msg,{icon: 5},function(){

                        });
                    }
                },
                complete: function () {//完成响应
                    layer.closeAll();
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

        form.on('switch(switchTest-left)', function(data){
                var id=data.elem.id;
                var ismenuleft= data.elem.checked?1:0;

                $.ajax({
                    url: "{{ route($RouteController.'.updatedisabled') }}",
                    type:"post",     //请求类型
                    data:{
                        id:id,ismenuleft:ismenuleft,keys:'left',

                                _token:"{{ csrf_token() }}"
            },  //请求的数据
                dataType:"json",  //数据类型
                        beforeSend: function () {
                    // 禁用按钮防止重复提交，发送前响应
                    var index = layer.load();

                },
                success: function(data){
                    //laravel返回的数据是不经过这里的
                    if(data.status==0){
                        layer.msg(data.msg,{},function(){

                        });

                    }else{
                        layer.msg(data.msg,{icon: 5},function(){

                        });
                    }
                },
                complete: function () {//完成响应
                    layer.closeAll();
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

        $(".updates").blur(function(){

            var id=$(this).attr("data-id");
            var keys=$(this).attr("name");
            var value=$(this).val();
            updates(id,keys,value);
        });

        function updates(id,keys,value){

            $.ajax({
                url: "{{ route($RouteController.'.updates') }}",
                type:"post",     //请求类型
                data:{

                    id:id,keys:keys,value:value,
                    _token:"{{ csrf_token() }}"
                },  //请求的数据
                dataType:"json",  //数据类型
                beforeSend: function () {
                    // 禁用按钮防止重复提交，发送前响应
                    var index = layer.load();

                },
                success: function(data){
                    //laravel返回的数据是不经过这里的
                    if(data.status==0){
                        layer.closeAll();
                        layer.msg(data.msg,{},function(){

                        });

                    }else{
                        layer.closeAll();
                        layer.msg(data.msg,{icon: 5},function(){

                        });
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


        function copy(id,page){
            layer.prompt(function(value, index, elem){
                // alert(value); //得到value
                layer.close(index);

                $.ajax({
                    url: "{{ route($RouteController.'.copy') }}",
                    type:"post",     //请求类型
                    data:{

                        'id':id,'value':value,
                        _token:"{{ csrf_token() }}"
                    },  //请求的数据
                    dataType:"json",  //数据类型
                    beforeSend: function () {
                        // 禁用按钮防止重复提交，发送前响应
                        var index = layer.load();

                    },
                    success: function(data){
                        //laravel返回的数据是不经过这里的
                        if(data.status==0){
                            layer.msg(data.msg,{},function(){
                                list(page,{});
                            });

                        }else{
                            layer.msg(data.msg,{icon: 5},function(){

                            });
                        }
                    },
                    complete: function () {//完成响应
                        layer.closeAll();
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






    </script>


    <script id="demo" type="text/html">


        <%#  layui.each(d.data, function(index, item){ %>

        <tr style="background-color:#cccccc;">
            <td>
                <% item.id %>
            </td>
            <td class="title_<% item.id %>"><input type="text" name="name" required  class="layui-input updates" value="<% item.name %>"  onblur="updates('<% item.id %>','name',this.value)"></td>
            <td><% item.model_name %></td>
            <td><input type="text" name="contr_name" required  class="layui-input updates" value="<% item.contr_name %>" onblur="updates('<% item.id %>','contr_name',this.value)"></td>
            <td><input type="text" name="action_name" required  class="layui-input updates" value="<% item.action_name %>" onblur="updates('<% item.id %>','action_name',this.value)"></td>
            <td><input type="text" name="icon" required  class="layui-input updates layui-icon" value="<% item.icon?item.icon:'' %>" onblur="updates('<% item.id %>','icon',this.value)"></td>
            <td><input type="text" name="sort" required  class="layui-input updates " value="<% item.sort %>" onblur="updates('<% item.id %>','sort',this.value)"></td>

               <td>

                <%# if(item.disabled==1){ %>
                <input type="checkbox"   lay-skin="switch" lay-filter="switchTest" lay-text="启用|禁用" id="<% item.id %>">
                <%# }else{ %>
                <input type="checkbox"    checked  lay-skin="switch" lay-filter="switchTest" lay-text="启用|禁用" id="<% item.id %>">

                <%# } %>

            </td>


            <td>

                <%# if(item.ismenuleft==0){ %>
                <input type="checkbox"   lay-skin="switch" lay-filter="switchTest-left" lay-text="启用|禁用" id="<% item.id %>">
                <%# }else{ %>
                <input type="checkbox"    checked  lay-skin="switch" lay-filter="switchTest-left" lay-text="启用|禁用" id="<% item.id %>">

                <%# } %>

            </td>

            <td>

                <%# if(item.ismenutop==0){ %>
                <input type="checkbox"   lay-skin="switch" lay-filter="switchTest-top" lay-text="启用|禁用" id="<% item.id %>">
                <%# }else{ %>
                <input type="checkbox"    checked  lay-skin="switch" lay-filter="switchTest-top" lay-text="启用|禁用" id="<% item.id %>">

                <%# } %>

            </td>



            <td class="td-manage">

                <a title="复制"  onclick="copy(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe656;</i>
                </a>

                <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe642;</i>
                </a>

                <a title="删除" onclick="del(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i>
                </a>
            </td>
        </tr>



        <%#  layui.each(item.menus, function(index, item2){ %>

        <tr>
            <td>
                <% item2.id %>
            </td>
            <td class="title_<% item2.id %>"><input type="text" name="name" required  class="layui-input updates" value="<% item2.name %>"  onblur="updates('<% item2.id %>','name',this.value)"></td>
            <td><% item2.model_name %></td>
            <td><input type="text" name="contr_name" required  class="layui-input updates" value="<% item2.contr_name %>" onblur="updates('<% item2.id %>','contr_name',this.value)"></td>
            <td><input type="text" name="action_name" required  class="layui-input updates" value="<% item2.action_name %>" onblur="updates('<% item2.id %>','action_name',this.value)"></td>
            <td><input type="text" name="icon" required  class="layui-input updates layui-icon" value="<% item2.icon?item2.icon:'' %>" onblur="updates('<% item2.id %>','icon',this.value)"></td>
            <td><input type="text" name="sort" required  class="layui-input updates" value="<% item2.sort %>" onblur="updates('<% item2.id %>','sort',this.value)"></td>

            <td>

                <%# if(item2.disabled==1){ %>
                <input type="checkbox"   lay-skin="switch" lay-filter="switchTest" lay-text="启用|禁用" id="<% item2.id %>">
                <%# }else{ %>
                <input type="checkbox"    checked  lay-skin="switch" lay-filter="switchTest" lay-text="启用|禁用" id="<% item2.id %>">

                <%# } %>

            </td>


            <td>

                <%# if(item2.ismenuleft==0){ %>
                <input type="checkbox"   lay-skin="switch" lay-filter="switchTest-left" lay-text="启用|禁用" id="<% item2.id %>">
                <%# }else{ %>
                <input type="checkbox"    checked  lay-skin="switch" lay-filter="switchTest-left" lay-text="启用|禁用" id="<% item2.id %>">

                <%# } %>

            </td>

            <td>

                <%# if(item2.ismenutop==0){ %>
                <input type="checkbox"   lay-skin="switch" lay-filter="switchTest-top" lay-text="启用|禁用" id="<% item2.id %>">
                <%# }else{ %>
                <input type="checkbox"    checked  lay-skin="switch" lay-filter="switchTest-top" lay-text="启用|禁用" id="<% item2.id %>">

                <%# } %>

            </td>



            <td class="td-manage">

                <a title="编辑"  onclick="update(<% item2.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe642;</i>
                </a>

                <a title="删除" onclick="del(<% item2.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i>
                </a>
            </td>
        </tr>



        <%#  }); %>


        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>






    </script>

@endsection


