@extends('hui.layouts.appstore')
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
        <label class="layui-form-label col-sm-1">手机号</label>

        <div class="layui-col-md3">
            <input type="text" name="mobile" lay-verify="required" required placeholder="手机号" autocomplete="off" class="layui-input">
        </div>
    </div>
	<div class="layui-form-item">
        <label class="layui-form-label col-sm-1">选择奖品</label>
        <div class="layui-input-inline">
            <select name="reward_id">
                @if($rewards_lists)
                    @foreach($rewards_lists as $v)
                        <option value="{{$v->id}}">{{$v->name}}</option>
                    @endforeach
                @endif

            </select>

        </div>
    </div>


    
@endsection
@section("layermsg")
    @parent
@endsection
@section('form')
@endsection