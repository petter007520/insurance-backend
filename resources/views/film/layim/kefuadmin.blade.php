<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>LayIM 3.x PC版本地演示</title>

    <link rel="stylesheet" href="{{asset("layim/css/layui.css")}}">
    <style>
        html{background-color: #333;}
    </style>
</head>
<body>


<script src="{{asset("layim/layui.js")}}"></script>

<script>

    if(!/^http(s*):\/\//.test(location.href)){
        alert('请部署到localhost上查看该演示');
    }

    layui.use('layim', function(layim){
        var $ =layui.$;

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

        //基础配置
        layim.config({



            init: {
                //我的信息
                mine: {
                    "username": "{{Cache::get('CompanyShort')}}客服" //我的昵称
                    ,"id": "-1" //我的ID
                    ,"avatar": "{{asset("layim/images/avatar/kf.png")}}" //我的头像
                    ,"sign": "{{Cache::get('CompanyShort')}}客服"
                }
                //我的好友列表
                ,friend: [{
                    "groupname": "前端码屌"
                    ,"id": 1
                    ,"online": 2
                    ,"list": [{
                        "username": "a1"
                        ,"id": "1"
                        ,"avatar": "http://tp1.sinaimg.cn/1571889140/180/40030060651/1"
                        ,"sign": "这些都是测试数据，实际使用请严格按照该格式返回"
                    },{
                        "username": "Z_子晴"
                        ,"id": "5"
                        ,"avatar": "http://tva3.sinaimg.cn/crop.0.0.512.512.180/8693225ajw8f2rt20ptykj20e80e8weu.jpg"
                        ,"sign": "微电商达人"
                    },{
                        "username": "Lemon_CC"
                        ,"id": "102101"
                        ,"avatar": "http://tp2.sinaimg.cn/1833062053/180/5643591594/0"
                        ,"sign": ""
                    },{
                        "username": "马小云"
                        ,"id": "168168"
                        ,"avatar": "http://tp4.sinaimg.cn/2145291155/180/5601307179/1"
                        ,"sign": "让天下没有难写的代码"
                        ,"status": "offline"
                    },{
                        "username": "徐小峥"
                        ,"id": "666666"
                        ,"avatar": "http://tp2.sinaimg.cn/1783286485/180/5677568891/1"
                        ,"sign": "代码在囧途，也要写到底"
                    }]
                },{
                    "groupname": "网红"
                    ,"id": 2
                    ,"online": 3
                    ,"list": [{
                        "username": "罗玉凤"
                        ,"id": "121286"
                        ,"avatar": "http://tp1.sinaimg.cn/1241679004/180/5743814375/0"
                        ,"sign": "在自己实力不济的时候，不要去相信什么媒体和记者。他们不是善良的人，有时候候他们的采访对当事人而言就是陷阱"
                    },{
                        "username": "长泽梓Azusa"
                        ,"id": "100001222"
                        ,"sign": "我是日本女艺人长泽あずさ"
                        ,"avatar": "http://tva1.sinaimg.cn/crop.0.0.180.180.180/86b15b6cjw1e8qgp5bmzyj2050050aa8.jpg"
                    },{
                        "username": "大鱼_MsYuyu"
                        ,"id": "12123454"
                        ,"avatar": "http://tp1.sinaimg.cn/5286730964/50/5745125631/0"
                        ,"sign": "我瘋了！這也太準了吧  超級笑點低"
                    },{
                        "username": "谢楠"
                        ,"id": "10034001"
                        ,"avatar": "http://tp4.sinaimg.cn/1665074831/180/5617130952/0"
                        ,"sign": ""
                    },{
                        "username": "柏雪近在它香"
                        ,"id": "3435343"
                        ,"avatar": "http://tp2.sinaimg.cn/2518326245/180/5636099025/0"
                        ,"sign": ""
                    }]
                },{
                    "groupname": "我心中的女神"
                    ,"id": 3
                    ,"online": 1
                    ,"list": [{
                        "username": "林心如"
                        ,"id": "76543"
                        ,"avatar": "http://tp3.sinaimg.cn/1223762662/180/5741707953/0"
                        ,"sign": "我爱贤心"
                    },{
                        "username": "佟丽娅"
                        ,"id": "4803920"
                        ,"avatar": "http://tp4.sinaimg.cn/1345566427/180/5730976522/0"
                        ,"sign": "我也爱贤心吖吖啊"
                    }]
                }]

            }



            //上传图片接口
            ,uploadImage: {
                url: '{{route("layim.uploadimgage")}}?_token={{ csrf_token() }}' //（返回的数据格式见下文）
                ,type: '' //默认post
            }

            ,isAudio: true //开启聊天工具栏音频
            ,isVideo: true //开启聊天工具栏视频

            //扩展工具栏
            ,tool: [{
                alias: 'code'
                ,title: '代码'
                ,icon: '&#xe64e;'
            }]

            //,brief: true //是否简约模式（若开启则不显示主面板）

            //,title: 'WebIM' //自定义主面板最小化时的标题
            //,right: '100px' //主面板相对浏览器右侧距离
            //,minRight: '90px' //聊天面板最小化时相对浏览器右侧距离
            ,initSkin: '5.jpg' //1-5 设置初始背景
            //,skin: ['aaa.jpg'] //新增皮肤
            //,isfriend: false //是否开启好友
            ,isgroup: false //是否开启群组
            //,min: true //是否始终最小化主面板，默认false
            ,notice: true //是否开启桌面消息提醒，默认false
            //,voice: false //声音提醒，默认开启，声音文件为：default.mp3

           // ,msgbox: layui.cache.dir + 'css/modules/layim/html/msgbox.html' //消息盒子页面地址，若不开启，剔除该项即可
           // ,find: layui.cache.dir + 'css/modules/layim/html/find.html' //发现页面地址，若不开启，剔除该项即可
            ,chatLog: layui.cache.dir + 'css/modules/layim/html/chatlog.html' //聊天记录页面地址，若不开启，剔除该项即可

        });



        //监听在线状态的切换事件
        layim.on('online', function(data){
            //console.log(data);
        });

        //监听签名修改
        layim.on('sign', function(value){
            //console.log(value);
        });

        //监听自定义工具栏点击，以添加代码为例
        layim.on('tool(code)', function(insert){
            layer.prompt({
                title: '插入代码'
                ,formType: 2
                ,shade: 0
            }, function(text, index){
                layer.close(index);
                insert('[pre class=layui-code]' + text + '[/pre]'); //将内容插入到编辑器
            });
        });

        //监听layim建立就绪
        layim.on('ready', function(res){

            //console.log(res.mine);

            layim.msgbox(5); //模拟消息盒子有新消息，实际使用时，一般是动态获得

            //添加好友（如果检测到该socket）
            layim.addList({
                type: 'group'
                ,avatar: "http://tva3.sinaimg.cn/crop.64.106.361.361.50/7181dbb3jw8evfbtem8edj20ci0dpq3a.jpg"
                ,groupname: 'Angular开发'
                ,id: "12333333"
                ,members: 0
            });
            layim.addList({
                type: 'friend'
                ,avatar: "http://tp2.sinaimg.cn/2386568184/180/40050524279/0"
                ,username: '冲田杏梨'
                ,groupid: 2
                ,id: "1233333312121212"
                ,remark: "本人冲田杏梨将结束AV女优的工作"
            });

            setTimeout(function(){
                //接受消息（如果检测到该socket）
                layim.getMessage({
                    username: "Hi"
                    ,avatar: "http://qzapp.qlogo.cn/qzapp/100280987/56ADC83E78CEC046F8DF2C5D0DD63CDE/100"
                    ,id: "10000111"
                    ,type: "friend"
                    ,content: "临时："+ new Date().getTime()
                });

                /*layim.getMessage({
                  username: "贤心"
                  ,avatar: "http://tp1.sinaimg.cn/1571889140/180/40030060651/1"
                  ,id: "100001"
                  ,type: "friend"
                  ,content: "嗨，你好！欢迎体验LayIM。演示标记："+ new Date().getTime()
                });*/

            }, 3000);
        });

        //监听发送消息
        layim.on('sendMessage', function(data){
            var To = data.to;
            //console.log(data);

            /*if(To.type === 'friend'){
                layim.setChatStatus('<span style="color:#FF5722;">对方正在输入。。。</span>');
            }*/


            var content = data.mine.content;

            console.log(data);

            var msgdata= {
                "username": To.name
                ,"avatar": To.avatar
                ,"fid": data.mine.id
                ,"fusername": data.mine.username
                ,"id": To.id
                ,"type": To.type
                ,"content": content,
                "_token":"{{ csrf_token() }}"
            };
            //console.log(msgdata);
            $.post("{{route('layim.send')}}",msgdata,function (datas) {

                if(datas.username){
                    layim.getMessage(datas);
                }

            });

        });

        //监听查看群员
        layim.on('members', function(data){
            //console.log(data);
        });

        //监听聊天窗口的切换
        layim.on('chatChange', function(res){
            var type = res.data.type;
            console.log(res.data.id)
            if(type === 'friend'){
                //模拟标注好友状态
                //layim.setChatStatus('<span style="color:#FF5722;">在线</span>');
            } else if(type === 'group'){
                //模拟系统消息
                layim.getMessage({
                    system: true
                    ,id: res.data.id
                    ,type: "group"
                    ,content: '模拟群员'+(Math.random()*100|0) + '加入群聊'
                });
            }
        });

        //console.log(layim.cache())

        //收到一条好友消息

        function Message(){
            //

            $.post("{{route('layim.getmsg')}}",{
                "_token":"{{ csrf_token() }}"
            },function (data) {

                if(data.username){
                    layim.getMessage(data);
                }


            },'json');

            setTimeout(function () {
                Message();
            }, 2000);
        }

        Message();

    });
</script>
</body>
</html>