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

                    <select name="s_adverid" lay-filter="adver">

                        <option value="">广告位</option>
                        @if($adver)
                        @foreach($adver as $adv)
                        <option value="{{$adv->id}}" @if(isset($_REQUEST['s_adverid']) && $_REQUEST['s_adverid']==$adv->id) selected="selected" @endif>{{$adv->name}}</option>
                        @endforeach
                        @endif

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
                <col width="200">
                <col width="200">
                <col width="200">
                <col width="200">
                <col>
            </colgroup>
            <thead>


            <tr>
                <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                <th>广告名称</th>
                <th>广告位</th>
                <th>封面图片</th>
                <th>广告排序</th>
                <th>广告添加时间</th>
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
            <td><% item.category_name %></td>
            <!-- <td><% item.thumb_url?'<img src="'+item.thumb_url+'" width="20" onmouseover="this.width=200" onmouseout="this.width=20"/>':'' %></td>-->
            <td  id="photo-front-<% item.id %>"><img style="max-width: 100px;" src=<% item.thumb_url?item.thumb_url:'' %>  width="20"  onclick="openFrontPhotos(<% item.id %>)"></td>

            <td><% item.sort %></td>
            <td><% item.created_at %></td>

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


        layui.use('form', function(){
            var form = layui.form;


            form.on('select(adver)', function(data){
                //console.log(data.elem); //得到select原始DOM对象
                //console.log(data.value); //得到被选中的值
                // console.log(data.othis); //得到美化后的DOM对象
                var obj={
                    s_key:$("[name='s_key']").val(),
                    s_adverid:data.value
                };
                lists(1,obj);
            });

        });

     function openFrontPhotos(id){
        layer.photos({
            photos: '#photo-front-'+id
            ,shift: 0
        });
    }

    </script>
@endsection

