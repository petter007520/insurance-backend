@extends('hui.layouts.appstore')



@section('title', $title)

@section('here')



@endsection

@section('addcss')

    @parent

@endsection

@section('addjs')

    @parent

@endsection



@section("mainbody")

    @parent

@endsection



@section('formbody')






    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">发给会员</label>

        <div class="layui-input-inline">

            <select name="userid" lay-verify="required" lay-search>
                <option value="0">全部</option>
                @if($member)
                    @foreach($member as $mb)
                        <option value="{{$mb->id}}">{{$mb->username}}</option>
                    @endforeach
                @endif
            </select>

        </div>

    </div>

    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">类型</label>

        <div class="layui-input-inline">

            <select name="types" lay-verify="required" lay-search>

                @if(\Cache::has('webmsgtype'))

                    @foreach(explode("|", \Cache::get('webmsgtype')) as $itme)
                        <option value="{{$itme}}" >{{$itme}}</option>
                    @endforeach
                @endif

                ……
            </select>

        </div>

    </div>



    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">消息标题</label>

        <div class="layui-input-inline">

            <input type="text" name="title" lay-verify="required" autocomplete="" class="layui-input" placeholder="消息标题" value="">

        </div>

    </div>



    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">内容</label>

        <div class="layui-input-block">
            <textarea name="content" lay-verify="required" class="layui-textarea"></textarea>


        </div>

    </div>





















@endsection

@section("layermsg")

    @parent

@endsection





@section('form')



    @endsection





