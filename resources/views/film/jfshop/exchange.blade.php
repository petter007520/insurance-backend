@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <a href="javascript:history.go(-1);">
            <img src="{{asset("mobile/film/images/back.png")}}" class="left backImg">
        </a>
        <span class="headerTitle">积分商城兑换</span>



    </header>



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
    <div class="formGroup">
        <span class="left">商品名称:</span>
        <span class="right">{{$product->title}}</span>
    </div>

    <div class="formGroup">
        <span class="left">兑换数量:</span>
        <span class="right">{{$request->number}}</span>
    </div>

    <div class="formGroup">
        <span class="left">收件人姓名:</span>
        <input type="text" name="name" id="calculate-realname" required="" class="spinner value numtext right">
    </div>

    <div class="formGroup">
        <span class="left">联系电话:</span>
        <input type="text" name="phone" id="calculate-phone" required="" class="spinner value numtext right">
    </div>

    <div class="formGroup">
        <span class="left">收货地址:</span>
        <input type="text" name="shouhuodizhi" id="calculate-address" required="" class="spinner value numtext right">
    </div>


    <input type="button" class="finishReg" id="subtouzi" onclick="SubForm();" value="立即兑换">
    <input type="hidden" name="productid" value="{{$product->id}}" id="productid">
    <input type="hidden" name="productname" value="{{$product->title}}" id="productname">
    <input type="hidden" name="number" value="{{$request->number}}">


        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    </form>

    <script>


        function SubForm(id) {


            $.ajax({
                url: '{{route("wap.exchange",["id"=>$product->id,"number"=>$request->number])}}',
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
@section('footcategory')
    @parent
@endsection

@section("footbox")

@endsection

@section("footer")
    @parent
@endsection

@section('footcategoryactive')

@endsection