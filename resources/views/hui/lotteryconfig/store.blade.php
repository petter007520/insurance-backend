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

            <input type="text" name="name" lay-verify="required" required placeholder="名称" autocomplete="off" class="layui-input" value="">

        </div>

    </div>








    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">奖金</label>

        <div class="layui-input-inline">

            <input type="text" name="moneys"   lay-verify="required" class="layui-input" placeholder="奖金" value="">

        </div>

    </div>





    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">奖品图片</label>



        <div class="layui-input-block">

            <button type="button" class="layui-btn" id="thumb_url" style="float:left;">
                <i class="layui-icon">&#xe67c;</i>上传图片
            </button>

            <span class="imgshow" style="float:left;width:100%;margin: 2px;"></span>

            <input type="text" name="img"  class="layui-input thumb" placeholder="奖品图片" style="float:left;width:50%;">





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


    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">中奖率</label>

        <div class="layui-input-block">
            <input type="text" name="winningrate"  class="layui-input " placeholder="中奖率" >


        </div>

    </div>

    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">中奖说明</label>

        <div class="layui-input-block">
            <textarea name="prize"   class="layui-textarea"></textarea>


        </div>

    </div>



@endsection

@section("layermsg")

    @parent

@endsection





@section('form')



@endsection





