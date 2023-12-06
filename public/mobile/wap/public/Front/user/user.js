


function nowToMoney(){

    var _token = $("[name='_token']").val();
    var idPay = $("#idPay").val();
    var amountPay = $("#amountPay").val();
    var pwdPay    = $("#passwordPay").val();
    var touzM     = $("#tzje").val();
    if(amountPay == ""){
        $("#terr").html("请填写投资金额");
        return;
    }else if(pwdPay == ""){
        $("#terr").html("请填写交易密码");
        return;
    }else if(idPay == ""){
        $("#terr").html("提交错误请刷新重试");
        return;
    }else if(amountPay < touzM){
        $("#terr").html("你输入的小于起投金额");
        return;
    }else{
        $.ajax({
            type : "POST",
            url : "/user/nowToMoney",
            dataType : "json",
            data:{
                amountPay:amountPay,
                idPay:idPay,
                pwdPay:pwdPay,
                _token:_token,
            },
            //data : 'amountPay=' + amountPay + '&idPay=' + idPay+ '&pwdPay=' + pwdPay+'&_token='+_token,
            success : function (data) {
                if(data.status == 1){
                   // $("#terr").html("投资成功，请进入会员中心进行管理");
                  //  alert("投资成功!请进入会员中心进行管理。");

                    layer.open({
                        content: data.msg,
                        btn: '确定',
                        shadeClose: false,
                        yes: function(index){
                            if(data.status){
                                window.location.reload();
                            }

                            layer.close(index);
                        }
                    });


                }else{
                   // $("#terr").html(data.msg);
                   // alert(data.msg);

                    layer.open({
                        content: data.msg,
                        btn: '确定',
                        shadeClose: false,
                        yes: function(index){
                            if(data.status){
                               // window.location.reload();
                            }

                            layer.close(index);
                        }
                    });
                }
            }
        });
    }
} 


function bank_hqcode() {
    var dianhua = $("#signtel").val();
    var sessionAction = $("#sessionAction").val();
    $("#signtelCode").html("");
    if (!dianhua.match(/^((13|14|15|17|18)+\d{9})$/)) {
       // alert("对不起您还未绑定手机");
       // window.location = '/mobile/user/certification_tel.php';

        layer.open({
            content: "对不起您还未绑定手机",
            btn: '确定',
            shadeClose: false,
            yes: function(index){
                window.location = '/mobile/user/certification_tel.php';

                layer.close(index);
            }
        });

        return false;
    } else {
        var url = "/mobile/action/user.php?action=smscode";
        $.post(url, {
            "dianhua": dianhua,
            "seesionType": sessionAction
        },
        function(data) {
            if (data == 0) {
                $("#hqcode").attr("background", "cccccc");
                $("#signtel").attr("readonly", "readonly");
                $("#hqcode").html("60秒后重新获取");
                $('#hqcode').attr('href', 'javascript:vide(0);');
                setTimeout('bank_miaocode(60)', 1000);
            } else if (data == 2) {
                $("#signtelCode").html("对不起！手机验证系统维护中，暂时不可用");
                return false;
            } else {
                $("#signtelCode").html("请正确填写手机号码");
                return false;
            }
        });
    }
}
function bank_miaocode(str) {
    $("#hqcode").html((str - 1) + "秒后重新获取");
    if (str > 0) {
        setTimeout('bank_miaocode(' + (str - 1) + ')', 1000);
    } else {
        $("#hqcode").attr("background", "FF9900");
        $('#hqcode').attr('href', 'javascript:bank_hqcode();');
        $("#signtelCode").html("");
        $("#hqcode").html("重新获取手机验证码");
    }
}
function hqcode() {

        var _token = $("[name='_token']").val();
        var action = $("[name='action']").val();
        var dianhua = $("[name='dianhua']").val();
    	var url = "/user/SendCode";
        $.post(url, {
            "action": 'regcode',
            "_token": _token
        },
        function(data) {
            if (data.status == 0) {
                $("#hqcode").attr("background", "cccccc");
                $("#signtel").attr("readonly", "readonly");
                $("#hqcode").html("60秒后重新获取");
                $('#hqcode').attr('href', 'javascript:vide(0);');
                setTimeout('miaocode(60)', 1000);
            } else if (data.status == 1) {
                $("#signtelCode").html("对不起！手机验证系统维护中，暂时不可用");
                return false;
            } else {
                $("#signtelCode").html(data.msg);
                return false;
            }
        });

}

//找回密码
function fdhqcode() {
    var dianhua = $("#signtel").val();
    var mobile = $("#mobile").val();
    var _token = $("[name='_token']").val();
    var action = $("[name='action']").val();
    $("#signtelCode").html("");
    if (!mobile.match(/^((13|14|15|17|18|16|19)+\d{9})$/)) {
        $("#signtelCode").html("请正确填写手机号码");
        return false;
    } else {
        var url = "/user/SendRZCode";
        $.post(url, {
            "dianhua": dianhua,
            "mobile": mobile,
            "action": 'regcode',
            "_token": _token
        },
        function(data) {
            if (data.status == 0) {
                $("#hqcode").attr("background", "cccccc");
                $("#signtel").attr("readonly", "readonly");
                $("#hqcode").html("60秒后重新获取");
                $('#hqcode').attr('href', 'javascript:vide(0);');
                setTimeout('miaocode(60)', 1000);
            } else if (data.status == 1) {
                $("#signtelCode").html("对不起！手机验证系统维护中，暂时不可用");
                return false;
            } else {
                $("#signtelCode").html(data.msg);
                return false;
            }
        });
    }
}


function miaocode(str) {
    $("#hqcode").html((str - 1) + "秒后重新获取");
    if (str > 0) {
        setTimeout('miaocode(' + (str - 1) + ')', 1000);
    } else {
        $("#hqcode").attr("background", "FF9900");
        $('#hqcode').attr('href', 'javascript:hqcode();');
        $("#signtelCode").html("");
        $("#hqcode").html("重新获取手机验证码");
    }
}
function hqemailcode() {
    var sinemail = $("#sinemail").val();
    $("#signemailCode").html("");
    if (!sinemail.match(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/)) {
        $("#signemailCode").html("请正确填写邮箱地址");
        return false;
    } else {
        $("#hqcode").html("正在发送验证码");
        var url = "/mail/mail.php?action=email";
        $.post(url, {
            "sinemail": sinemail
        },
        function(data) {
            if (data == 0) {
                $("#hqcode").attr("background", "cccccc");
                $("#sinemail").attr("readonly", "readonly");
                $('#hqcode').attr('href', 'javascript:vide(0);');
                setTimeout('emailmiaocode(300)', 1000);
            } else if (data == 2) {
                $("#hqcode").html("获取邮箱验证码");
                $("#signemailCode").html("对不起！邮箱验证系统维护中，暂时不可用");
                return false;
            } else {
                $("#hqcode").html("获取邮箱验证码");
                $("#signemailCode").html("对不起！邮箱验证系统维护中，暂时不可用");
                return false;
            }
        });
    }
}
function emailmiaocode(str) {
    $("#hqcode").html((str - 1) + "秒后重新获取");
    if (str > 0) {
        setTimeout('emailmiaocode(' + (str - 1) + ')', 1000);
    } else {
        $("#hqcode").html("没有收到，重新获取邮箱验证码");
        $("#hqcode").attr("background", "FF9900");
        $('#hqcode').attr('href', 'javascript:hqemailcode();');
        $("#signemailCode").html("");
        $("#hqcode").html("重新获取邮箱验证码");
    }
}
function user_msg(str) {
    var _token = $("[name='_token']").val();
    var url = "/user/MsgRead";
    $.post(url, {
        "id": str,
        _token:_token
    },
    function(data) {
        if (data.status == 0) {
            $("#zt" + str + "").html("<font color='#00A11D'>已读</font>");
        }else{
            $("#zt" + str + "").html("<font color='#00A11D'>网络异常</font>");
        }
    });
}

function user_msg_del(str) {
    var _token = $("[name='_token']").val();
    var url = "/user/MsgDel";
    $.post(url, {
        "id": str,
        _token:_token
    },
    function(data) {
        if (data.status == 0) {
            $("#del" + str + "").html("<font color='#00A11D'>删除成功</font>");
        }else{
            $("#del" + str + "").html("<font color='#00A11D'>网络异常</font>");
        }
        $("#sms" + str + "").hide();
    });
}