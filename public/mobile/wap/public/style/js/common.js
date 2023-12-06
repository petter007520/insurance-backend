function placeholderSupport() {
    return "placeholder" in document.createElement("input")
}
function exit_user() {
    window.location = "/user/exit_login"
}
function getCookie(name) {
    var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
    return (arr = document.cookie.match(reg)) ? unescape(arr[2]) : null
}
function setCookie(name, value) {
    var Days = 1,
    exp = new Date;
    exp.setTime(exp.getTime() + 24 * Days * 60 * 60 * 1e3),
    document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString()
}
function back_time(o) {
    0 == wait ? ($(o).removeAttr("disabled"), o.value = "获取验证码", wait = 60, $("#send_call_verify").css("display", "block"), $("#send_call_verify") && $("#send_call_verify").empty(), $("#tip_code") && $("#tip_code").css("display", "none"), $("#send_call_verify") && $("#send_call_verify").html("验证码已发送到绑定手机，如未收到，<a onclick='to_send_call_verifycode()' href='javascript:void(0)' class='getVoiceCode'>点击语音获取</a>")) : ($(o).attr("disabled", "true"), o.value = "重新发送(" + wait + ")", wait--, setTimeout(function() {
        back_time(o)
    },
    1e3))
}
/*
function send_verfiycode(obj) {
    var phone = $.trim($("#phone").val()),
    telReg = !!phone.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[0-9]|18[0-9]|19[0-9]|14[57])[0-9]{8}$/);
    if (1 != telReg) return $("#phone").parent().addClass("form_group_error"),
    void $("#tip_phone").show().children().next().html("您的手机号格式有误，请核实");
    var url = "/user/sendcoderand?phone=" + phone + "&t=" + Math.random();
    $.ajax({
        type: "GET",
        data: {},
        url: url,
        success: function(data) {
            var status = data.status;
            return "0" == status ? (back_time(obj), $("#exa").parent().addClass("form_group_error"), void $("#tip_code").show().children().next().html("短信验证码已发送，请查收")) : ($("#exa").parent().addClass("form_group_error"), void $("#tip_code").show().children().next().html(data.message))
        },
        dataType: "json"
    }),
    $("#phone").parent().removeClass("form_group_error"),
    $("#tip_phone").hide().children().next().html("您的手机号格式有误，请核实")
}
function getReferrer() {
    var refeSource, referrer = document.referrer,
    cookieName = "referrerName";
    if ( - 1 != referrer.indexOf("://www.91wangcai.com/?sem")) {
        if (refeSource = referrer, referrer.indexOf("?")) {
            var UrlParams = referrer.split("?");
            if (UrlParams.length = 2) {
                var UrlParam = UrlParams[1];
                if (UrlParam.indexOf("&")) {
                    for (var params = UrlParam.split("&"), tempParam = "", i = 0; i < params.length; i++) {
                        var param = params[i];
                        if (param.indexOf("=")) {
                            var sources = param.split("=");
                            2 == sources.length && "source" == sources[0] && (tempParam.length > 0 && (tempParam += ";"), tempParam += sources[1])
                        }
                    }
                    tempParam.length > 0 && (refeSource = tempParam)
                }
            }
        }
    } else if ( - 1 == referrer.indexOf("://www.91wangcai.com") && referrer.length > 0 && (refeSource = referrer, -1 != referrer.indexOf("://"))) {
        var urls = referrer.split("://");
        if (urls.length >= 2) {
            var murl = urls[1];
            if ( - 1 != murl.indexOf("/")) {
                var rootUrls = murl.split("/");
                if (rootUrls.length > 0) {
                    var rootUrl = rootUrls[0];
                    rootUrl = rootUrl.replace(/www.|.com|.cn|.net|.org|.edu/g, ""),
                    refeSource = rootUrl
                }
            } else refeSource = murl.replace(/www.|.com|.cn|.net|.org|.edu/g, "")
        }
    }
    refeSource && setCookie(cookieName, refeSource)
}
var wait = 60;
$().ready(function() {
    $(".show").hover(function() {
        var a = $(this).children(".ico").attr("class").split(" ");
        $(this).children(".ico").addClass(a[1] + "Hover"),
        $(this).children(".none").show()
    },
    function() {
        var a = $(this).children(".ico").attr("class").split(" ");
        a[2] && $(this).children(".ico").removeClass(a[2]),
        $(this).children(".none").hide()
    }),
    $(window).scroll(function() {
        $(window).scrollTop() > 150 ? $(".backtop").fadeIn(500) : $(".backtop").fadeOut(500)
    }),
    $(".backtop a").click(function() {
        $("html,body").animate({
            scrollTop: 0
        })
    }),
    $(".tNav div").hover(function() {
        $(this).children(".box").show()
    },
    function() {
        $(this).children(".box").hide()
    }),
    placeholderSupport() || $("[placeholder]").live("keyup focus",
    function() {
        $(this).parent().children(".form_text").remove()
    }).blur(function() {
        var input = $(this); ("" == input.val() || input.val() == input.attr("placeholder")) && input.parent().append('<span class="form_text">' + input.attr("placeholder") + "</span>")
    }).blur(),
    $(".dropdown a").click(function() {
        var smenu = $(this).next();
        smenu.is(":hidden") ? ($(this).parent().addClass("active"), smenu.slideDown("fast")) : ($(this).parent().removeClass("active"), smenu.slideUp("fast"))
    }),
    $(".dropdown.active").length && $(".dropdown.active").children(".dropdown-menu").slideDown(0),
    $(".checkStatusBtn").click(function(e) {
        var _link = $(this).attr("href");
        $.ajax({
            url: "/user/judgeUserInfo",
            dataType: "json",
            cache: !1,
            type: "get",
            success: function(data) {
                return data && "-100" == data.status.toString() ? void(window.location.href = "/user/to_login") : data && data.data && data.data.realnameFlag && "0" == data.data.realnameFlag.toString() ? void WC.artDialog({
                    msg: data.message,
                    icon: "error",
                    close: function() {},
                    linkOne: {
                        href: "/user/setup",
                        title: "确认"
                    }
                }) : data && data.data && data.data.bindbankFlag && "0" == data.data.bindbankFlag.toString() ? void WC.artDialog({
                    msg: data.message,
                    icon: "error",
                    close: function() {},
                    linkOne: {
                        href: "/user/recharge/to",
                        title: "确认"
                    }
                }) : void(window.location.href = _link)
            },
            error: function() {}
        }),
        e.preventDefault()
    }),
    getReferrer()
});*/
