@extends(env('WapTemplate').'.wap')

@section("header")
    <header class="blackHeader"><a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/whiteBack.png")}}" class="left backImg"></a><span class="headerTitle">站内消息</span></header>
@endsection

@section("js")
    @parent


@endsection

@section("css")

    @parent
    <link rel="stylesheet" href="{{asset("js/layui/css/layui.css")}}"/>

@endsection

@section("onlinemsg")
    @parent
@endsection

@section('body')

    <div class="height10"></div>
    <div style="margin-top: 50px;">

    </div>
                    <table class="inviteTab ">
                        <thead>
                        <tr>
                            <th><img src="{{asset("mobile/wap/public/Front/user/xf1.jpg")}}"></th>
                            <th>标题</th>
                            <th>时间</th>
                            <th>状态</th>
                            <th>删除</th>
                        </tr>
                        </thead>
                        <tbody id="view">

                        </tbody>
                    </table>




    <div class="layui-form layui-layer-page " id="layer_pages" style="margin: 0 auto;width: 80%"></div>



    <script id="demo" type="text/html">


        <%#  layui.each(d.data, function(index, item){ %>

        <tr >
            <td onclick="$('#sms<% item.id %>,#msg<% item.id %>').slideToggle(2);user_msg('<% item.id %>');"><% item.id %></td>
            <td onclick="$('#sms<% item.id %>,#msg<% item.id %>').slideToggle(2);user_msg('<% item.id %>');"><img src="{{asset("mobile/wap/public/Front/user/read.jpg")}}"> <% item.title %>

                <div height="34" style="display:none; width: 98%; height: auto; font-size: 12px; border: 1px dashed rgb(217, 38, 15); padding: 10px;margin: 10px 0 10px 0;
    background-color:#fffcfa;"  id="sms<% item.id %>">
                    <div style="width:100%; display:none;" id="msg<% item.id %>" ><% item.content %></div>
                </div>
            </td>
            <td onclick="$('#sms<% item.id %>,#msg<% item.id %>').slideToggle(2);user_msg('<% item.id %>');"><% item.date %></td>
            <td class="center" id="zt<% item.id %>" onclick="$('#sms<% item.id %>,#msg<% item.id %>').slideToggle(2);user_msg('<% item.id %>');"><font color="#00A11D"><% item.status?'已读':'未读' %> </font></td>
            <td class="center"  id="del<% item.id %>"><a onclick="user_msg_del('<% item.id %>')">删除</a></td>
        </tr>


        <%#  }); %>

        <%#  if(d.length === 0){ %>
        <tr>
            <td width="90%" colspan="5">暂无记录</td>
        </tr>
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



            var url = "{{ route('user.msglist') }}";




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


        function user_msg(str) {

            var url = "{{route("user.msgread")}}";
            $.post(url, {
                    "id": str,
                    "_token": "{{ csrf_token() }}"
                },
                function(data) {
                    if (data.status == 0) {
                        $("#zt" + str + "").html("<font color='#00A11D'>已读</font>");
                    }else{
                        $("#zt" + str + "").html("<font color='#00A11D'>网络异常</font>");
                    }
                });
        }




    </script>


@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

