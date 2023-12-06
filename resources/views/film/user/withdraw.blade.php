@extends(env('WapTemplate').'.wap')

@section("header")
    <header class="blackHeader"><a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/whiteBack.png")}}" class="left backImg"></a><span class="headerTitle">我要提现</span></header>
@endsection



@section("js")
    @parent
    <script type="text/javascript" src="{{asset("mobile/wap/static/js/jquery.js")}}"></script>
    <script type="text/javascript" src="{{asset("js/layer/layer.js")}}"></script>

    <script>

        var activation='{{$Member->activation}}';

        if(activation=='0'){
            layer.msg("帐号未激活,充值激活帐号",function () {
                window.location.href="{{route('user.recharge')}}";
            });
        }
    </script>
@endsection

@section("css")

    @parent


@endsection

@section("onlinemsg")
    @parent
@endsection

@section('body')

    <form action="" method="post" id="withdraw">
    <div class="formGroup"><span class="left">选择银行卡</span><select name="cardid" id="cardid" class="right">

            <option value="{{$Member->bankcode}}">{{$Member->realname}} | {{$Member->bankname}} | {{$Member->bankcode}} </option>                                    </select>
    </div>


    <div class="formGroup"><span class="left">可提现额度</span> <span class="right">¥<?php echo $UserMoneys; ?> 元</span> </div>

    <div class="formGroup"><span class="left">提现金额</span> <input class="right" type="text" name="amount" id="amount" maxlength="8" placeholder="提现金额最低为<?php echo Cache::get("withdrawalmin");?>元" value="<?php echo Cache::get("withdrawalmin");?>"></div>

    <div class="formGroup"><span class="left">支付密码</span> <input type="password" class="right" name="paypwd" id="paypwd" placeholder="输入支付密码" maxlength="18">			</div>


    <button type="button" class="finishReg" id="tixian-btn" onclick="SubForm()">申请提现</button>

        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

    </form>




<script>


    function SubForm() {

       var datas= $("#withdraw").serialize();

        $.ajax({
            url: '{{route("user.withdraw")}}',
            type: 'post',
            data: datas,
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
                             window.location.reload();
                        }else if(data.url){
                            window.location.href=data.url;
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

