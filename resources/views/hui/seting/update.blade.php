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
            <input type="text" name="name" lay-verify="required|name" autocomplete="off" class="layui-input" placeholder="名称" value="{{ $edit->name }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">键名</label>
        <div class="layui-input-inline">
            <input type="text" name="keyname" lay-verify="required|keyname" autocomplete="off" class="layui-input" placeholder="键名" value="{{ $edit->keyname }}" >
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">设置值</label>
        <div class="layui-input-inline">
            <input type="text" name="value" lay-verify="required" autocomplete="" class="layui-input" placeholder="设置值" value="{{ $edit->value }}">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">选择值</label>
        <div class="layui-input-inline">
            <input type="text" name="valuelist"  autocomplete="" class="layui-input" placeholder="选择值下拉多选框设置" value="{{ $edit->valuelist }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">排序</label>
        <div class="layui-input-inline">
            <input type="text" name="sort" lay-verify="required|number" autocomplete="" class="layui-input" placeholder="设置值" value="{{ $edit->sort }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">输入框类型</label>
        <div class="layui-input-inline">
            <select name="type">
                <option value="text" @if($edit->type =='text' ) selected="selected" @endif>文本</option>
                <option value="select" @if($edit->type =='select' ) selected="selected" @endif>下拉选择</option>
                <option value="radio" @if($edit->type =='radio' ) selected="selected" @endif>单选框</option>
                <option value="checkbox" @if($edit->type =='checkbox' ) selected="selected" @endif>复选框</option>
                <option value="upload" @if($edit->type =='upload' ) selected="selected" @endif>单图上传</option>
                <option value="photos" @if($edit->type =='photos' ) selected="selected" @endif>多图上传</option>
                <option value="video" @if($edit->type =='video' ) selected="selected" @endif>视频上传</option>
                <option value="number" @if($edit->type =='number' ) selected="selected" @endif>整数文本</option>
                <option value="datetime" @if($edit->type =='datetime' ) selected="selected" @endif>日期时间</option>
                <option value="hidden" @if($edit->type =='hidden' ) selected="selected" @endif>隐藏文本</option>
                <option value="disabled" @if($edit->type =='disabled' ) selected="selected" @endif>禁用文本</option>
                <option value="textarea" @if($edit->type =='textarea' ) selected="selected" @endif>多文本</option>
                <option value="switch" @if($edit->type  =='switch' ) selected="selected" @endif>开关键</option>
            </select>
        </div>
    </div>



@endsection
@section("layermsg")
    @parent
@endsection


@section('form')
    <script>
        layui.use('form', function(){
            var form = layui.form;

            //各种基于事件的操作，下面会有进一步介绍

            //自定义验证规则
            form.verify({



            });



        });

    </script>
    @endsection

