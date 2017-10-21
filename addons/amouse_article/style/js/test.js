define("appmsg/reward_entry.js", ["biz_common/dom/event.js", "biz_wap/utils/ajax.js"], function (e) {"use strict";
    function n(e) {
        e && (e.style.display = "block");
    }

    function r(e) {
        e && (e.style.display = "none");
    }

    function t(e) {
        var t = window.innerWidth || document.documentElement.innerWidth, a = (Math.ceil((c - 188) / 42) + 1) * Math.floor((t - 15) / 42);
        l = "http://mp.weixin.qq.com/mp/reward?act=getrewardheads&__biz=" + biz + "&appmsgid=" + mid + "&idx=" + idx + "&sn=" + sn + "&offset=0&count=" + a + "&source=1#wechat_redirect";
        var i = "#wechat_redirect", w = document.getElementById("js_reward_link");
        w && (w.href = "https://mp.weixin.qq.com/bizmall/reward?__biz=" + biz + "&appmsgid=" + mid + "&idx=" + idx + "&sn=" + sn + "&timestamp=" + e.timestamp + "&showwxpaytitle=1" + i),
            u = e.reward_head_imgs;
        var _ = d();
        m.reward && 1 == e.can_reward ? (n(m.reward), s.on(window, "load", function () {
            s.on(window, "scroll", o);
        })) : r(m.reward);
        var p = document.getElementById("js_reward_inner");
        p && _ > 0 && n(p);
        var f = document.getElementById("js_reward_total");
        f && (f.innerText = e.reward_total, f.setAttribute("href", l));
    }

    function a(e, n) {
        var r = document.createElement("span");
        r.className = "reward_user_avatar";
        var t = new Image;
        return t.onload = function () {
            window.logs && window.logs.reward_heads_total++, t.onload = t.onerror = null;
        }, t.onerror = function () {
            window.logs && window.logs.reward_heads_total++, window.logs && window.logs.reward_heads_fail++,
                t.onload = t.onerror = null;
        }, t.src = n, r.appendChild(t), e.appendChild(r), r;
    }

    function d() {
        if (u.length) {
            var e = document.getElementById("js_reward_list"), n = 0, r = document.createDocumentFragment();
            if (e) {
                for (var t = 0, d = u.length; d > t && (n++, a(r, u[t]), n != 3 * i); ++t);
                n > i && (e.className += " tl"), e.innerHTML = "", e.appendChild(r);
            }
            return n;
        }
    }

    function o() {
        var e = window.pageYOffset || document.documentElement.scrollTop;
        e + c > m.reward.offsetTop && (w({
            type: "GET",
            url: "/bizmall/reward?act=report&__biz=" + biz + "&appmsgid=" + mid + "&idx=" + idx,
            async: !0
        }), s.off(window, "scroll", o), o = null);
    }

    var i, l, s = e("biz_common/dom/event.js"), w = e("biz_wap/utils/ajax.js"), c = window.innerHeight || document.documentElement.clientHeight, m = {
        reward: document.getElementById("js_reward_area")
    }, u = [];
    return window.logs && (window.logs.reward_heads_total = 0, window.logs.reward_heads_fail = 0),
    {
        handle: function (e, n) {
            i = n, t(e);
        },
        render: function (e) {
            i = e, d();
        }
    };
});