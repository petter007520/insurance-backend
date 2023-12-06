@extends(env('WapTemplate').'.wap')

@section("header")
    <header class="blackHeader"><a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/whiteBack.png")}}" class="left backImg"></a><span class="headerTitle">安全设置</span></header>
@endsection

@section("js")
    @parent


@endsection

@section("css")

    @parent


@endsection

@section("onlinemsg")

@endsection

@section('body')


    <div class="commSafe"><i class="safePhone left"></i><span class="left">手机号</span><span class="right">{{\App\Member::DecryptPassWord($Member->mobile)}}</span></div>


    <a href="{{route("user.certification")}}" class="commSafe marginTop"><i class="saferealName left"></i><span class="left">实名认证</span><span class="right">{{$Member->realname}}</span></a>

    <a href="{{route("user.password")}}" class="commSafe accRight"><i class="safeLoginpwd left"></i><span class="left">修改登录密码</span></a>


    <a href="{{route("user.paypwd")}}" class="commSafe accRight"><i class="safePaypwd left"></i><span class="left">修改支付密码</span></a>


    <a href="{{route("wap.loginout")}}" class="finishReg logout">退出当前账户</a>






@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

