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
        <label class="layui-form-label col-sm-1">签到奖励名称</label>

        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" required placeholder="签到奖励名称" autocomplete="off" class="layui-input" value="{{$edit->name}}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">签到奖励数量</label>

        <div class="layui-input-block">
            <input type="text" name="num" lay-verify="required" required placeholder="签到奖励数量" autocomplete="off" class="layui-input" value="{{$edit->num}}">
        </div>
        
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">签到奖励类型</label>
        <div class="layui-input-inline">
            <select name="type">
                <option value="1" @if($edit->type==1 ) selected="selected" @endif>积分</option>
                <option value="2" @if($edit->type==2 ) selected="selected" @endif>现金</option>
            </select>
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">连签天数</label>

        <div class="layui-input-block">
            <input type="text" name="days" readonly="readonly" placeholder="连签天数" autocomplete="off" class="layui-input" value="{{$edit->days}}">
        </div>
        
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">详情</label>
        <div class="layui-input-block">
            <input type="text" name="detail" placeholder="详情" lay-verify="required"  autocomplete="off" class="layui-input" value="{{$edit->detail}}">

        </div>
    </div>





@endsection
@section("layermsg")
    @parent
@endsection


@section('form')

@endsection
