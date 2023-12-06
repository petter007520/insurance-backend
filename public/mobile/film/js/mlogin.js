    function tipShow(){
        $(".login-error").show();
    }
    function tipHide(){
        $(".login-error").hide();
    } 
    function chekNkNamefor() {
        var tip = $("#tip");
        var nkName = document.getElementById("username").value;
        if (nkName == "" || nkName == null || nkName == "请输入用户名/手机号") {
			layer.msg('请输入用户名/手机号');
            tipShow();
            return false;
        } else{
            tipHide();
            return true;
        }
    }

    function chekPwdfor_login() {
        var tip = $("#tip");
		$("#tpsmm").hide();
        var pwd = document.getElementById("password");
        if (pwd == null || pwd.value == "") {
			layer.msg('请输入密码');
            tipShow();
            return false;
        } else if (pwd.value.length < 6) {
			layer.msg('密码过短');
            tipShow();
            return false;
        } else {
            tipHide();
        }
        return true;
    }
    function loginSubmit(){
        var tip = $("#tip");
        if(!chekNkNamefor()){
            return;
        }else if(!chekPwdfor_login()){
            return;
        }else{
            var cookietime = 0;
            if(document.getElementById("cookietime").checked){
                cookietime = 2592000;
            }
            
            var url = "/user-login";
            var username = $("#username").val();
            var password = $("#password").val();
            $(".login :input").attr("disabled", true);
            $("#loginBt").val("登录中").addClass("disabled");
            $.post(url,{
                "username" : username,
                "password" : password,
				"dosubmit" : 1,
				"cookietime" : cookietime
                    },function(data){
                        if(data==1){
                            layer.msg("此账户限于4月6日中午12点前必须补交完成；否则系统删户处理，详情联系在线客服！");
                            tipShow();
                        }
                        else if(data==0){
                          
                            var callbackUrl = $('input[name="forward"]').val();
                            if (callbackUrl != "null")
                                window.location.href="/user-init";
                            else
                                window.location.href="/user-init";
                        }else if(data==-1){
                            layer.msg("用户名不存在");
                            tipShow();
                        }else if(data==-2){
                            layer.msg('您输入的用户名或密码有误，<a href="/user-forgetpassword">忘记登录密码？</a>');
                            tipShow();
                        }else if(data.indexOf("-3|") != -1){
                            layer.msg(data.replace("-3|", ""));
                            tipShow();
                        }else if(data==-3){
                            layer.msg('此账户限于4月6日中午12点前必须补交完成；否则系统删户处理，详情联系在线客服！');
                            tipShow();
                        }else if(data==-88){
                            layer.msg('您已多次输入错误用户名或密码，错误超过4次后账户将被锁定，<a href="/user-forgetpassword">忘记登录密码？</a>');
                            tipShow();
                        }
                        if (data != 0) {
                            $(".login :input").removeAttr('disabled');
                            $("#loginBt").val("登 录").removeClass("disabled");
                        }  
                    },"text"
            );
        }
    }
    if (document.addEventListener) { 
        document.addEventListener("keypress", fireFoxHandler, true);
    } else {
        document.attachEvent("onkeypress", ieHandler);
    }

    function fireFoxHandler(evt) { 
        if (evt.keyCode == 13) {
            loginSubmit()
        }
    }
    function ieHandler(evt) {
        if (evt.keyCode == 13) {
            loginSubmit()
        }
    }
