<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{  Cache::get('sitename') }} 网站设置</title>
    <link rel="stylesheet" href="{{ asset("admin/css/font.css")}}">
    <link rel="stylesheet" href="{{ asset("admin/css/xadmin.css")}}">
    <script type="text/javascript" src="{{ asset("admin/js/jquery.min.js")}}"></script>
    <script type="text/javascript" src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset("admin/js/xadmin.js")}}"></script>
    <script type="text/javascript" src="{{ asset("admin/js/cookie.js")}}"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div class="x-body">
        {!! Form::open(['route' => $RouteController.'.siteset','class'=>'layui-form layui-form-pane1']) !!}
        <table class="layui-table x-admin layui-form">
            <colgroup>
                <col width="50">
                <col width="150">

            </colgroup>
            <thead>
            <tr>
                <th>名称</th>
                <th>值</th>
            </tr>
            </thead>
            <tbody>
            @if($list)
            @foreach ($list as $d)
                @if($d->type !='hidden')
            <tr class="lists_{{ $d->id }}">
                <td class="title_{{ $d->id }}">{{ $d->name }}</td>
                <td>
                    @if($d->type=='disabled')
                        {!! Form::text($d->keyname,$d->value,["lay-verify"=>"required","class"=>"layui-input layerupdate","data-id"=>$d->id,"disabled"=>"disabled"]) !!}
                    @endif
                    @if($d->type=='text')
                        {!! Form::text($d->keyname,$d->value,["lay-verify"=>"required","class"=>"layui-input layerupdate","data-id"=>$d->id]) !!}
                    @endif
                    @if($d->type=='textarea')
                        {!! Form::textarea($d->keyname,$d->value,["lay-verify"=>"required","class"=>"layui-textarea layerupdate","data-id"=>$d->id]) !!}
                    @endif
                        @if($d->type=='number')
                                {!! Form::number($d->keyname,$d->value,["lay-verify"=>"required","class"=>"layui-input layerupdate","data-id"=>$d->id]) !!}
                        @endif

                        @if($d->type=='select')

                                <?php
                                $data=explode("|",$d->valuelist);
                                $datalist=[];
                                    foreach($data as $v){
                                        $datalist[$v]=$v;
                                    }
                                ?>


                                    <select name="{{$d->keyname}}" class="{{$d->id}}">
                                       @foreach($data as $v)
                                            @if($v == $d->value)
                                                <option value="{{$v}}" selected="selected" >{{$v}}</option>
                                            @else
                                                <option value="{{$v}}">{{$v}}</option>
                                            @endif
                                       @endforeach
                                    </select>

                        @endif
                        @if($d->type=='radio')

                                <?php
                                $data_radio=explode("|",$d->valuelist);

                                ?>
                                @foreach($data_radio as $v)
                                        @if($v == $d->value)
                                            <input type="radio" name="{{$d->keyname}}" value="{{$v}}" title="{{$v}}" class="{{$d->id}}"   checked="checked">
                                        @else
                                            <input type="radio" name="{{$d->keyname}}" value="{{$v}}" title="{{$v}}" class="{{$d->id}}">
                                        @endif
                                    @endforeach



                        @endif

                        @if($d->type=='switch')

                                <?php
                                $data_radio=explode("|",$d->valuelist);

                                ?>
                                    <input type="checkbox" name="{{$d->keyname}}" @if($d->value=='on') checked @endif lay-skin="switch" lay-text="{{$d->valuelist}}" class="{{$d->id}}">


                        @endif

                        @if($d->type=='checkbox')

                                <?php
                                $data_radio=explode("|",$d->valuelist);
                                $data_v=explode("|",$d->value);

                                ?>
                                @foreach($data_radio as $v)
                                        @if(in_array($v,$data_v))
                                            <input type="checkbox" name="{{$d->keyname}}" value="{{$v}}" title="{{$v}}" class="{{$d->id}}"   checked="checked">
                                        @else
                                            <input type="checkbox" name="{{$d->keyname}}" value="{{$v}}" title="{{$v}}" class="{{$d->id}}">
                                        @endif



                                    @endforeach



                        @endif


                        @if($d->type=='datetime')

                         <input required type="text" name="{{$d->keyname}}"  lay-verify="required" id="{{$d->keyname}}" onclick="layui.laydate({elem: this,format: 'YYYY-MM-DD hh:mm:ss',festival: true,min:'{{date("Y-m-d H:i:s",time())}}', istime: true,choose:function(date){ setsetings('{{$d->id}}',date)}})" class="layui-input layerupdate" placeholder="{{$d->name}}" value="{{$d->value}}" data-id="{{$d->id}}">



                            <script>
                                layui.use('laydate', function(){
                                    var laydate = layui.laydate;

                                    //执行一个laydate实例
                                    laydate.render({
                                        elem: '#{{$d->keyname}}' //指定元素
                                    });


                                });
                            </script>

                        @endif

                        @if($d->type=='upload')
                            <button type="button" class="layui-btn" id="{{$d->keyname}}">
                                <i class="layui-icon">&#xe67c;</i>上传图片
                            </button>

                        <br/>
                            <span class="imgshow{{$d->keyname}}"><img src="{{ url("uploads/".$d->value).'?time='.time()}}" width="100" style="float:left;"/></span>
                            <script>
                                layui.use('upload', function(){
                                    var upload = layui.upload;

                                    //执行实例
                                    var uploadInst = upload.render({
                                        elem: '#{{$d->keyname}}' //绑定元素
                                        ,url: '{{route($RouteController.".uplodeimg")}}?_token={{ csrf_token() }}&name={{$d->keyname}}' //上传接口
                                        , field:'files'
                                        ,done: function(src){
                                            //上传完毕回调

                                            console.log(src);
                                            if(src.status==0){
                                                layer.msg(src.msg,{time:500},function(){
                                                    $(".imgshow{{$d->keyname}}").html('<img src="'+src.src+'?t='+new Date()+'" width="100" style="float:left;"/>');
                                                });
                                            }

                                        }
                                        ,error: function(){
                                            //请求异常回调
                                        }
                                    });

                                });
                            </script>

                        @endif

                        @if($d->type=='video')

                            <span class="imgshow{{$d->keyname}}" style="display: block;height: 200px;float:left;">
{{--autoplay="autoplay"--}}
                                <video id="shakeVideo{{$d->keyname}}"  controls="controls" webkit-playsinline="true" playsinline="true" controlslist="nodownload" src="{{ url("uploads/".$d->value)}}" width="100%" height="200px"></video>

                                </span>

                            <button type="button" class="layui-btn" id="{{$d->keyname}}" style="margin-left:100px;height: 200px;float:left;">
                                <i class="layui-icon">&#xe67c;</i>上传视频
                            </button>



                            <script>
                                layui.use('upload', function(){


                                    var upload = layui.upload;

                                    //执行实例
                                    var uploadInst = upload.render({
                                        elem: '#{{$d->keyname}}' //绑定元素
                                        ,url: '{{route($RouteController.".uploadvideo")}}?_token={{ csrf_token() }}&name={{$d->value}}' //上传接口
                                        , field:'files'
                                        , exts:'mp4'
                                        ,done: function(src){
                                            //上传完毕回调

                                            console.log(src);
                                            if(src.status==0){
                                                layer.msg(src.msg,{time:500},function(){
                                                    $(".imgshow{{$d->keyname}}").html('<video id="shakeVideo{{$d->keyname}}" autoplay="autoplay" controls="controls" webkit-playsinline="true" playsinline="true" controlslist="nodownload" src="'+src.src+'" width="150px" height="100%"></video>');
                                                });
                                            }

                                        }
                                        ,error: function(){
                                            //请求异常回调
                                        }
                                    });

                                });
                            </script>

                        @endif


                        @if($d->type=='photos')

                            <button type="button" class="layui-btn" id="{{$d->keyname}}">
                                <i class="layui-icon">&#xe67c;</i>上传相册
                            </button>



                            <br/>

                            <span class="product_image_show{{$d->keyname}}">

                                @if($d->value!='')
                                <?php foreach (explode(",",$d->value) as $img){?>
                                    <img src="{{$img}}" data="'+src.src+'" width="100" style="float:left;margin:2px;"  class="productimagesrc{{$d->keyname}}"/>
                                    <?php } ?>
                                @endif


                            </span>

                            <span class="product_image_data{{$d->keyname}}" style="display: none;">

                                @if($d->value!='')
                                    <?php foreach (explode(",",$d->value) as $ik=>$img){?>
                                        <input type="hidden" name="{{$d->keyname}}[{{($ik+1)}}]" class="productimagedata{{$d->keyname}}" value="{{$img}}">
                                    <?php } ?>
                                @endif

                            </span>

                            <script>

                                layui.use(['upload','form'], function(){

                                    var uploads = layui.upload;
                                    var form = layui.form;


                                    //执行实例
                                    var Photos = uploads.render({
                                        elem: '#{{$d->keyname}}' //绑定元素
                                        ,url: '{{route("admin.uploads.uploadimg")}}?_token={{ csrf_token() }}' //上传接口
                                        , field:'thumb'
                                        ,done: function(src){

                                            if(src.status==0){

                                                layer.msg(src.msg,{time:500},function(){

                                                    var imageurl=$("input[name='image']").val();
                                                    if(imageurl==''){
                                                        $("input[name='image']").val(src.src);
                                                    }

                                                    var Number=$(".productimagedata{{$d->keyname}}").length;

                                                    $(".product_image_show{{$d->keyname}}").append('<img src="'+src.src+'?t='+new Date()+'" data="'+src.src+'" width="100" style="float:left;margin:2px;"  class="productimagesrc{{$d->keyname}}"/>');

                                                    $(".product_image_data{{$d->keyname}}").append('<input type="hidden" name="{{$d->keyname}}['+Number+']" class="productimagedata{{$d->keyname}}" value="'+src.src+'">');

                                                    var photos=[];
                                                    $(".productimagedata{{$d->keyname}}").each(function () {
                                                        photos.push($(this).val());
                                                    });

                                                    systemphotos({{$d->id}},photos);

                                                    form.render(); //更新全部

                                                });

                                            }

                                        }
                                        ,error: function(){
                                            //请求异常回调
                                        }
                                    });



                                });





                                $(document).on("click","img.productimagesrc{{$d->keyname}}",function(){

                                    $("input[value='"+$(this).attr("data")+"']").remove();
                                    $(this).remove();

                                    var photos=[];
                                    $(".productimagedata{{$d->keyname}}").each(function () {
                                        photos.push($(this).val());
                                    });

                                    systemphotos({{$d->id}},photos);


                                });



                            </script>


                        @endif
                </td>

            </tr>
                @endif
            @endforeach
            @endif

            </tbody>
        </table>
        {!! Form::close() !!}


    </div>




    <script>

        layui.use(['laypage', 'layer'], function(){
            var laypage = layui.laypage
                    ,layer = layui.layer;





        $(".layerupdate").change(function(){
            var id=$(this).attr("data-id");
            var setvalue=$(this).val();
            if(id=='' || setvalue==''){
                layer.msg("请您输入信息",{icon:5});
                return false;
            }
            $.post("{{ route($RouteController.".siteset") }}",{
                _token:"{{ csrf_token() }}",
                id:id,
                setvalue:setvalue
            },function(data){
                if(data.status==0){
                    layer.msg(data.msg,{},function(){

                    });
                }else{
                    layer.msg(data.msg,{icon:5});
                }
            });


        });


        });
        layui.use('form', function(){
            var form = layui.form;

            form.on('radio', function(data){

                console.log(data.elem.className);


                setsetings(data.elem.className,data.value);


            });


            form.on('select', function(data){

                setsetings(data.elem.className,data.value);

            });


            form.on('checkbox', function(data){
                //console.log(data.elem); //得到checkbox原始DOM对象
                //console.log(data.elem.checked); //是否被选中，true或者false
               // console.log(data.value); //复选框value值，也可以通过data.elem.value得到
                //console.log(data.elem.name); //得到美化后的DOM对象

                var name=data.elem.name;
                var cateIds=[];
                $("[name='"+name+"']:checked").each(function(index, el) {

                    cateIds.push($(this).val());

                });

                //console.log(cateIds);
                form.render(); //更新全部
                $.post("{{ route($RouteController.".siteset") }}",{
                    _token:"{{ csrf_token() }}",
                    id:data.elem.className,
                    checked:data.elem.checked,
                   // setvalue:data.value
                    setvalue:cateIds
                },function(data){
                    if(data.status==0){
                        layer.msg(data.msg,{},function(){

                        });
                    }else{
                        layer.msg(data.msg,{icon:5});
                    }
                });

            });


            form.on('switch', function(data){
                console.log(data.elem); //得到checkbox原始DOM对象
                console.log(data.elem.checked); //开关是否开启，true或者false
                console.log(data.value); //开关value值，也可以通过data.elem.value得到
                console.log(data.othis); //得到美化后的DOM对象
               var value= data.elem.checked?"on":"off";
                setsetings(data.elem.className,value);

            });



        });


        function setsetings(id,setvalue){

            $.post("{{ route($RouteController.".siteset") }}",{
                _token:"{{ csrf_token() }}",
                id:id,
                setvalue:setvalue
            },function(data){
                if(data.status==0){
                    layer.msg(data.msg,{},function(){

                    });
                }else{
                    layer.msg(data.msg,{icon:5});
                }
            });

        }


        function systemphotos(id,data) {

            $.post("{{ route($RouteController.".systemphotos") }}",{
                _token:"{{ csrf_token() }}",
                id:id,
                setvalue:data
            },function(data){


                @if(Cache::has("msgshowtime"))
                if(data.status==0){
                    layer.msg(data.msg,{time:"{{Cache::get("msgshowtime")}}" },function(){

                        lists(1);


                    });
                }else{
                    layer.msg(data.msg,{icon:5,time:"{{Cache::get("msgshowtime")}}"});
                }
                @else
                if(data.status==0){
                    layer.msg(data.msg,{},function(){
                        $(".lists_"+id).remove();
                        if(page>0){
                            lists(page);
                        }
                    });
                }else{
                    layer.msg(data.msg,{icon:5});
                }
                @endif


            });

        }
    </script>


</body>

</html>
