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
        <label class="layui-form-label col-sm-1">会员ID</label>
        <div class="layui-input-inline">
            <input type="text" name="bankname" autocomplete="" class="layui-input" readonly="readonly" placeholder="银行名称" value="{{$edit->userid}}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">卡片类型</label>
        <div class="layui-input-inline">
            <select name="type">
                <option value="1" @if($edit->type==1 ) selected="selected" @endif>银行卡</option>
                <option value="2" @if($edit->type==2 ) selected="selected" @endif>支付宝</option>
                <option value="3" @if($edit->type==3 ) selected="selected" @endif>USDT</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">银行名称</label>
        <div class="layui-input-inline">
            <input type="text" name="bankname" autocomplete="" class="layui-input" placeholder="银行名称" value="{{$edit->bankname }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">开户姓名</label>
        <div class="layui-input-inline">
            <input type="text" name="bankrealname"  autocomplete="" class="layui-input" placeholder="开户姓名" value="{{$edit->bankrealname }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">银行账号</label>
        <div class="layui-input-inline">
            <input type="text" name="bankcode" autocomplete="" class="layui-input" placeholder="银行账号" value="{{$edit->bankcode }}">
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">支行地址</label>
        <div class="layui-input-inline">
            <input type="text" name="bankaddress"  autocomplete="" class="layui-input" placeholder="支行地址" value="{{$edit->bankaddress }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">提现地址</label>
        <div class="layui-input-inline">
            <input type="text" name="address"  autocomplete="" class="layui-input" placeholder="提现地址" value="{{$edit->address }}">
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
            //自定义验证规则
            form.verify({
                password: function(value){
                    if(value != '' && value.length<6){
                        return '密码不能小于6位';
                    }
                }
                , paypwd: function(value){
                    if(value != '' && value.length<6){
                        return '支付密码不能小于6位';
                    }
                }
                ,password2: function(value){
                    if(value != $("input[name='password']").val() && $("input[name='password']").val()!=''){
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
