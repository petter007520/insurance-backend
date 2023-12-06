<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title>{{Cache::get('CompanyShort')}}-在线客服</title>



</head>
<body>
<link rel="stylesheet" href="{{asset("layim/css/layui.mobile.css")}}">
<script src="{{asset("admin/js/jquery.min.js")}}?t=v1"></script>
<script src="{{asset("layim/layui.js")}}?t=v1"></script>

<script>

    var mobile
        ,layim
        ,layer ;



    layui.config({
        version: true
    }).use(['mobile'], function(){
        mobile = layui.mobile;
        layim = mobile.layim;
        layer = mobile.layer;


        //演示自动回复
        var autoReplay = [
            '您好，我现在有事不在，一会再和您联系。',
            '你没发错吧？face[微笑] ',
            '洗澡中，请勿打扰，偷窥请购票，个体四十，团体八折，订票电话：一般人我不告诉他！face[哈哈] ',
            '你好，我是主人的美女秘书，有什么事就跟我说吧，等他回来我会转告他的。face[心] face[心] face[心] ',
            'face[威武] face[威武] face[威武] face[威武] ',
            '<（@￣︶￣@）>',
            '你要和我说话？你真的要和我说话？你确定自己想说吗？你一定非说不可吗？那你说吧，这是自动回复。',
            'face[黑线]  你慢慢说，别急……',
            '(*^__^*) face[嘻嘻] ，是贤心吗？'
        ];

        layim.config({


            init: {
                //我的信息
                mine: {
                    "username": "{{$Member->username}}" //我的昵称
                    ,"id": "{{$Member->id}}" //我的ID
                    ,"avatar": "{{asset("layim/images/avatar/".($Member->id%10).".jpg")}}" //我的头像
                    ,"sign": ""
                }
                //我的好友列表
                ,friend: [{
                    "groupname": "在线客服"
                    ,"id": 100
                    ,"online": 2
                    ,"list": [{
                        "username": "{{Cache::get('CompanyShort')}}客服"
                        ,"id": "-1"
                        ,"avatar": "{{asset("layim/images/avatar/kf.png")}}"
                        ,"sign": "官方在线客户"
                    }]
                }]

            }

            //上传图片接口
            ,uploadImage: {
                url: '{{route("layim.uploadimgage")}}?_token={{ csrf_token() }}' //（返回的数据格式见下文）
                ,type: '' //默认post
            }


            ,moreList:false

            //扩展更多列表
            ,moreList: [{
                alias: 'usercent'
                ,title: '用户中心'
                ,iconUnicode: '&#xe628;' //图标字体的unicode，可不填
                ,iconClass: '' //图标字体的class类名

            }]

            //,tabIndex: 1 //用户设定初始打开的Tab项下标
            ,isNewFriend: false //是否开启“新的朋友”
            ,isgroup: false //是否开启“群聊”
            //,chatTitleColor: '#c00' //顶部Bar颜色
            ,copyright:false
            //,brief:true
            ,title: '{{Cache::get('CompanyShort')}}客服' //应用名，默认：我的IM
        });

        /*        //创建一个会话

                layim.chat({
                    "name": "客服小丽"
                    ,"id": "10000"
                    ,"type": "friend"
                    ,"avatar": "http://tp1.sinaimg.cn/1571889140/180/40030060651/1"
                    ,"sign": "官方在线客户"
                });*/


//监听点击更多列表
        layim.on('moreList', function(obj){
            switch(obj.alias){ //alias即为上述配置对应的alias
                case 'usercent': //发现
                    window.location.href='/user/index.html';
                    break;

            }
        });

        //监听返回
        layim.on('back', function(){
            //如果你只是弹出一个会话界面（不显示主面板），那么可通过监听返回，跳转到上一页面，如：history.back();
        });



        //监听发送消息
        layim.on('sendMessage', function(senddata){
            var To = senddata.to;
            var content = senddata.mine.content;

            console.log(senddata);

            var msgdata= {
                "username": To.name
                ,"avatar": To.avatar
                ,"fid": senddata.mine.id
                ,"fusername": senddata.mine.username
                ,"id": To.id
                ,"type": To.type
                ,"content": content,
                "_token":"{{ csrf_token() }}"
            };
            //console.log(msgdata);
            $.post("{{route('layim.send')}}",msgdata,function (datas) {

                if(datas){
                    layim.getMessage(datas);
                }

            });


            // layim.getMessage(obj);


            /*            //演示自动回复
                        setTimeout(function(){
                            var obj = {};
                            if(To.type === 'group'){
                                obj = {
                                    username: '模拟群员'+(Math.random()*100|0)
                                    ,avatar: layui.cache.dir + 'images/face/'+ (Math.random()*72|0) + '.gif'
                                    ,id: To.id
                                    ,type: 'group'
                                    ,content: autoReplay[Math.random()*9|0]
                                }
                            } else {
                                obj = {
                                    username: To.name
                                    ,avatar: To.avatar
                                    ,id: To.id
                                    ,type: To.type
                                    ,content: autoReplay[Math.random()*9|0]
                                }
                            }
                            layim.getMessage(obj);
                        }, 3000);*/
        });



        //模拟收到一条好友消息

        function Message(){
            //

            $.post("{{route('layim.getmsg')}}",{
                "_token":"{{ csrf_token() }}"
            },function (data) {

                if(data){
                    layim.getMessage(data);

                    //  layim.chat(data);

                }


            },'json');

            setTimeout(function () {
                Message();
            }, 2000);
        }

        Message();


        //模拟"更多"有新动态
        //layim.showNew('More', true);
        //layim.showNew('find', true);

        //console.log(layim.cache())
    });




</script>
</body>
</html>
