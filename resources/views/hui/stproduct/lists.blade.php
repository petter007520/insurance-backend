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

                             <option value="">项目分类栏目</option>
                         {!! $tree_option !!}

                     </select>

                 </div>
                <div class="layui-input-inline">
                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>
                </div>
            </form>
        </div>
        <xblock>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>
            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                添加</button>

        </xblock>


            <table class="layui-table x-admin layui-form">
                <colgroup>
                    <col width="150">
                    <col width="200">
                    <col width="120">
                    <col width="200">
                    <col width="150">
                    <col width="500">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                </colgroup>
                <thead>
                <tr>
                    <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                    <th>商品名称</th>
                    <th>产品图片</th>
                    <th>零售价格</th>
                    <th>起购数量</th>
                    <th>商品详情</th>
                    <!--<th>一级佣金</th>
                    <th>二级佣金</th>-->
                    <th>限购数量</th>
                    <th>规格</th>
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
    {{--编号	分类	项目标题	保理机构	项目规模	投资进度	起投金额	交易收益	项目期限	投资状态	是否首页展示	添加时间	排序	操作--}}
    <script id="demo" type="text/html">


            <%#  layui.each(d.data, function(index, item){ %>

            <tr>
                <td>
                    <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'><i class="layui-icon">&#xe605;</i></div>
                    <span style=""><% item.id %></span>
                </td>
                <td><% item.name %></td>
                <td><img src=<% item.picurl %> width="20"></td>
                <td><% item.fee %></td>
                <td><% item.qtsl %></td>
                <td><% item.content %></td>
                <!--<td><% item.firstlevel %></td>
                <td><% item.secondlevel %></td>-->
                <td><% item.xg_num %></td>
                <td><% item.guige %></td>

                <td width="240"><% item.created_at %></td>
                <td class="td-manage">
                    <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon">&#xe642;</i>
                    </a>
                    <a title="删除" onclick="del(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon">&#xe640;</i>
                    </a>
                    <%# if(item.category_id==11){ %>
                        <a title="生成K数据" onclick="update_currline(<% item.id %>)" href="javascript:;">
                            <i class="layui-icon">&#xe62c;</i>
                        </a>
                    <%# } %>
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


        form.on('switch(switchTest-settop)', function(data){
            var id=data.elem.id;
            var top_status= data.elem.checked?1:0;
            var load;
            $.ajax({
                url: "",
                type:"post",     //请求类型
                data:{
                    id:id,top_status:top_status,

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

    function update_currline(id){
        var index;
        $.ajax({
            url: "",
            type:"post",     //请求类型
            data:{
                pid:id,
                _token:"{{ csrf_token() }}"
            },  //请求的数据
            dataType:"json",  //数据类型

            success: function(data){
                //laravel返回的数据是不经过这里的
                if(data.status==0){
                   layer.msg(data.msg,{icon: 1,time:1000});
                }else{
                    layer.msg(data.msg,{icon: 5,time:1000});
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


    </script>
@endsection

