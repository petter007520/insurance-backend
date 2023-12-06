    <!DOCTYPE html>
    <html class="v_scrollbar"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no">
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <title>项目投资协议</title>
        <style type="text/css">
            .seal{ background: url("/uploads/"+"{{Cache::get('offiseal')}}") no-repeat; width: 200px; height: 200px;position: absolute;top:0px;left:110px;}
            body{font:normal 16px/1.6em Microsoft YaHei,arial,verdana;padding:0;margin:0}.wrap{width:1170px;height:auto;margin:0 auto}.ylb-text{padding:20px 30px}h1{font-size:24px;line-height:2}h2{font-size:16px}em{font-style:normal}.col-w-12{width:100%}.col-w-11{width:91.6666%}.col-w-10{width:83.3333%}.col-w-9{width:75%}.col-w-8{width:66.6666%}.col-w-7{width:58.3333%}.col-w-6{width:50%}.col-w-5{width:41.6666%}.col-w-4{width:33.3333%}.col-w-3{width:25%}.col-w-2{width:16.6666%}.col-w-1{width:8.3333%}.font-sm{font-size:14px}.bg-blue{background:#287ebb}.border{border:#eee 1px solid}.btn{padding:8px 20px;border-radius:2px;text-decoration:none;background:#fff}.btn-primary{color:#fff;background-color:#108bdd;border-color:#0f86d4}.btn-primary:hover,.btn-primary:focus,.btn-primary:active{color:#fff;background-color:#108bdd;border-color:#0f86d4}.btn-default{color:#fff;background-color:#ff9c52;border-color:#ff9c52}.btn-default:hover,.btn-default:focus,.btn-default:active{color:#fff;background-color:#fe903e;border-color:#fe903e;box-shadow:0 1px 2px rgba(0,0,0,0.2)}.center{text-align:center}hr{border:0;border-top:1px solid #ddd;height:0;margin:15px 0}.ylb-text u{padding:0 5px}.table{margin:10px auto;width:88%;text-align:center;border-left:1px solid #ddd;border-top:1px solid #ddd}.textleft{text-align:left;padding-left:8px}.table>thead>tr>td,.table>tbody>tr>td{border-right:1px solid #ddd;border-bottom:1px solid #ddd}@media (max-width:1200px){.wrap{width:960px}}@media (max-width:992px){.wrap{width:750px}}@media (max-width:767px){.wrap{width:100%;border:none;margin:0}}@media (max-width:480px){.ylb-text{padding:15px}}.ylb-app{background:#287ebb;color:#fff;overflow:hidden;padding:60px 0}.ylb-app p{margin-bottom:30px}.ylb-app .logo{width:140px;height:140px;background:url('{{asset('mobile/public/style/pd/images/logo.png')}}') no-repeat;margin:0 auto 30px}.ylb-app .logo-text{width:160px;height:50px;background:url('{{asset('mobile/wap/public/style/pd/images/logo-text.png')}}') no-repeat;margin:0 auto 30px}.ylb-app .btn-default{padding:10px 50px;font-size:20px}.ylb-app h3{font-size:32px;font-weight:normal}
        </style>


    </head>
    <body style="padding:0px;">
    <div class="wrap">
        <div class="ylb-text">

            <h1 class="center">项目投资协议

            </h1>
            <p>项目编号：<u><?php echo $Pro->title; ?></u></p>
            <p>本投资协议（“本协议”）由以下双方于 <strong><u><?php echo date('Y年m月d日',strtotime($ProBuy->useritem_time)); ?></u></strong></p>
            <p>甲方：<strong><u><?php echo \Cache::get('CompanyLong'); ?></u></strong></p>
            <p>乙方（投资人）：<strong> <u><?php echo $Mb->bankrealname!=''?$Mb->bankrealname:'*****'; ?></u></strong></p>
            <p>银行账号：<strong>
                    <u>
                        <?php echo $Mb->bankcode!=''?$Mb->bankcode:'*****'; ?>
                        </u>
                </strong>
            </p>
            <p>用户名 ：<strong> <u><?php echo $Mb->username; ?></u></strong></p>
            <table cellpadding="0" cellspacing="0" class="table">
                <thead>
                <tr>
                    <td>平台用户名</td>
                    <td>投资金额（元）</td>
                    <td>投资期限（<?php echo $Pro->qxdw=='个小时'?'时':'天';?>）</td>
                    <td>到期应收利息（元）</td>
                    <td>到期应收本金（元）</td>
                </tr>
                </thead>
                <tbody>
                <!-- 获取数据 -->
                <tr>
                    <td><?php  echo $Mb->username;?></td>
                    <td><?php  echo $ProBuy->amount;?></td>
                    <td><?php  echo $Pro->shijian;?></td>
                    <td><?php  echo round($Pro->shijian*$ProBuy->amount*$Pro->jyrsy*0.01,2);?></td>
                    <td><?php  echo $ProBuy->amount;?></td>
                </tr>
                </tbody>
            </table>
            <p>甲方（<?php echo \Cache::get('CompanyLong'); ?>平台）：<?php echo \Cache::get('CompanyLong'); ?>.</p>
            <p>既乙方通过由甲方运营管理的<?php echo \Cache::get('CompanyLong'); ?>平台（域名:，“<?php echo \Cache::get('CompanyLong'); ?>”）进行其公司运营操作的产品项目投资意愿、双方根据平等、自愿的原则，达成本协议如下：</p>
            <h4>一、 收益金额、期限 、返款方式</h4>
            <p>1. 甲方同意通过<?php echo \Cache::get('CompanyLong'); ?>平台接收乙方投资金额如下，乙方同意通过<?php echo \Cache::get('CompanyLong'); ?>平台向甲方投资金额、收益、返款方式。</p>
            <p>　</p>
            <table cellpadding="0" cellspacing="0"  class="table" width="1099" height="130">
                <thead>
                <tr>
                    <td width="130" height="60">&nbsp;&nbsp;平台用户名</td>
                    <td width="163" height="60">&nbsp;&nbsp; 投资金额（元）</td>
                    <td width="150" height="60">&nbsp;&nbsp; 投资期限（<?php echo $Pro->qxdw=='个小时'?'时':'天';?>）</td>
                    <td width="187" height="60">&nbsp;&nbsp; 到期应收利息（元）</td>
                    <td width="203" height="60">&nbsp;&nbsp; 到期应收本金（元）</td>
                    <td width="266" height="60">&nbsp;&nbsp;&nbsp; 返款时间</td>
                </tr>
                </thead>
                <tbody>
                <!-- 获取数据 -->
                <tr>
                    <td width="130">&nbsp;&nbsp;&nbsp;&nbsp; <?php  echo $Mb->username;?></td>
                    <td width="163">&nbsp;&nbsp;&nbsp;&nbsp; <?php  echo $ProBuy->amount;?></td>
                    <td width="150">&nbsp;&nbsp;&nbsp;&nbsp; <?php  echo $Pro->shijian;?></td>
                    <td width="187">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php  echo round($Pro->shijian*$ProBuy->amount*$Pro->jyrsy*0.01,2);?></td>
                    <td width="203">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php  echo $ProBuy->amount;?></td>
                    <td width="266"><u>&nbsp; <?php
                            $d = $ProBuy->useritem_time;
                            echo date("Y-m-d",strtotime("$d+1day"));
                            ?></u>  - <u><?php echo date("Y-m-d",strtotime("$d+ {$Pro->shijian} day"));  ?></u></td>
                </tr>
                </tbody>
            </table>
            <p>　</p>
            <h2>二、 投资流程</h2>
            <p>2.1 本协议成立：甲方首先在<?php echo \Cache::get('CompanyLong'); ?>网站上发布相关的投资产品项目收益，乙方按照<?php echo \Cache::get('CompanyLong'); ?>的规则，通过在<?php echo \Cache::get('CompanyLong'); ?>网站上相关的投资产品项目收益，按钮确认
                投资时，本协议下所约定的关系即成立，本协议的签署关系
                同时成立。</p>
            <P>2.2 投资资金冻结：乙方点击“立即投资”按钮即视为其已经向甲方发出不可撤销的授权指令，授权甲方全权处理和操作本次的投资金额（“乙方<?php echo \Cache::get('CompanyLong'); ?>账户”）中的冻结金额等同于本协议
                投资的本金数额”的资金。上述冻结在本协议生效时或本协议确定失效时（项目周期结束）解除。</P>
            <p>2.3 投资资金变更：本协议生效的同时，甲方即不可撤销乙方所投资的金额，本金额等同于本协议第一条所列的“收益金额、期限 、返款方式”</p>
            <h2>三、 投资资金来源保证</h2>
            <p>3.1 乙方保证其所用于投资资金来源合法，乙方是该资金的合法所有人，如果合法性问题(如盗用他人的银行卡，信用卡等)发生争议，由乙方自行负责解决。如乙方未能解决，因乙方资金安全问题导致账户被相关司法机关冻结或者执行，
                由乙方自行负责解决相关司法问题。</p>
            <h2>四、 本息返还方式</h2>
            <p>4.1 甲方同意并承诺，乙方所投资金的收益金额、期限 、返款方式的执行，甲方负责对乙方所投资金额的收益金额和返款时间的保证，甲方必须按照本协议的约定
                按时间全额的将其返还到乙方的帐户中。</p>
            <p>4.2 甲方应在每周期或每日规定的返款时间（每日分红和到期返本金）（不得迟于24:00）将其按照本协议第一条所述的本息，转入乙方的账户。</p>
            <p>4.3 如果返款日遇到法定假日或公休日，返款日期时间不受影响。</p>
            <p>　</p>
            <h2>五、 违约</h2>
            <p>5.1 如果甲方严重违反本协议的协定，如：没能按时的返还乙方的红利及本金，甲方应向乙方支付投资总金额和投资总金额10%作为违约金。乙方须
                保证其资金来源的合法性不得隐瞒，否则甲方有权冻结其资金移送法办。</p>
            <p>5.2 乙方保证其提供的信息和资料的真实性，不得提供虚假资料或隐瞒。乙方提供虚假资料或者故意隐瞒，构成违约，应承担违约责任，同时本协议视为提前终止
                。</p>
            <p>5.3 发生下列任何一项或几项情形的， (1) 甲方、乙方
                任何财产遭受没收、征用、查封、扣押、冻结等可能影响其履约能力的不利事件，且不能及时提供有效补救措施的；(2) 提前终止本协议；(3) 采取法律、法规以及本协议约定的其他救济措施。</p>
            <p>　</p>
            <h2>六、 本协议未经乙方事先书面（包括但不限于电子邮件等方式）同意，甲方不得将本协议项下的任何权利义务转让给任何第三方。</h2>
            <h2>七、 其他</h2>
            <p>7.1 本协议在下述条件全部满足时生效：</p>
            <p>（1）甲方和乙方在本协议（含传真件）上签字盖章、且乙方通过<?php echo \Cache::get('CompanyLong'); ?>以网络在线点击：立即投资的方式签订；<br/>
                （2）自甲方发布的投资项目起，乙方所投项目资金全部在<?php echo \Cache::get('CompanyLong'); ?>账户中本金将被冻结直至项目周末结束。</p>
            <p>　</p>
            <p>　</p>
            <p>7.2 本协议的任何修改、补充均须以<?php echo \Cache::get('CompanyLong'); ?>平台电子文本形式作出。</p>
            <p>7.3 双方均确认，本协议的签订、生效和履行以不违反法律为前提。如果本协议中的任何一条或多条违反适用的法律，则该条将被视为无效，但该无效条款并不影响本协议其他条款的效力。</p>
            <p>7.4 如果双方在本协议履行过程中发生任何争议，应友好协商解决；如协商不成，则提交所在地的人民法院进行诉讼。</p>
            <p>7.5 甲方保留与本协议有关的书面文件或电子信息。</p>
            <div oncontextmenu="window.event.returnValue=false" style="height: 150px;width:300px;line-height: 150px;position: relative;">
                <span>（签字或盖章）</span>
                <div class="seal" oncontextmenu="window.event.returnValue=false"></div>
            </div>
        </div>
        <script type="text/javascript" src="{{asset("js/jquery.js")}}"></script>
        <script type="text/javascript">
            var srl ="/uploads/"+"{{Cache::get('offiseal')}}";
            var url = 'url("'+srl+'")';
            $(".seal").css({"background-image":url,"background-size":"200px 200px"});

        </script>
    </body>
    </html>


