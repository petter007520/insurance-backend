@extends(env('WapTemplate').'.wap')
<style type="text/css">
.tabbox{margin:10px;}
.tabbox ul{list-style:none;display:table;}
.tabbox ul li{float:left;line-height:30px;text-align:center;padding:1px 8px;border:1px solid #aaccff;margin-right:-1px;cursor:pointer;}
.tabbox ul li.active{background-color:#e73839;color:white;font-weight:bold;}
.tabbox ul li.tabdisabled{background-color: #F5F5F5; color:#ACA899;font-weight:italic;border:1px solid #ACA899;cursor:Default;}
.tabbox .content{border:1px solid #aaccff;padding:10px;}
.tabbox .content>div{display:none;}
.tabbox .content>div.active{display:block;}
</style>
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
                amount:$("#price").val(),
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

