function() {
    var a;
    sodaRender.addEventListener("nullvalue", function(b) {
        "nullattr" === b.type && a && a(b.data)
    });
    var b = {}
      , c = function(a, b) {
        var c = {};
        for (var d in b)
            "from" !== d && "sid" !== d && "bkn" !== d && (c[d] = b[d]);
        var e = a + "_" + JSON.stringify(c);
        return e
    }
      , d = function() {}
      , e = {}
      , f = {
        bindEvent: function(a, b, c, d) {
            var e = a;
            $.os.ios && $(e)[0] == document.body && (e = window),
            $(e).on("scroll", function() {
                var a, b = 0;
                return function(e) {
                    function f() {
                        var a, f, g, h = e.target, i = 300;
                        if (h == document)
                            a = window.scrollY,
                            g = window.innerHeight,
                            f = document.body.scrollHeight;
                        else {
                            var j = window.getComputedStyle(h);
                            a = h.scrollTop,
                            g = parseInt(j.height) + parseInt(j.paddingTop) + parseInt(j.paddingBottom) + parseInt(j.marginTop) + parseInt(j.marginBottom),
                            f = h.scrollHeight
                        }
                        i = d || i,
                        parseInt(i) != i && (i = f * i),
                        a + g + i >= f && c && c(e),
                        b = a
                    }
                    window.clearTimeout(a),
                    a = window.setTimeout(f, 200)
                }
            }())
        },
        removeModel: function(a) {
            var b = a._scrollEl;
            if (b) {
                var c = b;
                "string" == typeof b && (c = $(b));
                var d;
                if (d = c == window ? "__window__" : c.attr("id"),
                c && c.length && d) {
                    for (var f = 0; f < e[d].length && e[d] !== a; f++)
                        ;
                    e[d].splice(f, 1)
                }
            }
        },
        addModel: function(a) {
            var b = a._scrollEl
              , c = b;
            "string" == typeof b && (c = $(b));
            var d;
            c == window ? d = "__window__" : (d = c.attr("id"),
            d || (d = "d_" + ~~(1e5 * Math.random()),
            c.attr("id", d))),
            e[d] ? e[d].push(a) : (e[d] = [a],
            this.bindEvent(c, d, function() {
                e[d].map(function(a) {
                    a.canScrollInMTB && ("scrollModel" === a.type ? !a.freezed && a.scrollEnable && a.rock() : a.freezed || "scrollModel" !== a.currModel.type || a.currModel.freezed || !a.currModel.scrollEnable || a.currModel.rock())
                })
            }, a.scrollThreshold))
        }
    }
      , g = {}
      , h = {}
      , i = function(a) {
        var b = function() {}
        ;
        if (b.renderTmpl = a.renderTmpl,
        b.cgiName = a.cgiName,
        b.renderContainer = a.renderContainer,
        b.param = a.param,
        b.beforeRequest = a.beforeRequest || function() {}
        ,
        b.beforeRender = a.beforeRender || function() {}
        ,
        b.processData = a.processData,
        b.beforeRenderHtml = a.beforeRenderHtml,
        b.afterRenderHtml = a.afterRenderHtml,
        b.data = a.data,
        b.renderTool = a.renderTool,
        b.onreset = a.onreset,
        b.events = a.events,
        b.complete = a.complete,
        b.myData = a.myData,
        b.error = a.error,
        b.noRefresh = a.noRefresh || 0,
        b.scrollEl = a.scrollEl,
        b.scrollThreshold = a.scrollThreshold,
        b.noCache = a.noCache,
        b.prefetch = a.prefetch,
        b.effect = a.effect,
        b.comment = a.comment,
        b.cacheKey = a.cacheKey || c,
        b.method = a.method,
        b.ssoCmd = a.ssoCmd,
        b.remember = a.remember,
        b.paramCache = [],
        b.cgiCount = 0,
        b.dataCache = [],
        b.feedPool = [],
        b.isFirstRender = 1,
        b.eventsBinded = 0,
        b.dead = 0,
        b.isFirstDataRequestRender = 0,
        b.usePreLoad = a.usePreLoad,
        b.__proto__ = i.prototype,
        b.prototype = b,
        "function" != typeof b.cacheKey && (b.cacheKey = function() {
            return a.cacheKey || ""
        }
        ),
        a.renderContainer && (g[a.renderContainer] ? g[a.renderContainer]++ : g[a.renderContainer] = 1),
        b.prefetch && !this._passedFirstRender)
            try {
                var d = "object" == typeof b.param && b.param || b.param.call(b);
                b.paramCache[0] = d;
                var e = {
                    url: b.cgiName,
                    param: d,
                    type: b.method || "POST",
                    ssoCmd: b.ssoCmd,
                    succ: function(a) {
                        if ("function" == typeof b.dataCache[0]) {
                            var c = b.dataCache[0];
                            b.dataCache[0] = a,
                            c()
                        } else
                            b.dataCache[0] = a
                    },
                    err: function(a) {
                        if ("function" == typeof b.dataCache[0]) {
                            var c = b.dataCache[0];
                            b.dataCache[0] = a,
                            c("error")
                        } else
                            b.dataCache[0] = null 
                    }
                }
                  , f = b.beforeRequest && b.beforeRequest();
                if ("boolean" == typeof f && !f)
                    return;
                DB.cgiHttp(e),
                b.dataCache[0] = "@prefeching"
            } catch (h) {
                b._prefetchError = 1
            }
        return b
    }
    ;
    i.prototype = {
        type: "renderModel",
        exportTab: function(a, b) {
            var c = "recieveModel" + a
              , d = "realizeModel" + a
              , e = this;
            window[c] && "function" == typeof window[c] ? window[c](a, this, b) : window[d] = function(c) {
                c(a, e, b),
                delete window[d]
            }
        },
        info: function(a) {
            if ("string" == typeof a) {
                var b = window.location.pathname.replace("/mobile/", "") + " >> " + this.comment;
                console.info(b + " : " + a),
                console.report && console.report({
                    type: "info",
                    category: b,
                    content: a
                })
            } else
                console.info(a)
        },
        tell: function(a, b, c) {
            var d = ["__", b, "__"].join("");
            "undefined" != typeof this[b] && (this[d] = this[b]),
            Object.defineProperty(this, b, {
                get: function() {
                    return this[d]
                },
                set: function(e) {
                    this[d] = e,
                    c ? c.call(a, e) : a[b] = e
                }
            })
        },
        watch: function(a, b, c) {
            var d = ["__", b, "__"].join("");
            "undefined" != typeof a[b] && (a[d] = a[b]);
            var e = this;
            Object.defineProperty(a, b, {
                get: function() {
                    return this[d]
                },
                set: function(a) {
                    this[d] = a,
                    c ? c.call(e, a) : e[b] = a
                }
            })
        },
        getCache: function(a) {
            return 0 === a ? this.localData : this.dataCache[a - 1]
        },
        getData: function(a) {
            var b, d = this, e = d.paramCache[d.cgiCount] || "object" == typeof this.param && this.param || this.param.call(this), f = e;
            if (this._args) {
                for (var g = JSON.stringify(e), i = 0; i < this._args.length; i++) {
                    var j = i + 1;
                    g = g.replace(new RegExp("@param" + j,"g"), this._args[i] || "")
                }
                g = g.replace(/@param\d+/g, ""),
                f = JSON.parse(g)
            }
            var k = ~~(1e6 * Math.random());
            h[k] = 0;
            var l = {
                url: this.cgiName,
                type: this.method || "POST",
                ssoCmd: this.ssoCmd,
                param: f,
                succ: function(b, g) {
                    if (g)
                        try {
                            a(b, k)
                        } catch (h) {
                            window.badjsReport && window.badjsReport("ModelCE_C:" + h.stack)
                        }
                    else {
                        if (d.dataCache[d.cgiCount] = b,
                        d.paramCache[d.cgiCount] = e,
                        d.cgiCount++,
                        1 == d.cgiCount) {
                            var i = (d.cacheKey || c)(d.cgiName, f);
                            if (i)
                                try {
                                    window.localStorage.setItem(i, JSON.stringify(b))
                                } catch (h) {
                                    window.localStorage.clear(),
                                    window.localStorage.setItem(i, JSON.stringify(b))
                                }
                        }
                        console.info("返回数据：", b);
                        try {
                            a(b, k, f)
                        } catch (h) {
                            window.badjsReport && window.badjsReport("ModelCE:" + h.stack)
                        }
                    }
                },
                err: function(a) {
                    d.info("cgi返回数据失败 cgiCount：" + d.cgiCount + ", url->" + d.cgiName),
                    console.info("返回数据：", a),
                    d.paramCache[d.cgiCount] = e;
                    try {
                        d.error && d.error.call(d, a, d.cgiCount)
                    } catch (b) {
                        window.badjsReport && window.badjsReport("ModelCE_E:" + b.stack)
                    }
                    d.eventsBinded || (d.events && d.events(),
                    d.hasOwnProperty("eventsBinded") ? d.eventsBinded = 1 : d.__proto__.eventsBinded = 1)
                }
            };
            if (this.usePreLoad && this.preLoadData) {
                if (this.info("使用预加载数据相关逻辑"),
                this.usePreLoad = !1,
                "error" !== this.preLoadData.type && 0 == this.preLoadData.retcode)
                    return void l.succ(this.preLoadData);
                l.err(this.preLoadData)
            }
            if (!this.dead) {
                if (!d.noCache && 0 == d.cgiCount && d.isFirstRender) {
                    var m = (d.cacheKey || c)(this.cgiName, f);
                    b = null ;
                    try {
                        b = JSON.parse(window.localStorage.getItem(m))
                    } catch (n) {}
                    if (b && b.result)
                        try {
                            l.succ(b, 1)
                        } catch (n) {
                            throw window.localStorage.removeItem(m),
                            $(window).trigger("renderError", [d.cgiName]),
                            Q.monitor(453668),
                            n.category = window.location.pathname.replace("/mobile/", "") + " >> " + this.comment,
                            n
                        }
                    d.isFirstRender = 0,
                    this.localData = b
                }
                if (this.dataCache[d.cgiCount]) {
                    d.info("@prefeching使用缓存数据");
                    var o = this.beforeRequest && this.beforeRequest.call(this);
                    if ("boolean" == typeof o && !o)
                        return;
                    "@prefeching" === this.dataCache[d.cgiCount] ? this.dataCache[d.cgiCount] = function(a) {
                        a ? l.err(d.dataCache[d.cgiCount]) : l.succ(d.dataCache[d.cgiCount])
                    }
                     : l.succ(this.dataCache[d.cgiCount])
                } else if (this.usePreLoad)
                    d.paramCache[d.cgiCount] = e;
                else {
                    var o = this.beforeRequest && this.beforeRequest();
                    if ("boolean" == typeof o && !o)
                        return;
                    this.info("ct->" + (Number(this.cgiCount) + 1) + ", url->" + l.url),
                    console.info("请求参数：", f),
                    DB.cgiHttp(l)
                }
            }
        },
        clearLocalData: function(a) {
            var b = this.paramCache[a || 0]
              , d = (this.cacheKey || c)(this.cgiName, b);
            window.localStorage.removeItem(d)
        },
        setPreLoadData: function(a) {
            this.preLoadData = a
        },
        reset: function() {
            this.cgiCount = 0,
            this.melt(),
            this.onreset && this.onreset()
        },
        stop: function() {
            for (var a in h)
                h.hasOwnProperty(a) && (h[a] = 1)
        },
        die: function() {
            "scrollModel" === this.type && f.removeModel(this),
            this.dead = 1
        },
        fetchNextData: function() {
            var a = this
              , b = "object" == typeof this.param && this.param || this.param.call(this)
              , c = this.cgiCount;
            this.paramCache[c] = b;
            var d = {
                url: this.cgiName,
                param: b,
                type: this.method || "POST",
                ssoCmd: this.ssoCmd,
                succ: function(b) {
                    if ("function" == typeof a.dataCache[c]) {
                        var d = a.dataCache[c];
                        a.dataCache[c] = b,
                        d()
                    } else
                        a.dataCache[c] = b
                },
                err: function(b) {
                    if ("function" == typeof a.dataCache[c]) {
                        var d = a.dataCache[c];
                        a.dataCache[c] = b,
                        d("error")
                    } else
                        a.dataCache[c] = null 
                }
            };
            DB.cgiHttp(d),
            this.dataCache[c] = "@prefeching"
        },
        render: function(c, d) {
            var e, f = this;
            this.scrollEnable = 0,
            "string" == typeof c && (c = $(c));
            var g = this.beforeRender && this.beforeRender();
            if ("boolean" != typeof g || g)
                if ("scrollModel" === this.type,
                this.cgiName)
                    this.getData(function(d, g, i) {
                        if (!f.dead && (1 == f.cgiCount && (f.onreset && f.onreset(),
                        c.html("")),
                        f.processData && f.processData.call(f, d, f.cgiCount),
                        !f.dead)) {
                            if (h[g])
                                ;
                            else {
                                var j = [];
                                if (a = function(a) {
                                    var b = a.name;
                                    j.push(b)
                                }
                                ,
                                0 === f.cgiCount || $.os.android ? f.useInfiniteScroller ? (e = Tmpl(f.renderTmpl, d.result || d, f.renderTool || {}),
                                InfiniteScrollList.render(e.toString(), f.cgiCount)) : (f.beforeRenderHtml && f.beforeRenderHtml(),
                                Tmpl(f.renderTmpl, d.result || d, f.renderTool || {}).appendTo(c),
                                f.afterRenderHtml && f.afterRenderHtml()) : f.useInfiniteScroller ? (e = Tmpl(f.renderTmpl, d.result || d, f.renderTool || {}),
                                InfiniteScrollList.render(e.toString(), f.cgiCount)) : (f.beforeRenderHtml && f.beforeRenderHtml(),
                                Tmpl(f.renderTmpl, d.result || d, f.renderTool || {}).appendTo(c),
                                f.afterRenderHtml && f.afterRenderHtml()),
                                j.length) {
                                    var k = ["CGI Field Missing ", j.join(", "), "cgi:" + f.cgiName, "request: " + JSON.stringify(i)].join("|");
                                    console.warn("CGI Field Missing", "\nnames:" + j.join(", "), "\ncgi: " + f.cgiName, "\nrequest:", i, "\nresponse", d),
                                    Badjs(k, window.location.href, "sodaRender")
                                }
                                a = null 
                            }
                            f.cgiCount > 0 && (f.scrollEnable = 1),
                            f.eventsBinded || (f.events && f.events(),
                            f.hasOwnProperty("eventsBinded") ? f.eventsBinded = 1 : f.__proto__.eventsBinded = 1),
                            f.feedPool.map(function(a) {
                                a.noFeed || 0 === f.cgiCount && a.noCache || (a.setFeedData(d, f.cgiCount),
                                a.rock())
                            }),
                            "function" == typeof f.complete ? f.complete && f.complete(d, f.cgiCount) : b.fence && f.complete && ("string" == typeof f.complete || f.complete.hasOwnProperty("length")) && b.runWorkflow(f.complete, {
                                data: d,
                                cgiCount: f.cgiCount
                            }),
                            f.isFirstDataRequestRender++,
                            delete h[g],
                            f.preloadNextData && f.cgiCount > 0 && "scrollModel" === f.type && setTimeout(function() {
                                console.log("prefeting"),
                                f.fetchNextData()
                            }, 0)
                        }
                    });
                else if (this.data ? "function" == typeof this.data && (this.data = this.data()) : this.data = {},
                this.data) {
                    if (this.dead)
                        return;
                    if (f.processData && f.processData.call(this, this.data, f.cgiCount),
                    f.dead)
                        return;
                    c.html("");
                    var i = [];
                    a = function(a) {
                        var b = a.name;
                        i.push(b)
                    }
                    ,
                    Tmpl(f.renderTmpl, this.data.result || this.data, f.renderTool).update(c);
                    try {
                        if (i.length) {
                            var j = ["CGI Field Missing, data given ", i.join(", "), "modelBlock:" + f.comment || f.renderContainer].join("|");
                            console.warn("CGI Field Missing, data given", "\nnames:" + i.join(", "), "\nmodelBlock: " + f.comment || f.renderContainer, "\ndata", this.data),
                            Badjs(j, window.location.href, "sodaRender")
                        }
                    } catch (k) {}
                    a = null ,
                    f.eventsBinded || (f.events && f.events(),
                    f.hasOwnProperty("eventsBinded") ? f.eventsBinded = 1 : f.__proto__.eventsBinded = 1),
                    f.scrollEnable = 1,
                    f.feedPool.map(function(a) {
                        a.noFeed || (a.setFeedData(this.data, f.cgiCount),
                        a.rock())
                    }),
                    "function" == typeof f.complete ? f.complete && f.complete(this.data, f.cgiCount) : b.fence && f.complete && ("string" == typeof f.complete || f.complete.hasOwnProperty("length")) && b.runWorkflow(f.complete, {
                        data: this.data,
                        cgiCount: f.cgiCount
                    }),
                    f.isFirstDataRequestRender++
                }
        },
        rock: function() {
            if (this._passedFirstRender && !window.fireDragon) {
                if (0 === this.cgiCount) {
                    var a = this.paramCache[this.cgiCount] || "object" == typeof this.param && this.param || this.param.call(this);
                    this.paramCache[this.cgiCount] = a
                }
                return this.cgiCount = 1,
                this.isFirstDataRequestRender = 1,
                this._passedFirstRender = 0,
                void (this.events && this.events())
            }
            this.render(this.renderContainer, 1)
        },
        processData: function() {},
        feed: function(a) {
            this.feedPool.push(a),
            a.feeded = 1
        },
        setFeedData: function(a, b) {
            this.data = a,
            this.cgiCount = b
        },
        update: function(a) {
            this.data = a,
            this.reset(),
            this.rock()
        },
        resetData: function() {
            this.dataCache = [],
            this.cgiCount = 0,
            this.onreset && this.onreset()
        },
        refresh: function() {
            this.noRefresh || (this.dataCache = [],
            this.reset(),
            this.rock())
        },
        hide: function() {
            var a = this.renderContainer;
            "string" == typeof a && (a = $(a)),
            a.hide()
        },
        show: function() {
            var a = this.renderContainer;
            "string" == typeof a && (a = $(a)),
            a.show()
        },
        extend: function(a) {
            a || (a = {});
            var b = function() {}
              , c = a.events;
            b.prototype = this;
            var d = new b;
            d.feedPool = [],
            d.cgiCount = 0,
            d.dataCache = [],
            d.isFirstDataRequestRender = 0,
            d.isFirstRender = 1,
            d._addedToModel = 0,
            d.canScrollInMTB = 1,
            d.dead = 0,
            a.param && (d.paramCache = []);
            for (var e in a)
                d[e] = a[e];
            return c && (d.events = function() {
                c && c.call(this)
            }
            ,
            d.eventsBinded = 0),
            d.renderContainer && (g[d.renderContainer] ? g[d.renderContainer]++ : g[d.renderContainer] = 1),
            d
        },
        freeze: function() {
            this.freezed = 1
        },
        melt: function() {
            this.freezed = 0
        },
        supportFireDragon: function() {
            this._passedFirstRender = 1
        }
    };
    var j = function(a) {
        var b = new i(a);
        b.scrollEnable = 1,
        b.__proto__.eventsBinded = 0,
        b.type = "scrollModel",
        b._scrollEl = a.scrollEl || ($.os.ios ? "#js_bar_main" : window),
        b._ctlByMutitab = 0;
        var c = b.events;
        return b.events = function() {
            c && c.call(this)
        }
        ,
        b.renderContent = function() {
            this.render(this.renderContainer, 1)
        }
        ,
        b.preloadNextData = a.preloadNextData || 0,
        b._addedToModel = 0,
        b.canScrollInMTB = 1,
        b.rock = function() {
            if (this._passedFirstRender && !window.fireDragon) {
                if (0 === this.cgiCount) {
                    var a = this.paramCache[this.cgiCount] || "object" == typeof this.param && this.param || this.param.call(this);
                    this.paramCache[this.cgiCount] = a
                }
                this.cgiCount = 1,
                this.isFirstDataRequestRender = 1,
                this._passedFirstRender = 0,
                this.events && this.events()
            } else
                this.render(this.renderContainer, 1);
            this._addedToModel || "scrollModel" !== this.type || (f.addModel(this),
            this._addedToModel = 1)
        }
        ,
        b
    }
      , k = function() {}
    ;
    k.prototype = {
        type: "abstractModel",
        hide: d,
        show: d,
        rock: function() {},
        reset: function() {},
        refresh: function() {},
        freeze: function() {},
        resetData: function() {}
    };
    var l = function(a) {
        this.cgiName = a.cgiName,
        this.comment = a.comment,
        this.param = a.param,
        this.ssoCmd = a.ssoCmd,
        this.processData = a.processData,
        this.complete = a.complete,
        this.error = a.error,
        this.renderContainer = a.renderContainer,
        this.beforeRequest = a.beforeRequest || function() {}
        ,
        this.paramCache = [],
        this.cgiCount = 0,
        this.dataCache = [],
        this.feedPool = [],
        this.isFirstRender = 1,
        this.eventsBinded = 0,
        this.isFirstDataRequestRender = 0
    }
    ;
    l.prototype = {
        getData: i.prototype.getData,
        info: i.prototype.info,
        noCache: 1,
        rock: function() {
            var a = this;
            this._args = arguments,
            this.getData(function(b) {
                a.processData && a.processData(b, a.cgiCount),
                a.complete && a.complete(b, a.cgiCount)
            })
        },
        reset: function() {
            this.dataCache = []
        },
        refresh: function() {
            this.dataCache = [],
            this.rock()
        },
        show: function() {},
        hide: function() {},
        extend: i.prototype.extend
    };
    var m = function() {
        this.pool = [],
        this.currModel = null ,
        this.eventBinded = 0
    }
    ;
    m.prototype = {
        tell: i.prototype.tell,
        info: i.prototype.info,
        watch: i.prototype.watch,
        supportFireDragon: function() {
            this._passedFirstRender = 1
        },
        _loadJs: function(a, b) {
            var c = this.pool[a][0]
              , d = this.pool[a][1]
              , e = "recieveModel" + c
              , f = "realizeModel" + c
              , g = this
              , h = function(c, d, f) {
                c && d && (g.pool[a] = [c, d, f],
                window[e] = null ,
                delete window[e],
                b && b())
            }
            ;
            window[f] ? window[f](h) : window[e] = h,
            loadjs.loadModule(d)
        },
        _bindSwitchEvent: function() {
            var a = this;
            this.eventBinded || ($("body").on("tap", function(b) {
                for (var c = $(b.target), d = g, e = [], f = [], h = 0; h < a.pool.length; h++) {
                    var i = a.pool[h][0]
                      , j = c.closest(i);
                    j && j.length && (e.push(a.pool[h]),
                    f.push(h))
                }
                if (e.length) {
                    var k = e[0]
                      , i = k[0]
                      , l = k[1]
                      , m = k[2];
                    if ("string" == typeof l) {
                        var n = "recieveModel" + i;
                        return window[n] = function(b, c, d) {
                            b && c && (a.pool[f[0]] = [b, c, d],
                            window[n] = null ,
                            delete window[n],
                            a.rock(b, {
                                name: "switch"
                            }))
                        }
                        ,
                        void loadjs.loadModule(l)
                    }
                    if (a.currModel = l,
                    l.canScrollInMTB = 1,
                    a.beforeTabHandler && a.beforeTabHandler.call(a, i, "switch"),
                    l.renderContainer ? d[l.renderContainer] > 1 ? (a.currModel.reset(),
                    a.currModel.rock(),
                    $(a.currModel.renderContainer)[0].scrollTop = 0) : l.isFirstDataRequestRender > 0 ? "scrollModel" === l.type : l.rock() : "pageModel" === l.type && a.currModel._switchedToPage(),
                    "linkModel" === a.currModel.type)
                        return l.rock(),
                        void (a.tabHander && a.tabHander.call(a, i, "switch"));
                    a.pool.map(function(a) {
                        var b = a[0];
                        a[1] !== l && "string" != typeof a[1] && (a[1].hide(),
                        a[1].canScrollInMTB = 0,
                        $(b).removeClass("active").removeClass("selected"))
                    }),
                    $(i).addClass("active").addClass("selected"),
                    l.show(),
                    console.log("tab切换" + i),
                    m && m.call(a, "switch"),
                    a.tabHander && a.tabHander.call(a, i, "switch")
                }
            }),
            this.eventBinded = 1)
        },
        stop: function() {
            this.currModel.stop && this.currModel.stop()
        },
        rock: function(a, b) {
            for (var c, d, e, f, g, h = this, i = 0; i < this.pool.length; i++)
                d = this.pool[i],
                e = d[0],
                f = d[1],
                g = d[2],
                a ? e === a && (c = [e, f, g, i]) : this.initTab ? e === this.initTab && (c = [e, f, g, i]) : 0 === i && (c = [e, f, g, i]);
            if (!c) {
                console.info("Model cannot init mutitab, check if selector exists!");
                var j = this.pool[0];
                if (!j)
                    return;
                c = [j[0], j[1], j[2], 0]
            }
            if (this._bindSwitchEvent(),
            e = c[0],
            f = c[1],
            g = c[2],
            i = c[3],
            "string" == typeof f)
                return void this._loadJs(i, function() {
                    h.rock(e),
                    f.scrollEnable = 0;
                    for (var a = 0; a < h.pool.length; a++)
                        "string" == typeof h.pool[a][1] && h._loadJs(a)
                });
            this.beforeTabHandler && this.beforeTabHandler.call(this, e, "switch"),
            f.canScrollInMTB = 1,
            this._passedFirstRender && (f.supportFireDragon(),
            this._passedFirstRender = 0),
            this.currModel = f;
            var k = b && b.name || "init";
            g && g.call(this, k),
            this.currModel.reset(),
            this.currModel.rock(),
            this.pool.map(function(a) {
                var b = a[1];
                b !== f && ("string" == typeof b || ($(a[0]).removeClass("active").removeClass("selected"),
                b.hide(),
                b.canScrollInMTB = 0))
            }),
            $(e).addClass("active").addClass("selected"),
            this.currModel.show(),
            this.scrollEnable = this.currModel.scrollEnable,
            this.tabHander && this.tabHander.call(this, e, k)
        },
        add: function(a, b, c) {
            b._modelSelector = a,
            this.pool.push([a, b, c]),
            b.controller = this,
            "string" == typeof b ? window.loadJsConfig && window.loadJsConfig.modules && (window.loadJsConfig.modules[b] || console.info("mutitab connot load lazymodel while " + b + " not exists in loadJsConfig modules!")) : b.scrollEnable = 0
        },
        beforetabswitch: function(a) {
            console.log("beforetabswitch"),
            this.beforeTabHandler = a
        },
        ontabswitch: function(a) {
            this.tabHander = a
        },
        switchTo: function(a) {
            if (console.log("switchTo"),
            $(a)[0])
                $(a).trigger("tap");
            else {
                var b;
                b = /^#/.test(a) ? $("<div style='display: none;' id='" + a.replace("#", "") + "'></div>") : $("<div style='display: none;' class='" + a.replace(".", "") + "'></div>"),
                $("body").append(b),
                b.trigger("tap")
            }
        },
        init: function(a) {
            this.initTab = a
        },
        freeze: function() {
            this.freezed = 1
        },
        melt: function() {
            this.freezed = 0
        },
        freezeCurrent: function() {
            this.currModel.freeze()
        },
        refresh: function() {
            this.currModel.refresh()
        },
        refreshList: function(a) {
            this.currModel._modelSelector === a ? this.currModel.refresh() : this.currModel.resetData()
        }
    };
    var n = function(a) {
        a && a.renderContainer && (this.renderContainer = a.renderContainer),
        this.models = []
    }
    ;
    n.prototype = {
        info: i.prototype.info,
        tell: i.prototype.tell,
        watch: i.prototype.watch,
        set canScrollInMTB(a) {
            this.models.map(function(b) {
                b.canScrollInMTB = a
            })
        },
        supportFireDragon: function() {
            this.models.map(function(a) {
                a.supportFireDragon()
            })
        },
        type: "pageModel",
        exportTab: i.prototype.exportTab,
        add: function(a) {
            this.models.push(a),
            a.controller = this
        },
        stop: function() {
            this.models.map(function(a) {
                a.stop && a.stop()
            })
        },
        remove: function(a) {
            var b;
            this.models.map(function(c, d) {
                c == a && (b = d,
                a.controller = null )
            }),
            "number" == typeof b && this.models.splice(b, 1)
        },
        _switchedToPage: function() {
            this.models.map(function(a) {
                "page" === a.type ? a._switchedToPage() : a.renderContainer && (g[a.renderContainer] > 1 ? (a.reset(),
                a.rock(),
                a.renderContainer && ($(a.renderContainer)[0].scrollTop = 0)) : a.isFirstDataRequestRender > 0 ? "scrollModel" === a.type : a.rock())
            })
        },
        rock: function() {
            this.models.map(function(a) {
                a.feeded || a.rock()
            })
        },
        extend: function() {
            var a = function() {}
            ;
            a.prototype = this;
            var b = new a;
            b.parent = this;
            for (var c in b)
                b[c].parent = parent[c];
            return b
        },
        reset: function() {
            this.models.map(function(a) {
                a.reset()
            })
        },
        refresh: function() {
            this.models.map(function(a) {
                a.refresh()
            })
        },
        hide: function() {
            this.renderContainer ? $(this.renderContainer).hide() : this.models.map(function(a) {
                a.hide()
            })
        },
        show: function() {
            this.renderContainer ? $(this.renderContainer).show() : this.models.map(function(a) {
                a.show()
            })
        }
    };
    var o = function(a) {
        this.param = a.param,
        this.url = a.url,
        this.newWindow = a.newWindow,
        this.popBack = a.popBack,
        this.checkBack = a.checkBack
    }
    ;
    o.prototype = {
        type: "linkModel",
        hide: d,
        show: d,
        canJump: !0,
        rock: function() {
            var a = ""
              , b = this.param
              , c = this;
            if ("function" == typeof this.param && (b = this.param.call(this)),
            this.popBack && mqq.ui.popBack(),
            b) {
                var d = [];
                for (var e in b)
                    d.push(e + "=" + (b[e] || ""));
                a = d.join("&")
            }
            var f;
            if (f = a ? this.url + "?" + a : this.url) {
                if (this.checkBack) {
                    var g = document.referrer;
                    if (g.indexOf(this.url) > -1)
                        return void history.back()
                }
                this.canJump && (this.newWindow ? Util.openUrl(f, !0) : Util.openUrl(f),
                this.canJump = !1,
                setTimeout(function() {
                    c.canJump = !0
                }, 1e3))
            }
        },
        reset: function() {}
    },
    window.renderModel = window.RenderModel = i,
    window.scrollModel = window.ScrollModel = j,
    window.linkModel = window.LinkModel = o,
    window.mutitabModel = window.MutitabModel = m,
    window.pageModel = window.PageModel = n,
    window.cgiModel = window.CgiModel = l,
    window.abstractModel = window.AbstractModel = k,
    window.Model = b,
    console.info("Model: Ready!")
}(),
"undefined" != typeof Model || (Model = {}),
Array.prototype.then = function(a) {}
,
Model.extend = function(a) {
    for (var b in a)
        a.hasOwnProperty(b) && (Model[b] = a[b])
}
,
Model.extend({
    fence: 1,
    tasks: {},
    serviceMap: {},
    importList: [],
    "import": function() {
        arguments
    },
    nameSpace: function(a) {
        this.currNameSpace = a,
        this.tasks[a] || (this.tasks[a] = {})
    },
    service: function(a, b) {
        a = a.trim(),
        this.serviceMap[a] = {
            func: b,
            serviceResult: null 
        }
    },
    task: function(a, b, c) {
        "function" == typeof b && (c = b,
        b = []),
        b.length && "function" == typeof b[b.length - 1] && (c = b,
        b = []);
        var d;
        if (this.currNameSpace) {
            d = this.tasks[this.currNameSpace];
            for (var e = 0; e < b.length; e++) {
                var f = b[e];
                f.indexOf(".") > -1 || (f = this.currNameSpace + "." + f),
                b[e] = f
            }
        } else
            d = this.tasks;
        d[a] = {
            taskModules: b,
            func: c
        }
    },
    _runFunc: function(a, b) {
        var c;
        if (a.hasOwnProperty("length") && a[0]) {
            var d = a.length
              , e = a.splice(d - 1, 1);
            c = a,
            a = e[0]
        } else {
            var f = /^function\s*[^\(]*\(\s*([^\)]*)\)/m
              , g = /((\/\/.*$)|(\/\*[\s\S]*?\*\/))/gm
              , h = a.toString().replace(g, "")
              , i = h.match(f);
            c = i[1] && i[1].split(",") || []
        }
        for (var j = [], k = this, l = function(a) {
            if (a = a.trim(),
            "scope" === a)
                return b;
            var c = k.serviceMap[a]
              , d = c.func;
            if ("undefined" == typeof d)
                return console.error("Model: " + a + " is not defined"),
                function() {}
                ;
            if (c.serviceResult)
                ;
            else {
                var e = k._runFunc(d);
                c.serviceResult = e
            }
            return c.serviceResult
        }
        , m = 0; m < c.length; m++)
            j.push(l(c[m]));
        var b = {};
        return a.apply(b, j)
    },
    runWorkflow: function(a, b) {
        var c, d;
        if ("string" == typeof a) {
            var e, f = a;
            e = /\./.test(f) ? f.split(".") : [f];
            var g = this
              , h = function(a) {
                for (var b = g.tasks, c = 0; c < a.length; c++) {
                    var d = a[c];
                    if (!b[d])
                        return;
                    b = b[d]
                }
                return b
            }
              , i = h(e);
            i && (c = i.taskModules,
            d = i.func)
        } else
            a.hasOwnProperty("length") && (c = a,
            d = ["scope", function(a) {
                return a
            }
            ]);
        for (var j = b || {}, k = 0; k < c.length; k++) {
            var l = c[k]
              , m = this.runWorkflow(l, j);
            for (var n in m)
                m.hasOwnProperty(n) && (j[n] = m[n])
        }
        return d ? this._runFunc(d, j) : j
    }
}),
"undefined" != typeof require && (module.exports = Model);