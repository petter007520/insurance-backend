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
        <label class="layui-form-label col-sm-1">栏目</label>
        <div class="layui-input-inline">
            <select name="category_id">
                {!! $tree_option !!}

            </select>

        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">标题</label>

        <div class="layui-input-block">
            <input type="text" name="title" lay-verify="required" required placeholder="标题" autocomplete="off" class="layui-input" value="">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">关键字</label>

        <div class="layui-input-block">
            <input type="text" name="keyinfo" placeholder="关键字" autocomplete="off" class="layui-input" value="">
        </div>
    </div>


    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">描述</label>
        <div class="layui-input-block">
            <textarea placeholder="请填写描述" class="layui-textarea" name="descr"></textarea>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">作者</label>
        <div class="layui-input-inline">
            <input type="text" name="author" class="layui-input" placeholder="作者" value="">

        </div>
        <label class="layui-input-block authorlist"></label>
    </div>




    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">缩略图</label>



        <div class="layui-input-block">

            <button type="button" class="layui-btn" id="thumb_url" style="float:left;">
                <i class="layui-icon">&#xe67c;</i>上传图片
            </button>

            <span class="imgshow" style="float:left;width:100%;margin: 2px;"></span>

            <input type="text" name="image"  class="layui-input thumb" placeholder="缩略图" style="float:left;width:50%;">





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

        <label class="layui-form-label col-sm-1"> 图片相册</label>



        <div class="col-sm-11">

            <button type="button" class="layui-btn" id="product_image">
                <i class="layui-icon">&#xe67c;</i>上传相册
            </button>

            <br/>



            <span class="product_image_show">



            </span>

            <span class="product_image_data" style="display: none;">



            </span>

            <script>

                layui.use('upload', function(){

                    var uploads = layui.upload;


                    //执行实例
                    var Photos = uploads.render({
                        elem: '#product_image' //绑定元素
                        ,url: '{{route("admin.uploads.uploadimg")}}?_token={{ csrf_token() }}' //上传接口
                        , field:'thumb'
                        ,done: function(src){

                            if(src.status==0){

                                layer.msg(src.msg,{time:500},function(){

                                    var Number=$(".productimagedata").length;
                                    var imageurl=$("input[name='image']").val();
                                    if(imageurl==''){
                                        $("input[name='image']").val(src.src);
                                    }
                                    $(".product_image_show").append('<img src="'+src.src+'?t='+new Date()+'" data="'+src.src+'" width="100" style="float:left;margin:2px;"  class="productimagesrc"/>');

                                    $(".product_image_data").append('<input type="hidden" name="productimage['+Number+']" class="productimagedata" value="'+src.src+'">');

                                    form.render(); //更新全部

                                });

                            }

                        }
                        ,error: function(){
                            //请求异常回调
                        }
                    });



                });





                $(document).on("click","img.productimagesrc",function(){

                    //layer.msg($(this).attr("src"));

                    $("input[value='"+$(this).attr("data")+"']").remove();

                    $(this).remove();

                });



            </script>



        </div>

    </div>



    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">状态</label>
        <div class="layui-input-inline">
            <input type="radio" name="status" value="1" title="草稿">
            <input type="radio" name="status" value="2" title="发布" checked="checked">

        </div>


    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">积分</label>
        <div class="layui-input-inline">
            <input type="number" name="integral"  class="layui-input" placeholder="积分" value="1000">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">原价</label>
        <div class="layui-input-inline">
            <input type="number" name="original_price"  class="layui-input" placeholder="原价" value="0.00">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">规格</label>
        <div class="layui-input-inline">
            <input type="text" name="specs"  class="layui-input" placeholder="" value="">
            <span class="layui-badge layui-bg-green">规格用英文逗号隔开：红,黄,蓝</span>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">排序</label>
        <div class="layui-input-inline">
            <input type="number" name="sort"  class="layui-input" placeholder="排序" value="1">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">点击量</label>
        <div class="layui-input-inline">
            <input type="number" name="click_count"  class="layui-input" placeholder="点击量" value="1">
        </div>
    </div>



    @if(Cache::get('editor')=='markdown')


        <div id="container" class="editor">
            <textarea name="content" style="display:none;">这里填写保存内容</textarea>
        </div>

        @include('markdown::encode',['editors'=>['container']])



    @elseif(Cache::get('editor')=='u-editor')

        @include('UEditor::head')

        <!-- 加载编辑器的容器 -->
        <script id="container" name="content" type="text/plain">

        </script>


        <!-- 实例化编辑器 -->
        <script type="text/javascript">
            var ue = UE.getEditor('container', {
                autoHeightEnabled: true,
                autoFloatEnabled: true,
                // initialFrameWidth:95,
                initialFrameHeight:300,

            });

            ue.ready(function() {
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
            });

        </script>

    @else

        <div class="layui-form-item editor">

            <label class="layui-form-label col-sm-1">内容</label>

            <div class="layui-input-block">

                <textarea name="content" id="container" class="layui-hide" lay-filter="container"></textarea>




                <script>

                    var layeditIndex;


                    layui.use(['form','layedit'], function(){

                        var form = layui.form;

                        var layedit = layui.layedit;
                        layedit.set({
                            uploadImage: {
                                url: '{{route("admin.uploads.uploadeditorimg")}}?_token={{ csrf_token() }}'
                                ,type: 'post'
                            }
                        });

                        layeditIndex = layedit.build('container', {
                            tool: [
                                'code',
                                'strong' //加粗
                                ,'italic' //斜体
                                ,'underline' //下划线
                                ,'del' //删除线
                                ,'|' //分割线
                                ,'left' //左对齐
                                ,'center' //居中对齐
                                ,'right' //右对齐
                                ,'link' //超链接
                                ,'unlink' //清除链接
                                ,'image' //图片
                            ],
                            height: 400
                        });

                        function setdescr(){
                            if($('[name="descr"]').val()==''){
                                $('[name="descr"]').val(cutstr(layedit.getText(layeditIndex),200));
                            }
                            setTimeout(function () {
                                setdescr();
                            },5000);
                        }
                        setdescr();



                        /**
                         * js截取字符串，中英文都能用
                         * @param str：需要截取的字符串
                         * @param len: 需要截取的长度
                         */
                        function cutstr(str, len) {
                            var str_length = 0;
                            var str_len = 0;
                            str_cut = new String();
                            str_len = str.length;
                            for (var i = 0; i < str_len; i++) {
                                a = str.charAt(i);
                                str_length++;
                                if (escape(a).length > 4) {
                                    //中文字符的长度经编码之后大于4
                                    str_length++;
                                }
                                str_cut = str_cut.concat(a);
                                if (str_length >= len) {
                                    str_cut = str_cut.concat("...");
                                    return str_cut;
                                }
                            }
                            //如果给定字符串小于指定长度，则返回源字符串；
                            if (str_length < len) {
                                return str;
                            }
                        }



                        $("#layui-btn").click(function(){
                            $("#container").val(layedit.getContent(layeditIndex));
                        });

                        //自定义验证规则

                        form.verify({
                            container: function(value) {
                                layedit.sync(layeditIndex);
                            }
                        });


                    });

                </script>

            </div>

        </div>

    @endif






@endsection
@section("layermsg")
    @parent
@endsection


@section('form')
    <script>
        /*layui.use('laydate', function(){
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#offdate' //指定元素
            });


        });*/

        layui.use('form', function(){
            var form = layui.form;

            //各种基于事件的操作，下面会有进一步介绍

            //自定义验证规则
            form.verify({
               /* username: function(value){
                    if(value.length < 2){
                        return '帐号也太短了吧';
                    }
                }
                ,password: [/(.+){6,12}$/, '密码必须6到12位']
                ,password2: function(value){
                    if(value != $("input[name='password']").val()){
                        return '两次输入的密码不一致';
                    }
                }
                ,phone: function(value){
                    if(value != '' && !/^1[3|4|5|7|8]\d{9}$/.test(value)){
                        return '手机必须11位，只能是数字！';
                    }
                }

                ,email: function(value){
                    if(value !='' && !/^[a-z0-9._%-]+@([a-z0-9-]+\.)+[a-z]{2,4}$|^1[3|4|5|7|8]\d{9}$/.test(value)){
                        return '邮箱格式不对';
                    }
                }*/

            });


            });





        </script>
    @endsection
