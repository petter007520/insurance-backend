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
        <label class="layui-form-label col-sm-1">奖品名称</label>
        <div class="layui-col-md3">
            <input type="text" name="title" lay-verify="required" required placeholder="奖品名称" autocomplete="off" class="layui-input" value="{{$edit->title}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">兑换现金</label>
        <div class="layui-col-md3">
            <input type="text" name="point" lay-verify="required" required placeholder="兑换现金" autocomplete="off" class="layui-input" value="{{$edit->title}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">库存</label>
        <div class="layui-col-md3">
            <input type="text" name="stock" lay-verify="required" required placeholder="库存" autocomplete="off" class="layui-input" value="{{$edit->stock}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">中奖概率</label>
        <div class="layui-col-md3">
            <input type="text" name="odds" lay-verify="required" required placeholder="中奖概率(0-10000)" autocomplete="off" class="layui-input" value="{{$edit->odds}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">奖品图片</label>
        <div class="layui-col-md6">
            <button type="button" class="layui-btn" id="thumb_url" style="float:left;">
                <i class="layui-icon">&#xe67c;</i>上传奖品图片
            </button>
            <span class="imgshow" style="float:left;width:100%;margin: 2px;">
                @if($edit->image!='')
                    <img src="{{$edit->image}}" width="100" style="float:left;"/>
                    @endif
            </span>
            <input type="text" name="image" lay-verify="required" value="{{$edit->image}}" class="layui-input thumb" placeholder="奖品图片" style="float:left;width:50%;">
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
                            console.log(src);
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