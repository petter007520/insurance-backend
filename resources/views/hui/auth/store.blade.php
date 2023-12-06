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
            <input type="text" name="name" lay-verify="required|username" autocomplete="off" class="layui-input" placeholder="名称" value="{{ $errors->store->first('name') }}">
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">排序</label>
        <div class="layui-input-inline">
            <input type="text" name="sort" lay-verify="required|sort|number" autocomplete="" class="layui-input" placeholder="排序" value="{{ $errors->store->first('email') }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">登录时间</label>
        <div class="layui-input-block">
            <input type="checkbox"  class="layui-input-inline"  lay-filter="s_hor" title="全选">
            @for($H=0;$H<24;$H++)

            <input type="checkbox" name="atlogintime[{{$H}}]" value="{{$H}}" class="layui-input-inline hor"  title="@if($H<10)&nbsp;&nbsp;@endif{{$H}}点">
            @endfor
        </div>
    </div>

    <div class="layui-form-item" pane>
        <label class="layui-form-label col-sm-1">是否禁用</label>
        <div class="layui-input-inline">
            <input type="checkbox" name="disabled"  checked lay-skin="switch" title="开关"  lay-text="启|禁">
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

            //各种基于事件的操作，下面会有进一步介绍

            //自定义验证规则
            form.verify({

            });

            form.on('checkbox(s_hor)', function(data) {

                if (data.elem.checked) {
                    $(".hor").prop({checked: true});
                } else {
                    $(".hor").prop({checked: false});
                }
                //   alert("asdfsadf");
                form.render(); //更新全部
            });

        });


    </script>
    @endsection

