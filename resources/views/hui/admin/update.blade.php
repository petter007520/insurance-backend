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
        <label class="layui-form-label col-sm-1">用户名</label>

        <div class="layui-input-inline">
            <input type="text" name="username" lay-verify="required|username" required placeholder="请输用户名" autocomplete="off" class="layui-input" value="{{ $edit->username }}" disabled="disabled">
        </div>
    </div>




    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">姓名</label>
        <div class="layui-input-inline">
            <input type="text" name="name" lay-verify="required|username" autocomplete="off" class="layui-input" placeholder="用户姓名" value="{{ $edit->name }}">
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">手机</label>
        <div class="layui-input-inline">
            <input type="tel" name="phone" lay-verify="phone" autocomplete="off" class="layui-input" placeholder="手机号码" value="{{ $edit->phone }}">
        </div>
    </div>




    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password" lay-verify="password" placeholder="请输入密码" autocomplete="off" class="layui-input" value="">
        </div>
        <div class="layui-form-mid layui-word-aux">长度（6-12位）</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">确认密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password2" lay-verify="password2" placeholder="请确认密码" autocomplete="off" class="layui-input" value="">
        </div>
        <div class="layui-form-mid layui-word-aux">长度（6-12位）</div>
    </div>




    <div class="layui-form-item">

            <label class="layui-form-label col-sm-1">角色</label>
            <div class="layui-input-inline">
                <select name="authid">
                    @if($authlist)
                        @foreach($authlist as $item)
                            <option value="{{$item->id}}" @if($item->disabled) disabled @endif @if($edit->authid == $item->id) selected="selected" @endif>{{ $item->name }}</option>

                        @endforeach
                    @endif

                </select>
            </div>
        </div>



    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">状态</label>
        <div class="layui-input-inline">
            <select name="disabled">
                <option value="1" @if($edit->disabled=="1") selected="selected" @endif>停用</option>
                <option value="0" @if($edit->disabled=="0") selected="selected" @endif>启用</option>
            </select>

        </div>
    </div>

    @if($Admin->authid==1)

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">所属上级帐号</label>
        <div class="layui-input-inline">


            <select name="adminid">
                @if($adminlist)
                    @foreach($adminlist as $admin)
                        <option value="{{$admin->id}}" @if($admin->disabled) disabled @endif @if($edit->adminid == $admin->id) selected="selected" @endif>{{ $admin->name }} ({{ $admin->username }})</option>
                    @endforeach
                @endif

            </select>

        </div>
    </div>






    @endif
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">请填写描述</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入内容" class="layui-textarea" name="remarks">{{ $edit->remarks }}</textarea>
        </div>
    </div>


@endsection
@section("layermsg")
    @parent
@endsection


@section('form')
    <script>
        layui.use('laydate', function(){
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#offdate' //指定元素
            });


        });

        layui.use('form', function(){
            var form = layui.form;

            //各种基于事件的操作，下面会有进一步介绍

            //自定义验证规则
            form.verify({

                password: function(value){
                    if(value != '' && value.length<6){
                        return '密码不能小于6位';
                    }
                }
                ,password2: function(value){
                    if(value != $("input[name='password']").val() && $("input[name='password']").val()!=''){
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
                }

            });



        });


        function checkusername(){
            $.post("{{ route($RouteController.".checkusername") }}",{
                "_token":"{{ csrf_token() }}",
                adminlogin:$("[name='adminlogin']").val()
            },function(data){

                if(data.status==0 &&　data.id){
                    $("[name='adminid']").val(data.id)
                }
                layer.msg(data.msg);

            });
        }


    </script>
    @endsection
