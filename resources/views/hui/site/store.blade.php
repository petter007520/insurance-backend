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

            <input type="text" name="name" lay-verify="required" required placeholder="名称" autocomplete="off" class="layui-input" value="{{ $errors->store->first('name') }}">

        </div>

    </div>



    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">域名</label>

        <div class="layui-input-inline">

            <input type="text" name="domain" lay-verify="required" required placeholder="域名" autocomplete="off" class="layui-input" value="{{ $errors->store->first('domain') }}">

        </div>

    </div>



    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">LOGO</label>



        <div class="layui-input-inline">

            <button type="button" class="layui-btn" id="thumb_url">
                <i class="layui-icon">&#xe67c;</i>上传图片
            </button>



            <input type="hidden" name="logo"  class="thumb">

            <br/>

            <span class="imgshow"></span>

            <script>



                layui.use('upload', function(){


                    var upload = layui.upload;

                    //执行实例
                    var uploadInst = upload.render({
                        elem: '#thumb_url' //绑定元素
                        ,url: '{{route("admin.uploads.uploadclassifyimgage")}}?_token={{ csrf_token() }}&name=' //上传接口
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

        <label class="layui-form-label col-sm-1">网站模板</label>

        <div class="layui-input-inline">
            {!! Form::select("template",$TemplateList) !!}



        </div>

    </div>


    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">站长</label>

        <div class="layui-input-inline">
            <select name="adminid">
                @if($Admins)
                    @foreach($Admins as $admin)
                        <option value="{{$admin->id}}" @if($admin->disabled) disabled @endif @if($errors->store->first('adminid') == $admin->id) selected="selected" @endif>{{ $admin->name }} ({{ $admin->username }})</option>
                    @endforeach
                @endif

            </select>



        </div>

    </div>




    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">标题</label>

        <div class="layui-input-inline">

            <input type="text" name="seotitle"  class="layui-input" placeholder="标题" value="">

        </div>

    </div>





    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">关键字</label>

        <div class="layui-input-inline">

            <input type="text" name="keywords"  class="layui-input" placeholder="关键字" value="" >

        </div>

    </div>




    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">描述</label>

        <div class="layui-input-inline">
            <textarea name="description"  class="layui-textarea"></textarea>


        </div>

    </div>







    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">排序</label>

        <div class="layui-input-inline">

            <input type="text" name="sort" lay-verify="required|number" autocomplete="" class="layui-input" placeholder="排序" value="{{ $errors->store->first('sort') }}">

        </div>

    </div>




    <div class="layui-form-item" pane>
        <label class="layui-form-label col-sm-1">是否禁用</label>
        <div class="col-sm-11">
            <input type="checkbox" name="disabled"  checked lay-skin="switch" title="开关"  lay-text="启|禁">
        </div>
    </div>




@endsection

@section("layermsg")

    @parent

@endsection





@section('form')



@endsection





