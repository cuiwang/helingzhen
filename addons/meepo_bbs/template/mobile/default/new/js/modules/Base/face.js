!function(a) {
    function b(a, b) {
        return a.replace(b ? /&/g : /&(?!#?\w+;)/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#39;")
    }
    function c(a) {
        return a.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, '"').replace(/&#39;/g, "'")
    }
    function d(a, d, e) {
        a = encodeURI(c(a));
        var f = '<a href="javascript:void(0);" rel="openURL" url="' + a + '"'
          , g = null ;
        if (d)
            for (var h = 0; h < d.length; h++) {
                var i = d[h];
                if (!i.flag && encodeURI(c(i.url)) === a) {
                    g = i,
                    i.flag = 1;
                    break
                }
            }
        if (f += e ? ' class="link disabled"' : ' class="link"',
        f += ">",
        g && g.type) {
            var j = b(g.content) || "网页链接";
            if (j = j.replace(/&nbsp;/g, ""),
            j.length > 20 && (j = j.substring(0, 20) + "..."),
            "keyword" === g.type)
                if (e)
                    f = j;
                else {
                    var k = g.bid || "";
                    f = '<a href="javascript:void(0);" class="link link-keyword" rel="openURL" url="' + a + '" data-bid="' + k + '" >' + j + "</a>"
                }
            else
                f += '<span class="post-link link-' + g.type + '"></span>' + j + "</a>"
        } else
            f += '<span class="post-link link-normal"></span>网页链接</a>';
        return f
    }
    function e(a) {
        var b = [];
        for (var c in a)
            b.push(a[c]);
        return b
    }
    function f(a) {
        return "function" == typeof Array.isArray ? Array.isArray(a) : "[object Array]" === Object.prototype.toString.call(a)
    }
    function g(a, b, c) {
        if ("string" != typeof a)
            return "";
        for (var d = c ? "disabled" : "", e = b.length, f = 0; e > f; f++) {
            var g = b[f].content
              , h = b[f].bid
              , i = new RegExp(g,"gi");
            a = a.replace(i, function() {
                return '<a href="javascript:void(0);" class="link link-keyword ' + d + '" rel="openURL" url="http://xiaoqu.qq.com/mobile/barindex.html?_wv=1027&bid=' + h + '" >' + g + "</a>"
            })
        }
        return a
    }
    var h = /&lt;img.*?(?:&gt;|\/&gt;)/gi
      , i = /src=[\'\"]?([^\'\"]*)[\'\"]?/i
      , j = Config.FACE_2_TEXT
      , k = /\{\{(https?)\:\/\/(.*?)\}\}/gi
      , l = /\{\{\s*(\d+)\s*:\s*(\d)\s*\}\}/gi
      , m = /\{\{(tencentvideo?)\:\/\/(.*?)\}\}/gi
      , n = /(\[([^\[\]]+)\])/gi
      , o = new RegExp("\\/" + Config.FACE_2_TEXT.join("|\\/"),"g")
      , p = "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/face2/$index$.png"
      , q = 26
      , r = {
        richHtmlParse: function(a) {
            var b;
            return this.init(),
            b = this.asset,
            a.replace(this.rHtmlTag, function(a) {
                var c = "#$" + b.counter + "$#";
                return b[c] = a,
                b.counter += 1,
                c
            })
        },
        richHtmlRecover: function(a) {
            var b = this;
            return a.replace(/#\$\d+\$#/g, function(a) {
                return b.asset[a]
            })
        },
        init: function() {
            this.asset = {
                counter: 0
            },
            this.rHtmlTag = /<\w+[^>]*?>|<\/\w+>/g,
            this.mockHtml = ""
        }
    };
    a.replaceFaceCode = function(a) {
        return "string" != typeof a ? "" : a.replace(n, function(a, b, c) {
            var d = j.indexOf(c);
            return j.indexOf(c) > -1 ? '<img class="face" width="' + q + '" height="' + q + '" alt="' + c + '" src="' + p.replace("$index$", d) + '" >' : b
        }).replace(o, function(a) {
            var b = a.substring(1)
              , c = j.indexOf(b);
            return c > -1 ? (140 == c && (c = 122),
            141 == c && (c = 124),
            (142 == c || 143 == c) && (c = 33),
            144 == c && (c = 29),
            145 == c && (c = 79),
            146 == c && (c = 80),
            '<img class="face" width="' + q + '" height="' + q + '" alt="' + b + '" src="' + p.replace("$index$", c) + '" >') : a
        })
    }
    ,
    a.plain2rich = function(a, h, i, m) {
        var s = []
          , t = !1;
        if ("object" == typeof a && a && "number" != typeof a.length) {
            var u = a;
            a = u.hasOwnProperty("text") ? u.text : "",
            h = u.hasOwnProperty("search") ? u.search : "",
            i = u.hasOwnProperty("urlInfos") ? u.urlInfos : [],
            s = u.hasOwnProperty("keyInfos") ? u.keyInfos : [],
            m = u.hasOwnProperty("onlyText") ? u.onlyText : "",
            t = u.isRichPost
        }
        return "string" != typeof a ? "" : (f(i) || (i = e(i)),
        t && (a = r.richHtmlParse(a)),
        a = (h ? a : b(a)).replace(/\n/g, "<br>").replace(/(<br>){2,}/gi, "<br>"),
        a = g(a, s, m),
        a = a.replace(k, function(a, b, e) {
            var f = b + "://" + e;
            return d(c(f), i, m)
        }).replace(l, function(a, b, c) {
            if (c = Number(c)) {
                if (!Config.checkOpen) {
                    if (1 != c && 3 != c)
                        return b;
                    c = 1
                }
                return '<a href="javascript:void(0);" rel="showProfile" type="' + c + '" code="' + b + '" >' + b + "</a>"
            }
            return b
        }).replace(n, function(a, b, c) {
            var d = j.indexOf(c);
            return j.indexOf(c) > -1 ? '<img class="face" width="' + q + '" height="' + q + '" alt="' + c + '" src="' + p.replace("$index$", d) + '" >' : b
        }).replace(o, function(a) {
            var b = a.substring(1)
              , c = j.indexOf(b);
            return c > -1 ? (140 == c && (c = 122),
            141 == c && (c = 124),
            (142 == c || 143 == c) && (c = 33),
            144 == c && (c = 29),
            145 == c && (c = 79),
            146 == c && (c = 80),
            '<img class="face" width="' + q + '" height="' + q + '" alt="' + b + '" src="' + p.replace("$index$", c) + '" >') : a
        }),
        t ? r.richHtmlRecover(a) : a)
    }
    ,
    a.rich2plain = function(a, b) {
        if (a = a || "",
        "object" == typeof a && "number" != typeof a.length) {
            var g = a;
            a = g.hasOwnProperty("text") ? g.text : "",
            b = g.hasOwnProperty("urlInfos") ? g.urlInfos : []
        }
        return "string" != typeof a ? "" : (f(b) || (b = e(b)),
        a.replace(/(\n){3,}/gi, "\n").replace(/\n/g, " ").replace(k, function(a, e, f) {
            var g = e + "://" + f;
            return d(c(g), b, !0)
        }).replace(l, function(a, b, c) {
            return b
        }).replace(o, "").replace(n, "").replace(m, ""))
    }
    ,
    a.pureText = function(a, b) {
        return "string" != typeof a ? "" : a.replace(/(\n){3,}/gi, "\n").replace(/\n/g, " ").replace(/(<br>)/gi, " ").replace(l, function(a, b, c) {
            return b
        })
    }
    ,
    a.filterImgTag = function(a) {
        return a = a || "",
        a.replace(/<img[^>]*>/g, "")
    }
    ,
    a.getRSSContent = function(a) {
        for (var b = a.match(h) ? a.match(h) : [], c = [], d = ["p", "b"].join("|"), e = new RegExp("&lt;(" + d + ")&gt;|&lt;\\/(" + d + ")&gt;","ig"), f = 0; f < b.length; f++)
            c.push(b[f].match(i)[1]);
        for (var g = 0; g < c.length; g++)
            a = a.replace(/&lt;img.*?(?:&gt;|\/&gt;)/, "<image>src:" + c[g] + "<image>");
        return a.replace(e, "").replace(/(\n|\r){2,}/g, "\n").split("<image>")
    }
}(this)