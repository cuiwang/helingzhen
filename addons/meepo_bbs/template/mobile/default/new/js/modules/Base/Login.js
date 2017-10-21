!function(a, b) {
    a.Login = b(a.$)
}(this, function() {
    function a() {
        mqq.data.getUserInfo(function(a) {
            for (var b = 0; b < h.length; b++)
                h[b]();
            h = [],
            i = Date.now()
        })
    }
    function b(b, c) {
        if (h.push(b),
        ($.os.ios || $.os.android) && (mqq.compare("4.7.0") >= 0 || navigator.userAgent.indexOf(" Tribe/") > -1)) {
            var d = Date.now() - i;
            d >= j ? a() : (clearTimeout(e),
            e = setTimeout(function() {
                a()
            }, j - d))
        } else if (navigator.userAgent.match(/\/qqdownloader\/(\d+)?/))
            YybJsBridge.login();
        else if (navigator.userAgent.match(/\bMicroMessenger\/[\d\.]+/)) {
            var f = "http://" + (c || "xiaoqu.qq.com") + "/cgi-bin/bar/extra/wx/auth?redirect_uri=" + encodeURIComponent(window.location.href)
              , g = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxaaec71d24bf34f1d&redirect_uri=" + encodeURIComponent(f) + "&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
            window.location.href = g
        } else {
            var k = encodeURIComponent("http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/login-logo.png");
            window.location.href = "http://ui.ptlogin2.qq.com/cgi-bin/login?pt_no_onekey=1&style=9&appid=1006102&daid=371&s_url=" + encodeURIComponent(window.location.href) + "&low_login=0&hln_css=" + k
        }
    }
    function c() {
        DB.cgiHttp({
            url: f,
            type: "GET"
        })
    }
    function d() {
        $.os.ios || $.os.android || (c(),
        setTimeout(function() {
            d()
        }, g))
    }
    var e, f = "http://xiaoqu.qq.com/cgi-bin/bar/user/sync_auth_state", g = 9e5, h = [], i = 0, j = 1e3, k = function() {
        var a = $.cookie("uin");
        return a = a ? parseInt(a.substring(1, a.length), 10) : $.cookie("BL_ID"),
        a ? a + "" : null 
    }
    ;
    return {
        continueLogin: d,
        notLoginCallback: b,
        getUin: k
    }
});
