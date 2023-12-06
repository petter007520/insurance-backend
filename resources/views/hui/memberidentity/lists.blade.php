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

                    <input type="text" name="s_key"  placeholder="请输入会员账号" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">

                </div>
                
                <div class="layui-input-inline">

                    <select name="s_status" lay-filter="s_status">

                        <option value="" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']=='') selected="selected" @endif>认证状态</option>
                        <option value="0" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status'] !='' && $_REQUEST['s_status']==0) selected="selected" @endif>未认证</option>
                        <option value="1" @if(isset($_REQUEST['s_status']) && $_REQUEST['s_status']==1) selected="selected" @endif>已认证</option>
                    </select>

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
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">
                    <col width="200">

                    <col width="200">
                </colgroup>
                <thead>
                <tr>
                    <th>
                        <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                        ID
                    </th>
                    <th>会员ID</th>
                    <th>会员账号</th>
                    <th>真实姓名</th>
                    <th>身份证号</th>
                    <th>正面</th>
                    <th>反面</th>
                    <th>状态</th>
                    <th>提交日期</th>
                    <th>认证日期</th>
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
                <td><% item.userid?item.userid:'' %></td>
                <td><% item.username?item.username:'' %></td>
                
                <td><% item.realname?item.realname:'' %></td>
                <td><% item.idnumber?item.idnumber:'' %></td>
                <!--<td><img style="max-width: 200px;" src=<% item.img?item.img:'' %>  width="20" onmouseover="this.width=200" onmouseout="this.width=20"></td>
                <td><img style="max-width: 200px;" src=<% item.img?item.img:'' %>  width="20" onmouseover="this.width=200" onmouseout="this.width=20"></td> -->
                <td  id="photo-front-<% item.id %>"><img style="max-width: 100px;" src=<% item.facade_img?item.facade_img:'' %>  width="20"  onclick="openFrontPhotos(<% item.id %>)"></td>
                <td  id="photo-back-<% item.id %>"><img style="max-width: 100px;" src=<% item.revolt_img?item.revolt_img:'' %>  width="20"  onclick="openBackPhotos(<% item.id %>)"></td>
                <td>
                    <%# if(item.status==0){ %>
                    <input type="checkbox"   lay-skin="switch" lay-filter="switchTest-settop" lay-text="已认证|未认证" id="<% item.id %>">
                    <%# }else{ %>
                    <input type="checkbox"    checked  lay-skin="switch" lay-filter="switchTest-settop" lay-text="已认证|未认证" id="<% item.id %>">
                    <%# } %>
                </td>
                
                <td><% item.created_at %></td>
                <td><% item.updated_at %></td>
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

     function openFrontPhotos(id){
        layer.photos({
            photos: '#photo-front-'+id
            ,shift: 0
        });
    }
    
     function openBackPhotos(id){
        layer.photos({
            photos: '#photo-back-'+id
            ,shift: 0
        });
    }

    </script>
@endsection

