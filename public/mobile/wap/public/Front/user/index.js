function() {
    $.ajaxSetup({
        cache: false
    });
    var a = new Date().getTime();
    var c = 15 * 1000;
    $("head").ajaxSuccess(function() {
        var e = new Date().getTime();
        if (e - a > c) {
            d();
            a = e
        }
    }); (function(g) {
        var f;
        window.lufaxIndex = f = {};
        f.init = function() {
            f.autoChange()
        };
        var e = new Date().getTime() f.autoChange = function() {
            g(".notice-function-head a,.notice-function-list").hover(function() {
                f.tabChange(this, ".notice-function-list");
                clearInterval(h)
            },
            function() {
                h = setInterval(function() {
                    f.tabChange(".notice-function-head a.mouse-enter", ".notice-function-list")
                },
                30000)
            })
        }
    })(jQuery);
    $(function() {
        lufaxIndex.init();
        autoScroll.init();
        $(".select-box").click(function() {
            $(this).find(".select-box-list").fadeIn();
            $(this).mouseleave(function() {
                $(this).find(".select-box-list").fadeOut()
            })
        });
        tool.IE6Notice();
        $(".lazyload-part").lazyload({
            threshold: 100
        });
    })
});