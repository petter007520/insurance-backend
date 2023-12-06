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

        <label class="layui-form-label col-sm-1">收货人</label>



        <div class="layui-input-inline">

            <input type="text" name="receiver" lay-verify="required" required placeholder="收货人" autocomplete="off" class="layui-input" value="{{ $edit->receiver }}">

        </div>

    </div>


  <!--  <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">购买价格</label>

        <div class="layui-input-block">
            <input type="text" name="price"  class="layui-input " placeholder="购买价格" value="{{$edit->price}}" >


        </div>

    </div>-->


    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">电话</label>

        <div class="layui-input-inline">

            <input type="text" name="mobile"   lay-verify="required" class="layui-input" placeholder="馈赠比率:%" value="{{ $edit->mobile }}" >

        </div>


    </div>


    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">地址</label>

        <div class="layui-input-inline">

            <input type="text" name="address"   lay-verify="required" class="layui-input" placeholder="推荐人数" value="{{ $edit->address }}" >

        </div>


    </div>





@endsection

@section("layermsg")

    @parent

@endsection





@section('form')



@endsection





