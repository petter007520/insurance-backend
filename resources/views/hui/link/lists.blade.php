@extends('hui_layouts.appui')@section('title', $title)@section('here')@endsection@section('addcss')    @parent@endsection@section('addjs')    @parent@endsection@section('formbody')    <div class="formbody">                <form class="layui-form layui-form-pane1" action="{{ route($controller_name."_lists") }}" method="get">            <div class="layui-form-item" pane>            <div class="layui-input-inline">                <input type="text" name="s_key"  placeholder="请输入名称" autocomplete="off" class="layui-input" value="@if(isset($_REQUEST['s_key'])){{$_REQUEST['s_key']}}@endif">            </div>            <div class="layui-input-inline">                <button class="layui-btn" lay-submit lay-filter="go">查询</button>            </div>        </div></form>             <table class="table table-bordered">            <colgroup>                <col width="50">                <col width="150">                <col width="110">                <col>                <col>                <col>                <col width="100">            </colgroup>            <thead>            <tr>                <th>ID</th>                <th>名称</th>                <th>图片</th>                <th>链接</th>                <th>排序</th>                <th>添加时间</th>                <th>操作</th>            </tr>            </thead>            <tbody>            </tbody>        </table>        <div id="layer_pages"></div>    </div>@endsection@section("layermsg")    @parent@endsection@section('form')    <script>    {{--@parent--}}       layui.use(['form'], function(){        var form = layui.form();            form.on('select(s_storeid)', function(data){                    var op={                       s_storeid :data.value                    }                         lists(1,op);                    });    });    function pagelist(list,page){                var _html='';        $.each(list,function(i,item){            _html+='<tr class="lists_'+item.id+'">';            _html+=' <td>'+item.id+'</td>';            _html+=' <td class="title_'+item.id+'">'+item.name+'</td>';            _html+='<td>' ;            if(item.thumb_url){                _html+= '<img src="'+item.thumb_url+'" height="100"/> ';            }            _html+='</td>';            _html+='<td>'+item.url+'</td>';            _html+='<td>'+item.sort+'</td>';            _html+='<td>'+item.created_at+'</td>';            _html+='<td>';            _html+=window.menu(item.id,page);            _html+='</td>';            _html+='</tr>';        });        $("tbody").html(_html);    }    </script>@endsection