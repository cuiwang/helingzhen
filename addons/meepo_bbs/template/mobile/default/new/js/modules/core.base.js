var Util = {};
/**
 * 解析url
 * */
var C = function(a){
	var b = window.location.search.match(new RegExp("(?:\\?|&)" + a + "=([^&]*)(&|$)")), c = b ? decodeURIComponent(b[1]) : "";
	return c
}

var E = function(a){
	var b = window.location.hash.match(new RegExp("(?:#|&)" + a + "=([^&]*)(&|$)")), c = b ? decodeURIComponent(b[1]) : "";
  return c || C(a)
}

var F = function(a, b){
	var c = E(a);
	c ? window.location.hash = window.location.hash.replace(new RegExp("(?:#|&)" + a + "=([^&]*)(&|$)"), function(a, c) {
        return a.replace("=" + c, "=" + b)
    }) : window.location.hash += "&" + [a, b].join("=")
}

function b(a, b, c, d, e) {
    var f, g, h, i = a.indexOf(H) > -1;
    if (g = L(a),
    g.query || (g.query = ""),
    g.fragment || (g.fragment = ""),
    i && (g.fragment && (h = g.fragment,
    g.fragment = ""),
    h && (g.query ? g.query += "&" + h : g.query = h),
    console.info("=======start redirect========="),
    f = (new Date).valueOf(),
    g.fragment += "time_redirect=" + f,
    a = M(g),
    mqq && mqq.compare("4.4") >= 0 && b && window.CGI_Preload && (localStorage.setItem("internal_preload", "1"),
    window.setTimeout(function() {
        window.CGI_Preload.preload(a)
    }, 0)),
    window.useOpen = !0,
    I && (g.query += "&_bid=" + I),
    G && (g.query += "&from=" + G),
    -1 !== a.indexOf("/mobile/personal.html") ? g.query += "&_wv=16777219" : -1 !== a.indexOf("/mobile/activity/") ? g.query += "&_wv=1025" : e ? g.query += "&_wv=16778243" : g.query += "&_wv=" + J,
    a = M(g)),
    d)
        return void window.location.replace(a);
    if (w)
        return void YybJsBridge.openNewWindow(a);
    var j = u();
    if (c = 1,
    b && window.mqq && mqq.iOS && mqq.compare("4.4") >= 0)
        i ? a += "&webview=1" : c = 0,
        mqq.ui.openUrl({
            url: a,
            target: 1,
            style: c
        });
    else if (b && window.mqq && j && j[1] >= "5.6")
        i ? a += "&webview=1" : c = 0,
        mqq.invoke("nav", "openLinkInNewWebView", {
            url: a,
            target: 1,
            style: c
        });
    else if (b && window.mqq && mqq.android) {
        if (!K(a))
            return;
        "search" === G ? (i ? a += "&webview=1" : c = 0,
        mqq.invoke("Troop", "openUrl", {
            url: a
        }, function(b) {
            b === mqq.ERROR_NO_SUCH_METHOD && mqq.ui.openUrl({
                url: a,
                target: 1,
                style: c
            })
        })) : "pa" === G || "dongtai" === G ? (i ? a += "&webview=1" : (c = 0,
        g = L(a),
        g.query || (g.query = ""),
        g.query += "&_wv=" + J,
        a = M(g)),
        -1 !== a.indexOf("/mobile/activity/") && (c = 0),
        mqq.ui.openUrl({
            url: a,
            target: 1,
            style: c
        })) : (i ? a += "&webview=1" : c = 0,
        mqq.ui.openUrl({
            url: a,
            target: 1,
            style: c
        }))
    } else {
        -1 !== a.indexOf(window.location.pathname) && (a = a.replace(/#/, "&t=" + +new Date + "#"));
        try {
            var k = document.createElement("a");
            k.href = a,
            k.click()
        } catch (l) {
            window.location.href = a
        }
    }
}


Util.queryString = C;
Util.getHash = E;
Util.setHash = F;
Util.openUrl = b;