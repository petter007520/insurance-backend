@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/back.png")}}"
                                                  class="left backImg"></a>
        <span class="headerTitle">新闻资讯</span>
    </header>
@endsection

@section("js")
    @parent

     <script type="text/javascript" src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
@endsection

@section("css")
    @parent
    <link href="{{asset("admin/lib/layui/css/layui.css")}}" rel="stylesheet" type="text/css"/>

@endsection

@section("onlinemsg")
    @parent
@endsection

@section('body')

    <br/>
    <div class="abtxt" style="margin-top: 35px;">
        <div class="box newslist">
            <ul class="gglist" id="view">

            </ul>
        </div>
    </div>
    <div class="abpic " id="layer_pages"></div>


        <script id="demo" type="text/html">


            <%#  layui.each(d.data, function(index, item){ %>

            <a href="<% item.url %>" class="commSafe accRight"><span class="left"><% item.title %></span></a>
            <%#  }); %>

            <%#  if(d.length === 0){ %>
            无数据
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
                        , theme: '#1E9FFF'
                        , layout: [ 'prev', 'page', 'next']
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

                @if(isset($category))
                var url = "{{ route('articles.links',["links"=>$category->links]) }}";
                @else
                var url = "{{ route('articles') }}";
                @endif



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
                            if(document.getElementById("ALLCOUNT"))
                            {
                                $('#ALLCOUNT').html('<b>为您选出'+data.list.total+'款</b>') ;
                            }


                            if (data.tree) {
                                $("#view").html('');
                                pagelist_tree(list.data, 1);
                            } else {
                                pagelist(list);
                            }


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



            $('#view').on("click",".xiala",function () {
                $(".shangla").show();
                $(".xiala").hide();
                $(this).parent().parent().parent().children().eq(1).show()
            })
            $('#view').on("click",".shangla",function () {
                $(".xiala").show();
                $(".shangla").hide();
                $(this).parent().parent().parent().children().eq(1).hide()
            })



        </script>

@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

