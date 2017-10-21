!function() {
    var a = "http://buluo.qq.com/cgi-bin/bar/tdw/report?"
      , b = Object.prototype.toString
      , c = {}
      , d = []
      , e = []
      , f = ""
      , g = function(b, c, d, e, f, g) {
        if (b && c && d) {
            var h = {
                table: b,
                fields: JSON.stringify(c),
                datas: JSON.stringify(d),
                pr_ip: e || "obj3",
                pr_t: f || "ts",
                t: +new Date
            };
            g ? Q.send(a + mqq.toQuery(h), null , !0) : Q.send(a + mqq.toQuery(h))
        }
    }
      , h = function(a, h, j) {
        if (!a)
            throw "params can not be null";
        a.uin = Login.getUin(),
        a.ver2 = Util.queryString("activity_from") || Util.queryString("from") || Util.getHash("from") || "other";
        var k = "";
        if (k = $.os.ios ? "ios" : $.os.android ? "android" : "other",
        mqq.QQVersion) {
            var l = mqq.QQVersion.split(".");
            l.length > 3 && l.pop(),
            l = l.join("."),
            k += "-" + l
        }
        EnvInfo.network && (k += "-" + EnvInfo.network),
        k && (a.obj2 = k),
        f = j || f || "dc00141";
        var m, n, o;
        switch (b.call(a)) {
        case "[object Array]":
            m = a;
            break;
        case "[object Object]":
            if (h) {
                var p = []
                  , q = [];
                for (n in a)
                    if (a.hasOwnProperty(n)) {
                        if ("obj3" === n || "ts" === n)
                            continue;p.push(n),
                        q.push(a[n])
                    }
                return g(f, p, [q], a.obj3, a.ts, !0)
            }
            m = [a]
        }
        for (n = 0,
        o = m.length; o > n; n++) {
            var r = m[n]
              , s = [];
            e.push(s);
            for (var t in r)
                if (r.hasOwnProperty(t)) {
                    var u;
                    t in c ? (u = c[t],
                    d[u] = t) : (d.push(t),
                    c[t] = u = d.length - 1),
                    s[u] = r[t] || ""
                }
        }
        h ? i(!0) : Q.tick(i)
    }
      , i = function(a) {
        var b = e.length;
        if (b > 1)
            for (var h = 0; b > h; h++)
                for (var i = 0, j = d.length; j > i; i++)
                    e[h][i] || 0 === e[h][i] || (e[h][i] = "");
        a ? g(f, d, e, null , null , !0) : g(f, d, e),
        d.length = e.length = 0,
        c = {},
        f = ""
    }
    ;
    Q.mix(Q, {
        tdw: h
    })
}();