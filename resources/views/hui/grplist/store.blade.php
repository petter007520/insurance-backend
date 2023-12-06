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
        <label class="layui-form-label col-sm-1">名称</label>

        <div class="layui-input-block">
            <input type="text" name="name" lay-verify="required" required placeholder="名称" autocomplete="off" class="layui-input" value="">
        </div>
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">金额</label>
        <div class="layui-input-block">
            <input type="text" name="value" placeholder="金额" lay-verify="required"  autocomplete="off" class="layui-input" value="">

        </div>
    </div>



    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">类型</label>
        <div class="layui-input-block">
             <select name="type">
                <option value="1" selected="selected">红包</option>
                <option value="3" >实物</option>
            </select>
        </div>
    </div>
    
    
    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">比例</label>
        <div class="layui-input-block">
            <input type="text" name="rate" placeholder="比例" lay-verify="required"  autocomplete="off" class="layui-input" value="">

        </div>
    </div>
    
     <div class="layui-form-item">
        <label class="layui-form-label col-sm-1">赠送投资项目</label>
    <div class="layui-input-inline">
            <select name="p_id">
                <option value="0" selected="selected">请选择产品(无赠送)</option>
                @if($productlist)
                    @foreach($productlist as $v)
                        <option value="{{$v->id}}" >{{$v->title}}</option>
                    @endforeach
                @endif

            </select>


        </div>
    </div>



    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label col-sm-1">库存</label>
        <div class="layui-input-block">
            <input type="text" name="stock" placeholder="库存" lay-verify="required"  autocomplete="off" class="layui-input" value="">

        </div>
    </div>
    




@endsection
@section("layermsg")
    @parent
@endsection


@section('form')
    <script>


        
        layui.use('laydate', function(){
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#offdate' //指定元素
            });


        });

    


    </script>
@endsection
