function yzm(){

	var yzm = $("#pyzm").val();
	var tel = $("#tel").val();
	if(yzm == ""){
		$("#terr").html("请输入验证码");
		return;
	}else{
		$.ajax({
			type : "POST",
			url : "/action/register.php?action=Code",
			dataType : "json",
			data : 'code=' + yzm + '&tel=' + tel,
			success : function (data) {
				if(data.status == "n"){
					$("#terr").html(data.info);
					retCode();
				}else{$("#d1").hide();$("#d2").show();$("#d2").html("60秒后可重新发送"); timer1 =window.setInterval("timeDesc();", 1000);$("#terr").html(data.info);}
			}
		});
	}
} 

function reg(){

	var pyzm = $("#pyzm").val();
	var mcode = $("#mcode").val();
	if(pyzm == ""){
		$("#terr").html("请输入验证码");
		return;
	}else if(mcode == ""){
		$("#terr2").html("请输入短信验证码");
	}else{
		$.ajax({
			type : "POST",
			url : "/action/register.php?action=mobileCode",
			dataType : "json",
			data : 'mcode=' + mcode,
			success : function (data) {
				if(data.status == "n"){
					$("#terr2").html("短信验证码输入有误，请重新输入");
				}else{
					submits();
				}
			}
		});
	}
} 

function submits()
{
	var yhm = $("#yhm").val();
	var tel = $("#tel").val(); 
	var youxiang = $("#youxiang").val();
	var mima  = $("#mima").val();
	var yaoqingren = $("#yaoqingren").val();
	var qq = $("#qq").val();

	$.ajax({
			type : "POST",
			url : "/action/register.php?action=reg",
			dataType : "json",
			data : 'yhm=' + yhm + '&tel=' + tel + '&youxiang=' + youxiang + '&mima=' + mima + '&yaoqingren=' + yaoqingren+' &qq=' + qq,
			success : function (data) {
				if(data.status == "y"){
					$("#step2").hide();
					$(".ok").html(data.info);
					$("#step3").show();
					 window.setTimeout("window.location='/mobile/login.php'",3000); 
				}else{
					$("#step2").hide();
					$(".ok").html(data.info);
					$("#step3").show();
				}
			}
		});
}


var all=0;
function timeDesc()
{
	var sum=60;
	all=all+1;
	$("#d2").html(sum-all+'秒后可重新发送');
	if(all==60){
		all=0;
		clearInterval(timer1);
		$("#d2").hide();
		$("#d1").show();
		$("#terr").html("");
		retCode();
	}
}

