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
        <label class="layui-form-label col-sm-1">联系名称</label>

        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" required placeholder="联系名称" autocomplete="off" class="layui-input" value="">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">联系方式</label>

        <div class="layui-input-block">
            <input type="text" name="value" lay-verify="required" required placeholder="联系方式" autocomplete="off" class="layui-input" value="">
        </div>
        
    </div>


    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">图片</label>

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
        <label class="layui-form-label col-sm-1">排序</label>
        <div class="layui-input-block">
            <input type="text" name="sort" placeholder="排序" lay-verify="required"  autocomplete="off" class="layui-input" value="1">

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
