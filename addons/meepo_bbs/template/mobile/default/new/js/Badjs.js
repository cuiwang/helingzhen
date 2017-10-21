(function() {
    var e = this || window
      , o = function() {
        var o = "";
        Config && "isOffline" in Config && (o = (Config.isOffline ? "[off]" : "[on]") + o),
        window.Config && window.Config.offlineVersion && (o = "[" + window.Config.offlineVersion + "]" + o);
        var n = /\/([^\.,^\/]+)\.html/.exec(e.location.href);
        return n && (o = [n[1]] + ":" + o),
        o
    }
      , n = function(e) {
        var n = o();
        (new Image).src = "http://buluo.qq.com/cgi-bin/feedback/re/err?l=4&c=" + encodeURIComponent(n + e) + "&uin=" + Login.getUin() + "&h=" + encodeURIComponent(location.hash) + "&t=" + (new Date).getTime()
    }
    ;
    e.Badjs = function() {
        var n = function(e, r, t, i, c, a) {
            var s = n._mid || 195375
              , l = n._bid || 102;
            if (a = a || 447770,
            i > 0 || 0 === i ? s = i : -1 === i && (e = "Script Error:" + e,
            e = o() + e),
            c = c || 4,
            "sodaRender" === t && (c = 0,
            a = 636427),
            !n._closed) {
                var f = new Image;
                if (387377 === i || 2 === i)
                    f.src = "http://badjs.qq.com/cgi-bin/js_report?level=" + c + "&bid=" + l + (s ? "&mid=" + s : "") + "&msg=" + e + "|_|file:" + window.location.pathname + encodeURIComponent(r) + "|_|" + t + "&r=" + Math.random();
                else if (c) {
                    var d = Login.getUin();
                    f.src = "http://buluo.qq.com/cgi-bin/feedback/re/err?l=" + c + "&c=" + encodeURIComponent(e) + "&uin=" + d + "&h=" + encodeURIComponent(location.hash) + "&t=" + (new Date).getTime()
                }
                f = null ;
                var u = new Image;
                u.src = "http://cgi.pub.qq.com/report/report_vm?monitors=[" + a + "]&t=" + Date.now(),
                u = null 
            }
        }
        ;
        n.init = function(e, o, r, t) {
            n._mid = o,
            n._bid = e,
            n._smid = r,
            n._closed = t
        }
        ,
        n.title = function(e, o) {
            var n = "";
            switch (e) {
            case "file":
                return o ? "File Load Error" : "File Load Success";
            case "cgi":
                switch (o) {
                case 0:
                    n = "CGI Load Success";
                    break;
                case 1:
                    n = "CGI NoLogin Error";
                    break;
                case 4:
                    n = "CGI BaseKey Error"
                }
                return n + "!Ec:" + (o || "");
            case "http":
                switch (o) {
                case -1:
                    n = "Http Empty Error";
                    break;
                case 200:
                    n = "HTTP Load Success";
                    break;
                case 404:
                    n = "HTTP Page Does Not Exist";
                    break;
                case 500:
                    n = "HTTP Server Error";
                    break;
                default:
                    n = "Http Info"
                }
                return n + "!Status:" + (o || "")
            }
        }
        ,
        n.cgiErrMinitor = function() {
            var e = new Image;
            e.src = "http://cgi.pub.qq.com/report/report_vm?monitors=[464198]&t=" + Date.now(),
            e = null 
        }
        ;
        var r = [];
        return e.onerror = function(e, o, t, i, c) {
            return c && (console.error(c.stack || e),
            console.report && console.report({
                type: "error",
                category: c.category || "",
                content: e
            })),
            r.splice.apply(arguments, [3, 0, -1, null , !1]),
            n.apply(this, arguments),
            window.Config && window.Config.globalOnError ? !0 : void 0
        }
        ,
        n
    }(),
    e.badjsReport = n
}
).call(this),
Badjs.init(258, 343293);