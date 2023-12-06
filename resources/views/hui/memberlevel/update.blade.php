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
        <label class="layui-form-label col-sm-1">等级名称</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="name" lay-verify="required" required placeholder="等级名称" autocomplete="off" class="layui-input" value="{{ $edit->name }}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">Level(等级标识)</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="level" lay-verify="required" class="layui-input" placeholder="累计消费" value="{{ $edit->level }}">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">永久分红比例(小区)(%)</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="rate" lay-verify="required" class="layui-input" placeholder="累计消费" value="{{ $edit->rate }}">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">业绩要求(小区)(￥)</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="need_amount" lay-verify="required" class="layui-input" placeholder="累计消费" value="{{ $edit->need_amount }}">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">所需伞下星级数量</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="need_star_num" lay-verify="required" class="layui-input" placeholder="累计消费" value="{{ $edit->need_star_num }}">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">所需伞下星级等级</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="need_star_level" lay-verify="required" class="layui-input" placeholder="累计消费" value="{{ $edit->need_star_level }}">
            </label>
        </div>
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-2">福利</label>
        <div class="layui-input-block">
            <label>
                <textarea placeholder="请填写福利" class="layui-textarea" name="welfare">{{$edit->welfare}}</textarea>
            </label>
        </div>
    </div>
    <div class="layui-form-item zjysg">
    <label class="layui-form-label col-sm-1">产品图片</label>
    <div class="layui-col-md6">
        <button type="button" class="layui-btn" id="thumb_url" style="float:left;">
            <i class="layui-icon">&#xe67c;</i>上传产品图片
        </button>
        <span class="imgshow" style="float:left;width:100%;margin: 2px;">
                @if($edit->pic!='')
                    <img src="{{$edit->pic}}" width="100" style="float:left;"/>
                    @endif
            </span>
        <input type="text" name="headurl" lay-verify="required" value="{{$edit->headurl}}" class="layui-input thumb" placeholder="产品图片" style="float:left;width:50%;">
        <script>
            layui.use('upload', function(){
                var upload = layui.upload;
                //执行实例
                var uploadInst = upload.render({
                    elem: '#thumb_url' //绑定元素
                    ,url: '{{route("admin.uploads.uploadimg")}}?_token={{ csrf_token() }}' //上传接口
                    , field:'thumb'
                    ,done: function(src){
                        //上传完毕回调
                        if(src.status==0){
                            layer.msg(src.msg,{time:500},function(){
                                $(".imgshow").html('<img src="'+src.src+'?t='+new Date()+'" width="100" style="float:left;"/>');
                                $(".thumb").val(src.src);
                            });
                        }
                    }
                    ,error: function(){
                        //请求异常回调
                    }
                });
            });
        </script>
    </div>
</div>
@endsection
@section("layermsg")
    @parent
@endsection
@section('form')
@endsection
