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

        <label class="layui-form-label col-sm-1">名称</label>



        <div class="layui-input-inline">

            <input type="text" name="title" lay-verify="required" required placeholder="名称" autocomplete="off" class="layui-input" value="">

        </div>

    </div>



    <!--<div class="layui-form-item">

        <label class="layui-form-label col-sm-1">馈赠比率</label>

        <div class="layui-input-inline">

            <input type="text" name="rate"   lay-verify="required" class="layui-input" placeholder="馈赠比率:%" value="">

        </div>
        <label class="layui-form-label col-sm-1" style="text-align: left">%</label>
    </div>-->

    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">天数</label>

        <div class="layui-input-inline">

            <input type="text" name="days"   lay-verify="required" class="layui-input" placeholder="天数" value="">

        </div>

    </div>
    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">直推人数</label>

        <div class="layui-input-inline">

            <input type="text" name="tj_num"   lay-verify="required" class="layui-input" placeholder="推荐人数" value="">

        </div>

    </div>












@endsection

@section("layermsg")

    @parent

@endsection





@section('form')



@endsection





