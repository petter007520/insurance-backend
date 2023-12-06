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

{{--
                <div class="layui-input-inline">

                    <input type="text" name="s_key"  placeholder="请输入名称" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">

                </div>--}}


                <div class="layui-input-inline">


                    <select name="s_categoryid" lay-filter="s_categoryid">
                        <option value="">消息类型</option>
                        @if(\App\Smstmp::GetTypeNameList())

                            @foreach(\App\Smstmp::GetTypeNameList() as $tkey=> $itme)
                                <option value="{{$tkey}}" @if(isset($_REQUEST['s_categoryid']) && $_REQUEST['s_categoryid']==$itme) selected="selected" @endif>{{$itme}}</option>

                            @endforeach
                        @endif
                    </select>




                </div>


                {{--<div class="layui-input-inline">

                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>

                </div>--}}

            </form>
        </div>


        <xblock>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>
            <button class="layui-btn" onclick="store()">
                <i class="layui-icon download">&#xe654;</i>
                发送短信</button>

        </xblock>
            <table class="layui-table x-admin layui-form">

                <thead>
                <tr>
                    <th><div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div></th>
                    <th>手机号码</th>
                    <th>内容</th>
                    <th>状态</th>
                    <th>类型</th>
                    <th>发送日期</th>
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
                <td class="title_<% item.id %>"><% item.Showmobile?item.Showmobile:'' %></td>

                <td><% item.content?item.content:'' %></td>
                <td><% item.result?item.result:'' %></td>

                <td><% item.typeName?item.typeName:'' %></td>



                <td><% item.created_at %></td>

                <td class="td-manage">


                    <%# if(item.result!='发送成功'){ %>
                    <a title="重新发送" onclick="sendsms(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                        <i class="layui-icon" style="color: red;">&#xe678;</i>
                    </a>

                    <%# } %>

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
        layui.use('form', function() {
            var form = layui.form;

            form.on('select(s_categoryid)', function (data) {

                var obj = {
                    s_key: $("[name='s_key']").val(),
                    s_categoryid: data.value,
                };
                lists(1, obj);
            });

        });


        function sendsms(id,page){


            layer.confirm('确定要发送短信通知?', {icon: 3, title:'提示'}, function(index){


                $.post("{{ route($RouteController.".update") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id,
                },function(data){


                    @if(Cache::has("msgshowtime"))
                    if(data.status==0){
                        layer.msg(data.msg,{time:"{{Cache::get("msgshowtime")}}" },function(){
                            $(".lists_"+id).remove();

                            if(page>0){
                                lists(page);
                            }

                        });
                    }else{
                        layer.msg(data.msg,{icon:5,time:"{{Cache::get("msgshowtime")}}"});
                    }
                    @else
                    if(data.status==0){
                        layer.msg(data.msg,{},function(){
                            $(".lists_"+id).remove();
                            if(page>0){
                                lists(page);
                            }
                        });
                    }else{
                        layer.msg(data.msg,{icon:5});
                    }
                    @endif


                });

                layer.close(index);
            });

        }

    </script>
@endsection

