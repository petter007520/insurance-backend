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
            <label>
                <select name="category_id"  lay-filter="selctOnchange">
                    {!! $tree_option !!}
                </select>
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">产品名称</label>
        <div class="layui-col-md3">
            <label>
                <input type="text" name="title" lay-verify="required" required placeholder="产品名称" autocomplete="off" class="layui-input" value="{{$edit->title}}">
            </label>
        </div>
    </div>
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
            <label>
                <input type="text" name="pic" lay-verify="required" value="{{$edit->pic}}" class="layui-input thumb" placeholder="产品图片" style="float:left;width:50%;">
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
        <label class="layui-form-label col-sm-1">起购金额</label>
        <div class="layui-input-inline">
            <label>
                <input type="number" name="start_amount" placeholder="起购金额" autocomplete="off" class="layui-input" value="{{$edit->start_amount}}">
            </label>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">静态收益率(%)</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="income_rate" placeholder="静态收益率(%)" autocomplete="off" class="layui-input" value="{{$edit->income_rate}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">健康金倍数(倍)</label>
        <div class="layui-input-inline">
            <label>
                <input type="number" name="health_rate" placeholder="健康金倍数(倍)" autocomplete="off" class="layui-input" value="{{$edit->health_rate}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">可提现健康金倍数(%)</label>
        <div class="layui-input-inline">
            <label>
                <input type="number" name="health_ktx_rate" placeholder="可提现健康金倍数(%)" autocomplete="off" class="layui-input" value="{{$edit->health_ktx_rate}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">起购数量</label>
        <div class="layui-input-inline">
            <label>
                <input type="number" name="min_num" placeholder="起购数量" autocomplete="off" class="layui-input" value="{{$edit->min_num}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">限购数量</label>
        <div class="layui-input-inline">
            <label>
                <input type="number" name="max_num" placeholder="限购数量" autocomplete="off" class="layui-input" value="{{$edit->max_num}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">保额</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="insured_amount" placeholder="保额" autocomplete="off" class="layui-input" value="{{$edit->insured_amount}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">报销范围</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="scope" placeholder="报销范围" autocomplete="off" class="layui-input" value="{{$edit->scope}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">保障时间</label>
        <div class="layui-input-inline">
            <label>
                <input type="number" name="indemnity_time" placeholder="保障时间" autocomplete="off" class="layui-input" value="{{$edit->indemnity_time}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">保障时间名称</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="indemnity_time_name" placeholder="保障时间名称" autocomplete="off" class="layui-input" value="{{$edit->indemnity_time_name}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">保障描述</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="describe" placeholder="保障描述" autocomplete="off" class="layui-input" value="{{$edit->describe}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">延续保障</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="assure_name" placeholder="延续保障" autocomplete="off" class="layui-input" value="{{$edit->assure_name}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">延保说明</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="assure_text" placeholder="延保说明" autocomplete="off" class="layui-input" value="{{$edit->assure_text}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">保障详情</label>
        <div class="layui-input-block">
            <label>
                <textarea placeholder="保障详情" class="layui-textarea" name="assure_list">{{$edit->assure_list}}</textarea>
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">标签名称</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="tag_name" placeholder="标签名称" autocomplete="off" class="layui-input" value="{{$edit->tag_name}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">免赔额</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="compensation_amount" placeholder="免赔额" autocomplete="off" class="layui-input" value="{{$edit->compensation_amount}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">免赔额说明</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="compensation_text" placeholder="免赔额说明" autocomplete="off" class="layui-input" value="{{$edit->compensation_text}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">投保年龄</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="age" placeholder="投保年龄" autocomplete="off" class="layui-input" value="{{$edit->age}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">等待期</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="wait_time" placeholder="等待期" autocomplete="off" class="layui-input" value="{{$edit->wait_time}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">理赔说明</label>
        <div class="layui-input-block">
            <label>
                <textarea placeholder="理赔说明" class="layui-textarea" name="claims_info">{{$edit->claims_info}}</textarea>
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">项目排序</label>
        <div class="layui-input-inline">
            <label>
                <input type="text" name="sort" placeholder="项目排序" autocomplete="off" class="layui-input" value="{{$edit->sort}}">
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">是否返佣</label>
        <div class="layui-input-inline">
            <label>
                <select name="is_rebate">
                    <option value="1" @if($edit->is_rebate==1) selected="selected" @endif>反佣金</option>
                    <option value="0" @if($edit->is_rebate==0) selected="selected" @endif>无佣金</option>
                </select>
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">返佣方式</label>
        <div class="layui-input-inline">
            <label>
                <select name="rebate_type">
                    <option value="0" @if($edit->rebate_type==0) selected="selected" @endif>无返佣</option>
                    <option value="1" @if($edit->rebate_type==1) selected="selected" @endif>均返佣</option>
                    <option value="2" @if($edit->rebate_type==2) selected="selected" @endif>充值返，余额不返</option>
                    <option value="3" @if($edit->rebate_type==3) selected="selected" @endif>余额返，充值不返</option>
                </select>
            </label>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">投资状态</label>
        <div class="layui-input-inline">
            <label>
                <select name="status">
                    <option value="0" @if($edit->status==0) selected="selected" @endif>禁用</option>
                    <option value="1" @if($edit->status==1) selected="selected" @endif>启用</option>
                </select>
            </label>
        </div>
    </div>
    <script>
        $(".state").css("display","none")
        $("input[name='pic']").attr("lay-verify","")
        layui.use('form', function(){
            var form = layui.form;
                form.on('select(selctOnchange)', function (data) {
                    $(".state").css("display","block")
                    $("input[name='pic']").attr("lay-verify","required")
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
@endsection
@section("layermsg")
    @parent
@endsection
@section('form')
@endsection
