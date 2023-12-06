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

        <label class="layui-form-label col-sm-1">发送模板</label>

        <div class="layui-input-inline">

            <select name="sendtype" lay-verify="required" lay-filter="sendtype">

                <option value="2">自定义内容</option>
                <option value="1">短信模板</option>
            </select>

        </div>

    </div>

    <div class="layui-form-item GetTypeNameList" style="display: none">

        <label class="layui-form-label col-sm-1">短信模板</label>

        <div class="layui-input-inline">

            <select name="category_id" lay-filter="category_id" lay-search>
                <option value="" >短信模板</option>
                @if(\App\Smstmp::GetTypeNameList())

                    @foreach(\App\Smstmp::GetTypeNameList()  as $tkey=> $itme)
                        <option value="{{$tkey}}" >{{$itme}}</option>
                    @endforeach
                @endif


            </select>

        </div>

    </div>




    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">内容</label>

        <div class="layui-input-block">
            <textarea name="contents" lay-verify="required" class="layui-textarea"></textarea>


        </div>

    </div>





















@endsection

@section("layermsg")

    @parent

@endsection





@section('form')
<script>

    var Content=[];
    @if(\App\Smstmp::GetContentList())

    @foreach(\App\Smstmp::GetContentList()  as $tkey=> $itme)
        Content['{{$tkey}}']='{{$itme}}';
    @endforeach
            @endif
    layui.use('form', function() {
        var form = layui.form;


        form.on('select(sendtype)', function(data){
            //layer.alert(Content[data.value]);

            if(data.value==1){
                $(".GetTypeNameList").show();
            }else{
                $(".GetTypeNameList").hide();
            }

        });


        form.on('select(category_id)', function(data){
            //layer.alert(Content[data.value]);
            $("[name='contents']").val(Content[data.value]);
        });
    });


</script>


@endsection





