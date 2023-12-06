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







        <form class="layui-form layui-form-pane1" action="{{ route($RouteController.".lists") }}" method="get">

<!--

            <div class="layui-form-item" pane>



            <div class="layui-input-inline">

                <input type="text" name="s_key"  placeholder="请输入名称" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">

            </div>



            <div class="layui-input-inline">

                <select name="s_model" lay-filter="s_model">
                    <option value="">选择模型</option>
                    @if($modellist)
                        @foreach($modellist as $v)
                            <option value="{{$v['key']}}" @if(isset($_REQUEST['s_model']) && $_REQUEST['s_model']==$v['key'])selected="selected" @endif>{{$v['name']}}</option>
                        @endforeach
                    @endif
                </select>

            </div>
            <div class="layui-input-inline">

                <button class="layui-btn" lay-submit lay-filter="go">查询</button>

            </div>



        </div>-->



    </form>

        <xblock>

          <!--  <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                添加</button>
-->
        </xblock>

             <table class="layui-table x-admin layui-form">

            <colgroup>

                <col width="50">

                <col width="300">

                <col width="110">

                <col width="100">

                <col width="100">
                <col width="100">
                <col width="100">


                <col width="100">

                <col width="100">

            </colgroup>

            <thead>

            <tr>

                <th>ID</th>

                <th>名称</th>

                <th>日收益率</th>


                <th>周期</th>

                <th>所需人数</th>

                <th>所需积分</th>
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

    <script id="demo" type="text/html">


        <%#  layui.each(d.data, function(index, item){ %>

        <tr>
            <td>
                <% item.id %>
            </td>
            <td class="title_<% item.id %>"><% item.name %></td>


            <td><% item.wyb_rate %></td>

            <td><% item.wyb_date %></td>
            <td><% item.wyb_num %></td>
            <td><% item.wyb_jf %></td>

            <td class="td-manage">

                <a title="编辑"  onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe642;</i>
                </a>

                <!--<a title="删除" onclick="del(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i>
                </a>-->
            </td>
        </tr>






        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>

    </script>




    <script>

        function pagelist_tree(list,i){


            layui.use(['laytpl','form'], function(){

                var form = layui.form;

                layui.each(list, function(index, item){
                    var _html='';
                    var _h='';

                    for(var j=1;j<i;j++){
                        if(j==1){
                            _h+='|-';
                        }else{
                            _h+='-';
                        }

                    }
                    _html+=' <tr>';
                    _html+='  <td>';
                    _html+='  '+item.id +'';
                    _html+='  </td>';
                    _html+='  <td class="title_'+item.id +'">'+_h+ item.name +'</td>';





                    _html+='     <td>'+ item.wyb_rate +'%</td>';

                    _html+='      <td>'+ item.wyb_date +'</td>';
                    _html+='      <td>'+ item.wyb_num +'</td>';
                    _html+='      <td>'+ item.wyb_jf +'</td>';





                    _html+=' <td>';

                    if(item.is_kq==1){
                        _html+='  <input type="checkbox"  checked lay-skin="switch" lay-filter="switchTest-ismenus" lay-text="无忧保|隐藏" id="'+item.id +'">';
                    }else{
                        _html += '  <input type="checkbox"      lay-skin="switch" lay-filter="switchTest-ismenus" lay-text="无忧保|隐藏" id="'+item.id +'">';

                    }

                    _html+=' </td>';



                    _html+='      <td class="td-manage">';

                    _html+='      <a title="编辑"  onclick="update('+ item.id +',1)" href="javascript:;">';
                    _html+='     <i class="layui-icon">&#xe642;</i>';
                    _html+='  </a>';

                    _html+='  </td>';
                    _html+='  </tr>';
                    $("#view").append(_html);
                    form.render(); //更新全部
                    if(item.list.length>0){
                        i++;
                        pagelist_tree(item.list,i);
                    }



                });






            });



        }

       layui.use(['form'], function(){

        var form = layui.form;

            form.on('select(s_model)', function(data){
                    var op={
                        s_model :data.value,
                        s_key :$("[name='s_key']").val(),
                    }

                    lists(1,op);

            });


           form.on('switch(switchTest-atindex)', function(data){
               var id=data.elem.id;
               var atindex= data.elem.checked?1:0;
               console.log(atindex)
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
           form.on('switch(switchTest-atfoot)', function(data){
               var id=data.elem.id;
               var atindex= data.elem.checked?1:0;
               var load;
               $.ajax({
                   url: "{{ route($RouteController.'.iskq') }}",
                   type:"post",     //请求类型
                   data:{
                       id:id,atfoot:atindex,

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



