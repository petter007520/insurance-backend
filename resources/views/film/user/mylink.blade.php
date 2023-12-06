@extends(env('WapTemplate').'.wap')

@section("header")
    <header class="blackHeader"><a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/whiteBack.png")}}" class="left backImg"></a><span class="headerTitle">我的推广</span></header>

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


    <div class="tuijianbg" style="">
        <p class="f14 ">
        </p><div align="center">
            {{--<!--<font color=red>
<span class="fl remcommend_link" style="width:300px; overflow:hidden;text-overflow: ellipsis; white-space: nowrap;"  id="link" >&nbsp;&nbsp;http://yh.6538869.com/tj-xxxx</span>
</font>-->--}}
            <br><br>
            <br><br>
            <img src="{{route("user.QrCodeBg")}}" width="100%">
        </div><p></p>
    </div>





    <div class="inviteInput"><span class="left">我推荐的会员：</span><div class="clear"></div></div>

    <table class="inviteTab" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr class="tabTitle"><th>我的推荐码：<?php echo $Member->invicode; ?></th></tr>

        </tbody>
    </table>




    <table class="inviteTab" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th width="20%">下线会员账号</th>
            <th width="20%">下线层级</th>
            <th width="20%">注册时间</th>

        </tr>
        </thead>
        <tbody id="view">





        </tbody>
    </table>
    <table class="inviteTab" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr class="tabTitle"><th><p style="float:right;">总计充值: <?php echo $recharge; ?></th></tr>
        <tr class="tabTitle"><th>             </p>
                <p style="float:right;">总计提款: <?php echo $withdrawal; ?> </p></th></tr>

        </tbody>
    </table>
    <div class="layui-form layui-layer-page " id="layer_pages" style="margin: 0 auto;width: 85%"></div>
    <script id="demo" type="text/html">


        <%#  layui.each(d.data, function(index, item){ %>

        <tr class="odd gradeX" >
            <td><% item.username?item.username:'' %></td>
            <td><% item.cenji?item.cenji:'' %></td>
            <td><% item.date?item.date:'' %></td>

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
                    , theme: '#1E9FFF'
                    , layout: [ 'count','prev','curr','next']
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



            var url = "{{ route('user.record') }}";




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

<br><br><br>


@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

