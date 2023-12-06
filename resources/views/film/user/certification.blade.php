@extends(env('WapTemplate').'.wap')

@section("header")
    <header class="blackHeader"><a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/whiteBack.png")}}" class="left backImg"></a><span class="headerTitle">实名认证</span></header>
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

    <form action="" method="post">

    <div class="formGroup"><span class="left">姓名</span>
        <input name="realname" id="realname" type="text" value="{{$Member->realname}}" placeholder="输入姓名" class="right"></div>


    <div class="formGroup"><span class="left">身份证号码</span>
        <input name="card" id="identy" value="{{$Member->card}}" type="text" placeholder="输入有效身份证号" class="right"></div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

    <button type="button" class="finishReg" id="dlmima-btn" onclick="SubForm()">完成修改</button>

    </form>

    <script>


        function SubForm(id) {

            var _realname= $.trim($('#realname').val());
            var _identy = $.trim($('#identy').val());
            var reg_name = /^[a-z_A-Z·\u4e00-\u9fa5]+$/;
            if(_realname==''|| !reg_name.test(_realname)){
                layer.msg('姓名不为空或格式不对');
                return false;
            }
            if(!checkIdcard(_identy)){
                layer.msg('身份证号码不对');
                return false;
            }
            if(!Age(_identy)){
                layer.msg('身份证号年龄需大于18岁');
                return false;
            }




            $.ajax({
                url: '{{route("user.edit")}}',
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



        function checkIdcard(b) {
            b = b.toUpperCase();
            if (!/(^\d{15}$)|(^\d{17}([0-9]|X)$)/.test(b))
                return !1;
            var a;
            a = b.length;
            if (15 == a) {
                a = RegExp(/^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/);
                a = b.match(a);
                var c = new Date("19" + a[2] + "/" + a[3] + "/" + a[4]);
                if (a = c.getYear() == Number(a[2]) && c.getMonth() + 1 == Number(a[3]) && c.getDate() == Number(a[4])) {
                    a = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
                    var c = "10X98765432".split(""), e = 0, d;
                    b = b.substr(0, 6) + "19" + b.substr(6, b.length - 6);
                    for (d = 0; 17 > d; d++)
                        e += b.substr(d, 1) * a[d];
                    return !0
                }
                return !1
            }
            if (18 == a)
                if (a = RegExp(/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/),
                    a = b.match(a),
                    c = new Date(a[2] + "/" + a[3] + "/" + a[4]),
                    a = c.getFullYear() == Number(a[2]) && c.getMonth() + 1 == Number(a[3]) && c.getDate() == Number(a[4])) {
                    a = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
                    c = "10X98765432".split("");
                    for (d = e = 0; 17 > d; d++)
                        e += b.substr(d, 1) * a[d];
                    if (c[e % 11] != b.substr(17, 1))
                        return !1
                } else
                    return !1;
            return !0
        }
        function Age(b) {
            if (!b)
                return !1;
            var a = b.substring(6, 14);
            b = new Date;
            a = new Date(a.substring(0, 4),a.substring(4, 6),a.substring(6, 8));
            return 18 <= Math.round((b - a) / 31536E6)
        }


    </script>


@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

