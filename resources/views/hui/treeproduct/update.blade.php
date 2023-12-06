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
        <label class="layui-form-label col-sm-1">栏目</label>
        <div class="layui-input-inline">
            <select name="category_id"  lay-filter="selctOnchange">
                {!! $tree_option !!}

            </select>

        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">树木名称</label>

        <div class="layui-col-md3">
            <input type="text" name="title" lay-verify="required" required placeholder="树木名称" autocomplete="off" class="layui-input" value="{{$edit->title}}">
        </div>
    </div>



<div class="state">
  


 

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">需要能量</label>

        <div class="layui-input-inline">
            <input type="number" name="qtsl" placeholder="需要能量" autocomplete="off" class="layui-input" value="{{$edit->qtsl}}">
        </div>
    </div>

  


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">奖励金额</label>

        <div class="layui-input-inline">
            <input type="text" name="zgje" placeholder="奖励金额" autocomplete="off" class="layui-input" value="{{$edit->zgje}}">
        </div>
    </div>

    

   

  
  
  
  



    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">投资状态</label>


        <div class="layui-input-inline">
            <select name="tzzt">
                <option value="0" @if($edit->tzzt=='0') selected="selected" @endif>热售中</option>
                <option value="1" @if($edit->tzzt=='1') selected="selected" @endif>已投满</option>
                <option value="2" @if($edit->tzzt=='2') selected="selected" @endif>未发布</option>
                <option value="3" @if($edit->tzzt=='3') selected="selected" @endif>上市中</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">发布时间</label>


        <div class="layui-input-inline">
            <input type="text" name="created_at" placeholder="发布时间" autocomplete="off" class="layui-input" id="created_at" value="{{$edit->created_at}}">
        </div>
    </div>


 <!--  <div class="layui-form-item">-->
 <!--       <label class="layui-form-label col-sm-1">倒计时</label>-->
 <!--       <div class="layui-input-inline">-->
 <!--           <select name="djs">-->
 <!--               <option value="0" @if($edit->djs==0) selected="selected" @endif>关闭</option>-->
 <!--               <option value="1" @if($edit->djs==1) selected="selected" @endif>开启</option>-->
 <!--           </select>-->
 <!--       </div>-->
 <!--   </div>-->
	<!--<div class="layui-form-item">-->
 <!--       <label class="layui-form-label col-sm-1">倒计时结束</label>-->
 <!--       <div class="layui-input-inline">-->
 <!--           <input type="text" name="djs_at" placeholder="倒计时结束时间" autocomplete="off" class="layui-input" id="djs_at" value="{{$edit->djs_at}}">-->
 <!--       </div>-->
 <!--   </div>-->


    <div class="layui-form-item">

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

            <input type="text" name="pic" lay-verify="required" value="{{$edit->pic}}" class="layui-input thumb" placeholder="产品图片" style="float:left;width:50%;">


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





    <script>
    console.log({{$edit->category_id}},"23232")
        if({{$edit->category_id}}==42){
            $(".yebstate").css("display","block")
            $(".state").css("display","none")
        $("input[name='pic']").attr("lay-verify","")
        $("input[name='shijian']").attr("lay-verify","")
        $("input[name='xmgm']").attr("lay-verify","")
        }else{
             $(".yebstate").css("display","none")
             $(".state").css("display","block")
            $("input[name='pic']").attr("lay-verify","required")
            $("input[name='shijian']").attr("lay-verify","required")
            $("input[name='xmgm']").attr("lay-verify","required")
         }
        layui.use('form', function(){
            var form = layui.form;
                form.on('select(selctOnchange)', function (data) {
                  console.log(data.value,"22222222")
                    if(data.value==42){//余额宝
                        $(".yebstate").css("display","block")
                        $(".state").css("display","none")
                        $("input[name='pic']").attr("lay-verify","")
                        $("input[name='shijian']").attr("lay-verify","")
                        $("input[name='xmgm']").attr("lay-verify","")
                    }else{
                        $(".yebstate").css("display","none")
                        $(".state").css("display","block")
                        $("input[name='pic']").attr("lay-verify","required")
                        $("input[name='shijian']").attr("lay-verify","required")
                        $("input[name='xmgm']").attr("lay-verify","required")
                    }
                })
            });

        layui.use(['laydate'], function() {

            var laydate = layui.laydate;
            laydate.render({
                elem: '#created_at' //指定元素
                ,type: 'datetime'
            });

             laydate.render({
                    elem: '#djs_at' //指定元素
                    ,type: 'datetime'
                    ,value: '{{$edit->djs_at}}'
                });
        });
    </script>


<div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">描述(项目说明)</label>
        <div class="layui-input-block">
            <textarea placeholder="请填写描述" class="layui-textarea" name="describe">{{$edit->describe}}</textarea>
        </div>
    </div>



    @if(Cache::get('editor')=='markdown')

        <div class="layui-form-item">
            <label class="layui-form-label col-sm-1">产品说明</label>
            <div class="layui-input-block">

                <div id="container" class="editor">
                    <textarea name="content" style="display:none;">{!! $edit->content !!}</textarea>
                </div>

                @include('markdown::encode',['editors'=>['container']])
            </div>
        </div>




    @elseif(Cache::get('editor')=='u-editor')
        <div class="layui-form-item">
            <label class="layui-form-label col-sm-1">分红说明</label>
            <div class="layui-input-block">



            @include('UEditor::head')

            <!-- 加载编辑器的容器 -->
                <script id="container" name="content" type="text/plain">
                    {!! $edit->content !!}
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

                <textarea name="content" id="container" class="layui-hide" lay-filter="container">{!! $edit->content !!}</textarea>




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
</div>
    @endif






@endsection
@section("layermsg")
    @parent
@endsection


@section('form')

@endsection

