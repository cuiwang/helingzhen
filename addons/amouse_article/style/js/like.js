define("appmsg/like.js", ["biz_common/dom/event.js", "biz_common/dom/class.js", "biz_wap/utils/ajax.js"],
    function (require, exports, module) {
    "use strict";
    function like_report(e) {
        var tmpAttr = el_like.getAttribute("like"), tmpHtml = el_likeNum.innerHTML, isLike = parseInt(tmpAttr) ? parseInt(tmpAttr) : 0, like = isLike ? 0 : 1, likeNum = parseInt(tmpHtml) ? parseInt(tmpHtml) : 0;
        ajax({
            url: "/mp/appmsg_like?__biz=" + biz + "&mid=" + mid + "&idx=" + idx + "&like=" + like + "&f=json&appmsgid=" + appmsgid + "&itemidx=" + itemidx,
            type: "POST",
            timeout: 2e3,
            success: function (res) {
                var data = eval("(" + res + ")");
                0 == data.base_resp.ret && (isLike ? (Class.removeClass(el_like, "praised"), el_like.setAttribute("like", 0),
                likeNum > 0 && "100000+" !== tmpHtml && (el_likeNum.innerHTML = likeNum - 1 == 0 ? "赞" : likeNum - 1)) : (el_like.setAttribute("like", 1),
                    Class.addClass(el_like, "praised"), "100000+" !== tmpHtml && (el_likeNum.innerHTML = likeNum + 1)));
            },
            async: !0
        });
    }

    var DomEvent = require("biz_common/dom/event.js"), Class = require("biz_common/dom/class.js"), ajax = require("biz_wap/utils/ajax.js"), el_toolbar = document.getElementById("js_toobar3");
    if (el_toolbar && el_toolbar.querySelector) {
        var el_like = el_toolbar.querySelector("#like3"), el_likeNum = el_toolbar.querySelector("#likeNum3"), el_readNum = el_toolbar.querySelector("#readNum3");
        DomEvent.on(el_like, "click", function (e) {
            return like_report(e), !1;
        });
    }
});