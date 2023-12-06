@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/back.png")}}"
                                                  class="left backImg"></a>
        <span class="headerTitle">关于我们</span>
    </header>
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

    <div class="investTop">

        <div class="instru">{{$title}}</div>

        <div class="line">

            <div class="baseInfo">
                {!! \App\Formatting::Format($article->ccontent) !!}
            </div>

            <div>&nbsp;</div>

        </div>

    </div>

@endsection

@section('footcategoryactive')

@endsection
@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

