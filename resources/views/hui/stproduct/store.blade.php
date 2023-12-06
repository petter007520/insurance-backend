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
        <label class="layui-form-label col-sm-1">商品名称</label>

        <div class="layui-col-md3">
            <input type="text" name="name" lay-verify="required" required placeholder="商品名称" autocomplete="off" class="layui-input" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">商品图片</label>
        <div class="layui-col-md6">
            <button type="button" class="layui-btn" id="thumb_url" style="float:left;">
                <i class="layui-icon">&#xe67c;</i>上传产品图片
            </button>
            <span class="imgshow" style="float:left;width:100%;margin: 2px;"></span>
            <input type="text" name="picurl" lay-verify="required" class="layui-input thumb" placeholder="商品图片" style="float:left;width:50%;">

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
        <label class="layui-form-label col-sm-1">零售价格</label>

        <div class="layui-input-inline">
            <input type="text" name="fee" placeholder="零售价格" autocomplete="off" class="layui-input" value="">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">起购数量</label>

        <div class="layui-input-inline">
            <input type="number" name="qtsl" placeholder="起购数量" autocomplete="off" class="layui-input" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">限购数量</label>

        <div class="layui-input-inline">
            <input type="number" name="xg_num" placeholder="限购数量" autocomplete="off" class="layui-input" value="">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">规格</label>

        <div class="layui-input-inline">
            <input type="text" name="guige" placeholder="规格" autocomplete="off" class="layui-input" value="">
        </div>
    </div>




    <script>

        layui.use(['laydate'], function() {

                var laydate = layui.laydate;
                laydate.render({
                    elem: '#created_at' //指定元素
                    ,type: 'datetime'
                    ,value: '{{\Carbon\Carbon::now()}}'
                });
                laydate.render({
                    elem: '#djs_at' //指定元素
                    ,type: 'datetime'
                    ,value: '{{\Carbon\Carbon::now()}}'
                });
            });
    </script>

    @if(Cache::get('editor')=='markdown')

        <div class="layui-form-item">
            <label class="layui-form-label col-sm-1">分红说明</label>
            <div class="layui-input-block">

                <div id="container" class="editor">
                    <textarea name="content">这里填写保存内容</textarea>
                </div>

                @include('markdown::encode',['editors'=>['container']])
            </div>
        </div>

    @elseif(Cache::get('editor')=='u-editor')
        <div class="layui-form-item">
            <label class="layui-form-label col-sm-1">商品详情</label>
            <div class="layui-input-block">

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


            </div>
        </div>

    @else

        <div class="layui-form-item editor">

            <label class="layui-form-label col-sm-1">产品说明</label>

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


                        $("#layui-btn").click(function(){
                            $("#container").val(layedit.getContent(layeditIndex));

                        });

                        //自定义验证规则

                        form.verify({
                            container: function(value) {
                                layedit.sync(layeditIndex);
                            }
                        });





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

                    });

                </script>

            </div>

        </div>

    @endif

  <!--  <div class="layui-form-item">
        <label class="layui-form-label col-sm-1" style="margin-top:-2px">设置分佣</label>
        <div class="layui-input-inline" style="width:30px;margin-top:7px;">一级</div>
        <div class="layui-input-inline">
            <input type="text" name="firstlevel" autocomplete="off" class="layui-input" value="">
        </div>
        <div class="layui-input-inline" style="margin-top:7px;width:30px;">元</div>
       <div class="layui-input-inline" style="width:30px;margin-top:7px;">二级</div>
       <div class="layui-input-inline">
           <input type="text" name="secondlevel" autocomplete="off" class="layui-input" value="">
       </div>
       <div class="layui-input-inline"  style="margin-top:7px;">元</div>
    </div>-->




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

