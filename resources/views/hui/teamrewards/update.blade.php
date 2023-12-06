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
        <label class="layui-form-label col-sm-1">团队认购人数</label>

        <div class="layui-input-block">
            <input type="text" name="team_num" lay-verify="required" required placeholder="人数" autocomplete="off" class="layui-input" value="{{$edit->team_num}}">
        </div>
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">认购总额</label>
        <div class="layui-input-block">
            <input type="text" name="team_amount" placeholder="金额" lay-verify="required"  autocomplete="off" class="layui-input" value="{{$edit->team_amount}}">

        </div>
    </div>
    
    
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">奖励金额</label>
        <div class="layui-input-block">
            <input type="text" name="reward_amount" placeholder="金额" lay-verify="required"  autocomplete="off" class="layui-input" value="{{$edit->reward_amount}}">

        </div>
    </div>
    
    
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">奖励产品名</label>
    <div class="layui-input-inline">
            <select name="p_id">
                <option value="0" selected="selected">请选择产品(无赠送)</option>
                @if($productlist)
                    @foreach($productlist as $v)
                        <option value="{{$v->id}}" @if($edit->reward_equ_pid==$v->id) selected="selected" @endif>{{$v->title}}</option>
                    @endforeach
                @endif

            </select>


        </div>
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">奖励产品数量</label>
        <div class="layui-input-block">
            <input type="text" name="reward_equ_num" placeholder="库存" lay-verify="required"  autocomplete="off" class="layui-input" value="{{$edit->reward_equ_num}}">

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
