!function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_actionsheet = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("title")
          , f = c("items")
          , g = c("cancleText")
          , h = "";
        h += " ",
        e && (h += ' <div class="sheet-item sheet-title" value="-1">',
        h += d(e),
        h += "</div> "),
        h += " ";
        for (var i = 0; i < f.length; i++)
            h += ' <div class="sheet-item ',
            h += d(f[i].type || ""),
            h += '" value="',
            h += d(i),
            h += '"> <div class="sheet-item-text" >',
            h += d(f[i].text || f[i]),
            h += "</div> </div> ";
        return h += ' <div class="sheet-item sheet-cancle" value="-1" > <div class="sheet-item-text">',
        h += d(g || "取消"),
        h += "</div> </div> "
    }
    ;
    return a.frame = "TmplInline_actionsheet.frame",
    Tmpl.addTmpl(a.frame, b),
    a
}),
function(a, b) {
    a.ActionSheet = b(a.$, a.Tmpl)
}(this, function(a, b) {
    function c() {
        i = a('<div class="action-sheet"></div>'),
        h = a('<div class="action-sheet-mask"></div>'),
        a(document.body).append(i).append(h),
        h.on("tap", function() {
            f()
        }).on("webkitTransitionEnd", function() {
            h.hasClass("show") || h.hide()
        }),
        i.on("tap", ".sheet-item", e).on("webkitTransitionEnd", function() {
            i.hasClass("show") || (h.removeClass("show"),
            i.hide())
        }),
        k = !0
    }
    function d(d) {
        mqq.compare("4.7") >= 0 && !d.useH5 ? mqq.ui.showActionSheet({
            items: d.items,
            cancel: "取消"
        }, function(a, b) {
            0 === a ? d.onItemClick && d.onItemClick(b) : d.onCancel && d.onCancel(b)
        }) : (k || c(),
        j = d,
        document.body.style.overflow = "hidden",
        i.html(""),
        l.on("touchmove", g),
        b(window.TmplInline_actionsheet.frame, d).appendTo(i),
        h.show(),
        i.show(),
        setTimeout(function() {
            h.addClass("show"),
            i.addClass("show")
        }, 50)),
        d.cancle && a('.sheet-item[value="-1"] .sheet-item-text').html(d.cancle)
    }
    function e() {
        var b = a(this).attr("value");
        b = Number(b),
        -1 === b ? j.onCancel && j.onCancel() : j.onItemClick && j.onItemClick(b),
        f()
    }
    function f(a) {
        i && (a ? (h.hide().removeClass("show"),
        i.hide().removeClass("show")) : i.removeClass("show"),
        l.off("touchmove", g),
        document.body.style.overflow = "")
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
}(this, function() {
    return {
        substr2: function(a, b, c) {
            var d = 0
              , e = 0
              , f = 0;
            for (f = 0; f < a.length; f++)
                if (e += a.charCodeAt(f) > 255 ? 3 : 1,
                b >= e)
                    d++;
                else if (e > b + c)
                    break;
            return a.substr(d, f)
        },
        size: function(a) {
            return a.replace(/[^\u0000-\u007F]/gim, "...").length
        }
    }
}),
function() {
    window.honourHelper = {
        renderHonours: function(a, b) {
            var c, d, e = a.admin_ext, f = a["continue"], g = '<span class="honour vip-icon" data-url="http://buluo.qq.com/xylm/business/personal_center/center.html?from=exp_icon"></span>', h = "", i = "";
            return a.vipno && (i = "&nbsp;",
            h = g),
            8 === (8 & e) ? (c = a.title || "大明星",
            h += i + '<span class="honour vip" data-report-action="star" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">' + c + "</span>") : 2 === (2 & e) ? (d = a.owner ? "首席酋长" : "大酋长",
            h += i + '<span class="honour admin" data-report-action="big" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">' + d + "</span>") : 4 === (4 & e) ? h += i + '<span class="honour border-1px admin" data-report-action="small" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">小酋长</span>' : 256 === (256 & e) ? h += i + '<span class="honour admin xiaobian" data-report-action="xiaobian" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=admin&from=icon">小编</span>' : 2048 === (2048 & e) ? h += i + '<span class="honour rich" data-report-action="tuhao" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=rich&from=icon">土豪</span>' : 1024 === (1024 & e) ? h += i + '<span class="honour expert" data-report-action="daren" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=expert&from=icon">达人</span>' : f >= 7 && (h += i + '<span class="honour fans" data-report-action="iron" data-url="http://buluo.qq.com/mobile/hof.html?bid=' + b + '&type=fans&from=icon">铁杆粉</span>'),
            h
        },
        renderPoster: function(a, b) {
            var c, d = arguments.length;
            return 1 === d ? c = !!a : 2 === d && (c = a && b ? a === b : !1),
            c ? '<span class="honour poster">楼主</span>' : ""
        },
        isAdmin: function(a) {
            return 1 === (1 & a) || 2 === (2 & a) || 4 === (4 & a) || 32 === (32 & a)
        },
        renderVip: function(a) {
            var b = '<span class="vip-icon" data-url="http://buluo.qq.com/xylm/business/personal_center/center.html?from=exp_icon"></span>';
            return 1 === a ? b : ""
        }
    }
}(),
function(a) {
    a.smallSlider = {};
    var b, c, d = .15, e = null , f = 0, g = !0, h = document.documentElement.clientWidth || document.body.clientWidth, i = function(a) {
        if (null  === e) {
            if (!g)
                return void setTimeout(function() {
                    g = !0
                }, 330);
            var b = a.touches[0];
            e = b.clientX,
            c.css("-webkit-transition", "none")
        }
    }
    , j = function() {
        if (event.preventDefault(),
        null  !== e) {
            var a = event.touches[0]
              , b = Number(c.attr("data-offset")) || 0;
            f = (e - a.clientX) / h;
            var d = 120 * -f;
            d += b,
            f > 0 ? c.css("-webkit-transform", "translate3d(" + d + "px,0,0)") : c.css("-webkit-transform", "translate3d(" + d + "px,0,0)")
        }
    }
    , k = function() {
        var a;
        if (e = null ,
        g = !1,
        c.css("-webkit-transition", "330ms"),
        f >= d) {
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
        } else
            a = Number(c.attr("data-offset")) || 0,
            c.css("-webkit-transform", "translate3d(" + a + "px,0,0)");
        f = 0
    }
    ;
    window.smallSlider.init = function(a) {
        var d = a.images
          , e = new Image;
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
            for (var l = "style='width:" + h + "px;float:left;'", m = 0; m < d.length; m++) {
                var n = $("<li " + l + "><img style='width:100%;' src='" + d[m] + "' data-index='" + (m + 1) + "'/></li>");
                c.append(n)
            }
            1 !== d.length && (c.append($("<li " + l + "><img style='width:100%;' src='" + d[0] + "' data-index='1'/></li>")),
            c.prepend($("<li " + l + "><img style='width:100%;' src='" + d[d.length - 1] + "' data-index='" + d.length + "'/></li>")),
            c.on({
                touchstart: i,
                touchmove: j,
                touchend: k,
                touchcancel: k
            }),
            c.on("transitionend webkitTransitionEnd", function(b) {
                var e, f = h, i = Number($(b.target).attr("data-offset"));
                0 === i && (e = -(i + d.length * f),
                c.css("-webkit-transition", "none").css("-webkit-transform", "translate3d(" + e + "px,0,0)"),
                c.attr("data-offset", e)),
                i === -((d.length + 1) * f) && (e = i + d.length * f,
                c.css("-webkit-transition", "none").css("-webkit-transform", "translate3d(" + e + "px,0,0)"),
                c.attr("data-offset", e));
                var j = function(b) {
                    a.onImgIntoScreen && a.onImgIntoScreen(Math.abs(b / f))
                }
                ;
                j(e || i),
                g = !0
            })),
            c.find("img").on("tap", function(b) {
                a.onImgClick && a.onImgClick($(b.target).attr("data-index"))
            }),
            b.append(c)
        }
    }
}(window),
function(a, b) {
    a.feedRenderTool = b()
}(this, function() {
    var a = document.documentElement.clientWidth || document.body.clientWidth
      , b = a - 30
      , c = a - 20
      , d = {
        renderVip: function(a) {
            return window.honourHelper.renderVip(a)
        },
        checkSysMsg: function(a) {
            if (100 === Number(a.ft)) {
                a.reply = !0,
                a.post = {
                    title: a.title || "",
                    post: {
                        content: a.breif || ""
                    }
                },
                a.img_url && (a.post.post.pic_list = [{
                    url: a.img_url
                }]);
                var b = "data-msgtype=" + (a.type || "0") + " data-pushtype=" + a.push_type + " data-url=" + (a.url || null ) + " data-id=" + a.id + " data-status=" + a.status + " data-cid=" + a.category_id + " data-collid=" + a.collection_id + " data-ptype=" + a.post_type;
                a.dataBind = b
            } else
                10 === Number(a.ft) && (a.post.post || (a.post = {
                    post: a.post,
                    title: a.title || "",
                    type: a.type || 0
                }));
            return a
        },
        useSmallAvator: function(a) {
            return Util.setExterParam(a.replace(/&amp;/gi, "&"), "s", "100")
        },
        renderDate: function(a) {
            var b = new Date(a);
            return b.getMonth() + 1 + "月" + b.getDate() + "日" + (b.getHours() < 10 ? "0" : "") + b.getHours() + ":" + (b.getMinutes() < 10 ? "0" : "") + b.getMinutes()
        },
        getAdFeeds: function(a) {
            var b = {
                post: {
                    pic_list: a
                }
            };
            return this.getNormalFeeds(b, !1, !0)
        },
        renderActDate: function(a, b) {
            var c = new Date(a)
              , d = c.getMonth() + 1
              , e = c.getDate()
              , f = new Date(b)
              , g = f.getMonth() + 1
              , h = f.getDate();
            return d + "月" + e + "日" + (c.getHours() < 10 ? "0" : "") + c.getHours() + ":" + (c.getMinutes() < 10 ? "0" : "") + c.getMinutes() + " - " + (d === g && e === h ? "" : g + "月" + h + "日") + (f.getHours() < 10 ? "0" : "") + f.getHours() + ":" + (f.getMinutes() < 10 ? "0" : "") + f.getMinutes()
        },
        getNormalFeeds: function(a, d, e) {
            var f, g, h, i, j, k = "", l = "", m = [];
            if (f = a.post.pic_list || [],
            j = a.post.cellInfo ? a.post.cellInfo.picCount : f.length,
            e || (f = f.filter(function(a) {
                return a.w && a.w > 200 && a.h && a.h > 200 && a.h + a.w >= 500 ? !0 : a.w && a.h ? !1 : !0
            })),
            h = Math.min(f.length, 3),
            d) {
                var n = c;
                if (1 === h) {
                    var o = Math.floor(n / 1.5);
                    f[0].w && f[0].h && f[0].url ? (f = imgHandle.formatThumb(f, !0, n, o, !0),
                    l = "width:" + f[0].width + "px; height:" + f[0].height + "px;margin-left:" + f[0].marginLeft + "px; margin-top:" + f[0].marginTop + "px;",
                    g = "width:" + n + "px;height:" + o + "px;") : (f[0] = f[0].url ? {
                        url: f[0].url
                    } : {
                        url: f[0]
                    },
                    g = "width:" + n + "px;height:" + o + "px;",
                    k = "w:" + n + "&h:" + o + "&notFeed:true"),
                    m = [{
                        noSize: k,
                        picList: f,
                        imageStyle: l,
                        styleStr: g
                    }]
                }
                if (2 === h) {
                    i = (n - 4) / 2,
                    f = imgHandle.formatThumb(f, !0, i, i);
                    for (var p = 0; 2 > p; p++)
                        g = "float: left;width: " + i + "px;height: " + i + "px;",
                        1 === p && (g += "margin-left: 4px;"),
                        f[p].w && f[p].h ? l = "width:" + f[p].width + "px; height:" + f[p].height + "px;margin-left:" + f[p].marginLeft + "px; margin-top:" + f[p].marginTop + "px;" : (f[p] = f[p].url ? {
                            url: f[p].url
                        } : {
                            url: f[p]
                        },
                        k = "w:" + i + "&h:" + i + "&notFeed:true"),
                        m.push({
                            noSize: k,
                            picList: f,
                            imageStyle: l,
                            styleStr: g,
                            totalLength: j
                        })
                }
                if (h >= 3) {
                    i = (n - 4) / 3,
                    f = imgHandle.formatThumb(f, !0, i, i);
                    for (var q = 0; h > q; q++)
                        g = "float: left;background-size: 100%;width: " + i + "px;height: " + i + "px;",
                        l = "",
                        k = "",
                        2 !== q && (g += "margin-right:2px;"),
                        f[q].w && f[q].h ? l = "width:" + f[q].width + "px; height:" + f[q].height + "px;margin-left:" + f[q].marginLeft + "px; margin-top:" + f[q].marginTop + "px;" : (f[q] = f[q].url ? {
                            url: f[q].url
                        } : {
                            url: f[q]
                        },
                        k = "w:" + i + "&h:" + i + "&notFeed:true"),
                        m.push({
                            noSize: k,
                            picList: f,
                            imageStyle: l,
                            styleStr: g,
                            totalLength: f.length
                        })
                }
            } else {
                i = (b - 6) / 3,
                f = imgHandle.formatThumb(f, !0, i, i);
                for (var r = 0; h > r; r++)
                    g = "float: left;background-size: 100%;width: " + i + "px;height: " + i + "px;",
                    l = "",
                    k = "",
                    2 !== r && (g += "margin-right:3px;"),
                    f[r].w && f[r].h ? l = "width:" + f[r].width + "px; height:" + f[r].height + "px;margin-left:" + f[r].marginLeft + "px; margin-top:" + f[r].marginTop + "px;" : (f[r] = f[r].url ? {
                        url: f[r].url
                    } : {
                        url: f[r]
                    },
                    k = "w:" + i + "&h:" + i + "&notFeed:true"),
                    m.push({
                        noSize: k,
                        picList: f,
                        imageStyle: l,
                        styleStr: g,
                        totalLength: j
                    })
            }
            return m
        },
        getMusicFeeds: function(a) {
            var b = ""
              , c = ""
              , d = "";
            try {
                b = a.post.qqmusic_list[0].image_url,
                c = a.post.qqmusic_list[0].title,
                d = a.post.qqmusic_list[0].desc
            } catch (e) {}
            return {
                title: c,
                url: b,
                desc: d
            }
        },
        getAudioFeeds: function(a) {
            var b, c = "", d = "", e = "", f = "";
            try {
                c = "http://q.qlogo.cn/g?b=qq&nk=" + a.uin + "&s=140",
                d = a.post.audio_list[0].duration,
                b = parseInt(d / 60),
                e = 10 > b ? "0" + b : b,
                f = parseInt(60 * (d / 60 - b))
            } catch (g) {}
            return {
                time: e + ":" + (10 > f ? "0" + f : f),
                url: c
            }
        },
        getVideoFeeds: function(a, d) {
            var e, f = "", g = b, h = b / 2;
            d && (g = c,
            h = Math.floor(c / 1.5));
            try {
                e = a.post.video_list && a.post.video_list[0] || {
                    vid: a.post.vid,
                    image: a.post.image1
                },
                f = e.image || "http://shp.qpic.cn/qqvideo/0/" + e.vid + "/400"
            } catch (i) {}
            return {
                height: h,
                url: f,
                noSize: "w:" + g + "&h:" + h + "&notFeed:true"
            }
        },
        getPKFeeds: function(a) {
            var b, c, d = [], e = [], f = 0, g = "", h = "", i = "";
            try {
                if (b = a.post.aSide,
                c = a.post.bSide,
                i = a.post.content,
                h = b.content + '<span class="vs-tag">vs</span>' + c.content,
                g = a.post.start_time === a.post.end_time ? "进行中" : parseInt(a.post.start_time + "000") > Date.now() ? "未开始" : parseInt(a.post.end_time + "000") < Date.now() ? "已结束" : "进行中",
                b.pic && c.pic) {
                    e = imgHandle.formatThumb([b.pic, c.pic], !0, 42, 90);
                    for (var j = 0; j < e.length; j++)
                        d.push({
                            url: e[j].url,
                            style: "width:" + e[j].width + "px;height:" + e[j].height + "px;margin-left:" + e[j].marginLeft + "px;margin-top:" + e[j].marginTop + "px;"
                        })
                }
                for (var k = 0; k < a.vote_result.ops.length; k++)
                    f += a.vote_result.ops[k].count
            } catch (l) {}
            return {
                pklisk: d,
                pktitle: h,
                content: i,
                count: f,
                condition: g,
                aSideContent: b.content,
                bSideContent: c.content
            }
        },
        setSubscribers: function(a, b) {
            a.post.type && 101 === a.post.type && "recover" === b ? a.post.post.openact_enroll && (a.subscribers = a.post.post.openact_enroll || 0) : a.type && 101 === a.type && "barindex" === b && a.post.openact_enroll && (a.subscribers = a.post.openact_enroll || 0)
        },
        getActivityFeeds: function(a) {
            var b, c = "", d = "", e = "", f = Date.now();
            try {
                101 === a.type ? (b = imgHandle.formatThumb([{
                    w: 160,
                    h: 240,
                    url: a.post.cover
                }], !0, 85, 85, !0),
                (a.post.grpact_start && a.post.grpact_end || a.post.start && a.post.end) && (a.post.start = 1e3 * (a.post.grpact_start || a.post.start),
                a.post.end = 1e3 * (a.post.grpact_end || a.post.end))) : b = imgHandle.formatThumb(a.post.pic_list, !0, 85, 85, !0),
                c = b[0].url,
                d = "width:" + b[0].width + "px; height:" + b[0].height + "px;margin-left:" + b[0].marginLeft + "px; margin-top:" + b[0].marginTop + "px;",
                e = ["报名中", "进行中", "已结束"][f > a.post.end ? 2 : f > a.post.start ? 1 : 0]
            } catch (g) {}
            return {
                url: c,
                joinImgStyle: d,
                condition: e
            }
        },
        getSysmsgFeeds: function(a) {
            var b = "";
            try {
                b = a.post.pic_list[0].url
            } catch (c) {}
            return {
                url: b
            }
        },
        getForwardFeeds: function(a, b, c) {
            var d, e, f, g, h, i, j = "", k = {}, l = "", m = c || 65;
            try {
                d = 600 === b,
                e = 200 === b,
                f = 201 === b,
                g = a.post.qqmusic_list,
                h = a.post.audio_list,
                i = d ? [{
                    url: "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/re-pk-icon.png?t=20151109"
                }] : e ? [{
                    url: a.post.image1
                }] : f ? [{
                    url: this.getVideoFeeds(a).url
                }] : g ? [{
                    url: this.getMusicFeeds(a).url
                }] : h ? [{
                    url: this.getAudioFeeds(a).url
                }] : a.post.pic_list || [],
                i.length ? i[0].w && i[0].h && i[0].url ? (i = imgHandle.formatThumb(i, !0, m, m, !0),
                k = i[0],
                j = "width:" + k.width + "px; height:" + k.height + "px;margin-left:" + k.marginLeft + "px; margin-top:" + k.marginTop + "px;") : (k = i[0].url ? {
                    url: i[0].url
                } : {
                    url: i[0]
                },
                l = "w:" + m + "&h:" + m + "&notFeed:true") : (l = "w:" + m + "&h:" + m + "&notFeed:true",
                k = {
                    url: "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/re-onlytext-icon.png"
                })
            } catch (n) {}
            return {
                noSize: l,
                pic_item: k,
                imageStyle: j
            }
        },
        getPicFeeds: function(a) {
            var c = []
              , d = (b - 8) / 3
              , e = "";
            a.post.pic_list = a.post.pic_list.filter(function(a) {
                return a.w && a.w < 250 || a.h && a.h < 250 ? !1 : !0
            });
            try {
                a.post.pic_list.length = Math.min(a.post.pic_list.length, 3),
                c = imgHandle.formatThumb(a.post.pic_list, !0, d, d),
                e = a.show
            } catch (f) {}
            return {
                piclist: c,
                width: d,
                share: e
            }
        },
        getPictextFeeds: function(a) {
            var c = []
              , d = (b - 8) / 3
              , e = "";
            try {
                for (var f = 0; f < a.hotest_posts.length && 3 > f; f++)
                    c.push({
                        title: a.hotest_posts[f].title,
                        content: a.hotest_posts[f].post.content
                    });
                e = a.show
            } catch (g) {}
            return {
                piclist: c,
                width: d,
                share: e
            }
        },
        getTwoPicModel: function(a) {
            var b, d, e, f, g, h = "", i = "", j = [];
            b = a.post.pic_list || [],
            g = a.post.cellInfo ? a.post.cellInfo.picCount : b.length,
            b = b.filter(function(a) {
                return a.w && a.w < 250 || a.h && a.h < 250 ? !1 : !0
            }),
            e = Math.min(b.length, 2);
            var k = c;
            if (1 === e) {
                var l = Math.floor(k / 1.5);
                b[0].w && b[0].h && b[0].url ? (b = imgHandle.formatThumb(b, !0, k, l, !0),
                i = "width:" + b[0].width + "px; height:" + b[0].height + "px;margin-left:" + b[0].marginLeft + "px; margin-top:" + b[0].marginTop + "px;",
                d = "width:" + k + "px;height:" + l + "px;") : (b[0] = b[0].url ? {
                    url: b[0].url
                } : {
                    url: b[0]
                },
                d = "width:" + k + "px;height:" + l + "px;",
                h = "w:" + k + "&h:" + l + "&notFeed:true"),
                j = [{
                    noSize: h,
                    picList: b,
                    imageStyle: i,
                    styleStr: d
                }]
            }
            if (e >= 2) {
                f = (k - 4) / 2,
                b = imgHandle.formatThumb(b, !0, f, f);
                for (var m = 0; 2 > m; m++)
                    d = "float: left;width: " + f + "px;height: " + f + "px;",
                    1 === m && (d += "margin-left: 4px;"),
                    b[m].w && b[m].h ? i = "width:" + b[m].width + "px; height:" + b[m].height + "px;margin-left:" + b[m].marginLeft + "px; margin-top:" + b[m].marginTop + "px;" : (b[m] = b[m].url ? {
                        url: b[m].url
                    } : {
                        url: b[m]
                    },
                    h = "w:" + f + "&h:" + f + "&notFeed:true"),
                    j.push({
                        noSize: h,
                        picList: b,
                        imageStyle: i,
                        styleStr: d,
                        totalLength: g
                    })
            }
            return j
        },
        getFeedBref: function(a) {
            return a = a || "",
            a.replace(/(\n){3,}/gi, "\n").replace(/\n/g, " ").replace(/(<br>)/gi, " ")
        }
    };
    return d
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_ad = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("type")
          , f = c("uiType")
          , g = c("title")
          , h = c("desc")
          , i = c("imgHandle")
          , j = c("img")
          , k = c("document")
          , l = c("reply")
          , m = c("imgHeight")
          , n = c("style")
          , o = c("starHtml")
          , p = c("wording")
          , q = "";
        if (q += "",
        "barindex" === e) {
            if (q += " ",
            1 === f)
                q += ' <div class="detail-text-content haspic"> <div class="text-container"> <h3 class="text">',
                q += d(g),
                q += '<span class="list-ad-mark-top">广告</span></h3> <div class="list-content">',
                q += d(h),
                q += '</div> </div> <div class="act-img img-gallary img-ph"> <img class="ad-img" src="',
                q += d(i.getThumbUrl(j)),
                q += '" style="width:',
                q += d(j.width),
                q += "px; height:",
                q += d(j.height),
                q += "px; margin-left:",
                q += d(j.marginLeft),
                q += "px; margin-top:",
                q += d(j.marginTop),
                q += 'px;" /> </div> </div> ';
            else if (2 === f) {
                (k.documentElement.clientWidth || k.body.clientWidth) - 30;
                q += ' <div class="detail-text-content haspic"> <div class="report-content"> <h3 class="grouptitle feed-two-line"><span class="ad-mark-new">广告</span>',
                q += d(g),
                q += '</h3> <div class="groupbrief feed-two-line">',
                q += d(h),
                q += "</div> </div> ";
                var r = window.feedRenderTool.getAdFeeds(j);
                q += ' <div class="img-wrap clearfix"> ';
                for (var s = 0; s < r.length; s++)
                    q += ' <div class="img-ph feed-img ',
                    q += d(l ? "report" : ""),
                    q += '" style="',
                    q += d(r[s].styleStr),
                    q += '"> <img hidebg="true" noSize="',
                    q += d(r[s].noSize),
                    q += '" src="',
                    q += d(r[s].picList[s].url),
                    q += '" style="width:100%;height:100%;"/> </div> ';
                q += " </div> </div> "
            } else
                q += ' <div class="gdt-ad-wrapper"> <div class="detail-text-content haspic"> <div class="text-container"> <h3 class="text">',
                q += d(g),
                q += '</h3> <div class="list-content">',
                q += d(h),
                q += '</div> </div> <div class="act-img img-gallary img-ph"> <img class="ad-img" src="',
                q += d(i.getThumbUrl(j)),
                q += '" style="width:',
                q += d(j.width),
                q += "px; height:",
                q += d(j.height),
                q += "px; margin-left:",
                q += d(j.marginLeft),
                q += "px; margin-top:",
                q += d(j.marginTop),
                q += 'px;" /> </div> <span class="ad-ribbon">广告</span> </div> </div> ';
            q += " "
        } else
            ("post" === e || "recommend" === e) && (q += " ",
            j = i.formatThumb(j, !0, 80, 80),
            j = j[0],
            q += ' <div class="ad-banner-img-wrapper img-ph" style="height: ',
            q += d(m),
            q += 'px"> <img src="',
            q += d(i.getThumbUrl(j)),
            q += '" class="ad-banner-img"/> <div class="ad-tag"></div> </div> <div class="ad-banner-wording-wrapper"> ',
            n && o ? (q += ' <p class="app-stars">',
            q += d(o),
            q += '</p> <div class="app-title">今日热门推荐</div> ') : (q += ' <div class="wording">',
            q += d(p[0]),
            q += "</div> "),
            q += ' <div class="ad-btn">',
            q += d(p[1]),
            q += "</div> </div> ");
        return q += " "
    }
    ;
    return a.ad = "TmplInline_ad.ad",
    Tmpl.addTmpl(a.ad, b),
    a
}),
function(a, b) {
    a.LazyExpose = b()
}(this, function() {
    var a = function(a, b, c) {
        var d;
        return !1 !== c && (c = !0),
        function() {
            function e() {
                c || a.apply(f, g),
                d = null 
            }
            var f = this
              , g = arguments;
            d ? clearTimeout(d) : c && a.apply(f, g),
            d = setTimeout(e, b || 100)
        }
    }
      , b = 0
      , c = {}
      , d = window.innerHeight
      , e = {
        start: !0,
        defer: 300,
        handler: function() {},
        container: window
    };
    return {
        init: function(b, c) {
            console.debug("广告LazyExpose init..."),
            this.elements = b,
            this.options = c = $.extend({}, e, "object" == typeof c && c),
            this.handler = c.handler,
            this.delay = c.delay || 0,
            this.container = $.os.ios ? c.container : window,
            this.$container = $(this.container),
            this.onScroll = a($.proxy(this._onScroll, this), c.defer, !1),
            this.status = 1,
            setTimeout(function() {
                this.inited || (this.inited = !0,
                this.containerHeight = this._getContainerHeight(),
                this.$container.on("scroll", this.onScroll))
            }
            .bind(this), this.options.defer)
        },
        _getContainerHeight: function() {
            var a = this.container;
            if (a.document)
                return d;
            var b = window.getComputedStyle(a);
            return parseInt(b.height) + parseInt(b.paddingTop) + parseInt(b.paddingBottom) + parseInt(b.marginTop) + parseInt(b.marginBottom)
        },
        _onScroll: function() {
            if (console.debug(">>>>>>>>>onScroll"),
            this.status) {
                var a = this.elements;
                if (!a || !a.length)
                    return void this._destory();
                var c, d, e, f = this;
                for (d = 0,
                e = a.length; e > d; d++) {
                    c = a[d],
                    c.cacheId = c.cacheId || ++b;
                    var g = f._elementInViewport(c);
                    c && f.isWebViewHidden !== !0 && g && setTimeout(function() {
                        this._elementInViewport(c) && (this.handler(),
                        Array.prototype.splice.call(this.elements, d, 1),
                        1 === e && this._destory())
                    }
                    .bind(f), f.delay)
                }
            }
        },
        _elementInViewport: function(a) {
            if ("none" === $(a).css("display"))
                return !1;
            var b = this.container
              , d = a.cacheId
              , e = c[d] || $(a).offset()
              , f = 0;
            return b ? (f += b.document ? b.scrollY || b.pageYOffset : (b.scrollTop || window.scrollY) + (b.offsetTop || window.pageYOffset),
            c[d] = e,
            f >= e.top - e.height) : !1
        },
        _destory: function() {
            console.debug(">>>>ad scroll off"),
            this.$container.off("scroll", this.onScroll),
            c = {},
            this.status = 0,
            this.elements = null ,
            this.container = null ,
            this.$container = null 
        }
    }
}),
function(a) {
    "use strict";
    function b(a, b) {
        var c = (65535 & a) + (65535 & b)
          , d = (a >> 16) + (b >> 16) + (c >> 16);
        return d << 16 | 65535 & c
    }
    function c(a, b) {
        return a << b | a >>> 32 - b
    }
    function d(a, d, e, f, g, h) {
        return b(c(b(b(d, a), b(f, h)), g), e)
    }
    function e(a, b, c, e, f, g, h) {
        return d(b & c | ~b & e, a, b, f, g, h)
    }
    function f(a, b, c, e, f, g, h) {
        return d(b & e | c & ~e, a, b, f, g, h)
    }
    function g(a, b, c, e, f, g, h) {
        return d(b ^ c ^ e, a, b, f, g, h)
    }
    function h(a, b, c, e, f, g, h) {
        return d(c ^ (b | ~e), a, b, f, g, h)
    }
    function i(a, c) {
        a[c >> 5] |= 128 << c % 32,
        a[(c + 64 >>> 9 << 4) + 14] = c;
        var d, i, j, k, l, m = 1732584193, n = -271733879, o = -1732584194, p = 271733878;
        for (d = 0; d < a.length; d += 16)
            i = m,
            j = n,
            k = o,
            l = p,
            m = e(m, n, o, p, a[d], 7, -680876936),
            p = e(p, m, n, o, a[d + 1], 12, -389564586),
            o = e(o, p, m, n, a[d + 2], 17, 606105819),
            n = e(n, o, p, m, a[d + 3], 22, -1044525330),
            m = e(m, n, o, p, a[d + 4], 7, -176418897),
            p = e(p, m, n, o, a[d + 5], 12, 1200080426),
            o = e(o, p, m, n, a[d + 6], 17, -1473231341),
            n = e(n, o, p, m, a[d + 7], 22, -45705983),
            m = e(m, n, o, p, a[d + 8], 7, 1770035416),
            p = e(p, m, n, o, a[d + 9], 12, -1958414417),
            o = e(o, p, m, n, a[d + 10], 17, -42063),
            n = e(n, o, p, m, a[d + 11], 22, -1990404162),
            m = e(m, n, o, p, a[d + 12], 7, 1804603682),
            p = e(p, m, n, o, a[d + 13], 12, -40341101),
            o = e(o, p, m, n, a[d + 14], 17, -1502002290),
            n = e(n, o, p, m, a[d + 15], 22, 1236535329),
            m = f(m, n, o, p, a[d + 1], 5, -165796510),
            p = f(p, m, n, o, a[d + 6], 9, -1069501632),
            o = f(o, p, m, n, a[d + 11], 14, 643717713),
            n = f(n, o, p, m, a[d], 20, -373897302),
            m = f(m, n, o, p, a[d + 5], 5, -701558691),
            p = f(p, m, n, o, a[d + 10], 9, 38016083),
            o = f(o, p, m, n, a[d + 15], 14, -660478335),
            n = f(n, o, p, m, a[d + 4], 20, -405537848),
            m = f(m, n, o, p, a[d + 9], 5, 568446438),
            p = f(p, m, n, o, a[d + 14], 9, -1019803690),
            o = f(o, p, m, n, a[d + 3], 14, -187363961),
            n = f(n, o, p, m, a[d + 8], 20, 1163531501),
            m = f(m, n, o, p, a[d + 13], 5, -1444681467),
            p = f(p, m, n, o, a[d + 2], 9, -51403784),
            o = f(o, p, m, n, a[d + 7], 14, 1735328473),
            n = f(n, o, p, m, a[d + 12], 20, -1926607734),
            m = g(m, n, o, p, a[d + 5], 4, -378558),
            p = g(p, m, n, o, a[d + 8], 11, -2022574463),
            o = g(o, p, m, n, a[d + 11], 16, 1839030562),
            n = g(n, o, p, m, a[d + 14], 23, -35309556),
            m = g(m, n, o, p, a[d + 1], 4, -1530992060),
            p = g(p, m, n, o, a[d + 4], 11, 1272893353),
            o = g(o, p, m, n, a[d + 7], 16, -155497632),
            n = g(n, o, p, m, a[d + 10], 23, -1094730640),
            m = g(m, n, o, p, a[d + 13], 4, 681279174),
            p = g(p, m, n, o, a[d], 11, -358537222),
            o = g(o, p, m, n, a[d + 3], 16, -722521979),
            n = g(n, o, p, m, a[d + 6], 23, 76029189),
            m = g(m, n, o, p, a[d + 9], 4, -640364487),
            p = g(p, m, n, o, a[d + 12], 11, -421815835),
            o = g(o, p, m, n, a[d + 15], 16, 530742520),
            n = g(n, o, p, m, a[d + 2], 23, -995338651),
            m = h(m, n, o, p, a[d], 6, -198630844),
            p = h(p, m, n, o, a[d + 7], 10, 1126891415),
            o = h(o, p, m, n, a[d + 14], 15, -1416354905),
            n = h(n, o, p, m, a[d + 5], 21, -57434055),
            m = h(m, n, o, p, a[d + 12], 6, 1700485571),
            p = h(p, m, n, o, a[d + 3], 10, -1894986606),
            o = h(o, p, m, n, a[d + 10], 15, -1051523),
            n = h(n, o, p, m, a[d + 1], 21, -2054922799),
            m = h(m, n, o, p, a[d + 8], 6, 1873313359),
            p = h(p, m, n, o, a[d + 15], 10, -30611744),
            o = h(o, p, m, n, a[d + 6], 15, -1560198380),
            n = h(n, o, p, m, a[d + 13], 21, 1309151649),
            m = h(m, n, o, p, a[d + 4], 6, -145523070),
            p = h(p, m, n, o, a[d + 11], 10, -1120210379),
            o = h(o, p, m, n, a[d + 2], 15, 718787259),
            n = h(n, o, p, m, a[d + 9], 21, -343485551),
            m = b(m, i),
            n = b(n, j),
            o = b(o, k),
            p = b(p, l);
        return [m, n, o, p]
    }
    function j(a) {
        var b, c = "";
        for (b = 0; b < 32 * a.length; b += 8)
            c += String.fromCharCode(a[b >> 5] >>> b % 32 & 255);
        return c
    }
    function k(a) {
        var b, c = [];
        for (c[(a.length >> 2) - 1] = void 0,
        b = 0; b < c.length; b += 1)
            c[b] = 0;
        for (b = 0; b < 8 * a.length; b += 8)
            c[b >> 5] |= (255 & a.charCodeAt(b / 8)) << b % 32;
        return c
    }
    function l(a) {
        return j(i(k(a), 8 * a.length))
    }
    function m(a, b) {
        var c, d, e = k(a), f = [], g = [];
        for (f[15] = g[15] = void 0,
        e.length > 16 && (e = i(e, 8 * a.length)),
        c = 0; 16 > c; c += 1)
            f[c] = 909522486 ^ e[c],
            g[c] = 1549556828 ^ e[c];
        return d = i(f.concat(k(b)), 512 + 8 * b.length),
        j(i(g.concat(d), 640))
    }
    function n(a) {
        var b, c, d = "0123456789abcdef", e = "";
        for (c = 0; c < a.length; c += 1)
            b = a.charCodeAt(c),
            e += d.charAt(b >>> 4 & 15) + d.charAt(15 & b);
        return e
    }
    function o(a) {
        return unescape(encodeURIComponent(a))
    }
    function p(a) {
        return l(o(a))
    }
    function q(a) {
        return n(p(a))
    }
    function r(a, b) {
        return m(o(a), o(b))
    }
    function s(a, b) {
        return n(r(a, b))
    }
    function t(a, b, c) {
        return b ? c ? r(b, a) : s(b, a) : c ? p(a) : q(a)
    }
    "function" == typeof define && define.amd ? define(function() {
        return t
    }) : a.md5 = t
}(this),
function(a, b) {
    a.ad = b(a.TmplInline_ad, a.LazyExpose, a.Q)
}(this, function(a, b, c) {
    function d(a) {
        var b, c, d = Login.getUin(), e = {
            page_type: 0,
            _uin: Number(d),
            bid: Util.getHash("bid") || Util.queryString("bid")
        }, f = {
            adposcount: 1,
            posid: "recommend" === a ? u[a][0] : u[a],
            count: "1",
            datatype: 2,
            datafmt: "json",
            charset: "utf8"
        }, g = {
            wapos: $.os.android ? "ADR" : $.os.ios ? "IPH" : "",
            carrier: 0
        };
        try {
            b = localStorage.getItem(d + "-lat"),
            c = localStorage.getItem(d + "-lon")
        } catch (h) {}
        b && c && (g.lat = b,
        g.lng = c),
        console.debug("deviceInfo: ", C),
        console.debug(r);
        for (var i in C)
            C.hasOwnProperty(i) && (g[i] = C[i]);
        return f.ext = g,
        e._param = JSON.stringify(f),
        e
    }
    function e(a) {
        console.log(a, "暂时无广告数据，清除广告容器"),
        l(t[a].container),
        F.monitor("noAd", a)
    }
    function f(a) {
        var b, f;
        console.debug(a, "准备拉取CGI广告数据");
        try {
            DB.cgiHttp({
                url: _domain + "/cgi-bin/bar/extra/get_ads",
                param: d(a),
                succ: function(d) {
                    if (0 !== d.retcode || !d.result || void 0 !== d.result.ret && 0 !== d.result.ret || $.isEmptyObject(d.result))
                        return void e(a);
                    console.info("/cgi-bin/bar/extra/get_ads data: ", d);
                    var j, k, l, m, o = 1 != d.result.ads_from && 2 != d.result.ads_from, p = 2 === d.result.ads_from;
                    if (p) {
                        if (0 === d.result.ads.length)
                            return void e(a);
                        l = d.result.ads[0],
                        l.trl = l.url,
                        m = {
                            id: l.id,
                            type: a,
                            uiType: v,
                            wording: g(l.producttype),
                            style: h(l.producttype),
                            appGrade: l.ext && l.ext.appscore,
                            title: n(l.title),
                            desc: n(l.desc),
                            img: l.pic[0],
                            rl: l.url,
                            apurl: l.e_url
                        },
                        console.log("捕获到来自新系统的广告", m)
                    } else if (o) {
                        if (j = d.result.data,
                        !j)
                            return void e(a);
                        if (k = j[u[a]],
                        "recommend" === a && (k = j[u[a][0]],
                        w = 2,
                        k || (k = j[u[a][1]],
                        w = 1)),
                        !(k && 0 === k.ret && k.list && k.list.length > -1))
                            return void e(a);
                        l = k.list[0];
                        var q = k.cfg
                          , r = q.id;
                        m = {
                            id: r,
                            type: a,
                            uiType: "barindex" === a ? v : w,
                            wording: g(l.producttype),
                            style: h(l.producttype),
                            appGrade: l.ext && l.ext.appscore,
                            cl: l.cl,
                            title: n(l.txt),
                            desc: n(l.desc),
                            img: l.img || l.img_s || l.img2,
                            rl: l.rl,
                            apurl: l.apurl
                        }
                    } else
                        l = d.result,
                        m = {
                            id: l.id,
                            type: a,
                            uiType: v,
                            title: n(l.title),
                            desc: n(l.desc),
                            img: l.pic,
                            rl: l.link
                        };
                    if (m.appGrade) {
                        for (m.starHtml = "",
                        f = 0,
                        b = m.appGrade; b > 1; b -= 2)
                            m.starHtml += "<i></i>",
                            f++;
                        for (1 === b && (m.starHtml += '<i class="half"></i>',
                        f++),
                        b = 5 - f; b > 0; b--)
                            m.starHtml += '<i class="gray"></i>'
                    }
                    if ("post" === a || "recommend" === a) {
                        var s = D - 30;
                        m.imgHeight = Math.round(166 * s / 582)
                    }
                    m.img = [{
                        url: m.img,
                        h: 90,
                        w: 90
                    }],
                    "barindex" !== a || p || (l.pic2 && m.img.push({
                        url: l.pic2,
                        h: 90,
                        w: 90
                    }),
                    l.pic3 && m.img.push({
                        url: l.pic3,
                        h: 90,
                        w: 90
                    })),
                    "barindex" === a && p && (l.pic[1] && m.img.push({
                        url: l.pic[1],
                        h: 90,
                        w: 90
                    }),
                    l.pic[2] && m.img.push({
                        url: l.pic[2],
                        h: 90,
                        w: 90
                    })),
                    console.debug(a, "CGI拉取完广告数据"),
                    z.isSpecific && c.monitor(2021682, !1),
                    t[a].data = m,
                    t[a].hasData = !0,
                    t[a].container && i(a, l.producttype, l.trl)
                },
                err: function() {
                    console.error("广告拉取CGI报错！", j),
                    l(t[a].container)
                }
            })
        } catch (j) {
            console.error(a + "广告拉取失败:", j)
        }
        F.monitor("total", a)
    }
    function g(a) {
        return [12, 19].indexOf(a) > -1 ? x[0] : [1, 2, 25].indexOf(a) > -1 ? x[1] : 26 === a ? x[2] : x[3]
    }
    function h(a) {
        return [12, 19].indexOf(a) > -1 ? y[0] : y[1]
    }
    function i(c, d, e) {
        if (console.log("准备展示广告", c, d, e),
        !t[c].data)
            return void (t[c].hasData && (console.debug(c, "暂时无广告数据"),
            l(t[c].container)));
        var f = t[c].data
          , g = t[c].container
          , h = "barindex" === c ? 0 : 200;
        Tmpl(a.ad, f).update(g),
        g.data("href", f.rl),
        g.show(),
        console.debug(c, "已展示广告"),
        j(g, c, d, e),
        b.init($(".gdt-ad"), {
            container: t[c].page,
            handler: function() {
                F.exposure(f.apurl, c)
            },
            delay: h
        }),
        "recommend" !== c && m(c)
    }
    function j(a, b, c, d) {
        var e = !0;
        a.on("tap", function(a) {
            var d = $(a.currentTarget)
              , f = d.data("href")
              , g = Util.queryString("from") || Util.getHash("from")
              , h = "xingquhao" === g;
            if (F.click(b),
            e) {
                var i = a.srcElement || a.target;
                12 === c && ($(i).hasClass("ad-btn") || $(i).hasClass("btn")) ? k(f, h) : $.os.android && h ? Util.openUrl("http://buluo.qq.com/mobile/ad.html?gdturl=" + encodeURIComponent(f), !0) : $.os.ios && 19 === c ? $.getJSON(f + "&acttype=1&callback=?", function(a) {
                    a.data && a.data.dstlink && (mqq.invoke && mqq.compare("6.3.1") >= 0 ? mqq.invoke("ui", "showAppstoreProduct", {
                        appUrl: a.data.dstlink,
                        jump: "1"
                    }) : window.location = a.data.dstlink)
                }) : Util.openUrl(f + (f.indexOf("?") > 0 ? "" : "?") + "&_wv=4", !0),
                setTimeout(function() {
                    e = !0
                }, 1e3),
                e = !1
            }
            a.stopPropagation()
        })
    }
    function k(a, b) {
        mqq && mqq.support("mqq.device.getNetworkType") && mqq.device.getNetworkType(function(c) {
            1 === c && (a += "&acttype=42"),
            b ? Util.openUrl("http://buluo.qq.com/mobile/ad.html?gdturl=" + encodeURIComponent(a), !0) : Util.openUrl(a + (a.indexOf("?") > 0 ? "" : "?") + "&_wv=4", !0)
        })
    }
    function l(a) {
        a && a.remove()
    }
    function m(a) {
        t[a] = {
            container: null ,
            hasData: !1,
            data: null 
        }
    }
    function n(a) {
        return a ? a.replace("&nbsp;", " ") : a
    }
    function o(a) {
        if (!a)
            return !1;
        var b = $(a)
          , c = b.offset();
        return c ? c.top + c.height > 10 : !1
    }
    function p(a) {
        var b = a.height()
          , c = $(".bottom-bar");
        return "block" === c.css("display") && "visible" === c.css("visibility") && (E -= c.height()),
        console.log("postHeight=" + b + " screenHeight=" + E),
        b > E
    }
    function q(a) {
        var b, c, d = $("#top_post_wrapper"), e = '<div class="gdt-ad-banner gdt-ad section-1px"></div>';
        return a || (a = p(d)),
        c = a ? "post" : "recommend",
        console.debug("帖子详情里显示广告isAfterPost =" + a),
        $("#detail_ad_wrapper").html(e),
        b = $(".gdt-ad-banner"),
        t[c].container = b,
        c
    }
    var r, s, t = {
        barindex: {
            container: null ,
            hasData: !1,
            data: null 
        },
        post: {
            container: null ,
            hasData: !1,
            data: null 
        },
        recommend: {
            container: null ,
            hasData: !1,
            data: null 
        }
    }, u = {
        barindex: "7000703150186964",
        post: "4030808509165743",
        recommend: ["4090805065898364"]
    }, v = 2, w = 2, x = {
        0: ["热门应用", "下载"],
        1: ["精选商品", "去看看"],
        2: ["吃喝玩乐", "去体验"],
        3: ["推荐", "查看"]
    }, y = {
        0: "app-info",
        1: ""
    }, z = {}, A = Util.queryString("from") || Util.getHash("from"), B = "xingquhao" === A, C = {}, D = $(document).width(), E = $(document).height();
    !function() {
        mqq && mqq.support("mqq.device.getDeviceInfo") && mqq.device.getDeviceInfo(function(a) {
            C.c_os = $.os.android ? "android" : $.os.ios ? "ios" : "other",
            C.c_osver = a.systemVersion,
            C.c_device = a.modelVersion || a.model,
            C.muidtype = $.os.android ? 1 : $.os.ios ? 2 : -1,
            1 === C.muidtype ? C.muid = md5(a.identifier) : C.muid = md5(a.idfa),
            r = !0
        }),
        mqq && mqq.support("mqq.device.getNetworkType") && mqq.device.getNetworkType(function(a) {
            C.conn = -1 === a ? a + 1 : a,
            s = !0
        })
    }();
    var F = {
        startTime: 0,
        endTime: 0,
        _init: function() {
            this.endTime = Date.now()
        },
        exposure: function(a, b) {
            if (a) {
                var c = new Image;
                c.src = a + "&adt=" + Date.now(),
                c = null 
            }
            console.debug(b + "广告曝光上报"),
            this.tdw("expose", b),
            this.monitor("expose", b)
        },
        click: function(a) {
            this.tdw("click", a),
            this.monitor("click", a)
        },
        tdw: function(a, b) {
            var d = {
                obj1: Util.getHash("pid") || Util.queryString("pid"),
                ver1: Util.queryString("bid") || Util.getHash("bid"),
                ver6: $.os.ios ? 2 : $.os.android ? 1 : ""
            }
              , e = {
                barindex: "post_list",
                post: "post_detail",
                recommend: "post_detail"
            }
              , f = {
                barindex: "tribe_ad",
                post: "Grp_tribe",
                recommend: "Grp_tribe"
            }
              , g = {
                barindex: {
                    click: "Clk_ad",
                    expose: "Pv_ad"
                },
                post: {
                    click: "Clk_ad_com_mid",
                    expose: "exp_ad_com_mid"
                },
                recommend: {
                    click: "Clk_ad_recom",
                    expose: "exp_ad_recom"
                }
            };
            d.opername = f[b],
            d.module = e[b],
            d.action = g[b][a],
            "barindex" === b && (d.ver3 = z.barName,
            d.ver4 = z.category,
            d.ver5 = z.categoryName),
            c.tdw(d)
        },
        monitor: function(a, b) {
            if (a && b) {
                var d = {
                    total: {
                        barindex: 2004784,
                        post: 2004929,
                        recommend: 2004786
                    },
                    noAd: {
                        barindex: 2004785,
                        post: 2004930,
                        recommend: 2004787
                    },
                    expose: {
                        barindex: 2004768,
                        post: 2004770,
                        recommend: 2004769
                    },
                    click: {
                        barindex: 2004900,
                        post: 2004902,
                        recommend: 2004901
                    }
                }
                  , e = {
                    total: 2012454,
                    expose: 2012455,
                    click: 2012456
                }
                  , f = {
                    total: 2021681,
                    expose: 2021683,
                    click: 2021684
                }
                  , g = d[a][b];
                g && (c.monitor(g, !1),
                B && c.monitor(e[a], !1),
                z.isSpecific && c.monitor(f[a], !1))
            }
        }
    };
    return {
        init: function(a, b) {
            var c = /\bMicroMessenger\/[\d\.]+/.test(navigator.userAgent);
            c || (F.startTime = Date.now(),
            console.debug(a, "广告模块初始化..."),
            "detail" === a && (a = q()),
            b && (t[a].page = $(b)[0]),
            console.debug("AD container", t[a].container),
            window.setTimeout(function() {
                f(a)
            }, 1e3))
        },
        show: function(a, b) {
            b && (t[a].container = $(b)),
            i(a),
            console.log("ad show()", a)
        },
        justShow: function(a) {
            t[a].container && t[a].container.removeClass("ui-hide"),
            t[a].hasData === !0 && m(a)
        },
        silentFetch: function(a, c) {
            o(c) && (F.startTime = Date.now(),
            t[a].container = $(c),
            console.debug(a, "开始静默获取广告..."),
            "barindex" === a && document.addEventListener("qbrowserVisibilityChange", function(a) {
                b.isWebViewHidden = a.hidden,
                b._onScroll(),
                console.debug("qbrowserVisibilityChange", a.hidden, b.isWebViewHidden)
            }),
            f(a))
        },
        setBarInfo: function(a) {
            z = a,
            console.debug("BarInfo:", z)
        }
    }
}),
function(a, b) {
    "function" == typeof define && define.amd ? define(b) : "undefined" == typeof document ? module.exports = b() : a.TmplInline_barindex = b()
}(this, function() {
    var a = {}
      , b = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("cover")
          , f = c("pic")
          , g = c("bar_class")
          , h = c("isStarCategory")
          , i = c("star_charm_rank")
          , j = c("category_name")
          , k = c("category_rank")
          , l = c("name")
          , m = c("barTypeArr")
          , n = c("isWeiXin")
          , o = c("today_sign")
          , p = c("fans")
          , q = c("star_charm_count")
          , r = c("pids")
          , s = c("exsit")
          , t = c("intro")
          , u = "";
        u += '<div class="header-cover-img" style="background-image: url(',
        u += d(e),
        u += ');"></div> <div class="header" > <div class="cover mask-gray"></div> <div class="info" id="js_bar_info"> <div class="logo-container"> <img class="logo" src="',
        u += d(f),
        u += '" /> </div> ',
        88 != g && (u += " ",
        h ? (u += " ",
        i > 0 && 50 >= i ? (u += ' <div class="logo-rank"> <span>魅力榜:No.',
        u += d(i),
        u += "</span> </div> ") : u += ' <div class="logo-rank not-in-charm-rank"> <span >未上TOP50</span> </div> ',
        u += " ") : (u += ' <div class="logo-rank"> <span>',
        u += d(j),
        u += "类:No.",
        u += d(k),
        u += "</span> </div> "),
        u += " "),
        u += ' <div class="name-info"> <div class="labels"> <span class="name">',
        u += d(l),
        u += "</span> ";
        for (var v = 0; v < m.length && 3 > v; v++)
            u += ' <label class="',
            u += d(m[v].type),
            u += '">',
            u += d(m[v].value),
            u += "</label> ";
        return u += ' </div> <div class="info-num" id="js_bar_info_num"> ',
        h ? (u += " ",
        n ? (u += ' <label>今日签到 </label><span id="js_bar_sign_count" num="',
        u += d(o),
        u += '">',
        u += d(b.numHelper(o) || 0),
        u += "</span> ") : (u += ' <label>关注 </label><span id="js_bar_vote_count" num="',
        u += d(p),
        u += '">',
        u += d(b.numHelper(p) || 0),
        u += "</span> "),
        u += ' <span id="charm_count_wrap" class="charm-wrapper"><label>魅力 </label><span id="js_bar_charm_count" num="',
        u += d(q),
        u += '">',
        u += d(q || 0),
        u += '</span></span> <span class="charm-add-animation">+5</span> ') : (u += ' <label>话题 </label><span id="js_bar_pids_count" num="',
        u += d(r),
        u += '">',
        u += d(b.numHelper(r) || 0),
        u += "</span> ",
        n ? (u += ' <label>今日签到 </label><span id="js_bar_sign_count" num="',
        u += d(o),
        u += '">',
        u += d(b.numHelper(o) || 0),
        u += "</span> ") : (u += ' <label>关注 </label><span id="js_bar_vote_count" num="',
        u += d(p),
        u += '">',
        u += d(b.numHelper(p) || 0),
        u += "</span> "),
        u += " "),
        u += " </div> ",
        2 != s || n ? (u += ' <div class="bar-info-text" style="display: none;">',
        u += d(t),
        u += "</div> ") : (u += ' <div class="bar-info-text">',
        u += d(t),
        u += "</div> "),
        u += ' </div> <div class="sign" id="signArea"> </div> ',
        u += 2 != s || n ? ' <div class="op" id="opArea" style="display: none;"> ' : ' <div class="op" id="opArea" style="display: block;"> ',
        u += ' <a class="vote-btn btn" id="js_bar_vote_btn" href="javascript:void(0)"> <i class="vote-btn-icon"></i> 关注 </a> </div> </div> </div> '
    }
    ;
    a.bar_basic = "TmplInline_barindex.bar_basic",
    Tmpl.addTmpl(a.bar_basic, b);
    var c = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("url")
          , f = "";
        return f += '<iframe id="iframe" src="',
        f += d(e),
        f += '" style="border: none; width: 100%; height:100%; margin-bottom: 20px;"></iframe> '
    }
    ;
    a.bar_iframe_cup = "TmplInline_barindex.bar_iframe_cup",
    Tmpl.addTmpl(a.bar_iframe_cup, c);
    var d = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("posts")
          , f = c("rich2plain")
          , g = c("admin")
          , h = "";
        h += "";
        for (var i = 0; i < e.length; i++) {
            h += " ";
            var j = e[i].pid;
            window.postsRecorder[j] || (window.postsRecorder[j] = 1,
            h += ' <li class="section-1px" id="',
            h += d(e[i].pid),
            h += '" pid="',
            h += d(e[i].pid),
            h += '"> <h3> ',
            3 == e[i].type ? h += ' <label class="cls">原创</label> ' : 4 == e[i].type && (h += ' <label class="cls">招募</label> '),
            h += " ",
            "undefined" != typeof e[i].best && 1 == e[i].best && (h += ' <label class="best">精</label> '),
            h += " ",
            h += d(f(e[i].title)),
            h += " </h3> ",
            e[i].post.pic_list && e[i].post.pic_list.length && (h += ' <div class="img"> <img class="pre_load" lazy-src="',
            h += d(e[i].post.pic_list[0].url + "640"),
            h += '" style="margin-top:',
            h += d(e[i].post.pic_list[0].marginTop),
            h += '" height="',
            h += d(e[i].post.pic_list[0].h),
            h += '"/> ',
            e[i].post.pic_list.length > 1 && (h += '<span class="num">',
            h += d(e[i].post.pic_list.length),
            h += "</span>"),
            h += " </div> "),
            h += " <p>",
            h += d(f(e[i].post.content)),
            h += '</p> <div class="info ',
            h += d(e[i].post.pic_list.length ? "" : "noimg"),
            h += '"> <span class="nick"> ',
            (e[i].uin + "").indexOf("*") > -1 ? (h += " ",
            h += d(e[i].user.nick_name),
            h += "(",
            h += d(e[i].uin),
            h += ") ") : (h += " ",
            h += d(e[i].user.nick_name),
            h += " "),
            h += " ",
            "undefined" == typeof e[i].addr || "undefined" == typeof e[i].addr.province && "undefined" == typeof e[i].addr.city || (h += ' <span class="split">|</span> ',
            "undefined" != typeof e[i].addr.city ? (h += " ",
            h += d(e[i].addr.city),
            h += " ") : "undefined" != typeof e[i].addr.province && (h += " ",
            h += d(e[i].addr.province),
            h += " "),
            h += " "),
            h += ' </span> <span class="reply-num">',
            h += d(e[i].total_comment),
            h += '</span> <span class="reply-icon"></span> <span class="time">',
            h += d(e[i].time_formate),
            h += "</span> </div> ",
            g && (h += ' <div class="icons"> <a class="delete delete_post" data-pid="',
            h += d(e[i].pid),
            h += '" href="javascript:void(0)" title="删除"></a> </div> '),
            h += " </li> ")
        }
        return h += " "
    }
    ;
    a.bar_list = "TmplInline_barindex.bar_list",
    Tmpl.addTmpl(a.bar_list, d);
    var e = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("posts")
          , f = c("Date")
          , g = "";
        g += "";
        for (var h, i = 0; i < e.length; i++) {
            h = e[i];
            var j = h.pid;
            if (!window.postsRecorder[j]) {
                window.postsRecorder[j] = 1;
                var k = (h.post.pic_list,
                +new f)
                  , l = new f(h.post.start);
                g += ' <li class="section-1px act-mini" id="',
                g += d(h.pid),
                g += '" pid="',
                g += d(h.pid),
                g += '" type="',
                g += d(h.type),
                g += '" ',
                g += d("openact" === h.post.from ? 'openactid="' + h.post.openact_id + '"' : ""),
                g += "> ",
                100 == h.type && h.post.purchase_link ? (g += ' <div class="act-img-wrapper"> <img class="act-img" src="',
                g += d(h.post.pic_list && h.post.pic_list[0].url),
                g += '" /> </div> <div class="act-info music-ticket"> <h3 class="text act"> <div class="post-tags"> <label class="act">活动</label> ',
                "undefined" != typeof h.best && 1 == h.best && (g += ' <label class="best">精</label> '),
                g += " ",
                h.isTop && (g += ' <label class="rec">顶</label> '),
                g += " </div> ",
                g += d(h.title),
                g += ' </h3> <div class="act-comm-wrap"> <div class="act-comm">时间: ',
                g += d(h.post.time),
                g += '</div> <div class="act-comm act-address">地点: ',
                g += d(h.post.addr),
                g += '</div> </div> <div class="price">',
                g += d(h.post.price),
                g += '</div> <div class="purchase-button" data-src="',
                g += d(h.post.purchase_link),
                g += '">我要购票</div> </div> ') : 100 == h.type && (g += ' <div class="act-img-wrapper"> <img class="act-img" src="',
                g += d(h.post.pic_list && h.post.pic_list[0].url),
                g += '" /> </div> <div class="act-info"> <h3 class="text act"> ',
                (1 == h.best || h.isTop) && (g += ' <div class="post-tags"> ',
                1 == h.best && (g += ' <label class="best">精</label> '),
                g += " ",
                h.isTop && (g += ' <label class="rec">顶</label> '),
                g += " </div> "),
                g += d(h.title),
                g += ' </h3> <div class="act-comm-wrap"> <div class="single-ellipsis">',
                g += d(h.post.addr),
                g += '</div> <div class="time">',
                g += d(["周" + ["日", "一", "二", "三", "四", "五", "六"][l.getDay()], [l.getMonth() + 1 + "/" + l.getDate()], (l.getHours() < 10 ? "0" : "") + l.getHours() + ":" + (l.getMinutes() < 10 ? "0" : "") + l.getMinutes()].join("&nbsp;")),
                g += '</div> <div class="status">',
                k > h.post.end ? g += '<span class="tag-expire"></span>' : (g += '<span class="people-num">',
                g += d(h.subscribers),
                g += "</span>人已报名"),
                g += "</div> </div> </div> "),
                g += " </li> "
            }
        }
        return g += " "
    }
    ;
    a.bar_list_tab_act = "TmplInline_barindex.bar_list_tab_act",
    Tmpl.addTmpl(a.bar_list_tab_act, e);
    var f = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("localStorage")
          , f = c("JSON")
          , g = c("admin_ext")
          , h = c("len")
          , i = c("feeds_list")
          , j = c("Number")
          , k = c("Date")
          , l = (c("type"),
        c("post_type"))
          , m = c("gbar")
          , n = (c("name"),
        c("pic"),
        c("feeds"),
        c("nick_name"))
          , o = c("avator")
          , p = c("is_show_top")
          , q = c("uin")
          , r = c("sub_feed_type")
          , s = c("imgHandle")
          , t = c("sysmsg")
          , u = c("profile_uin")
          , v = c("honourHelper")
          , w = c("FormatTime")
          , x = c("iforward")
          , y = c("plain2rich")
          , z = c("reply")
          , A = "";
        A += " ";
        var B = e.getItem("clickedMap");
        if (B = B ? f.parse(B) : {},
        window.honourHelper && window.honourHelper.isAdmin)
            var C = window.honourHelper.isAdmin(g);
        for (var D = 0, h = i.length; h > D; D++) {
            i[D] = b.checkSysMsg(i[D]);
            var E = i[D].pid ? i[D].pid.trim() : ""
              , F = i[D].bid
              , G = i[D].ts || i[D].time
              , H = i[D].ft
              , I = (i[D].subscribed,
            i[D].best)
              , J = B[E]
              , K = 360
              , L = j(E.split("-")[1])
              , M = (k.now() / 1e3 - L) / 60 < K;
            if (window.postsRecorders && 100 != H && 10 != H) {
                if (window.postsRecorders[E])
                    continue;window.postsRecorders[E] = 1
            }
            var r, N = i[D] || {
                post: {},
                type: ""
            }, l = N.type, m = i[D].gbar || {
                name: "",
                pic: ""
            };
            r = N.post.video_list && N.post.video_list.length || "201" == l ? "video" : N.post.audio_list && N.post.audio_list.length ? "audiofeed" : N.post.qqmusic_list && N.post.qqmusic_list.length ? "musicaudio" : "100" == l || "101" == l ? "activity" : "600" == l ? "pkfeed" : "400" == l ? "piclistfeed" : "401" == l ? "pictextfeed" : "102" == l ? "buluoqun" : "normal";
            var n, q, o, p = !1, O = 0, P = !1, Q = "", t = 100 == H, x = 5 == H, u = (i[D].uin + "").indexOf("*") > -1 ? "" : i[D].uin;
            if (0 == H ? (n = i[D].user.nick_name,
            O = i[D].user.vipno,
            Q = b.renderVip(i[D].user.vipno),
            o = b.useSmallAvator(i[D].user.pic),
            q = i[D].user.uin,
            P = !0) : (n = m.name + "部落",
            o = m.pic,
            p = !0,
            q = !1),
            A += " ",
            N.ad)
                A += ' <li class="gdt-ad"> </li> ';
            else if ("buluoqun" === r) {
                if (A += ' <li class="new-feed new-feed-buluoqun" data-pid="',
                A += d(E),
                A += '" data-bid="',
                A += d(F),
                A += '" data-gc="',
                A += d(N.post.group_code),
                A += '" data-isjoined="',
                A += d(N.post.is_joined),
                A += '"> <div class="buluoqun-header"> ',
                0 === N.member_num)
                    A += ' <img src="',
                    A += d(s.defaultAvatar),
                    A += '" class="buluoqun-avatar" lazy-src="',
                    A += d("http://q.qlogo.cn/g?b=qq&nk=" + N.user.uin + "&s=100"),
                    A += '"> <span class="buluoqun-info">',
                    A += d(N.user.nick_name),
                    A += "创建了部落群</span> ";
                else if (N.member_array) {
                    for (var R = 0; R < N.member_array.length; R++) {
                        var q = N.member_array[R].uin;
                        if (3 === R)
                            break;
                        A += ' <img src="',
                        A += d(s.defaultAvatar),
                        A += '" class="buluoqun-avatar" lazy-src="',
                        A += d("http://q.qlogo.cn/g?b=qq&nk=" + q + "&s=100"),
                        A += '"> '
                    }
                    A += ' <span class="buluoqun-info">',
                    A += d(N.member_num),
                    A += "人正在部落群讨论</span> "
                }
                A += ' </div> <div class="buluoqun-title">',
                N.top && (A += '<span class="buluoqun-top">顶</span>'),
                A += '<span class="buluoqun-tag">部落群</span>',
                A += d(N.title),
                A += '</div> <div class="buluoqun-content">',
                A += d(N.post.content),
                A += "</div> </li> "
            } else {
                if (A += ' <li id="',
                A += d(E),
                A += '" class="ui-ignore-space section-1px new-feed ',
                A += d(J ? "clicked" : ""),
                A += " ",
                A += d(t ? "sysmsg-feed" : ""),
                A += '" data-redeem="',
                A += d(i[D].source),
                A += '" data-bid="',
                A += d(F),
                A += '" data-pid="',
                A += d(E),
                A += '" data-ts="',
                A += d(G),
                A += '" data-feed="',
                A += d(H),
                A += '" data-type="',
                A += d(N.type),
                A += '" data-from="',
                A += d(N.from),
                A += '" ',
                A += d("openact" === N.post.from ? 'openactid="' + N.post.openact_id + '"' : ""),
                A += " ",
                A += d(t ? i[D].dataBind : ""),
                A += '> <div class="user-wrap" data-uin="',
                A += d(u),
                A += '"> <div class="user-avatar"> <img src="',
                A += d(s.defaultAvatar),
                A += '" lazy-src="',
                A += d(N.user && N.user.pic ? N.user.pic : "http://q.qlogo.cn/g?b=qq&nk=0&s=100"),
                A += '"/> </div> <div class="name-wrap"> <div class="name-section1"> <span class="author nick ',
                A += d(N.user.vipno ? " vip" : ""),
                A += '" data-profile-uin="',
                A += d(u),
                A += '"> ',
                A += d(N.user.nick_name),
                A += " </span> ",
                A += d(v.renderHonours(N.user, F)),
                A += ' </div> <span class="post-datetime">',
                A += d(w(G)),
                A += '</span> </div> </div> <div class="go-detail',
                A += d(x ? " fcontent" : ""),
                A += '"> <div class="report-content"> <p class="grouptitle feed-two-line"> ',
                "undefined" != typeof I && 1 == I ? A += ' <label class="best">精</label> ' : M && (A += ' <label class="new">新</label> '),
                A += " ",
                "pictextfeed" === r ? A += ' <span class="piclist-icon">文集</span> ' : "piclistfeed" === r ? A += ' <span class="piclist-icon">图集</span> ' : "pkfeed" === r && (A += ' <span class="pk-icon">投票</span> '),
                A += " ",
                A += d(y(N.title.trim() || "")),
                A += " </p> ",
                "pictextfeed" != r && "pictextfeed" != r && "pkfeed" != r && "activity" != r && (A += ' <p class="groupbrief feed-two-line">',
                A += d(y(b.getFeedBref(N.post.content), "", N.post.urlInfo, !0)),
                A += "</p> "),
                A += " </div> ",
                A += " ",
                "normal" === r) {
                    if (N.post.pic_list && N.post.pic_list.length) {
                        var S = b.getNormalFeeds(N);
                        A += ' <div class="img-wrap clearfix"> ';
                        for (var T = 0; T < S.length; T++)
                            A += ' <div class="img-ph feed-img ',
                            A += d(z ? "report" : ""),
                            A += '" style="',
                            A += d(S[T].styleStr),
                            A += '"> <img hidebg="true" noSize="',
                            A += d(S[T].noSize),
                            A += '" lazy-src="',
                            A += d(s.getThumbUrl(S[T].picList[T].url, "200")),
                            A += '" style="',
                            A += d(S[T].imageStyle),
                            A += '" hideSmallImg="true"/> ',
                            2 == T && (A += ' <span class="total-img"><span class="total-img-text">',
                            A += d(S[T].totalLength),
                            A += "</span></span> "),
                            A += " ",
                            S[T].picList[T].type_info && "gif" === S[T].picList[T].type_info.type && (A += ' <span class="img-gif"></span> '),
                            A += " </div> ";
                        A += " </div> "
                    }
                    A += ' <div class="groupbody clearfix"> <div class="icon-wrap left"> <i class="reply-icon"></i> <span class="groupreply">',
                    A += d(i[D].total_comment_v2 || i[D].total_comment || 0),
                    A += '</span> <i class="seperator"></i> <i class="read-icon"></i> <span class="readnum">',
                    A += d(i[D].readnum || 0),
                    A += '</span> </div> <div class="feed-user-time"> ',
                    C && (A += ' <a class="delete delete_post" data-pid="',
                    A += d(E),
                    A += '" data-bid="',
                    A += d(F),
                    A += '" href="javascript:void(0)" >删除</a> '),
                    A += " </div> </div> "
                } else if ("musicaudio" === r || "audiofeed" === r || "video" === r) {
                    if ("musicaudio" === r) {
                        var U = b.getMusicFeeds(N);
                        A += ' <div class="feed-music"> <span class="type-icon musicaudio"></span> <div class="feed-music-img-container"> <div class="feed-mask"></div> <img class="feed-music-img" lazy-src="',
                        A += d(s.getThumbUrl(U.url, "200")),
                        A += '"/> </div> <div class="feed-music-text"> <p class="feed-music-title feed-one-line">',
                        A += d(U.title),
                        A += '</p> <p class="feed-music-desc feed-one-line">',
                        A += d(U.desc),
                        A += "</p> </div> </div> "
                    } else if ("audiofeed" === r) {
                        var V = b.getAudioFeeds(N);
                        A += ' <div class="feed-audio"> <span class="type-icon audiofeed"></span> <div class="feed-audio-img-container"> <div class="feed-mask"></div> <img class="feed-audio-img" lazy-src="',
                        A += d(s.getThumbUrl(V.url, "200")),
                        A += '"/> </div> <div class="feed-audio-text"> <div class="audio-time">',
                        A += d(V.time),
                        A += '</div> <div class="audio-boxing"></div> </div> </div> '
                    } else {
                        var W = b.getVideoFeeds(N);
                        A += ' <div class="feed-video" style="height:',
                        A += d(W.height),
                        A += 'px;"> <span class="type-icon video"></span> <img noSize="',
                        A += d(W.noSize),
                        A += '" class="feed-video-img" lazy-src="',
                        A += d(s.getThumbUrl(W.url, "200")),
                        A += '"/> </div> '
                    }
                    A += ' <div class="groupbody clearfix"> <div class="icon-wrap left"> <i class="reply-icon"></i> <span class="groupreply">',
                    A += d(i[D].total_comment_v2 || i[D].total_comment || 0),
                    A += '</span> <i class="seperator"></i> <i class="read-icon"></i> <span class="readnum">',
                    A += d(i[D].readnum || 0),
                    A += '</span> </div> <div class="feed-user-time"> ',
                    C && (A += ' <a class="delete delete_post" data-pid="',
                    A += d(E),
                    A += '" data-bid="',
                    A += d(F),
                    A += '" href="javascript:void(0)" >删除</a> '),
                    A += " </div> </div> "
                } else if ("activity" === r) {
                    var X = b.getActivityFeeds(N);
                    b.setSubscribers(i[D], "barindex"),
                    A += ' <div class="item-join clearfix"> <div class="join-left"> <img class="join-image" lazy-src="',
                    A += d(s.getThumbUrl(X.url, "200")),
                    A += '" style="',
                    A += d(X.joinImgStyle),
                    A += '"> </div> <div class="join-info-bottom"> <p class="join-icon join-info-time">时间：',
                    A += d(b.renderActDate(N.post.start, N.post.end)),
                    A += '</p> <p class="join-icon join-info-addr">地点：',
                    A += d(N.post.addr),
                    A += '</p> </div> <div class="pk-info-bottom clearfix"> <div class="info-bottom-left"><span class="activity-icon">活动</span>',
                    A += d(X.condition),
                    A += '</div> <div class="info-bottom-right"><span class="pk-count-icon"></span>',
                    A += d(i[D].subscribers || "0"),
                    A += "人参与</div> </div> </div> "
                } else if ("pkfeed" === r) {
                    var Y = b.getPKFeeds(N);
                    A += ' <div class="item-pk clearfix"> ',
                    0 === Y.pklisk.length ? (A += ' <div class="clearfix pk-text-content"> <div class="pk-text-item"><i class="pk-text-icon"></i>',
                    A += d(Y.aSideContent),
                    A += '</div> <div class="pk-text-item"><i class="pk-text-icon"></i>',
                    A += d(Y.bSideContent),
                    A += "</div> </div> ") : (A += ' <div class="pk-left img-ph"> <div class="clearfix pk-wrap"> <div class="pk-img-wrap" style="width:41px;height:85px;float:left;"> <img class="pk-img" src="',
                    A += d(Y.pklisk[0].url),
                    A += '" style="',
                    A += d(Y.pklisk[0].style),
                    A += '"/> </div> <div class="pk-bubb"></div> <div class="pk-divide"></div> <div class="pk-img-wrap hack-skew" style="width:48px;height:85px;float:left;overflow: hidden;"> <img class="pk-img" src="',
                    A += d(Y.pklisk[1].url),
                    A += '" style="',
                    A += d(Y.pklisk[1].style),
                    A += '"/> </div> </div> </div> <div class="pk-info-wrap"> <p class="pk-info-title feed-one-line">',
                    A += d(Y.pktitle),
                    A += '</p> <p class="pk-info-desc feed-two-line">',
                    A += d(y(b.getFeedBref(Y.content), "", N.post.urlInfo, !0)),
                    A += "</p> </div> "),
                    A += ' <div class="pk-info-bottom clearfix"> <div class="info-bottom-left"><span class="pk-condition">',
                    A += d(Y.condition),
                    A += '</span></div> <div class="info-bottom-right"><span class="pk-count-icon"></span>',
                    A += d(Y.count),
                    A += "人参与</div> </div> </div> "
                } else if ("piclistfeed" === r) {
                    var Z = b.getPicFeeds(N);
                    A += ' <div class="img-wrap clearfix"> ';
                    for (var T = 0; T < Z.piclist.length; T++)
                        A += ' <div class="img-ph feed-img piclist-img" style="',
                        A += d(2 === T ? "margin-right:0px;" : ""),
                        A += "width:",
                        A += d(Z.width),
                        A += "px;height:",
                        A += d(Z.width),
                        A += 'px;"> <img hidebg="true" lazy-src="',
                        A += d(s.getThumbUrl(Z.piclist[T].url, "200")),
                        A += '" style="width:',
                        A += d(Z.piclist[T].width),
                        A += "px; height:",
                        A += d(Z.piclist[T].height),
                        A += "px;margin-left:",
                        A += d(Z.piclist[T].marginLeft),
                        A += "px; margin-top:",
                        A += d(Z.piclist[T].marginRight),
                        A += 'px;"/> </div> ';
                    A += ' </div> <div class="groupbody piclist clearfix"> <div class="feed-user-time"> ',
                    C && (A += ' <a class="delete delete_post" data-pid="',
                    A += d(E),
                    A += '" data-bid="',
                    A += d(F),
                    A += '" href="javascript:void(0)" >删除</a> '),
                    A += ' <div class="piclist-share left"><span class="piclist-share-icon"></span>',
                    A += d(Z.share),
                    A += "张晒图</div> </div> </div> "
                } else if ("pictextfeed" === r) {
                    var $ = b.getPictextFeeds(N);
                    if (A += ' <div class="img-wrap clearfix"> ',
                    $.piclist.length)
                        for (var T = 0; T < $.piclist.length; T++)
                            A += ' <div class="pictext-img" style="',
                            A += d(2 === T ? "margin-right:0px;" : ""),
                            A += ';"> <p class="pictext-title feed-one-line">',
                            A += d($.piclist[T].title),
                            A += '</p> <p class="pictext-content feed-one-line"> ',
                            A += d(y(b.getFeedBref($.piclist[T].content), "", N.post.urlInfo, !0)),
                            A += " </p> </div> ";
                    else
                        A += ' <div class="groupbrief feed-two-line">',
                        A += d(y(b.getFeedBref(N.post.content), "", N.post.urlInfo, !0)),
                        A += " ";
                    A += ' </div> <div class="groupbody piclist clearfix"> <div class="feed-user-time"> ',
                    C && (A += ' <a class="delete delete_post" data-pid="',
                    A += d(E),
                    A += '" data-bid="',
                    A += d(F),
                    A += '" href="javascript:void(0)" >删除</a> '),
                    A += ' <div class="piclist-share left"><span class="pictextlist-share-icon"></span>',
                    A += d($.share),
                    A += "个话题</div> </div> </div> "
                }
                A += " </div> </li> "
            }
            A += " "
        }
        return A += " "
    }
    ;
    a.bar_list_text = "TmplInline_barindex.bar_list_text",
    Tmpl.addTmpl(a.bar_list_text, f);
    var g = function(a, b) {
        a = a || {};
        var c = "";
        return c += '<div id="js_relative_main"> <div class="ui-top-bar js-no-bounce hide"> <div class="ui-tab page1 js-active"> <ul class="ui-groupbutton" role="tablist"> <li class="ui-button act_tab" data-toggle="tab" id=\'hot\' data-target="#page1-tab2" aria-controls="tab2" role="tab" data-index="1">热门群 </li> <li class="ui-button act_tab" data-toggle="tab" data-target="#page1-tab1" id=\'city\' aria-controls="tab1" role="tab" data-index="0">同城群 </li> </ul> </div> </div> <div class="ui-page page1 js-active" id="page1"> <div class="ui-page-content"> <div class="ui-item ui-ignore-space arrow-right guide-white hide" id="bar_guide" data-type="no_sub"> <a> <div class="my-item-left"><img src="http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/gl_icon.png" class="group-icon"></div> <div class="my-item-right"> <p class="ui-no-wrap grouptitle">设置我的关联群</p> <p class="ui-gray ui-no-wrap">想在这里展示你的群吗？立即加入</p> </div> </a> </div> <div class="ui-tab ui_page" id="hot_page" role="tabpanel"> <ul class="ui-list ui-spliter-ios" id="hot_list" > </ul> <div class="loading" id="hot_list_loading" >载入中，请稍候</div> </div> <div class="ui-tab js-active ui_page" id="city_page" role="tabpanel"> <ul class="ui-list hide ui-spliter-ios" id="city_list" > </ul> <div class="loading" id="city_list_loading" >载入中，请稍候</div> </div> <div class="ui-tab ui_page" id="page_wechat"> <ul class="ui-list hide ui-spliter-ios" id="wechat_list"> </ul> <div class="loading" id="wechat_list_loading" ><span>载入中，请稍候</span></div> </div> </div> </div> </div> '
    }
    ;
    a.bar_relativegroup = "TmplInline_barindex.bar_relativegroup",
    Tmpl.addTmpl(a.bar_relativegroup, g);
    var h = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("exsit")
          , f = c("is_star_game")
          , g = c("level")
          , h = (c("QQ"),
        c("isStarCategory"))
          , i = c("isQQ")
          , j = c("showCalendarEntrance")
          , k = c("isGameAppTribe")
          , l = c("sign")
          , m = c("scoreInfo")
          , n = (c("opername"),
        c("module"),
        c("action"),
        c("ver1"),
        c("Util"))
          , o = c("Q")
          , p = "";
        if (p += "",
        1 === e) {
            p += ' <div class="info-grade ',
            p += d(f ? "js-hidden" : ""),
            p += "\" id=\"js_bar_grade\"> <div class='info-grade-wrap'> <div class='info-level jump_level' id='info_level' > <span class='jump_level l-level lv",
            p += d(g.level),
            p += "' >LV.",
            p += d(g.level),
            p += " ",
            p += d(g.level_title),
            p += "</span><span class='info-name jump_level'></span> </div> <div class='l-bar jump_level' id='level_bar' data-point='",
            p += d(g.point),
            p += "'><span style='width:",
            p += d(g.point / g.next * 100),
            p += "%; ' class='l-inner-bar jump_level'></span></div> </div> </div> ";
            var q = h && i
              , r = h && i && j;
            if (k = k && i,
            p += " ",
            k && !f && (p += ' <a id="game-btn" class="btn"></a> '),
            p += " ",
            r && !f && (p += ' <a id="sendHeartBtn" class="send-heart-btn btn is-star-category"><i class="heart-icon"><span class="heart-scale-animation"></span></i>送心</a> '),
            p += " ",
            1 == l) {
                if (p += " ",
                r) {
                    var s = m.continueDays > 999 ? 999 : m.continueDays
                      , t = {
                        opername: "Grp_tribe",
                        module: "tribe_hp",
                        action: "exp_signed",
                        ver1: n.queryString("bid") || n.getHash("bid")
                    };
                    o.tdw(t),
                    p += ' <a id="signBtn" class="sign-btn btn signed is-star-category"><i class="signed-icon"><span class="signed-days-count">',
                    p += d(s),
                    p += "</span></i>已签</a> "
                } else if (q)
                    p += ' <a id="sendHeartBtn" class="send-heart-btn btn disable"><i class="heart-icon"><span class="heart-scale-animation"></span></i>送心</a> ';
                else if (k) {
                    var s = m.continueDays > 999 ? 999 : m.continueDays;
                    p += ' <a id="signBtn" class="sign-btn btn signed is-star-category"><i class="signed-icon"><span class="signed-days-count">',
                    p += d(s),
                    p += "</span></i>已签</a> "
                } else {
                    var s = m.continueDays > 999 ? 999 : m.continueDays
                      , u = m.continueDays > 1 ? "签到" + s + "天" : "已签";
                    p += ' <a id="signBtn" class="sign-btn btn signed"><i class="signed-icon"></i>',
                    p += d(u),
                    p += "</a> "
                }
                p += " "
            } else
                p += " ",
                p += r || k ? ' <a id="signBtn" class="sign-btn btn is-star-category"><i class="sign-icon"></i>签到</a> ' : ' <a id="signBtn" class="sign-btn btn"><i class="sign-icon"></i>签到</a> ',
                p += " ";
            p += " "
        }
        return p += " "
    }
    ;
    a.bar_sign = "TmplInline_barindex.bar_sign",
    Tmpl.addTmpl(a.bar_sign, h);
    var i = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("starid")
          , f = c("starinfo")
          , g = "";
        return g += "",
        e && f && (f.title || f.link) && (g += " "),
        g += " "
    }
    ;
    a.bar_star_info = "TmplInline_barindex.bar_star_info",
    Tmpl.addTmpl(a.bar_star_info, i);
    var j = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("sub_number")
          , f = "";
        return f += '<h4 class="sub_bar_title">订阅部落</h4> <i class="sub_rignt_icon"></i> ',
        e ? (f += ' <span class="sub_info">我有',
        f += d(e),
        f += "个群订阅了该部落</span> ") : f += ' <span class="sub_info">让群与部落联动起来</span> ',
        f += " "
    }
    ;
    a.bar_subscribe_info = "TmplInline_barindex.bar_subscribe_info",
    Tmpl.addTmpl(a.bar_subscribe_info, j);
    var k = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("recommend")
          , f = c("bar_class")
          , g = c("best_total")
          , h = c("related_bar")
          , i = c("bid")
          , j = "";
        if (j += "",
        0 == e.length)
            j += ' <div class="top_list-wrap"> ',
            77 == f && g && (j += " "),
            j += " ",
            h && (j += ' <div class="top-related-wrap" data-bid="',
            j += d(h.related_bid),
            j += '"> <div class="top-related section-1px" data-bid="',
            j += d(h.related_bid),
            j += '"> <img class="logo" src="',
            j += d(h.pic),
            j += '"> <span>',
            j += d(h.name),
            j += '</span> <span class="text">',
            j += d(h.text),
            j += "</span> </div> </div> "),
            j += " </div> ";
        else {
            j += ' <div class="top-list-wrap"> ',
            77 == f && g && (j += " "),
            j += " ",
            h && (j += ' <div class="top-related-wrap" data-bid="',
            j += d(h.related_bid),
            j += '"> <div class="top-related section-1px"> <img class="logo" src="',
            j += d(h.pic),
            j += '"> <span>',
            j += d(h.name),
            j += '</span> <span class="text">',
            j += d(h.text),
            j += "</span> </div> </div> "),
            j += " ";
            for (var k = 0; k < e.length; k++) {
                j += " ";
                var l = e[k];
                j += ' <div class="top-list"> ',
                j += ' <div class="top-list-item"> ',
                j += ' <a data-href="detail&bid=',
                j += d(i),
                j += "&pid=",
                j += d(l.pid),
                j += '" class="link" data-bid="',
                j += d(i),
                j += '" data-pid="',
                j += d(l.pid),
                j += '" data-type="',
                j += d(l.type),
                j += '"> <label class="rec">顶</label> ',
                401 === l.type ? j += ' <span class="piclist-icon">文集</span> ' : 400 === l.type && (j += ' <span class="piclist-icon">图集</span> '),
                j += " ",
                "employ" === l.type && (j += ' <span class="piclist-icon">招募</span> '),
                j += ' <span class="name">',
                j += d(l.title),
                j += "</span> </a> </div> </div> "
            }
        }
        return j += " </div> "
    }
    ;
    a.bar_top = "TmplInline_barindex.bar_top",
    Tmpl.addTmpl(a.bar_top, k);
    var l = function(a, b) {
        function c(b) {
            return d("undefined" == typeof a[b] ? this[b] : a[b])
        }
        function d(a) {
            return "undefined" == typeof a ? "" : a
        }
        a = a || {};
        var e = c("tab1")
          , f = c("bar_class")
          , g = c("tab2")
          , h = c("tab3")
          , i = c("hideReQun")
          , j = c("tab4")
          , k = c("ls")
          , l = c("len")
          , m = "";
        if (m += '<div class="new-tab-list"> ',
        e ? (m += ' <div><a class="myTab_1_link tab_tribe" href="javascript:void(0)" data-tab="tribe">',
        m += d(e.name),
        m += "</a></div> ") : (m += " ",
        m += 77 == f ? ' <div><a id="tab_best" href="javascript:void(0)" class="mulu" data-tab="tribe">连载</a></div> ' : ' <div><a id="tab_best" href="javascript:void(0)" data-tab="tribe">精华</a></div> ',
        m += " "),
        m += " ",
        g ? (m += ' <div><a class="myTab_2_link" href="javascript:void(0)" data-tab="topic" data-cls="2">',
        m += d(g.name),
        m += "</a></div> ") : m += ' <div><a id="tab_hof" href="javascript:void(0)" data-tab="hof" data-cls="2">名人堂</a></div> ',
        m += " ",
        h ? (m += ' <div><a class="myTab_3_link" href="javascript:void(0)" data-tab="topic" data-cls="2">',
        m += d(h.name),
        m += "</a></div> ") : (m += " ",
        m += i ? ' <div><a id="tab_more" href="javascript:void(0)" data-tab="more">更多<span id="m_point" class="m-point"></span></a></div> ' : ' <div><a id="tab_qun" class="tab_qun" href="javascript:void(0)" data-tab="qun">相关群</a></div> ',
        m += " "),
        m += " ",
        j ? (m += " <div><a ",
        "分类" === j.name && (m += ' id="tab_classify" '),
        m += ' class="myTab_4_link " href="javascript:void(0)" data-tab="topic" data-cls="2">',
        m += d(j.name),
        m += "</a></div> ") : m += ' <div><a id="tab_album" href="javascript:void(0)" data-tab="hof" data-cls="2">相册</a></div> ',
        m += " ",
        k && k.length > 0) {
            m += " ";
            for (var n = 0, l = k.length; l > n; n++) {
                m += " ";
                var o = k[n];
                m += " ",
                o.mid < 5 || (m += " ",
                i && "相关群" === o.name ? m += ' <div><a id="tab_more" href="javascript:void(0)" data-tab="more">更多<span id="m_point" class="m-point"></span></a></div> ' : "相册" === o.name && o.replaced ? m += ' <div><a id="tab_album" href="javascript:void(0)" data-tab="hof" data-cls="2">相册</a></div> ' : (m += ' <div><a class="myTab_',
                m += d(o.mid),
                m += '_link" href="javascript:void(0)" data-tab="topic" data-cls="2"><div>',
                m += d(o.name),
                m += "</div></a></div> "),
                m += " ")
            }
            m += " "
        }
        if (m += " </div> ",
        e && e.css && (m += ' <link media="all" rel="stylesheet" href="',
        m += d(e.css),
        m += '" type="text/css" /> '),
        m += " ",
        g && g.css && (m += ' <link media="all" rel="stylesheet" href="',
        m += d(g.css),
        m += '" type="text/css" /> '),
        m += " ",
        h && h.css && (m += ' <link media="all" rel="stylesheet" href="',
        m += d(h.css),
        m += '" type="text/css" /> '),
        m += " ",
        j && j.css && (m += ' <link media="all" rel="stylesheet" href="',
        m += d(j.css),
        m += '" type="text/css" /> '),
        m += ' <style type="text/css"> ',
        e && (m += " .tab_video:before,.myTab_1_link:before,.tab_tribe:before{ ",
        e.icon_pre && (m += " background-image: url(",
        m += d(e.icon_pre),
        m += ") !important; "),
        m += " } "),
        m += " ",
        g && (m += " .tab_video:before,.myTab_2_link:before,.myTab_2:before{ ",
        g.icon_pre && (m += " background-image: url(",
        m += d(g.icon_pre),
        m += ") !important; "),
        m += " } "),
        m += " ",
        h && (m += " .tab_video:before,.myTab_3_link:before,.myTab_3:before{ ",
        h.icon_pre && (m += " background-image: url(",
        m += d(h.icon_pre),
        m += ") !important; "),
        m += " } "),
        m += " ",
        j && (m += " .tab_video:before,.myTab_4_link:before,.myTab_4:before{ ",
        j.icon_pre && (m += " background-image: url(",
        m += d(j.icon_pre),
        m += ") !important; "),
        m += " } "),
        m += " ",
        k && k.length > 0) {
            m += " ";
            for (var n = 0, l = k.length; l > n; n++) {
                m += " ";
                var o = k[n];
                m += " .tab_video:before,.myTab_",
                m += d(o.mid),
                m += "_link:before,.myTab_",
                m += d(o.mid),
                m += ":before{ ",
                o.icon_pre && (m += " background-image: url(",
                m += d(o.icon_pre),
                m += ") !important;"),
                m += " } "
            }
            m += " "
        }
        return m += " </style> "
    }
    ;
    a.bar_uitest_tab = "TmplInline_barindex.bar_uitest_tab",
    Tmpl.addTmpl(a.bar_uitest_tab, l);
    var m = '<div class="header-cover-img" style="background-image: url({{movie_info.vertical_pic || pic}});"></div>\r\n<div class="header">\r\n    <div class="cover mask-gray"></div>\r\n    <div class="mask-white"></div>\r\n    <div class="special-info" id="js_bar_info">\r\n        <div class="logo-container" id="js_bar_logo">\r\n            <img soda-src="{{movie_info.vertical_pic || pic}}" class="logo" alt=""/>\r\n        </div>\r\n        <div class="text-info">\r\n            <div class="name" soda-bind-html="name"></div>\r\n            <div class="rating-container {{ (movie_info.noScore) ? \'rate-no-score-container\' : \'\'}} ">\r\n                <div class="rate-star" style="background-position: 0 {{movie_info.scoreBarPosition}}px"></div>\r\n                <span class="rate-score {{ (movie_info.noScore) ? \'rate-no-score\' : \'\'}}">{{movie_info.score}}</span>\r\n            </div>\r\n            <div class="category-and-date">\r\n                <span class="start-date">\r\n                    上映{{movie_info.pub_date ? \'时间：\' + movie_info.pub_date : (movie_info.pub_year ? \'年份：\' + movie_info.pub_year : \'时间：暂无\')}}\r\n                </span>\r\n\r\n            </div>\r\n            <div class="tags" >类型：<span soda-bind-html="movie_info.label || \'暂无\'"></span></div>\r\n\r\n            <div class="actors">演员：<span soda-bind-html="movie_info.leading_actor || \'暂无\'"></span></div>\r\n            <div class="buluo-info">话题：{{display_pids}} &nbsp;&nbsp;&nbsp;&nbsp;关注：{{display_fans}}</div>\r\n        </div>\r\n        <div class="intro-wrapper" soda-if="movie_info.description">\r\n\r\n            <div class="intro lastline-space-ellipsis" id="js_go_intro" title="{{movie_info.description}}" ><span id="js_go_intro_arrow" class="right-arrow"></span></div>\r\n        </div>\r\n\r\n\r\n\r\n        <div class="btn-area">\r\n            <div class="{{hasTicket ? \'btn-buy\' : \'btn-see\'}}" data-pid="{{movie_info.movie_pid}}" id="js_op_movie">{{hasTicket ? \'立即购票\' : \'看电影\'}}</div>\r\n            <div class="btn-subscribe btn-movie" id="js_movie_focus_btn" soda-if="exsit == 2">\r\n                关注\r\n            </div>\r\n            <div class="btn-movie btn-movie-sign" id="js_movie_sign_btn" soda-if="exsit == 1 && sign == 2">\r\n                <i class="subscribed-unsign-icon"></i>签到\r\n            </div>\r\n            <div class="btn-movie btn-movie-sign btn-movie-signed" id="js_bar_sign_btn" soda-if="exsit == 1 && sign == 1">\r\n                <i class="subscribed-icon"></i>{{ continue > 1 ? \'签到\' + continue + \'天\' : \'已签到\'}}\r\n            </div>\r\n\r\n        </div>\r\n    </div>\r\n</div>\r\n';
    a.bar_special_basic = "TmplInline_barindex.bar_special_basic",
    Tmpl.addTmpl(a.bar_special_basic, m);
    var n = '<div class="header-cover-img" style="background-image: url({{movie_info.vertical_pic || pic}});"></div>\r\n<div class="header">\r\n    <div class="cover mask-gray"></div>\r\n    <div class="mask-white"></div>\r\n    <div class="special-info" id="js_bar_info">\r\n        <div class="logo-container" id="js_bar_logo">\r\n            <img soda-src="{{movie_info.vertical_pic || pic}}" class="logo" alt=""/>\r\n        </div>\r\n        <div class="text-info">\r\n            <div class="name" soda-bind-html="name"></div>\r\n            <div class="rating-container {{ (movie_info.noScore) ? \'rate-no-score-container\' : \'\'}} ">\r\n                <div class="rate-star" style="background-position: 0 {{movie_info.scoreBarPosition}}px"></div>\r\n                <span class="rate-score {{ (movie_info.noScore) ? \'rate-no-score\' : \'\'}}">{{movie_info.score}}</span>\r\n            </div>\r\n            <div class="category-and-date">\r\n                <span class="start-date">\r\n                    首播：{{movie_info.publish_year ? movie_info.publish_year : \'暂无\'}}\r\n                </span>\r\n                <span class="updated-episode" soda-bind-html="movie_info.episodeupdated || \'\'"></span>\r\n            </div>\r\n            <div class="tags" >类型：<span soda-bind-html="movie_info.label || \'暂无\'"></span></div>\r\n            <div class="actors">主演：<span soda-bind-html="movie_info.leading_actor || \'暂无\'"></span></div>\r\n            <div class="buluo-info">话题：{{display_pids}}&nbsp;&nbsp;&nbsp;&nbsp;关注：{{display_fans}}</div>\r\n        </div>\r\n\r\n        <div class="btn-area">\r\n            <div class="btn-see" data-mediaexist="{{movie_info.mediaexist}}" id="js_op_movie">看电视剧</div>\r\n            <div class="btn-subscribe btn-teleplay" id="js_movie_focus_btn" soda-if="exsit == 2">\r\n                关注\r\n            </div>\r\n            <div class="btn-teleplay btn-movie-sign" id="js_movie_sign_btn" soda-if="exsit == 1 && sign == 2">\r\n                签到\r\n            </div>\r\n            <div class="btn-teleplay btn-movie-sign btn-movie-signed" id="js_bar_sign_btn" soda-if="exsit == 1 && sign == 1">\r\n                <i class="subscribed-icon"></i>\r\n                {{ continue > 1 ? \'签到\' + continue + \'天\' : \'已签\'}}\r\n            </div>\r\n\r\n        </div>\r\n    </div>\r\n</div>\r\n';
    a.bar_special_teleplay = "TmplInline_barindex.bar_special_teleplay",
    Tmpl.addTmpl(a.bar_special_teleplay, n);
    var o = '<div class="header-cover-img" style="background-image: url({{movie_info.vertical_pic || pic}});"></div>\r\n<div class="header">\r\n    <div class="cover mask-gray"></div>\r\n    <div class="mask-white"></div>\r\n    <div class="special-info" id="js_bar_info">\r\n        <div class="logo-container" id="js_bar_logo">\r\n            <img soda-src="{{movie_info.vertical_pic || pic}}" class="logo" alt=""/>\r\n        </div>\r\n        <div class="text-info">\r\n            <div class="name" soda-bind-html="name"></div>\r\n            <div class="category-and-date">\r\n\r\n                <div class="tags" >标签：<span soda-bind-html="movie_info.label || \'暂无\'"></span></div>\r\n\r\n            </div>\r\n            <div class="length">语言：<span soda-bind-html="movie_info.langue || \'暂无\'"></span></div>\r\n            <div class="actors">主持人：<span soda-bind-html="movie_info.presenter || \'暂无\'"></span></div>\r\n            <div class="gray"><span soda-bind-html="movie_info.newdate || \'\'"></span></div>\r\n            <div class="buluo-info variety-buluo-info">话题：{{display_pids}}&nbsp;&nbsp;&nbsp;&nbsp;关注：{{display_fans}}</div>\r\n        </div>\r\n\r\n        <div class="btn-area">\r\n            <div class="btn-see" data-mediaexist="{{movie_info.mediaexist}}" id="js_op_movie">看综艺</div>\r\n            <div class="btn-subscribe btn-variety" id="js_movie_focus_btn" soda-if="exsit == 2">\r\n                关注\r\n            </div>\r\n            <div class="btn-variety btn-movie-sign" id="js_movie_sign_btn" soda-if="exsit == 1 && sign == 2">\r\n                签到\r\n            </div>\r\n            <div class="btn-variety btn-movie-sign btn-movie-signed" id="js_bar_sign_btn" soda-if="exsit == 1 && sign == 1">\r\n                <i class="subscribed-icon"></i>\r\n                {{ continue > 1 ? \'签到\' + continue + \'天\' : \'已签\'}}\r\n            </div>\r\n\r\n        </div>\r\n    </div>\r\n</div>\r\n';
    return a.bar_special_variety = "TmplInline_barindex.bar_special_variety",
    Tmpl.addTmpl(a.bar_special_variety, o),
    a
}),
function(a, b) {
    a.DB = b()
}(this, function() {
    return DB.extend({
        deletePost: function(a) {
            a.url = "/cgi-bin/bar/post/delete",
            a.type = "POST",
            a.ssoCmd = "del_post",
            DB.cgiHttp(a)
        },
        bestPost: function(a) {
            a.url = "/cgi-bin/bar/post/best",
            a.type = "POST",
            a.ssoCmd = "post_best",
            DB.cgiHttp(a)
        },
        addMenuFav: function(a) {
            a.url = "/cgi-bin/bar/extra/add_mqq_fave",
            a.ssoCmd = "add_mqq_fave",
            a.type = "POST",
            DB.cgiHttp(a)
        }
    }),
    DB
}),
function(a, b) {
    a.postsRecorders = {},
    a.barindex_all = b()
}(this, function() {
    function a(a) {
        a = Number(a);
        var b = a % 60
          , c = ~~(a / 60);
        return (c >= 10 ? c : "0" + c) + ":" + (b >= 10 ? b : "0" + b)
    }
    function b(a, b) {
        var c = localStorage.getItem("clickedMap");
        c = c ? JSON.parse(c) : {},
        c[a] = 1;
        try {
            localStorage.setItem("clickedMap", JSON.stringify(c))
        } catch (d) {
            localStorage.clear()
        }
        b.addClass("clicked")
    }
    function c() {
        var a = $(".delete-confirm");
        return a ? (a.empty(),
        a.removeClass("delete-confirm")) : void 0
    }
    function d() {
        var a = $(".add-best-confirm");
        return a ? a.removeClass("add-best-confirm") : void 0
    }
    function e(a, b) {
        var c, d = {
            opername: "Grp_tribe",
            module: "tribe_hp",
            ver1: l || Util.queryString("bid") || Util.getHash("bid")
        };
        for (c in i[a])
            i[a].hasOwnProperty(c) && (d[c] = i[a][c]);
        for (c in b)
            b.hasOwnProperty(c) && (d[c] = b[c]);
        Q.tdw(d)
    }
    function f(a) {
        var b = 1;
        return b = 5 > a ? 1 : a > 4 && 9 > a ? 2 : 3
    }
    var g, h, i, j, k = 10, l = Util.queryString("bid") || Util.getHash("bid"), m = [], n = {}, o = !1, p = Util.queryString("uinav") || Util.getHash("uinav") || 1, q = [7], r = "", s = !1, t = window.ScrollModel || window.scrollModel, u = window.ad || null , v = !0;
    return g = p,
    h = window.requestAnimationFrame || window.webkitRequestAnimationFrame || function(a) {
        setTimeout(a, 0)
    }
    ,
    window.formatDuration = a,
    i = {
        5: {
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
        18: {
            action: "Clk_essence_post"
        },
        19: {
            action: "Clk_activity_post"
        },
        25: {
            action: "Clk_votepost"
        },
        Clk_topicpost: {
            action: "Clk_topicpost"
        },
        Clk_person: {
            action: "Clk_person"
        },
        Clk_person_best: {
            action: "Clk_person",
            module: "essence"
        }
    },
    j = new t({
        comment: "allTab",
        renderTmpl: window.TmplInline_barindex.bar_list_text,
        renderContainer: "#js_bar_list",
        scrollEl: $.os.ios ? $("#js_bar_wraper") : window,
        cgiName: _domain + "/cgi-bin/bar/post/get_post_by_page",
        renderTool: window.feedRenderTool,
        scrollThreshold: .25,
        param: function() {
            var a = k
              , b = -a;
            return function() {
                return b += a,
                {
                    bid: l,
                    num: a,
                    start: b
                }
            }
        }(),
        myData: {
            type: 1
        },
        processData: function(a, b) {
            function c(a) {
                var b = new Date(a);
                return b.getMonth() + 1 + "月" + b.getDate() + "日"
            }
            var d, h, i, j, p, t, u, v, w, x, y, z, A, B, C, D, E, F = a.result.posts, G = !F || !F.length;
            if (1 === b && (window.startProcessDataTime = Date.now()),
            a.result && a.result.posts) {
                for (d = 0,
                h = a.result.posts.length; h > d; d++)
                    for (j = 0; j < m.length; j++)
                        if (m[j].pid === a.result.posts[d].pid) {
                            a.result.posts.splice(d, 1),
                            h--,
                            d--;
                            break
                        }
            } else
                a.result.posts || (a.result.isend = 1);
            if (p = 1 === b,
            t = this.myData.type,
            r = a.result.stargroup,
            w = [],
            1 === t)
                p && (w = m);
            else if (2 === t) {
                if (p)
                    for (d = 0; d < m.length; d++)
                        i = m[d],
                        i.best && w.push(i)
            } else if (p)
                for (d = 0; d < m.length; d++)
                    i = m[d],
                    i.type === t && w.push(i);
            for (F = a.result.posts || [],
            a.result.posts = F,
            a.result.barId = l,
            F = a.result.posts = w.concat(a.result.posts),
            b > 1 && e("tribe_loadmore"),
            $(".top-refresh-loading").hide(),
            Refresh.hide(),
            u = $("#js_bar_loading"),
            v = null ,
            d = 0; d < F.length; d++)
                v = F[d],
                v.ad || (n[v.pid] = JSON.stringify(F[d]),
                v.gname && v.related_group && (v.user.sub_flag = 1),
                500 === Number(v.type) && (v.zan || (v.zan = v.vote_zan,
                v.cai = v.vote_cai),
                v.zan = Number(v.zan),
                v.cai = Number(v.cai)),
                x = v.post.pic_list,
                x && x.length && "object" == typeof x[0] && "openact" === v.post.from && (v.post.pic_list[0].url = v.post.pic_list[0].url.replace(/\/0$/, "/160")),
                z = 150,
                v.fullbrief = v.brief,
                window.SubStr.size(v.brief) > z && (v.brief = window.SubStr.substr2(v.brief, 0, z) + "..."),
                v.user && v.user.level && v.user.level > 2 && (v.user.grade = f(v.user.level)),
                y = a.result.admin_ext,
                this.myData.isAdmin = 1 === (1 & y),
                this.myData.isBigBarAdmin = 2 === (2 & y),
                this.myData.isSmallBarAdmin = 4 === (4 & y),
                v.post.start && v.post.end && (F[d].post.time = c(v.post.start) + " - " + c(v.post.end)));
            o || "allTab" !== this.comment || (0 === b ? (mqq.QQVersion && $.os.ios && (Config.isOffline ? Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 31) : Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 30)),
            Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 23),
            Q.isd(7832, 47, 10, +new Date - window.pageStartTime, 13, null , "allWithCache", !0)) : 1 === b && (mqq.QQVersion && $.os.ios && (Config.isOffline ? Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 27) : Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 26)),
            Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 22),
            Q.isd(7832, 47, 10, +new Date - window.pageStartTime, 13, null , "allWithCache", !1)),
            o = !0),
            (10055 === Number(l) || 10064 === Number(l)) && (a.result.gameAct = !0),
            a.result.feeds_list = F,
            A = 1 === g && "allTab" === this.comment,
            C = (b - 1) * k,
            D = b * k,
            console.log("hasAd=", A, "分页数据start=", C, "end=", D, "renderCount=", b),
            A && b && $.each(q, function(a, c) {
                c - 1 >= C && D > c - 1 && (s = !0,
                console.log("广告位：renderCount=", b, "floor=", c - C - 1 + (w && w.length || 0)),
                F.splice(c - C - 1 + (w && w.length || 0), 0, {
                    ad: !0,
                    type: 0,
                    pid: "",
                    post: {
                        video_list: []
                    },
                    user: {}
                }))
            }),
            a.result.isend && (E = "",
            B = {
                2: "精华",
                3: "原创",
                4: "招募",
                100: "活动"
            },
            p && G ? (E = 1 === t ? "暂时还没有人发表话题" : "暂无" + (B[t] || "") + "话题",
            u.html('<div class="no-content">' + E + "</div>")) : u.html(""),
            this.freeze())
        },
        beforeRenderHtml: function() {
            window.beforeRenderHtmlTime = Date.now()
        },
        afterRenderHtml: function() {
            window.afterRenderHtmlTime = Date.now()
        },
        events: function() {
            function a() {
                g && (g.removeClass("list-active"),
                g = null ),
                h && (clearTimeout(h),
                h = null )
            }
            function f(c) {
                var d, f, g, h, j, k, m, o, q, s = c.target, t = $(s), w = t.closest(".user-wrap");
                if (!$.os.ios || i) {
                    if (i = !1,
                    s = $(s).closest("li"),
                    d = s.attr("data-pid"),
                    h = s.attr("data-bid"),
                    j = s.attr("data-gc"),
                    k = s.attr("data-isjoined"),
                    j)
                        return $(s).removeClass("list-active"),
                        1 === parseInt(k) ? (mqq.ui.openAIO({
                            uin: j,
                            chat_type: "group"
                        }),
                        void mqq.ui.closeWebViews({
                            mode: 0,
                            exclude: !1
                        })) : void Util.openUrl("http://buluo.qq.com/mobile/buluoqun.html?bid=" + h + "&pid=" + d + "&gcode=" + j, !0);
                    if (t.hasClass("honour"))
                        return void Util.openUrl(t.data("url"), !0);
                    if (t.parent().hasClass("user-avatar") || t.hasClass("post-datetime") && t.parent().hasClass("name-wrap") || t.hasClass("nick"))
                        return void (w.data("uin") && (window.location.href.indexOf("barindex_best.html") > 0 ? e("Clk_person_best", {
                            obj1: d,
                            ver1: h,
                            obj3: Login.getUin()
                        }) : e("Clk_person", {
                            obj1: d,
                            ver1: h,
                            obj3: Login.getUin()
                        }),
                        window.setTimeout(function() {
                            Util.openUrl("http://buluo.qq.com/mobile/personal.html?&#scene=barindex&uin=" + w.data("uin"), !0)
                        }, 300)));
                    if (g = s[0].getAttribute("data-new") ? "1" : "0",
                    e(5, {
                        obj1: d,
                        ver4: g
                    }),
                    d) {
                        if (m = s.attr("data-type"),
                        o = s.attr("mock"))
                            return void Util.openDetail({
                                "#bid": h || l,
                                "#pid": d,
                                "#uploading": 1,
                                "#source": "barindex"
                            }, null , m);
                        if (a(),
                        $(s).addClass("list-active"),
                        localStorage.removeItem("fastContent"),
                        b(d, s),
                        400 === Number(m) && e("Clk_photopost", {
                            obj1: d,
                            ver4: g
                        }),
                        u && u.silentFetch("barindex", "#js_bar_list .gdt-ad"),
                        r)
                            Util.openDetail({
                                "#bid": l,
                                "#pid": d,
                                stargroup: r,
                                "#source": "barindex"
                            }, null , m);
                        else {
                            if (2 === p ? e(18) : 100 === p ? e(19) : 600 === p && e(25),
                            0 === Number(m)) {
                                try {
                                    localStorage.setItem("fastContent", n[d])
                                } catch (x) {}
                                q = $($(s).find(".feed-img img")[0]),
                                q.data("loaded") && (f = 1)
                            } else
                                401 === Number(m) && e("Clk_topicpost");
                            v && (Util.openDetail({
                                useCacheImg: f,
                                "#bid": h || l,
                                "#pid": d,
                                "#source": "barindex"
                            }, null , m),
                            setTimeout(function() {
                                v = !0
                            }, 1e3),
                            v = !1)
                        }
                        setTimeout(function() {
                            $(s).removeClass("list-active")
                        }, 20)
                    }
                }
            }
            var g, h, i, j, k = this, m = $(window), o = $("#js_bar_list"), q = $("#js_bar_wraper"), s = $("#js_bar_loading");
            q.on("tap", ".barcerbtn", function() {
                s.html("载入中，请稍候..."),
                k.rock()
            }),
            $("#js_bar_list")[0].addEventListener("load", function(a) {
                var b = $(a.target)
                  , c = b.attr("src") || "";
                c.indexOf("/200") > -1 && b.data("loaded", "1")
            }, !0),
            m.on("scroll", function() {
                j = Date.now()
            }),
            o.on("touchstart", "li", function() {
                if (a(),
                $.os.ios) {
                    if (Date.now() - j < 300)
                        return void (i = !1);
                    i = !0
                }
                g = $(this),
                h = setTimeout(function() {
                    var a = g.attr("pid");
                    a && g.addClass("list-active")
                }, 200)
            }).on("touchmove touchcancel", "li", a).on("touchend", "li", a).on("press", "li", f),
            $("#js_bar_list,#js_uploading_video").on("tap", function(b) {
                var e, g, h, i = $(b.srcElement);
                return (k.myData.isAdmin || k.myData.isBigBarAdmin || k.myData.isSmallBarAdmin) && i.hasClass("delete_post") ? (h = i.attr("data-pid"),
                e = i.attr("data-bid"),
                a(),
                window.setTimeout(function() {
                    ActionSheet.show({
                        items: ["同时将该用户拉黑", "只删除不拉黑"],
                        onItemClick: function(a) {
                            var b = {
                                bid: e || l,
                                pid: h
                            };
                            0 === a ? b.black = 1 : b.black = 0,
                            g = $("#" + h),
                            g.remove(),
                            DB.cgiHttp({
                                param: {
                                    bid: e,
                                    pid: h,
                                    black: b.black
                                },
                                url: "/cgi-bin/bar/post/delete",
                                ssoCmd: "del_post",
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
                }, 0),
                !1) : k.myData.isBigBarAdmin && i.hasClass("add-best-disabled") ? (a(),
                i.hasClass("add-best-confirm") ? (h = i.attr("data-pid"),
                DB.bestPost({
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
                        d()
                    }
                }),
                !1) : (i.addClass("add-best-confirm"),
                !1)) : i.hasClass("icons") ? (a(),
                d(),
                c(),
                !1) : (c(),
                d(),
                void f(b))
            }),
            Q.huatuo(1486, 1, 1, Date.now() - window.pageStartTime, 2)
        },
        complete: function(a, b) {
            imgHandle.lazy($(this.renderContainer)[0]),
            2 > b && $.os.ios && ($("#js_bar_main")[0].scrollTop = 0),
            b && s && (u && u.show("barindex", "#js_bar_list .gdt-ad"),
            s = !1)
        },
        onreset: function() {
            window.postsRecorders = {},
            $("#js_bar_list").html("");
            var a = $("#js_bar_loading");
            a.html("载入中，请稍候..."),
            a.css("opacity", "1")
        },
        error: function(a) {
        	console.log(a);
            var b = $("#js_bar_loading");
            return 101001 === Number(a.retcode) ? void b.text("对不起，您没有权限访问该部落") : void (1e5 !== Number(a.retcode) && (b.html("拉取失败[" + a.retcode + "]<div id='barcErBtn' class='error-rbtn barcerbtn'>点击重试</div>"),
            Tip.show("获取帖子列表失败[" + a.retcode + "]", {
                type: "warning"
            })))
        }
    }),
    j.setStarGroupFlag = function(a) {
        r = a
    }
    ,
    j.setTopListSpecialPool = function(a) {
        m = m.concat(a)
    }
    ,
    j
}),
function(a, b) {
    a.postsRecorder = {},
    a.BarIndex = b()
}(this, function() {
    function a(a) {
        x && y ? a(x, y) : mqq.sensor.getLocation(function(b, c, d) {
            0 === b && (x = parseInt(1e6 * (c || 0), 10),
            y = parseInt(1e6 * (d || 0), 10)),
            a(x, y)
        })
    }
    function b(a) {
        if ("number" != typeof a)
            return a;
        a = +a;
        var b, c = "", d = 1e4, e = 1e8;
        if (d > a)
            c = "" + a;
        else if (a >= d && e > a) {
            if (b = ("" + (a / d).toFixed(1)).split("."),
            0 === parseInt(b[1], 10))
                return b[0] + "万";
            c = (b[1] > 5 ? parseInt(b[0], 10) + 1 : b[0]) + "万"
        } else if (a >= e) {
            if (b = ("" + (a / e).toFixed(2)).split("."),
            0 === parseInt(b[1], 10))
                return b[0] + "亿";
            b[1][1] + "" == "0" && (b[1] = b[1][0]),
            c = b.join(".") + "亿"
        }
        return c
    }
    function c(a) {
        a += "",
        a = a.substr(0, 4);
        var b = a.match(/\d/g)
          , c = /\./.test(a);
        switch (b.length) {
        case 1:
            a += ".0%";
            break;
        case 2:
            a += c ? "%" : ".0%";
            break;
        default:
            a += "%"
        }
        return a
    }
    function d(a, b) {
        for (var c = $(a).find("li"), d = c.length, e = 360 / d, f = 0; d > f; f++) {
            var g = (b * Math.sin(e * f * Math.PI / 180 - Math.PI / 2)).toFixed(6)
              , h = (b * Math.cos(e * f * Math.PI / 180 - Math.PI / 2)).toFixed(6)
              , i = c.eq(f)
              , j = "translate3d(" + h + "px," + g + "px, 0)";
            i[0].style.WebkitTransform = j,
            i.css({
                transform: j
            })
        }
    }
    function e(a, b, c, e, f, g) {
        setTimeout(function() {
            var h, i, j = "", k = "";
            7 > c && (j += '<p class="tips-main">已经签到 <span style="color: #ffba00;">' + c + "</span> 天</p>",
            j += '<small class="tips-small">连续签到7天可获得铁杆粉勋章</small>',
            $(".sign-continue-tips").addClass("lt-7days")),
            7 === c && (j += '<p class="tips-main">恭喜获得铁杆粉勋章</p>',
            j += '<small class="tips-small">已连续签到<span style="color: #ffba00;">7</span> 天</small>',
            $(".sign-continue-tips").addClass("eq-7days")),
            c > 7 && (j += '<p class="tips-main">已连续签到 <span style="color: #ffba00;">' + c + "</span> 天</p>",
            j += '<small class="tips-small">已获得铁杆粉勋章</small>',
            $(".sign-continue-tips").addClass("gt-7days")),
            g > 0 && (j += '<p class="sign-add-credit-tip">经验值<span style="margin-left:5px;">+<span style="margin-left:6px;">' + g + "</span></span></p>"),
            $("#main-tips").html(j),
            ya && a && b && e > 0 && f > 0 && (k += '<span class="resign-btn">漏签' + e + "天，立即补签</span> ",
            $("#resign-tips").html(k),
            nb("exp_repair")),
            h = $("#sign-continue-day-list li"),
            i = 1e3;
            for (var l = function(a) {
                U ? $(h[a]).addClass("active2") : setTimeout(function() {
                    $(h[a]).addClass("active")
                }, i)
            }
            , m = c > 7 ? 7 : c, n = 0; m - 1 > n; n++)
                $(h[n]).addClass(U ? "active2" : "active");
            l(n),
            $(window.innerHeight) > 568 ? d("#sign-continue-day-list", 120) : d("#sign-continue-day-list", 100),
            U ? $("#sign-continue-tips .sign-continue-tips").css("-webkit-animation-name", "") : ($("#sign-continue-tips .sign-continue-tips").css("-webkit-animation-name", "pulse"),
            U = !0),
            $("#sign-continue-tips").show()
        }, 500)
    }
    function f(a, b) {
        b = b || "部落首页";
        var c = "http://buluo.qq.com/mobile/sign_calendar.html?_wv=1027&from=" + b + "&bid=" + a;
        Util.openUrl(c, !0)
    }
    function g(a) {
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
    function h() {
        var a = aa
          , b = a ? "立即续费" : "立即开通"
          , c = a ? "xufei" : "kaitong";
        Alert.show("", "星影会员在明星部落<br>获得双倍经验", {
            confirm: b,
            cancel: "取消",
            confirmAtRight: !0,
            callback: function() {
                var a = $.os.ios ? "ios" : $.os.android ? "android" : "mobile";
                XYPAY.openVip({
                    month: 3,
                    aid: "xylm.double." + a + "." + ua + "." + c,
                    device: a,
                    from: "部落首页",
                    privilege: "双倍经验"
                }, function(a) {
                    console.log("星影会员支付回调：", a)
                })
            }
        })
    }
    function i() {
        U ? e(G, bb, cb, db, eb, fb) : (V.refresh(),
        nb(9),
        G && nb("star_sign_clk"))
    }
    function j(a) {
        ya && ($.getScript = function(a, b) {
            var c = document.createElement("script");
            c.async = "async",
            c.src = a,
            b && (c.onload = b),
            document.getElementsByTagName("head")[0].appendChild(c)
        }
        ,
        $.getScript("http://imgcache.qq.com/club/gamecenter/widgets/gameBtn.js?_bid=" + ua, function() {
            var b, c = $("#game-btn");
            window.gameBtn.init({
                appId: a,
                btn: document.getElementById("game-btn"),
                src: "buluo",
                downloadTxt: "",
                launchTxt: "",
                callback: function(a) {
                    if (0 === a.result) {
                        switch (a.data.gameStatus) {
                        case 0:
                            b = "game_down",
                            c.html('<i class="game-icon game-icon-down"></i>下载');
                            break;
                        case 1:
                            b = "game_order",
                            c.html('<i class="game-icon game-icon-order"></i>预约');
                            break;
                        case 2:
                            b = "game_start",
                            c.html('<i class="game-icon game-icon-start"></i>启动');
                            break;
                        case 5:
                        }
                        b && (c.css({
                            display: "inline-block"
                        }),
                        nb("game_exp", {
                            ver1: ua
                        }),
                        c.on("tap", function(a) {
                            a.stopPropagation(),
                            nb(b, {
                                ver1: ua
                            }, !0)
                        }))
                    }
                }
            })
        }))
    }
    function k() {
        if (!window.needFollowTip) {
            $("#follow-tips-barname").html(Ha);
            var a, b = $("#follow-tips-num"), c = $("#follow-sign"), d = parseInt(Ia) + 1, e = d.toString().length, f = 0, g = 1;
            setTimeout(function() {
                $("#follow-mask").on("touchmove", function(a) {
                    a.preventDefault(),
                    a.stopPropagation()
                }).on("tap", function(a) {
                    var b = a.target;
                    "follow-mask" === b.id ? $("#follow-mask").addClass("out").on("webkitTransitionEnd", function() {
                        $("#follow-mask").remove()
                    }) : "follow-sign" === b.id && $("#follow-mask").addClass("out").on("webkitTransitionEnd", function() {
                        $("#follow-mask").remove(),
                        i()
                    })
                }),
                G && c.text("立即签到，增加偶像魅力值"),
                U && c.hide(),
                $("#follow-mask").show(),
                setTimeout(function() {
                    if ($.os.ios && e >= 3)
                        var c = setInterval(function() {
                            a = 20 > g ? parseInt(Math.pow(10, Math.floor(e / 3)) / 20) : 40 > g ? parseInt((Math.pow(10, Math.floor(e / 3 * 2)) - f) / 20) : parseInt((Math.pow(10, Math.floor(e)) - f) / 20),
                            f + a >= d ? (f = d,
                            clearInterval(c)) : f += a,
                            b.html(f),
                            g++
                        }, 16);
                    else
                        b.html(d)
                }, 600),
                setTimeout(function() {
                    $("#follow-tips-icon").css("opacity", 1)
                }, 900)
            }, 300)
        }
    }
    function l() {
        var a = "心不足，开通星影会员每日可领5心";
        Alert.show("温馨提示", a, {
            callback: function() {
                var a = $.os.ios ? "ios" : $.os.android ? "android" : "mobile";
                nb("Clk_vip", {
                    ver3: 2,
                    ver4: a
                }),
                XYPAY.openVip({
                    month: 3,
                    aid: "xylm.dabang." + a + ".fuceng." + ua,
                    device: a,
                    from: "浮层",
                    privilege: "打榜"
                }, function(a) {
                    0 === a.ret && n()
                })
            },
            confirm: "立即开通",
            cancel: "取消",
            confirmAtRight: !0
        })
    }
    function m() {
        var a = "心不足，星影会员今日还可领5心";
        Alert.show("温馨提示", a, {
            callback: function() {
                nb("Clk_take", {
                    ver3: 2
                }),
                Z.rock()
            },
            confirm: "立即领取",
            cancel: "取消",
            confirmAtRight: !0
        }),
        nb("exp_take", {
            ver3: 2
        })
    }
    function n() {
        var a = new sb({
            comment: "starHeart",
            cgiName: "/cgi-bin/bar/get_user_love_info",
            param: {
                plat: $.os.ios ? 3 : 2
            },
            processData: function(a) {
                a.retcode || (aa = a.result.vip_flag,
                ba = a.result.today_get_loved)
            },
            complete: function() {}
        });
        a.rock()
    }
    function o() {
        if (window.Publish) {
            var a, b = {};
            $.extend(b, {
                bid: ua,
                flag: B,
                barName: Ha,
                pid: 0,
                ctxNode: $("#js_bar_wraper"),
                pubulishType: "pub",
                fromType: "barindex",
                succ: function(a) {
                    p(a.level),
                    Util.openUrl(_domain + "detail&bid=" + (a.bid || ua) + "&pid=" + a.pid + "&source=barindex&justPublished=yes", !0, 0)
                },
                onhidden: function() {
                    nb("Post_cancel")
                },
                ondestroy: function() {},
                config: {
                    from: "barindex",
                    needCategory: 0
                }
            }),
            ab && 8 & Ja && (b.config.minLength = 10,
            b.config.minTitleLength = 2),
            1 === E && (b.config.minLength = 10),
            mqq && mqq.compare("5.0") < 0 && (a = Util.getHash("target"),
            "send" !== a && (window.location.hash += "&target=send")),
            localStorage.getItem("pho_alert" + ua) || 10364 !== Number(ua) && 10679 !== Number(ua) ? Publish.init(b) : Util.showStatement(function() {
                Publish.init(b)
            }, ua)
        }
    }
    function p(a) {
        if (a && a.level) {
            if ($("#info_level").html('<span class="jump_level l-level lv' + a.level + '" >LV.' + a.level + " " + a.level_title + "</span>"),
            $("#level_bar")[0]) {
                var b = 3;
                0 !== a.point && (b = a.point / a.next * 115),
                $("#level_bar").find(".l-inner-bar").css({
                    width: b
                }),
                $("#level_bar").attr("data-point", a.point)
            }
            $a && _.syncLevel(a)
        }
    }
    function q() {
        if (!Ma) {
            if (Za)
                return window.YybJsBridge.setShareInfo({
                    allowShare: 1,
                    iconUrl: Na,
                    jumpUrl: location.href,
                    title: "兴趣部落-" + Pa,
                    summary: Oa
                }),
                void (Ma = !0);
            var a, b, c, d = 0, e = 0;
            d = Util.queryString("bid") || Util.getHash("bid"),
            e = Util.queryString("pid") || Util.getHash("pid"),
            a = function() {
                nb("Clk_collect", {
                    obj1: e,
                    os: $.os.ios ? "ios" : "android"
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
            }
            ,
            F = ["share_qq", "share_qzone", "share_wechat", "share_circle", "share_weibo", "share_link", "share_collection", "share_shortcut"],
            ActionButton.build({
                iconID: "4",
                type: "share"
            }, function() {
                nb("Clk_share"),
                1 === parseInt(S) && nb(30);
                var b = location.href
                  , c = [];
                S && c.push({
                    text: "订阅到群",
                    img: "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/share/subscribe_qun.png",
                    onTap: function() {
                        nb(31),
                        Util.openUrl("http://buluo.qq.com/mobile/sub/subscribe_qun.html?bid=" + ua + "&bname=" + encodeURIComponent($.str.decodeHtml(Pa)) + "&bpic=" + Na + "&_wv=1027", !0)
                    }
                }),
                c.push({
                    text: "投诉举报",
                    img: "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/share/report.png",
                    onTap: function() {
                        Util.openUrl("http://buluo.qq.com/mobile/report.html#bid=" + ua + "&from=buluo", !0)
                    }
                }),
                c.push({
                    text: "意见反馈",
                    img: "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/share/tucao.png",
                    onTap: function() {
                        var a = "https://support.qq.com/embed/app/1301?";
                        DB.cgiHttp({
                            url: "/cgi-bin/bar/access_support",
                            succ: function(b) {
                                w.data = b.result.desencryptstr,
                                w.customInfo = ua,
                                Util.openUrl(a + $.param(w), !0)
                            }
                        }),
                        nb("Clk_feedback")
                    }
                }),
                RichShare.build(c),
                Util.shareMessage({
                    shareUrl: b,
                    pageUrl: location.href,
                    imageUrl: Na,
                    title: "兴趣部落-" + Pa,
                    barName: Pa,
                    barId: ua,
                    content: Oa,
                    showCancelFocusBtn: Ua,
                    onCancelFocusSuccess: function() {
                        mqq.dispatchEvent("event_focus_tribe", {
                            bid: ua,
                            focus: !1,
                            from: "barindex"
                        }, {
                            domains: ["*.qq.com"]
                        })
                    },
                    imageInfo: {
                        bid: ua
                    },
                    succHandler: function(a) {
                        nb(["qq_suc", "qzone_suc", "wechat_suc", "circle_suc"][a])
                    }
                }, function(b, c) {
                    6 === c ? a() : 7 === c ? nb("shortcut") : 6 > c ? nb(F[c]) : 8 === c && nb("share_qrcode")
                })
            }),
            Ma = !0
        }
    }
    function r(a) {
        a = Number(a);
        var b = a % 60
          , c = ~~(a / 60);
        return (c >= 10 ? c : "0" + c) + ":" + (b >= 10 ? b : "0" + b)
    }
    function s() {
        var a = Util.getHash("shortcut");
        "true" === a && mqq && mqq.compare("5.5.1") > -1 && mqq.support("mqq.ui.closeWebViews") && (mqq.support("mqq.ui.setTitleButtons") && mqq.ui.setTitleButtons({
            left: {
                title: "返回"
            }
        }),
        mqq.ui.closeWebViews({
            mode: 2,
            exclude: !0
        }),
        console.log("桌面快捷方式打开部落，清理其余WebView"))
    }
    function t(a, b, c) {
        try {
            var d = {}
              , e = $.cookie("uin");
            e && (e = e.replace("o", "")),
            d.time = window.globalPreLoader.getCgiDuration(a),
            d.uin = e,
            d.url = b,
            c && 0 === c.retcode ? (d.type = 1,
            d.rate = 20) : (d.type = 3,
            d.rate = 1),
            window.reportCgi.report(d)
        } catch (f) {}
    }
    function u() {
        w = {
            clientInfo: "手Q兴趣部落",
            clientVersion: mqq.QQVersion,
            os: "",
            osVersion: "",
            imei: "",
            netType: ""
        },
        mqq.device.getDeviceInfo(function(a) {
            w.os = a.systemName,
            w.osVersion = a.systemVersion,
            w.imei = a.identifier
        }),
        mqq.device.getNetworkType(function(a) {
            w.netType = 1 === a ? 1 : 2
        })
    }
    function v() {
        function a() {
            try {
                var a, b = {
                    total: {
                        ios: 650284,
                        android: 650286
                    },
                    cbSuc: {
                        ios: 650288,
                        android: 650290
                    }
                };
                Util.h5Data.h5DataSupport && ($.os.android ? Q.monitor(b.total.android) : Q.monitor(b.total.ios),
                a = {
                    key: "readH5DataApiTest",
                    path: "buluo",
                    host: "",
                    callid: +new Date % 1e4
                },
                mqq.data.writeH5Data(a, function() {
                    $.os.android ? Q.monitor(b.cbSuc.android) : Q.monitor(b.cbSuc.ios)
                }))
            } catch (c) {}
        }
        ("share_app" === Util.queryString("source") || "share_app" === Util.getHash("source")) && (console.log("显示下载引导模块"),
        window.InvokeApp.buildGuide("gbar_home")),
        xa && $("#js_bar_basic").addClass("weixin-barindex"),
        u();
        var b = Util.getHash("type");
        ua = ua || tools.querystring("i");
        return ua ? (va && Util.openDetail({
            "#bid": ua,
            "#pid": va
        }, null , b),
        ua = ua.replace(/[^\d]*/g, ""),
        wa && (ea.hide(),
        $("#js_bar_main").css({
            bottom: 0
        })),
        s(),
        window.CGI_Preload && window.CGI_Preload.isProviderCacheReady ? (ca.rock(),
        za.rock()) : (ca.usePreLoad = !0,
        window.globalPreLoader.hasData("barTopData") ? Q.monitor(477705) : (ca.rock(),
        Q.monitor(477703)),
        window.globalPreLoader.getData("barTopData", function(a) {
            ca.setPreLoadData(a),
            ca.rock(),
            t("barTopData", "http://buluo.qq.com/cgi-bin/bar/page", a)
        }),
        za.usePreLoad = !0,
        window.globalPreLoader.hasData("postData") ? Q.monitor(477706) : (za.rock(),
        Q.monitor(477704)),
        window.globalPreLoader.getData("postData", function(a) {
            za.setPreLoadData(a),
            za.rock(),
            t("postData", "http://buluo.qq.com/cgi-bin/bar/post/get_post_by_page", a)
        })),
        Ya && Ya.timing && Q.huatuo(1486, 1, 1, Ya.timing.responseStart - Ya.timing.navigationStart, 1),
        xb && xb.init("barindex"),
        nb(1),
        nb(0, {
            ver5: navigator.userAgent
        }),
        Q.monitor(428051),
        N = Util.getHash("topic"),
        "1" === N && (O = $("#js_bar_top").offset().top,
        window.scrollTo(0, O),
        $("#js_bar_main").scrollTop(O)),
        window.mqq && mqq.addEventListener && mqq.addEventListener("addreadnum", function(a) {
            var b, c = a.pid, d = a.bid, e = $("#" + c);
            if (e.length && Number(ua) === Number(d) && (b = $(e.find(".read-num-text")),
            b.length)) {
                var f = Number(b.text());
                isNaN(f) || b.text(f + 1)
            }
        }),
        setTimeout(function() {
            a()
        }, 0),
        "0" !== mqq.QQVersion && (Config.isOffline ? Q.monitor(663305) : Q.monitor(663304)),
        Config.isOffline ? (Q.huatuo(1486, 1, 1, window.resourcesLoadedTime - window.domFinishedTime, 11),
        Q.huatuo(1486, 1, 1, window.domStartLoadTime - window.cssStartLoadTime, 13)) : (Q.huatuo(1486, 1, 1, window.resourcesLoadedTime - window.domFinishedTime, 10),
        Q.huatuo(1486, 1, 1, window.domStartLoadTime - window.cssStartLoadTime, 12)),
        void Q.huatuo(1486, 1, 1, window.domFinishedTime - window.domStartLoadTime, 14)) : (window.location.href.indexOf("s.p.qq.com") > -1 && (window.badjsReport("id-error"),
        Q.monitor(676545)),
        Tip.show("部落ID错误", {
            type: "warning"
        }))
    }
    var w = {};
    Model.defaultEffect = "fadeIn";
    var x, y, z, A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, R, S, T, U, V, W, X, Y, Z, _, aa, ba, ca, da, ea, fa, ga, ha, ia, ja, ka, la, ma, na, oa, pa, qa, ra, sa, ta, ua = Util.queryString("bid") || Util.getHash("bid"), va = Util.queryString("pid") || Util.getHash("pid"), wa = Util.getHash("uibarhide") || 0, xa = /\bMicroMessenger\/[\d\.]+/.test(navigator.userAgent), ya = 0 !== Number(mqq.QQVersion), za = window.barindex_all, Aa = "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/temp-bar-icon/", Ba = {
        "http://pub.idqqimg.com/pc/misc/files/20150218/9d647e60fb2c47aba4d095801f43f3d3.png": Aa + "bar-video-icon.png",
        "http://pub.idqqimg.com/pc/misc/files/20141204/5932910a2e95408e83d4bc0f9ce247d5.png": Aa + "bar-image-icon.png",
        "http://pub.idqqimg.com/pc/misc/files/20141204/843063f9eaf44cec92349d29f857dfe5.png": Aa + "bar-shoping-icon.png",
        "http://pub.idqqimg.com/pc/misc/files/20150120/6cd83902db9840aba8ab1c43c24c5d70.png": Aa + "bar-view-icon.png",
        "http://pub.idqqimg.com/pc/misc/files/20150107/3eaa7e15b48e4d0fa8a66d096a051789.png": Aa + "bar-view-icon.png"
    }, Ca = !1, Da = !1, Ea = !1, Fa = {}, Ga = _domain +"", Ha = "", Ia = 0, Ja = 0, Ka = document.documentElement.clientWidth || document.body.clientWidth, La = document.documentElement.clientHeight || document.body.clientHeight, Ma = !1, Na = "http://q3.qlogo.cn/g?b=qq&k=XnGFNfgzCr7LnaWFrAw0UQ&s=100", Oa = "", Pa = "", Qa = "", Ra = "", Sa = !1, Ta = 0, Ua = !1, Va = navigator.userAgent.match(/\/qqdownloader\/(\d+)?/), Wa = 0, Xa = $(".all-tab-refresh-btn"), Ya = window.performance, Za = !1, $a = !1, _a = function(a) {
        var b = Util.getHash("scene");
        return "" !== b && a[b] ? a[b] : "other"
    }({
        detail_topbar: 1,
        detail_titleNav: 2,
        detail_recommend: 3,
        detail_share: 4,
        rencent: 5,
        recommend: 6,
        rank_recommend: 7,
        rank_category: 8,
        discovery: 9,
        personal: 10,
        search: 11,
        tribeinfo: 12,
        detail_delete: 13,
        index: 14
    }), ab = 0, bb = !1, cb = 0, db = 0, eb = 0, fb = 0, gb = 0, hb = "", ib = 0, jb = 0, kb = Util.queryString("uinav") || Util.getHash("uinav") || 1, lb = ["#tab_all", "#tab_hof", "#tab_qun", "#tab_best", ".myTab_2", ".myTab_1", ".tab_video"], mb = {
        0: {
            action: "visit"
        },
        1: {
            action: "visit_hp",
            ver3: Config.isOffline ? 11 : 10
        },
        2: {
            action: "Clk_banner"
        },
        3: {
            action: "Clk_focus"
        },
        6: {
            action: "Clk_pub"
        },
        7: {
            action: "Clk_refresh"
        },
        "#tab_tribe": {
            action: "exp",
            module: "order"
        },
        9: {
            action: "Clk_sign"
        },
        10: {
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
        16: {
            action: "Clk_playgame"
        },
        "#tab_qun": {
            action: "Clk_grp"
        },
        20: {
            action: "Clk_head",
            module: "order"
        },
        21: {
            action: "Clk_tribe",
            module: "order"
        },
        22: {
            action: "Clk_ask",
            module: "order"
        },
        23: {
            action: "Clk_more",
            module: "order"
        },
        24: {
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
            ver3: $.os.ios ? "ios" : $.os.android ? "android" : ""
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
        exp_signed: {
            module: "tribe_hp",
            action: "exp_signed"
        },
        Clk_signed: {
            module: "tribe_hp",
            action: "Clk_signed"
        },
        exp_repair: {
            module: "sign_float",
            action: "exp_repair"
        },
        Clk_repair: {
            module: "sign_float",
            action: "Clk_repair"
        },
        exp_layer: {
            module: "star_hit",
            action: "exp_layer"
        },
        Clk_vip: {
            module: "star_hit",
            action: "Clk_vip"
        },
        30: {
            opername: "Grp_subscription",
            module: "sub_grp",
            action: "exp_button"
        },
        31: {
            opername: "Grp_subscription",
            module: "sub_grp",
            action: "Clk_button"
        },
        32: {
            module: "new_guide",
            action: "exp_home"
        },
        Clk_rankfocus: {
            opername: "tribe_cgi",
            module: "score_sys",
            action: "Clk_rankfocus"
        },
        Post_cancel: {
            action: "Post_cancel"
        },
        game_exp: {
            action: "game_exp"
        },
        game_start: {
            action: "game_start"
        },
        game_down: {
            action: "game_down"
        },
        game_order: {
            action: "game_order"
        },
        Clk_feedback: {
            action: "Clk_feedback"
        }
    }, nb = function(a, b, c) {
        var d, e = {
            opername: "Grp_tribe",
            module: "tribe_hp",
            ver1: ua || Util.queryString("bid") || Util.getHash("bid")
        };
        for (d in mb[a])
            mb[a].hasOwnProperty(d) && (e[d] = mb[a][d]);
        for (d in b)
            b.hasOwnProperty(d) && (e[d] = b[d]);
        Q.tdw(e, c)
    }
    , ob = function(a, b, c) {
        var d, e = {
            opername: "Grp_tribe",
            module: "tribe_hp",
            action: a,
            ver1: ua || Util.queryString("bid") || Util.getHash("bid")
        };
        for (d in b)
            b.hasOwnProperty(d) && (e[d] = b[d]);
        Q.tdw(e, c)
    }
    , pb = !1, qb = !1, rb = $(".empty-container"), sb = window.cgiModel, tb = window.renderModel, ub = window.linkModel, vb = window.mutitabModel, wb = window.pageModel, xb = window.ad || null , yb = function() {
        var a = !1
          , b = 2;
        return function() {
            b--,
            window.CGI_Preload && 0 >= b && !a && (a = !0,
            window.CGI_Preload.reportOpenWebView(),
            window.CGI_Preload.reportUsable(),
            window.CGI_Preload.reportUsableWithOpen())
        }
    }();
    Va && (Wa = 1),
    Va && (Za = !0),
    window.isLowerIos = $.os.ios && $.os.version < "6.9",
    window.isLowerIos && $("html,body").removeClass("ios"),
    function() {
        var a = {
            card: "395362",
            search: "395364",
            pa: "395363",
            other: "395370"
        }
          , b = Util.queryString("from") || Util.getHash("from") || "other"
          , c = a[b] || a.other;
        $.os.android && localStorage.setItem("card-open", 1),
        Q.monitor(c)
    }(),
    V = new sb({
        comment: "signCgi",
        cgiName: "/cgi-bin/bar/user/sign",
        param: {
            bid: +ua
        },
        processData: function(a, b) {
            if (0 !== b) {
                var c, d, f, g, h, i = mqq && mqq.compare("5.3.2") > -1, j = $("#js_bar_charm_count"), k = $("#charm_count_wrap"), l = $("#sign-star-charm-added");
                if (0 === a.retcode) {
                    if (a.result && (cb = a.result["continue"],
                    db = a.result.leak_day,
                    eb = a.result.can_repari_day,
                    fb = a.result.add_credits,
                    gb = a.result.new_level,
                    hb = a.result.new_title,
                    ib = a.result.level && a.result.level.point,
                    jb = a.result.level && a.result.level.next),
                    Ca || Da || Ea) {
                        if (d = $("#js_movie_sign_btn"),
                        a.result && a.result["continue"] >= 2) {
                            var m = a.result["continue"] > 999 ? 999 : a.result["continue"];
                            d.html('<i class="subscribed-icon"></i>签到' + m + "天")
                        } else
                            d.html('<i class="subscribed-icon"></i>已签');
                        d.addClass("btn-movie-signed")
                    } else {
                        if (c = $("#signBtn"),
                        c.addClass("signed"),
                        a.result && a.result["continue"] >= 2 ? (cb = cb > 999 ? 999 : cb,
                        c.html("签到" + cb + "天")) : c.html('<i class="signed-icon"></i>已签'),
                        H && ya && (cb = cb > 999 ? 999 : cb,
                        c.html('<i class="signed-icon"><span class="signed-days-count">' + cb + "</span></i>已签")),
                        G && ya)
                            if (bb) {
                                cb = cb > 999 ? 999 : cb,
                                c.html('<i class="signed-icon"><span class="signed-days-count">' + cb + "</span></i>已签");
                                var n = bb && G && ya ? "is-star-category" : "disable";
                                c.addClass(n),
                                nb("exp_signed")
                            } else
                                c.addClass("send-heart-btn").attr("id", "sendHeartBtn").removeClass("signed sign_btn").html('送心<i class="icon-heart-animation"></i>'),
                                nb("exp_send", {
                                    ver3: 2
                                });
                        f = Number($("#level_bar").data("point")) || 0,
                        g = ab ? Number(a.result.level.point) - f : fb || 0,
                        h = g >= 0 ? "+" : ""
                    }
                    p(a.result.level),
                    e(G, bb, cb, db, eb, fb),
                    Ca || Da || Ea || G && (l.text("魅力值+1").show(),
                    setTimeout(function() {
                        k.addClass("charm-count-animation")
                    }, 2e3)),
                    G && (j.text(parseInt(j.attr("num"), 10) + 1),
                    _.syncCharm(1)),
                    mqq.dispatchEvent("event_sign_tribe", {
                        bid: ua,
                        sign: !0
                    }, {
                        domains: ["*.qq.com"]
                    }),
                    nb("sso_sign", {
                        ver3: i ? 1 : 2
                    })
                } else
                    Tip.show("签到失败，请稍后重试", {
                        type: "warning"
                    }),
                    nb("sso_sign", {
                        ver3: i ? 3 : 4
                    });
                mqq.compare("5.0") >= 0 && setTimeout(function() {
                    mqq.dispatchEvent("updateFocusList", {}, {
                        domains: ["*.qq.com"]
                    })
                }, 200)
            }
        },
        error: function() {
            Tip.show("签到失败，请稍后重试", {
                type: "warning"
            });
            var a = mqq && mqq.compare("5.3.2") > -1;
            nb("sso_sign", {
                ver3: a ? 5 : 6
            })
        }
    }),
    W = new sb({
        comment: "voteCgi",
        cgiName: "/cgi-bin/bar/user/fbar",
        ssoCmd: "follow_bar",
        param: {
            bid: ua,
            op: 1
        },
        processData: function(a) {
            var c, d, e, f = $("#opArea"), g = $("#js_bar_vote_count"), h = $("#js_bar_bottom_wrap"), i = $("#js_bar_vote_btn");
            if (f.hide(),
            $(".bar-info-text").hide(),
            c = parseInt(g.attr("num"), 10) + 1,
            g.attr("num", c),
            g.html(b(c)),
            h.removeClass("focus"),
            !h.hasClass("reply") && h.addClass("reply"),
            d = _domain + "/cgi-bin/bar/page/" + encodeURIComponent(JSON.stringify({
                bid: ua,
                platform: Wa
            })),
            e = $.storage.get(d),
            ab || (a = e && "string" == typeof e ? JSON.parse(e) : $.storage.get(d) || a),
            a && a.result && (a.result.exsit = 1,
            $a ? (a.result.is_star_game = 1,
            _.update(a)) : ga.update(a),
            H && j(I),
            (Ca || Da || Ea) && (i = $("#js_movie_focus_btn"),
            i.attr("id", "js_movie_sign_btn").addClass("btn-movie-sign").removeClass("btn-subscribe"),
            1 === Number(a.result.sign) ? i.addClass("btn-movie-signed").html('<i class="subscribed-icon"></i>' + (Number(a.result["continue"]) >= 2 ? "签到" + a.result["continue"] + "天" : "已签")) : i.html("签到"))),
            Ua = !0,
            ya && ~~a.result.green_hand && "1" !== localStorage.getItem("fbar_green_hand")) {
                localStorage.setItem("fbar_green_hand", "1");
                var l = "关注后可在“兴趣圈”查看部落的精华内容，并会收到兴趣号的推送。";
                nb(32, {
                    ver3: ua
                }),
                Alert.show("关注成功", l, {
                    confirm: "我知道了"
                })
            } else
                ($.os.ios || $.os.android) && mqq.compare("4.6") >= 0 && mqq.data.readH5Data({
                    callid: 1,
                    path: "/buluo",
                    key: "followTipsMap"
                }, function(a) {
                    var b = {};
                    0 === a.ret ? (b = JSON.parse(a.response.data),
                    0 !== b[ua] ? (b[ua] = 0,
                    k()) : Tip.show("关注成功", {
                        type: "ok"
                    })) : (b[ua] = 0,
                    k()),
                    mqq.data.writeH5Data({
                        callid: 1,
                        path: "/buluo",
                        key: "followTipsMap",
                        data: JSON.stringify(b)
                    }, function() {})
                }),
                mqq.dispatchEvent("event_focus_tribe", {
                    bid: ua,
                    focus: !0
                }, {
                    domains: ["*.qq.com"]
                })
        }
    }),
    X = W.extend({
        comment: "unvoteCgi",
        param: {
            bid: ua,
            op: 2
        },
        processData: function(a) {
            $(".focus-btn").html("关注").removeClass("unvote"),
            Ua = !1,
            a && a.result && (a.result.exsit = 0,
            ga.update(a),
            $("#opArea").show(),
            $(".bar-info-text").show())
        }
    }),
    Z = new sb({
        comment: "getHeartModel",
        cgiName: "/cgi-bin/bar/get_free_love",
        param: {
            plat: $.os.ios ? 3 : 2
        },
        processData: function(a) {
            !a.retcode && a.result && (Tip.show("领心成功，在明星魅力榜查看总数。", {
                type: "ok"
            }),
            ba = !0,
            mqq.dispatchEvent("event_get_heart", {
                count: a.result.love_total
            }, {
                domains: ["*.qq.com"]
            }))
        },
        error: function() {
            Tip.show("领心失败，请稍后重试", {
                type: "warning"
            })
        }
    }),
    Y = new sb({
        comment: "sendHeartModel",
        cgiName: "/cgi-bin/bar/love_support",
        param: function() {
            return {
                plat: $.os.ios ? 3 : 2,
                bid: ua
            }
        },
        processData: function(a) {
            var b = $("#js_bar_charm_count")
              , c = b.closest(".header")
              , d = c.find(".charm-add-animation");
            if (!a.retcode)
                if (!a.result.err && b.length) {
                    $a ? _.syncCharm(5) : (c.addClass("sending-heart"),
                    d.text("+5"),
                    setTimeout(function() {
                        b.text(Number(b.text()) + 5)
                    }, 1200),
                    setTimeout(function() {
                        c.removeClass("sending-heart")
                    }, 2500),
                    mqq.dispatchEvent("event_send_heart", {
                        send: !0,
                        bid: ua
                    }, {
                        domains: ["*.qq.com"]
                    })),
                    a.result.new_level > 0 && window.UpgradeTip && window.UpgradeTip.show({
                        level: a.result.new_level,
                        level_title: a.result.new_title
                    });
                    var e = a.result.add_credits;
                    e > 0 && Tip.show("送心成功" + (e > 0 ? "，经验值+" + e : ""), {
                        type: "ok"
                    }),
                    p(a.result.level)
                } else
                    2 === a.result.err && (aa ? ba ? Tip.show("你所拥有的心不足", {
                        type: "warning"
                    }) : m() : (nb("exp_layer", {
                        ver3: 2
                    }),
                    l()))
        },
        error: function() {
            Tip.show("你所拥有的心不足", {
                type: "warning"
            })
        }
    }),
    ca = new tb({
        comment: "topInfo",
        renderTmpl: window.TmplInline_barindex.bar_basic,
        renderContainer: $("#js_bar_basic"),
        renderTool: {
            numHelper: b
        },
        cgiName: _domain + "/cgi-bin/bar/page",
        param: function() {
            var a = {
                bid: ua,
                platform: Wa
            };
            return a
        },
        processData: function(a, c) {
            function d(a) {
                return $.isArray(a) ? a.join(" / ") : a
            }
            var e, f, h, i, j, l, m, o, p, r = {
                bname: a.result.name,
                pic: a.result.pic
            }, s = 0, t = mqq.QQVersion, u = !1, v = 0, w = 14, x = 20, y = $(".header-cover");
            if (y.removeClass("header-cover-loading"),
            localStorage.setItem("barInfoForRecruit", JSON.stringify(r)),
            $("body").removeClass("waiting-render"),
            a.result && a.result.errno && (Tip.show("该部落已关闭", {
                type: "warning"
            }),
            setTimeout(function() {
                mqq.ui.popBack()
            }, 1e3)),
            $a = a.result.is_star_game && ya && $.os.android,
            a.result.is_star_game = $a,
            e = a.result,
            Ra = e.category_name,
            o = e.movie_info,
            G = "明星" === Ra,
            e.isStarCategory = G,
            H = !!e.gameid,
            I = e.gameid,
            e.isGameAppTribe = H,
            bb = e.show_calendar_entrance,
            cb = e["continue"],
            db = e.leak_day,
            eb = e.can_repari_day,
            G && c && n(),
            o && 0 !== o.use_new_style && (Ca = "电影" === Ra,
            Da = "电视剧" === Ra,
            Ea = "综艺" === Ra),
            (Ca || Da || Ea) && (e.display_fans = b(e.fans),
            e.display_pids = b(e.pids)),
            Ca) {
                try {
                    m = localStorage.getItem("movieTicketMap")
                } catch (D) {
                    Fa = {}
                }
                if (m)
                    try {
                        Fa = JSON.parse(m)
                    } catch (D) {
                        Fa = {}
                    }
                else
                    Fa = {};
                this.renderTmpl = window.TmplInline_barindex.bar_special_basic,
                o ? (o.label || (o.label = [],
                o.area && o.label.push(o.area),
                o.main_genre && o.label.push(o.main_genre),
                o.sub_genre && (o.label = o.label.concat(o.sub_genre))),
                o.label = d(o.label),
                o.score ? (o.score = parseFloat(o.score).toFixed(1),
                o.scoreBarPosition = 17 * (Math.floor(parseInt(o.score, 10) + .5) - 10)) : (o.score = "暂无评分",
                o.noScore = !0,
                o.scoreBarPosition = "-170"),
                o.vertical_pic ? o.vertical_pic = Util.qqLiveImageResizer(o.vertical_pic, "f") : o.vertical_pic = e.pic,
                o.leading_actor && (o.leading_actor = d(o.leading_actor)),
                o.description && (p = Math.floor((Ka - x) / w),
                o.description = "" + $.str.decodeHtml(o.description.replace(/^\s+/g, "")),
                o.descLines = Math.min(3, Math.ceil(o.description.length / p)))) : e.movie_info = {
                    score: "暂无评分",
                    noScore: !0,
                    scoreBarPosition: "-170"
                },
                e.intro && (e.intro = "" + $.str.decodeHtml(e.intro.replace(/^\s+/g, ""))),
                z = e.movie_id,
                1 === Number(Fa[e.bid]) && (A = !0),
                e.hasTicket = A,
                y.addClass("special"),
                o.descLines ? y.addClass("has-desc-line-" + o.descLines) : y.addClass("no-desc")
            } else
                Da ? (this.renderTmpl = window.TmplInline_barindex.bar_special_teleplay,
                o.label || (o.label = [],
                o.area && o.label.push(o.area),
                o.main_genre && o.label.push(o.main_genre),
                o.sub_genre && (o.label = o.label.concat(o.sub_genre))),
                o.label = d(o.label),
                o.score ? (o.score = parseFloat(o.score).toFixed(1),
                o.scoreBarPosition = 17 * (Math.floor(parseInt(o.score, 10) + .5) - 10)) : (o.score = "暂无评分",
                o.noScore = !0,
                o.scoreBarPosition = "-170"),
                o.vertical_pic ? o.vertical_pic = Util.qqLiveImageResizer(o.vertical_pic, "f") : o.vertical_pic = e.pic,
                o.leading_actor && (o.leading_actor = d(o.leading_actor)),
                o.director && (o.director = d(o.director)),
                o.description && (p = Math.floor((Ka - x) / w),
                o.description = "" + $.str.decodeHtml(o.description.replace(/^\s+/g, "")),
                o.descLines = Math.min(3, Math.ceil(o.description.length / p))),
                e.intro && (e.intro = "" + $.str.decodeHtml(e.intro.replace(/^\s+/g, ""))),
                z = e.movie_id,
                y.addClass("special teleplay")) : Ea && (this.renderTmpl = window.TmplInline_barindex.bar_special_variety,
                o.label || (o.label = [],
                o.area && o.label.push(o.area),
                o.main_genre && o.label.push(o.main_genre),
                o.sub_genre && (o.label = o.label.concat(o.sub_genre))),
                o.label = d(o.label),
                o.vertical_pic ? o.vertical_pic = o.vertical_pic : o.vertical_pic = e.pic,
                o.presenter && (o.presenter = d(o.presenter)),
                o.description && (p = Math.floor((Ka - x) / w),
                o.description = "" + $.str.decodeHtml(o.description.replace(/^\s+/g, "")),
                o.descLines = Math.min(3, Math.ceil(o.description.length / p))),
                o.newdate && (o.newdate = "更新至" + o.newdate + "期"),
                e.intro && (e.intro = "" + $.str.decodeHtml(e.intro.replace(/^\s+/g, ""))),
                z = e.movie_id,
                y.addClass("special variety"));
            if (Qa = e.bar_class,
            e.barId = ua,
            xa && (e.hideReQun = !0),
            e.isQQ = ya,
            Na = e.pic || Na,
            Oa = e.intro || Oa,
            Pa = e.name,
            U = 1 === Number(e.sign),
            Ua = 1 === Number(e.exsit),
            f = e.recommend)
                for (h = 0,
                j = f.length; j > h; h++)
                    i = f[h],
                    i.share = b(i.share),
                    i.views = b(i.views);
            if (e.pic.indexOf("ugc.qpic.cn") > -1 && /\/$/.test(e.pic) && (e.pic += "120"),
            $(".header-cover-img").css("background-image", "url(" + e.cover + ")"),
            Ja = e.admin_ext,
            E = e.wxflag,
            Ha = e.name,
            Ia = e.fans,
            c && (($.os.ios || $.os.android) && mqq.compare("4.6") >= 0 && mqq.data.readH5Data({
                callid: 1,
                path: "/buluo",
                key: "followTipsMap"
            }, function(a) {
                if (0 === a.ret) {
                    var b;
                    try {
                        b = JSON.parse(a.response.data)
                    } catch (c) {
                        b = {}
                    }
                    1 === b[ua] && (b[ua] = 0,
                    mqq.data.writeH5Data({
                        callid: 1,
                        path: "/buluo",
                        key: "followTipsMap",
                        data: JSON.stringify(b)
                    }, function() {
                        k()
                    }))
                }
            }),
            K = {
                isSpecific: 51 === e.category,
                barName: e.name,
                category: e.category,
                categoryName: e.category_name
            },
            xb && xb.setBarInfo(K),
            K.isSpecific && Q.monitor(2021680)),
            document.title = $.str.decodeHtml(Ha),
            xa)
                var F = $('<iframe src="/favicon.ico"></iframe>').on("load", function() {
                    setTimeout(function() {
                        F.off("load").remove()
                    }, 0)
                }).appendTo($("body"));
            if (mqq && mqq.ui && mqq.ui.refreshTitle && mqq.ui.refreshTitle(),
            ab = a.result.stargroup || 0,
            za.setStarGroupFlag(ab),
            ia.param.starid = T = a.result.starid,
            ab)
                try {
                    $("body").addClass("stargroup")
                } catch (D) {}
            if ($("#js_bar_top").removeClass("hide"),
            oa.param.keyword = C = Util.getHash("key") || e.key_word || e.name || "",
            B = e.flag,
            e.barTypeArr = g(B),
            q(),
            $("#js_fans_num").text(b(e.fans)),
            Sa || (Sa = !0,
            0 === c ? ("0" !== t && (Config.isOffline ? ($.os.ios && (Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 29),
            s += 21),
            Q.huatuo(1486, 1, 1, +new Date - window.pageStartTime, 4)) : ($.os.ios && (Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 28),
            s += 14),
            Q.huatuo(1486, 1, 1, +new Date - window.pageStartTime, 5))),
            Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 20),
            Q.isd(7832, 47, 10, +new Date - window.pageStartTime, 1, null , "allWithCache", !0)) : 1 === c && ("0" !== t && (Config.isOffline ? ($.os.ios && (Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 25),
            s += 7),
            Q.huatuo(1486, 1, 1, +new Date - window.pageStartTime, 4)) : ($.os.ios && Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 24),
            Q.huatuo(1486, 1, 1, +new Date - window.pageStartTime, 5))),
            Q.isd(7832, 47, 2, +new Date - window.pageStartTime, 21),
            Q.isd(7832, 47, 10, +new Date - window.pageStartTime, 1, null , "allWithCache", !1)),
            "0" !== t && $.os.ios && (t.split(".").length > 3 && (t = t.split("."),
            t = t[0] + "." + t[1] + "." + t[2]),
            "5.4.0" === t ? s += 7 : "5.3.2" === t ? s += 6 : "5.3.0" === t ? s += 5 : "5.2.1" === t ? s += 4 : "5.1.1" === t ? s += 3 : "5.0.0" === t ? s += 2 : -1 !== t.indexOf("4.7") && (s += 1),
            s && (l = +new Date,
            window.EnvInfo.getNetwork(function(a) {
                var b = 0;
                "wifi" === a ? b = 25 : "3g" === a ? b = 27 : "2g" === a && (b = 28),
                Q.isd(7832, 47, b, l - window.pageStartTime, s)
            }))),
            "0" !== t ? $.os.ios ? v = 5 : $.os.android && (v = 7) : $.os.ios ? v = 6 : $.os.android && (v = 8),
            u || (Q.isd(7832, 47, 31, +new Date - window.pageStartTime, v),
            u = !0)),
            /\bMicroMessenger\/[\d\.]+/.test(navigator.userAgent)) {
                var J = {
                    link: "http://buluo.qq.com/mobile/barindex.html?from=wechat&_bid=128&_wv=1027&bid=" + ua,
                    title: "兴趣部落-" + Pa,
                    desc: Oa
                };
                e.pic && (J.imgUrl = e.pic),
                window.WechatShare.init(J)
            }
            c > 0 && a.result.push_banner && zb.show(a.result.push_banner.banner_pic, a.result.push_banner.banner_url),
            S = a.result.is_qun_admin
        },
        complete: function(b, c) {
            if (b.result.is_star_game && this.renderContainer.hide(),
            !Ta && 0 === b.retcode)
                if (Ta = 1,
                mqq.compare("5.0") >= 0)
                    setTimeout(function() {
                        mqq.dispatchEvent("updateFocusList", b.result || {}, {
                            domains: ["*.qq.com"]
                        })
                    }, 200);
                else
                    try {
                        window.localStorage.setItem("recentVisitedData", JSON.stringify(b))
                    } catch (d) {
                        window.localStorage.clear(),
                        window.localStorage.setItem("recentVisitedData", JSON.stringify(b))
                    }
            Ca && z > 0 && (this._isFetchingMovie || (this._isFetchingMovie = !0,
            a(function(a, b) {
                $.ajax({
                    url: "http://s.p.qq.com/cgi-bin/coupon_q/dianying/movie_query_v2.fcg?callback=?",
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
                        a.data && 1 === Number(a.data.canbuyticket) ? (A = !0,
                        Fa[ua] = 1,
                        $("#js_op_movie").removeClass("btn-see").addClass("btn-buy").html("立即购票")) : (A = !1,
                        delete Fa[ua]);
                        try {
                            localStorage.setItem("movieTicketMap", JSON.stringify(Fa))
                        } catch (b) {}
                    },
                    error: function(a) {
                        console.log(a)
                    }
                })
            }))),
            c && yb()
        },
        events: function() {
            var a = $("#js_top_focus_nav")
              , b = $("#btn_focus_tribe");
            b.on("tap", function() {
                W.refresh(),
                nb(3, {
                    ver3: _a
                }),
                a.hide()
            })
        },
        error: function(a) {
            101001 !== a.retcode && (this.getCache(0) || 1e5 === a.retcode || (Tip.show("获取部落信息失败[" + a.retcode + "]", {
                type: "warning"
            }),
            $("#js_bar_basic").hide()))
        }
    }),
    da = new tb({
        comment: "barTop",
        renderTmpl: window.TmplInline_barindex.bar_top,
        renderContainer: "#js_bar_top",
        processData: function(a) {
            a.bid = ua;
            var b = 0 !== a.result.owneruin
              , c = a.result.recommend || [];
            b || c.unshift({
                pid: 0,
                type: "employ",
                title: "首席酋长招募啦~快到碗里来！"
            }),
            c = c.slice(0, 4),
            a.result.recommend = c,
            c.length ? $("#js_bar_top").show() : $("#js_bar_top").addClass("hide-top-border")
        },
        complete: function(a, b) {
            b && yb()
        },
        events: function() {
            var a, b, c;
            $("#js_bar_top").on("tap", function(d) {
                var e, f, g = d.target;
                if ($(g).hasClass("top-related-wrap"))
                    return a = g.getAttribute("data-bid"),
                    b = _domain + "barindex&bid=" + a + "&source=barindex",
                    void Util.openUrl(b, !0, 0);
                if ("A" !== g.tagName && (g = g.parentNode),
                "A" === g.tagName) {
                    if ($(g.parentNode.parentNode).addClass("list-active"),
                    nb(10),
                    b = g.getAttribute("data-href"),
                    a = g.getAttribute("data-bid"),
                    e = g.getAttribute("data-pid"),
                    f = g.getAttribute("data-type"),
                    "employ" === f)
                        return void ApplyAdmin.doApply(a, 6, 1);
                    setTimeout(function() {
                        e && (Util.openDetail({
                            bid: a,
                            pid: e,
                            "#source": "barindex"
                        }, null , f),
                        d.preventDefault()),
                        "tribe" === c && (nb("", {
                            module: "tribe_hp",
                            action: "Clk_allsections"
                        }),
                        Util.openUrl(location.href + "&uibarhide=1&uinav=4", !0, 0),
                        d.preventDefault()),
                        $(g.parentNode.parentNode).removeClass("list-active")
                    }, 100)
                }
            })
        },
        error: function(a) {
            this.getCache(0) || Tip.show("获取帖子列表失败[" + a.retcode + "]", {
                type: "warning"
            })
        }
    }),
    fa = new tb({
        comment: "barStarInfo",
        renderTmpl: window.TmplInline_barindex.bar_star_info,
        renderContainer: "#js_bar_star_info",
        processData: function(a) {
            var b = a.result.starinfo;
            b && (b.pic || (b.pic = "http://pub.idqqimg.com/qqun/xiaoqu/mobile/img/star_info_icon.png"))
        },
        events: function() {
            $("#js_bar_star_info").on("tap", function(a) {
                var b, c = a.target, d = $(c).closest(".star-info-container");
                d && (b = d.attr("data-url"),
                Util.openUrl(b + "&starid=" + T, !0))
            })
        }
    }),
    ["10817"].indexOf(ua) > -1 && (qb = !0),
    ea = new tb({
        comment: "barUItestTab",
        renderTmpl: window.TmplInline_barindex.bar_uitest_tab,
        renderContainer: $("#uiTestNav"),
        processData: function(b, c) {
            var d, e, f, g, h, i, j, k;
            if (b = b.result,
            b.isActBar = pb,
            b.bid = ua,
            b.uiNav = kb,
            b.platform = Wa,
            $(".ui-test-nav-wrap").show(),
            "array" === $.type(b.ls) || (b.ls = []),
            g = b.ls,
            ("地区" === b.category_name || "城市" === b.category_name) && b.ls.length && 1 === c) {
                var l = localStorage.getItem("user_location_city")
                  , m = localStorage.getItem("user_location_city_time");
                for (e = 0; e < b.ls.length; e++)
                    if ("同城活动" === b.ls[e].name) {
                        P = b.ls[e],
                        b.ls.splice(e, 1);
                        break
                    }
                P && "0" !== mqq.QQVersion && (!l || !m || Number(m) - Date.now() > 36e5 ? a(function(a, b) {
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
                                b.indexOf(Pa) > -1 && (g.splice(1, 0, P),
                                R = !0,
                                ea.refresh(),
                                localStorage.setItem("user_location_city", b),
                                localStorage.setItem("user_location_city_time", Date.now()))
                            }
                        }
                    };
                    DB.cgiHttp(c)
                }) : l.indexOf(Pa) > -1 && (R = !0,
                g.splice(1, 0, P)))
            }
            if (b.ls.forEach(function(a, b) {
                "分类" === a.name && (h = b),
                "相册" === a.name && (i = b),
                4 === parseInt(a.mid) && (j = !0)
            }),
            "undefined" != typeof h)
                if ("undefined" != typeof i && b.ls[h].mid > b.ls[i].mid) {
                    var n = b.ls[h];
                    b.ls[h] = b.ls[i],
                    b.ls[i] = n
                } else {
                    var o = b.ls[h];
                    o.mid > 4 && !j && (b.ls.push({
                        mid: o.mid,
                        name: "相册",
                        replaced: !0,
                        url: ""
                    }),
                    o.mid = 4)
                }
            if (b.ls.sort(function(a, b) {
                return a.mid - b.mid
            }),
            b.ls.length > 0)
                for (e = 0,
                f = b.ls.length; f > e; e++)
                    b["tab" + b.ls[e].mid] = b.ls[e],
                    b.ls[e].icon_pre in Ba && (b.ls[e].icon_pre = Ba[b.ls[e].icon_pre]),
                    b.ls[e].url || b.ls[e].replaced || (1 === c && nb("exp_guide"),
                    b.ls[e].url = "http://buluo.qq.com/mobile/barindex_game.html?_bid=128&title=" + encodeURIComponent(b.ls[e].name || "") + "&menuid=" + b.ls[e].id + "&bid=" + ua),
                    d = b.ls[e].url.replace(/{{id}}/g, b.ls[e].id).replace(/{{bid}}/g, b.ls[e].bid).replace(/&amp;/g, "&"),
                    k = new ub({
                        comment: "mytab" + b.ls[e].mid + "Link",
                        newWindow: 1
                    }),
                    "相关群" === b.ls[e].name && (d += "&keyword=" + C),
                    d.indexOf("barindex_video") > -1 && 1 === c && nb("exp_video"),
                    b.ls[e] && b.ls[e].name.indexOf("大神") > -1 && 1 === c && nb("exp_god"),
                    k.url = d,
                    sa.add(".myTab_" + b.ls[e].mid + "_link", k)
        },
        events: function() {
            $(".publish-btn").on("tap", function(a) {
                var b = this;
                a.preventDefault(),
                a.stopPropagation(),
                nb(6),
                $(b).addClass("active"),
                setTimeout(function() {
                    $(b).removeClass("active"),
                    o()
                }, 0)
            }),
            this.renderContainer.on("tap", function(a) {
                var b = $(a.target);
                (b.hasClass("myTab_4") || b.hasClass("myTab_4_link")) && nb("tab4_click"),
                "视频" === b.text() && nb("Clk_video"),
                "攻略" === b.text() && nb("Clk_guide"),
                b.text().indexOf("大神") > -1 && nb("Clk_god")
            })
        },
        complete: function(a, b) {
            var c, d, e = 4, f = 0;
            for ("13276" === ua && $(".publish-btn").hide(),
            c = 0; c < a.result.ls.length; c++)
                a.result.ls[c].mid <= 4 ? e -= 1 : f += 1;
            if (f > 0 && ($("#uiTestNav .new-tab-list").css("width", Ka / 4.5 * (a.result.ls.length + e)),
            setTimeout(function() {
                $("html, body").scrollTop(1),
                d = new IScroll("#uiTestNav",{
                    scrollX: !0,
                    scrollY: !1
                }),
                d.on("scrollStart", function() {
                    mqq && $.os.ios && mqq.ui.setWebViewBehavior({
                        swipeBack: 0
                    })
                }),
                d.on("scrollEnd", function() {
                    mqq && $.os.ios && mqq.ui.setWebViewBehavior({
                        swipeBack: 1
                    })
                })
            }, 0)),
            a = a.result,
            $.os.ios || (document.body.style.overflowY = "scroll"),
            1 === b && (a.ls && (a.best = a.best || {},
            a.tribe = a.tribe || {},
            "视频" === a.best.name && 5 === Number(kb) ? kb = 7 : "视频" === a.tribe.name && 6 === Number(kb) && (kb = 7),
            1 === a.ls.length ? 5 === Number(kb) && sa.init(lb[4]) : Number(kb) > 4 && sa.init(lb[kb - 1])),
            sa.rock(),
            xa && $.ajax({
                url: "/cgi-bin/bar/user/mybarlist_v2",
                data: {
                    num: 1,
                    start: 0,
                    t: (new Date).getTime()
                },
                success: function(a) {
                    var b = a.result.point;
                    b > 0 && ($("#m_point").html(b),
                    $("#m_point").show()),
                    $("#m_point")[0].setAttribute("data-point", b),
                    $("#m_point")[0].setAttribute("data-sys", a.result.point_sys),
                    $("#m_point")[0].setAttribute("data-reply", a.result.point_reply)
                }
            })),
            1 === a.hire_flag && a.big_admin_num < 3 && 0 === a.public_type) {
                var g = JSON.parse(localStorage.getItem("apply_admin_tag_cache"));
                g && g[ua] || $("#tab_hof").addClass("apply")
            }
            xa || ta.show()
        }
    }),
    ea.feeded = 1,
    ga = new tb({
        comment: "barSign",
        renderTmpl: window.TmplInline_barindex.bar_sign,
        renderContainer: "#signArea",
        processData: function(a) {
            var b = a.result
              , c = {};
            if (b.isQQ = ya,
            b.showCalendarEntrance = bb,
            b.isGameAppTribe = H,
            !Ca || Da || Ea)
                if (1 === a.result.exsit) {
                    try {
                        D = a.result.level.level
                    } catch (d) {
                        D = 0
                    }
                    c.continueDays = b["continue"],
                    b.scoreInfo = c,
                    b.isStarGroup = ab,
                    b.isStarCategory = G,
                    $(".focus-btn").html("取消关注").addClass("unvote")
                } else
                    $(".focus-btn").html("关注").removeClass("unvote");
            this.topFastContent = a.result
        },
        events: function() {
            var a, b = this;
            $("#js_bar_basic,#gameSignArea").on("tap", function(c) {
                var d, g, j = c.target;
                if ($(j).hasClass("jump_level"))
                    return void Util.openUrl(Ga + "bar_level_rank.html?bid=" + ua, !0, 0);
                if ("js_bar_vote_btn" === j.id || "js_movie_focus_btn" === j.id)
                    return W.refresh(),
                    nb(3, {
                        ver3: _a
                    }),
                    void (Util.getHash("rcmd") && nb("Clk_rankfocus", {
                        ver1: ua
                    }));
                if ("js_op_movie" === j.id)
                    return g = $(j).data("mediaexist"),
                    void (Da ? g ? Util.openUrl(Ga + "barindex_teleplay.html?bid=" + ua + "&type=episode", !0, 0) : Util.openUrl("http://m.v.qq.com/search.html?keyWord=" + encodeURIComponent($.str.decodeHtml(Ha).replace(/\s/g, " ")), !0, 0) : Ea ? g ? Util.openUrl(Ga + "barindex_variety.html?bid=" + ua + "&type=episode", !0, 0) : Util.openUrl("http://m.v.qq.com/search.html?keyWord=" + encodeURIComponent($.str.decodeHtml(Ha).replace(/\s/g, " ")), !0, 0) : $(j).hasClass("btn-see") ? (d = $(j).data("pid"),
                    d ? Util.openDetail({
                        bid: ua,
                        pid: d
                    }, null , 0) : Util.openUrl("http://m.v.qq.com/search.html?keyWord=" + encodeURIComponent($.str.decodeHtml(Ha).replace(/\s/g, " ")), !0, 0),
                    nb("Clk_film")) : $(j).hasClass("btn-buy") && (Util.openUrl("http://web.p.qq.com/qqmpmobile/coupon/nearby2.html?_bid=108&show_movie=1&_wv=5123&maplat=" + x + "maplng=" + y + "&shop_id=" + z, !0, 0),
                    nb("Clk_ticket")));
                if ("signBtn" === j.id || "signBtn" === j.parentNode.id || $(j).hasClass("signed-days-count"))
                    return $("#signBtn").hasClass("signed") ? void (bb && G && ya ? (f(ua, "tribe_hp"),
                    nb("Clk_signed")) : e(G, bb, cb, db, eb, fb)) : i();
                if ("js_go_intro" === j.id || "js_go_intro_arrow" === j.id || $(j).hasClass("intro-wrapper"))
                    return void Util.openUrl(Ga + "barindex_entertainment_intro.html?bid=" + ua, !0, 0);
                if ("sendHeartBtn" === j.id || "sendHeartBtn" === j.parentNode.id || "sendHeartBtn" === j.parentNode.parentNode.id)
                    return nb("Clk_send", {
                        ver3: 2
                    }),
                    void Y.rock();
                if ("js_movie_sign_btn" === j.id)
                    return i();
                if ($(j).closest(".info-grade-wrap").length && T && ya)
                    return h();
                if (nb(2),
                Ca || Da || Ea) {
                    if ("js_bar_logo" === j.id || "js_bar_logo" === j.parentNode.id) {
                        if (localStorage && b.topFastContent)
                            try {
                                localStorage.setItem("topFastContent", JSON.stringify(b.topFastContent))
                            } catch (k) {}
                        return a = Util.getHash("scene"),
                        a = a ? "&scene=" + a : "",
                        void Util.openUrl(Ga + "bar_rank.html?bid=" + ua + a, !0)
                    }
                    return "js_bar_sign_btn" === j.id ? void e(G, bb, cb, db, eb, fb) : void Util.openUrl(Ga + "barindex_entertainment_intro.html?bid=" + ua + "&type=" + (Ca ? "movie" : Da ? "teleplay" : Ea ? "variety" : ""), !0, 0)
                }
                if (!xa) {
                    if (localStorage && b.topFastContent)
                        try {
                            localStorage.setItem("topFastContent", JSON.stringify(b.topFastContent))
                        } catch (k) {}
                    a = Util.getHash("scene"),
                    a = a ? "&scene=" + a : "",
                    Util.openUrl(Ga + "bar_rank.html?bid=" + ua + a, !0)
                }
            }),
            $(".focus-btn").on("tap", function() {
                $(this).hasClass("unvote") ? X.refresh() : (W.refresh(),
                nb(3, {
                    ver3: _a
                }))
            }),
            $("#sign-continue-tips").on("tap", function(a) {
                var b = a.target;
                $(b).hasClass("resign-btn") ? (nb("Clk_repair"),
                f(ua, "sign_float")) : gb > 0 && window.UpgradeTip && window.UpgradeTip.show({
                    level: gb,
                    level_title: hb
                }),
                $("#sign-continue-tips").hide()
            }),
            $("#sign-continue-tips").on("touchstart", function(a) {
                a.preventDefault()
            })
        },
        complete: function(a, b) {
            1 === b && (1 === a.result.sign && a.result.isStarCategory && nb("exp_send", {
                ver3: 2
            }),
            H && 1 === a.result.exsit && j(I))
        }
    }),
    _ = ga.extend({
        $wrap: $("#starGameWrap"),
        $iframe: $("#starGameIframe"),
        $loading: $("#starGameLoading"),
        $sign: $("#gameSignArea"),
        $gamelv: $("#gamelvWrap"),
        $publishbtn: $(".publish-btn"),
        gamehost: "http://ss.xintiao100.com/",
        renderTmpl: window.TmplInline_barindex.bar_sign,
        renderContainer: "#gameSignArea",
        noCache: !0,
        isfollow: !1,
        processData: function(a, b) {
            ga.processData(a, b),
            console.log("-- isStarGame", a.result.is_star_game, b),
            a.result.is_star_game ? (ga.die(),
            window.starGameStarTime = +new Date,
            this.isfollow = 1 === a.result.exsit ? !0 : !1,
            $.os.ios && (document.title = "　"),
            $(".poll-wrapper").hide(),
            this.isInit ? this.syncLevel(a.result.level) : this.initGame(a, b)) : _.die()
        },
        initGame: function(a, b) {
            var c = a.result
              , d = c.user_info || {}
              , e = 2 === c.exsit ? "exp_unfocus" : "exp_focus";
            b && (this.$wrap.css("height", .65 * Ka),
            this.$wrap.show(),
            this.$loading.show(),
            this.isInit = !0,
            this.barinfoData = {
                is_follow: c.exsit,
                openid: d.openid || "",
                openkey: d.openkey || "",
                star_charm_rank: c.star_charm_rank,
                star_charm_count: c.star_charm_count,
                bid: c.bid,
                name: c.name,
                fans: c.fans,
                pic: c.pic,
                today_sign: c.today_sign,
                user_name: d.nick_name,
                user_pic: d.pic,
                user_vipno: d.vipno || 0,
                pf: $.os.ios ? "ios" : "android",
                level: c.level
            },
            console.log("-- starGameInitData", this.barinfoData),
            this.$iframe[0].src = this.gamehost + "index.html?_bid=2263&uin=" + Login.getUin() + "&bid=" + ua,
            ob(e, {
                module: "star_show"
            }))
        },
        events: function() {
            if ($a) {
                var a, b = this, c = !1, d = Util.getHash("scene") ? "&scene=" + d : "", e = Ga + "barindex_stargame_pay.html?bid=" + ua + "&openid=" + b.barinfoData.openid + "&openkey=" + b.barinfoData.openkey;
                ga.events(),
                window.addEventListener("message", function(f) {
                    switch (console.info("-- I listening", JSON.stringify(f.data)),
                    a = JSON.parse(f.data) || {},
                    a.load) {
                    case "init":
                        Q.huatuo(1486, 1, 1, +new Date - window.starGameStarTime, 21);
                        break;
                    case "start":
                        var g = JSON.stringify({
                            bar_info: b.barinfoData
                        });
                        window.frames.gameIframe.postMessage(g, b.gamehost);
                        break;
                    case "done":
                        b.$loading.hide(),
                        1 * a.offline === 1 ? (Q.huatuo(1486, 1, 1, +new Date - window.pageStartTime, 15),
                        Q.huatuo(1486, 1, 1, +new Date - window.starGameStarTime, 17)) : (Q.huatuo(1486, 1, 1, +new Date - window.pageStartTime, 16),
                        Q.huatuo(1486, 1, 1, +new Date - window.starGameStarTime, 18)),
                        Q.huatuo(1486, 1, 1, 1 * a.init_start_time, 19),
                        Q.huatuo(1486, 1, 1, 1 * a.start_done_time, 20)
                    }
                    switch (a.screenmode) {
                    case "half":
                        b.$wrap.css("height", .8 * Ka),
                        setTimeout(function() {
                            b.$publishbtn.show(),
                            b.$iframe.css("height", .8 * Ka)
                        }, 1e3),
                        mqq.ui.setOnCloseHandler(function() {
                            mqq.ui.popBack()
                        }),
                        mqq.ui.setWebViewBehavior({
                            swipeBack: 1
                        });
                        break;
                    case "all":
                        b.$publishbtn.hide(),
                        b.$iframe.css("height", La + 2),
                        b.$wrap.css("height", La + 2),
                        mqq.ui.setOnCloseHandler(function() {
                            window.frames.gameIframe.postMessage(JSON.stringify({
                                action: "to_half"
                            }), b.gamehost)
                        }),
                        mqq.ui.setWebViewBehavior({
                            swipeBack: 0
                        });
                        break;
                    case "lvup":
                        mqq.ui.setOnCloseHandler(function() {
                            window.frames.gameIframe.postMessage(JSON.stringify({
                                action: "to_all"
                            }), b.gamehost)
                        })
                    }
                    switch (a.cmd) {
                    case "follow":
                        W.refresh(),
                        nb(3, {
                            ver3: _a
                        });
                        break;
                    case "sign_change":
                        if (1 * a.sign_visible) {
                            var h = 2 * a.sc
                              , i = {
                                left: a.sign_x - 15 * Math.abs(1 / h),
                                top: a.sign_y - 5 * Math.abs(1 / h)
                            };
                            b.$sign.show(),
                            (h > 1.12 || .88 > h) && (i["-webkit-transform"] = "scale(" + h + ")"),
                            b.$sign.css(i),
                            setTimeout(function() {
                                b.$sign.addClass("to-show")
                            }, 500)
                        } else
                            b.$sign.removeClass("to-show"),
                            setTimeout(function() {
                                b.$sign.hide()
                            }, 500);
                        break;
                    case "takeheart":
                        Y.rock(),
                        nb("Clk_send", {
                            ver3: 2
                        });
                        break;
                    case "show_tips":
                        var j = "幸运星不足，立即前往获得";
                        Alert.show("温馨提示", j, {
                            callback: function() {
                                ob("exp_clk", {
                                    module: "star_show"
                                }),
                                Util.openUrl(e, !0)
                            },
                            confirm: "立即前往",
                            cancel: "取消",
                            confirmAtRight: !0
                        }),
                        ob("exp_unstar", {
                            module: "star_show"
                        });
                        break;
                    case "to_pay":
                        c || (c = !0,
                        ob("Clk_plus", {
                            module: "star_show"
                        }),
                        Util.openUrl(e, !0),
                        setTimeout(function() {
                            c = !1
                        }, 1e3));
                        break;
                    case "to_barrank":
                        Util.openUrl(Ga + "bar_rank.html?bid=" + ua + d, !0);
                        break;
                    case "to_barlevelrank":
                        Util.openUrl(Ga + "bar_level_rank.html?bid=" + ua + d, !0)
                    }
                    switch (a.action) {
                    case "Clk_egg":
                        ob("Clk_egg", {
                            module: "star_show"
                        });
                        break;
                    case "Clk_gift":
                        ob("Clk_gift", {
                            module: "star_show"
                        });
                        break;
                    case "select_gift":
                        ob("select_gift", {
                            module: "star_show",
                            ver3: a.id
                        });
                        break;
                    case "send_gift":
                        ob("send_gift", {
                            module: "star_show",
                            ver3: a.id
                        })
                    }
                }),
                mqq.addEventListener("xiaoqu-barindex-stargame-pay", function() {
                    window.frames.gameIframe.postMessage(JSON.stringify({
                        action: "update",
                        key: "luckstar"
                    }), _.gamehost)
                })
            }
        },
        complete: function() {
            ga.complete()
        },
        syncCharm: function(a) {
            if (this.isInit) {
                var b = JSON.stringify({
                    action: "update",
                    key: "charm",
                    value: a
                });
                console.log("--- update charm", b),
                window.frames.gameIframe.postMessage(b, _.gamehost)
            }
        },
        syncLevel: function(a) {
            if (this.isInit) {
                var b = JSON.stringify({
                    action: "update",
                    key: "level",
                    value: a
                });
                console.log("--- update value", b),
                setTimeout(function() {
                    window.frames.gameIframe.postMessage(b, _.gamehost)
                }, 1e3)
            }
        }
    }),
    window.honourHelper._zeroMaker = c,
    ha = new ub({
        url: "http://buluo.qq.com/mobile/barindex_hof.html",
        newWindow: 1,
        param: {
            _bid: 128,
            bid: ua,
            source: "tribe"
        }
    }),
    ia = new ub({
        url: "http://buluo.qq.com/mobile/albums.html",
        newWindow: 1,
        param: {
            _bid: 128,
            bid: ua,
            starid: T
        }
    }),
    ja = new ub({
        url: "http://buluo.qq.com/mobile/more.html",
        newWindow: 1,
        param: {
            _bid: 128,
            bid: ua,
            barname: Ha,
            act: pb,
            source: "tribe"
        }
    }),
    ka = new ub({
        comment: "mytab1Link",
        newWindow: 1
    }),
    la = new ub({
        comment: "mytab2Link",
        newWindow: 1
    }),
    ma = new ub({
        comment: "mytab3Link",
        newWindow: 1
    }),
    na = new ub({
        comment: "mytab4Link",
        newWindow: 1
    }),
    oa = new ub({
        comment: "relativegroupLink",
        url: "http://buluo.qq.com/mobile/relativegroup.html",
        newWindow: 1,
        param: {
            _bid: 128,
            keyword: C,
            bid: ua,
            barname: Ha,
            act: pb,
            source: "tribe"
        }
    }),
    pa = new ub({
        url: "http://buluo.qq.com/mobile/barindex_best.html",
        newWindow: 1,
        param: {
            _bid: 128,
            bid: ua,
            barname: Ha,
            source: "tribe"
        }
    }),
    qa = new tb({
        renderContainer: rb,
        comment: "emptyRenderModel"
    }),
    sa = new vb({}),
    sa.add("#tab_all", qa),
    sa.add("#tab_hof", ha),
    sa.add("#tab_album", ia),
    xa ? sa.add("#tab_more", ja) : sa.add("#tab_qun", oa),
    sa.add("#tab_best", pa),
    sa.add(".tab_qun_link", oa),
    kb > 1 && 10 > kb && sa.init(lb[kb - 1] || ""),
    sa.beforetabswitch(function(a) {
        if (window._s_selector = a,
        "#tab_hof" === a) {
            if ($(a).hasClass("apply")) {
                var b = JSON.parse(localStorage.getItem("apply_admin_tag_cache")) || {};
                b[ua] = 1,
                localStorage.setItem("apply_admin_tag_cache", JSON.stringify(b)),
                $(a).removeClass("apply"),
                ha.param.showrecruit = 1
            }
            $(a).hasClass("reddot") && ta.write()
        }
    }),
    sa.ontabswitch(function(a, b) {
        var c, d = $("#js_bar_basic");
        J = a,
        ".tab_video" === a && nb("", {
            module: "custom_tab",
            action: "Clk_tab",
            ver3: 4
        }),
        c = $(this).scrollTop(),
        c > 800 ? Xa.removeClass("hide-refresh-btn") : Xa.addClass("hide-refresh-btn"),
        $(".indexpage1").show(),
        $(".indexpage2").hide(),
        $(".indexpage6").hide(),
        $("#js_best_top").hide(),
        $("#js_menu_list").hide(),
        fa.show(),
        $a || d.show(),
        Xa.show(),
        $("#js_bar_list").show(),
        $("#js_menu_list").hide(),
        $(".indexpage7").hide(),
        $(".indexpage8").hide(),
        ".myTab_1" === a || ".myTab_1_link" === a ? nb("", {
            module: "custom_tab",
            action: "Clk_tab",
            ver3: 4
        }) : (".myTab_2" === a || ".myTab_2_link" === a) && nb("", {
            module: "custom_tab",
            action: "Clk_tab",
            ver3: 2
        }),
        "switch" === b && nb(a, {
            ver3: 1
        })
    }),
    L = null ,
    Refresh && Refresh.init({
        dom: document.body,
        reload: function() {
            return L && clearTimeout(L),
            L = setTimeout(function() {
                za.refresh(),
                Q.monitor(429257)
            }, 400),
            window.setTimeout(function() {
                Refresh.hide()
            }, 1e3),
            nb(7),
            nb(0),
            !0
        },
        usingPollRefresh: 1
    }),
    ca.feed(da),
    ca.feed(_),
    ca.feed(ga),
    ca.feed(fa),
    ca.feed(ea),
    ra = new wb,
    ra.add(ca),
    ra.add(_),
    ra.add(ga),
    ra.add(fa),
    ra.add(da),
    ra.add(ea),
    $(window).on("hashchange", function() {
        var a = Util.getHash("target")
          , b = Util.getHash("poi");
        a || Publish.destroy(),
        b || !Publish || Publish.isNative || Publish.hidePoiList()
    }),
    window.mqq && mqq.addEventListener && (mqq.addEventListener("avt_refresh_page", function(a) {
        var b = $("#" + a.data.pid);
        b.length && ("item_close" === a.type ? b.remove() : b.find(".people-num").html(a.data.enroll))
    }),
    mqq.addEventListener("event_focus_tribe", function(a) {
        ("barrank" === a.from || "barindex" === a.from) && (a.focus ? (Ca || Da || Ea ? ($("#js_movie_focus_btn").addClass("btn-movie-sign").removeClass("btn-subscribe").attr("id", "js_movie_sign_btn").html("签到"),
        U && $("#js_movie_focus_btn").addClass("btn-movie-signed").html('<i class="subscribed-icon"></i>已签')) : ($("#opArea").hide(),
        $(".bar-info-text").hide(),
        ca.refresh()),
        Ua = !0,
        $a && window.frames.gameIframe.postMessage(JSON.stringify({
            cmd: "vote"
        }), _.gamehost)) : (Ca || Da || Ea ? $("#js_movie_sign_btn").removeClass("btn-movie-sign btn-movie-signed").addClass("btn-subscribe btn-movie").attr("id", "js_movie_focus_btn").html("关注") : ($("#opArea").show(),
        $(".bar-info-text").show(),
        $(ga.renderContainer).html("")),
        Ua = !1,
        $a && (window.frames.gameIframe.postMessage(JSON.stringify({
            cmd: "unvote"
        }), _.gamehost),
        _.$sign.hide())))
    }),
    mqq.addEventListener("calendar_event_resign_tribe", function() {
        ca.refresh()
    }),
    mqq.addEventListener("event_tribe_credit_change", function(a) {
        a.bid === ua && p(a)
    }),
    mqq.addEventListener("event_post_sticky", function() {
        ca.refresh()
    })),
    ActionButton.setCallback(function() {
        M = {
            admin_ext: 0,
            posts: ActionButton.getUploadVideo(ua)
        };
        var a = Tmpl(window.TmplInline_barindex.bar_list_text, M, window.honourHelper).toString();
        $("#js_uploading_video").html(a)
    }),
    window.formatDuration = r,
    mqq.addEventListener("qbrowserTitleBarClick", function() {
        var a = $.os.android ? $("body") : $("#js_bar_wraper")
          , b = a.scrollTop()
          , c = $("#js_bar_wraper");
        Util.scrollElTop(b, a, c)
    });
    var zb = {
        apiSupport: mqq && mqq.data && mqq.support("mqq.data.readH5Data") && mqq.support("mqq.data.writeH5Data"),
        _showCallback: function(a, b, c) {
            var d = $(".wechat-banner")
              , e = document.getElementById("banner-img")
              , f = this;
            e.onload = function() {
                var a = this.naturalHeight || this.height
                  , b = this.naturalWidth || this.width
                  , c = a / b * Ka || 40;
                d.removeClass("hide").height(c),
                nb("exp_global")
            }
            ,
            e.onerror = function() {
                console.error("Barindex Banner Img error, url =", a)
            }
            ,
            setTimeout(function() {
                e.src = a
            }, 200),
            $("body").on("tap", ".wechat-banner", function() {
                b = b.replace("&amp;", "&").replace("#bid#", ua),
                nb("Clk_global"),
                f._write(c),
                setTimeout(function() {
                    d.addClass("hide"),
                    Util.openUrl(b, !0)
                }, 500)
            })
        },
        _write: function(a) {
            var b = {
                imgId: a
            }
              , c = this;
            if (c.apiSupport)
                mqq.data.writeH5Data({
                    callid: 1024,
                    path: "/buluo",
                    key: "barindexBannerFlag",
                    data: JSON.stringify(b)
                }, function(a) {
                    console.log("barindexBannerFlag writeH5Data: data =", b, "ret =", a)
                });
            else
                try {
                    window.localStorage.setItem("buluo-barindexBannerFlag", JSON.stringify(b))
                } catch (d) {
                    console.error(d)
                }
        },
        _readlocalStorage: function(a, b, c) {
            var d, e, f = this;
            try {
                d = window.localStorage.getItem("buluo-barindexBannerFlag"),
                e = JSON.parse(d),
                console.log("LocalStorage中的banner标记数据", e)
            } catch (g) {
                console.error(g)
            }
            return e && e.imgId && e.imgId === String(c) ? void console.log("barindex banner已点击不再显示") : void f._showCallback(a, b, c)
        },
        show: function(a, b) {
            if (a && b) {
                var c = this
                  , d = a.split("/").pop();
                return c.apiSupport ? void mqq.data.readH5Data({
                    callid: 1024,
                    path: "/buluo",
                    key: "barindexBannerFlag"
                }, function(e) {
                    if (console.log("barindexBannerFlag readH5Data: ret =", e),
                    0 !== e.ret || !e.response)
                        return void c._showCallback(a, b, d);
                    var f;
                    try {
                        f = JSON.parse(e.response.data)
                    } catch (g) {
                        console.log(g)
                    }
                    return f && f.imgId && f.imgId === String(d) ? void console.log("barindex banner已点击不再显示") : void c._showCallback(a, b, d)
                }) : void c._readlocalStorage(a, b, d)
            }
        }
    };
    return ta = {
        _key: "barindex_hoftab_reddot_hide",
        show: function() {
            if (G) {
                var a, b = 6048e5, c = 1440555331588, d = !0;
                try {
                    a = localStorage.getItem(this._key),
                    a = Boolean(a)
                } catch (e) {
                    console.error(e)
                }
                (Date.now() - c > b || a === !0) && (d = !1),
                d && $("#tab_hof").addClass("reddot")
            }
        },
        write: function() {
            if (G)
                try {
                    localStorage.setItem(this._key, !0),
                    $("#tab_hof").removeClass("reddot")
                } catch (a) {
                    console.error(a)
                }
        }
    },
    window.mqq && mqq.addEventListener && mqq.addEventListener("publich_complete_back", function(a) {
        a.user = a.user_info;
        var b = Tmpl(window.TmplInline_barindex.bar_list_text, {
            posts: [a],
            admin_ext: a.admin_ext
        }, window.honourHelper).toString();
        $("#js_bar_list").prepend(b);
        var c = $("#js_bar_list li").eq(0).find(".act-img img");
        if (c.attr("src", c.attr("lazy-src")),
        c.css({
            width: "80px",
            height: "80px"
        }),
        a.post.pic_list && a.post.pic_list.length > 0) {
            var d = imgHandle.formatThumb([{
                w: a.post.pic_list[0].w,
                h: a.post.pic_list[0].h
            }], !0, 80, 80, !0);
            c.css(d[0])
        }
    }),
    {
        init: v,
        updateLevel: p
    }
}),
function(a, b) {
    a.WindowPostMessager = b(a.$, a.Util, a.DB)
}(this, function(a, b, c) {
    function d(a) {
        g.validUrl = a.validUrl,
        g.bid = a.bid,
        g.shareCallback = a.shareCallback,
        g.followCallback = a.followCallback,
        window.addEventListener("message", function(a) {
            if (-1 !== g.validUrl.indexOf(a.origin)) {
                var b = a.data.postcode;
                h[b](a)
            }
        }, !1)
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
    function f(a, d) {
        var e = {
            type: "POST",
            url: "/cgi-bin/bar/user/fbar",
            ssoCmd: "follow_bar",
            param: {
                bid: g.bid,
                op: 1
            },
            succ: function(c) {
                if (0 !== Number(mqq.QQVersion) && ~~c.result.green_hand && "1" !== localStorage.getItem("fbar_green_hand")) {
                    localStorage.setItem("fbar_green_hand", "1");
                    var d = "关注后可在“兴趣圈”查看部落的精华内容，并会收到兴趣号的推送。";
                    Alert.show("关注成功", d, {
                        cancel: "关闭",
                        confirm: "进部落逛逛",
                        confirmAtRight: !0,
                        callback: function() {
                            b.openUrl("http://buluo.qq.com/mobile/barindex.html?&#bid=" + g.bid, !0)
                        }
                    })
                }
                a(c)
            },
            err: function(a) {
                d(a)
            }
        };
        c.cgiHttp(e)
    }
    var g = {}
      , h = {
        share: function(a) {
            var c = location.href;
            c = b.setExterParam(c, "uinav", "5");
            var d = {
                shareUrl: c,
                pageUrl: c,
                imageUrl: a.data.data.imageUrl ? a.data.data.imageUrl : "http://q3.qlogo.cn/g?b=qq&k=XnGFNfgzCr7LnaWFrAw0UQ&s=100",
                title: a.data.data.title ? a.data.data.title : "兴趣部落",
                content: a.data.data.desc ? a.data.data.desc : "总有你喜欢"
            };
            b.shareMessage(d, function() {
                a.source.postMessage({
                    retcode: "share",
                    data: "success"
                }, "*"),
                g.shareCallback && g.shareCallback()
            })
        },
        follow: function(a) {
            f(function() {
                a.source.postMessage({
                    retcode: "follow",
                    data: "success"
                }, "*"),
                g.followCallback && g.followCallback()
            }, function() {
                a.source.postMessage({
                    retcode: "follow",
                    data: "error"
                }, "*")
            })
        },
        getOid: function(a) {
            e(function(b) {
                a.source.postMessage({
                    retcode: "openid",
                    data: b
                }, "*")
            })
        }
    };
    return {
        init: d
    }
}),
function(a, b) {
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
}),
function(a, b) {
    a.ApplyAdmin = b()
}(this, function() {
    function a(a, b) {
        var e = {
            opername: "tribe_cgi",
            module: "post",
            ver1: c,
            ver3: d,
            action: a
        };
        for (var f in b)
            b.hasOwnProperty(f) && (e[f] = b[f]);
        Q.tdw(e)
    }
    function b(b, c, d, e) {
        b = b,
        c = c,
        a("apply_chief"),
        DB.cgiHttp({
            url: "http://xiaoqu.qq.com/cgi-bin/bar/apply/check",
            param: {
                bid: b
            },
            succ: function(c) {
                if (!c.result || c.retcode) {
                    var f = c.retcode || "error";
                    return Tip.show("申请失败(" + f + ")", {
                        type: "warning"
                    }),
                    void a("reject_chief", {
                        ver4: 6
                    })
                }
                if (1 === c.result.violated)
                    return void Tip.show(c.result.violate_msg, {
                        type: "warning"
                    });
                var g = c.result.small_admins
                  , h = c.result.big_admins
                  , i = "";
                if (0 === g && (i += "&sna=1"),
                0 === h && (i += "&bna=1"),
                !c.result.qq_level || c.result.qq_level <= 12)
                    Tip.show("您的QQ等级太低，暂时无法申请酋长！", {
                        type: "warning"
                    }),
                    a("reject_chief", {
                        ver4: 1
                    });
                else if (c.result.isban)
                    mqq.ui.showDialog({
                        title: "",
                        text: "您的帐号因在部落被举报有违规操作，暂时无法申请酋长！",
                        needOkBtn: !0,
                        okBtnText: "我知道了",
                        needCancelBtn: !1
                    }, function() {}),
                    a("reject_chief", {
                        ver4: 2
                    });
                else if (1 === c.result.has_submit || 2 === c.result.has_submit || 3 === c.result.has_submit)
                    3 === c.result.has_submit ? Tip.show("您的酋长申请还在审核流程中，请勿重复申请！", {
                        type: "warning"
                    }) : (1 === c.result.has_submit || 2 === c.result.has_submit) && Tip.show("您在一个月内无法重复申请该部落的酋长！", {
                        type: "warning"
                    }),
                    a("reject_chief", {
                        ver3: 4
                    });
                else if (2 & c.result.admin)
                    Tip.show("您已经是大酋长了，无法申请！", {
                        type: "warning"
                    }),
                    a("reject_chief", {
                        ver3: 5
                    });
                else {
                    var j = "";
                    1 === d && (j = "&bo=1"),
                    11 === d && (j = "&bo=1&noadmin=1"),
                    2 === d && (j = "&so=1"),
                    4 === c.result.has_submit && (j = "&so=1"),
                    5 === c.result.has_submit && (j = "&bo=1"),
                    i += j,
                    1 === e && (i += "&notip=1"),
                    localStorage.setItem("recruitCurrentState", "1"),
                    Util.openUrl("http://xiaoqu.qq.com/mobile/recruit.html?_wv=1027&bid=" + b + "&bname=" + c.result.bname + "&sa=" + (4 & c.result.admin ? 1 : 0) + i, !0)
                }
            }
        })
    }
    var c, d;
    return {
        doApply: b
    }
});
