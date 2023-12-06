@extends(env('WapTemplate').'.wap')

@section("header")
    <header>
        <a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/back.png")}}" class="left backImg"></a>
        <span class="headerTitle">我要投资</span>
        <a href="{{route("user.index")}}" class="headerRight"><img src="{{asset("mobile/film/images/touxiang.png")}}" height="33" style="float:right;vertical-align: middle; margin-top:5px; padding-left:5px;"></a>
    </header>
@endsection

@section("js")
    @parent

@endsection

@section("css")

    @parent


@endsection

@section("onlinemsg")
    @parent
@endsection

@section('body')




    <div class="investTop">
  <div class="investImg">
            <img src="{{$productview->pic}}" style="width:100%">
        </div>
        
        
        
        
        
        <div class="investName">{{\App\Formatting::Format($productview->title)}}</div>
       <?php if ($productview->djs == 1) { ?>
        
         <div style="background:#FD4401; color:#FFF; text-align:center; font-size:16px; padding:10px 0px; width:96%; margin: 0 auto;">
        	 <p>距认购结束还剩</p>
        	 <div style="height:10px;"></div>
        	 <strong id="limit_discount_countdown" >00 天 00 小时 00 分 00 秒</strong>
        	 <div style="height:10px;"></div>
        	  <p style="font-size:15px;">认购期：{{$productview->created_at2}} ~ {{$productview->djs_at2}}</p>
        </div>
        <?php } ?>
        
          <div class="line">
            <ul class="detailBlock">
                <li class="detailList detailLeft">
                    <ul>
                        <li class="textlab">项目规模</li>
                        <li class="num"><em>¥</em>{{$productview->xmgm}}<em>万元</em></li>
                    </ul>
                </li>
                <li class="detailList detailLeft">
                    <ul>
                        <li class="textlab"><?php if ($productview->hkfs == 2) { ?> 时<?php } else { ?>日<?php } ?>化利率</li>
                        <li class="num">{{ $productview->jyrsy }}<em>%</em></li>
                    </ul>
                </li>
                <li class="detailList detailRight">
                    <ul>
                        <li class="textlab">项目期限 </li>
                        <li class="num"><?php echo $productview->shijian; ?><em><?php if ($productview->hkfs == 2) { ?> 小时<?php } else { ?>个自然日<?php } ?></em></li>
                    </ul>
                </li>
            </ul>
            
            <div class="cooperate">
                还款方式：<?php if ($productview->hkfs == 0) { ?>
                按天付收益，到期还本
                <?php } elseif ($productview->hkfs == 1) { ?>
                按周期付收益，到期还本
                <?php }elseif ($productview->hkfs == 2) { ?>
                按小时付收益，到期还本
                <?php }elseif ($productview->hkfs == 3) { ?>
                按日付收益，按日平均还本(等额本息)
                <?php } ?></div>
            <div class="cooperate">
                担保机构：<?php echo $productview->bljg; ?></div>
            <div class="clear"></div>投资进度:&nbsp;&nbsp;<span class="color"><?php 
            
            //总投资额
            $buyamounts=  \App\Productbuy::where("productid",$productview->id)->sum("amount");
            
            $jindu = $buyamounts / ($productview->xmgm *100) + $productview->xmjd;
            
            if ($jindu < 100) {
            	echo intval($jindu);
            }else{
            	echo 100;
            }
            
            ?>%</span><div class="progress"><span class="progressBar" style="width:<?php  echo intval($jindu); ?>%;"></span></div><div class="clear"></div>
        </div>
        
        
        
        
        <script>
        
        
        (function($) {
	// 倒计时
	$.fn.countdown = function(options) {

		var defaults = {
			// 间隔时间，单位：毫秒
			time: 0,
			// 更新时间，默认为1000毫秒
			updateTime: 1000,
			// 显示模板
			htmlTemplate: "%{d} 天 %{h} 小时 %{m} 分 %{s} 秒",
			minus: false,
			onChange: null,
			onComplete: null,
			leadingZero: false
		};
		var opts = {};
		var rDate = /(%\{d\}|%\{h\}|%\{m\}|%\{s\})/g;
		var rDays = /%\{d\}/;
		var rHours = /%\{h\}/;
		var rMins = /%\{m\}/;
		var rSecs = /%\{s\}/;
		var complete = false;
		var template;
		var floor = Math.floor;
		var onChange = null;
		var onComplete = null;

		var now = new Date();

		$.extend(opts, defaults, options);

		template = opts.htmlTemplate;
		return this.each(function() {

			var interval = opts.time - (new Date().getTime() - now.getTime());

			var $this = $(this);
			var timer;
			var msPerDay = 864E5; // 24 * 60 * 60 * 1000
			var timeLeft = interval;
			var e_daysLeft = timeLeft / msPerDay;
			var daysLeft = floor(e_daysLeft);
			var e_hrsLeft = (e_daysLeft - daysLeft) * 24; // Gets remainder
			// and * 24
			var hrsLeft = floor(e_hrsLeft);
			var minsLeft = floor((e_hrsLeft - hrsLeft) * 60);
			var e_minsleft = (e_hrsLeft - hrsLeft) * 60; // Gets remainder
			// and * 60
			var secLeft = floor((e_minsleft - minsLeft) * 60);
			var time = "";

			if (opts.onChange) {
				$this.bind("change", opts.onChange);
			}

			if (opts.onComplete) {
				$this.bind("complete", opts.onComplete);
			}

			if (opts.leadingZero) {

				if (daysLeft < 10) {
					daysLeft = "0" + daysLeft;
				}

				if (hrsLeft < 10) {
					hrsLeft = "0" + hrsLeft;
				}

				if (minsLeft < 10) {
					minsLeft = "0" + minsLeft;
				}

				if (secLeft < 10) {
					secLeft = "0" + secLeft;
				}
			}

			// Set initial time
			if (interval >= 0 || opts.minus) {
				time = template.replace(rDays, daysLeft).replace(rHours, hrsLeft).replace(rMins, minsLeft).replace(rSecs, secLeft);
			} else {
				time = template.replace(rDate, "00");
				complete = true;
			}

			timer = window.setInterval(function() {

				var interval = opts.time - (new Date().getTime() - now.getTime());

				var TodaysDate = new Date();
				var CountdownDate = new Date(opts.date);
				var msPerDay = 864E5; // 24 * 60 * 60 * 1000
				var timeLeft = interval;
				var e_daysLeft = timeLeft / msPerDay;
				var daysLeft = floor(e_daysLeft);
				var e_hrsLeft = (e_daysLeft - daysLeft) * 24; // Gets
				// remainder and
				// * 24
				var hrsLeft = floor(e_hrsLeft);
				var minsLeft = floor((e_hrsLeft - hrsLeft) * 60);
				var e_minsleft = (e_hrsLeft - hrsLeft) * 60; // Gets
				// remainder and
				// * 60
				var secLeft = floor((e_minsleft - minsLeft) * 60);
				var time = "";

				if (opts.leadingZero) {

					if (daysLeft < 10) {
						daysLeft = "0" + daysLeft;
					}

					if (hrsLeft < 10) {
						hrsLeft = "0" + hrsLeft;
					}

					if (minsLeft < 10) {
						minsLeft = "0" + minsLeft;
					}

					if (secLeft < 10) {
						secLeft = "0" + secLeft;
					}
				}

				if (interval >= 0 || opts.minus) {
					time = template.replace(rDays, daysLeft).replace(rHours, hrsLeft).replace(rMins, minsLeft).replace(rSecs, secLeft);
				} else {
					time = template.replace(rDate, "00");
					complete = true;
				}

				$this.html(time);

				$this.trigger('change', [timer]);

				if (complete) {

					$this.trigger('complete');
					clearInterval(timer);
				}

			}, opts.updateTime);

			$this.html(time);

			if (complete) {
				$this.trigger('complete');
				clearInterval(timer);
			}
		});
	};
})(jQuery);
	
        	 $().ready(function() {
		        // <font id="groupbuy_countdown">此商品正在参加团购活动 3天19时28秒后结束</font>
		         <?php if ($productview->djs == 1) { ?>
		        $("#limit_discount_countdown").countdown({
		            time: "{{$productview->djst}}",
		            leadingZero: true,
		            onComplete: function(event) {
		               $(this).parent().html("活动已结束！");
		            }
		        });
		         <?php } ?>
		    });
		  
        </script>
      
    </div>


    <div class="investTop">
        <div class="instru">项目详情</div>
        <div class="line">
            <div class="baseInfo">
                {!! \App\Formatting::Format($productview->content) !!}
            </div>
        </div>
    </div>


    <div class="investTop">
        <div class="instru">项目规则</div>
        <div class="line">
            <div class="baseInfo">
			<table border="1" >
                <tr>
				   <td>项目金额：</td>
				   <td><font size="3" color="red"><em>¥</em><?php echo $productview->xmgm; ?><em>万元</em>人民币</td>
				</tr>
                <tr>
				   <td>还款方式：</td>
				   <td><em> <?php if ($productview->hkfs == 0) { ?>
                        按天付收益，到期还本
                        <?php } elseif ($productview->hkfs == 1) { ?>
                        按周期付收益，到期还本
                        <?php }elseif ($productview->hkfs == 2) { ?>
                        按小时付收益，到期还本
                        <?php }elseif ($productview->hkfs == 3) { ?>
                        按日付收益，按日平均还本(等额本息)
                        <?php } ?></em>（节假日照常收益）
				   </td>
				</tr>
                <tr>
				    <td >起投金额：</td>
					<td><font size="3" color="red"><em><?php echo $productview->qtje; ?></em>元</td>
				</tr>
                <tr><td>每<?php if ($productview->hkfs == 2) { ?> 时<?php } else { ?>日<?php } ?>释放：</td>
				     <td><font size="3" color="red">{{ $productview->jyrsy }}%</td>
				</tr>
                <tr>
			    	<td>释放周期：</td>
					<td><em><?php echo $productview->shijian; ?>&nbsp;<?php if ($productview->hkfs == 2) { ?> 个小时<?php } else { ?>个自然日<?php } ?></em>，满<b class="blue"><?php if ($productview->hkfs == 2) { ?> <?php echo $productview->shijian; ?>个小时<?php } else { ?>24小时<?php } ?>  </b>自动结息</td>
				</tr>
                {{--<li>会员加息：<em>+0%</em></li>--}}
                <tr>
				    <td rowspan="2">预计收益：</td>
					<td><span class="cred"><?php echo $productview->qtje; ?></span>*<span class="cred"><?php echo $productview->jyrsy; ?></span>%*<span class="cred"><?php echo $productview->shijian; ?><?php if ($productview->hkfs == 2) { ?>小 时<?php } else { ?>天<?php } ?></span>
                    =总收益<span class="cred"><?php echo $productview->shijian*$productview->qtje*$productview->jyrsy/100; ?>元</span>
                    + 本金<span class="cred"><?php echo $productview->qtje; ?>元</span> </td>
                </tr>
				<tr><td>总计本息<span class="cred"><?php echo $productview->qtje*$productview->jyrsy/100*$productview->shijian+$productview->qtje; ?>元</span></td></tr>
             	<tr>
				    <td rowspan="2" >收益说明：</td>
					<td>当天投资，当天计息，到期返本</td>
					<tr>
					<td></td></tr>
			    
				</tr>
				
			
                <tr>
				    <td>能否复投：</td>
					<td><?php if ($productview->isft == 0) {
                        echo  '不能复投';
                    } elseif ($productview->isft == 1) {
                        echo  '可以复投';
                    } ?></td>
				</tr>

                <!--
                <div class="investTop">
                    <div class="instru">最近成交</div>
                    <div class="line">
                        <div class="baseInfo">

                            <div class="list_lh" style="clear:both">
                                <ul id="xpay_list"></ul>
                                <div id="xpages"></div>
              -->
			  </table>
            </div>

        </div>
    </div>





@endsection


@section('footcategory')

@endsection

@section("footer")
    @if(isset($Member))

        @if($productview->tzzt==1 || 100 <= $jindu )
            <div class="max detailBtn"><a href="javascript:void(0)" class="finishReg invBtn startTb">投资已满额</a></div>

        @else
            <div class="max detailBtn"><a href="{{route("product.buy",["id"=>$productview->id])}}" class="finishReg invBtn startTb">马上投资</a></div>
        @endif


    @else
        <div class="max detailBtn"><a href="{{route("wap.login")}}" class="finishReg invBtn startTb">请先登录</a></div>
    @endif
@endsection

