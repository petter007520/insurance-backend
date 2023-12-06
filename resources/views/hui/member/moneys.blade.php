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
    <div class="x-body">
        {!! Form::open(['route' =>$RouteController.'.moneys','class'=>'layui-form']) !!}
        <div class="layui-form-item">

        </div>



        <div class="layui-form-item">

            <label class="layui-form-label col-sm-1">资金流向</label>
            <div class="layui-input-inline">
                <select name="moneytype" lay-verify="required" lay-search>
                            <option value="+" >充值</option>
                            <option value="-" >扣款</option>

                </select>

            </div>
        </div>

        <div class="layui-form-item">

            <label class="layui-form-label col-sm-1">会员帐号</label>
            <div class="layui-input-inline">
                <select name="userid" lay-verify="required" lay-search>
                    
                        <option value="{{$edit_money->id}}">{{$edit_money->username}}</option>
                        
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

        </div>








        <div class="layui-form-item">

            <label class="layui-form-label col-sm-1">备注说明</label>

            <div class="layui-col-md4">

                <textarea name="memo" placeholder="备注说明" class="layui-textarea" ></textarea>
            </div>

        </div>






        <div class="layui-form-item" style="display: none">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="go" id="layui-btn">新增</button>
            <a type="reset" class="layui-btn layui-btn-primary" onclick=" parent.layer.closeAll();">取消</a>
        </div>
    </div>

    {!! Form::close() !!}
@endsection



@section('formbody')



@endsection

@section("layermsg")

            <script>



                layui.use(['laypage','layer','form'], function() {
                    var $ = layui.jquery;
                    var laypage = layui.laypage;
                    var layer = layui.layer;
                    var form = layui.form;


                    @if(session("msg"))
                    @if(Cache::has("msgshowtime"))
                    layer.msg("{{ session("msg") }}", {time: '{{Cache::get('msgshowtime')}}'}, function () {
                        @if(!session("status"))

                        @if(Cache::has("closelayer") && Cache::get('closelayer')=='开启')
                        parent.layer.closeAll();
                        @endif
                        @endif
                    });
                    @else
                    layer.msg("{{ session("msg") }}", {time: '500'}, function () {
                        @if(!session("status"))
                        @if(Cache::has("closelayer") && Cache::get('closelayer')=='开启')
                        parent.layer.closeAll();
                        @endif
                        @endif
                    });
                            @endif
                            @endif


                            @if (count($errors) > 0)
                    var alert_msg = '';
                    @foreach ($errors->all() as $error)
                        alert_msg += "{{ $error }} <br/> ";
                    @endforeach
                    layer.alert(alert_msg);
                    @endif


                    //监听提交
                    form.on('submit(go)', function(data){
                        params = data.field;
                        submit($,params);
                        return false;


                    });

                    form.render(); //更新全部

                });
                /**{{$RouteController}}**/
                function submit($,params){

                    $.ajax({
                        url: "{{ route($RouteController.'.moneys') }}",
                        type:"post",     //请求类型
                        data:params,  //请求的数据
                        dataType:"json",  //数据类型
                        beforeSend: function () {
                            // 禁用按钮防止重复提交，发送前响应
                            index = layer.load();

                        },
                        success: function(data){
                            //laravel返回的数据是不经过这里的
                            if(data.status==0){
                                layer.msg(data.msg,{time:"{{Cache::get("msgshowtime")}}" },function(){
                                    @if($RouteController!='admin.menu' && Cache::has("closelayer") && Cache::get('closelayer')=='开启')
                                    parent.layer.closeAll();
                                    @endif
                                });
                            }else{
                                layer.msg(data.msg,{icon: 5},function(){

                                });
                            }
                        },
                        complete: function () {//完成响应
                            // layer.closeAll();
                            layer.close(index);
                        },
                        error: function(msg) {
                            var json=JSON.parse(msg.responseText);
                            var errormsg='';
                            $.each(json,function(i,v){
                                errormsg+=' <br/>'+ v.toString();
                            } );
                            layer.alert(errormsg);

                        },

                    });

                }

            </script>
@endsection





@section('form')



@endsection





