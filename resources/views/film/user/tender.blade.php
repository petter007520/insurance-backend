@extends(env('WapTemplate').'.wap')

@section("header")
    <header class="blackHeader" style="background-color:royalblue"><a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/whiteBack.png")}}" class="left backImg"></a><span class="headerTitle">我的投资</span></header>

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
    <?php



    $Dlist=\App\Productbuy::where("userid",$Member->id)->where("status","1")->get();
    $Ylist=\App\Productbuy::where("userid",$Member->id)->where("status","0")->get();


    $Dmoneys=0;
    $Ymoneys=0;

    $Products= \App\Product::get();
    foreach ($Products as $Product){
        $Products[$Product->id]=$Product;
    }


    foreach ($Dlist as $item){
        //$item->rate=isset($this->Memberlevels[$item->level])?$this->Memberlevels[$item->level]->rate:'';
        if(isset($Products[$item->productid])){
            if($Products[$item->productid]->hkfs == 0){
                $moneyCount = $Products[$item->productid]->jyrsy * $item->amount/100;
                //$item->moneyCount= round($moneyCount,2);
            }else{
                $moneyCount = $Products[$item->productid]->jyrsy * $item->amount/100*($item->sendday_count-$item->useritem_count);
                //$moneyCount = $Products[$item->productid]->jyrsy * $item->amount/100*$Products[$item->productid]->shijian;
                //$item->moneyCount= round($moneyCount,2);
            }
            $Dmoneys+=$moneyCount;



        }



    }

    foreach ($Ylist as $item){
        if(isset($Products[$item->productid])){
            if($Products[$item->productid]->hkfs == 0){
                $moneyCount = $Products[$item->productid]->jyrsy * $item->amount/100;
                //$item->moneyCount= round($moneyCount,2);
            }else{
                $moneyCount = $Products[$item->productid]->jyrsy * $item->amount/100*$item->useritem_count;
                //$item->moneyCount= round($moneyCount,2);
            }

            $Ymoneys+=$moneyCount;
        }



    }


    ?>
    <script type="text/javascript" src="/mobile/my_files/jquery.min.js"></script>
    <script type="text/javascript" src="/mobile/my_files/layer.js"></script><link rel="stylesheet" href="./my_files/layer.css" id="layui_layer_layercss" style="">
    <script type="text/javascript" src="/mobile/my_files/long.js"></script>
    <link rel="stylesheet" type="text/css" href="/mobile/my_files/main.css">
    <link rel="stylesheet" href="/mobile/my_files/bootstrap.min.css">
    <link href="/mobile/my_files/css.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/mobile/my_files/_zlt.css">
    <script src="/mobile/my_files/jquery1.42.min.js"></script>
    <script src="/mobile/my_files/jquery.SuperSlide.2.1.1.js"></script>
    <script src="/mobile/my_files/TouchSlide.1.1.js"></script>

    <script type="text/javascript">
        var browserWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    </script>
    <style>
        body{ background:#fff}
        .tab_isext{font-size:12px;line-height:20px}
        .money_top{ background:url(/mobile/my_files/scs.jpg) no-repeat center bottom; background-size:100% auto}
        .jerte_ul{ padding:25px 15px; float:left; width:100%}
        .jerte_ul li{ float:left; width:50%; text-align:center;color: rgba(255,255,255,0.5); border-right:1px solid rgba(255,255,255,0.2); padding-right:19px;}
        .jerte_ul li span{ display:block}
        .jerte_ul li p{ font-size:30px; color:#fff}
        .jerte_ul li.on{ border-right:0; padding-right:0; padding-left:20px;}
        .tab_open{ padding:0 15px;}
        .tab_open .jque{ background:#fff; height:auto; line-height:normal; border:0; padding:10px 10px;
            -webkit-border-top-left-radius:10px;
            border-top-left-radius: 10px;
            -moz-border-top-left-radius: 10px;
            -webkit-border-top-right-radius:10px;
            border-top-right-radius: 10px;
            -moz-border-top-right-radius: 10px;
            border-bottom:1px solid #f1f1f1;
            padding-bottom:0
        }
        .tab_open .nav{ margin:0; padding:0; background:none; line-height:normal; height:auto; border:0}
        .tab_isext{ text-align:center; color:#999; display:block; padding-top:5px;}
        .tab_open .nav li{ margin:0; width:50%; padding:0 15px; border:0; background:none;}
        .tab_open .nav li a,.nav-tabs>li>a{ height:auto; line-height:normal; padding:10px 0; padding-bottom:12px; display:block; border:0; background:none; text-align:center}
        .nav-tabs>li>a{ margin:0; width:100%; color:#000; font-weight:bold; font-size:15px; position:relative}
        .tab_open .nav li.active a{ color:#2c51d7;border:0;}
        .nav-tabs>li>a:before{ width:100%; height:2px; position:absolute; left:0; bottom:0; content:""; background:none; display:block}
        .tab_open .nav li.active a:before{ background:#2c51d7}
        #t1{ padding:15px;}
        #t1 li{float: left;
            width: 100%;
            background: #fff;
            -moz-box-shadow: 2px 2px 15px rgba(0,0,0,0.1);
            -webkit-box-shadow: 2px 2px 15px rgba(0,0,0,0.1);
            box-shadow: 2px 2px 15px rgba(0,0,0,0.1);
            -webkit-border-radius: 10px;
            border-radius: 10px;
            -moz-border-radius: 10px; padding:15px;margin-bottom:15px; position:relative; margin-top:0; font-size:12px;}
        #t1 li .subtitle{ border-bottom:1px solid #f1f1f1; color:#222; padding-bottom:6px; float:left; width:100%}
        #t1 li .subtitle span{ display:block; float:left; font-weight:bold}
        #t1 li .subtitle font{ display:block; float:right; text-align:right}
        #t1 li .money{ color:#222; padding-top:6px; float:left; width:100%; padding-bottom:6px;}
        #t1 li .money em{ font-style:normal; color:#fc3043; }
        #t1 li .money b{ font-weight:normal; font-size:18px; color:#fc3043}
        #t1 li .money b.b{ margin-left:10px;}
        #t1 li .money span{ display:block; float:left;}
        #t1 li .money font{ display:block; float:right; text-align:right}
        #t1 li .time{ color:#222;float:left; width:100%; font-size:12px; color:#999; padding-bottom: 6px;}
        #t1 li .time span{ display:block; float:left;}
        #t1 li .time font{ display:block;float:right; text-align:right}
        /**/
        #t2{ padding:15px;}
        #t2 li{float: left;
            width: 100%;
            background: #fff;
            -moz-box-shadow: 2px 2px 15px rgba(0,0,0,0.1);
            -webkit-box-shadow: 2px 2px 15px rgba(0,0,0,0.1);
            box-shadow: 2px 2px 15px rgba(0,0,0,0.1);
            -webkit-border-radius: 10px;
            border-radius: 10px;
            -moz-border-radius: 10px; padding:15px;margin-bottom:15px; position:relative; margin-top:0; font-size:12px;}
        #t2 li .subtitle{ border-bottom:1px solid #f1f1f1; color:#222; padding-bottom:6px; float:left; width:100%}
        #t2 li .subtitle span{ display:block; float:left; font-weight:bold}
        #t2 li .subtitle font{ display:block; float:right; text-align:right}
        #t2 li .money{ color:#222; padding-top:6px; float:left; width:100%;padding-bottom:6px;}
        #t2 li .money em{ font-style:normal; color:#fc3043; }
        #t2 li .money b{ font-weight:normal; font-size:18px; color:#fc3043}
        #t2 li .money b.b{ margin-left:10px;}
        #t2 li .money span{ display:block; float:left;}
        #t2 li .money font{ display:block; float:right; text-align:right}
        #t2 li .time{ color:#222;float:left; width:100%; font-size:12px; color:#999}
        #t2 li .time span{ display:block; float:left;}
        #t2 li .time font{ display:block;float:right; text-align:right}

        .active {
             background-color: #ffffff;
        }
    </style>
    <link id="layuicss-skinlayercss" rel="stylesheet" href="/mobile/my_files/layer(1).css" media="all">

    <div class="section_big_remain" style="margin-top: 48px">

        <div class="money_top" style="background-color:royalblue">
            <div class="jerte_ul">
                <li><span>已收收益金额 (元)</span><p><?php echo sprintf("%.2f",$Ymoneys); ?></p></li>
                <li class="on"><span>预期待收收益 (元)</span><p><?php echo sprintf("%.2f",$Dmoneys); ?></p></li>
                        </div>



            <div class="clear"></div>
            <div class="tab_open">
                <div class="jque">

                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" @if(!isset($_REQUEST['status']))class="active"@endif> <a href="{{ route('user.tender') }}" aria-controls="home" role="tab" data-toggle="tab"><span>正在生产</span></a> </li>
                        <li role="presentation" @if(isset($_REQUEST['status']))class="active"@endif><a href="{{ route('user.tender',["status"=>1]) }}" aria-controls="profile" role="tab" data-toggle="tab"><span>生产结束</span></a></li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="tab-content" >
            <div role="tabpanel" class="tab-pane active" id="t1">
                <!--T1-->
                <div class="tzjl" >
                    <ul id="view">


                    </ul>
                </div>
                <!--T1 End-->
            </div>

        </div>
    </div>
    <div class="clear"></div>
    <div style="height:100px"></div>
    <script>
        $(".nav-tabs>li").click(function(){
            var neq=$(this).index();
            $(this).addClass("active").siblings().removeClass("active");
            $(".tab-content>.tab-pane").eq(neq).addClass("active").siblings().removeClass("active");
        })
    </script>
    <div style="text-align:center" id="layer_pages"></div>

    <script type="text/javascript">
        function replaceParamVal(url, paramName, replaceWith) {
            var oUrl = url.toString();
            if (oUrl.indexOf(paramName) > 0) {
                var re = eval('/(' + paramName + '=)([^?]*)/gi');
                var nUrl = oUrl.replace(re, paramName + '=' + replaceWith);
                location.href = nUrl;
            } else {
                var nUrl = oUrl + "?" + paramName + "=" + replaceWith;
                location.href = nUrl;
            }
        }


    </script>
    <!--MAN End-->
    <!--MAN End-->
    <script>
        $("#mlindex").addClass("btn-long16");
    </script>
    <script type="text/javascript">
        $('.navbarFix li:eq(3) img').attr('src', '/i1/nav3a.png');
        $('.navbarFix li:eq(3) a').css('color', '#06d996')
    </script>




    <script id="demo" type="text/html">
        <%#  layui.each(d.data, function(index, item){ %>

        <li >
            <div class="subtitle">
                <span><% item.title %></span>
                <font><% (item.sendday_count-item.useritem_count)?'剩余时间'+(item.sendday_count-item.useritem_count)+(item.qxdw=='个小时'?'时':'日'):'已结束' %></font>
            </div>
            <div class="money">
                <span>已生产：<b><% item.useritem_count %></b><em><% item.qxdw=='个小时'?'时':'日'%></em><b class="b"><% item.jyrsy*item.amount/100*item.useritem_count %></b><em>元</em></span>
                <font>投资每<% item.qxdw=='个小时'?'时':'日'%>产生：<em><% item.jyrsy*item.amount/100 %>元</em></font>
            </div>
            <div class="time">
                <span>购买时间：<% item.date %></span>
                <font>总共可以产生：<% item.jyrsy*item.amount/100*item.sendday_count %>元</font>
            </div>
             <tr class="odd gradeX">
           


            <td>
                <%# if(item.tzzt==1){ %>
                <a ng-href="<% item.url %>"   href="<% item.url %>" style="color: rgb(0, 0, 255); float: right;">下载合同</a>

                <%# }else{%>
                <a ng-href="<% item.url %>" href="<% item.url %>" style="color: rgb(0, 0, 255); float: right;">下载合同</a>
                    <%# } %>
            </td>
        </tr>
        


        <%#  }); %>

        </li>

        
       
        <%#  if(d.length === 0){ %>
        <tr>
            <td width="90%" colspan="5">暂无记录</td>
        </tr>
        <%#  } %>

    </script>

    <script>


        layui.use(['laypage', 'layer', 'form'], function () {
            //var $ = layui.jquery;
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
                    ,groups:2
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


            @if(isset($_REQUEST['status']))
            var url = "{{ route('user.tender',["status"=>$_REQUEST['status']]) }}";
            @else
            var url = "{{ route('user.tender') }}";
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

                var getTpl = $("#demo").html();
                var view = document.getElementById('view');
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
    <p>
        <br/>
        <br/>
    </p>
@endsection

