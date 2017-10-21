!function(a, b) {
    a.ViewPreloader = b(a.$, a.DB)
}(this, function() {
    var a, b = 15e3;
    return a = {
        canUseViewPreload: $.os.android && mqq.compare("5.0") >= 0,
        open: function(a) {
            if (this.canUseViewPreload) {
                var b = this
                  , c = a.cgiMap;
                localStorage.setItem("storageConfirm", 1),
                $.each(c, function(a, c) {
                    b.__removeDataStorage(a),
                    b.__send(a, c)
                })
            }
            this.__openView(a)
        },
        __openView: function(a) {
            if (a.url) {
                var b = a.url;
                this.canUseViewPreload && (b += b.indexOf("?") > -1 ? "&viewPreLoad=1" : "?viewPreLoad=1"),
                Util.openUrl(b, !0)
            } else
                a.callback && a.callback(this.canUseViewPreload ? 1 : 0)
        },
        __send: function(a, b) {
            var c, d = this;
            DB.cgiHttp({
                url: b.cgi,
                param: b.param,
                type: "get",
                succ: function(b) {
                    try {
                        c = b,
                        localStorage.setItem("viewPreloadData-" + a, JSON.stringify(c)),
                        mqq.dispatchEvent("preloadDataSuccess", {
                            dataName: a,
                            preloadData: c
                        })
                    } catch (e) {
                        localStorage.setItem("viewPreloadData-" + a, JSON.stringify({
                            type: "error"
                        })),
                        d.__dispatchError(a)
                    }
                },
                err: function() {
                    localStorage.setItem("viewPreloadData-" + a, JSON.stringify({
                        type: "error"
                    })),
                    d.__dispatchError(a)
                }
            })
        },
        __dispatchError: function(a) {
            mqq.dispatchEvent("preloadDataError", {
                dataName: a
            })
        },
        __removeDataStorage: function(a) {
            localStorage.removeItem("viewPreloadData-" + a)
        },
        __callbackHandler: function(a, b, c, d) {
            c && c({
                dataName: b,
                status: a,
                preloadData: d
            })
        },
        useViewPreload: function() {
            return ~~(Util.queryString("viewPreLoad") || Util.getHash("viewPreLoad"))
        },
        receive: function(a, c) {
            var d = this
              , e = this.useViewPreload();
            if (a = a || [],
            e) {
                var f, g, h = {}, i = localStorage.getItem("storageConfirm");
                if (localStorage.removeItem("storageConfirm"),
                "1" === i) {
                    for (var j = 0; j < a.length; j++)
                        g = a[j],
                        f = localStorage.getItem("viewPreloadData-" + g),
                        f && (f = JSON.parse(f),
                        a.splice(j, 1),
                        j--,
                        h[g] = 1,
                        "error" !== f.type ? d.__callbackHandler(0, g, c, f) : d.__callbackHandler(2, g, c)),
                        this.__removeDataStorage(g);
                    a.length && ($.each(a, function(a, b) {
                        d.__callbackHandler(1, b, c)
                    }),
                    mqq.addEventListener("preloadDataSuccess", function(a) {
                        var b = a.dataName;
                        if (d.__removeDataStorage(b),
                        !h[b]) {
                            h[b] = 1;
                            try {
                                f = a.preloadData,
                                f ? d.__callbackHandler(0, b, c, f) : d.__callbackHandler(2, b, c)
                            } catch (e) {
                                d.__callbackHandler(2, b, c)
                            }
                        }
                    }),
                    mqq.addEventListener("preloadDataError", function(a) {
                        var b = a.dataName;
                        d.__removeDataStorage(b),
                        h[b] = 1,
                        d.__callbackHandler(2, b, c)
                    }),
                    setTimeout(function() {
                        $.each(a, function(a, b) {
                            h[b] || (d.__removeDataStorage(b),
                            d.__callbackHandler(3, b, c))
                        })
                    }, b))
                } else
                    $.each(a, function(a, b) {
                        h[b] || (d.__callbackHandler(4, b, c),
                        d.__removeDataStorage(b),
                        h[b] = 1)
                    })
            }
        }
    }
});