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

        <label class="layui-form-label col-sm-1">下线层级</label>



        <div class="layui-input-inline">

            <input type="text" name="name" lay-verify="required" required placeholder="下线层级" autocomplete="off" class="layui-input" value="{{ $edit->name }}">

        </div>

    </div>






    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">提成比例(%)</label>

        <div class="layui-input-inline">

            <input type="text" name="percent"   lay-verify="required" class="layui-input" placeholder="提成比例(%)" value="{{ $edit->percent }}" >

        </div>
        <label class="layui-form-label col-sm-1" style="text-align: left">%</label>

    </div>






@endsection

@section("layermsg")

    @parent

@endsection





@section('form')



@endsection





