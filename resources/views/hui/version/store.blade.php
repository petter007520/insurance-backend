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
        <label class="layui-form-label col-sm-1">应用名称</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="app_name" lay-verify="required" required placeholder="应用名称" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">版本号</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="edition_number" lay-verify="required" required placeholder="版本号" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">版本名称</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="edition_name" lay-verify="required" required placeholder="版本名称" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">系统类型</label>
        <div class="layui-input-inline">
            <label>
                <input type="radio" name="platform" value="android" title="Android" checked="checked">
            </label>
            <label>
                <input type="radio" name="platform" value="ios" title="Ios">
            </label>
        </div>
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">安装包类型</label>
        <div class="layui-input-block">
            <label>
                <input type="radio" name="package_type" value="0" title="整包更新" checked="checked">
            </label>
            <label>
                <input type="radio" name="package_type" value="1" title="Wgt热更新">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">是否发行</label>
        <div class="layui-input-inline">
            <label>
                <input type="radio" name="edition_issue" value="0" title="否" checked="checked">
            </label>
            <label>
                <input type="radio" name="edition_issue" value="1" title="是">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">静默更新</label>
        <div class="layui-input-inline">
            <label>
                <input type="radio" name="edition_silence" value="0" title="否" checked="checked">
            </label>
            <label>
                <input type="radio" name="edition_silence" value="1" title="是">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">强制更新</label>
        <div class="layui-input-inline">
            <label>
                <input type="radio" name="edition_force" value="0" title="否" checked="checked">
            </label>
            <label>
                <input type="radio" name="edition_force" value="1" title="是">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">下载地址</label>
        <div class="layui-input-block">
            <label>
                <input type="text" name="edition_url" lay-verify="required" required class="layui-input" placeholder="点击量" value="">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">更新内容</label>
        <div class="layui-input-block">
            <label>
                <textarea placeholder="更新内容" class="layui-textarea" name="edition_content"></textarea>
            </label>
        </div>
    </div>
@endsection
@section("layermsg")
    @parent
@endsection
@section('form')
@endsection
