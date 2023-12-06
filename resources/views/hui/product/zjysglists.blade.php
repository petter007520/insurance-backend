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

<!--
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

                    <select name="s_status" lay-filter="status">

                            <option value="">项目状态</option>
                            <option value="0" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='0') selected="selected" @endif>投资中</option>
                            <option value="1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='1') selected="selected" @endif>已投满</option>
							<option value="2" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='2') selected="selected" @endif>未发布</option>


                    </select>

                </div>
                <div class="layui-input-inline">

                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>

                </div>-->

            </form>
        </div>
        <xblock>
           <!-- <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>
            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                添加</button>-->

        </xblock>


            <table class="layui-table x-admin layui-form">
                <colgroup>
                    <col width="150">
                    <col width="200">
                    <col width="200">
                    <col width="120">
                    <col width="200">
                    <!--<col width="200">-->
                    <col width="200">
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
                    <th>分类</th>
                    <th>项目标题</th>
                    <th>产品图片</th>
                   <!-- <th>项目规模</th>-->
                    <!--<th>投资进度</th>-->
                    <!--<th>发行价</th>-->
                    <th>最新价</th>
                 <!--   <th>起购数量</th>
                    <th>日收益率 %</th>-->
                    <!--<th>还本日收益率 %</th>-->
                  <!--  <th>项目进度</th>
                    <th>项目期限</th>-->

                    <th>投资状态</th>
              <!--      <th>是否赠送积分</th>-->
                    <!--<th>首页展示</th>-->
                    <th>日期</th>
                    <!--<th>排序</th>-->
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
                <td width="180"><% item.category_name %></td>
                <td class="title_<% item.id %>"><% item.title %></td>
                <td><img src=<% item.pic %> width="20"></td>
             
               
                <td><% item.qtje %></td>
          

             

                <td><%# if(item.tzzt==0){ %>
                    投资中
                <%# }else if(item.tzzt==1){ %>
                    已投满
                <%# }else if(item.tzzt==2){ %>
                    未发布
                <%# }%>
                </td>
              

               
                <td width="240"><% item.created_at %></td>
           

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
/*        form.on('radio(authid)', function(data){
            lists(1,{authid:data.value});
        });*/

        // form.on('select(category_id)', function(data){
        //     //console.log(data.elem); //得到select原始DOM对象
        //     //console.log(data.value); //得到被选中的值
        //     // console.log(data.othis); //得到美化后的DOM对象
        //     var obj={
        //         s_key:$("[name='s_key']").val(),
        //         s_categoryid:data.value,
        //         s_status:$("[name='s_status']").val(),
        //     };
        //     lists(1,obj);
        // });

        // form.on('select(status)', function(data){
        //     //console.log(data.elem); //得到select原始DOM对象
        //     //console.log(data.value); //得到被选中的值
        //     // console.log(data.othis); //得到美化后的DOM对象
        //     var obj={
        //         s_key:$("[name='s_key']").val(),
        //         s_status:data.value,
        //         s_categoryid:$("[name='s_category_id']").val(),
        //     };
        //     lists(1,obj);
        // });


        form.on('switch(switchTest-settop)', function(data){
            var id=data.elem.id;
            var top_status= data.elem.checked?1:0;
            var load;
            $.ajax({
                url: "{{ route($RouteController.'.settop') }}",
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
            url: "{{ route($RouteController.".update_currline") }}",
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

