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

        <label class="layui-form-label col-sm-1">工资</label>

        <div class="layui-input-block">
            <input type="text" name="price"  class="layui-input " placeholder="工资" >

        </div>

    </div>



    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">直推购买金额</label>

        <div class="layui-input-block">
            <input type="text" name="level_fee"  class="layui-input " placeholder="直推购买金额" >

        </div>

    </div>


 
    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">推荐人数</label>

        <div class="layui-input-inline">

            <input type="text" name="tj_num"   lay-verify="required" class="layui-input" placeholder="推荐人数" value="">

        </div>

    </div>


    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">等级图片</label>



        <div class="layui-col-md6">

            <button type="button" class="layui-btn" id="thumb_url" style="float:left;">
                <i class="layui-icon">&#xe67c;</i>上传产品图片
            </button>

            <span class="imgshow" style="float:left;width:100%;margin: 2px;"></span>

            <input type="text" name="headurl" lay-verify="required" class="layui-input thumb" placeholder="等级图片" style="float:left;width:50%;">


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
   <!-- <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">团队直属等级ID</label>

        <div class="layui-input-inline">

            <input type="text" name="zt_level"   lay-verify="required" class="layui-input" placeholder="累计消费" value="">

        </div>

    </div>
    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">人数</label>

        <div class="layui-input-inline">

            <input type="text" name="zt_num"   lay-verify="required" class="layui-input" placeholder="累计消费" value="">

        </div>

    </div>-->
   <!-- <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">团队累计</label>

        <div class="layui-input-inline">

            <input type="text" name="groupsum"   lay-verify="required" class="layui-input" placeholder="累计消费" value="">

        </div>

    </div>-->







    <!--<div class="layui-form-item">-->

    <!--    <label class="layui-form-label col-sm-1">投资额要求</label>-->

    <!--    <div class="layui-input-block">-->
    <!--        <input type="text" name="inte"  class="layui-input " placeholder="投资额要求" >-->


    <!--    </div>-->

    <!--</div>-->



    <!--<div class="layui-form-item">-->

    <!--    <label class="layui-form-label col-sm-1">每日玩大转盘次数</label>-->

    <!--    <div class="layui-input-block">-->
    <!--        <input type="text" name="wheels"  class="layui-input " placeholder="每日玩大转盘次数" >-->


    <!--    </div>-->

    <!--</div>-->




    <!--<div class="layui-form-item">-->

    <!--    <label class="layui-form-label col-sm-1">下线发展人数要求</label>-->

    <!--    <div class="layui-input-block">-->
    <!--        <input type="text" name="offlines"  class="layui-input " placeholder="下线发展人数要求" >-->


    <!--    </div>-->

    <!--</div>-->





@endsection

@section("layermsg")

    @parent

@endsection





@section('form')



@endsection





