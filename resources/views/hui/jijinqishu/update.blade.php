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

        <label class="layui-form-label col-sm-1">名称</label>



        <div class="layui-input-inline">

            <input type="text" name="title" lay-verify="required" required placeholder="名称" autocomplete="off" class="layui-input" value="{{ $edit->title }}">

        </div>

    </div>




   <!-- <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">比率</label>

        <div class="layui-input-inline">

            <input type="text" name="rate"   lay-verify="required" class="layui-input" placeholder="馈赠比率:%" value="{{ $edit->rate }}" >

        </div>
        <label class="layui-form-label col-sm-1" style="text-align: left">%</label>

    </div>-->


    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">天数</label>

        <div class="layui-input-inline">

            <input type="text" name="days"   lay-verify="required" class="layui-input" placeholder="天数" value="{{ $edit->days }}" >

        </div>


    </div>
    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">推荐人数</label>

        <div class="layui-input-inline">

            <input type="text" name="tj_num"   lay-verify="required" class="layui-input" placeholder="推荐人数" value="{{ $edit->tj_num }}" >

        </div>


    </div>



    <!--<div class="layui-form-item">-->

    <!--    <label class="layui-form-label col-sm-1">投资额要求</label>-->

    <!--    <div class="layui-input-block">-->
    <!--        <input type="text" name="inte"  class="layui-input " placeholder="投资额要求" value="{{$edit->inte}}" >-->


    <!--    </div>-->

    <!--</div>-->

    <!--<div class="layui-form-item">-->

    <!--    <label class="layui-form-label col-sm-1">每日玩大转盘次数</label>-->

    <!--    <div class="layui-input-block">-->
    <!--        <input type="text" name="wheels" value="{{$edit->wheels}}" class="layui-input " placeholder="每日玩大转盘次数" >-->


    <!--    </div>-->

    <!--</div>-->


    <!--<div class="layui-form-item">-->

    <!--    <label class="layui-form-label col-sm-1">下线发展人数要求</label>-->

    <!--    <div class="layui-input-block">-->
    <!--        <input type="text" name="offlines"  class="layui-input " placeholder="下线发展人数要求" value="{{$edit->offlines}}">-->


    <!--    </div>-->

    <!--</div>-->



@endsection

@section("layermsg")

    @parent

@endsection





@section('form')



@endsection





