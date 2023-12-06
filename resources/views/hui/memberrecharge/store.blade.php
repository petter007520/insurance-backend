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

        <label class="layui-form-label col-sm-1">会员帐号</label>
        <div class="layui-input-inline">
        <select name="userid" lay-verify="required" lay-search>
            @if($member)
                @foreach($member as $mb)
                    <option value="{{$mb->id}}">{{$mb->username}}</option>
                @endforeach
            @endif
        </select>

        </div>
    </div>




    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">充值方式</label>
        <div class="layui-input-inline">
            <select name="paymentid" lay-verify="required" lay-search>

                @if($payment)

                    @foreach($payment as $payk=> $itme)
                        <option value="{{$payk}}" >{{$itme}}</option>
                    @endforeach
                @endif

            </select>

        </div>
    </div>

    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">充值类型</label>
        <div class="layui-input-inline">
            <select name="type" lay-verify="required" lay-search>

                @if(\Cache::has('RechargeType'))

                    @foreach(explode("|", \Cache::get('RechargeType')) as $itme)
                        <option value="{{$itme}}" >{{$itme}}</option>
                    @endforeach
                @endif

            </select>

        </div>
    </div>








    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">充值金额</label>

        <div class="layui-input-inline">

            <input type="text" name="amount"   lay-verify="required" class="layui-input" placeholder="充值金额" value="100">

        </div>
        {{--<label class="layui-form-label col-sm-1" style="text-align: left">%</label>--}}
    </div>








    <div class="layui-form-item">

        <label class="layui-form-label col-sm-1">备注说明</label>

        <div class="layui-col-md4">

            <textarea name="memo" placeholder="备注说明" class="layui-textarea" ></textarea>
        </div>

    </div>





@endsection

@section("layermsg")

    @parent

@endsection





@section('form')



@endsection





