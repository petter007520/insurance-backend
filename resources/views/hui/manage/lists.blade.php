@extends('hui_layouts.appui')

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

    <div class="formbody">

     

        <form class="layui-form layui-form-pane1" action="{{ route($controller_name."_lists") }}" method="get">

            <div class="layui-form-item" pane>

                <div class="layui-input-inline">
                    <input type="text" name="s_key"  placeholder="请输入帐号/姓名/手机/邮箱" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_storeid'])){{$_REQUEST['s_key']}}@endif">
                </div>


                <div class="layui-input-inline">
                    <select name="s_storeid">
                        <option value="0">请选择站点</option>
                        <optgroup label="站点名称">
                            @if($storelist)
                                @foreach($storelist as $item)
                                    <option value="{{$item->id}}" @if(isset($_REQUEST['s_storeid']) && $_REQUEST['s_storeid']==$item->id) selected="selected" @endif>{{$item->name}}</option>
                                @endforeach
                            @endif
                        </optgroup>

                    </select>
                </div>

                <div class="layui-input-inline">
                    <button class="layui-btn" lay-submit lay-filter="go">查询</button>
                </div>

            </div>


        </form>

              <table class="table table-bordered">
            <colgroup>
                <col width="50">
                <col width="150">
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col width="80">
            </colgroup>
            <thead>
            <tr>
                <th>ID</th>
                <th>登录帐号</th>
                <th>姓名</th>
                <th>卡号</th>
                <th>手机</th>
                <th>邮箱</th>
                <th>分组</th>
                <th>站点</th>
                <th>添加时间</th>
                <th>最后登录</th>
                <th>备注</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if(Cache::has("post_ajax"))
            @if(Cache::get("post_ajax")!='ajax')

                            @if($list)
                                @foreach ($list as $d)
                                    <tr class="lists_{{ $d->id }}">
                                        <td>{{ $d->id }}</td>
                                        <td class="title_{{ $d->id }}">{{ $d->username }}</td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->cardnumber }}</td>
                                        <td>{{ $d->phone }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td>{{ $d->authname }}</td>
                                        <td>{{ $d->storename }}</td>
                                        <td>{{ $d->created_at }}</td>
                                        <td>{{ $d->lastlogin_at }}</td>
                                        <td>{{ $d->remarks }}</td>
                                        <td>
                                            @if($path_update>0)
                                            <img src="{{ asset("admin/images/t02.png") }}" data-id="{{ $d->id }}" alt="编辑" class="layerupdate"/>
                                                @endif
                                                @if($path_delete>0)
                                                <img src="{{ asset("admin/images/t03.png") }}" alt="删除" class="layerdel" data-id="{{ $d->id }}"/>
                                                @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                @endif

                @else
{{-- 没有设置选项的默认显示 --}}
                @if($list)
                    @foreach ($list as $d)
                        <tr class="lists_{{ $d->id }}">
                            <td>{{ $d->id }}</td>
                            <td class="title_{{ $d->id }}">{{ $d->username }}</td>
                            <td>{{ $d->name }}</td>
                            <td>{{ $d->cardnumber }}</td>
                            <td>{{ $d->phone }}</td>
                            <td>{{ $d->email }}</td>
                            <td>{{ $d->authname }}</td>
                            <td>{{ $d->storename }}</td>
                            <td>{{ $d->created_at }}</td>
                            <td>{{ $d->lastlogin_at }}</td>
                            <td>{{ $d->remarks }}</td>
                            <td>

                                @if($path_update>0)
                                    <img src="{{ asset("admin/images/t02.png") }}" data-id="{{ $d->id }}" alt="编辑" class="layerupdate"/>
                                @endif
                                @if($path_delete>0)
                                    <img src="{{ asset("admin/images/t03.png") }}" alt="删除" class="layerdel" data-id="{{ $d->id }}"/>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                @endif

            @endif
            </tbody>
        </table>
        @if(Cache::has("post_ajax"))
            @if(Cache::get("post_ajax")!='ajax')
                {{$list->appends($page_url)->links()}}
            @else
        <div id="layer_pages"></div>
            @endif
        @endif



    </div>
@endsection
@section("layermsg")
    @parent
@endsection

@section('form')
    <script>
    {{--@parent--}}

    function pagelist(list,page){
        var _html='';
        $.each(list,function(i,item){

            _html+='<tr class="lists_'+item.id+'">';
            _html+=' <td>'+item.id+'</td>';
            _html+=' <td class="title_'+item.id+'">'+item.username+'</td>';
            _html+=' <td>'+item.name+'</td>';
            _html+='<td>'+item.cardnumber+'</td>';
            _html+='<td>'+item.phone+'</td>';
            _html+='<td>'+item.email+'</td>';
            _html+=' <td>'+item.authname+'</td>';
            _html+='<td>'+item.storename+'</td>';
            _html+='<td>'+item.created_at+'</td>';
            _html+='<td>'+item.lastlogin_at+'</td>';
            _html+='<td>'+item.remarks+'</td>';
            _html+='<td>';

            @if($path_update>0)
                    _html+='<img src="{{ asset("admin/images/t02.png") }}" data-id="'+item.id+'" alt="编辑" class="layerupdate" onclick="update(\''+item.id+'\',\''+page+'\')"/>';
            @endif
                    @if($path_delete>0)
                    _html+='<img src="{{ asset("admin/images/t03.png") }}" alt="删除" class="layerdel" data-id="'+item.id+'" onclick="del(\''+item.id+'\',\''+page+'\')"/>';
            @endif
            _html+='</td>';
            _html+='</tr>';
        });

        $("tbody").html(_html);

    }


    layui.use('form', function(){
        var form = layui.form();


        });



    </script>
@endsection


{{--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{  Cache::get('sitename') }} 管理员列表</title>
    <link href="{{ asset("admin/css/style.css") }}" rel="stylesheet" type="text/css" />

    <script language="JavaScript" src="{{ asset("admin/js/jquery.js") }}"></script>




    <link rel="stylesheet" href="{{ asset("layui/build/css/layui.css") }}">
    <script src="{{ asset("layui/build/layui.js") }}"></script>





</head>

<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="{{ route("admin_main") }}">首页</a></li>
    <li><a href="{{ route("admin_lists") }}">管理员列表</a></li>
    </ul>
    </div>
    
    <div class="formbody">
    
    <div class="formtitle"><span>管理员</span></div>


        <table class="layui-table">
            <colgroup>
                <col width="50">
                <col width="150">
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col width="80">
            </colgroup>
            <thead>
            <tr>
                <th>ID</th>
                <th>登录帐号</th>
                <th>姓名</th>
                <th>手机</th>
                <th>邮箱</th>
                <th>分组</th>
                <th>店铺</th>
                <th>添加时间</th>
                <th>最后登录</th>
                <th>备注</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if($list)
            @foreach ($list as $d)
            <tr class="lists_{{ $d->id }}">
                <td>{{ $d->id }}</td>
                <td class="username_{{ $d->id }}">{{ $d->username }}</td>
                <td>{{ $d->name }}</td>
                <td>{{ $d->phone }}</td>
                <td>{{ $d->email }}</td>
                <td>{{ $d->authname }}</td>
                <td>{{ $d->storename }}</td>
                <td>{{ $d->created_at }}</td>
                <td>{{ $d->lastlogin_at }}</td>
                <td>{{ $d->remarks }}</td>
                <td><img src="{{ asset("admin/images/t02.png") }}" data-id="{{ $d->id }}" alt="编辑" class="layerupdate"/><img src="{{ asset("admin/images/t03.png") }}" alt="删除" class="layerdel" data-id="{{ $d->id }}"/> </td>
            </tr>
            @endforeach
            @endif

            </tbody>
        </table>
        {{$list->links()}}
        <div id="demo_page"></div>


        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">

    </div>




    <script>
        layui.use(['form'], function(){
            var form = layui.form();
        });
        layui.use(['laypage', 'layer'], function(){
            var laypage = layui.laypage
                    ,layer = layui.layer;

           var total= "{{ $list->total() }}";
           var count= "{{ $pagesize }}";
           var page_count=Math.ceil(total/count);

            laypage({
                cont: 'demo_page'
                ,pages: page_count
                ,groups: 5
                ,first: true
                ,last: true
                ,curr:"{{ $list->currentPage() }}"
                ,jump: function(obj, first){
                    if(!first) {
                        location.href = "{{ route("admin_lists") }}?page=" + obj.curr;
                    }
                }
            });


        $(".layerdel").click(function(){
            var id=$(this).attr("data-id");
            layer.confirm('确定要删除'+$(".username_"+id).text()+'?', {icon: 3, title:'提示'}, function(index){


                $.post("{{ route("admin_delete") }}",{
                    _token:"{{ csrf_token() }}",
                    id:id
                },function(data){
                    if(data.status==0){
                        layer.msg(data.msg,{},function(){
                            $(".lists_"+id).remove();
                        });
                    }else{
                        layer.msg(data.msg,{icon:5});
                    }
                });

                layer.close(index);
            });
        });

            $(".layerupdate").click(function(){

                var id=$(this).attr("data-id");
                update(id);
            });


            function update(id){
                layer.open({
                    title:false,//"编辑管理员："+$(".username_"+id).text(),
                    type: 2,
                    area: ['90%', '90%'],
                    content: ['{{ route("admin_update")}}?id='+id,'yes'],
                    end: function () {
                        location.reload(true);
                    }
                });
            }

        });
    </script>

</body>

</html>--}}
