@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <a href="javascript:history.go(-1);">
            <img src="{{asset("mobile/film/images/back.png")}}" class="left backImg">
        </a>
        <span class="headerTitle">积分商城</span>
    </header>



@endsection

@section("js")
    @parent


@endsection

@section("css")

    @parent
    <link rel="stylesheet" href="{{asset("js/layui/css/layui.css")}}"/>

@endsection

@section("onlinemsg")

@endsection

@section('body')


    <div class="top_slide" style="margin-top: 50px;">
        @if($wapad['banner'])
            @foreach($wapad['banner'] as $ad)
                <a href="{{$ad->url}}"><img src="{{$ad->thumb_url}}"></a>
            @endforeach
        @endif
    </div>

    <div class="max mall-index-content ">
        <div class="integral-record">
            <span class="split"></span>
            <a class="btns integral" href="{{route("wap.exchangelog")}}">
                <img class="i" src="{{asset("mobile/film/images/ico-integral.png")}}" border="0">
                <span class="txt">积分<em class="ng-binding"></em></span>
            </a>
            <a class="btns record" href="{{route("wap.exchangelog")}}">
                <img class="i" src="{{asset("mobile/film/images/ico-record.png")}}" border="0">
                <span class="txt">兑换记录</span>
            </a>
        </div>
        <div class="card-box">
        </div>
        <div class="list-of-prizes">
            <div class="title" style="border-left:0px;padding-left:0px"><h2>积分兑换</h2></div>
            <div class="list" id="view">

            </div>
        </div>
    </div>


    <div class="layui-form layui-layer-page " id="layer_pages" style="margin: 0 auto;width: 80%"></div>



    <script id="demo" type="text/html">


        <%#  layui.each(d.data, function(index, item){ %>

        <a class="link" href="/Jifen/<% item.id %>.html">
            <span class="img"><img class="i" border="0" src="<% item.image %>"></span>
            <span class="txt ng-binding"><% item.title %></span>
            <span class="bottom">

                    <em class="right">
                        <span class="red ng-binding"><% item.integral %></span>
                        积分
                    </em>
                </span>
        </a>




        <%#  }); %>

        <%#  if(d.length === 0){ %>
        暂无记录
        <%#  } %>

    </script>

    <script>


        layui.use(['laypage', 'layer', 'form'], function () {
            var $ = layui.jquery;
            var layer = layui.layer;
            var form = layui.form;
            var laypage = layui.laypage;

            var obj={

            };
            lists(1, obj);

        });

        function pageshow(page_count, pagesize, page,op) {
            layui.use('laypage', function () {
                var laypage = layui.laypage;



                laypage.render({
                    elem: 'layer_pages'
                    , count: page_count //数据总数，从服务端得到
                    , curr: page
                    , limit: pagesize
                    //, groups: 3
                    , theme: '#1E9FFF'
                    , layout: [ 'count','prev', 'curr', 'next']
                    , jump: function (obj, first) {

                        //首次不执行
                        if (!first) {
                            lists(obj.curr, op);
                        }
                    }
                });
            });

        }

        function lists(page = 1, op2 = {}) {

            var op1 = {
                page: page,
                "_token": "{{ csrf_token() }}"
            };

            var obj = Object.assign(op1, op2);



            var url = "{{ route('wap.shop') }}";




            $.ajax({
                url: url,
                type: "post",     //请求类型
                data: obj,  //请求的数据
                dataType: "json",  //数据类型
                beforeSend: function () {
                    // 禁用按钮防止重复提交，发送前响应
                    // var index = layer.load();

                },
                success: function (data) {
                    //laravel返回的数据是不经过这里的
                    if (data.status == 0) {
                        var list = data.list;


                        pagelist(list);


                        //pageshow(data.list.last_page,page);
                        pageshow(data.list.total, data.pagesize, page,op2);


                    } else {
                        layer.msg(data.msg, {icon: 5}, function () {

                        });
                    }
                },
                complete: function () {//完成响应
                    //layer.closeAll();
                },
                error: function (msg) {
                    var json = JSON.parse(msg.responseText);
                    var errormsg = '';
                    $.each(json, function (i, v) {
                        errormsg += ' <br/>' + v.toString();
                    });
                    layer.alert(errormsg);

                },

            });


        }


        function pagelist(list) {


            layui.use(['laytpl', 'form'], function () {
                var laytpl = layui.laytpl;
                var form = layui.form;
                laytpl.config({
                    open: '<%',
                    close: '%>'
                });

                var getTpl = demo.innerHTML
                    , view = document.getElementById('view');
                laytpl(getTpl).render(list, function (html) {
                    view.innerHTML = html;
                });


                form.render(); //更新全部

            });


        }


        /**
         * js截取字符串，中英文都能用
         * @param str：需要截取的字符串
         * @param len: 需要截取的长度
         */
        function cutstr(str, len) {
            var str_length = 0;
            var str_len = 0;
            str_cut = new String();
            str_len = str.length;
            for (var i = 0; i < str_len; i++) {
                a = str.charAt(i);
                str_length++;
                if (escape(a).length > 4) {
                    //中文字符的长度经编码之后大于4
                    str_length++;
                }
                str_cut = str_cut.concat(a);
                if (str_length >= len) {
                    str_cut = str_cut.concat("...");
                    return str_cut;
                }
            }
            //如果给定字符串小于指定长度，则返回源字符串；
            if (str_length < len) {
                return str;
            }
        }






    </script>


@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
    <br/>
    <br/>
    <br/>
@endsection

