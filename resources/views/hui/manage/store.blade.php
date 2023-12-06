@extends('hui_layouts.appuistore')

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
        <label class="layui-form-label">用户名</label>

        <div class="layui-input-inline">
            <input type="text" name="username" lay-verify="required|username" required placeholder="请输用户名" autocomplete="off" class="layui-input" value="{{ $errors->store->first('username') }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">姓名</label>
        <div class="layui-input-inline">
            <input type="text" name="name" lay-verify="required|username" autocomplete="off" class="layui-input" placeholder="用户姓名" value="{{ $errors->store->first('name') }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">卡号</label>
        <div class="layui-input-inline">
            <input type="tel" name="cardnumber" autocomplete="" class="layui-input" placeholder="卡号" value="{{ $errors->store->first('cardnumber') }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手机</label>
        <div class="layui-input-inline">
            <input type="tel" name="phone" lay-verify="phone" autocomplete="" class="layui-input" placeholder="手机号码" value="{{ $errors->store->first('phone') }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-inline">
            <input type="text" name="email" lay-verify="email" autocomplete="" class="layui-input" placeholder="邮件地址" value="{{ $errors->store->first('email') }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password" lay-verify="password" placeholder="请输入密码" autocomplete="off" class="layui-input" value="{{ $errors->store->first('password') }}">
        </div>
        <div class="layui-form-mid layui-word-aux">长度（6-12位）</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">确认密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password2" lay-verify="password2" placeholder="请确认密码" autocomplete="off" class="layui-input" value="{{ $errors->store->first('password2') }}">
        </div>
        <div class="layui-form-mid layui-word-aux">长度（6-12位）</div>
    </div>

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">用户分组</label>
            <div class="layui-input-block">
                <select name="authid">
                    @if($authlist)
                        @foreach($authlist as $auth)
                            <option value="{{$auth->id}}" @if($auth->disabled) disabled @endif @if($errors->store->first('authid') == $auth->id) selected="selected" @endif>{{ $auth->name }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>

    </div>

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">选择站点</label>
            <div class="layui-input-block">
                <select name="storeid">
                    @if($storelist)
                        @foreach($storelist as $auth)
                            <option value="{{$auth->id}}" @if($auth->disabled) disabled @endif @if($errors->store->first('storeid') == $auth->id) selected="selected" @endif>{{ $auth->name }}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>

    </div>



    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">请填写描述</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入内容" class="layui-textarea" name="remarks">{{ $errors->store->first('remarks') }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="go">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>

@endsection
@section("layermsg")
    @parent
@endsection


@section('form')
    <script>

        layui.use('form', function(){
            var form = layui.form();

            //各种基于事件的操作，下面会有进一步介绍

            //自定义验证规则
            form.verify({
                username: function(value){
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
                }

            });



            });


        </script>
    @endsection