!
function t(e, n, i) {
    function a(r, s) {
        if (!n[r]) {
            if (!e[r]) {
                var l = "function" == typeof require && require;
                if (!s && l) return l(r, !0);
                if (o) return o(r, !0);
                throw new Error("Cannot find module '" + r + "'")
            }
            var c = n[r] = {
                "exports": {}
            };
            e[r][0].call(c.exports,
            function(t) {
                var n = e[r][1][t];
                return a(n ? n: t)
            },
            c, c.exports, t, e, n, i)
        }
        return n[r].exports
    }
    for (var o = "function" == typeof require && require,
    r = 0; r < i.length; r++) a(i[r]);
    return a
} ({
    "1": [function() {
        function t(t) {
            if (0 != t.length) {
                for (var e = 0; e < t.length; e++) t[e].title2 = t[e].title.length >= 16 ? t[e].title.substring(0, 16) + "...": t[e].title,
                t[e].url = t[e].url;
                return t
            }
        }
        function e() {
            function t() {
                e = setInterval(function() {
                    var t = $(".focusing ul.list_news").find("li:first"),
                    e = 32;
                    t.animate({
                        "marginTop": -e + "px"
                    },
                    2e3,
                    function() {
                        t.css("marginTop", 0).appendTo($(".focusing ul.list_news"))
                    })
                },
                3e3)
            }
            var e = null;
            $(".focusing ul.list_news li").length > 3 && t()
        }
        angular.element(document).ready(function() {
            angular.bootstrap(document.getElementById("bannerModule"), ["bannerModule"])
        });
        var n = angular.module("llcModule", []);
        n.directive("timeLeft", ["$interval",
        function(t) {
            return {
                "restrict": "A",
                "scope": {
                    "timeLeft": "=",
                    "onTimeEnded": "="
                },
                "template": "{{timeLeft && text}}",
                "link": function(e) {
                    function n() {
                        return parseInt((new Date).valueOf())
                    }
                    var i = function() {
                        var t = e.timeLeft,
                        n = ~~ (t / 1e3),
                        i = 0,
                        a = 0,
                        o = 0,
                        r = "";
                        n > 59 && (i = ~~ (n / 60), n %= 60),
                        i > 59 && (a = ~~ (i / 60), i %= 60),
                        a > 24 && (o = ~~ (a / 24), a %= 24),
                        r = "" + n + " \u79d2 ",
                        i && (r = "" + i + " \u5206 " + r),
                        a && (r = "" + a + " \u5c0f\u65f6 " + r),
                        o && (r = "" + o + " \u5929 " + r),
                        e.text = r
                    };
                    e.$watch("timeLeft", i);
                    var a = function() {
                        var i = null,
                        o = t(function() {
                            i = i || parseInt(e.timeLeft) + n() - 1e3,
                            e.timeLeft = i - n(),
                            e.timeLeft <= 0 && (t.cancel(o), e.onTimeEnded && e.onTimeEnded(function() {
                                a()
                            }))
                        },
                        1e3)
                    };
                    a()
                }
            }
        }]),
        n.controller("getLoanList", ["$scope", "$http",
        function(t, e) {
            {
                var n = {
                    "ADJUSTINTEREST": "specialAdjustRate",
                    "RETURNRATE": "specialReturnRate",
                    "LOAN_FOR_NEW_USER": "specialReturnRate"
                },
                i = {
                    "OPEN": {
                        "c": "orange"
                    },
                    "OPENED": {
                        "c": "orange"
                    },
                    "SCHEDULED": {
                        "c": "finished"
                    },
                    "FINISHED": {
                        "c": "gray"
                    },
                    "SETTLED": {
                        "c": "gray"
                    },
                    "CLEARED": {
                        "c": "gray"
                    }
                },
                a = {
                    "SCHEDULED": "\u5f85\u53d1\u552e",
                    "OPEN": "\u8d2d\u4e70",
                    "OPENED": "\u70ed\u552e\u4e2d",
                    "FINISHED": "\u5df2\u552e\u7f44",
                    "ZQ_FINISHED": "\u5df2\u8f6c\u8ba9",
                    "SETTLED": "\u6536\u76ca\u4e2d",
                    "CLEARED": "\u5df2\u7ed3\u6e05",
                    "FAILED": "\u5ba1\u6838\u4e2d",
                    "CANCELED": "\u5df2\u53d6\u6d88",
                    "OVERDUE": "\u903e\u671f",
                    "ARCHIVED": "\u5df2\u5b58\u6863"
                },
                o = [],
                r = function() {
                    var t = 0;
                    return $.ajax({
                        "type": "GET",
                        "async": !1,
                        "url": "/api/v2/server/date?_t=" + (new Date).valueOf(),
                        "data": {},
                        "success": function(e) {
                            t = e.serverDate
                        }
                    }),
                    t
                },
                s = function(t) {
                    var e = [];
                    return t.forEach(function(t) {
                        var r = {};
                        if (r.title = t.title, r.loanUrl = domainUrl(t.productKey ? "/financing/loan/" + t.id: "/financing/zqdetail/" + t.id), r.corporationUrl = domainUrl(t.corpUrl || ""), r.corporationShortName = t.corpName, r.corporationTip = t.corpDesc, r.rate = B.format.amount(t.percentRate, 1), B.utility.specialLoans.indexOf(t.id) > -1 ? r.rate = parseFloat(r.rate).toFixed(1) + "+1.5": B.utility.specialCorpName.indexOf(t.corpName) > -1 && t.timeopen >= new Date(2015, 9, 9, 0, 0, 0).valueOf() && t.timeopen < new Date(2015, 9, 22, 0, 0, 0).valueOf() && (r.rate = parseFloat(r.rate - 1).toFixed(1) + "+1.0"), t.privileges && t.privileges.length > 0 && (t.privileges = t.privileges.slice(0, 1), r.privileges = [], t.privileges.forEach(function(t) {
                            var e = {};
                            e.className = n[t.type],
                            t.value && (r.rate = parseFloat(r.rate).toFixed(1) + "+" + parseFloat(t.value).toFixed(1)),
                            e.text = t.name ? t.name: "",
                            r.privileges.push(e)
                        })), r.durationText = B.format.durationToString(t.duration), "undefined" != typeof t.creditDealRate && (r.creditDealRate = B.format.amount(100 * t.creditDealRate, 1)), t.productKey) {
                            var s = B.format.unit(t.balanceAmount < 0 ? 0 : t.balanceAmount, !1);
                            s.indexOf("\u4e07") > -1 ? (r.balanceText = s.replace("\u4e07", ""), r.balanceUnit = "\u4e07\u5143") : s.indexOf("\u4ebf") > -1 ? (r.balanceText = s.replace("\u4ebf", ""), r.balanceUnit = "\u4ebf\u5143") : (r.balanceText = s, r.balanceUnit = "\u5143")
                        } else r.balanceText = B.format.amount(t.balanceAmount < 0 ? 0 : t.balanceAmount, 2) + "\u5143";
                        r.repayMethod = B.format.getRepayMethodName(t.repayMethod),
                        r.btnClass = "btn-" + i[t.status].c,
                        r.statusText = t.productKey || "FINISHED" != t.status ? a[t.status] : a.ZQ_FINISHED,
                        r.activity = l(t),
                        r.timeOpen = t.timeopen,
                        "SCHEDULED" == t.status && o.indexOf(t.timeopen) < 0 && o.push(t.timeopen),
                        e.push(r)
                    }),
                    e
                },
                l = function(t) {
                    if (t.productKey && "FENG_YY" == t.productKey && t.corpName.indexOf("\u4e2d\u4fe1\u4fdd\u7406") > -1 && 0 == t.percentRate) {
                        var e = {};
                        return e.Url = domainUrl("/specialTopic/zx"),
                        e.Desc1 = "\u6295\u8d44\u9886iPhone6s",
                        e.Desc2 = "\u73b0\u8d27\u4e0d\u7528\u7b49",
                        e
                    }
                },
                c = function() {
                    var n = "/api/v2/loan/list?usedFor=home";
                    e.get(n).then(function(e) {
                        var n = e.data.data,
                        i = [],
                        l = [],
                        c = [],
                        u = [];
                        for (obj in n) if (n[obj] instanceof Array) switch (obj) {
                        case "FENG_CX":
                            i = s(n[obj]);
                            break;
                        case "FENG_RT":
                            l = s(n[obj]);
                            break;
                        case "FENG_YY":
                            c = s(n[obj]);
                            break;
                        case "creditAssign":
                            u = s(n[obj])
                        }
                        o.sort(function(t, e) {
                            return t - e
                        });
                        var d = 0,
                        g = _.filter(c,
                        function(t) {
                            return t.statusText == a.OPENED
                        }).length,
                        m = _.filter(i,
                        function(t) {
                            return t.statusText == a.OPENED
                        }).length,
                        f = _.filter(l,
                        function(t) {
                            return t.statusText == a.OPENED
                        }).length;
                        if (d = g > 0 ? 0 : m > 0 ? 1 : f > 0 ? 2 : 0, t.activeTabIndex = d, t.loansFcx = i, t.loansFrt = l, t.loansFyy = c, t.loansAssign = u, o.length > 0) {
                            t.scheduled = o[0];
                            var p = t.scheduled - r();
                            p > 0 && (t.timeLeft = p)
                        }
                    })
                };
                t.onTimeEnd = function(e) {
                    var n = function(e) {
                        e.forEach(function(e) {
                            e.statusText == a.SCHEDULED && e.timeOpen <= t.scheduled && (e.statusText = a.OPENED, e.btnClass = "btn-" + i.OPENED.c)
                        })
                    };
                    if (n(t.loansFcx), n(t.loansFrt), n(t.loansFyy), o.length > 0 && o.shift(), o.length > 0) {
                        t.scheduled = o[0];
                        var s = r(),
                        l = t.scheduled - s;
                        l > 0 && (t.timeLeft = l, e && e())
                    }
                }
            }
            c()
        }]);
        var i = angular.module("advertisingModule", []);
        i.controller("getAdvertising", ["$scope", "$http",
        function(t, e) {
            var n = "/api/v2/home/HomePageScroll";
            e.get(n).then(function(e) {
                t.isAdLoading = !1,
                t.advertisingNoData = !1,
                e.data.length || (t.advertisingNoData = !0, e.data = [{
                    "imageUrl": "",
                    "uri": "//img2.fengjr.com/public/dist/20151027211850/img/index/kong_advise-c50af747.jpg"
                },
                {
                    "imageUrl": "",
                    "uri": "//img2.fengjr.com/public/dist/20151027211850/img/index/kong_advise-c50af747.jpg"
                },
                {
                    "imageUrl": "",
                    "uri": "//img2.fengjr.com/public/dist/20151027211850/img/index/kong_advise-c50af747.jpg"
                },
                {
                    "imageUrl": "",
                    "uri": "//img2.fengjr.com/public/dist/20151027211850/img/index/kong_advise-c50af747.jpg"
                }]),
                t.advertisingData = e.data
            },
            function() {
                t.isAdLoading = !0
            })
        }]),
        i.controller("getRankData", ["$scope", "$http", "$timeout",
        function(t, e, n) {
            var i = "/statistics/api/v2/rank/top";
            e.get(i).then(function(e) {
                function i(t) {
                    for (var e = [], n = 0; n < t.length; n++) e.push({
                        "user": t[n].user,
                        "amount": "\uffe5" + B.format.amount(t[n].amount, 2)
                    });
                    return e
                }
                return t.rankDataFlag = !1,
                e.data && e.data.data && e.data.data["super-star"] && (t.totalData = i(e.data.data["super-star"].slice(0, 5))),
                e.data && e.data.data && e.data.data["month-star"] && e.data.data["month-star"].length && (t.rankMonthData = i(e.data.data["month-star"].slice(0, 5)), t.rankDataFlag = !0),
                n(function() {})
            }).then(function() {
                $("ul.rlist li").hover(function() {
                    $("ul.rlist li").removeClass("current_selected"),
                    $(".amount_s").hide(),
                    $("ul.rlist li").eq($(this).index()).addClass("current_selected"),
                    $(".amount_s").eq($(this).index()).show()
                })
            })
        }]);
        var a = angular.module("guideAndHelpModule", []);
        a.controller("getHelp", ["$scope", "$http",
        function(e, n) {
            var i = "/api/v2/home/help";
            n.get(i).then(function(n) {
                var i = n.data;
                0 == i.length && (i = [{
                    "title": "\u52a0\u8f7d\u4e2d\uff0c\u8bf7\u8010\u5fc3\u7b49\u5f85\uff01",
                    "url": ""
                }]),
                i = t(i).slice(0, 5),
                e.helpDataFlag = !1,
                e.helpData = i
            },
            function(t) {
                e.helpDataFlag = !0
            })
        }]),
        a.controller("getGuide", ["$scope", "$http",
        function(e, n) {
            var i = "/api/v2/home/newGuide";
            n.get(i).then(function(n) {
                var i = n.data;
                0 == i.length && (i = [{
                    "title": "\u52a0\u8f7d\u4e2d\uff0c\u8bf7\u8010\u5fc3\u7b49\u5f85\uff01",
                    "url": ""
                }]),
                i = t(i).slice(0, 5),
                e.guideDataFlag = !1,
                e.guideData = i
            },
            function(t) {
                e.guideDataFlag = !0
            })
        }]);
        var o = angular.module("focusingModule", []);
        o.controller("focusingCon", ["$scope", "$http", "$timeout",
        function(t, n, i) {
            var a = function(t) {
                if (0 != t.length) {
                    t.length > 9 && (t = t.slice(0, 9));
                    for (var e = 0; e < t.length; e++) t[e].title2 = t[e].title.length >= 16 ? t[e].title.substring(0, 16) + "...": t[e].title,
                    t[e].url = domainUrl("/cms/p/" + t[e].id);
                    return t
                }
            },
            o = "/api/v2/home/focus";
            n.get(o).then(function(e) {
                var n = e.data;
                return 0 == n.length && (n = [{
                    "title": "\u52a0\u8f7d\u4e2d\uff0c\u8bf7\u8010\u5fc3\u7b49\u5f85\uff01",
                    "url": ""
                }]),
                t.focusDataFlag = !1,
                t.focusingData = a(n),
                i(function() {})
            },
            function(e) {
                t.focusDataFlag = !0
            }).then(function() {
                e()
            })
        }]);
        var r = angular.module("zcModule", []);
        r.controller("zcCon", ["$scope", "$http", "$timeout",
        function(t, e, n) {
            var i = "/api/v2/crowdfundings/listAllProjectWithOrdinal";
            e.get(i).then(function(e) {
                function i(t) {
                    for (var e = {
                        "OPENED": "\u4f17\u7b79\u4e2d",
                        "FAILED": "\u7b79\u6b3e\u5931\u8d25",
                        "FINISHED": "\u4f17\u7b79\u6210\u529f",
                        "LOAN": "\u653e\u6b3e\u4e2d",
                        "SETTLED": "\u4f17\u7b79\u6210\u529f",
                        "DELIVED": "\u56de\u62a5\u5df2\u53d1\u9001",
                        "SCHEDULED": "\u5f85\u53d1\u552e"
                    },
                    n = "", i = "", a = 0; a < t.length; a++) {
                        var o = t[a];
                        if (t[a].project.detailUrl = domainUrl("/zc/" + t[a].project.id), t[a].project.percent = B.format.amount(100 * o.project.raiseAmount / o.project.goalAmount, 1) + "%", t[a].project.raiseAmount = B.format.amount(o.project.raiseAmount, 2) + "\u5143", t[a].project.goalAmount = B.format.amount(o.project.goalAmount, 2) + "\u5143", t[a].project.focusImg = o.images.preImage[0] || "//img2.fengjr.com/public/dist/20151027211850/img/new/zc-project-38b06999.png", n = 0 == a ? "/public/dist/20151027211850/img/index/on-the-way_b-467ccaf1.jpg": "//img1.fengjr.com/public/dist/20151027211850/img/index/on-the-way_s2-c4e1c6eb.jpg", -1 != ["FINISHED", "LOAN", "SETTLED", "DELIVED"].indexOf(o.project.status)) i = e[o.project.status],
                        t[a].isZcSuccessFlag = !1;
                        else if ("OPENED" == o.project.status) {
                            t[a].isZcSuccessFlag = !0;
                            var r = moment(o.project.openTime).add(o.project.timeOut, "hours"),
                            s = moment(),
                            l = r.diff(s, "days"),
                            c = r.diff(s, "hours");
                            i = 0 == l ? 0 == c ? "1\u5c0f\u65f6": c + "\u5c0f\u65f6": l + "\u5929"
                        } else "SCHEDULED" == o.project.status && (i = "\u5373\u5c06\u5f00\u59cb", t[a].isZcSuccessFlag = !1);
                        t[a].statusText = i,
                        t[a].preDjImg = t[a].images.preImage[0] || n
                    }
                    return t
                }
                function a(t) {
                    return 0 == t.length ? t: (t.sort(function(t, e) {
                        return e.project.openTime - t.project.openTime
                    }), t)
                }
                var o = _.groupBy(e.data,
                function(t) {
                    return t.project.ordinal ? t.project.ordinal: void 0
                }),
                r = [];
                for (var s in o) o[s].length >= 0 && a(o[s]),
                r.push(o[s][0]);
                return r = i(r.slice(0, 9)),
                t.isZcLoading = !1,
                t.zcData1 = r.slice(0, 1),
                t.zcData2 = r.slice(1, 3),
                t.zcData3 = r.slice(3),
                n(function() {})
            },
            function() {
                t.isZcLoading = !0
            }).then(function() {
                var t = function(t) {
                    for (var e = $("." + t).find("ul.tab li"), n = $("." + t).find(".content .box"), i = 0; i < e.length; i++) e[i].index = i,
                    $(e[i]).mouseover(function() {
                        $(e).removeClass("active"),
                        $(this).addClass("active"),
                        $(n).removeClass("active"),
                        $(n[this.index]).addClass("active")
                    })
                };
                t("zc_index")
            })
        }]);
        var s = angular.module("bannerModule", []);
        s.controller("bannerCon", ["$scope", "$http", "$timeout",
        function(t, e, n) {
            var i = "/api/v2/home/homeScroll";
            e.get(i).then(function(e) {
                var i = e.data;
                i.length || (i = [{
                    "orginal": 1,
                    "imageUrl": "",
                    "uri": "/public/dist/20151027211850/img/index/moren_bg_big-d8d0af46.jpg",
                    "salveImages": [{
                        "imageUrl": "",
                        "orginal": 0,
                        "uri": "/public/dist/20151027211850/img/index/moren_bg_0-593c989d.jpg"
                    }]
                }]);
                for (var a = $(".public-messagee-user").attr("un-id"), o = 0; o < i.length; o++) {
                    var r = i[o];
                    if (r.salveImages && r.salveImages.length > 0) {
                        for (var s = {},
                        l = 0; l < r.salveImages.length; l++) r.salveImages[l].imageUrl = r.salveImages[l].imageUrl || "",
                        s[r.salveImages[l].orginal] = r.salveImages[l];
                        a && s[2] && (s[2] = {
                            "uri": "/public/dist/20151027211850/img/index/toAccount-a7074058.png",
                            "imageUrl": "/account"
                        }),
                        r.modifyImages = s
                    } else r.modifyImages = {
                        "0": {
                            "uri": "/public/dist/20151027211850/img/new/moren-de89943f.jpg",
                            "imageUrl": "/"
                        }
                    }
                }
                return t.bannerDataFlag = !1,
                t.bannerData = i,
                n(function() {})
            },
            function(e) {
                t.bannerDataFlag = !0
            }).then(function() {
                new Slide({
                    "targetEle": ".prime-slide"
                })
            })
        }])
    },
    {}],
    "2": [function() {
        function t(t) {
            this.lazy = this.baseAPI.getElementsByClass(t),
            this.fnLoad = this.baseAPI.bind(this, this.load),
            this.load(),
            this.baseAPI.on(window, "scroll", this.fnLoad),
            this.baseAPI.on(window, "resize", this.fnLoad)
        }
        t.prototype = {
            "load": function() {
                var t = document.documentElement.scrollTop || document.body.scrollTop,
                e = document.documentElement.clientHeight + t,
                n = 0,
                i = 0,
                a = 0,
                o = this.loaded(0);
                if (this.loaded(1).length != this.lazy.length) {
                    var r = o.length;
                    for (n = 0; r > n; n++) {
                        i = this.baseAPI.pageY(o[n]) - 100,
                        a = this.baseAPI.pageY(o[n]) + o[n].offsetHeight + 100;
                        var s = i > t && e > i ? !0 : !1,
                        l = a > t && e > a ? !0 : !1;
                        if (s || l) {
                            var c = this.baseAPI.attr(o[n], "js-src") || "";
                            if (c) {
                                var u = document.createElement("script");
                                u.type = "text/javascript",
                                u.src = c,
                                document.body.appendChild(u)
                            }
                            this.baseAPI.hasClass(o[n], "loaded") || ("" != o[n].className ? o[n].className += " loaded": o[n].className = "loaded")
                        }
                    }
                }
            },
            "loaded": function(t) {
                var e = [],
                n = 0;
                for (n = 0; n < this.lazy.length; n++) {
                    var i = this.baseAPI.hasClass(this.lazy[n], "loaded");
                    t || i || e.push(this.lazy[n]),
                    t && i && e.push(this.lazy[n])
                }
                return e
            },
            "baseAPI": {
                "on": function(t, e, n) {
                    return t.addEventListener ? t.addEventListener(e, n, !1) : t.attachEvent("on" + e, n)
                },
                "bind": function(t, e) {
                    return function() {
                        return e.apply(t, arguments)
                    }
                },
                "pageX": function(t) {
                    return t.offsetLeft + (t.offsetParent ? arguments.callee(t.offsetParent) : 0)
                },
                "pageY": function(t) {
                    return t.offsetTop + (t.offsetParent ? arguments.callee(t.offsetParent) : 0)
                },
                "hasClass": function(t, e) {
                    return new RegExp("(^|\\s)" + e + "(\\s|$)").test(t.className)
                },
                "attr": function(t, e, n) {
                    return 2 == arguments.length ? t.attributes[e] ? t.attributes[e].nodeValue: void 0 : void(3 == arguments.length && t.setAttribute(e, n))
                },
                "getElementsByClass": function(t) {
                    for (var e = [], n = document.getElementsByTagName("*"), i = n.length, a = 0; i > a; a++) this.hasClass(n[a], t) && e.push(n[a]);
                    return e
                }
            }
        },
        window.LazyLoadJS = t
    },
    {}],
    "3": [function(t) {
        "use strict";
        t("../controllers/index.js"),
        t("./slide.js"),
        t("./LazyLoadJS.js"),
        $(function() {
            function t() {
                $.get("", {},
                function(t) {
                    if (t.success && (t.data.turnover || t.data.creditAssign) && t.data.bidDays) {
                        var n = Number(t.data.turnover) + Number(t.data.creditAssign || 0),
                        i = Number(t.data.profit || 0),
                        a = new CountUp("amoutmoney1", 0, t.data.bidDays, 0, 2.5, e),
                        o = new CountUp("amoutmoney2", 0, t.data.userCount, 0, 2.5, e),
                        r = new CountUp("amoutmoney3", 0, n, 0, 2.5, e),
                        s = new CountUp("amoutmoney4", 0, i, 0, 2.5, e);
                        a.start(),
                        o.start(),
                        r.start(),
                        s.start()
                    }
                })
            }
            var e = {
                "useEasing": !0,
                "useGrouping": !0,
                "separator": ",",
                "decimal": ".",
                "prefix": "",
                "suffix": ""
            };
            t(),
            setInterval(function() {
                t()
            },
            3e5),
            $("li.llc_nav").mouseover(function() {
                var t = [domainUrl("/projectlist.php?type=rt"), domainUrl("/projectlist.php?type=dt"), domainUrl("/projectlist.php?type=yt"), domainUrl("/projectlist.php?type=vip")];
                $("li.llc_nav").removeClass("llc_active"),
                $(this).addClass("llc_active"),
                $(".llc_loantext").hide(),
                $(".goodlink").hide(),
                $(".goodmessage").hide(),
                $(".llc_loantext").eq($(this).index()).show(),
                $(".goodlink").eq($(this).index()).show(),
                $(".goodmessage").eq($(this).index()).show(),
                $(".more").find("a").attr("href", t[$(this).index()]),
                0 == $(this).index() ? ($(".zqTips").hide(),$(".ytTips").hide(),$(".vipTips").hide(),$(".normalTips").show()) : ( $(".normalTipsx").hide()),
                1 == $(this).index() ? ($(".normalTips").hide(),$(".ytTips").hide(),$(".vipTips").hide(),$(".zqTips").show()) : ( $(".normalTipsx").hide()),
                2 == $(this).index() ? ($(".zqTips").hide(),$(".normalTips").hide(),$(".vipTips").hide(),$(".ytTips").show()) : ( $(".normalTipsx").hide()),
                3 == $(this).index() ? ($(".zqTips").hide(),$(".ytTips").hide(),$(".normalTips").hide(),$(".vipTips").show()) : ( $(".normalTipsx").hide())
            }),
            $("li.lld_nav").mouseover(function() {
                0 == $(this).index() ? $(".fqd").removeClass("fjd") : $(".fqd").addClass("fjd"),
                $("li.lld_nav").removeClass("llc_active"),
                $(this).addClass("llc_active"),
                $(".con_lld").hide(),
                $(".con_lld").eq($(this).index()).show()
            }),
            new LazyLoadJS("lazyloadjs"),
            B.utility.cookie("isBottomShadeShow") || $(".bottom_shade").animate({
                "bottom": "0px"
            },
            500),
            $(".bottom_shade .content .btnClose").click(function() {
                $(".bottom_shade").animate({
                    "bottom": "-130px"
                },
                500),
                B.utility.cookie("isBottomShadeShow", "true")
            })
        })
    },
    {
        "../controllers/index.js": 1,
        "./LazyLoadJS.js": 2,
        "./slide.js": 4
    }],
    "4": [function() {
        function t(t) {
            this.settings = $.extend({
                "targetEle": null,
                "callback": function() {}
            },
            t),
            this.speed = 700,
            this.autoSpeed = 6e3,
            this.$targetEle = $(this.settings.targetEle),
            this.$itemList = this.$targetEle.find(".ps-item"),
            this.$itemBgList = this.$targetEle.find(".ps-bg-item"),
            this.$triggerList = this.$targetEle.find(".trigger-item"),
            this.$triggerPre = this.$targetEle.find(".ps-trigger-pre"),
            this.$triggerNext = this.$targetEle.find(".ps-trigger-next"),
            this.animateLock = !1,
            this.autoTimer = null,
            this.currentIndex = 0,
            this.init()
        }
        t.prototype.init = function() {
            this.initStyle(),
            this.bindEvent(),
            this.autoPlay()
        },
        t.prototype.bindEvent = function() {
            var t = this;
            this.$targetEle.find(".trigger-item").on("click",
            function() {
                var e = $(this),
                n = e.index();
                t.animateLock || t.switchTo(n)
            }),
            this.$targetEle.find(".item-puzzle").on({
                "mouseenter": function() {
                    clearInterval(t.autoTimer)
                },
                "mouseleave": function() {
                    t.autoPlay()
                }
            }),
            this.$targetEle.find(".ps-trigger").on({
                "mouseenter": function() {
                    clearInterval(t.autoTimer)
                }
            }),
            this.$targetEle.find(".ps-trigger-pre").on({
                "click": function() {
                    var e = t.$itemList.length,
                    n = t.getCurrentIndex(),
                    i = 0 === n ? e - 1 : n - 1;
                    t.animateLock || t.switchTo(i)
                },
                "mouseenter": function() {
                    clearInterval(t.autoTimer)
                },
                "mouseleave": function() {
                    t.autoPlay()
                }
            }),
            this.$targetEle.find(".ps-trigger-next").on({
                "click": function() {
                    var e = t.$itemList.length,
                    n = t.getCurrentIndex(),
                    i = n === e - 1 ? 0 : n + 1;
                    t.animateLock || t.switchTo(i)
                },
                "mouseenter": function() {
                    clearInterval(t.autoTimer)
                },
                "mouseleave": function() {
                    t.autoPlay()
                }
            }),
            this.$targetEle.on({
                "mouseenter": function() {
                    t.$targetEle.find(".ps-trigger-pre").stop().fadeIn(),
                    t.$targetEle.find(".ps-trigger-next").stop().fadeIn()
                },
                "mouseleave": function() {
                    t.$targetEle.find(".ps-trigger-pre").stop().fadeOut(),
                    t.$targetEle.find(".ps-trigger-next").stop().fadeOut()
                }
            })
        },
        t.prototype.getCurrentIndex = function() {
            var t = this.$targetEle.find(".trigger-item.current").index();
            return t
        },
        t.prototype.initStyle = function() {
            this.$itemBgList.eq(0).css({
                "z-index": "1",
                "opacity": "1"
            }).siblings().css({
                "z-index": "0",
                "opacity": "0"
            }),
            this.$itemList.eq(0).css({
                "z-index": "5"
            }).siblings().css({
                "z-index": "4"
            }),
            this.resetOtherFrames(0)
        },
        t.prototype.switchTo = function(t) {
            var e = this,
            n = this.$itemList.eq(t),
            i = this.$itemBgList.eq(t);
            this.animateLock = !0,
            this.$triggerList.eq(t).addClass("current").siblings().removeClass("current"),
            i.siblings().css({
                "z-index": "0"
            }),
            i.css({
                "zIndex": "1"
            }).stop(!0).animate({
                "opacity": "1"
            },
            this.speed, "easeInOutQuint",
            function() {
                $(this).siblings().css({
                    "opacity": "0"
                })
            }),
            n.siblings().css({
                "z-index": "4"
            }),
            n.css({
                "zIndex": "5"
            }).find(".puzzle-item.item-1 .puzzle-item-block").stop(!0).animate({
                "margin-left": "0px"
            },
            this.speed, "easeInOutQuint",
            function() {
                e.resetOtherFrames(e.getCurrentIndex()),
                e.animateLock = !1
            }),
            n.find(".puzzle-item.item-2 .puzzle-item-block").stop(!0).animate({
                "margin-left": "0px"
            },
            this.speed - 200, "easeInOutQuint"),
            n.find(".puzzle-item.item-3 .puzzle-item-block").stop(!0).animate({
                "margin-left": "0px"
            },
            this.speed - 100, "easeInOutQuint")
        },
        t.prototype.resetOtherFrames = function(t) {
            var e = this.$itemList.eq(t);
            e.siblings().each(function() {
                $(this).css({
                    "z-index": "4"
                }).removeClass("current").removeClass("init"),
                $(this).find(".puzzle-item").find(".puzzle-item-block").removeAttr("style")
            })
        },
        t.prototype.resetFrame = function(t) {
            var e = this.$itemList.eq(t);
            e.css({
                "z-index": "4"
            }).removeClass("current").removeClass("init"),
            e.find(".puzzle-item").find(".puzzle-item-block").removeAttr("style")
        },
        t.prototype.autoPlay = function() {
            var t = this,
            e = this.$itemList.length;
            clearInterval(t.autoTimer),
            this.autoTimer = setInterval(function() {
                var n = t.getCurrentIndex(),
                i = n === e - 1 ? 0 : n + 1;
                t.switchTo(i)
            },
            t.autoSpeed)
        },
        window.Slide = t
    },
    {}]
},
{},
[3]);