function send_verfiycodeRegister(obj) {
    if (!$(obj).hasClass("disabled")) {
        var username = $.trim($("#username").val()),
            phone = $.trim($("#phone").val()),
            telReg = !!phone.match(/^(0|86|17951)?(13[0-9]|15[012356789]|16[0-9]|17[0-9]|18[0-9]|19[0-9]|14[57])[0-9]{8}$/),
            _code = $.trim($("#exaCode").val());
        var _token = $("[name='_token']").val();

/*        if (!/\d{4}/.test(_code)) return "" == username ? ($("#username").parent().addClass("form_group_error"), $("#tip_name").show().children().next().html("用户名不能为空"), void $("#exaCode").parent().removeClass("form_group_error")) : _code ? ($("#exaCode").parent().addClass("form_group_error"),
            void $("#tip_codeImg").show().children().next().html("验证码格式错误")) : ($("#username").parent().removeClass("form_group_error"), $("#exaCode").parent().addClass("form_group_error"),
            void $("#tip_codeImg").show().children().next().html("请输入验证码"));*/
        if ($("#exaCode").parent().removeClass("form_group_error"), $("#username").parent().removeClass("form_group_error"), $("#tip_codeImg").hide(), 1 != telReg) return "" == phone ? ($("#phone").parent().addClass("form_group_error"), void $("#tip_phone").show().children().next().html("请您填写手机号")) : ($("#phone").parent().addClass("form_group_error"), void $("#tip_phone").show().children().next().html("您的手机号格式有误，请核实"));
        var url = "/sendsms?_token=" + _token + "&tel=" + phone + "&captcha=" + _code + "&t=" + Math.random();
        $.ajax({
            type: "POST",
            data: {},
            url: url,
            beforeSend: function () {
                $(obj).addClass("disabled").text("发送中...")
            },
            success: function (data) {
                var status = data.status;
                return "y" == status ? (back_timeRegister(obj), $("#exa").parent().addClass("form_group_error"), $("#tip_code").show().children().next().html("短信验证码已发送，请查收"), void $("#send_call_verify").hide()) : ("exist" == data.data ? ($("#phone").parent().addClass("form_group_error"), $("#tip_phone").show().children().next().html(data.info)) : ($("#exaCode").parent().addClass("form_group_error"), $("#tip_codeImg").hide().children().next().html(data.info),

                        msgbox(data.msg, obj)

                ), void $(obj).removeClass("disabled").text("获取验证码"))
            },
            error: function () {
                $(obj).removeClass("disabled").text("获取验证码")
            },
            dataType: "json"
        }),
            $("#phone").parent().removeClass("form_group_error"),
            $("#username").parent().removeClass("form_group_error"),
            $("#tip_phone").hide().children().next().html("您的手机号格式有误，请核实2")
    }
}

function back_timeRegister(o) {
    0 == wait ? ($(o).removeClass("disabled").text("获取验证码"), wait = 60, $("#send_call_verify").css("display", "block"), $("#send_call_verify") && $("#send_call_verify").empty(), $("#tip_code").length && $("#tip_code").css("display", "none"), $("#send_call_verify").length && $("#send_call_verify").html("验证码已发送到绑定手机，请注意查收！").show()) : ($(o).text("重新发送(" + wait + ")"), wait--, setTimeout(function () {
            back_timeRegister(o)
        },
        1e3))
}


function msgbox(msg, obj) {
    layer.open({
        content: msg,
        btn: '确定',
        shadeClose: false,
        yes: function (index) {
            layer.close(index);
            $(obj).removeClass("disabled").text("获取验证码");
        }
    });
}

function register_new() {
    var reg1 = /^[\w\-－＿\d\u4e00-\u9fa5\uFF3A\uFF41-\uff5a]+$/,
        reg2 = /^\d+$/,
        username = $("#username").val(),
        password = $("#pwd").val(),
        code = $("#exa").val(),
        exaCode = $("#exaCode").val(),
        phone = $("#phone").val(),
        qq = $("#qq").val(),
        yaoqingren = $("#yaoqingren").val(),
        source = getCookie("referrerName");
    var _token = $("[name='_token']").val();
    (null == source || source.length <= 0) && (source = "web");
    var pwd_again = $("#pwd_again").val(),
        agreed = $("#tiaokuan")[0].checked;
    if ("" == $.trim(username)) return $("#username").parent().addClass("form_group_error"),
        void $("#tip_name").show().children().next().html("用户名不能为空");
    if ($.trim(username).length < 4 || $.trim(username).length > 20) return $("#username").parent().addClass("form_group_error"),
        void $("#tip_name").show().children().next().html("用户名长度必须为4-20位");
    //if (null == reg1.exec($.trim(username)) || null != reg2.exec($.trim(username)) || $.trim(username).length > 15 || $.trim(username).length < 4) return $("#username").parent().addClass("form_group_error"),
    //void $("#tip_name").show().children().next().html("用户名格式错误");
    if ("" == $.trim(phone)) return $("#phone").parent().addClass("form_group_error"),
        void $("#tip_phone").show().children().next().html("请您填写手机号");
    var telReg = !!phone.match(/^(0|86|17951)?(13[0-9]|15[012356789]|16[0-9]|17[0-9]|18[0-9]|19[0-9]|14[57])[0-9]{8}$/);
    if (0 == telReg) return $("#phone").parent().addClass("form_group_error"),
        void $("#tip_phone").show().children().next().html("您的手机号格式有误，请核实");
    if (document.getElementById("exa") && "" == $.trim(code)) return $("#exa").parent().addClass("form_group_error"),
        void $("#tip_code").show().children().next().html("请您填写验证码");
    if ("" == $.trim(password)) return $("#pwd").parent().addClass("form_group_error"),
        void $("#tip_pwd").show().children().next().html("请您填写密码");
    if ($.trim(password).length < 6 || $.trim(password).length > 20) return $("#pwd").parent().addClass("form_group_error"),
        void $("#tip_pwd").show().children().next().html("密码长度必须为6-20位");
    if ("" == $.trim(pwd_again)) return $("#pwd_again").parent().addClass("form_group_error"),
        void $("#tip_pwd_again").show().children().next().html("请您填写确认密码");
    /*   if ("" == $.trim(qq)) return $("#qq").parent().addClass("form_group_error"),
       void $("#tip_qq").show().children().next().html("请您填写QQ");*/
    //if ($.trim(qq).length < 5 || $.trim(password).length > 12) return $("#qq").parent().addClass("form_group_error"),
    //void $("#tip_qq").show().children().next().html("QQ长度不对");
    if ("" == $.trim(yaoqingren)) return $("#yaoqingren").parent().addClass("form_group_error"),
        void $("#tip_yaoqingren").show().children().next().html("请您填写邀请人推荐ID");

    if (!agreed) return $("#tiaokuan").parent().addClass("form_group_error"),
        void $("#tip_tiaokuan").show().children().next().html("请勾选注册条款");
    if (password != pwd_again) return $("#pwd_again").parent().addClass("form_group_error"),
        void $("#tip_pwd_again").show().children().next().html("两次输入的密码不一致，请重新输入！");
    var url = "/register.html";
    $.ajax({
        type: "POST",
        data: {
            username: username,
            phone: phone,
            password: password,
            confirmpassword: pwd_again,
            qq: qq,
            yaoqingren: yaoqingren,
            captcha: exaCode,
            code: code,
            _token:_token
        },
        url: url,
        beforeSend: function () {
            $("#registerBtn").addClass("disable").val("正在注册")
        },
        success: function (data) {
            $("#registerBtn").removeClass("disable").val("注册中...");
            var status = data.status;
            if (!status) {
                $(".error_tip").show(), $("#error_detail_message").html(data.msg);
                $("#registerBtn").removeClass("disable").val("立即注册")
            } else {
                //alert(data.info);

                layer.open({
                    content: data.msg,
                    btn: '确定',
                    shadeClose: false,
                    yes: function (index) {
                        layer.close(index);
                        if(status==0){
                            window.location.href = "/login.html";
                        }


                    }
                });


            }
        },
        error: function () {
            $("#registerBtn").removeClass("disable").val("立即注册")
        },
        dataType: "json"
    })
}

$(function () {
    $(".errorTip").hide(),
        $(".ico_del").live("click",
            function () {
                $(this).parent().prev().val(""),
                    $(this).remove()
            }),
        $(".inp").focus(function () {
            $(this).parent().addClass("form_group_focus")
        }),
        $(".inp").focusout(function () {
            $(this).parent().removeClass("form_group_focus")
        }),
        $(".inp").live("keyup focus",
            function () {
                $(this).val().length > 0 ? $(this).next().replaceWith("<div class='error'><i class='ico_del'></i></div>") : ($(this).next(".error").hide(), $(".errorTip").hide(), $(".error_tip").hide())
            }),
        $("#pwd").live("keyup focus",
            function () {
                $("#pwd_tip").hide();
                var strongRegex = new RegExp("^(?=.{10,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g"),
                    mediumRegex = new RegExp("^(?=.{8,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g"),
                    enoughRegex = new RegExp("(?=.{6,}).*", "g");
                return 0 == enoughRegex.test($(this).val()) ? ($(this).parent().addClass("form_group_error"), $(this).parent().removeClass("form_group_flaw"), $(".pwd_tip em").css("width", "0")) : strongRegex.test($(this).val()) ? ($(this).parent().addClass("form_group_flaw"), $(this).parent().removeClass("form_group_focus"), $(this).parent().removeClass("form_group_error"), $(".pwd_tip em").css("width", "300px")) : mediumRegex.test($(this).val()) ? ($(this).parent().addClass("form_group_flaw"), $(this).parent().removeClass("form_group_error"), $(this).parent().removeClass("form_group_focus"), $(".pwd_tip em").css("width", "199px")) : ($(this).parent().addClass("form_group_flaw"), $(this).parent().removeClass("form_group_error"), $(this).parent().removeClass("form_group_focus"), $(".pwd_tip em").css("width", "100px")),
                    !0
            }),
        $("#pwd").focusout(function () {
            $("#pwd_tip").hide(),
                $(this).parent().removeClass("form_group_error"),
                $(this).parent().removeClass("form_group_focus"),
                $(this).parent().removeClass("form_group_flaw")
        }),
        $("#username").change(function () {
            var username = $("#username").val(),
                url = "/checkusername";
            $.ajax({
                type: "GET",
                data: {
                    username: username
                },
                url: url,
                success: function (data) {
                    var status = data.status;
                    return "1" == status ? ($("#username").parent().addClass("form_group_error"), void $("#tip_name").show().children().next().html(data.msg)) : ($("#username").parent().removeClass("form_group_error"), void $("#tip_name").show().children().next().html(data.msg))
                },
                dataType: "json"
            })
        }),
        $("#registerBtn").click(function () {
            $(this).hasClass("disable") || register_new()
        }),
        $("#getRegisterCode").click(function () {
            $(this).hasClass("disable") || send_verfiycodeRegister($(this)[0])
        })
}),
    $("#tip_phone").show().children().next().html("");