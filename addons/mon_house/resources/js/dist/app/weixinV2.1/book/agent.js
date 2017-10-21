define("app/weixinV2.1/book/agent", ["gallery/zepto/1.1.3/zepto", "app/weixinV2.1/book/utilities"], function (a) {
    var b = a("gallery/zepto/1.1.3/zepto"), c = a("app/weixinV2.1/book/utilities");
    b(function () {
        function a() {
            b("a.guan").on("click", function () {
                return b.mobileModal.hideModal(), !1
            })
        }

        var d = (b("div[data-moduleId='house_agent']"), b("#agentlist")), e = d.find("img111"), f = d.find("a.wx"), g = d.find("a.tel");
        e.on("click", function () {

            var d = b(this).parent().attr("href");
            return b.mobileModal.buildContainer(), b.mobileModal.showModal(b("#loading-container").html()), c.getAjaxPageBody(d, function (d) {
                var e = c.getDomBody(d);
                b.mobileModal.showModal(e), a()
            }, function () {
            }), !1
        }), g.on("click", function (a) {
            a.stopPropagation()
        }), f.on("click", function () {
            var a = b(this).attr("href");
            return window.location = a, !1

        })
    })
});