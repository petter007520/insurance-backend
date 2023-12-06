@extends('wap.wap')

@section("header")
    @parent
    <div class="top" id="top" >
        <div class="kf">
            <p><a class="sb-back" href="javascript:history.back(-1)" title="返回"
                  style=" display: block; width: 40px;    height: 40px;
                          margin: auto; background: url('{{asset("mobile/images/arrow_left.png")}}') no-repeat 15px center;float: left;
                          background-size: auto 16px;font-weight:bold;">
                </a>
            </p>
            <div style="display: block;width:100%; position: absolute;top: 0;
     left: 0;text-align: center;  height: 40px; line-height: 40px; ">
                <a href="javascript:;" style="text-align: center;  font-size: 16px; ">{{Cache::get('CompanyLong')}}</a>
            </div>

        </div>
    </div>

    <link rel="stylesheet" href="{{asset("mobile/public/Front/css/common.css")}}" />

    <link rel="stylesheet" type="text/css" href="{{asset("mobile/public/style/css/style.css")}}"/>
    <link href="{{asset("mobile/public/Front/user/user.css")}}" type="text/css" rel="stylesheet">
    <script type="text/javascript" charset="utf-8" src="{{asset("mobile/public/Front/user/user.js").'?t='.time()}}"></script>

@endsection

@section("js")
    @parent

     <script type="text/javascript" src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset("admin/lib/layui/css/layui.css")}}"/>
@endsection

@section("css")

    @parent


@endsection

@section("onlinemsg")
    @parent
@endsection

@section('body')

    <div class="user_zx_right" >
        <div class="box" style="margin-top: 50px">
            <div class="tagMenu">
                <ul class="menu">
                    <li><a href="{{route("user.mylink")}}">推广链接</a></li>
                    <li><a href="{{route("user.record")}}">推广记录</a></li>
                    <li class="current"><a href="{{route("user.offline")}}">下线分成</a></li>
                    <li><a href="{{route("user.offline.budget")}}">下线收支</a></li>

                </ul>
                <div class="hite"> <span id="account"></span> </div>
            </div>
        </div>
        <div class="myinfo" style="padding: 20px; margin-bottom: 15px;background:#fff;">

            <p style="margin:15px 0px;">尊敬的<?php \Cache::get('CompanyShort'); ?>会员，以下是您在<?php echo \Cache::get('CompanyShort'); ?>的推广分成记录，敬请审阅！</p>
            <p>截止 <?php echo \Carbon\Carbon::now()->format("y-m-d H:i"); ?>  您的下线投资抽成成功： <span style="color:#3579f7; font-size:14px;">￥<?php echo $chenggong; ?></span>。</p>

            <div class="container-fluid">
                <div class="row-fluid">
                    <table class="datatable table table-striped table-bordered table-hover ">
                        <thead>
                        <tr>
                            <th>分成时间</th>
                            <th>下线会员账号</th>
                            <th>投资金额</th>
                            <th>抽成金额</th>
                            <th>状态</th>
                        </tr>
                        </thead>
                        <tbody id="view">





                        </tbody>
                    </table>



                </div>
            </div>
            <div class="layui-form layui-layer-page " id="layer_pages">
            </div>
        </div>

    </div>


    <script id="demo" type="text/html">


        <%#  layui.each(d.data, function(index, item){ %>

        <tr class="odd gradeX" >
            <td><% item.date?item.date:'' %></td>
            <td><% item.xxusername?item.xxusername:'' %>|<% item.xxcenter?item.xxcenter:'' %></td>
            <td><% item.amount?item.amount:'' %></td>
            <td><% item.preamount?item.preamount:'' %></td>

            <%# if(item.status==1){ %>
            <td class="center" ><font color="#00A11D">成功</font></td>
            <%# }else{ %>
            <td class="center" ><font color="#ff0000">失败 </font></td>
            <%# } %>

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
                    , layout: [ 'count','prev', 'page', 'next']
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



            var url = "{{ route('user.offline') }}";




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
@endsection

