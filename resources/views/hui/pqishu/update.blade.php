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

            <input type="text" name="name" lay-verify="required" required placeholder="等级名称" autocomplete="off" class="layui-input" value="{{ $edit->name }}">

        </div>

    </div>





    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">赎回比率</label>

        <div class="layui-input-inline">

            <input type="text" name="rate"   lay-verify="required" class="layui-input" placeholder="馈赠比率:%" value="{{ $edit->rate }}" >

        </div>
        <label class="layui-form-label col-sm-1" style="text-align: left">%</label>

    </div>
  <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">保证金比率</label>

        <div class="layui-input-inline">

            <input type="text" name="bzj_rate"   lay-verify="required" class="layui-input" placeholder="馈赠比率:%" value="{{ $edit->bzj_rate }}" >

        </div>
        <label class="layui-form-label col-sm-1" style="text-align: left">%</label>

    </div>

    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">产品</label>

        <div class="layui-input-inline">

            <select name="productid">
                <option value="0" selected="selected">请选择产品</option>
                @if($productlist)

                @foreach($productlist as $v)
                <option value="{{$v->id}}" @if($edit->productid==$v->id) selected="selected" @endif>{{$v->title}}</option>
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





