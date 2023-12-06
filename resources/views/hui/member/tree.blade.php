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



    <link rel="stylesheet" href="/admin/tree/css/zTreeStyle.css" type="text/css">
    <link rel="stylesheet" href="/admin/tree/css/animate.css" type="text/css">
    <script type="text/javascript" src="/admin/tree/js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="/admin/tree/js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="/admin/tree/js/jquery.ztree.excheck.js"></script>

    <SCRIPT type="text/javascript">
        var setting = {
            treeObj: null,
            check: {
                enable: true
            },
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "pId",
                    left_amount: "left_amount",
                    right_amount: "right_amount",
                    left_blance: "left_blance",
                    right_blance: "right_blance",
                    rootPId: 0
                }
            },
            callback: {
                onCheck: zTreeOnCheck
            },
            view: {
                showLine: false,
                showIcon: false,
                showTitle: true,
                fontCss : {color:"red"},
                addDiyDom: addDiyDom
            }

        };

        var zNodes =[

        ];



        @if($members)
            @foreach($members as $member)

            zNodes.push({

                "id": "{{$member->id}}",
                "pId": "{{$member->top_uid}}",
                "name": "{{$member->username}}({{$member->region ==1 ?'左区':'右区'}})",
                "left_amount": "左区业绩:{{$member->left_amount}}",
                "right_amount": "右区业绩:{{$member->right_amount}}",
                "left_blance": "左区余额:{{$member->left_blance}}",
                "right_blance": "左区余额:{{$member->right_blance}}",
                });
            @endforeach

        @endif

        var code;


        function addDiyDom(treeId, treeNode) {
            var aObj = $("#" + treeNode.tId + "_a");
            if ($("#diyBtn_"+treeNode.id).length>0) return;
            var editStr = "<br/><span id='diyBtn_space_" +treeNode.id+ "' > "+treeNode.left_amount+"</span><br/>" +
                "<span id='diyBtn_space_" +treeNode.id+ "' > "+treeNode.right_amount+"</span><br/>" +
                "<span id='diyBtn_space_" +treeNode.id+ "' > "+treeNode.left_blance+"</span><br/>" +
                "<span id='diyBtn_space_" +treeNode.id+ "' > "+treeNode.right_blance+"</span><br/>";
            aObj.append(editStr);
        };

        function setCheck() {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
                type = {Y: "ps", N: "ps"}
            zTree.setting.check.chkboxType = type;
            zTree.expandAll(true); //全部展开
            showCode('setting.check.chkboxType = { "Y" : "' + type.Y + '", "N" : "' + type.N + '" };');
            minejs();
        }

        function showCode(str) {
            if (!code) code = $("#code");
            code.empty();
            code.append("<li>" + str + "</li>");

        }

        function zTreeOnCheck(event, treeId, treeNode) {
            getSelectedNodes();
            //当前被选中对象携带参数
            // console.log(treeNode.tId + ", " + treeNode.name + "," + treeNode.checked);
        };


        function getSelectedNodes() {
            // var zTree = $.fn.zTree.getZTreeObj("treeDemo");
            // var selectedNode = zTree.getCheckedNodes();

            // 获取当前被勾选的节点集合
            var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
            var nodes = treeObj.getCheckedNodes(true);
        }

        $(document).ready(function () {
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            setCheck();
            $("#py").bind("change", setCheck);
            $("#sy").bind("change", setCheck);
            $("#pn").bind("change", setCheck);
            $("#sn").bind("change", setCheck);
            $('.ztree li span.button.switch').click(function () {
                minejs();
            })
        });

        function minejs() {

        }
    </SCRIPT>

    <style>

        .addbor{
            background-color: green;
        }
        .tabbox {
            width: 100%;
            margin: 10% auto;
            padding: 15px;
            height: 600px;
            background: white;
            display: none;

        }

        .tabbox li, .tabbox ul {
            list-style-type: none
        }

        .tabbox ul {
            margin: 0;
            padding: 0
        }

        .tabbox .tabnav, .tabbox .tabcon {
            width: 98%;
            display: block;
            float: left;
        }

        .tabbox .tabnav {
            border-bottom: 1px solid #ddd
        }

        .tabbox .tabnav li {
            position: relative;
            cursor: pointer;
            float: left;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            margin: 0px;
            padding: 8px 26px;
            color: #337ab7;
            border: 1px solid #fff;
        }

        .tabnav li.active {
            border: 1px solid #ddd;
            border-bottom: none;
            color: #555;
        }

        .tabnav li.active:before {
            content: '';
            width: 100%;
            height: 1px;
            bottom: -2px;
            left: 0;
            position: absolute;
            background: white;
        }

        .tabbox .tabcon li {
            display: none;
            padding: 10px;
            width: 100%;
            height: 540px;
            overflow: auto;
        }

        .tabbox .tabcon li:first-child {
            display: block
        }

        .mask {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgb(212, 212, 212);
            display: none
        }

        body
        {

            overflow: scroll;
        }


        body::-webkit-scrollbar {/*滚动条整体样式*/
            width: 10px;     /*高宽分别对应横竖滚动条的尺寸*/
            height: 10px;
        }
        body::-webkit-scrollbar-thumb {/*滚动条里面小方块*/
            border-radius: 10px;
            background-color: #F90;
            background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent);
        }
        body::-webkit-scrollbar-track {/*滚动条里面轨道*/
            -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
            /*border-radius: 10px;*/
            background: #EDEDED;
        }

        .content_wrap
        {


        }
    </style>

    <div class="content_wrap" >
        <div class="zTreeDemoBackground left">
            <ul id="treeDemo" class="ztree" ></ul>

        </div>
        <div class="mask">
            <div class="tabbox">
                <ul class="tabnav">

                </ul>
                <ul class="tabcon">

                </ul>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('.tabnav li').click(function () {
                $('.tabnav li').removeClass('active')
                $(this).addClass('active');
                var pd = $(this).index();
                $('.tabcon li').hide()
                $('.tabcon li').eq(pd).show();
            })
        })
    </script>


    @endsection



    @section('formbody')



    @endsection

    @section("layermsg")

        <script>


        </script>
@endsection





@section('form')



@endsection





