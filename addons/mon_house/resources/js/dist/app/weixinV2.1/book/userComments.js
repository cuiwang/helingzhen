define("app/weixinV2.1/book/userComments", ["gallery/zepto/1.1.3/zepto", "app/weixinV2.1/book/utilities"], function (a) {
    function b(b) {
        d.ajax({
            type: "get", dataType: "text", url: b, beforeSend: function () {
                d.mobileModal.buildContainer(), d.mobileModal.showModal(d("#loading-container").html())
            }, success: function (b) {
                d.mobileModal.getModalElement().addClass("transparentStyle");
                var g = e.getDomBody(b);
                d.mobileModal.showModal(g), f.addClass("blur"), c(), a.async("./userCommentForm.js")
            }, error: function () {
            }
        })
    }

    function c() {
        d("a.guan").on("click", function () {
            return d.mobileModal.hideModal(), d.mobileModal.getModalElement().removeClass("transparentStyle"), f.removeClass("blur"), !1
        })
    }

    var d = a("gallery/zepto/1.1.3/zepto"), e = a("app/weixinV2.1/book/utilities"), f = (d.mobileModal.getModalElement(), d("div[data-moduleId='user_comments']"));
    f.on("click", "a.comment", function () {
        var a = d(this).attr("href");
        return a && b(a), !1
    })
});