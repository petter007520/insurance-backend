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
        <label class="layui-form-label col-sm-1">名称</label>
        <div class="layui-input-inline">
            <input type="text" name="name" lay-verify="required|name" autocomplete="off" class="layui-input" placeholder="名称" value="{{ $edit->name }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">模型名</label>
        <div class="layui-input-inline">
            <input type="text" name="model_name" lay-verify="required|model_name" autocomplete="off" class="layui-input" placeholder="模型名" value="{{ $edit->model_name }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">控制器</label>
        <div class="layui-input-inline">
            <input type="text" name="contr_name" lay-verify="required" autocomplete="" class="layui-input" placeholder="控制器" value="{{ $edit->contr_name }}">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">操作名</label>
        <div class="layui-input-inline">
            <input type="text" name="action_name"  autocomplete="" class="layui-input" placeholder="操作名" value="{{ $edit->action_name }}">
        </div>
    </div>


    <div class="layui-form-item" pane>
        <label class="layui-form-label col-sm-1">常用操作</label>
        <div class="layui-input-inline">

            <div class="layui-btn-group ">
                <label class="layui-btn layui-btn-small" onclick="set('store','添加')"><i class="layui-icon">&#xe654;</i></label>
                <label class="layui-btn layui-btn-small" onclick="set('update','编辑')"><i class="layui-icon">&#xe642;</i></label>
                <label class="layui-btn layui-btn-small" onclick="set('delete','删除')"><i class="layui-icon">&#xe640;</i></label>
                <label class="layui-btn layui-btn-small" onclick="set('lists','列表')"><i class="layui-icon ">&#xe60a;</i></label>
                <label class="layui-btn layui-btn-small" onclick="set('index','首页')"><i class="layui-icon">&#xe62a;</i></label>

            </div>
        </div>

    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">上级</label>
        <div class="layui-input-inline">
            <select name="parent" lay-filter="parent">
                <option value="0" @if($edit->parent =='0' ) selected="selected" @endif>顶级</option>
                @if($menu)
                    @foreach($menu as $d)

                        <option value="{{ $d->id }}" @if($edit->parent ==$d->id ) selected="selected" @endif m="{{ $d->model_name }}" c="{{ $d->contr_name }}" a="{{ $d->action_name }}">{{$d->name}}</option>

                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="layui-form-item" pane>
        <label class="layui-form-label col-sm-1">是否显示菜单</label>
        <div class="layui-input-inline">


            <input type="checkbox" @if($edit->disabled ==0 )checked @endif  name="disabled" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
        </div>

    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">排序</label>
        <div class="layui-input-inline">
            <input type="text" name="sort" lay-verify="required|number" autocomplete="" class="layui-input" placeholder="排序" value="{{ $edit->sort }}">
        </div>
    </div>






@endsection
@section("layermsg")
    @parent
@endsection


@section('form')
    <script>

        function set(act,name){
            $("[name='action_name']").val(act);
            $("[name='name']").val(name);
            //$("[name='path']").val( $("[name='model_name']").val()+'/'+ $("[name='contr_name']").val()+'/'+act);

        }
        function setdatas(){
            //$("[name='path']").val( $("[name='model_name']").val()+'/'+ $("[name='contr_name']").val()+'/'+$("[name='action_name']").val());
        }

        $("[name='model_name'],[name='contr_name'],[name='action_name']").change(function(){
            setdatas();
        });


        layui.use('form', function(){
            var form = layui.form;

            //各种基于事件的操作，下面会有进一步介绍

            //自定义验证规则
            form.verify({



            });


            form.on('select(parent)', function(data){

                console.log(data.elem); //得到select原始DOM对象
                console.log(data.value); //得到被选中的值
                console.log(data.othis); //得到美化后的DOM对象
                $("select[name='parent'] option").each(function(){
                    if($(this).val()==data.value){
                        $("[name='model_name']").val($(this).attr("m"));
                        $("[name='contr_name']").val($(this).attr("c"));
                        //$("[name='action_name']").val($(this).attr("a"));
                       // $("[name='path']").val( $("[name='model_name']").val()+'/'+ $("[name='contr_name']").val()+'/'+$("[name='action_name']").val());
                    }
                });
            });



        });


    </script>
    @endsection

