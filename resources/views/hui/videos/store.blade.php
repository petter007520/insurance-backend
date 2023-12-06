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

        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" required placeholder="名称" autocomplete="off" class="layui-input" value="">
        </div>
    </div>

         <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">分类</label>
        <div class="layui-input-inline">
            <select name="category_id">
                {!! $tree_option !!}

            </select>

        </div>
    </div>



    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">视频</label>

        <div class="layui-input-block">

            <button type="button" class="layui-btn" id="video_url" style="margin-bottom: 5px;">
                <i class="layui-icon">&#xe6ed;</i>上传视频(点击上传后请耐心等待，提示上传成功)
            </button>

             <span class="videoshow" style="display: block;">{{--autoplay="autoplay"--}}
                <!-- <video id="shakeVideo"  controls="controls" webkit-playsinline="true" playsinline="true" controlslist="nodownload" src="" width="50%" height="200px"></video> -->
            </span>
            <input type="text" name="video_url" lay-verify="" class="layui-input video" placeholder="视频路径" style="float:left;width:50%;">

            <script>
                layui.use('upload', function(){

                    var upload = layui.upload;

                    //执行实例
                    var uploadInst = upload.render({
                        elem: '#video_url' //绑定元素
                        , url: '{{route($RouteController.".uploadvideo")}}?_token={{ csrf_token() }}' //上传接口
                        , field:'files'
                        , exts:'mp4'
                        ,done: function(src){
                            //上传完毕回调

                            console.log(src);
                            if(src.status==0){
                                layer.msg(src.msg,{time:500},function(){
                                    $(".videoshow").html('<video id="shakeVideo" autoplay="autoplay" controls="controls" webkit-playsinline="true" playsinline="true" controlslist="nodownload" src="'+src.src+'" width="50%" height="200px"></video>');
                                    $(".video").val(src.src);
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


    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">视频图片</label>

        <div class="layui-input-block">

            <button type="button" class="layui-btn" id="thumb_url" style="float:left;">
                <i class="layui-icon">&#xe67c;</i>上传图片
            </button>

            <span class="imgshow" style="float:left;width:100%;margin: 2px;"></span>

            <input type="text" name="thumb_url" lay-verify="required" class="layui-input thumb" placeholder="缩略图" style="float:left;width:50%;">

            <script>

                layui.use('upload', function(){

                    var upload = layui.upload;

                    //执行实例
                    var uploadInst = upload.render({
                        elem: '#thumb_url' //绑定元素
                        ,url: '{{route("admin.uploads.uploadimg")}}?_token={{ csrf_token() }}' //上传接口
                        ,field:'thumb'
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


    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">标题</label>
        <div class="layui-input-block">
            <input type="text" name="title" placeholder="标题" lay-verify="required"  autocomplete="off" class="layui-input" value="">

        </div>
    </div>



    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">排序</label>
        <div class="layui-input-block">
            <input type="text" name="sort" placeholder="排序" lay-verify="required"  autocomplete="off" class="layui-input" value="1">

        </div>
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">说明</label>
        <div class="layui-input-block">
            <textarea placeholder="请填写描述" class="layui-textarea" name="description"></textarea>
        </div>
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">视频代码</label>
        <div class="layui-input-block">
            <textarea placeholder="请填写视频代码" class="layui-textarea" name="code"></textarea>
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



            //自定义验证规则
            form.verify({


            });


        });



    </script>
@endsection
