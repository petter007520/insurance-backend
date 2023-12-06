@extends('hui.layouts.appupdate')

@section('title', $title)
@section('here')

@endsection
@section('addcss')
    @parent
@endsection
@section('addjs')
    @parent
@endsection

@section("mainbody")
    @parent
@endsection

@section('formbody')





    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">收益周期</label>

        <div class="layui-input-block">
            <input type="text" name="type_name" lay-verify="required" required placeholder="收益周期" autocomplete="off" class="layui-input" value="{{$edit->type_name}}">
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">周期天数</label>

        <div class="layui-input-block">
            <input type="text" name="dividend_day" lay-verify="required" required placeholder="周期天数" autocomplete="off" class="layui-input" value="{{$edit->dividend_day}}">
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">比例%</label>

        <div class="layui-input-block">
            <input type="text" name="dividend_ratio" lay-verify="required" required placeholder="比例%" autocomplete="off" class="layui-input" value="{{$edit->dividend_ratio}}">
        </div>
    </div>

    
    






@endsection
@section("layermsg")
    @parent
@endsection


@section('form')
        
        <script>
        layui.use('laydate', function(){
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#offdate' //指定元素
            });


        });

    </script>
        
@endsection
