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
            <label>
                <select name="category_id" lay-filter="selctOnchange">
                    {!! $tree_option !!}
                </select>
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">项目名称</label>
        <div class="layui-col-md3">
            <label>
                <input type="text" name="title" lay-verify="required" required placeholder="项目名称" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>
    <div class="state">
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">产品图片</label>
        <div class="layui-col-md6">
            <button type="button" class="layui-btn" id="thumb_url" style="float:left;">
                <i class="layui-icon">&#xe67c;</i>上传产品图片
            </button>
            <span class="imgshow" style="float:left;width:100%;margin: 2px;"></span>
            <label>
                <input type="text" name="pic" lay-verify="required" class="layui-input thumb" placeholder="产品图片" style="float:left;width:50%;">
            </label>
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
        <label class="layui-form-label col-sm-1">金额</label>
        <div class="layui-input-inline">
            <label>
                <input type="number" name="price" placeholder="金额" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">静态收益率(%)</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="income_rate" placeholder="静态收益率(%)" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">财富力(倍)</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="wealth_rate" placeholder="财富力(倍)" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">对碰倍数</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="collision_times" placeholder="对碰倍数" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">中奖财富力(普通)</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="lottery_rate" placeholder="中奖财富力(普通中奖者出局倍数)" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">中奖财富力(星级)</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="lottery_star_rate" placeholder="中奖财富力(星级中奖者出局倍数)" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">开奖周期(天)</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="lottery_cycle" placeholder="开奖周期(天)" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">中奖人数(订单数少于抽奖人数，则不开奖)</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="lottery_num" placeholder="抽奖人数(订单数少于抽奖人数，则不开奖)" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">下次开奖日期</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="next_lottery_time" placeholder="下次开奖日期" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">内定中奖用户(多个用户请用|隔开)</label>
        <div class="layui-input-block">
            <label>
                <textarea placeholder="内定中奖用户(多个用户请用|隔开)" class="layui-textarea" name="list"></textarea>
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">财富等级</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="level" placeholder="财富等级" autocomplete="off" class="layui-input" value="">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">项目排序</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="sort" placeholder="项目排序" autocomplete="off" class="layui-input" value="1">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">是否返佣</label>
        <div class="layui-input-inline">
            <label>
                <select name="is_rebate">
                    <option value="1" selected="selected">反佣金</option>
                    <option value="0">无佣金</option>
                </select>
            </label>
        </div>
    </div>
	<div class="layui-form-item">
        <label class="layui-form-label col-sm-1">返佣方式</label>
        <div class="layui-input-inline">
            <label>
                <select name="rebate_type">
                    <option value="0" selected="selected">无返佣</option>
                    <option value="1">均返佣</option>
                    <option value="2">充值返，余额不返</option>
                    <option value="3">余额返，充值不返</option>
                </select>
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">投资状态</label>
        <div class="layui-input-inline">
            <label>
                <select name="status">
                    <option value="0" selected="selected">禁用</option>
                    <option value="1">启用</option>
                </select>
            </label>
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

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">描述(项目说明)</label>
        <div class="layui-input-block">
            <textarea placeholder="请填写描述" class="layui-textarea" name="describe"></textarea>
        </div>
    </div>
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
            <label class="layui-form-label col-sm-1">产品说明</label>
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
</div>
    @endif
@endsection
@section("layermsg")
    @parent
@endsection
@section('form')
    <script>
         function gradeChange(tx){
                alert(tx);
                this.options[this.options.selectedIndex].text;
            }
        layui.use('form', function(){
            var form = layui.form;
                form.on('select(selctOnchange)', function (data) {
                    $(".state").css("display","block")
                    $("input[name='pic']").attr("lay-verify","required")
                })
            });
        </script>
    @endsection
    <style>
        .yebstate{
            display:none
        }
    </style>
