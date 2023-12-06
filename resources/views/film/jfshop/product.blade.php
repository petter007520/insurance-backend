@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <a href="javascript:history.go(-1);">
            <img src="{{asset("mobile/film/images/back.png")}}" class="left backImg">
        </a>
        <span class="headerTitle">积分商城</span>

        <a href="{{route("user.index")}}" class="headerRight"><img src="{{asset("mobile/film/images/touxiang.png")}}" height="33" style="float:right;vertical-align: middle; margin-top:5px; padding-left:5px;"></a>

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


    <div class="max goodsdetails-detail">
        <div class="big-img"><img class="i" alt="商品大图" src="{{$product->image}}" border="0"></div>
        <div class="goodsdetails-denomination">
            <div class="title-box">
                <h2 class="ng-binding">{{$product->title}}</h2>
                <h3 class="ng-binding">{{$product->descr}}</h3>
            </div>

        </div>
        <div class="my-points">
            <div class="mp-box">
                <div class="ico"><span class="txt"></span></div>

                <em>我的积分:<span class="red ng-binding">@if(isset($Member)){{$Member->integral}}@else 0 @endif</span></em>
                <em>所需积分：<span class="red ng-binding">{{$product->integral}}</span></em>
                <a href="javascript:void(0)" class="btn-login"><span class="txt"></span></a>

            </div>
        </div>
        <div class="exchange-rules">
            <div class="title"><h2>兑换规则</h2></div>
            <div class="desc">
                <span class="row-1617">1、兑换资格：平台充值用户可参与积分兑换。</span> <span class="row-1617">2、用户每充值{{Cache::get('integralratio')}}元即累计1个积分，充一次计一次，单笔充值不足{{Cache::get('integralratio')}}元或小于{{Cache::get('integralratio')}}元的部分不计入充值积分累积。(例如，单笔充值{{Cache::get('integralratio')+90}}元，则{{Cache::get('integralratio')}}元部分累计1分， 90元部分不计入充值积分累积)</span> <span class="row-1617">3、用户兑换后10个工作日内完成礼品发放（不可兑换现金），节假日顺延。)</span>                                </div>
        </div>
        <div class="exchange-rules goods-info">
            <div class="title"><h2>商品信息</h2></div>
            <div class="desc ng-binding">
                {!! \App\Formatting::Format($product->content) !!}
            </div>
        </div>
    </div>




    <div class="v-goodsdetails-bar">
        <div class="det-d">
            <div class="d-n-a">
                <div class="dec" onclick="numDec()"><span class="t">-</span></div>
                <input type="text" name="buy-num" id="J-buy-num" value="1" class="num ng-pristine ng-untouched ng-valid">
                <div class="add" onclick="numAdd()"><span class="t">+</span></div>
            </div>
            <div class="det-db">
                <button href="javascript:void(0);" class="btn" onclick="exchange();">立即兑换</button>
                <input type="hidden" name="userid" value="101">
                <input type="hidden" name="id" value="58" id="id">
            </div>
        </div>
    </div>

    <script>
        $(".desc img").css('max-width','100%');
        function numDec()
        {
            var buynum = $("#J-buy-num").val();
            if(buynum > 1){
                $("#J-buy-num").attr('value',buynum-1);
            }
        }
        function numAdd()
        {
            var buynum = $("#J-buy-num").val();
            $("#J-buy-num").attr('value',parseInt(buynum)+1);
        }
        function exchange()
        {
            var userid = "{{$UserId}}";
            if(!userid){
                layer.msg('请先登录',function () {
                    location.href="{{route("wap.login")}}";
                });

                return true;
            }
            var id = "{{$product->id}}";
            if(!id){
                layer.msg('参数错误');
                return true;
            }
            var buynum = $("#J-buy-num").val();
            location.href="/Jifen/exchange/{{$product->id}}-"+buynum+".html";
            return false;

        }
    </script>

@endsection
@section('footcategory')
@endsection

@section("footbox")

@endsection

@section("footer")

@endsection

