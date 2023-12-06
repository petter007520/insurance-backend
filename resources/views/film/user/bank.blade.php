@extends(env('WapTemplate').'.wap')

@section("header")
    <header class="blackHeader"><a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/whiteBack.png")}}" class="left backImg"></a><span class="headerTitle">银行卡信息</span></header>
@endsection

@section("css")

    @parent

@endsection

@section("js")

    @parent


@endsection

@section("onlinemsg")

@endsection

@section('body')


    <?php
    if($Member->realname==''){
    ?>
    <script>
        layer.open({
            content: '请先进行实名认证',
            btn: '确定',
            shadeClose: false,
            yes: function(index){
                parent.location.href='{{route("user.certification")}}';

                layer.close(index);
            }
        });
    </script>
    <?php
      }
    ?>


    @if($Member->isbank==1)
    <div class="formGroup marginTop"><img src="{{asset("mobile/film/images/bank.png")}}" class="left"><span class="left marginLeft">{{$Member->bankname}} {{$Member->realname}}<br>{{$Member->bankcode}}</span>
    </div>
    @else



    <div style="text-align:center">您绑定银行卡的开户名必须与您认证的实名一致，否则将无法成功提现。</div>

    <form action="" method="post">

    <div class="formGroup"><span class="left">开户人姓名</span>

        <input name="bankrealname" class="right" id="bankrealname" value="{{$Member->realname}}" readonly type="text"></div>

    <div class="formGroup"><span class="left">选择银行</span>

        <select class="right" name="bankname" id="bankname">
            <option value="">--请选择--</option>
            <option value="支付宝">支付宝</option>
            <option value="中国农业银行">中国农业银行</option>
            <option value="中国工商银行">中国工商银行</option>
            <option value="中国建设银行">中国建设银行</option>
            <option value="中国银行">中国银行</option>
            <option value="招商银行">招商银行</option>
            <option value="交通银行">交通银行</option>
            <option value="浦发银行">浦发银行</option>
            <option value="广发银行">广发银行</option>
            <option value="中信银行">中信银行</option>
            <option value="中国光大银行">中国光大银行</option>
            <option value="兴业银行">兴业银行</option>
            <option value="深圳发展银行">深圳发展银行</option>
            <option value="中国民生银行">中国民生银行</option>
            <option value="华夏银行">华夏银行</option>
            <option value="平安银行">平安银行</option>
            <option value="中国邮政储蓄银行">中国邮政储蓄银行</option>
            <option value="渤海银行">渤海银行</option>
            <option value="东亚银行">东亚银行</option>
            <option value="宁波银行">宁波银行</option>
            <option value="微商银行">微商银行</option>
            <option value="富滇银行">富滇银行</option>
            <option value="广州银行">广州银行</option>
            <option value="上海农村商业银行">上海农村商业银行</option>
            <option value="大连银行">大连银行</option>
            <option value="东莞银行">东莞银行</option>
            <option value="河北银行">河北银行</option>
            <option value="江苏银行">江苏银行</option>
            <option value="宁夏银行">宁夏银行</option>
            <option value="齐鲁银行">齐鲁银行</option>
            <option value="厦门银行">厦门银行</option>
            <option value="苏州银行">苏州银行</option>
            <option value="温州市商业银行">温州市商业银行</option>
            <option value="上海银行">上海银行</option>
            <option value="杭州银行">杭州银行</option>
            <option value="南京银行">南京银行</option>
        </select></div>


    <div class="formGroup"><span class="left">银行卡号</span><input name="bankcode" id="bankcode" value="{{$Member->bankcode}}" type="text" class="right"></div>



    <div class="formGroup"><span class="left">开户行</span><input name="bankaddress" id="bankaddress" value="{{$Member->bankaddress}}" type="text" class="right"></div>



    <input class="finishReg" onclick="SubForm();" id="subcard" type="button" value="立即添加">

        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    </form>







    <script>


        function SubForm(id) {


            $.ajax({
                url: '{{route("user.bank")}}',
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
@endif

@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

