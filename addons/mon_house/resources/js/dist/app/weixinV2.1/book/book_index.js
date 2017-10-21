define("app/weixinV2.1/book/book_index", ["gallery/zepto/1.1.3/zepto", "app/weixinV2.1/book/global"], function (a) {
    var b = a("gallery/zepto/1.1.3/zepto"), c = a("app/weixinV2.1/book/global");
    b(function () {
        function a() {
            var a = d.find("div.bymu"), b = a.find(".bymu .gun"), e = b.find("p"), f = c.flipObj, g = b.eq(0).width(), h = e.eq(0).width(), i = g - h, j = 2, k = 1 == j ? 0 : j, l = f.count(), m = k / l, n = m * i;
            return e.html("<em>" + j + "</em>/" + l), e.css("left", n + "px"), !1
        }

        var d = b("div[data-moduleId='book_index']"), e = d.find("a[data-pageId]"), f = d.find("a[href^='tel:']"), g = d.find("a.external");
        f.on("click", function (a) {
            b("#fixedFooter a.zi").click(), a.stopPropagation()
        }), setTimeout(function () {
            var a = b(window).height(), c = d.find(".bytopf").height(), e = d.find(".bybot-box").height(), f = a - c - e;
            d.find(".bmu-box").height(f)
        }, 0), a(), d.find("#shareBtn").on("click", function () {
            var a = d.find(".maskshare img").clone();
            return b.mobileModal.showModal(a), a.one("click", function () {
                return b.mobileModal.hideModal(), !1
            }), !1
        }), d.find(".watchButton").on("click", function () {
            return b.mobileModal.hideModal(), b("#watch-container").show(), !1
        }), e.on("click", function () {
            var a = b(this).attr("data-pageId");
            return c.flipObj.setCurrentPage(a), !1
        }), g.on("click", function () {
            return window.location = b(this).attr("href"), !1
        })
    })
});