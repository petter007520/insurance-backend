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



    <!--<div class="layui-form-item">-->
    <!--    <label class="layui-form-label col-sm-1">栏目</label>-->
    <!--    <div class="layui-input-inline">-->
    <!--        <select name="category_id">-->
    <!--            {!! $tree_option !!}-->

    <!--        </select>-->

    <!--    </div>-->
    <!--</div>-->


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">进度</label>

        <div class="layui-input-block">
            <input type="text" name="xmjd" lay-verify="required" required placeholder="进度" autocomplete="off" class="layui-input" value="{{$edit->xmjd}}">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">排序</label>
        <div class="layui-input-inline">
            <input type="number" name="sort"  class="layui-input" placeholder="排序" value="{{$edit->sort}}">
        </div>
    </div>



    @if(Cache::get('editor')=='markdown')


        <div id="container" class="editor">
            <textarea name="content" style="display:none;">{{$edit->introduction}}</textarea>
        </div>

        @include('markdown::encode',['editors'=>['introduction']])



    @elseif(Cache::get('editor')=='u-editor')

        @include('UEditor::head')

        <!-- 加载编辑器的容器 -->
        <script id="container" name="introduction" type="text/plain">
            {!! $edit->introduction !!}
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

                <textarea name="content" id="container" class="layui-hide" lay-filter="container">{{$edit->introduction}}</textarea>




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

@endsection
