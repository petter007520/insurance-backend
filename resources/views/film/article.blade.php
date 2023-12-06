@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/back.png")}}"
                                                  class="left backImg"></a>
        <span class="headerTitle">{{$title}}</span>
    </header>
@endsection

@section("js")
    @parent

     <script type="text/javascript" src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
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
                {!! \App\Formatting::Format($article->content) !!}
            </div>

            <div>
            </div>

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

