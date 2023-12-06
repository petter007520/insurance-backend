@extends(env('WapTemplate').'.wap')

@section("header")
    <header class="blackHeader"><a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/whiteBack.png")}}" class="left backImg"></a><span class="headerTitle">在线充值</span></header>
@endsection

@section("js")
    @parent

@endsection

@section("css")

    @parent


@endsection

@section("onlinemsg")
    @parent
@endsection

@section('body')





    <form id="recharge">



    </form>





<script>
    @if($Payments)
        @foreach($Payments as $k=> $Payment)
            @if($k==0)
            payconfig({{$Payment->id}})
            @endif

        @endforeach
    @endif

    function payconfig(id) {


        var _token="{{csrf_token()}}";
        $.ajax({
            type : "POST",
            url : "{{route("user.payconfig")}}",
            dataType : "json",
            data:{
                payid:id,
                _token:_token,
            },
            success : function (data) {
                if(data.status == 0){


                    $("form").html(data.html);


                }else{


                    layer.open({
                        content: data.msg,
                        btn: '确定',
                        shadeClose: false,
                        yes: function(index){
                            layer.close(index);
                        }
                    });
                }
            }
        });


/*
        layer.open({
            content: id,
            btn: '确定',
            shadeClose: false,
            yes: function(index){

                layer.close(index);
            }
        });*/


    }


    function SubForm() {

       var datas= $("#recharge").serialize();

        $.ajax({
            url: '{{route("user.recharge")}}',
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

