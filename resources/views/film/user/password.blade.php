@extends(env('WapTemplate').'.wap')

@section("header")
    <header class="blackHeader"><a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/whiteBack.png")}}" class="left backImg"></a><span class="headerTitle">登录密码</span></header>
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

    <form  method="post" >


    <div class="formGroup"><span class="left">原登陆密码</span>
        <input type="password" name="pass" id="pass" class="right" placeholder="请输入登陆密码"></div>



    <div class="formGroup"><span class="left">新登陆密码</span>
        <input type="password" name="newpass" id="newpass" class="right" placeholder="请输入新登陆密码"></div>


    <div class="formGroup"><span class="left">确认密码</span>
        <input class="right" type="password" name="renewpass" id="renewpass" placeholder="请输入确认密码"></div>

        <button type="button" class="finishReg" id="dlmima-btn" onclick="SubForm()">完成修改</button>

        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

    </form>



    <script>


        function SubForm(id) {


            $.ajax({
                url: '{{route("user.password")}}',
                type: 'post',
                data: $("form").serialize(),
                dataType: 'json',
                error: function () {
                },
                success: function (data) {
                    layer.open({
                        content: data.msg,
                        btn: '确定',
                        shadeClose: false,
                        yes: function(index){
                            if(data.status==0){
                                history.go(-1);
                            }

                            layer.close(index);
                        }
                    });
                }
            });
        }

    </script>


@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

