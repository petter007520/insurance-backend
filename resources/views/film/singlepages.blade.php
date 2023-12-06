@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/back.png")}}" class="left backImg"></a>
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

<br/>
                        @if($articlescategory)
                            @foreach($articlescategory as $item)
                                <a href="{{route("singlepage",["links"=>$item->links])}}" class="commSafe accRight"><span class="left">{{$item->name}}</span></a>
                            @endforeach
                        @endif


@endsection

@section('footcategoryactive')

@endsection
@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

