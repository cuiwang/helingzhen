!
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_actionsheet = b()
} (this,
function() {
    var a = {},
    b = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("title"),
        e = b("items"),
        f = "";
        f += " ",
        d && (f += ' <div class="sheet-item sheet-title">', f += c(d), f += "</div> "),
        f += " ";
        for (var g = 0; g < e.length; g++) f += ' <div class="sheet-item ',
        f += c(e[g].type || ""),
        f += '" value="',
        f += c(g),
        f += '"> <div class="sheet-item-text" >',
        f += c(e[g].text || e[g]),
        f += "</div> </div> ";
        return f += ' <div class="sheet-item" value="-1" > <div class="sheet-item-text">取消</div> </div> '
    };
    return a.frame = "TmplInline_actionsheet.frame",
    Tmpl.addTmpl(a.frame, b),
    a
}),
function(a, b) {
    a.ActionSheet = b(a.$, a.Tmpl)
} (this,
function(a, b) {
    function c() {
        i = a('<div class="action-sheet"></div>'),
        h = a('<div class="action-sheet-mask"></div>'),
        a(document.body).append(i).append(h),
        h.on("tap",
        function() {
            f()
        }).on("webkitTransitionEnd",
        function() {
            h.hasClass("show") || h.hide()
        }),
        i.on("tap", ".sheet-item", e).on("webkitTransitionEnd",
        function() {
            i.hasClass("show") || (h.removeClass("show"), i.hide())
        }),
        k = !0
    }
    function d(a) {
        mqq.compare("4.7") >= 0 && !a.useH5 ? mqq.ui.showActionSheet({
            items: a.items,
            cancel: "取消"
        },
        function(b, c) {
            0 === b ? a.onItemClick && a.onItemClick(c) : a.onCancel && a.onCancel(c)
        }) : (k || c(), j = a, document.body.style.overflow = "hidden", i.html(""), l.on("touchmove", g), b(window.TmplInline_actionsheet.frame, a).appendTo(i), h.show(), i.show(), setTimeout(function() {
            h.addClass("show"),
            i.addClass("show")
        },
        50))
    }
    function e() {
        var b = a(this).attr("value");
        b = Number(b),
        -1 === b ? j.onCancel && j.onCancel() : j.onItemClick && j.onItemClick(b),
        f()
    }
    function f(a) {
        i && (a ? (h.hide().removeClass("show"), i.hide().removeClass("show")) : i.removeClass("show"), l.off("touchmove", g), document.body.style.overflow = "")
    }
    function g(a) {
        a.preventDefault()
    }
    var h, i, j, k, l = a(document);
    return {
        show: d,
        hide: f
    }
}),
function(a, b) {
    a.SubStr = b(a.$)
} (this,
function() {
    return {
        substr2: function(a, b, c) {
            var d = 0,
            e = 0,
            f = 0;
            for (f = 0; f < a.length; f++) if (e += a.charCodeAt(f) > 255 ? 3 : 1, b >= e) d++;
            else if (e > b + c) break;
            return a.substr(d, f)
        },
        size: function(a) {
            return a.replace(/[^\u0000-\u007F]/gim, "...").length
        }
    }
}),
function() {
    window.honourHelper = {
        renderHonours: function(a) {
            var b, c, d = a.admin_ext,
            e = a["continue"];
            return 8 === (8 & d) ? (c = a.title || "大明星", b = '<span class="honour border-1px vip">' + c + "</span>") : 2 === (2 & d) ? b = '<span class="honour border-1px admin">大酋长</span>': 4 === (4 & d) ? b = '<span class="honour border-1px admin">小酋长</span>': 64 === (64 & d) ? (c = a.flag || "达人", b = '<span class="honour border-1px expert">' + c + "</span>") : e >= 7 && (b = '<span class="honour border-1px">铁杆粉</span>'),
            b
        },
        renderPoster: function(a, b) {
            var c, d = arguments.length;
            return 1 === d ? c = !!a: 2 === d && (c = a && b ? a === b: !1),
            c ? '<span class="honour border-1px poster">楼主</span>': ""
        },
        isAdmin: function(a) {
            return 1 === (1 & a) || 2 === (2 & a) || 4 === (4 & a) || 32 === (32 & a)
        }
    }
} (),
function(a) {
    a.smallSlider = {};
    var b, c, d = .15,
    e = null,
    f = 0,
    g = !0,
    h = document.documentElement.clientWidth || document.body.clientWidth,
    i = function(a) {
        if (null === e) {
            if (!g) return void setTimeout(function() {
                g = !0
            },
            330);
            var b = a.touches[0];
            e = b.clientX,
            c.css("-webkit-transition", "none")
        }
    },
    j = function() {
        if (event.preventDefault(), null !== e) {
            var a = event.touches[0],
            b = Number(c.attr("data-offset")) || 0;
            f = (e - a.clientX) / h;
            var d = 120 * -f;
            d += b,
            f > 0 ? c.css("-webkit-transform", "translate3d(" + d + "px,0,0)") : c.css("-webkit-transform", "translate3d(" + d + "px,0,0)")
        }
    },
    k = function() {
        var a;
        if (e = null, g = !1, c.css("-webkit-transition", "330ms"), f >= d) {
            a = Number(c.attr("data-offset")) || 0;
            var b = -h;
            b += a,
            c.attr("data-offset", b),
            c.css("-webkit-transform", "translate3d(" + b + "px,0,0)")
        } else if (Math.abs(f) >= d) {
            a = Number(c.attr("data-offset")) || 0;
            var i = h;
            i += a,
            c.attr("data-offset", i),
            c.css("-webkit-transform", "translate3d(" + i + "px,0,0)")
        } else a = Number(c.attr("data-offset")) || 0,
        c.css("-webkit-transform", "translate3d(" + a + "px,0,0)");
        f = 0
    };
    window.smallSlider.init = function(a) {
        var d = a.images,
        e = new Image;
        h = a.width || h,
        e.src = d[0],
        e.onload = function(e) {
            var f = e.target.height / e.target.width;
            b = $(a.container),
            b.css({
                position: "relative",
                paddingTop: 100 * f + "%",
                overflow: "hidden"
            }),
            c = $("<ul data-offset='-" + (1 === d.length ? 0 : h) + "' style='position: absolute; left:0; top:0;-webkit-transform: translate3d(-" + (1 === d.length ? 0 : h) + "px, 0px, 0px);-webkit-transition:330ms;width:22222px;'></ul>");
            for (var l = "style='width:" + h + "px;float:left;'",
            m = 0; m < d.length; m++) {
                var n = $("<li " + l + "><img style='width:100%;' src='" + d[m] + "' data-index='" + (m + 1) + "'/></li>");
                c.append(n)
            }
            1 !== d.length && (c.append($("<li " + l + "><img style='width:100%;' src='" + d[0] + "' data-index='1'/></li>")), c.prepend($("<li " + l + "><img style='width:100%;' src='" + d[d.length - 1] + "' data-index='" + d.length + "'/></li>")), c.on({
                touchstart: i,
                touchmove: j,
                touchend: k,
                touchcancel: k
            }), c.on("transitionend webkitTransitionEnd",
            function(b) {
                var e, f = h,
                i = Number($(b.target).attr("data-offset"));
                0 === i && (e = -(i + d.length * f), c.css("-webkit-transition", "none").css("-webkit-transform", "translate3d(" + e + "px,0,0)"), c.attr("data-offset", e)),
                i === -((d.length + 1) * f) && (e = i + d.length * f, c.css("-webkit-transition", "none").css("-webkit-transform", "translate3d(" + e + "px,0,0)"), c.attr("data-offset", e));
                var j = function(b) {
                    a.onImgIntoScreen && a.onImgIntoScreen(Math.abs(b / f))
                };
                j(e || i),
                g = !0
            })),
            c.find("img").on("tap",
            function(b) {
                a.onImgClick && a.onImgClick($(b.target).attr("data-index"))
            }),
            b.append(c)
        }
    }
} (window),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_barindex = b()
} (this,
function() {
    var a = {},
    b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var e = c("cover"),
        f = c("pic"),
        g = c("Util"),
        h = c("barId"),
        i = c("bar_class"),
        j = c("isStarCategory"),
        k = c("star_charm_rank"),
        l = c("category_name"),
        m = c("category_rank"),
        n = c("name"),
        o = c("barTypeArr"),
        p = c("isWeiXin"),
        q = c("today_sign"),
        r = c("fans"),
        s = c("star_charm_count"),
        t = c("pids"),
        u = c("exsit"),
        v = c("intro"),
        w = c("vote_total"),
        x = "";
        if (x += '<div class="header-cover-img" style="background-image: url(', x += d(e), x += ');"></div> <div class="header" > <div class="cover mask-gray"></div> <div class="info" id="js_bar_info"> <div class="logo-container"> <img class="logo" src="', x += d(f), x += '" /> </div> ', g.isFestival(h)) x += ' <div class="festival-bar-top ',
        x += d(0 == w ? "novote": ""),
        x += '"> <div class="name-info"> <div class="labels"> <span class="name">',
        x += d(n),
        x += '</span> <div class="bar-detail"> <div class="bar-single-detail"> <div class="bar-focus-num">',
        x += d(b.numHelper(r) || 0),
        x += '</div> <div class="bar-focus-text">关注</div> </div> ',
        0 != w && (x += ' <div class="bar-single-detail"> <div class="bar-vote-num">6.7亿</div> <div class="bar-vote-text">投票</div> </div> '),
        x += ' </div> </div> </div> <button id="festival-bar-focus" class="focus-btn ',
        x += d(2 == u ? "": "has-focus"),
        x += '">',
        x += d(2 == u ? "关注": "已关注"),
        x += "</button> </div> ";
        else {
            x += " ",
            88 != i && (x += " ", j ? (x += " ", 50 >= k ? (x += ' <div class="logo-rank"> <span>魅力榜:No.', x += d(k), x += "</span> </div> ") : x += ' <div class="logo-rank not-in-charm-rank"> <span >未上TOP50</span> </div> ', x += " ") : (x += ' <div class="logo-rank"> <span>', x += d(l), x += "类:No.", x += d(m), x += "</span> </div> "), x += " "),
            x += ' <div class="name-info"> <div class="labels"> <span class="name">',
            x += d(n),
            x += "</span> ";
            for (var y = 0; y < o.length && 3 > y; y++) x += ' <label class="',
            x += d(o[y].type),
            x += '">',
            x += d(o[y].value),
            x += "</label> ";
            x += ' </div> <div class="info-num" id="js_bar_info_num"> ',
            j ? (x += " ", p ? (x += ' <label>今日签到 </label><span id="js_bar_sign_count" num="', x += d(q), x += '">', x += d(b.numHelper(q) || 0), x += "</span> ") : (x += ' <label>关注 </label><span id="js_bar_vote_count" num="', x += d(r), x += '">', x += d(b.numHelper(r) || 0), x += "</span> "), x += ' <span id="charm_count_wrap" class="charm-wrapper"><label>魅力 </label><span id="js_bar_charm_count" num="', x += d(s), x += '">', x += d(s || 0), x += '</span></span> <span class="charm-add-animation">+5</span> ') : (x += ' <label>话题 </label><span id="js_bar_pids_count" num="', x += d(t), x += '">', x += d(b.numHelper(t) || 0), x += "</span> ", p ? (x += ' <label>今日签到 </label><span id="js_bar_sign_count" num="', x += d(q), x += '">', x += d(b.numHelper(q) || 0), x += "</span> ") : (x += ' <label>关注 </label><span id="js_bar_vote_count" num="', x += d(r), x += '">', x += d(b.numHelper(r) || 0), x += "</span> "), x += " "),
            x += " </div> ",
            2 != u || p ? (x += ' <div class="bar-info-text" style="display: none;">', x += d(v), x += "</div> ") : (x += ' <div class="bar-info-text">', x += d(v), x += "</div> "),
            x += ' </div> <div class="sign" id="signArea"> </div> ',
            x += 2 != u || p ? ' <div class="op" id="opArea" style="display: none;"> ': ' <div class="op" id="opArea" style="display: block;"> ',
            x += ' <a class="vote-btn btn" id="js_bar_vote_btn" href="javascript:void(0)"> <i class="vote-btn-icon"></i> 关注 </a> </div> '
        }
        return x += " </div> </div> "
    };
    a.bar_basic = "TmplInline_barindex.bar_basic",
    Tmpl.addTmpl(a.bar_basic, b);
    var c = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("url"),
        e = "";
        return e += '<iframe id="iframe" src="',
        e += c(d),
        e += '" style="border: none; width: 100%; height:100%; margin-bottom: 20px;"></iframe> '
    };
    a.bar_iframe_cup = "TmplInline_barindex.bar_iframe_cup",
    Tmpl.addTmpl(a.bar_iframe_cup, c);
    var d = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("posts"),
        e = b("rich2plain"),
        f = b("admin"),
        g = "";
        g += "";
        for (var h = 0; h < d.length; h++) {
            g += " ";
            var i = d[h].pid;
            window.postsRecorder[i] || (window.postsRecorder[i] = 1, g += ' <li class="section-1px" id="', g += c(d[h].pid), g += '" pid="', g += c(d[h].pid), g += '"> <h3> ', 3 == d[h].type ? g += ' <label class="cls">原创</label> ': 4 == d[h].type && (g += ' <label class="cls">招募</label> '), g += " ", "undefined" != typeof d[h].best && 1 == d[h].best && (g += ' <label class="best">精</label> '), g += " ", g += c(e(d[h].title)), g += " </h3> ", d[h].post.pic_list && d[h].post.pic_list.length && (g += ' <div class="img"> <img class="pre_load" lazy-src="', g += c(d[h].post.pic_list[0].url + "640"), g += '" style="margin-top:', g += c(d[h].post.pic_list[0].marginTop), g += '" height="', g += c(d[h].post.pic_list[0].h), g += '"/> ', d[h].post.pic_list.length > 1 && (g += '<span class="num">', g += c(d[h].post.pic_list.length), g += "</span>"), g += " </div> "), g += " <p>", g += c(e(d[h].post.content)), g += '</p> <div class="info ', g += c(d[h].post.pic_list.length ? "": "noimg"), g += '"> <span class="nick"> ', (d[h].uin + "").indexOf("*") > -1 ? (g += " ", g += c(d[h].user.nick_name), g += "(", g += c(d[h].uin), g += ") ") : (g += " ", g += c(d[h].user.nick_name), g += " "), g += " ", "undefined" == typeof d[h].addr || "undefined" == typeof d[h].addr.province && "undefined" == typeof d[h].addr.city || (g += ' <span class="split">|</span> ', "undefined" != typeof d[h].addr.city ? (g += " ", g += c(d[h].addr.city), g += " ") : "undefined" != typeof d[h].addr.province && (g += " ", g += c(d[h].addr.province), g += " "), g += " "), g += ' </span> <span class="reply-num">', g += c(d[h].total_comment), g += '</span> <span class="reply-icon"></span> <span class="time">', g += c(d[h].time_formate), g += "</span> </div> ", f && (g += ' <div class="icons"> <a class="delete delete_post" data-pid="', g += c(d[h].pid), g += '" href="javascript:void(0)" title="删除"></a> </div> '), g += " </li> ")
        }
        return g += " "
    };
    a.bar_list = "TmplInline_barindex.bar_list",
    Tmpl.addTmpl(a.bar_list, d);
    var e = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("posts"),
        e = b("Date"),
        f = "";
        f += "";
        for (var g, h = 0; h < d.length; h++) {
            g = d[h];
            var i = g.pid;
            if (!window.postsRecorder[i]) {
                window.postsRecorder[i] = 1;
                var j = (g.post.pic_list, +new e),
                k = new e(g.post.start);
                f += ' <li class="section-1px act-mini" id="',
                f += c(g.pid),
                f += '" pid="',
                f += c(g.pid),
                f += '" type="',
                f += c(g.type),
                f += '" ',
                f += c("openact" === g.post.from ? 'openactid="' + g.post.openact_id + '"': ""),
                f += "> ",
                100 == g.type && g.post.purchase_link ? (f += ' <div class="act-img-wrapper"> <img class="act-img" src="', f += c(g.post.pic_list && g.post.pic_list[0].url), f += '" /> </div> <div class="act-info music-ticket"> <h3 class="text act"> <div class="post-tags"> <label class="act">活动</label> ', "undefined" != typeof g.best && 1 == g.best && (f += ' <label class="best">精</label> '), f += " ", g.isTop && (f += ' <label class="rec">顶</label> '), f += " </div> ", f += c(g.title), f += ' </h3> <div class="act-comm-wrap"> <div class="act-comm">时间: ', f += c(g.post.time), f += '</div> <div class="act-comm act-address">地点: ', f += c(g.post.addr), f += '</div> </div> <div class="price">', f += c(g.post.price), f += '</div> <div class="purchase-button" data-src="', f += c(g.post.purchase_link), f += '">我要购票</div> </div> ') : 100 == g.type && (f += ' <div class="act-img-wrapper"> <img class="act-img" src="', f += c(g.post.pic_list && g.post.pic_list[0].url), f += '" /> </div> <div class="act-info"> <h3 class="text act"> ', (1 == g.best || g.isTop) && (f += ' <div class="post-tags"> ', 1 == g.best && (f += ' <label class="best">精</label> '), f += " ", g.isTop && (f += ' <label class="rec">顶</label> '), f += " </div> "), f += c(g.title), f += ' </h3> <div class="act-comm-wrap"> <div class="single-ellipsis">', f += c(g.post.addr), f += '</div> <div class="time">', f += c(["周" + ["日", "一", "二", "三", "四", "五", "六"][k.getDay()], [k.getMonth() + 1 + "/" + k.getDate()], (k.getHours() < 10 ? "0": "") + k.getHours() + ":" + (k.getMinutes() < 10 ? "0": "") + k.getMinutes()].join("&nbsp;")), f += '</div> <div class="status">', j > g.post.end ? f += '<span class="tag-expire"></span>': (f += '<span class="people-num">', f += c(g.subscribers), f += "</span>人已报名"), f += "</div> </div> </div> "),
                f += " </li> "
            }
        }
        return f += " "
    };
    a.bar_list_tab_act = "TmplInline_barindex.bar_list_tab_act",
    Tmpl.addTmpl(a.bar_list_tab_act, e);
    var f = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var e = c("admin_ext"),
        f = c("posts"),
        g = c("isMenu"),
        h = c("Date"),
        i = c("cur_time"),
        j = (c("list"), c("Util")),
        k = (c("g"), c("_pzan")),
        l = c("_pcai"),
        m = c("Number"),
        n = c("_cai"),
        o = (c("tms"), c("imgHandle")),
        p = (c("gm"), c("formatDuration")),
        q = c("rich2plain"),
        r = c("useStaticData"),
        s = c("isNaN"),
        t = c("plain2rich"),
        u = c("gameAct"),
        v = c("parseInt"),
        w = "";
        w += "";
        var x;
        if (b && b.isAdmin) var y = b.isAdmin(e);
        for (var z = 360,
        A = 0; A < f.length; A++) {
            x = f[A];
            var B = x.pid;
            if (!x.isMock && !g) {
                if (window.postsRecorder[B]) continue;
                window.postsRecorder[B] = 1
            }
            var C = x.post.pic_list,
            D = ( + new h, x.pid.split("-")[1]),
            E = z > (i - D) / 60;
            if (w += " ", x.ad) w += ' <li class="section-1px gdt-ad"> </li> ';
            else {
                w += ' <li bid="',
                w += d(x.bid),
                w += '" class="section-1px ',
                w += d(x.isMock ? "uploading-video": ""),
                w += '" id="',
                w += d(x.pid),
                w += '" pid="',
                w += d(x.pid),
                w += '" ',
                E && (w += 'data-new="1"'),
                w += ' type="',
                w += d(x.type),
                w += '" mock = "',
                w += d(x.isMock),
                w += '" ',
                w += d("openact" === x.post.from ? 'openactid="' + x.post.openact_id + '"': ""),
                w += "> ";
                var F = x.post.audio_list && x.post.audio_list.length ? "audio-img": x.post.qqmusic_list && x.post.qqmusic_list.length ? "music-img": 200 == x.type || 201 == x.type || x.post.video_list && x.post.video_list.length ? "video-img": "";
                if (500 == x.type) {
                    var G = x.content ? x.content: x.post,
                    H = j.isBeforeFestival() ? "投票": "节目";
                    G.content = G.content || "";
                    var I = "#tab_all" === window._s_selector || !window._s_selector;
                    if (w += ' <div class="festival-menu-list"> <div class="festival-title-container"> ', x.playing && (w += ' <span class="festival-live-icon">live</span> '), w += ' <span class="festival-show-icon">', I ? w += d(H) : (w += '<span class="festival-no">', w += d(x.seq_no), w += "</span>"), w += '</span> <span class="festival-title">', w += d(x.title), w += '</span> </div> <div class="festival-content">', w += d(G.content.replace(/<br>/g, "")), w += '</div> <div class="festival-comment">', w += 1 == G.like_state ? d(1 == x.vote_self ? "我已投票": 2 == x.vote_self ? "我已投票": "正在投票") : 0 == G.like_state ? "投票未开始": "投票已结束", w += "</div> ", 0 == G.like_state) k = "0.00%",
                    l = "0.00%";
                    else {
                        var J = m(x.zan) || 0,
                        n = m(x.cai) || 0,
                        K = J + n === 0 ? 1 : J + n;
                        k = (J / K * 1e3).toFixed(0),
                        0 === J && 0 === n ? (k = "0.00%", l = "0.00%") : (l = 1e3 - k, k = b._zeroMaker(k / 10), l = b._zeroMaker(l / 10)),
                        w += " "
                    }
                    w += ' <div class="festival-like-container"> <div class="festival-likes"> <i></i><span>',
                    w += d(k),
                    w += '</span> </div> <div class="festival-unlikes"> <i></i><span>',
                    w += d(l),
                    w += "</span> </div> </div> </div> "
                } else if (100 != x.type && 400 != x.type && 401 != x.type && 600 !== x.type) {
                    if (w += ' <div class="detail-text-content ', w += d(C && C.length || "video-img" == F || "music-img" == F || "audio-img" == F ? "haspic": "nopic"), w += '"> ', C && C.length) {
                        "object" == typeof C[0] && (C = o.formatThumb(x.post.pic_list, !0, 90, 90, !0)),
                        302 === x.type && (C[0].url = C[0].url.replace(/&quot;/gm, "").replace(/"{2,}/gm, '"'));
                        var L = C[0];
                        w += ' <div class="act-img img-gallary ',
                        w += d(F),
                        w += '"> ',
                        L.url ? (w += ' <img needHideBg="true" lazy-src="', w += d(o.getThumbUrl(L)), w += '" style="width:', w += d(L.width), w += "px; height:", w += d(L.height), w += "px; margin-left:", w += d(L.marginLeft), w += "px; margin-top:", w += d(L.marginTop), w += 'px;" /> ') : "string" == typeof L && (w += ' <img lazy-src="', w += d(o.getThumbUrl(L)), w += '" style="width:90px;height:90px;" /> '),
                        w += " ",
                        C.length > 1 && (w += ' <div class="pic-count-tips"> ', w += d(C.length), w += "图 </div> "),
                        w += " </div> "
                    } else {
                        var M = "";
                        if ("video-img" == F && (x.post.image1 || (x.post.image1 = x.post.image2), M = x.post.image1 ? o.getThumbUrl(x.post.image1, 200) : "http://shp.qpic.cn/qqvideo/0/" + x.post.video_list[0].vid + "/128"), "music-img" == F && (M = x.post.qqmusic_list[0].image_url), "audio-img" == F && (M = "http://q.qlogo.cn/g?b=qq&nk=" + x.uin + "&s=140"), M) {
                            if (w += ' <div class="act-img img-gallary ', w += d(F), w += '"> <img lazy-src="', w += d(M), w += '" style="width:90px;height:90px;" data-nosize="1" /> ', "audio-img" == F) {
                                var N = p(x.post.audio_list[0].duration || 0);
                                w += ' <div class="img-mask ',
                                w += d(F),
                                w += '"> <div class="audio-center-icon"></div> <div class="audio-duration ',
                                w += d(1 == x.user.gender ? "male": "female"),
                                w += '"> ',
                                w += d(N),
                                w += ' <i class="audio-gender-icon"></i> </div> </div> '
                            }
                            w += " ",
                            ("video-img" == F || "music-img" == F) && (w += ' <div class="img-mask ', w += d(F), w += '"> <i class="img-type-icon"></i> </div> '),
                            w += " </div> "
                        }
                        w += " "
                    }
                    w += ' <div class="text-container"> <h3 class="text"> <div id="post_tags_',
                    w += d(x.pid),
                    w += '" class="post-tags">',
                    "undefined" != typeof x.best && 1 == x.best ? w += '<label class="best">精</label>': E && (w += '<label class="new">新</label>'),
                    w += "</div>",
                    w += d(x.title),
                    w += ' </h3> <div class="list-content ">',
                    w += d(q(x.post.content, x.post.urlInfo)),
                    w += '</div> </div> <div class="info ',
                    w += d(C && C.length ? "": "noimg"),
                    w += '"> ',
                    x.user && (w += ' <div> <span class="nick" > <span class="', w += d((C && C.length || M) && (x.user.admin_ext > 0 || x.user["continue"] >= 7) ? "single-ellipsis": "single-ellipsis-long"), w += d(x.user.vipno ? " nick-vipno": ""), w += '"> ', (x.uin + "").indexOf("*") > -1 ? (w += " ", w += d(x.user.nick_name), w += "(", w += d(x.uin), w += ") ") : (w += " ", w += d(x.user.nick_name), w += " "), w += ' </span> <span class="ver-middle"> ', w += d(b.renderHonours(x.user)), w += " </span> </span> </div> "),
                    w += " ",
                    r || (w += ' <div class="fl-right"> <i class="read-icon"></i> <span class="read-num-text">', w += d(x.readnum || 0), w += '</span> <i class="reply-icon"></i> <span class="reply-num-text" style="margin-right: 0;">', w += d(x.total_comment_v2 || x.total_comment), w += "</span> </div> "),
                    w += " </div> </div> ",
                    y && (w += ' <div class="icons"> <a class="delete delete_post" data-pid="', w += d(x.pid), w += '" data-bid="', w += d(x.bid), w += '" href="javascript:void(0)" title="删除"></a> </div> '),
                    w += " "
                } else if (100 == x.type && x.post.purchase_link) {
                    if (w += " ", C && C.length) {
                        C = o.formatThumb(x.post.pic_list, !0, 90, 90);
                        var L = C[0]
                    }
                    if (w += ' <div class="act-img img-gallary activity"> ', L.url && (w += ' <img lazy-src="', w += d(o.getThumbUrl(L)), w += '" style="width:', w += d(L.width), w += "px; height:", w += d(L.height), w += "px; margin-left:", w += d(L.marginLeft), w += "px; margin-top:", w += d(L.marginTop), w += 'px;" /> '), w += ' </div> <div class="act-info music-ticket"> <h3 class="text act"> <div class="post-tags"> <label class="act">活动</label> ', "undefined" != typeof x.best && 1 == x.best && (w += ' <label class="best">精</label> '), w += " ", x.isTop && (w += ' <label class="rec">顶</label> '), w += " </div> ", w += d(x.title), w += ' </h3> <div class="act-comm-wrap"> <div class="act-comm">时间: ', w += d(x.post.time), w += '</div> <div class="act-comm act-address">地点: ', w += d(x.post.addr), w += "</div> ", x.post.end && x.post.start) {
                        var O = h.now(),
                        P = O > x.post.end ? "已结束": O < x.post.start ? "未开始": "进行中";
                        w += ' <div class="act-comm">状态: ',
                        w += d(P),
                        w += "</div> "
                    }
                    w += ' </div> <div class="price" style="display:none;">',
                    w += d(x.post.price),
                    w += '</div> <div class="purchase-button" data-src="',
                    w += d(x.post.purchase_link),
                    w += '" style="display:none;">我要购票</div> </div> '
                } else if (400 == x.type) {
                    var Q = (window.innerWidth - 32) / 3,
                    C = o.formatThumb(x.post.pic_list, !0, Q, Q) || [];
                    w += ' <h3 class="text pho-list"> <div id="post_tags_',
                    w += d(x.pid),
                    w += '" class="post-tags">',
                    "undefined" != typeof x.best && 1 == x.best ? w += '<label class="best">精</label>': E && (w += '<label class="new">新</label>'),
                    w += "</div>",
                    w += d(x.title),
                    w += ' </h3> <div class="pho-list-container"> ';
                    for (var R = 0; 3 > R; R++) {
                        var L = C[R];
                        if (w += " ", L && L.url) {
                            w += ' <div class="act-img img-gallary" style="width:',
                            w += d(Q),
                            w += "px;height:",
                            w += d(Q),
                            w += 'px;"> <img lazy-src="',
                            w += d(o.getThumbUrl(L)),
                            w += '" style="width:',
                            w += d(L.width),
                            w += "px; height:",
                            w += d(L.height),
                            w += "px; margin-left:",
                            w += d(L.marginLeft),
                            w += "px; margin-top:",
                            w += d(L.marginTop),
                            w += 'px;" /> ';
                            var S = m(x.share || 0);
                            2 == R && (S > 3 || s(S)) && (w += ' <div class="share-count-tips"> ', w += d(x.share), w += "张晒图 </div> "),
                            w += " </div> "
                        }
                        w += " "
                    }
                    w += " </div> "
                } else if (401 == x.type) {
                    var Q = (window.innerWidth - 32) / 3,
                    T = x.hotest_posts || [];
                    if (w += ' <h3 class="text pho-list"> <div id="post_tags_', w += d(x.pid), w += '" class="post-tags"> ', "undefined" != typeof x.best && 1 == x.best ? w += ' <label class="best">精</label> ': E && (w += ' <label class="new">新</label> '), w += " </div> ", w += d(x.title), w += ' </h3> <div class="pho-list-container"> ', T && T.length) {
                        for (var R = 0; 3 > R; R++) if (T[R] && T[R].post) {
                            var U = T[R].title,
                            V = T[R].post.content,
                            C = T[R].post.pic_list,
                            W = !1;
                            C && C.length && (W = !0);
                            var C = o.formatThumb(C, !0, Q, Q) || [];
                            w += ' <div class="article-item ',
                            w += d(W ? "article-item-img": "article-item-color"),
                            w += '" style="width:',
                            w += d(Q),
                            w += "px;height:",
                            w += d(Q),
                            w += 'px;"> ',
                            W && (w += ' <img class="topic-img" lazy-src="', w += d(o.getThumbUrl(C[0].url + "512")), w += '" width="', w += d(C[0].width), w += '" height="', w += d(C[0].height), w += '"> '),
                            w += ' <div class="article-wrapper ',
                            w += d(W ? "article-img": "article-color"),
                            w += " ",
                            w += d(W ? "": "article-color-" + R),
                            w += '"> <div class="article-word-wrapper"> <div class="article-title">',
                            w += d(t(U)),
                            w += '</div> <div class="article-content">',
                            w += d(t(V)),
                            w += "</div> </div> </div> ";
                            var S = m(x.share || 0);
                            2 == R && (S > 3 || s(S)) && (w += ' <div class="share-count-tips"> ', w += d(x.share), w += "个话题 </div> "),
                            w += " </div> "
                        }
                    } else {
                        var L = x.post.pic_list[0];
                        w += ' <div class="act-img img-gallary" style="width:',
                        w += d(Q),
                        w += "px;height:",
                        w += d(Q),
                        w += 'px;"> <img lazy-src="',
                        w += d(o.getThumbUrl(L)),
                        w += '" style="width:',
                        w += d(L.width),
                        w += "px; height:",
                        w += d(L.height),
                        w += "px; margin-left:",
                        w += d(L.marginLeft),
                        w += "px; margin-top:",
                        w += d(L.marginTop),
                        w += 'px;" /> </div> '
                    }
                    w += " </div> "
                } else if (100 == x.type) {
                    if (w += " ", C && C.length) {
                        C = o.formatThumb(x.post.pic_list, !0, 90, 90);
                        var L = C[0]
                    }
                    w += ' <div class="act-img img-gallary activity"> ',
                    L.url && (w += ' <img lazy-src="', w += d(o.getThumbUrl(L)), w += '" style="width:', w += d(L.width), w += "px; height:", w += d(L.height), w += "px; margin-left:", w += d(L.marginLeft), w += "px; margin-top:", w += d(L.marginTop), w += 'px;" /> '),
                    w += ' </div> <div class="act-info"> <h3 class="text act"> <div class="post-tags"> <label class="act">活动</label> ',
                    "undefined" != typeof x.best && 1 == x.best && (w += ' <label class="best">精</label> '),
                    w += " ",
                    x.isTop && (w += ' <label class="rec">顶</label> '),
                    w += " </div> ",
                    w += d(x.title),
                    w += ' </h3> <div class="act-comm-wrap activity-info-wrap"> <div class="">时间：',
                    w += d(x.post.time),
                    w += '</div> <div class="single-ellipsis">地点：',
                    w += d(x.post.addr),
                    w += '</div> <div class=""><span class="people-num">',
                    w += u ? "预约": "报名",
                    w += "：",
                    w += d(x.subscribers),
                    w += "</span>人</div> </div> </div> "
                } else if (600 === x.type) {
                    w += " ";
                    var i = v((new h).getTime().toString().substring(0, 10), 10);
                    w += " ";
                    var X = i < x.post.start_time ? 0 : i > x.post.end_time ? 2 : 1;
                    w += " ",
                    x.post.time_type && X && (X = 1),
                    w += ' <div class="pk-img"> <div class="pk-logo-bg"> </div> </div> <div class="pk-info"> <div class="pk-text-container"> <h3 class="text"> <div class="post-tags"> ',
                    "undefined" != typeof x.best && 1 == x.best && (w += ' <label class="best">精</label> '),
                    w += " ",
                    x.isTop && (w += ' <label class="rec">顶</label> '),
                    w += " </div>",
                    w += d(x.title),
                    w += ' </h3> <div class="pk-content"> ',
                    w += d(q(x.post.content, x.post.urlInfo)),
                    w += ' </div> </div> <div class="pk-bottom"> <span class="left"> ',
                    w += 0 === X ? " 投票未开始 ": 2 === X ? " 投票已结束 ": " 投票进行中 ",
                    w += " </span> ",
                    x.vote_result && (w += ' <span class="right">', w += d(x.vote_result.ops[0].count + x.vote_result.ops[1].count), w += "</span> "),
                    w += " </div> </div> "
                }
                w += " </li> "
            }
        }
        return w += " "
    };
    a.bar_list_text = "TmplInline_barindex.bar_list_text",
    Tmpl.addTmpl(a.bar_list_text, f);
    var g = function(a) {
        a = a || {};
        var b = "";
        return b += '<div id="js_relative_main"> <div class="ui-top-bar js-no-bounce hide"> <div class="ui-tab page1 js-active"> <ul class="ui-groupbutton" role="tablist"> <li class="ui-button act_tab" data-toggle="tab" id=\'hot\' data-target="#page1-tab2" aria-controls="tab2" role="tab" data-index="1">热门群 </li> <li class="ui-button act_tab" data-toggle="tab" data-target="#page1-tab1" id=\'city\' aria-controls="tab1" role="tab" data-index="0">同城群 </li> </ul> </div> </div> <div class="ui-page page1 js-active" id="page1"> <div class="ui-page-content"> <div class="ui-item ui-ignore-space arrow-right guide-white hide" id="bar_guide" data-type="no_sub"> <a> <div class="my-item-left"><img src="http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/gl_icon.png" class="group-icon"></div> <div class="my-item-right"> <p class="ui-no-wrap grouptitle">设置我的关联群</p> <p class="ui-gray ui-no-wrap">想在这里展示你的群吗？立即加入</p> </div> </a> </div> <div class="ui-tab ui_page" id="hot_page" role="tabpanel"> <ul class="ui-list ui-spliter-ios" id="hot_list" > </ul> <div class="loading" id="hot_list_loading" >载入中，请稍候</div> </div> <div class="ui-tab js-active ui_page" id="city_page" role="tabpanel"> <ul class="ui-list hide ui-spliter-ios" id="city_list" > </ul> <div class="loading" id="city_list_loading" >载入中，请稍候</div> </div> <div class="ui-tab ui_page" id="page_wechat"> <ul class="ui-list hide ui-spliter-ios" id="wechat_list"> </ul> <div class="loading" id="wechat_list_loading" ><span>载入中，请稍候</span></div> </div> </div> </div> </div> '
    };
    a.bar_relativegroup = "TmplInline_barindex.bar_relativegroup",
    Tmpl.addTmpl(a.bar_relativegroup, g);
    var h = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("exsit"),
        e = b("isWeiXin"),
        f = b("scoreInfo"),
        g = b("sign"),
        h = b("isStarCategory"),
        i = b("isQQ"),
        j = "";
        if (j += "", 1 === d || e) if (j += ' <div class="info-grade" id="js_bar_grade"> <div class="info-grade-wrap"> <div id="gradeValueBar" class="info-grade-value-bar"> <i id="gradeIcon" class="info-grade-icon level', j += c(f.lv), j += '"></i> <div id="gradeValueInnerBar" class="info-grade-value-inner-bar" style="width: ', j += c(f.width), j += '%;"> <span id="starLevel">lv', j += c(f.lv), j += '</span> </div> </div> <div id="gradeScore" class="info-grade-score"> <span id="currentScore">', j += c(f.currentScore), j += '</span>/<span id="totalScore">', j += c(f.totalPoint), j += '</span> </div> <div id="addScoreTips" class="add-score-tips"> <span class="add-score-title">经验值</span><span id="addScoreElem" class="add-score-value">+1</span> </div> </div> </div> ', 1 == g) {
            var k = "";
            k += f.continueDays > 1 ? "签到" + f.continueDays + "天": "已签",
            j += " ",
            h && i ? j += ' <a id="sendHeartBtn" class="send-heart-btn btn">送心<i class="icon-heart-animation"></i></a> ': (j += ' <a id="signBtn" class="sign-btn btn signed disable"><i class="signed-icon"></i>', j += c(k), j += "</a> "),
            j += " "
        } else j += ' <a id="signBtn" class="sign-btn btn"><i class="sign-icon"></i>签到</a> ';
        return j += " "
    };
    a.bar_sign = "TmplInline_barindex.bar_sign",
    Tmpl.addTmpl(a.bar_sign, h);
    var i = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("starid"),
        e = b("starinfo"),
        f = "";
        return f += "",
        d && e && (e.title || e.link) && (f += ' <div class="star-info-container" data-url="', f += c(e.link), f += '"> ', e.pic && (f += ' <i class="star-icon" style="background-image:url(', f += c(e.pic), f += ');"></i> '), f += " ", e.title && (f += ' <span class="star-title">', f += c(e.title), f += "</span> "), f += " ", e.link && (f += ' <a class="star-link">马上进入</a> '), f += " </div> "),
        f += " "
    };
    a.bar_star_info = "TmplInline_barindex.bar_star_info",
    Tmpl.addTmpl(a.bar_star_info, i);
    var j = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("sub_number"),
        e = "";
        return e += '<h4 class="sub_bar_title">订阅部落</h4> <i class="sub_rignt_icon"></i> ',
        d ? (e += ' <span class="sub_info">我有', e += c(d), e += "个群订阅了该部落</span> ") : e += ' <span class="sub_info">让群与部落联动起来</span> ',
        e += " "
    };
    a.bar_subscribe_info = "TmplInline_barindex.bar_subscribe_info",
    Tmpl.addTmpl(a.bar_subscribe_info, j);
    var k = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("recommend"),
        e = b("bar_class"),
        f = b("best_total"),
        g = b("related_bar"),
        h = b("bid"),
        i = "";
        if (i += "", 0 == d.length) i += ' <div class="top_list-wrap"> ',
        77 == e && f && (i += " "),
        i += " ",
        g && (i += ' <div class="top-related-wrap" data-bid="', i += c(g.related_bid), i += '"> <div class="top-related section-1px" data-bid="', i += c(g.related_bid), i += '"> <img class="logo" src="', i += c(g.pic), i += '"> <span>', i += c(g.name), i += '</span> <span class="text">', i += c(g.text), i += "</span> </div> </div> "),
        i += " </div> ";
        else {
            i += ' <div class="top-list-wrap"> ',
            77 == e && f && (i += " "),
            i += " ",
            g && (i += ' <div class="top-related-wrap" data-bid="', i += c(g.related_bid), i += '"> <div class="top-related section-1px"> <img class="logo" src="', i += c(g.pic), i += '"> <span>', i += c(g.name), i += '</span> <span class="text">', i += c(g.text), i += "</span> </div> </div> "),
            i += " ";
            for (var j = 0; j < d.length; j++) {
                i += " ";
                var k = d[j];
                i += ' <div class="top-list"> ',
                0 === j ? (i += ' <div class="top-list-item', 77 == e && (i += " section-1px"), i += '"> ') : i += ' <div class="top-list-item section-1px"> ',
                i += ' <a data-href="detail.html#bid=',
                i += c(h),
                i += "&pid=",
                i += c(k.pid),
                i += '" class="link"> <label class="rec">顶</label> <span class="name">',
                i += c(k.title),
                i += "</span> </a> </div> </div> "
            }
        }
        return i += " </div> "
    };
    a.bar_top = "TmplInline_barindex.bar_top",
    Tmpl.addTmpl(a.bar_top, k);
    var l = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("Util"),
        e = b("barId"),
        f = "";
        if (f += "", d.isFestival(e)) {
            var g = d.isQQbar(e) ? "": "春晚节目";
            f += ' <div class="festival-bar-top-menu"> <i class="festival-top-menu-icon"></i> <span>',
            f += c(g),
            f += "人气榜</span> </div> "
        }
        return f
    };
    a.bar_top_menu = "TmplInline_barindex.bar_top_menu",
    Tmpl.addTmpl(a.bar_top_menu, l);
    var m = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("tab1"),
        e = b("bar_class"),
        f = b("tab2"),
        g = b("tab3"),
        h = b("isWeiXin"),
        i = b("tab4"),
        j = b("ls"),
        k = b("len"),
        l = "";
        if (l += '<div class="new-tab-list"> ', d ? (l += ' <div><a class="myTab_1_link tab_tribe" href="javascript:void(0)" data-tab="tribe">', l += c(d.name), l += "</a></div> ") : (l += " ", l += 77 == e ? ' <div><a id="tab_best" href="javascript:void(0)" class="mulu" data-tab="tribe">连载</a></div> ': ' <div><a id="tab_best" href="javascript:void(0)" data-tab="tribe">精华</a></div> ', l += " "), l += " ", f ? (l += ' <div><a class="myTab_2_link" href="javascript:void(0)" data-tab="topic" data-cls="2">', l += c(f.name), l += "</a></div> ") : l += ' <div><a id="tab_hof" href="javascript:void(0)" data-tab="hof" data-cls="2">名人堂</a></div> ', l += " ", g ? (l += ' <div><a class="myTab_3_link" href="javascript:void(0)" data-tab="topic" data-cls="2">', l += c(g.name), l += "</a></div> ") : (l += " ", h || (l += ' <div><a id="tab_qun" class="tab_qun" href="javascript:void(0)" data-tab="qun">相关群</a></div> '), l += " "), l += " ", i ? (l += ' <div><a class="myTab_4_link" href="javascript:void(0)" data-tab="topic" data-cls="2">', l += c(i.name), l += "</a></div> ") : l += ' <div><a id="tab_album" href="javascript:void(0)" data-tab="hof" data-cls="2">相册</a></div> ', l += " ", j && j.length > 0) {
            l += " ";
            for (var m = 0,
            k = j.length; k > m; m++) {
                l += " ";
                var n = j[m];
                l += " ",
                n.mid < 5 || (l += ' <div><a class="myTab_', l += c(n.mid), l += '_link" href="javascript:void(0)" data-tab="topic" data-cls="2"><div>', l += c(n.name), l += "</div></a></div> ")
            }
            l += " "
        }
        if (l += " ", !h || g && i || (l += ' <div><a id="tab_more" href="javascript:void(0)" data-tab="more">更多<span id="m_point" class="m-point"></span></a></div> '), l += " </div> ", d && d.css && (l += ' <link media="all" rel="stylesheet" href="', l += c(d.css), l += '" type="text/css" /> '), l += " ", f && f.css && (l += ' <link media="all" rel="stylesheet" href="', l += c(f.css), l += '" type="text/css" /> '), l += " ", g && g.css && (l += ' <link media="all" rel="stylesheet" href="', l += c(g.css), l += '" type="text/css" /> '), l += " ", i && i.css && (l += ' <link media="all" rel="stylesheet" href="', l += c(i.css), l += '" type="text/css" /> '), l += ' <style type="text/css"> ', d && (l += " .tab_video:before,.myTab_1_link:before,.tab_tribe:before{ ", d.icon_pre && (l += " background-image: url(", l += c(d.icon_pre), l += ") !important; "), l += " } "), l += " ", f && (l += " .tab_video:before,.myTab_2_link:before,.myTab_2:before{ ", f.icon_pre && (l += " background-image: url(", l += c(f.icon_pre), l += ") !important; "), l += " } "), l += " ", g && (l += " .tab_video:before,.myTab_3_link:before,.myTab_3:before{ ", g.icon_pre && (l += " background-image: url(", l += c(g.icon_pre), l += ") !important; "), l += " } "), l += " ", i && (l += " .tab_video:before,.myTab_4_link:before,.myTab_4:before{ ", i.icon_pre && (l += " background-image: url(", l += c(i.icon_pre), l += ") !important; "), l += " } "), l += " ", j && j.length > 0) {
            l += " ";
            for (var m = 0,
            k = j.length; k > m; m++) {
                l += " ";
                var n = j[m];
                l += " .tab_video:before,.myTab_",
                l += c(n.mid),
                l += "_link:before,.myTab_",
                l += c(n.mid),
                l += ":before{ ",
                n.icon_pre && (l += " background-image: url(", l += c(n.icon_pre), l += ") !important;"),
                l += " } "
            }
            l += " "
        }
        return l += " </style> "
    };
    a.bar_uitest_tab = "TmplInline_barindex.bar_uitest_tab",
    Tmpl.addTmpl(a.bar_uitest_tab, m);
    var n = '<div class="header-cover-img" style="background-image: url({{movie_info.vertical_pic || pic}});"></div>\r\n<div class="header">\r\n    <div class="cover mask-gray"></div>\r\n    <div class="mask-white"></div>\r\n    <div class="special-info" id="js_bar_info">\r\n        <div class="logo-container" id="js_bar_logo">\r\n            <img soda-src="{{movie_info.vertical_pic || pic}}" class="logo" alt=""/>\r\n        </div>\r\n        <div class="text-info">\r\n            <div class="name" soda-bind-html="name"></div>\r\n            <div class="rating-container {{ (movie_info.noScore) ? \'rate-no-score-container\' : \'\'}} ">\r\n                <div class="rate-star" style="background-position: 0 {{movie_info.scoreBarPosition}}px"></div>\r\n                <span class="rate-score {{ (movie_info.noScore) ? \'rate-no-score\' : \'\'}}">{{movie_info.score}}</span>\r\n            </div>\r\n            <div class="category-and-date">\r\n                <span class="start-date">\r\n                    上映{{movie_info.pub_date ? \'时间：\' + movie_info.pub_date : (movie_info.pub_year ? \'年份：\' + movie_info.pub_year : \'时间：暂无\')}}\r\n                </span>\r\n\r\n            </div>\r\n            <div class="tags" >类型：<span soda-bind-html="movie_info.label || \'暂无\'"></span></div>\r\n            <div class="length">时长：<span soda-bind-html="movie_info.time_long ? movie_info.time_long + \'分钟\' : \'暂无\'"></span></div>\r\n            <div class="actors">演员：<span soda-bind-html="movie_info.leading_actor || \'暂无\'"></span></div>\r\n        </div>\r\n        <div class="intro-wrapper" soda-if="movie_info.description">\r\n\r\n            <div class="intro lastline-space-ellipsis" id="js_go_intro" title="{{movie_info.description}}" ><span id="js_go_intro_arrow" class="right-arrow"></span></div>\r\n        </div>\r\n\r\n\r\n\r\n        <div class="btn-area">\r\n            <div class="{{hasTicket ? \'btn-buy\' : \'btn-see\'}}" data-pid="{{movie_info.movie_pid}}" id="js_op_movie">{{hasTicket ? \'立即购票\' : \'看电影\'}}</div>\r\n            <div class="btn-subscribe" id="js_movie_focus_btn" soda-if="exsit == 2">\r\n                关注\r\n            </div>\r\n            <div class="btn-movie-sign" id="js_movie_sign_btn" soda-if="exsit == 1 && sign == 2">\r\n                <i class="subscribed-unsign-icon"></i>签到\r\n            </div>\r\n            <div class="btn-movie-sign btn-movie-signed" id="js_bar_sign_btn" soda-if="exsit == 1 && sign == 1">\r\n                <i class="subscribed-icon"></i>{{ continue > 1 ? \'签到\' + continue + \'天\' : \'已签到\'}}\r\n            </div>\r\n\r\n        </div>\r\n    </div>\r\n</div>\r\n';
    a.bar_special_basic = "TmplInline_barindex.bar_special_basic",
    Tmpl.addTmpl(a.bar_special_basic, n);
    var o = '<div class="header-cover-img" style="background-image: url({{movie_info.vertical_pic || pic}});"></div>\r\n<div class="header">\r\n    <div class="cover mask-gray"></div>\r\n    <div class="mask-white"></div>\r\n    <div class="special-info" id="js_bar_info">\r\n        <div class="logo-container" id="js_bar_logo">\r\n            <img soda-src="{{movie_info.vertical_pic || pic}}" class="logo" alt=""/>\r\n        </div>\r\n        <div class="text-info">\r\n            <div class="name" soda-bind-html="name"></div>\r\n            <div class="rating-container {{ (movie_info.noScore) ? \'rate-no-score-container\' : \'\'}} ">\r\n                <div class="rate-star" style="background-position: 0 {{movie_info.scoreBarPosition}}px"></div>\r\n                <span class="rate-score {{ (movie_info.noScore) ? \'rate-no-score\' : \'\'}}">{{movie_info.score}}</span>\r\n            </div>\r\n            <div class="category-and-date">\r\n                <span class="start-date">\r\n                    年份：{{movie_info.publish_year ? movie_info.publish_year : \'暂无\'}}\r\n                </span>\r\n\r\n            </div>\r\n            <div class="tags" >类型：<span soda-bind-html="movie_info.label || \'暂无\'"></span></div>\r\n            <div class="actors">主演：<span soda-bind-html="movie_info.leading_actor || \'暂无\'"></span></div>\r\n\r\n            <div class="gray"><span soda-bind-html="movie_info.episodeupdated || \'\'"></span></div>\r\n        </div>\r\n\r\n        <div class="btn-area">\r\n            <div class="btn-see" data-mediaexist="{{movie_info.mediaexist}}" id="js_op_movie">看电视剧</div>\r\n            <div class="btn-subscribe" id="js_movie_focus_btn" soda-if="exsit == 2">\r\n                关注\r\n            </div>\r\n            <div class="btn-movie-sign" id="js_movie_sign_btn" soda-if="exsit == 1 && sign == 2">\r\n                签到\r\n            </div>\r\n            <div class="btn-movie-sign btn-movie-signed" id="js_bar_sign_btn" soda-if="exsit == 1 && sign == 1">\r\n                <i class="subscribed-icon"></i>\r\n                {{ continue > 1 ? \'签到\' + continue + \'天\' : \'已签\'}}\r\n            </div>\r\n\r\n        </div>\r\n    </div>\r\n</div>\r\n';
    a.bar_special_teleplay = "TmplInline_barindex.bar_special_teleplay",
    Tmpl.addTmpl(a.bar_special_teleplay, o);
    var p = '<div class="header-cover-img" style="background-image: url({{movie_info.vertical_pic || pic}});"></div>\r\n<div class="header">\r\n    <div class="cover mask-gray"></div>\r\n    <div class="mask-white"></div>\r\n    <div class="special-info" id="js_bar_info">\r\n        <div class="logo-container" id="js_bar_logo">\r\n            <img soda-src="{{movie_info.vertical_pic || pic}}" class="logo" alt=""/>\r\n        </div>\r\n        <div class="text-info">\r\n            <div class="name" soda-bind-html="name"></div>\r\n            <div class="category-and-date">\r\n\r\n                <div class="tags" >标签：<span soda-bind-html="movie_info.label || \'暂无\'"></span></div>\r\n\r\n            </div>\r\n            <div class="length">语言：<span soda-bind-html="movie_info.langue || \'暂无\'"></span></div>\r\n            <div class="actors">主持人：<span soda-bind-html="movie_info.presenter || \'暂无\'"></span></div>\r\n            <div class="gray"><span soda-bind-html="movie_info.newdate || \'\'"></span></div>\r\n        </div>\r\n\r\n        <div class="btn-area">\r\n            <div class="btn-see" data-mediaexist="{{movie_info.mediaexist}}" id="js_op_movie">看综艺</div>\r\n            <div class="btn-subscribe" id="js_movie_focus_btn" soda-if="exsit == 2">\r\n                关注\r\n            </div>\r\n            <div class="btn-movie-sign" id="js_movie_sign_btn" soda-if="exsit == 1 && sign == 2">\r\n                签到\r\n            </div>\r\n            <div class="btn-movie-sign btn-movie-signed" id="js_bar_sign_btn" soda-if="exsit == 1 && sign == 1">\r\n                <i class="subscribed-icon"></i>\r\n                {{ continue > 1 ? \'签到\' + continue + \'天\' : \'已签\'}}\r\n            </div>\r\n\r\n        </div>\r\n    </div>\r\n</div>\r\n';
    return a.bar_special_variety = "TmplInline_barindex.bar_special_variety",
    Tmpl.addTmpl(a.bar_special_variety, p),
    a
}),
function(a, b) {
    a.DB = b()
} (this,
function() {
    return DB.extend({
        deletePost: function(a) {
            a.url = "/cgi-bin/bar/post/delete",
            a.type = "POST",
            DB.cgiHttp(a)
        },
        bestPost: function(a) {
            a.url = "/cgi-bin/bar/post/best",
            a.type = "POST",
            DB.cgiHttp(a)
        },
        addMenuFav: function(a) {
            a.url = "/cgi-bin/bar/extra/add_mqq_fave",
            a.type = "POST",
            DB.cgiHttp(a)
        }
    }),
    DB
}),
function(a, b) {
    a.postsRecorder = {},
    a.barindex_all = b()
} (this,
function() {
    function a(a) {
        a = Number(a);
        var b = a % 60,
        c = ~~ (a / 60);
        return (c >= 10 ? c: "0" + c) + ":" + (b >= 10 ? b: "0" + b)
    }
    function b() {
        var a = $(".delete-confirm");
        return a ? (a.empty(), a.removeClass("delete-confirm")) : void 0
    }
    function c() {
        var a = $(".add-best-confirm");
        return a ? a.removeClass("add-best-confirm") : void 0
    }
    function d(a, b) {
        var c, d = {
            opername: "Grp_tribe",
            module: "tribe_hp",
            ver1: l || Util.queryString("bid") || Util.getHash("bid")
        };
        for (c in h[a]) h[a].hasOwnProperty(c) && (d[c] = h[a][c]);
        for (c in b) b.hasOwnProperty(c) && (d[c] = b[c]);
        Q.tdw(d)
    }
    function e(a) {
        var b = 1;
        return b = 5 > a ? 1 : a > 4 && 9 > a ? 2 : 3
    }
    var f, g, h, i, j, k = 10,
    l = Util.queryString("bid") || Util.getHash("bid"),
    m = [],
    n = {},
    o = !1,
    p = Util.queryString("uinav") || Util.getHash("uinav") || 1,
    q = $(".all-tab-refresh-btn"),
    r = [7],
    s = "",
    t = !1,
    u = window.ScrollModel || window.scrollModel;
    return f = p,
    g = window.requestAnimationFrame || window.webkitRequestAnimationFrame ||
    function(a) {
        setTimeout(a, 0)
    },
    window.formatDuration = a,
    h = {
        5 : {
            action: "Clk_onepost"
        },
        tribe_loadmore: {
            action: "Clk_load"
        },
        Clk_top: {
            action: "Clk_top"
        },
        Clk_photopost: {
            action: "Clk_photopost"
        },
        18 : {
            action: "Clk_essence_post"
        },
        19 : {
            action: "Clk_activity_post"
        },
        25 : {
            action: "Clk_votepost"
        },
        Clk_topicpost: {
            action: "Clk_topicpost"
        }
    },
    i = new u({
        comment: "allTab",
        renderTmpl: window.TmplInline_barindex.bar_list_text,
        renderContainer: "#js_bar_list",
        cgiName: "/cgi-bin/bar/post/get_post_by_page",
        renderTool: window.honourHelper,
        param: function() {
            var a = k,
            b = -a;
            return function() {
                return b += a,
                {
                    bid: l,
                    num: a,
                    start: b
                }
            }
        } (),
        myData: {
            type: 1
        },
        processData: function(a, b) {
            function c(a) {
                var b = new Date(a);
                return b.getMonth() + 1 + "月" + b.getDate() + "日"
            }
            var g, h, i, j, p, q, u, v, w, x, y, z, A, B, C, D, E, F = a.result.posts,
            G = !F || !F.length;
            if (a.result && a.result.posts) {
                for (g = 0, h = a.result.posts.length; h > g; g++) for (j = 0; j < m.length; j++) if (m[j].pid === a.result.posts[g].pid) {
                    a.result.posts.splice(g, 1),
                    h--,
                    g--;
                    break
                }
            } else a.result.posts || (a.result.isend = 1);
            if (p = 1 === b, q = this.myData.type, s = a.result.stargroup, w = [], 1 === q) p && (w = m);
            else if (2 === q) {
                if (p) for (g = 0; g < m.length; g++) i = m[g],
                i.best && w.push(i)
            } else if (p) for (g = 0; g < m.length; g++) i = m[g],
            i.type === q && w.push(i);
            for (F = a.result.posts || [], a.result.posts = F, a.result.barId = l, F = a.result.posts = w.concat(a.result.posts), b > 1 && d("tribe_loadmore"), $(".top-refresh-loading").hide(), Refresh.hide(), u = $("#js_bar_loading"), v = null, g = 0; g < F.length; g++) v = F[g],
            v.ad || (n[v.pid] = JSON.stringify(F[g]), v.time_formate = FormatTime(v.time), v.gname && v.related_group && (v.user.sub_flag = 1), 500 === Number(v.type) && (v.zan || (v.zan = v.vote_zan, v.cai = v.vote_cai), v.zan = Number(v.zan), v.cai = Number(v.cai)), v.post.content = window.replaceFaceCode(v.post.content), x = v.post.pic_list, x && x.length && "object" == typeof x[0] && "openact" === v.post.from && (v.post.pic_list[0].url = v.post.pic_list[0].url.replace(/\/0$/, "/160")), z = 150, v.fullbrief = v.brief, window.SubStr.size(v.brief) > z && (v.brief = window.SubStr.substr2(v.brief, 0, z) + "..."), v.user && v.user.level && v.user.level > 2 && (v.user.grade = e(v.user.level)), y = a.result.admin_ext, this.myData.isAdmin = 1 === (1 & y), this.myData.isBigBarAdmin = 2 === (2 & y), this.myData.isSmallBarAdmin = 4 === (4 & y), v.post.start && v.post.end && (F[g].post.time = c(v.post.start) + " - " + c(v.post.end)));
            return o || "allTab" !== this.comment || (0 === b ? (mqq.QQVersion && $.os.ios && (Config.isOffline ? Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 31) : Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 30)), Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 23), Q.isd(7832, 47, 10, +new Date - window.pageStartTime, 13, null, "allWithCache", !0)) : 1 === b && (mqq.QQVersion && $.os.ios && (Config.isOffline ? Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 27) : Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 26)), Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 22), Q.isd(7832, 47, 10, +new Date - window.pageStartTime, 13, null, "allWithCache", !1)), o = !0),
            (10055 === Number(l) || 10064 === Number(l)) && (a.result.gameAct = !0),
            a.result.isend ? (E = "", B = {
                2 : "精华",
                3 : "原创",
                4 : "招募",
                100 : "活动"
            },
            p && G ? (E = 1 === q ? "暂时还没有人发表话题": "暂无" + (B[q] || "") + "话题", u.html('<div class="no-content">' + E + "</div>")) : u.html(""), void this.freeze()) : (A = 1 === f && "allTab" === this.comment, C = (b - 1) * k, D = b * k, console.log("hasAd=", A, "分页数据start=", C, "end=", D, "renderCount=", b), void(A && b && $.each(r,
            function(a, c) {
                c - 1 >= C && D > c - 1 && (t = !0, console.log("广告位：renderCount=", b, "floor=", c - C - 1 + (w && w.length || 0)), F.splice(c - C - 1 + (w && w.length || 0), 0, {
                    ad: !0,
                    type: 0,
                    pid: "",
                    post: {
                        video_list: []
                    },
                    user: {}
                }))
            })))
        },
        events: function() {
            function a() {
                f && (f.removeClass("list-active"), f = null),
                g && (clearTimeout(g), g = null)
            }
            function e(b) {
                var c, e, f, g, i, j, k, m = b.target;
                if ((!$.os.ios || h) && (h = !1, m = $(m).closest("li"), f = m[0].getAttribute("data-new") ? "1": "0", c = m.attr("pid"), g = m.attr("bid"), d(5, {
                    obj1: c,
                    ver4: f
                }), c)) {
                    if (i = m.attr("type"), j = m.attr("mock")) return void Util.openDetail({
                        "#bid": g || l,
                        "#pid": c,
                        "#uploading": 1,
                        "#source": "barindex"
                    },
                    null, i);
                    a(),
                    $(m).addClass("list-active"),
                    localStorage.removeItem("fastContent"),
                    setTimeout(function() {
                        var a = m.attr("openactid");
                        if (400 === Number(i) && d("Clk_photopost", {
                            obj1: c,
                            ver4: f
                        }), ad.silentFetch("barindex", "#js_bar_list .gdt-ad"), s) Util.openDetail({
                            "#bid": l,
                            "#pid": c,
                            stargroup: s,
                            "#source": "barindex"
                        },
                        null, i);
                        else if (a) Util.openQunAct(a);
                        else {
                            if (2 === p ? d(18) : 100 === p ? d(19) : 600 === p && d(25), 0 === Number(i)) {
                                try {
                                    localStorage.setItem("fastContent", n[c])
                                } catch(b) {}
                                k = $($(m).find(".img-gallary img")[0]),
                                k.data("loaded") && (e = 1)
                            } else 401 === Number(i) && d("Clk_topicpost");
                            setTimeout(function() {
                                window.ViewPreloader.open({
                                    cgiMap: {
                                        _data_detail_content: {
                                            cgi: "/cgi-bin/bar/post/content",
                                            param: {
                                                bid: g,
                                                pid: c,
                                                barlevel: 1,
                                                start: 0,
                                                num: 10,
                                                get_like_ul: 1
                                            }
                                        }
                                    },
                                    callback: function(a) {
                                        Util.openDetail({
                                            useCacheImg: e,
                                            "#bid": g || l,
                                            "#pid": c,
                                            "#source": "barindex",
                                            "#viewPreLoad": a
                                        },
                                        null, i)
                                    }
                                })
                            },
                            100)
                        }
                        setTimeout(function() {
                            $(m).removeClass("list-active")
                        },
                        20)
                    },
                    50)
                }
            }
            var f, g, h, k, m = this,
            o = $(window),
            r = $("#js_bar_list");
            o.on("scroll",
            function() {
                if ("allTab" === m.comment) {
                    var a = $(this).scrollTop();
                    a > 800 ? q.removeClass("hide-refresh-btn") : q.addClass("hide-refresh-btn")
                }
                j = $(this).scrollTop()
            }),
            $(this.renderContainer)[0].addEventListener("load",
            function(a) {
                var b, c, d, e, f = $(a.target);
                "1" === f.data("nosize") && (b = f.prop("naturalWidth"), c = f.prop("naturalHeight"), e = f.prop("src"), d = imgHandle.formatThumb([{
                    w: b,
                    h: c
                }], !0, 90, 90, !0), f.css(d[0]))
            },
            !0),
            q.on("tap",
            function() {
                if ($.os.ios) {
                    var a = $(".top-refresh-loading");
                    a.show(),
                    o.scrollTop(0),
                    i.refresh()
                } else o.scrollTop(0),
                setTimeout(function() {
                    Refresh.show(),
                    i.refresh()
                },
                0);
                ad.init("barindex"),
                d("Clk_top")
            }),
            $("#js_bar_list")[0].addEventListener("load",
            function(a) {
                var b = $(a.target),
                c = b.attr("src") || "";
                c.indexOf("/200") > -1 && b.data("loaded", "1")
            },
            !0),
            o.on("scroll",
            function() {
                k = Date.now()
            }),
            r.on("touchstart", "li",
            function() {
                if (a(), $.os.ios) {
                    if (Date.now() - k < 300) return void(h = !1);
                    h = !0
                }
                f = $(this),
                g = setTimeout(function() {
                    var a = f.attr("pid");
                    a && f.addClass("list-active")
                },
                200)
            }).on("touchmove touchcancel", "li", a).on("touchend", "li", a).on("press", "li", e),
            $("#js_bar_list,#js_uploading_video").on("tap",
            function(d) {
                var f, g, h, i = $(d.srcElement);
                return (m.myData.isAdmin || m.myData.isBigBarAdmin || m.myData.isSmallBarAdmin) && i.hasClass("delete_post") ? (h = i.attr("data-pid"), f = i.attr("data-bid"), a(), window.setTimeout(function() {
                    ActionSheet.show({
                        items: ["同时将该用户拉黑", "只删除不拉黑"],
                        onItemClick: function(a) {
                            var b = {
                                bid: f || l,
                                pid: h
                            };
                            b.black = 0 === a ? 1 : 0,
                            g = $("#" + h),
                            g.remove(),
                            DB.cgiHttp({
                                param: {
                                    bid: f,
                                    pid: h,
                                    black: b.black
                                },
                                url: "/cgi-bin/bar/post/delete",
                                succ: function() {
                                    Tip.show("删除成功", {
                                        type: "ok"
                                    })
                                },
                                err: function() {
                                    Tip.show("删除失败，请稍后重试。", {
                                        type: "warning"
                                    })
                                }
                            })
                        }
                    })
                },
                0), !1) : m.myData.isBigBarAdmin && i.hasClass("add-best-disabled") ? (a(), i.hasClass("add-best-confirm") ? (h = i.attr("data-pid"), DB.bestPost({
                    param: {
                        bid: l,
                        pid: h
                    },
                    succ: function() {
                        i.removeClass("add-best-disabled"),
                        i.removeClass("add-best-confirm");
                        var a = document.createElement("label");
                        a.className = "best",
                        a.innerHTML = "精",
                        $("#post_tags_" + h).append(a)
                    },
                    err: function() {
                        c()
                    }
                }), !1) : (i.addClass("add-best-confirm"), !1)) : i.hasClass("icons") ? (a(), c(), b(), !1) : (b(), c(), void e(d))
            }),
            Q.huatuo(1486, 1, 1, Date.now() - window.pageStartTime, 2)
        },
        complete: function(a, b) {
            imgHandle.lazy($(this.renderContainer)[0]),
            2 > b && $.os.ios && ($("#js_bar_main")[0].scrollTop = 0),
            b && t && (ad.show("barindex", "#js_bar_list .gdt-ad"), t = !1)
        },
        onreset: function() {
            window.postsRecorder = {},
            $("#js_bar_list").html("");
            var a = $("#js_bar_loading");
            a.html("载入中，请稍候..."),
            a.css("opacity", "1")
        },
        error: function(a) {
            101001 === Number(a.retcode) && $("#js_bar_loading").text("对不起，您没有权限访问该部落"),
            this.getCache(0) || 1e5 === Number(a.retcode) || Tip.show("获取帖子列表失败[" + a.retcode + "]", {
                type: "warning"
            })
        }
    }),
    i.setStarGroupFlag = function(a) {
        s = a
    },
    i.setTopListSpecialPool = function(a) {
        m = m.concat(a)
    },
    i
}),
document.domain = "qq.com",
function(a, b) {
    a.postsRecorder = {},
    a.BarIndex = b()
} (this,
function() {
    function a() {
        var a = Date.now();
        return a >= new Date("2015/2/11 00:00:00").getTime() && a <= new Date("2015/3/2 23:59:59").getTime()
    }
    function b() {
        var a = Date.now();
        return a >= new Date("2015/2/18 19:30:00").getTime() && a <= new Date("2015/2/19 01:30:00").getTime()
    }
    function c(a) {
        x && y ? a(x, y) : mqq.sensor.getLocation(function(b, c, d) {
            0 === b && (x = parseInt(1e6 * (c || 0), 10), y = parseInt(1e6 * (d || 0), 10)),
            a(x, y)
        })
    }
    function d(a) {
        if ("number" != typeof a) return a;
        a = +a;
        var b, c = "",
        d = 1e4,
        e = 1e8;
        if (d > a) c = "" + a;
        else if (a >= d && e > a) {
            if (b = ("" + (a / d).toFixed(1)).split("."), 0 === parseInt(b[1], 10)) return b[0] + "万";
            c = (b[1] > 5 ? parseInt(b[0], 10) + 1 : b[0]) + "万"
        } else if (a >= e) {
            if (b = ("" + (a / e).toFixed(2)).split("."), 0 === parseInt(b[1], 10)) return b[0] + "亿";
            b[1][1] + "" == "0" && (b[1] = b[1][0]),
            c = b.join(".") + "亿"
        }
        return c
    }
    function e(a) {
        a += "",
        a = a.substr(0, 4);
        var b = a.match(/\d/g),
        c = /\./.test(a);
        switch (b.length) {
        case 1:
            a += ".0%";
            break;
        case 2:
            a += c ? "%": ".0%";
            break;
        default:
            a += "%"
        }
        return a
    }
    function f(a) {
        var b = [];
        return 1 & a && b.push({
            type: "tupian",
            value: "图片"
        }),
        2 & a && b.push({
            type: "niming",
            value: "匿名"
        }),
        4 & a && b.push({
            type: "shijian",
            value: "时间"
        }),
        8 & a && b.push({
            type: "diqu",
            value: "地区"
        }),
        b
    }
    function g(a, b) {
        var c = h(a, b),
        d = c.lv,
        e = c.totalPoint,
        f = c.width,
        g = c.currentScore,
        i = $("#gradeValueInnerBar"),
        j = $("#gradeIcon"),
        k = $("#currentScore"),
        l = $("#totalScore"),
        m = $("#starLevel");
        j.addClass("level" + d),
        i.css("width", f + "%"),
        m.html("LV" + d),
        l.html(e),
        k.html(g)
    }
    function h(a, b) {
        a || (a = {});
        var c, d = a.level || 1,
        e = a.point || 0,
        f = cc[d] || 15,
        g = cc[d - 1] || 0,
        h = (e - g) / (f - g) * 100;
        return 0 !== h && (h = Math.max(h, 10)),
        Zb ? (c = i(e, b), h = c.percent, f = c.totalPoint, {
            width: h,
            lv: d,
            totalPoint: f,
            currentScore: e
        }) : {
            width: h,
            lv: d,
            totalPoint: f - g,
            currentScore: e - g
        }
    }
    function i(a, b) {
        var c, d, e, f, g = [0, 100, 200, 300, 500, 700, 1e3, 1500, 2e3, 3e3, 4e3, 6e3, 1e4, 15e3, 2e4, 3e4, Number.POSITIVE_INFINITY],
        h = g.length,
        i = [0, 7, 14, 30, Number.POSITIVE_INFINITY],
        j = [0, 2, 2, 2, 2],
        k = i.length;
        if (0 === Number(a)) d = 1,
        c = 0;
        else for (d = h; d > 0; d--) if (a > g[d - 1]) {
            c = d === h - 1 ? 100 : 100 * (a - g[d - 1]) / (g[d] - g[d - 1]);
            break
        }
        for (e = i[1] - i[0], f = k; f > 0; f--) if (b >= i[f - 1]) {
            e = i[f] - b;
            break
        }
        return {
            growing: j[f],
            nextGrowing: j[f + 1],
            level: d,
            percent: c,
            remain: e,
            totalPoint: g[d]
        }
    }
    function j() {
        Util.openUrl("http://imgcache.qq.com/club/themes/mobile/xingying/html/signIn.html?starid=" + R, !0)
    }
    function k() {
        Util.openUrl("http://imgcache.qq.com/club/themes/mobile/xingying/html/level.html?starid=" + R, !0)
    }
    function l() {
        T.refresh(),
        bc(9),
        G && bc("star_sign_clk")
    }
    function m() {
        if (!window.needFollowTip) {
            $("#follow-tips-barname").html(Fb);
            var a, b = $("#follow-tips-num"),
            c = $("#follow-sign"),
            d = parseInt(Gb) + 1,
            e = d.toString().length,
            f = 0,
            g = 1;
            setTimeout(function() {
                $("#follow-mask").on("touchmove",
                function(a) {
                    a.preventDefault(),
                    a.stopPropagation()
                }).on("tap",
                function(a) {
                    var b = a.target;
                    "follow-mask" === b.id ? $("#follow-mask").addClass("out").on("webkitTransitionEnd",
                    function() {
                        $("#follow-mask").remove()
                    }) : "follow-sign" === b.id && $("#follow-mask").addClass("out").on("webkitTransitionEnd",
                    function() {
                        $("#follow-mask").remove(),
                        l()
                    })
                }),
                G && c.text("立即签到，增加偶像魅力值"),
                S && c.hide(),
                $("#follow-mask").show(),
                setTimeout(function() {
                    if ($.os.ios && e >= 3) var c = setInterval(function() {
                        a = parseInt(20 > g ? Math.pow(10, Math.floor(e / 3)) / 20 : 40 > g ? (Math.pow(10, Math.floor(e / 3 * 2)) - f) / 20 : (Math.pow(10, Math.floor(e)) - f) / 20),
                        f + a >= d ? (f = d, clearInterval(c)) : f += a,
                        b.html(f),
                        g++
                    },
                    16);
                    else b.html(d)
                },
                600),
                setTimeout(function() {
                    $("#follow-tips-icon").css("opacity", 1)
                },
                900)
            },
            300)
        }
    }
    function n() {
        var a = "心不足，开通星影会员每日可领5心";
        Alert.show("温馨提示", a, {
            callback: function() {
                var a = $.os.ios ? "ios": $.os.android ? "android": "mobile";
                bc("Clk_vip", {
                    ver3: 2,
                    ver4: a
                }),
                XYPAY.openVip({
                    month: 3,
                    aid: "xylm.dabang." + a + ".fuceng." + rb,
                    device: a,
                    from: "浮层",
                    privilege: "打榜"
                },
                function(a) {
                    0 === a.result.resultCode && p()
                })
            },
            confirm: "立即开通",
            cancel: "取消",
            confirmAtRight: !0
        })
    }
    function o() {
        var a = "心不足，星影会员今日还可领5心";
        Alert.show("温馨提示", a, {
            callback: function() {
                bc("Clk_take", {
                    ver3: 2
                }),
                X.rock()
            },
            confirm: "立即领取",
            cancel: "取消",
            confirmAtRight: !0
        }),
        bc("exp_take", {
            ver3: 2
        })
    }
    function p() {
        var a = new gc({
            comment: "starHeart",
            cgiName: "/cgi-bin/bar/get_user_love_info",
            param: {
                plat: $.os.ios ? 3 : 2
            },
            processData: function(a) {
                a.retcode || (Y = a.result.vip_flag, Z = a.result.today_get_loved)
            },
            complete: function() {}
        });
        a.rock()
    }
    function q() {
        var a, b = {};
        $.extend(b, {
            bid: rb,
            flag: B,
            barName: Fb,
            pid: 0,
            ctxNode: $("#js_bar_wraper"),
            pubulishType: "pub",
            succ: function(a) {
                Util.openUrl(Eb + "detail.html#bid=" + (a.bid || rb) + "&pid=" + a.pid + "&source=barindex", !0, 0)
            },
            cancel: function() {
                window.history.go( - 1)
            },
            ondestroy: function() {},
            config: {
                from: "barindex",
                needCategory: 0
            }
        }),
        Zb && 8 & Hb && (b.config.minLength = 10, b.config.minTitleLength = 2),
        1 === E && (b.config.minLength = 10),
        mqq && mqq.compare("5.0") < 0 && (a = Util.getHash("target"), "send" !== a && (window.location.hash += "&target=send")),
        localStorage.getItem("pho_alert" + rb) || 10364 !== Number(rb) && 10679 !== Number(rb) ? Publish.init(b) : Util.showStatement(function() {
            Publish.init(b)
        },
        rb)
    }
    function r() {
        if (!Jb) {
            if (Xb) return window.YybJsBridge.setShareInfo({
                allowShare: 1,
                iconUrl: Kb,
                jumpUrl: location.href,
                title: "兴趣部落-" + Mb,
                summary: Lb
            }),
            void(Jb = !0);
            var a, b, c, d = 0,
            e = 0;
            d = Util.queryString("bid") || Util.getHash("bid"),
            e = Util.queryString("pid") || Util.getHash("pid"),
            a = function() {
                bc("Clk_collect", {
                    obj1: e,
                    os: $.os.ios ? "ios": "android"
                }),
                b = $(".name").text(),
                c = $(".logo")[0].src || "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/recommend-blue-icon.png",
                DB.addMenuFav({
                    param: {
                        bid: d,
                        pic: c,
                        pid: e,
                        sub_title: "兴趣部落",
                        title: b
                    },
                    succ: function() {
                        Tip.show("已收藏")
                    },
                    err: function() {
                        Tip.show("操作失败，请重试！", {
                            type: "warning"
                        })
                    }
                })
            },
            F = ["share_qq", "share_qzone", "share_wechat", "share_circle", "share_weibo", "share_link", "share_collection", "share_shortcut"],
            ActionButton.build({
                iconID: "4",
                type: "share"
            },
            function() {
                bc("Clk_share"),
                1 === parseInt(P) && bc(30);
                var b = location.href;
                P && RichShare.build([{
                    text: "订阅到群",
                    img: "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/share/subscribe_qun.png",
                    onTap: function() {
                        bc(31),
                        Util.openUrl("http://buluo.qq.com/mobile/sub/subscribe_qun.html?bid=" + rb + "&bname=" + Mb + "&bpic=" + Kb + "&_wv=1027", !0)
                    }
                }], {
                    insertMode: "after"
                }),
                Util.shareMessage({
                    shareUrl: b,
                    pageUrl: location.href,
                    imageUrl: Kb,
                    title: "兴趣部落-" + Mb,
                    barName: Mb,
                    content: Lb,
                    imageInfo: {
                        bid: rb
                    },
                    succHandler: function(a) {
                        bc(["qq_suc", "qzone_suc", "wechat_suc", "circle_suc"][a])
                    }
                },
                function(b, c) {
                    6 === c ? a() : 7 === c ? bc("shortcut") : 6 > c ? bc(F[c]) : 8 === c && bc("share_qrcode")
                })
            }),
            Jb = !0
        }
    }
    function s(a) {
        a = Number(a);
        var b = a % 60,
        c = ~~ (a / 60);
        return (c >= 10 ? c: "0" + c) + ":" + (b >= 10 ? b: "0" + b)
    }
    function t() {
        setTimeout(function() {
            var a, b, c = "im_xiaoqu.qq.com_is_guide_wsq",
            d = localStorage.getItem(c) || $.cookie(c),
            e = $("#guide-wsq-mask");
            d || (e.show(), $("#guide-wsq-mask img").each(function(a, b) {
                b.src = $(b).data("src")
            }), a = window.innerHeight, 420 >= a ? ($("#guide-wsq-mask .wsq-pic").css("margin-top", 0), $("#guide-wsq-mask .wsq-text").css("margin-top", 0), $("#guide-wsq-button").css("margin-top", 10), $("#wsqSkipBtn").addClass("smaller")) : 490 > a && ($("#guide-wsq-mask .wsq-pic").css("margin-top", Math.floor(a / 520 / 2 * 50)), $("#guide-wsq-mask .wsq-text").css("margin-top", Math.floor(a / 520 / 2 * 40)), $("#wsqSkipBtn").addClass("smaller")), $("#WsqGuideCarousel").carousel({
                interval: 0,
                wrap: !1,
                containerWidth: window.innerWidth || 320
            }), $(document).on("wsq_carousel_end",
            function() {
                $("#wsqSkipBtn").hide()
            }), mqq.ui.setRightDragToGoBackParams({
                enable: !0,
                width: 30
            }), b = function() {
                localStorage.setItem(c, "true"),
                $.cookie(c, "true", {
                    expires: 1024
                }),
                e.addClass("hide-anim"),
                setTimeout(function() {
                    e.hide()
                },
                300)
            },
            $("#guide-wsq-button").on("tap",
            function() {
                b(),
                bc("", {
                    action: "Clk_open",
                    module: "tribe_update"
                })
            }), $("#wsqSkipBtn").on("tap",
            function() {
                b(),
                bc("", {
                    action: "Clk_jump",
                    module: "tribe_update"
                })
            }), bc("", {
                action: "exp",
                module: "tribe_update"
            }))
        },
        2e3)
    }
    function u() {
        function a() {
            f({
                callid: 1024,
                path: "/buluo",
                key: "heartGuideFlag"
            },
            function(a) {
                if (0 !== a.ret || !a.response) return void(window.localStorage && "1" !== localStorage.getItem("heartGuideFlag") && (d(), b()));
                var c;
                try {
                    c = JSON.parse(a.response.data)
                } catch(e) {
                    console.log(e)
                }
                c && c[h] || (d(), b())
            })
        }
        function b() {
            var a = {};
            a[h] = !0,
            g({
                callid: 1024,
                path: "/buluo",
                key: "heartGuideFlag",
                data: JSON.stringify(a)
            },
            function(a) {
                0 !== a.ret && window.localStorage && localStorage.setItem("heartGuideFlag", 1)
            })
        }
        function c() {
            return e ? void a() : void console.log("supportGuide not support mqq")
        }
        function d() {
            var a, b = ["http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/heart-guide-1.png", "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/heart-guide-2.png"],
            c = "";
            for ("xylm" === ub && b.unshift("http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/nopack/heart-guide-3.png"), a = 0; a < b.length; a++) c += '<li class="dot ' + (0 === a ? "current": "") + '"></li>';
            var d = '<div class="ui-slide-container"><div class="ui-slide"></div><ul class="ui-slider-indicators">' + c + "</ul></div>";
            Alert.show("", d, {
                confirm: "我知道了"
            }),
            window.smallSlider.init({
                onImgIntoScreen: function(a) {
                    $(".ui-slider-indicators .dot").removeClass("current"),
                    $(".ui-slider-indicators .dot").eq(a - 1).addClass("current")
                },
                container: ".ui-slide",
                width: 240,
                images: b
            })
        }
        var e = mqq && mqq.data && mqq.support("mqq.data.readH5Data") && mqq.support("mqq.data.writeH5Data"),
        f = mqq.data.readH5Data,
        g = mqq.data.writeH5Data,
        h = "showed";
        c()
    }
    function v() {
        var a = Util.getHash("shortcut");
        "true" === a && mqq && mqq.compare("5.5.1") > -1 && mqq.support("mqq.ui.closeWebViews") && (mqq.support("mqq.ui.setTitleButtons") && mqq.ui.setTitleButtons({
            left: {
                title: "返回"
            }
        }), mqq.ui.closeWebViews({
            mode: 2,
            exclude: !0
        }), console.log("桌面快捷方式打开部落，清理其余WebView"))
    }
    function w() {
        vb && $("#js_bar_basic").addClass("weixin-barindex");
        var a = Util.getHash("type");
        if (!rb) return Tip.show("部落ID错误", {
            type: "warning"
        });
        sb && Util.openDetail({
            "#bid": rb,
            "#pid": sb
        },
        null, a),
        rb = rb.replace(/[^\d]*/g, ""),
        tb && (cb.hide(), $("#js_bar_main").css({
            bottom: 0
        })),
        v(),
        _.usePreLoad = !0,
        window.globalPreLoader.hasData("barTopData") ? Q.monitor(477705) : (_.rock(), Q.monitor(477703)),
        window.globalPreLoader.getData("barTopData",
        function(a) {
            _.setPreLoadData(a),
            _.rock()
        }),
        xb.usePreLoad = !0,
        window.globalPreLoader.hasData("postData") ? Q.monitor(477706) : (xb.rock(), Q.monitor(477704)),
        window.globalPreLoader.getData("postData",
        function(a) {
            xb.setPreLoadData(a),
            xb.rock()
        }),
        Wb && Wb.timing && Q.huatuo(1486, 1, 1, Wb.timing.responseStart - Wb.timing.navigationStart, 1),
        ad.init("barindex"),
        bc(1),
        bc(0, {
            ver5: navigator.userAgent
        });
        var b = {
            34356 : 623131,
            30768 : 623132,
            160031 : 623133,
            202364 : 623134,
            36244 : 623135
        };
        b.hasOwnProperty(rb) && Q.monitor(b[rb]),
        Q.monitor(428051),
        Q.huatuo(1486, 1, 1, 1e3, 3),
        L = Util.getHash("topic"),
        "1" === L && (M = $("#js_bar_top").offset().top, window.scrollTo(0, M), $("#js_bar_main").scrollTop(M)),
        window.mqq && mqq.addEventListener && mqq.addEventListener("addreadnum",
        function(a) {
            var b, c = a.pid,
            d = a.bid,
            e = $("#" + c);
            if (e.length && Number(rb) === Number(d) && (b = $(e.find(".read-num-text")), b.length)) {
                var f = Number(b.text());
                isNaN(f) || b.text(f + 1)
            }
        })
    }
    Model.defaultEffect = "fadeIn";
    var x, y, z, A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, R, S, T, U, V, W, X, Y, Z, _, ab, bb, cb, db, eb, fb, gb, hb, ib, jb, kb, lb, mb, nb, ob, pb, qb, rb = Util.queryString("bid") || Util.getHash("bid"),
    sb = Util.queryString("pid") || Util.getHash("pid"),
    tb = Util.getHash("uibarhide") || 0,
    ub = Util.queryString("from") || Util.getHash("from"),
    vb = /\bMicroMessenger\/[\d\.]+/.test(navigator.userAgent),
    wb = 0 !== Number(mqq.QQVersion),
    xb = window.barindex_all,
    yb = "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/temp-bar-icon/",
    zb = {
        "http://pub.idqqimg.com/pc/misc/files/20150218/9d647e60fb2c47aba4d095801f43f3d3.png": yb + "bar-video-icon.png",
        "http://pub.idqqimg.com/pc/misc/files/20141204/5932910a2e95408e83d4bc0f9ce247d5.png": yb + "bar-image-icon.png",
        "http://pub.idqqimg.com/pc/misc/files/20141204/843063f9eaf44cec92349d29f857dfe5.png": yb + "bar-shoping-icon.png",
        "http://pub.idqqimg.com/pc/misc/files/20150120/6cd83902db9840aba8ab1c43c24c5d70.png": yb + "bar-view-icon.png",
        "http://pub.idqqimg.com/pc/misc/files/20150107/3eaa7e15b48e4d0fa8a66d096a051789.png": yb + "bar-view-icon.png"
    },
    Ab = !1,
    Bb = !1,
    Cb = !1,
    Db = {},
    Eb = "http://xiaoqu.qq.com/mobile/",
    Fb = "",
    Gb = 0,
    Hb = 0,
    Ib = [],
    Jb = !1,
    Kb = "http://q3.qlogo.cn/g?b=qq&k=XnGFNfgzCr7LnaWFrAw0UQ&s=100",
    Lb = "",
    Mb = "",
    Nb = "",
    Ob = "",
    Pb = Util.queryString("historyfrom") || Util.getHash("historyfrom") || "",
    Qb = !1,
    Rb = 0,
    Sb = !1,
    Tb = navigator.userAgent.match(/\/qqdownloader\/(\d+)?/),
    Ub = 0,
    Vb = $(".all-tab-refresh-btn"),
    Wb = window.performance,
    Xb = !1,
    Yb = function(a) {
        var b = Util.getHash("scene"),
        c = 0;
        return c = "" !== b ? a[b] : a[Util.queryString("from") || Util.getHash("from")]
    } ({
        recent: 1,
        rank: 2,
        personal: 3,
        search: 4,
        weixin: 5,
        clientsearch: 6,
        tribeinfo: 7,
        detail: 8
    }),
    Zb = 0,
    $b = Util.queryString("uinav") || Util.getHash("uinav") || 1,
    _b = ["#tab_all", "#tab_hof", "#tab_qun", "#tab_best", ".myTab_2", ".myTab_1", ".tab_video"],
    ac = {
        0 : {
            action: "visit"
        },
        1 : {
            action: "visit_hp",
            ver3: Config.isOffline ? 11 : 10
        },
        2 : {
            action: "Clk_banner"
        },
        3 : {
            action: "Clk_focus"
        },
        6 : {
            action: "Clk_pub"
        },
        7 : {
            action: "Clk_refresh"
        },
        "#tab_tribe": {
            action: "exp",
            module: "order"
        },
        9 : {
            action: "Clk_sign"
        },
        10 : {
            action: "Clk_toppost"
        },
        ".tab_1": {
            action: "Clk_all"
        },
        ".tab_2": {
            action: "Clk_essence"
        },
        ".tab_3": {
            action: "Clk_original"
        },
        ".tab_4": {
            action: "Clk_recruit"
        },
        ".tab_100": {
            action: "Clk_activity"
        },
        "#tab_rank": {
            action: "Clk_sub_ranking"
        },
        "#tab_intro": {
            action: "Clk_intro"
        },
        "#tab_menu": {
            action: "Clk_sub_playbill"
        },
        "#Clk_acspace": {
            action: "Clk_acspace"
        },
        "#tab_all": {
            action: "Clk_all"
        },
        "#tab_best": {
            action: "Clk_essence"
        },
        "#tab_hof": {
            action: "Clk_hof"
        },
        "#tab_act": {
            action: "Clk_activity"
        },
        "#tab_more": {
            action: "Clk_more"
        },
        16 : {
            action: "Clk_playgame"
        },
        "#tab_qun": {
            action: "Clk_grp"
        },
        20 : {
            action: "Clk_head",
            module: "order"
        },
        21 : {
            action: "Clk_tribe",
            module: "order"
        },
        22 : {
            action: "Clk_ask",
            module: "order"
        },
        23 : {
            action: "Clk_more",
            module: "order"
        },
        24 : {
            action: "Clk_reverse",
            module: "essence"
        },
        xiaomi_share: {
            action: "share",
            module: "activity",
            opername: "Grp_xiaomi"
        },
        xiaomi_pv: {
            action: "Clk_lottery"
        },
        Clk_share: {
            action: "Clk_share"
        },
        share_qq: {
            action: "share_qq"
        },
        share_qzone: {
            action: "share_qzone"
        },
        share_wechat: {
            action: "share_wechat"
        },
        share_circle: {
            action: "share_circle"
        },
        share_weibo: {
            action: "share_weibo"
        },
        share_link: {
            action: "share_link"
        },
        share_qrcode: {
            action: "share_qrcode"
        },
        share_collection: {
            action: "share_collection"
        },
        Clk_top: {
            action: "Clk_top"
        },
        exp_global: {
            action: "exp_global"
        },
        Clk_global: {
            action: "Clk_global"
        },
        sso_sign: {
            action: "safe_sign",
            module: "post",
            opername: "tribe_cgi"
        },
        star_sign_clk: {
            action: "hit",
            module: "tribe_hp",
            opername: "Grp_tribe"
        },
        tab4_click: {
            action: "Clk_album"
        },
        shortcut: {
            action: "cut_tribe",
            module: "tribe_destop",
            ver3: $.os.ios ? "ios": $.os.android ? "android": ""
        },
        exp_video: {
            action: "exp_video"
        },
        Clk_video: {
            action: "Clk_video"
        },
        exp_guide: {
            action: "exp_guide"
        },
        Clk_guide: {
            action: "Clk_guide"
        },
        qq_suc: {
            action: "qq_suc"
        },
        qzone_suc: {
            action: "qzone_suc"
        },
        wechat_suc: {
            action: "wechat_suc"
        },
        circle_suc: {
            action: "circle_suc"
        },
        Clk_god: {
            action: "Clk_god"
        },
        exp_god: {
            action: "exp_god"
        },
        Clk_film: {
            action: "Clk_film"
        },
        Clk_ticket: {
            action: "Clk_ticket"
        },
        Clk_star: {
            action: "Clk_star"
        },
        exp_send: {
            module: "star_hit",
            action: "exp_send"
        },
        Clk_send: {
            module: "star_hit",
            action: "Clk_send"
        },
        exp_take: {
            module: "star_hit",
            action: "exp_take"
        },
        Clk_take: {
            module: "star_hit",
            action: "Clk_take"
        },
        exp_layer: {
            module: "star_hit",
            action: "exp_layer"
        },
        Clk_vip: {
            module: "star_hit",
            action: "Clk_vip"
        },
        30 : {
            opername: "Grp_subscription",
            module: "sub_grp",
            action: "exp_button"
        },
        31 : {
            opername: "Grp_subscription",
            module: "sub_grp",
            action: "Clk_button"
        }
    },
    bc = function(a, b) {
        var c, d = {
            opername: "Grp_tribe",
            module: "tribe_hp",
            ver1: rb || Util.queryString("bid") || Util.getHash("bid")
        };
        for (c in ac[a]) ac[a].hasOwnProperty(c) && (d[c] = ac[a][c]);
        for (c in b) b.hasOwnProperty(c) && (d[c] = b[c]);
        Q.tdw(d)
    },
    cc = [0, 15, 30, 80, 160, 300, 500, 1e3, 1500, 3e3, 4500, 9e3, 15e3, 25e3, 5e4, 1e5, 2e5],
    dc = !1,
    ec = !1,
    fc = $(".empty-container"),
    gc = window.cgiModel,
    hc = window.renderModel,
    ic = window.linkModel,
    jc = window.mutitabModel,
    kc = window.pageModel;
    Tb && (Ub = 1),
    Tb && (Xb = !0),
    window.isLowerIos = $.os.ios && $.os.version < "6.9",
    window.isLowerIos && $("html,body").removeClass("ios"),
    function() {
        var a = {
            card: "395362",
            search: "395364",
            pa: "395363",
            other: "395370"
        },
        b = Util.queryString("from") || Util.getHash("from") || "other",
        c = a[b] || a.other;
        $.os.android && localStorage.setItem("card-open", 1),
        Q.monitor(c)
    } (),
    T = new gc({
        comment: "signCgi",
        cgiName: vb ? "/cgi-bin/bar/user/fbar_and_sign": "/cgi-bin/bar/user/sign",
        param: {
            bid: +rb
        },
        processData: function(a, b) {
            if (0 !== b) {
                var c, d, e, f, h, i, j, k, l, m, n, o, p = mqq && mqq.compare("5.3.2") > -1,
                q = $("#js_bar_charm_count"),
                r = $("#charm_count_wrap"),
                s = $("#sign-star-charm-added");
                0 === a.retcode ? (Ab || Bb || Cb ? (d = $("#js_movie_sign_btn"), d.html(a.result && a.result["continue"] >= 2 ? '<i class="subscribed-icon"></i>签到' + a.result["continue"] + "天": '<i class="subscribed-icon"></i>已签'), d.addClass("btn-movie-signed")) : (c = $("#signBtn"), c.html(a.result && a.result["continue"] >= 2 ? "签到" + a.result["continue"] + "天": '<i class="signed-icon"></i>已签'), c.addClass("signed"), G && wb ? (bc("exp_send", {
                    ver3: 2
                }), c.addClass("send-heart-btn").attr("id", "sendHeartBtn").removeClass("signed sign_btn").html('送心<i class="icon-heart-animation"></i>')) : c.addClass("disable"), e = $("#addScoreTips"), f = $("#addScoreElem"), h = Number($("#currentScore").html()) || 0, i = +$("#starLevel").text().substr(2) || 1, j = Zb ? Number(a.result.level.point) - h: Number(a.result.level.point) - h - cc[i - 1] || 0, k = j >= 0 ? "+": "", f.html(k + j)), l = a.result["continue"], l && (7 > l ? setTimeout(function() {
                    m = $("#sign-continue-day-list li"),
                    n = 1e3;
                    var a = function(a) {
                        setTimeout(function() {
                            $(m[a]).addClass("active")
                        },
                        n)
                    };
                    for ($("#continue-left-day").html(1 === Number(l) ? '连续签到 <span style="color: #8AC628; font-size: 18px;">7</span> 天可点亮': '还需签到 <span style="color: #8AC628; font-size: 18px;">' + (7 - l) + "</span> 天即可点亮"), o = 0; l - 1 > o; o++) $(m[o]).addClass("active");
                    a(o),
                    $("#sign-continue-tips-lt7").show(),
                    setTimeout(function() {
                        $("#sign-continue-tips-lt7").hide()
                    },
                    2500 + n)
                },
                500) : l > 7 && setTimeout(function() {
                    $("#sign-continue-tips-num").html(l),
                    $("#sign-continue-tips-gt7").show(),
                    setTimeout(function() {
                        $("#sign-continue-tips-gt7").hide()
                    },
                    2e3)
                },
                500)), 7 === Number(l) && setTimeout(function() {
                    $("#sign-continue-tips-eq7").show(),
                    setTimeout(function() {
                        $("#sign-continue-tips-eq7").hide()
                    },
                    3e3)
                },
                500), Ab || Bb || Cb || (g(a.result.level), G ? (e.find(".add-score-title").text("魅力值"), f.html("+1"), s.text("魅力值+1").show(), setTimeout(function() {
                    r.addClass("charm-count-animation")
                },
                2e3)) : (e.addClass("show"), setTimeout(function() {
                    e.removeClass("show")
                },
                3e3))), G && q.text(parseInt(q.attr("num"), 10) + 1), mqq.dispatchEvent("event_sign_tribe", {
                    bid: rb,
                    sign: !0
                },
                {
                    domains: ["*.qq.com"]
                }), bc("sso_sign", {
                    ver3: p ? 1 : 2
                })) : (Tip.show("签到失败，请稍后重试", {
                    type: "warning"
                }), bc("sso_sign", {
                    ver3: p ? 3 : 4
                })),
                mqq.compare("5.0") >= 0 && setTimeout(function() {
                    mqq.dispatchEvent("updateFocusList", {},
                    {
                        domains: ["*.qq.com"]
                    })
                },
                200)
            }
        },
        error: function() {
            Tip.show("签到失败，请稍后重试", {
                type: "warning"
            });
            var a = mqq && mqq.compare("5.3.2") > -1;
            bc("sso_sign", {
                ver3: a ? 5 : 6
            })
        }
    }),
    U = new gc({
        comment: "voteCgi",
        cgiName: "/cgi-bin/bar/user/fbar",
        param: {
            bid: rb,
            op: 1
        },
        processData: function(a) {
            var b, c, e, f = $("#opArea"),
            g = $("#js_bar_vote_count"),
            h = $("#js_bar_bottom_wrap"),
            i = $("#js_bar_vote_btn");
            f.hide(),
            $(".bar-info-text").hide(),
            b = parseInt(g.attr("num"), 10) + 1,
            g.attr("num", b),
            g.html(d(b)),
            h.removeClass("focus"),
            !h.hasClass("reply") && h.addClass("reply"),
            c = "/cgi-bin/bar/page/" + encodeURIComponent(JSON.stringify({
                bid: rb,
                platform: Ub
            })),
            e = $.storage.get(c),
            Zb || (a = e && "string" == typeof e ? JSON.parse(e) : $.storage.get(c) || a),
            a && a.result && (a.result.exsit = 1, eb.update(a), (Ab || Bb || Cb) && (i = $("#js_movie_focus_btn"), i.attr("id", "js_movie_sign_btn").addClass("btn-movie-sign").removeClass("btn-subscribe"), 1 === Number(a.result.sign) ? i.addClass("btn-movie-signed").html('<i class="subscribed-icon"></i>' + (Number(a.result.
            continue) >= 2 ? "签到" + a.result.
            continue + "天": "已签")) : i.html("签到"))),
            Sb = !0,
            ($.os.ios || $.os.android) && mqq.compare("4.6") >= 0 && mqq.data.readH5Data({
                callid: 1,
                path: "/buluo",
                key: "followTipsMap"
            },
            function(a) {
                var b = {};
                0 === a.ret ? (b = JSON.parse(a.response.data), 0 !== b[rb] ? (b[rb] = 0, m()) : Tip.show("关注成功", {
                    type: "ok"
                })) : (b[rb] = 0, m()),
                mqq.data.writeH5Data({
                    callid: 1,
                    path: "/buluo",
                    key: "followTipsMap",
                    data: JSON.stringify(b)
                },
                function() {})
            }),
            mqq.dispatchEvent("event_focus_tribe", {
                bid: rb,
                focus: !0
            },
            {
                domains: ["*.qq.com"]
            })
        }
    }),
    V = U.extend({
        comment: "unvoteCgi",
        param: {
            bid: rb,
            op: 2
        },
        processData: function(a) {
            $(".focus-btn").html("关注").removeClass("unvote"),
            Sb = !1,
            a && a.result && (a.result.exsit = 0, eb.update(a), $("#opArea").show(), $(".bar-info-text").show())
        }
    }),
    X = new gc({
        comment: "getHeartModel",
        cgiName: "/cgi-bin/bar/get_free_love",
        param: {
            plat: $.os.ios ? 3 : 2
        },
        processData: function(a) { ! a.retcode && a.result && (Tip.show("领心成功，在明星魅力榜查看总数。", {
                type: "ok"
            }), Z = !0, mqq.dispatchEvent("event_get_heart", {
                count: a.result.love_total
            },
            {
                domains: ["*.qq.com"]
            }))
        },
        error: function() {
            Tip.show("领心失败，请稍后重试", {
                type: "warning"
            })
        }
    }),
    W = new gc({
        comment: "sendHeartModel",
        cgiName: "/cgi-bin/bar/love_support",
        param: function() {
            return {
                plat: $.os.ios ? 3 : 2,
                bid: rb
            }
        },
        processData: function(a) {
            var b = $("#js_bar_charm_count"),
            c = b.closest(".header"),
            d = c.find(".charm-add-animation");
            a.retcode || (!a.result.err && b.length ? (c.addClass("sending-heart"), d.text("+5"), setTimeout(function() {
                c.removeClass("sending-heart"),
                b.text(Number(b.text()) + 5)
            },
            1200), mqq.dispatchEvent("event_send_heart", {
                send: !0,
                bid: rb
            },
            {
                domains: ["*.qq.com"]
            })) : 2 === a.result.err && (Y ? Z ? Tip.show("你所拥有的心不足", {
                type: "warning"
            }) : o() : (bc("exp_layer", {
                ver3: 2
            }), n())))
        },
        error: function() {
            Tip.show("你所拥有的心不足", {
                type: "warning"
            })
        }
    }),
    _ = new hc({
        comment: "topInfo",
        renderTmpl: window.TmplInline_barindex.bar_basic,
        renderContainer: $("#js_bar_basic"),
        renderTool: {
            numHelper: d
        },
        cgiName: "/cgi-bin/bar/page",
        param: function() {
            var a = {
                bid: rb,
                platform: Ub
            };
            return Pb && (a.greenhand = 1),
            a
        },
        processData: function(a, b) {
            function c(a) {
                return $.isArray(a) ? a.join(" / ") : a
            }
            var e, g, h, i, j, k, l, n, o, q, s, v = {
                bname: a.result.name,
                pic: a.result.pic
            },
            w = 0,
            x = mqq.QQVersion,
            y = !1,
            D = 0,
            F = document.documentElement.clientWidth || document.body.clientWidth,
            H = 14,
            J = 20,
            K = $(".header-cover");
            if (K.removeClass("header-cover-loading"), localStorage.setItem("barInfoForRecruit", JSON.stringify(v)), $("body").removeClass("waiting-render"), a.result && a.result.errno && (Tip.show("该部落已关闭", {
                type: "warning"
            }), setTimeout(function() {
                mqq.ui.popBack()
            },
            1e3)), e = a.result, Ob = e.category_name, q = e.movie_info, G = "明星" === Ob, e.isStarCategory = G, G && (p(), u()), Ab = "电影" === Ob && q, Bb = "电视剧" === Ob && q, Cb = "综艺" === Ob && q, Ab) {
                try {
                    o = localStorage.getItem("movieTicketMap")
                } catch(L) {
                    Db = {}
                }
                if (o) try {
                    Db = JSON.parse(o)
                } catch(L) {
                    Db = {}
                } else Db = {};
                this.renderTmpl = window.TmplInline_barindex.bar_special_basic,
                q ? (q.label || (q.label = [], q.area && q.label.push(q.area), q.main_genre && q.label.push(q.main_genre), q.sub_genre && (q.label = q.label.concat(q.sub_genre))), q.label = c(q.label), q.score ? (q.score = parseInt(q.score, 10).toFixed(1), q.scoreBarPosition = 17 * (Math.floor(parseInt(q.score, 10) + .5) - 10)) : (q.score = "暂无评分", q.noScore = !0, q.scoreBarPosition = "-170"), q.vertical_pic = q.vertical_pic ? q.vertical_pic.replace(/(?:_\w)?\.(jpg|jpeg)$/g, "_f.$1") : e.pic, q.leading_actor && (q.leading_actor = c(q.leading_actor)), q.description && (s = Math.floor((F - J) / H), q.description = "" + $.str.decodeHtml(q.description.replace(/^\s+/g, "")), q.descLines = Math.min(3, Math.ceil(q.description.length / s)))) : e.movie_info = {
                    score: "暂无评分",
                    noScore: !0,
                    scoreBarPosition: "-170"
                },
                e.intro && (e.intro = "" + $.str.decodeHtml(e.intro.replace(/^\s+/g, ""))),
                z = e.movie_id,
                1 === Number(Db[e.bid]) && (A = !0),
                e.hasTicket = A,
                e.fans = d(e.fans),
                K.addClass("special"),
                K.addClass(q.descLines ? "has-desc-line-" + q.descLines: "no-desc")
            } else Bb ? (this.renderTmpl = window.TmplInline_barindex.bar_special_teleplay, q.label || (q.label = [], q.area && q.label.push(q.area), q.main_genre && q.label.push(q.main_genre), q.sub_genre && (q.label = q.label.concat(q.sub_genre))), q.label = c(q.label), q.score ? (q.score = parseInt(q.score, 10).toFixed(1), q.scoreBarPosition = 17 * (Math.floor(parseInt(q.score, 10) + .5) - 10)) : (q.score = "暂无评分", q.noScore = !0, q.scoreBarPosition = "-170"), q.vertical_pic = q.vertical_pic ? q.vertical_pic.replace(/(?:_\w)?\.(jpg|jpeg)$/g, "_f.$1") : e.pic, q.leading_actor && (q.leading_actor = c(q.leading_actor)), q.director && (q.director = c(q.director)), q.description && (s = Math.floor((F - J) / H), q.description = "" + $.str.decodeHtml(q.description.replace(/^\s+/g, "")), q.descLines = Math.min(3, Math.ceil(q.description.length / s))), e.intro && (e.intro = "" + $.str.decodeHtml(e.intro.replace(/^\s+/g, ""))), z = e.movie_id, e.fans = d(e.fans), K.addClass("special teleplay")) : Cb && (this.renderTmpl = window.TmplInline_barindex.bar_special_variety, q.label || (q.label = [], q.area && q.label.push(q.area), q.main_genre && q.label.push(q.main_genre), q.sub_genre && (q.label = q.label.concat(q.sub_genre))), q.label = c(q.label), q.vertical_pic = q.vertical_pic ? q.vertical_pic: e.pic, q.presenter && (q.presenter = c(q.presenter)), q.description && (s = Math.floor((F - J) / H), q.description = "" + $.str.decodeHtml(q.description.replace(/^\s+/g, "")), q.descLines = Math.min(3, Math.ceil(q.description.length / s))), q.newdate && (q.newdate = "更新至" + q.newdate + "期"), e.intro && (e.intro = "" + $.str.decodeHtml(e.intro.replace(/^\s+/g, ""))), z = e.movie_id, e.fans = d(e.fans), K.addClass("special variety"));
            if (Nb = e.bar_class, e.barId = rb, e.isWeiXin = vb, e.isQQ = wb, Kb = e.pic || Kb, Lb = e.intro || Lb, Mb = e.name, S = 1 === Number(e.sign), Sb = 1 === Number(e.exsit), g = e.recommend) for (h = 0, j = g.length; j > h; h++) i = g[h],
            i.share = d(i.share),
            i.views = d(i.views);
            if (e.pic.indexOf("ugc.qpic.cn") > -1 && (e.pic += "120"), $(".header-cover-img").css("background-image", "url(" + e.cover + ")"), Hb = e.admin_ext, E = e.wxflag, Fb = e.name, Gb = e.fans, b && (($.os.ios || $.os.android) && mqq.compare("4.6") >= 0 && mqq.data.readH5Data({
                callid: 1,
                path: "/buluo",
                key: "followTipsMap"
            },
            function(a) {
                if (0 === a.ret) {
                    var b;
                    try {
                        b = JSON.parse(a.response.data)
                    } catch(c) {
                        b = {}
                    }
                    1 === b[rb] && (b[rb] = 0, mqq.data.writeH5Data({
                        callid: 1,
                        path: "/buluo",
                        key: "followTipsMap",
                        data: JSON.stringify(b)
                    },
                    function() {
                        m()
                    }))
                }
            }), I = {
                barName: e.name,
                category: e.category,
                categoryName: e.category_name
            },
            ad.setBarInfo(I)), document.title = $.str.decodeHtml(Fb), mqq && mqq.ui && mqq.ui.refreshTitle && mqq.ui.refreshTitle(), Zb = a.result.stargroup || 0, xb.setStarGroupFlag(Zb), gb.param.starid = R = a.result.starid, Zb) try {
                $("body").addClass("stargroup")
            } catch(L) {}
            $("#js_bar_top").removeClass("hide"),
            mb.param.keyword = C = Util.getHash("key") || e.key_word || e.name || "",
            B = e.flag,
            e.barTypeArr = f(B),
            r(),
            $("#js_fans_num").text(d(e.fans)),
            Qb || (Qb = !0, 0 === b ? ("0" !== x && $.os.ios && (Config.isOffline ? (Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 29), w += 21) : (Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 28), w += 14)), Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 20), Q.isd(7832, 47, 10, +new Date - window.pageStartTime, 1, null, "allWithCache", !0)) : 1 === b && ("0" !== x && $.os.ios && (Config.isOffline ? (Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 25), w += 7) : Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 24)), Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 21), Q.isd(7832, 47, 10, +new Date - window.pageStartTime, 1, null, "allWithCache", !1)), "0" !== x && $.os.ios && (x.split(".").length > 3 && (x = x.split("."), x = x[0] + "." + x[1] + "." + x[2]), "5.4.0" === x ? w += 7 : "5.3.2" === x ? w += 6 : "5.3.0" === x ? w += 5 : "5.2.1" === x ? w += 4 : "5.1.1" === x ? w += 3 : "5.0.0" === x ? w += 2 : -1 !== x.indexOf("4.7") && (w += 1), w && (k = +new Date, window.EnvInfo.getNetwork(function(a) {
                var b = 0;
                "wifi" === a ? b = 25 : "3g" === a ? b = 27 : "2g" === a && (b = 28),
                Q.isd(7832, 47, b, k - window.pageStartTime, w)
            }))), "0" !== x ? $.os.ios ? D = 5 : $.os.android && (D = 7) : $.os.ios ? D = 6 : $.os.android && (D = 8), y || (Q.isd(7832, 47, 31, +new Date - window.pageStartTime, D), y = !0)),
            /\bMicroMessenger\/[\d\.]+/.test(navigator.userAgent) && (l = function() {
                var a = {
                    link: "http://m.qzone.com/l?g=441&from=wechat&_bid=128&_wv=1027&bid=" + rb,
                    title: "兴趣部落-" + Mb,
                    desc: Lb
                };
                n = window.WeixinJSBridge,
                e.pic && (a.img_url = e.pic),
                n.on("menu:share:timeline",
                function() {
                    n.invoke("shareTimeline", a,
                    function(a) {
                        n.log(a.err_msg)
                    })
                }),
                n.on("menu:share:appmessage",
                function() {
                    n.invoke("sendAppMessage", a,
                    function(a) {
                        n.log(a.err_msg)
                    })
                })
            },
            window.WeixinJSBridge ? l() : document.addEventListener("WeixinJSBridgeReady",
            function() {
                l()
            })),
            b > 0 && a.result.push_banner && lc.show(a.result.push_banner.banner_pic, a.result.push_banner.banner_url),
            0 === e.greenhand && t(),
            P = a.result.is_qun_admin
        },
        complete: function(a) {
            if (!Rb && 0 === a.retcode) if (Rb = 1, mqq.compare("5.0") >= 0) setTimeout(function() {
                mqq.dispatchEvent("updateFocusList", a.result || {},
                {
                    domains: ["*.qq.com"]
                })
            },
            200);
            else try {
                window.localStorage.setItem("recentVisitedData", JSON.stringify(a))
            } catch(b) {
                window.localStorage.clear(),
                window.localStorage.setItem("recentVisitedData", JSON.stringify(a))
            }
            Ab && z > 0 && (this._isFetchingMovie || (this._isFetchingMovie = !0, c(function(a, b) {
                $.ajax({
                    url: "http://s.p.qq.com/cgi-bin/coupon_q/dianying/movie_query.fcg?callback=?",
                    type: "GET",
                    data: {
                        movie_id: z,
                        cmd: 100,
                        maplat: a,
                        maplng: b,
                        nlct: 1
                    },
                    dataType: "jsonp",
                    success: function(a) {
                        a.data && 1 === Number(a.data.canbuyticket) ? (A = !0, Db[rb] = 1, $("#js_op_movie").removeClass("btn-see").addClass("btn-buy").html("立即购票")) : (A = !1, delete Db[rb]);
                        try {
                            localStorage.setItem("movieTicketMap", JSON.stringify(Db))
                        } catch(b) {}
                    },
                    error: function(a) {
                        console.log(a)
                    }
                })
            })))
        },
        events: function() {
            var a, b, c = $("#js_top_focus_nav"),
            d = $("#btn_focus_tribe"),
            e = $("#uiTestNav"),
            f = $("#js_bar_basic"),
            g = f.height();
            d.on("tap",
            function() {
                U.refresh(),
                bc(3, {
                    ver3: Yb
                }),
                c.hide()
            }),
            a = $(window),
            b = function() { (!$.os.ios || window.isLowerIos) && (e[0].style.position = document.body.scrollTop > g ? "fixed": "static")
            },
            a.on("scroll", b)
        },
        error: function(a) {
            this.getCache(0) || 1e5 === a.retcode || (Tip.show("获取部落信息失败[" + a.retcode + "]", {
                type: "warning"
            }), $("#js_bar_basic").hide())
        }
    }),
    ab = new hc({
        comment: "barTop",
        renderTmpl: window.TmplInline_barindex.bar_top,
        renderContainer: "#js_bar_top",
        processData: function(a) {
            var b, c, d, e;
            if (a.bid = rb, a.result.recommend && a.result.recommend.length) for (b = 0, c = a.result.recommend.length; c > b; b++) d = a.result.recommend[b],
            (100 === d.type || 200 === d.type || 400 === d.type || 201 === d.type || 401 === d.type) && (e = a.result.recommend.splice(b, 1), e[0].isTop = 1, Ib = Ib.concat(e), xb.setTopListSpecialPool(Ib), b--, c--);
            else $("#js_bar_top").addClass("hide-top-border")
        },
        events: function() {
            var a, b, c;
            $("#js_bar_top").on("tap",
            function(d) {
                var e = d.target;
                return $(e).hasClass("top-related-wrap") ? (a = e.getAttribute("data-bid"), b = "http://xiaoqu.qq.com/mobile/barindex.html#bid=" + a + "&source=barindex", void Util.openUrl(b, !0, 0)) : ("A" !== e.tagName && (e = e.parentNode), void("A" === e.tagName && ($(e.parentNode.parentNode).addClass("list-active"), bc(10), b = e.getAttribute("data-href"), c = e.getAttribute("data-tab"), setTimeout(function() {
                    b && (Util.openUrl("http://xiaoqu.qq.com/mobile/" + b + "&source=barindex&stargroup=" + Zb, !0, 0), d.preventDefault()),
                    "tribe" === c && (bc("", {
                        module: "tribe_hp",
                        action: "Clk_allsections"
                    }), Util.openUrl(location.href + "&uibarhide=1&uinav=4", !0, 0), d.preventDefault()),
                    $(e.parentNode.parentNode).removeClass("list-active")
                },
                100))))
            })
        },
        error: function(a) {
            this.getCache(0) || Tip.show("获取帖子列表失败[" + a.retcode + "]", {
                type: "warning"
            })
        }
    }),
    bb = new hc({
        comment: "barTopMenu",
        renderTmpl: window.TmplInline_barindex.bar_top_menu,
        renderContainer: "#js_bar_top_menu",
        processData: function(a) {
            a.bid = rb
        },
        events: function() {
            $("#js_bar_top_menu").on("tap", ".festival-bar-top-menu",
            function() {
                var c = $(this);
                c.addClass("list-active"),
                Q.monitor(508674),
                setTimeout(function() {
                    c.removeClass("list-active"),
                    22631 === Number(rb) && a() && (b() ? (bc("#Clk_acspace"), Util.openUrl("http://xiaoqu.qq.com/mobile/spring_rank.html?bid=" + rb, !0)) : Util.openUrl("http://xiaoqu.qq.com/mobile/activity/actotal/index.html?_wv=1025&from=chunwan", !0))
                },
                400)
            })
        },
        complete: function() {}
    }),
    db = new hc({
        comment: "barStarInfo",
        renderTmpl: window.TmplInline_barindex.bar_star_info,
        renderContainer: "#js_bar_star_info",
        processData: function(a) {
            var b = a.result.starinfo;
            b && (b.pic || (b.pic = "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/star_info_icon.png"))
        },
        events: function() {
            $("#js_bar_star_info").on("tap",
            function(a) {
                var b, c = a.target,
                d = $(c).closest(".star-info-container");
                d && (b = d.attr("data-url"), Util.openUrl(b + "?starid=" + R, !0))
            })
        }
    }),
    ["10817"].indexOf(rb) > -1 && (ec = !0),
    cb = new hc({
        comment: "barUItestTab",
        renderTmpl: window.TmplInline_barindex.bar_uitest_tab,
        renderContainer: $("#uiTestNav"),
        processData: function(a, b) {
            var d, e, f, g, h;
            if (a = a.result, a.isActBar = dc, a.bid = rb, a.uiNav = $b, a.platform = Ub, $(".ui-test-nav-wrap").show(), "array" === $.type(a.ls) || (a.ls = []), g = a.ls, ("地区" === a.category_name || "城市" === a.category_name) && a.ls.length && 1 === b) {
                var i = localStorage.getItem("user_location_city"),
                j = localStorage.getItem("user_location_city_time");
                for (e = 0; e < a.ls.length; e++) if ("同城活动" === a.ls[e].name) {
                    N = a.ls[e],
                    a.ls.splice(e, 1);
                    break
                }
                N && "0" !== mqq.QQVersion && (!i || !j || Number(j) - Date.now() > 36e5 ? c(function(a, b) {
                    var c = {
                        type: "POST",
                        url: "/cgi-bin/bar/user/my_lbs",
                        param: {
                            lat: a,
                            lon: b
                        },
                        succ: function(a) {
                            if (0 === a.retcode) {
                                var b = a.result.city;
                                b.indexOf(Mb) > -1 && (g.splice(1, 0, N), O = !0, cb.refresh(), localStorage.setItem("user_location_city", b), localStorage.setItem("user_location_city_time", Date.now()))
                            }
                        }
                    };
                    DB.cgiHttp(c)
                }) : i.indexOf(Mb) > -1 && (O = !0, g.splice(1, 0, N)))
            }
            if (a.ls.sort(function(a, b) {
                return a.mid - b.mid
            }), a.ls.length > 0) for (e = 0, f = a.ls.length; f > e; e++) a["tab" + a.ls[e].mid] = a.ls[e],
            a.ls[e].icon_pre in zb && (a.ls[e].icon_pre = zb[a.ls[e].icon_pre]),
            a.ls[e].url || (bc("exp_guide"), a.ls[e].url = "http://xiaoqu.qq.com/mobile/barindex_game.html?_bid=128&title=" + encodeURIComponent(a.ls[e].name || "") + "&menuid=" + a.ls[e].id + "&bid=" + rb),
            d = a.ls[e].url.replace(/{{id}}/g, a.ls[e].id).replace(/{{bid}}/g, a.ls[e].bid).replace(/&amp;/g, "&"),
            h = new ic({
                comment: "mytab" + a.ls[e].mid + "Link",
                newWindow: 1
            }),
            d.indexOf("barindex_video") > -1 && bc("exp_video"),
            a.ls[e] && a.ls[e].name.indexOf("大神") > -1 && bc("exp_god"),
            h.url = d,
            qb.add(".myTab_" + a.ls[e].mid + "_link", h)
        },
        events: function() {
            $.os.ios && $(".publish-btn-container").on("touchmove",
            function(a) {
                a.preventDefault()
            }),
            $(".publish-btn, .publish-btn-float").on("tap",
            function(a) {
                var b = this;
                a.preventDefault(),
                a.stopPropagation(),
                bc(6),
                $(b).addClass("active"),
                setTimeout(function() {
                    $(b).removeClass("active"),
                    q()
                },
                0)
            }),
            this.renderContainer.on("tap",
            function(a) {
                var b = $(a.target); (b.hasClass("myTab_4") || b.hasClass("myTab_4_link")) && bc("tab4_click"),
                "视频" === b.text() && bc("Clk_video"),
                "攻略" === b.text() && bc("Clk_guide"),
                b.text().indexOf("大神") > -1 && bc("Clk_god")
            })
        },
        complete: function(a, b) {
            var c, d, e = 4,
            f = 0;
            for (c = 0; c < a.result.ls.length; c++) a.result.ls[c].mid <= 4 ? e -= 1 : f += 1;
            if (f > 0 && (mqq.iOS && mqq.ui.setWebViewBehavior({
                swipeBack: 0
            }), $("#uiTestNav .new-tab-list").css("width", (document.documentElement.clientWidth || document.body.clientWidth) / 4.5 * (a.result.ls.length + e)), setTimeout(function() {
                $("html, body").scrollTop(1),
                d = new IScroll("#uiTestNav", {
                    scrollX: !0,
                    scrollY: !1
                })
            },
            0)), a = a.result, $.os.ios || (document.body.style.overflowY = "scroll"), 1 === b && (a.ls && (a.best = a.best || {},
            a.tribe = a.tribe || {},
            "视频" === a.best.name && 5 === Number($b) ? $b = 7 : "视频" === a.tribe.name && 6 === Number($b) && ($b = 7), 1 === a.ls.length ? 5 === Number($b) && qb.init(_b[4]) : Number($b) > 4 && qb.init(_b[$b - 1])), qb.rock(), vb && $.ajax({
                url: "/cgi-bin/bar/user/mybarlist_v2",
                data: {
                    num: 1,
                    start: 0,
                    t: (new Date).getTime()
                },
                success: function(a) {
                    var b = a.result.point;
                    b > 0 && ($("#m_point").html(b), $("#m_point").show()),
                    $("#m_point")[0].setAttribute("data-point", b),
                    $("#m_point")[0].setAttribute("data-sys", a.result.point_sys),
                    $("#m_point")[0].setAttribute("data-reply", a.result.point_reply)
                }
            })), 0 === a.big_admin_num) {
                var g = JSON.parse(localStorage.getItem("apply_admin_tag_cache"));
                g && g[rb] || $("#tab_hof").addClass("apply")
            }
        }
    }),
    cb.feeded = 1,
    eb = new hc({
        comment: "barSign",
        renderTmpl: window.TmplInline_barindex.bar_sign,
        renderContainer: "#signArea",
        processData: function(a) {
            var b, c = a.result;
            c.isWeiXin = vb,
            c.isQQ = wb,
            (!Ab || Bb || Cb) && (1 === a.result.exsit || vb ? (D = a.result.level.level, b = h(a.result.level, a.result["continue"]), b.continueDays = c["continue"], c.scoreInfo = b, c.isStarGroup = Zb, c.isStarCategory = G, $(".focus-btn").html("取消关注").addClass("unvote")) : $(".focus-btn").html("关注").removeClass("unvote")),
            this.topFastContent = a.result
        },
        events: function() {
            var a, b = this;
            $("#js_bar_basic").on("tap",
            function(c) {
                var d, e, f = c.target;
                if ("js_bar_vote_btn" === f.id || "js_movie_focus_btn" === f.id) return U.refresh(),
                void bc(3, {
                    ver3: Yb
                });
                if ("js_op_movie" === f.id) return e = $(f).data("mediaexist"),
                void(Bb ? e ? Util.openUrl(Eb + "barindex_teleplay.html?bid=" + rb + "&type=episode", !0, 0) : Util.openUrl("http://m.v.qq.com/search.html?keyWord=" + encodeURIComponent($.str.decodeHtml(Fb).replace(/\s/g, " ")), !0, 0) : Cb ? e ? Util.openUrl(Eb + "barindex_variety.html?bid=" + rb + "&type=episode", !0, 0) : Util.openUrl("http://m.v.qq.com/search.html?keyWord=" + encodeURIComponent($.str.decodeHtml(Fb).replace(/\s/g, " ")), !0, 0) : $(f).hasClass("btn-see") ? (d = $(f).data("pid"), d ? Util.openDetail({
                    bid: rb,
                    pid: d
                },
                null, 0) : Util.openUrl("http://m.v.qq.com/search.html?keyWord=" + encodeURIComponent($.str.decodeHtml(Fb).replace(/\s/g, " ")), !0, 0), bc("Clk_film")) : $(f).hasClass("btn-buy") && (Util.openUrl("http://web.p.qq.com/qqmpmobile/coupon/nearby2.html?_bid=108&show_movie=1&_wv=5123&maplat=" + x + "maplng=" + y + "&shop_id=" + z, !0, 0), bc("Clk_ticket")));
                if ("signBtn" === f.id) return $(f).hasClass("disable") ? !1 : $(f).hasClass("signed") ? void j() : l();
                if ("js_go_intro" === f.id || "js_go_intro_arrow" === f.id || $(f).hasClass("intro-wrapper")) return void Util.openUrl(Eb + "barindex_entertainment_intro.html?bid=" + rb, !0, 0);
                if ("sendHeartBtn" === f.id || "sendHeartBtn" === f.parentNode.id) return bc("Clk_send", {
                    ver3: 2
                }),
                void W.rock();
                if ("js_movie_sign_btn" === f.id) {
                    if ($(f).hasClass("btn-movie-signed")) return;
                    return l()
                }
                if ($(f).closest(".info-grade-wrap").length && R) return k();
                if (bc(2), Ab || Bb || Cb) {
                    if ("js_bar_logo" === f.id || "js_bar_logo" === f.parentNode.id) {
                        if (localStorage && b.topFastContent) try {
                            localStorage.setItem("topFastContent", JSON.stringify(b.topFastContent))
                        } catch(g) {}
                        return a = Util.getHash("scene"),
                        a = a ? "&scene=" + a: "",
                        void Util.openUrl(Eb + "bar_rank.html?bid=" + rb + a, !0)
                    }
                    return "js_bar_sign_btn" === f.id ? void 0 : void Util.openUrl(Eb + "barindex_entertainment_intro.html?bid=" + rb + "&type=" + (Ab ? "movie": Bb ? "teleplay": Cb ? "variety": ""), !0, 0)
                }
                if (!vb) {
                    if (localStorage && b.topFastContent) try {
                        localStorage.setItem("topFastContent", JSON.stringify(b.topFastContent))
                    } catch(g) {}
                    a = Util.getHash("scene"),
                    a = a ? "&scene=" + a: "",
                    Util.openUrl(Eb + "bar_rank.html?bid=" + rb + a, !0)
                }
            }),
            $(".focus-btn").on("tap",
            function() {
                $(this).hasClass("unvote") ? V.refresh() : (U.refresh(), bc(3, {
                    ver3: Yb
                }))
            })
        },
        complete: function(a, b) {
            1 === b && 1 === a.result.sign && a.result.isStarCategory && bc("exp_send", {
                ver3: 2
            })
        }
    }),
    window.honourHelper._zeroMaker = e,
    fb = new ic({
        url: "http://xiaoqu.qq.com/mobile/barindex_hof.html",
        newWindow: 1,
        param: {
            _bid: 128,
            bid: rb,
            source: "tribe"
        }
    }),
    gb = new ic({
        url: "http://xiaoqu.qq.com/mobile/albums.html",
        newWindow: 1,
        param: {
            _bid: 128,
            bid: rb,
            starid: R
        }
    }),
    hb = new ic({
        url: "http://xiaoqu.qq.com/mobile/more.html",
        newWindow: 1,
        param: {
            _bid: 128,
            bid: rb,
            barname: Fb,
            act: dc,
            source: "tribe"
        }
    }),
    ib = new ic({
        comment: "mytab1Link",
        newWindow: 1
    }),
    jb = new ic({
        comment: "mytab2Link",
        newWindow: 1
    }),
    kb = new ic({
        comment: "mytab3Link",
        newWindow: 1
    }),
    lb = new ic({
        comment: "mytab4Link",
        newWindow: 1
    }),
    mb = new ic({
        comment: "relativegroupLink",
        url: "http://xiaoqu.qq.com/mobile/relativegroup.html",
        newWindow: 1,
        param: {
            _bid: 128,
            keyword: C,
            bid: rb,
            barname: Fb,
            act: dc,
            source: "tribe"
        }
    }),
    nb = new ic({
        url: "http://xiaoqu.qq.com/mobile/barindex_best.html",
        newWindow: 1,
        param: {
            _bid: 128,
            bid: rb,
            barname: Fb,
            source: "tribe"
        }
    }),
    ob = new hc({
        renderContainer: fc,
        comment: "emptyRenderModel"
    }),
    qb = new jc({}),
    qb.add("#tab_all", ob),
    qb.add("#tab_hof", fb),
    qb.add("#tab_album", gb),
    vb ? qb.add("#tab_more", hb) : qb.add("#tab_qun", mb),
    qb.add("#tab_best", nb),
    qb.add(".tab_qun_link", mb),
    $b > 1 && 10 > $b && qb.init(_b[$b - 1] || ""),
    qb.beforetabswitch(function(a) {
        if (window._s_selector = a, "#tab_hof" === a && $(a).hasClass("apply")) {
            var b = JSON.parse(localStorage.getItem("apply_admin_tag_cache")) || {};
            b[rb] = 1,
            localStorage.setItem("apply_admin_tag_cache", JSON.stringify(b)),
            $(a).removeClass("apply"),
            fb.param.showrecruit = 1
        }
    }),
    qb.ontabswitch(function(b, c) {
        var d, e = $("#js_bar_top"),
        f = $("#js_bar_basic");
        H = b,
        ".tab_video" === b && bc("", {
            module: "custom_tab",
            action: "Clk_tab",
            ver3: 4
        }),
        d = $(this).scrollTop(),
        d > 800 ? Vb.removeClass("hide-refresh-btn") : Vb.addClass("hide-refresh-btn"),
        $(".indexpage1").show(),
        $(".indexpage2").hide(),
        $(".indexpage6").hide(),
        $("#js_best_top").hide(),
        $("#js_menu_list").hide(),
        db.show(),
        e.show(),
        f.show(),
        Vb.show(),
        a() && 22631 === Number(rb) && bb.show(),
        $("#js_bar_list").show(),
        $("#js_menu_list").hide(),
        $(".indexpage7").hide(),
        $(".indexpage8").hide(),
        ".myTab_1" === b || ".myTab_1_link" === b ? bc("", {
            module: "custom_tab",
            action: "Clk_tab",
            ver3: 4
        }) : (".myTab_2" === b || ".myTab_2_link" === b) && bc("", {
            module: "custom_tab",
            action: "Clk_tab",
            ver3: 2
        }),
        "switch" === c && bc(b, {
            ver3: 1
        })
    }),
    J = null,
    Refresh && Refresh.init({
        dom: document.body,
        reload: function() {
            return J && clearTimeout(J),
            J = setTimeout(function() {
                xb.refresh(),
                window.pollRefreshUi.reset(),
                Q.monitor(429257)
            },
            400),
            window.setTimeout(function() {
                Refresh.hide()
            },
            1e3),
            bc(7),
            !0
        },
        usingPollRefresh: 1
    }),
    _.feed(ab),
    _.feed(eb),
    _.feed(db),
    _.feed(cb),
    _.feed(bb),
    pb = new kc,
    pb.add(_),
    pb.add(eb),
    pb.add(db),
    pb.add(ab),
    pb.add(cb),
    pb.add(bb),
    $(window).on("hashchange",
    function() {
        var a = Util.getHash("target"),
        b = Util.getHash("poi");
        a || Publish.destroy(),
        b || !Publish || Publish.isNative || Publish.hidePoiList()
    }),
    window.mqq && mqq.addEventListener && (mqq.addEventListener("avt_refresh_page",
    function(a) {
        var b = $("#" + a.data.pid);
        b.length && ("item_close" === a.type ? b.remove() : b.find(".people-num").html(a.data.enroll))
    }), mqq.addEventListener("event_focus_tribe",
    function(a) {
        "barrank" === a.from && (a.focus ? Ab || Bb || Cb ? ($("#js_movie_focus_btn").addClass("btn-movie-sign").removeClass("btn-subscribe").attr("id", "js_movie_sign_btn").html("签到"), S && $("#js_movie_focus_btn").addClass("btn-movie-signed").html('<i class="subscribed-icon"></i>已签')) : ($("#opArea").hide(), $(".bar-info-text").hide(), _.refresh()) : Ab || Bb || Cb ? $("#js_movie_sign_btn").removeClass("btn-movie-sign btn-movie-signed").addClass("btn-subscribe").attr("id", "js_movie_focus_btn").html("关注") : ($("#opArea").show(), $(".bar-info-text").show(), $(eb.renderContainer).html("")))
    })),
    ActionButton.setCallback(function() {
        K = {
            admin_ext: 0,
            posts: ActionButton.getUploadVideo(rb)
        };
        var a = Tmpl(window.TmplInline_barindex.bar_list_text, K, window.honourHelper).toString();
        $("#js_uploading_video").html(a)
    }),
    window.formatDuration = s,
    mqq.addEventListener("qbrowserTitleBarClick",
    function() {
        var a = $("body"),
        b = a.scrollTop(),
        c = $("#js_bar_wrapper");
        Util.scrollElTop(b, a, c)
    });
    var lc = {
        apiSupport: mqq && mqq.data && mqq.support("mqq.data.readH5Data") && mqq.support("mqq.data.writeH5Data"),
        _showCallback: function(a, b, c) {
            var d = $(".wechat-banner"),
            e = document.getElementById("banner-img"),
            f = document.documentElement.clientWidth || document.body.clientWidth,
            g = this;
            e.onload = function() {
                var a = this.naturalHeight || this.height,
                b = this.naturalWidth || this.width,
                c = a / b * f || 40;
                d.removeClass("hide").height(c),
                bc("exp_global")
            },
            e.onerror = function() {
                console.error("Barindex Banner Img error, url =", a)
            },
            setTimeout(function() {
                e.src = a
            },
            200),
            $("body").on("tap", ".wechat-banner",
            function() {
                b = b.replace("&amp;", "&").replace("#bid#", rb),
                bc("Clk_global"),
                g._write(c),
                setTimeout(function() {
                    d.addClass("hide"),
                    Util.openUrl(b, !0)
                },
                500)
            })
        },
        _write: function(a) {
            var b = {
                imgId: a
            },
            c = this;
            if (c.apiSupport) mqq.data.writeH5Data({
                callid: 1024,
                path: "/buluo",
                key: "barindexBannerFlag",
                data: JSON.stringify(b)
            },
            function(a) {
                console.log("barindexBannerFlag writeH5Data: data =", b, "ret =", a)
            });
            else try {
                window.localStorage.setItem("buluo-barindexBannerFlag", JSON.stringify(b))
            } catch(d) {
                console.error(d)
            }
        },
        _readlocalStorage: function(a, b, c) {
            var d, e, f = this;
            try {
                d = window.localStorage.getItem("buluo-barindexBannerFlag"),
                e = JSON.parse(d),
                console.log("LocalStorage中的banner标记数据", e)
            } catch(g) {
                console.error(g)
            }
            return e && e.imgId && e.imgId === String(c) ? void console.log("barindex banner已点击不再显示") : void f._showCallback(a, b, c)
        },
        show: function(a, b) {
            if (a && b) {
                var c = this,
                d = a.split("/").pop();
                return c.apiSupport ? void mqq.data.readH5Data({
                    callid: 1024,
                    path: "/buluo",
                    key: "barindexBannerFlag"
                },
                function(e) {
                    if (console.log("barindexBannerFlag readH5Data: ret =", e), 0 !== e.ret || !e.response) return void c._showCallback(a, b, d);
                    var f;
                    try {
                        f = JSON.parse(e.response.data)
                    } catch(g) {
                        console.log(g)
                    }
                    return f && f.imgId && f.imgId === String(d) ? void console.log("barindex banner已点击不再显示") : void c._showCallback(a, b, d)
                }) : void c._readlocalStorage(a, b, d)
            }
        }
    };
    return {
        init: w
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : a.TmplInline_ad = b()
} (this,
function() {
    var a = {},
    b = function(a) {
        function b(b) {
            return c("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function c(a) {
            return "undefined" == typeof a ? "": a
        }
        a = a || {};
        var d = b("img"),
        e = b("imgHandle"),
        f = b("type"),
        g = b("uiType"),
        h = b("title"),
        i = b("desc"),
        j = b("id"),
        k = b("imgHeight"),
        l = (b("banner"), "");
        return l += "",
        d = e.formatThumb(d, !0, 80, 80),
        d = d[0],
        "barindex" === f ? (l += " ", 1 === g ? (l += ' <div class="detail-text-content haspic"> <div class="text-container"> <h3 class="text">', l += c(h), l += '<span class="list-ad-mark-top">推广</span></h3> <div class="list-content">', l += c(i), l += '</div> </div> <div class="act-img img-gallary"> <img class="ad-img" src="', l += c(e.getThumbUrl(d)), l += '" style="width:', l += c(d.width), l += "px; height:", l += c(d.height), l += "px; margin-left:", l += c(d.marginLeft), l += "px; margin-top:", l += c(d.marginTop), l += 'px;" /> </div> </div> ') : 2 === g ? (l += ' <div class="detail-text-content haspic"> <div class="text-container"> <div class="act-img img-gallary"> <img class="ad-img" src="', l += c(e.getThumbUrl(d)), l += '" style="width:', l += c(d.width), l += "px; height:", l += c(d.height), l += "px; margin-left:", l += c(d.marginLeft), l += "px; margin-top:", l += c(d.marginTop), l += 'px;" /> </div> <h3 class="text"><span class="ad-mark-new">推广</span>', l += c(h), l += '</h3> <div class="list-content">', l += c(i), l += "</div> </div> </div> ") : (l += ' <div class="gdt-ad-wrapper"> <div class="detail-text-content haspic"> <div class="text-container"> <h3 class="text">', l += c(h), l += '</h3> <div class="list-content">', l += c(i), l += '</div> </div> <div class="act-img img-gallary"> <img class="ad-img" src="', l += c(e.getThumbUrl(d)), l += '" style="width:', l += c(d.width), l += "px; height:", l += c(d.height), l += "px; margin-left:", l += c(d.marginLeft), l += "px; margin-top:", l += c(d.marginTop), l += 'px;" /> </div> <span class="ad-ribbon">推广</span> </div> </div> '), l += " ") : "comment" === f ? (l += ' <div class="gdt-ad-wrapper comment-wrapper"> <div class="user-avatar" data-profile-uin="', l += c(d || ""), l += '"> <img src="', l += c(e.getThumbUrl(d)), l += '"/> </div> <div class="name-wrap"><span class="author user-nick ad-title">', l += c(h), l += '</span></div> <div class="content-wrapper"> <div class="content" id="detail_comment_', l += c(j), l += '">', l += c(i), l += '</div> </div> </div> <span class="ad-ribbon">推广</span> ') : "recommend" === f ? (g = 2, l += " ", 1 === g ? (l += ' <div class="list-content"> <p class="grouptitle">', l += c(h), l += '</p> <div class="groupbody">', l += c(i), l += '</div> </div> <span class="ad-ribbon">推广</span> ') : 2 === g && (l += ' <div class="ad-title "><div class="ad-title-txt">赞助商提供</div><div class="ad-title-dashed"></div></div> <div class="ad-mark">推广</div> <div class="ad-banner-img-wrapper" style="height: ', l += c(k), l += 'px"> <img src="', l += c(e.getThumbUrl(d)), l += '" class="ad-banner-img"/> </div> '), l += " ") : "10" === f && (l += ' <div class="list-content ad-banner" style="background-image: url(http://xiaoqu.qq.com/mobile/img/personal_banner.jpg?1416541398);"> </div> <span class="ad-ribbon">推广</span> '),
        l += " "
    };
    return a.ad = "TmplInline_ad.ad",
    Tmpl.addTmpl(a.ad, b),
    a
}),
function(a, b) {
    a.ad = b(a.TmplInline_ad)
} (this,
function(a) {
    function b(a) {
        var b, c, d = Login.getUin(),
        e = {
            page_type: 0,
            _uin: Number(d),
            bid: Util.getHash("bid") || Util.queryString("bid"),
            _datafmt: 0,
            _charset: 1
        },
        f = {
            poscount: 1,
            posid: "recommend" === a ? k[a][0] : k[a],
            adcount: "1",
            datatype: 2,
            outformat: 0
        },
        g = {
            wapos: $.os.android ? "ADR": $.os.ios ? "IPH": "",
            wapver: "1"
        },
        h = {};
        mqq.compare("4.5") >= 0 && mqq.device.getDeviceInfo(function(a) {
            g.model = $.os.android ? a.model: $.os.ios ? a.modelVersion: "",
            h.c_device = g.model,
            h.c_osver = a.systemVersion
        });
        try {
            b = localStorage.getItem(d + "-lat"),
            c = localStorage.getItem(d + "-lon")
        } catch(i) {}
        return b && c && (h.lat = b, h.lng = c),
        g.req = h,
        f.ext = g,
        e._param = JSON.stringify(f),
        e
    }
    function c(a, c) {
        console.log(a, "准备拉取CGI广告数据"),
        DB.cgiHttp({
            url: "/cgi-bin/bar/extra/get_ads",
            param: b(a),
            succ: function(b) {
                if (0 !== b.retcode || !b.result || void 0 !== b.result.ret && 0 !== b.result.ret || $.isEmptyObject(b.result)) return void console.log(a, "没有广告数据");
                var e, f, g, i, n = 1 != b.result.ads_from;
                if (n) {
                    if (e = b.result.data, !e) return void console.log(a, "没有广告数据");
                    if (f = e[k[a]], "recommend" === a && (f = e[k[a][0]], m = 2, f || (f = e[k[a][1]], m = 1)), !(f && 0 === f.ret && f.list && f.list.length > -1)) return void console.log(a, "没有广告数据");
                    g = f.list[0];
                    var o = f.cfg,
                    p = o.id;
                    if (i = {
                        id: p,
                        type: a,
                        uiType: "barindex" === a ? l: m,
                        cl: g.cl,
                        title: h(g.txt),
                        desc: h(g.desc),
                        img: g.img || g.img_s || g.img2,
                        rl: g.rl,
                        apurl: g.apurl
                    },
                    "recommend" === a) {
                        var q = $(document).width() - 20;
                        i.imgHeight = Math.round(166 * q / 582)
                    }
                } else g = b.result,
                i = {
                    id: g.id,
                    type: a,
                    uiType: l,
                    title: h(g.title),
                    desc: h(g.desc),
                    img: g.pic,
                    rl: g.link
                };
                i.img = [{
                    url: i.img,
                    h: 90,
                    w: 90
                }],
                console.log(a, "CGI拉取完广告数据"),
                j[a].data = i,
                j[a].hasData = !0,
                j[a].container && d(a, c)
            },
            err: function() {
                f(j[a].container)
            }
        })
    }
    function d(b, c) {
        if (!j[b].data) return void(j[b].hasData && (console.log(b, "暂时无广告数据"), f(j[b].container)));
        var d, h = j[b].data,
        i = j[b].container;
        "recommend" === b && 2 === m && (d = $("#recommend-list"), d.find(".gdt-ad").hide(), d.after('<div class="gdt-ad-banner"></div>'), i = j[b].container = $(".gdt-ad-banner")),
        Tmpl(a.ad, h).update(i),
        i.data("href", h.rl),
        i.show(),
        "recommend" === b && 1 === m && (c = c || 3, console.log(b, "隐藏第", c - 1, "个推荐帖子（从0开始）"), $("li", "#recommend-list .recommend-post-list").eq(c - 1).hide()),
        console.log(b, "已展示广告"),
        e(i, b),
        p.exposure(h.apurl, b),
        p.isd(b),
        g(b)
    }
    function e(a, b) {
        a.on("tap",
        function(a) {
            var c = $(a.currentTarget),
            d = c.data("href");
            p.tdw("click", b),
            Util.openUrl(d + (d.indexOf("?") > 0 ? "": "?") + "&_wv=4", !0),
            a.stopPropagation()
        })
    }
    function f(a) {
        a && a.remove()
    }
    function g(a) {
        j[a] = {
            container: null,
            hasData: !1,
            data: null
        }
    }
    function h(a) {
        return a ? a.replace("&nbsp;", " ") : a
    }
    function i(a) {
        if (!a) return ! 1;
        var b = $(a),
        c = b.offset();
        return c ? c.top + c.height > 10 : !1
    }
    var j = {
        barindex: {
            container: null,
            hasData: !1,
            data: null
        },
        comment: {
            container: null,
            hasData: !1,
            data: null
        },
        recommend: {
            container: null,
            hasData: !1,
            data: null
        }
    },
    k = {
        barindex: "7000703150186964",
        comment: "9050801025398385",
        recommend: ["4090805065898364"]
    },
    l = 2,
    m = 2,
    n = $.os.android ? "android": $.os.ios ? "ios": "unknown",
    o = {},
    p = {
        startTime: 0,
        endTime: 0,
        map: {
            "2g": {
                barindex: {
                    android: 0,
                    ios: 1
                },
                comment: {
                    android: 2,
                    ios: 3
                },
                recommend: {
                    android: 4,
                    ios: 5
                }
            },
            "3g": {
                barindex: {
                    android: 6,
                    ios: 7
                },
                comment: {
                    android: 8,
                    ios: 9
                },
                recommend: {
                    android: 10,
                    ios: 11
                }
            },
            wifi: {
                barindex: {
                    android: 12,
                    ios: 13
                },
                comment: {
                    android: 14,
                    ios: 15
                },
                recommend: {
                    android: 16,
                    ios: 17
                }
            }
        },
        _init: function() {
            this.endTime = Date.now()
        },
        _getNetwork: function(a) {
            var b = ""; ($.os.ios || $.os.android) && mqq.compare("4.6") >= 0 ? mqq.device.getNetworkType(function(c) {
                var d = {
                    1 : "wifi",
                    2 : "2g",
                    3 : "3g",
                    4 : "unknown"
                };
                b = d[c],
                b = "unknown" === b ? "wifi": b,
                a(b)
            }) : b = "unknown",
            b = "unknown" === b ? "wifi": b,
            a(b)
        },
        isd: function(a) {
            this._init();
            var b = this;
            this._getNetwork(function(c) {
                if (b.map[c]) {
                    var d = b.map[c][a][n];
                    "number" == typeof d && (console.log("env =", d), Q.isd(7832, 47, 29, b.endTime - b.startTime, 1, null, d))
                }
            })
        },
        exposure: function(a, b) {
            if (a) {
                var c = new Image;
                c.src = a,
                c = null
            }
            this.tdw("expose", b)
        },
        tdw: function(a, b) {
            var c = {
                obj1: Util.getHash("pid") || Util.queryString("pid"),
                ver1: Util.queryString("bid") || Util.getHash("bid"),
                ver6: $.os.ios ? 2 : $.os.android ? 1 : ""
            },
            d = {
                barindex: "post_list",
                comment: "post_detail",
                recommend: "post_detail"
            },
            e = {
                barindex: "tribe_ad",
                comment: "Grp_tribe",
                recommend: "Grp_tribe"
            },
            f = {
                barindex: {
                    click: "Clk_ad",
                    expose: "Pv_ad"
                },
                comment: {
                    click: "Clk_ad_com",
                    expose: "exp_ad_com"
                },
                recommend: {
                    click: "Clk_ad_recom",
                    expose: "exp_ad_recom"
                }
            };
            c.opername = e[b],
            c.module = d[b],
            c.action = f[b][a],
            "barindex" === b && (c.ver3 = o.barName, c.ver4 = o.category, c.ver5 = o.categoryName),
            Q.tdw(c)
        }
    };
    return {
        init: function(a, b) {
            var d = /\bMicroMessenger\/[\d\.]+/.test(navigator.userAgent);
            if (!d) {
                p.startTime = Date.now(),
                console.log(a, "广告模块初始化..."),
                console.log("ad init()", a);
                try {
                    c(a, b)
                } catch(e) {
                    console.error(e)
                }
            }
        },
        show: function(a, b, c) {
            j[a].container = $(b),
            d(a, c),
            console.log("ad show()", a)
        },
        silentFetch: function(a, b) {
            i(b) && (p.startTime = Date.now(), j[a].container = $(b), console.debug(a, "开始静默获取广告..."), c(a))
        },
        setBarInfo: function(a) {
            o = a,
            console.debug("BarInfo:", o)
        }
    }
}),
function(a, b) {
    a.WindowPostMessager = b(a.$, a.Util, a.DB)
} (this,
function(a, b, c) {
    function d(a) {
        g.validUrl = a.validUrl,
        g.bid = a.bid,
        g.shareCallback = a.shareCallback,
        g.followCallback = a.followCallback,
        window.addEventListener("message",
        function(a) {
            if ( - 1 !== g.validUrl.indexOf(a.origin)) {
                var b = a.data.postcode;
                h[b](a)
            }
        },
        !1)
    }
    function e(a, b) {
        var d = {
            type: "POST",
            url: "/cgi-bin/bar/get_mid",
            succ: function(b) {
                a(b)
            },
            err: function(a) {
                b(a)
            }
        };
        c.cgiHttp(d)
    }
    function f(a, b) {
        var d = {
            type: "POST",
            url: "/cgi-bin/bar/user/fbar",
            param: {
                bid: g.bid,
                op: 1
            },
            succ: function(b) {
                a(b)
            },
            err: function(a) {
                b(a)
            }
        };
        c.cgiHttp(d)
    }
    var g = {},
    h = {
        share: function(a) {
            var c = location.href;
            c = b.setExterParam(c, "uinav", "5");
            var d = {
                shareUrl: c,
                pageUrl: c,
                imageUrl: a.data.data.imageUrl ? a.data.data.imageUrl: "http://q3.qlogo.cn/g?b=qq&k=XnGFNfgzCr7LnaWFrAw0UQ&s=100",
                title: a.data.data.title ? a.data.data.title: "兴趣部落",
                content: a.data.data.desc ? a.data.data.desc: "总有你喜欢"
            };
            b.shareMessage(d,
            function() {
                a.source.postMessage({
                    retcode: "share",
                    data: "success"
                },
                "*"),
                g.shareCallback && g.shareCallback()
            })
        },
        follow: function(a) {
            f(function() {
                a.source.postMessage({
                    retcode: "follow",
                    data: "success"
                },
                "*"),
                g.followCallback && g.followCallback()
            },
            function() {
                a.source.postMessage({
                    retcode: "follow",
                    data: "error"
                },
                "*")
            })
        },
        getOid: function(a) {
            e(function(b) {
                a.source.postMessage({
                    retcode: "openid",
                    data: b
                },
                "*")
            })
        }
    };
    return {
        init: d
    }
}),
function() {
    function a(a, b) {
        this.$element = $(a),
        this.$indicators = this.$element.find(".ui-carousel-indicators"),
        this.options = b,
        this.containerWidth = b.containerWidth || this.$element.width() || window.innerWidth,
        this.paused = this.sliding = this.interval = this.$active = this.$items = null,
        this.swipeable()
    }
    var b = "js-active",
    c = "slid:carousel",
    d = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame;
    a.DEFAULTS = {
        interval: 5e3,
        wrap: !0
    },
    a.prototype.cycle = function(a) {
        return a || (this.paused = !1),
        this.interval && clearInterval(this.interval),
        this.options.interval && !this.paused && (this.interval = setInterval($.proxy(this.next, this), this.options.interval)),
        this
    },
    a.prototype.getActiveIndex = function() {
        return this.$active = this.$element.find(".ui-carousel-item." + b),
        this.$items = this.$active.parent().children(),
        this.$items.index(this.$active)
    },
    a.prototype.to = function(a) {
        var b = this,
        d = this.getActiveIndex();
        return a > this.$items.length - 1 || 0 > a ? void 0 : this.sliding ? this.$element.one(c,
        function() {
            b.to(a)
        }) : d == a ? this.pause().cycle() : this.slide(a > d ? "next": "prev", $(this.$items[a]))
    },
    a.prototype.pause = function(a) {
        return a || (this.paused = !0),
        this.$element.find(".js-next, .js-prev").length && $.support.transition && (this.$element.trigger($.support.transition.end), this.cycle(!0)),
        this.interval = clearInterval(this.interval),
        this
    },
    a.prototype.next = function() {
        return this.sliding ? void 0 : this.slide("next")
    },
    a.prototype.prev = function() {
        return this.sliding ? void 0 : this.slide("prev")
    },
    a.prototype.slide = function(a, d) {
        var e = this.$element.find(".ui-carousel-item." + b),
        f = d || e[a](),
        g = "next" == a ? "left": "right",
        h = "next" == a ? "first": "last",
        i = this;
        if (!f.length) {
            if (!this.options.wrap) return;
            f = this.$element.find(".ui-carousel-item")[h]()
        }
        if (f.hasClass(b)) return this.sliding = !1;
        var j = $.Event(c, {
            relatedTarget: f[0],
            direction: g
        });
        if (this.$element.trigger(j), !j.isDefaultPrevented()) {
            if (this.sliding = !0, this.pause(), this.$indicators.length && (this.$indicators.find("." + b).removeClass(b), this.$element.one(c,
            function() {
                var a = $(i.$indicators.children()[i.getActiveIndex()]);
                a && a.addClass(b)
            })), $.support.transition && this.$element.hasClass("js-slide") && !this.swiping) {
                var k = "js-" + g,
                l = "js-" + a;
                f.addClass(l),
                f[0].offsetWidth,
                e.addClass(k),
                f.addClass(k),
                e.one($.support.transition.end,
                function() {
                    f.removeClass([l, k].join(" ")).addClass(b),
                    e.removeClass([b, k].join(" ")),
                    i.sliding = !1,
                    setTimeout(function() {
                        i.$element.trigger(c)
                    },
                    0)
                }).emulateTransitionEnd(1e3 * e.css("transition-duration").slice(0, -1))
            } else e.removeClass(b),
            f.addClass(b),
            this.sliding = !1,
            this.$element.trigger(c);
            this.cycle(),
            this.$center = f;
            var m = function() {
                var b = f[a]();
                if (!b.length) {
                    if (!i.options.wrap) return;
                    b = i.$element.find(".ui-carousel-item")[h]()
                }
                return b
            } ();
            return this.$left = "next" == a ? e: m,
            this.$right = "next" == a ? m: e,
            this
        }
    },
    a.prototype.swipeable = function() {
        function a(a) {
            return a.targetTouches && a.targetTouches.length >= 1 ? a.targetTouches[0].clientX: a.clientX
        }
        function c() {
            var a, b, c, d;
            a = Date.now(),
            b = a - m,
            m = a,
            c = o - l,
            l = o,
            d = 800 * c / (1 + b),
            k = .8 * d + .2 * k
        }
        function e(a) {
            o = a,
            a = -Math.round(a),
            Math.abs(a) > p && (a = 0 > a ? -p: p),
            v.$left && (v.$left[0].style[s] = "translate(" + (a - p) + "px, 0)"),
            v.$center[0].style[s] = "translate(" + a + "px, 0)",
            v.$right && (v.$right[0].style[s] = "translate(" + (a + p) + "px, 0)")
        }
        function f() {
            var a, b;
            if (i) {
                if (a = Date.now() - m, b = i * Math.exp( - a / r), b > 10 || -10 > b) return e(j - b),
                d(f);
                x = !1,
                e(0),
                v.cycle(),
                [v.$left, v.$right].forEach(function(a) {
                    a && (a[0].style[s] = "translate(0, 0)", a.removeClass("js-show"))
                }),
                t && v.slide(u),
                v.swiping = !1,
                v.$right || $(document).trigger("wsq_carousel_end")
            } else x = !1
        }
        var g = this.$element.find(".ui-carousel-item." + b);
        this.$center = g,
        this.$left = this.options.wrap ? this.$element.find(".ui-carousel-item").last() : null,
        this.$right = g.next();
        var h, i, j, k, l, m, n, o = 0,
        p = this.containerWidth,
        q = !1,
        r = 125,
        s = "webkitTransform",
        t = !1,
        u = "",
        v = this,
        w = !1,
        x = !1;
        this.$element.on("touchstart",
        function(b) {
            return x ? void b.preventDefault() : (w = !1, v.sliding || (q = !0, h = a(b), k = i = 0, l = o, m = Date.now(), clearInterval(n), n = setInterval(c, 100), v.pause(), v.$left && v.$left.addClass("js-show"), v.$right && v.$right.addClass("js-show"), e(1)), void b.preventDefault())
        }),
        this.$element.on("touchmove",
        function(b) {
            if (!x) {
                var c, d;
                if (q && (c = a(b), d = h - c, d > 2 || -2 > d)) {
                    if (!v.options.wrap && d > 0 && !v.$right || 0 > d && !v.$left) return;
                    w = !0,
                    h = c,
                    e(o + d)
                }
            }
        }),
        this.$element.on("touchend",
        function() {
            x || v.sliding || (q = !1, x = !0, clearInterval(n), j = o, (k > 10 || -10 > k) && (i = 1.2 * k, j = o + i), j = Math.round(j / p * 3) * p, j = -p > j ? -p: j > p ? p: j, i = j - o, m = Date.now(), v.swiping = !0, t = 0 !== j, u = o > 0 ? "next": "prev", f(), w && (w = !1))
        })
    },
    $.Carousel = a,
    $.fn.carousel = function(b) {
        return this.each(function() {
            var c = $(this),
            d = c.data("carousel"),
            e = $.extend({},
            a.DEFAULTS, c.data(), "object" == typeof b && b),
            f = "string" == typeof b ? b: e.slide;
            d || c.data("carousel", d = new a(this, e)),
            "number" == typeof b ? d.to(b) : f ? d[f]() : e.interval && d.pause().cycle()
        })
    }
} (),
function(a, b) {
    a.ViewPreloader = b(a.$, a.DB)
} (this,
function() {
    var a, b = 15e3;
    return a = {
        canUseViewPreload: $.os.android && mqq.compare("5.0") >= 0,
        open: function(a) {
            if (this.canUseViewPreload) {
                var b = this,
                c = a.cgiMap;
                localStorage.setItem("storageConfirm", 1),
                $.each(c,
                function(a, c) {
                    b.__send(a, c)
                })
            }
            this.__openView(a)
        },
        __openView: function(a) {
            if (a.url) {
                var b = a.url;
                this.canUseViewPreload && (b += b.indexOf("?") > -1 ? "&viewPreLoad=1": "?viewPreLoad=1"),
                Util.openUrl(b, !0)
            } else a.callback && a.callback(this.canUseViewPreload ? 1 : 0)
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
                    } catch(e) {
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
            return~~ (Util.queryString("viewPreLoad") || Util.getHash("viewPreLoad"))
        },
        receive: function(a, c) {
            var d = this,
            e = this.useViewPreload();
            if (a = a || [], e) {
                var f, g, h = {},
                i = localStorage.getItem("storageConfirm");
                if (localStorage.removeItem("storageConfirm"), "1" === i) {
                    for (var j = 0; j < a.length; j++) g = a[j],
                    f = localStorage.getItem("viewPreloadData-" + g),
                    f && (f = JSON.parse(f), a.splice(j, 1), j--, h[g] = 1, "error" !== f.type ? d.__callbackHandler(0, g, c, f) : d.__callbackHandler(2, g, c)),
                    this.__removeDataStorage(g);
                    a.length && ($.each(a,
                    function(a, b) {
                        d.__callbackHandler(1, b, c)
                    }), mqq.addEventListener("preloadDataSuccess",
                    function(a) {
                        var b = a.dataName;
                        if (d.__removeDataStorage(b), !h[b]) {
                            h[b] = 1;
                            try {
                                f = a.preloadData,
                                f ? d.__callbackHandler(0, b, c, f) : d.__callbackHandler(2, b, c)
                            } catch(e) {
                                d.__callbackHandler(2, b, c)
                            }
                        }
                    }), mqq.addEventListener("preloadDataError",
                    function(a) {
                        var b = a.dataName;
                        d.__removeDataStorage(b),
                        h[b] = 1,
                        d.__callbackHandler(2, b, c)
                    }), setTimeout(function() {
                        $.each(a,
                        function(a, b) {
                            h[b] || (d.__removeDataStorage(b), d.__callbackHandler(3, b, c))
                        })
                    },
                    b))
                } else d.__callbackHandler(4, g, c)
            }
        }
    }
});