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
        <label class="layui-form-label col-sm-1">会员账号</label>

        <div class="layui-input-inline">
            <input type="text" name="username" lay-verify="required|username" required placeholder="会员账号" autocomplete="off" class="layui-input" value="{{ $errors->store->first('username') }}">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">推荐人推荐号</label>
        <div class="layui-input-inline">
            <input type="text" name="inviter" lay-verify="" autocomplete="off" class="layui-input" placeholder="推荐人推荐号" value="{{ $errors->store->first('inviter') }}">
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">手机</label>
        <div class="layui-input-inline">
            <input type="tel" name="mobile" lay-verify="mobile" autocomplete="" class="layui-input" placeholder="手机号码" value="{{ $errors->store->first('mobile') }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">昵称</label>
        <div class="layui-input-inline">
            <input type="text" name="nickname" lay-verify="" autocomplete="" class="layui-input" placeholder="昵称" value="{{ $errors->store->first('nickname') }}">
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">会员密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password" lay-verify="password" placeholder="请输入密码" autocomplete="off" class="layui-input" value="{{ $errors->store->first('password') }}">
        </div>
        <div class="layui-form-mid layui-word-aux">长度（6-12位）</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">确认密码</label>
        <div class="layui-input-inline">
            <input type="password" name="password2" lay-verify="password2" placeholder="请确认密码" autocomplete="off" class="layui-input" value="{{ $errors->store->first('password2') }}">
        </div>
        <div class="layui-form-mid layui-word-aux">长度（6-12位）</div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">支付密码</label>
        <div class="layui-input-inline">
            <input type="password" name="paypwd" lay-verify="paypwd" placeholder="请确认密码" autocomplete="off" class="layui-input" value="{{ $errors->store->first('paypwd') }}">
        </div>
        <div class="layui-form-mid layui-word-aux">长度（6-12位）</div>
    </div>
    
    <!--<div class="layui-form-item">-->

    <!--        <label class="layui-form-label col-sm-1">VIP等级</label>-->
    <!--        <div class="layui-input-inline">-->
    <!--            <select name="level">-->
    <!--                @if($memberlevel)-->
    <!--                    @foreach($memberlevel as $level)-->
    <!--                        <option value="{{$level->id}}">{{$level->name}}</option>-->
    <!--                    @endforeach-->
    <!--                @endif-->

    <!--            </select>-->
    <!--        </div>-->


    <!--</div>-->


    <!--<div class="layui-form-item">-->

    <!--    <label class="layui-form-label col-sm-1">会员身份</label>-->
    <!--    <div class="layui-input-inline">-->
    <!--        <select name="mcategory">-->
    <!--            <option value="0" selected="selected">普通会员</option>-->
    <!--            <option value="1" >代理会员</option>-->
    <!--        </select>-->
    <!--    </div>-->
    <!--    <div class="layui-form-mid layui-word-aux">仅是标识，无特殊效果</div>-->
    <!--</div>-->



    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">联系Q Q</label>
        <div class="layui-input-inline">
            <input type="text" name="qq"  autocomplete="" class="layui-input" placeholder="联系Q Q" value="{{ $errors->store->first('qq') }}">
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">真实姓名</label>
        <div class="layui-input-inline">
            <input type="text" name="realname" autocomplete="" class="layui-input" placeholder="真实姓名" value="{{ $errors->store->first('realname') }}">
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">身份证号码</label>
        <div class="layui-input-inline">
            <input type="text" name="card"  autocomplete="" class="layui-input" placeholder="身份证号码" value="{{ $errors->store->first('card') }}">
        </div>
    </div>

    <!--<div class="layui-form-item">-->

    <!--    <label class="layui-form-label col-sm-1">云商户</label>-->
    <!--    <div class="layui-input-inline">-->
    <!--        <select name="is_ysh">-->

    <!--            <option value="0" selected="selected">普通会员</option>-->
    <!--            <option value="1" >云商队长</option>-->

    <!--        </select>-->
    <!--    </div>-->
    
    <!--</div>-->
    
    <!--<div class="layui-form-item">-->
    <!--    <label class="layui-form-label col-sm-1">云聊号</label>-->
    <!--    <div class="layui-input-inline">-->
    <!--        <input type="text" name="cloudchat" lay-verify="cloudchat" placeholder="请输入云聊号" autocomplete="off" class="layui-input" value="{{ $errors->store->first('cloudchat') }}">-->
    <!--    </div>-->
    <!--    <div class="layui-form-mid layui-word-aux"></div>-->
    <!--</div>-->

    <!--<div class="layui-form-item">-->
    <!--    <label class="layui-form-label col-sm-1">云商户星级</label>-->
    <!--    <div class="layui-input-inline">-->
    <!--        <input type="text" name="yshlevel" lay-verify="yshlevel" placeholder="请输入云商户星级" autocomplete="off" class="layui-input" value="{{ $errors->store->first('yshlevel') }}">-->
    <!--    </div>-->
    <!--    <div class="layui-form-mid layui-word-aux">星数（1-9颗）</div>-->
    <!--</div>-->


    <!--<div class="layui-form-item">-->
    <!--    <label class="layui-form-label col-sm-1">银行名称</label>-->
    <!--    <div class="layui-input-inline">-->
    <!--        <input type="text" name="bankname" autocomplete="" class="layui-input" placeholder="银行名称" value="{{ $errors->store->first('bankname') }}">-->
    <!--    </div>-->
    <!--</div>-->



    <!--<div class="layui-form-item">-->
    <!--    <label class="layui-form-label col-sm-1">开户姓名</label>-->
    <!--    <div class="layui-input-inline">-->
    <!--        <input type="text" name="bankrealname"  autocomplete="" class="layui-input" placeholder="开户姓名" value="{{ $errors->store->first('bankrealname') }}">-->
    <!--    </div>-->
    <!--</div>-->



    <!--<div class="layui-form-item">-->
    <!--    <label class="layui-form-label col-sm-1">银行账号</label>-->
    <!--    <div class="layui-input-inline">-->
    <!--        <input type="text" name="bankcode" autocomplete="" class="layui-input" placeholder="银行账号" value="{{ $errors->store->first('bankcode') }}">-->
    <!--    </div>-->
    <!--</div>-->



    <!--<div class="layui-form-item">-->
    <!--    <label class="layui-form-label col-sm-1">支行地址</label>-->
    <!--    <div class="layui-input-inline">-->
    <!--        <input type="text" name="bankaddress"  autocomplete="" class="layui-input" placeholder="支行地址" value="{{ $errors->store->first('bankaddress') }}">-->
    <!--    </div>-->
    <!--</div>-->




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
                username: function(value){
                    if(value.length < 2){
                        return '帐号也太短了吧';
                    }
                }
                ,paypwd: [/(.+){6,12}$/, '支付密码必须6到12位']
                ,password: [/(.+){6,12}$/, '密码必须6到12位']
                ,password2: function(value){
                    if(value != $("input[name='password']").val()){
                        return '两次输入的密码不一致';
                    }
                }
                ,mobile: function(value){
                    if(value != '' && !/^1[3|4|5|6|7|8|9]\d{9}$/.test(value)){
                        return '手机必须11位，只能是数字！';
                    }
                }

                ,email: function(value){
                    if(value !='' && !/^[a-z0-9._%-]+@([a-z0-9-]+\.)+[a-z]{2,4}$|^1[3|4|5|7|8]\d{9}$/.test(value)){
                        return '邮箱格式不对';
                    }
                },yshlevel: function(value){
                    if(value < 0 || value >=10){
                        return '星级不可大于9';
                    }
                }

            });


            });



        </script>
    @endsection
