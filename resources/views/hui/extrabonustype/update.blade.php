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
        <label class="layui-form-label col-sm-1">最低金额</label>

        <div class="layui-input-block">
            <input type="text" name="min_money" lay-verify="required" autocomplete="off" class="layui-input" value="{{$edit->min_money}}">
        </div>
        <span id="helpBlock2" class="help-block">（最低金额：请按从小到大金额，此处金额不能小于前面已有金额）</span>
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">赠送金额</label>
        <div class="layui-input-block">
            <input type="text" name="money" placeholder="赠送金额" lay-verify="required"  autocomplete="off" class="layui-input" value="{{$edit->money}}">

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
