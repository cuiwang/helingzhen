!function(a, b) {
    var c = a.Detail;
    c.Comment = b(c, window.TmplInline_commentList)
}(this, function(a, b) {
    function c(b) {
        var c = -R;
        return function() {
            return c += R,
            {
                bid: O,
                pid: P,
                num: R,
                start: c + b,
                liveorder: a.commentOrder,
                barlevel: 1
            }
        }
    }
    function d() {
        var a = R;
        return R > S ? (a = S,
        S = 0) : S -= R,
        {
            bid: O,
            pid: P,
            num: a,
            start: S,
            barlevel: 1
        }
    }
    function e() {
        K.cgiName = 200 === y ? da : ca,
        K.param = d(),
        K.rock()
    }
    function f(a) {
        if (a && a.post_array) {
            for (var b = a.post_array, c = {
                audio_list: [],
                video_list: [],
                pic_list: [],
                qqmusic_list: [],
                content: ""
            }, d = 0; d < b.length; d++)
                "text" === b[d].type && (c.content = b[d].content),
                "audio" === b[d].type && c.audio_list.push(b[d]),
                "video" === b[d].type && c.video_list.push(b[d]),
                "qqmusic" === b[d].type && c.qqmusic_list.push(b[d]),
                "pic" === b[d].type && c.pic_list.push({
                    w: b[d].width,
                    h: b[d].height,
                    url: b[d].url
                });
            for (var e in c)
                c.hasOwnProperty(e) && $.isArray(c[e]) && !c[e].length && delete c[e];
            a = c
        }
        return a
    }
    function g(b, c) {
        var d, e, g = b.result || {};
        for (g.comments = g.comments || [],
        T = c - 1,
        g.currentPage = T,
        200 === y && (g.comments = o(g.comments)),
        d = 0,
        e = g.comments.length; e > d; d++) {
            var h, i = g.comments[d], j = i.ref_comment;
            if (window.innerWidth <= 320 && (i.user.vipno && i.user.admin_ext > 0 || i.user.vipno && i.user["continue"] > 7 ? i._needShort = !0 : i._needShort = !1),
            i.comment = f(i.comment),
            i.reward_money && (i.comment.content = "{$=赞赏" + Number(i.reward_money) / 100 + "元}" + i.comment.content),
            h = i.comment.pic_list,
            h && h.length) {
                var k;
                k = h.length > 1 ? imgHandle.formatThumb(h, !0, _, _) : imgHandle.formatThumb(h, !1, 180, 180),
                i.comment.pic_list = k
            }
            if (j) {
                j.comment = f(j.comment);
                var l = j.comment.pic_list
                  , m = j.comment.audio_list
                  , n = j.comment.qqmusic_list
                  , p = j.comment.video_list
                  , q = "";
                if (j.comment.isRefComment = !0,
                l && l.length)
                    for (var r = 0; r < l.length; r++)
                        q += "[图片]";
                m && m.length && (q = "[语音]"),
                n && n.length && (q = "[音乐]"),
                p && p.length && (q = "[视频]"),
                j.comment.textPrefix = q,
                j.isdel && (j.comment.textPrefix = "",
                j.comment.content = "评论已删除"),
                aa[j.cid] = j
            }
        }
        b.result.comments.length ? ($(".empty-comment").hide(),
        $("#js_detail_scroll_top").removeClass("no-comment")) : $("#js_detail_scroll_top").addClass("no-comment"),
        g._isAdmin = honourHelper.isAdmin(g.admin_ext),
        g._myuin = U,
        g._isPoster = z,
        g._isStarGroup = a.isStarGroup,
        g.barId = O,
        g.isOfficialAccountTribe = a.isOfficialAccountTribe
    }
    function h() {
        var b = "";
        return b = 1 === a.commentType ? a.commentOrder ? da : ca : ea
    }
    function i(a, b) {
        Z = !1,
        j(a),
        J.melt(),
        H.hide(),
        I.show(),
        F.show(),
        Y.off("scroll.down").off("scroll.up"),
        G.empty(),
        J.cgiName = h(),
        J.refresh(),
        A = b
    }
    function j(b) {
        "undefined" == typeof b && (X = 0),
        S = b ? 200 === y ? a.postData.total_comment_v2 - b - 1 : b - 1 : 0,
        J.paramCache = [];
        var d = $(".show-more-before");
        S ? (J.param = c(S),
        d.show()) : (J.param = c(0),
        d.hide())
    }
    function k(b) {
        if (console.log("评论成功", b),
        b.new_level > 0 && (window.UpgradeTip && window.UpgradeTip.show({
            level: b.new_level,
            level_title: b.new_title
        }),
        a.UI.updateLevel(b.new_level, b.new_title)),
        mqq.dispatchEvent("event_tribe_credit_change", $.extend({
            bid: O
        }, b.level), {
            domains: ["*.qq.com"]
        }),
        Y.off("scroll.up").off("scroll.down"),
        G.empty(),
        Z && (T = 0),
        1 === a.commentOrder || 2 === a.commentType)
            return void J.refresh();
        if (b.post) {
            var c = b.new_index || b.post.total + 1
              , d = parseInt((c - 1) / R);
            d === T ? l(d, function(a) {
                $("#comment_page_" + d).html(a),
                imgHandle.lazy($("#comment_page_" + d)[0]),
                r(c, !1, function() {})
            }) : l(d, function(b, e) {
                n(e, c) && (G.append(b),
                J.freeze(),
                r(c, !1, function() {
                    m(d),
                    B.addClass("spinner").html("载入中，请稍候...").show(),
                    I.hide(),
                    F.hide(),
                    Z || 0 === a.currentCommentFloor ? H.text("回到顶部").show() : H.text("回到" + a.currentCommentFloor + "楼").show()
                }))
            })
        }
    }
    function l(a, c) {
        var d = new N({
            comment: "comment_page_model",
            cgiName: ca,
            param: {
                bid: O,
                pid: P,
                num: R,
                start: a * R,
                barlevel: 1
            },
            noCache: 1,
            renderTmpl: b.normal,
            renderContainer: $(document.createDocumentFragment()),
            processData: function(b) {
                g.call(this, b, a + 1),
                b.result.barId = O
            },
            complete: function(a) {
                c(this.renderContainer, a)
            }
        });
        d.rock()
    }
    function m(a) {
        function b() {
            if (Y.scrollTop() < 60) {
                if (f)
                    return;
                if (console.log("加载上一页"),
                d === J.cgiCount - 1) {
                    B.hide(),
                    I.show(),
                    F.show(),
                    H.hide(),
                    Y.off("scroll.down");
                    var a = $("#comment_page_" + (d + 1)).position().top;
                    return void Y.scrollTop(a)
                }
                B.addClass("spinner").html("载入中，请稍候...").show(),
                f = !0,
                l(d, function(a) {
                    f = !1,
                    G.prepend(a);
                    var b = $("#comment_page_" + (d + 1)).position().top;
                    imgHandle.lazy(C[0]),
                    Y.scrollTop(b - 63),
                    d--
                })
            }
        }
        function c() {
            var a = Y.scrollTop()
              , b = $.os.ios ? Y[0].scrollHeight - 100 : $("body")[0].scrollHeight - 160
              , c = $(window).height();
            100 > b - a - c && (g || (g = !0,
            l(e + 1, function(a, b) {
                g = !1,
                G.append(a),
                imgHandle.lazy(C[0]),
                e++,
                b.result.isend && (Y.off("scroll.up"),
                B.removeClass("spinner").html("已经到底了").show())
            })))
        }
        var d = a - 1
          , e = a
          , f = !1
          , g = !1;
        Y.off("scroll.down").on("scroll.down", b).off("scroll.up").on("scroll.up", c),
        Y.scrollTop() < 60 && b()
    }
    function n(a, b) {
        for (var c = a.result.comments || [], d = c.length, e = 0, f = !1; d > e; e++)
            if (c[e].index === b) {
                f = !0;
                break
            }
        return f
    }
    function o(a) {
        return a
    }
    function p(a) {
        return a ? a.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&quot;/g, '"').replace(/&apos;/g, "'") : a
    }
    function q() {
        var b = ["a", ".user-avatar", ".user-nick", ".honour", ".ref-comment", ".js-btn-action", ".music", ".link", ".audio", ".post-video", ".img-box img", ".richpost-new img", ".l-level"].join(",");
        $("#btnShowInturn").tap(function() {
            var b = $(this);
            a.commentOrder ? (a.commentOrder = 0,
            b.text("倒序查看").parent().addClass("reverse"),
            V("Clk_inturn")) : (a.commentOrder = 1,
            b.text("顺序查看").parent().removeClass("reverse"),
            V("Clk_reverse")),
            i()
        }),
        $("#show-more-before").tap(function() {
            e()
        }),
        C.on("tap", ".js-btn-action", function(a) {
            var b = $(this)
              , c = b.closest("li")
              , d = c.attr("cid");
            if (d)
                return a.stopImmediatePropagation(),
                b.hasClass("delete") ? void L.delWithConfirm("delComment", {
                    cid: d
                }, b, c) : void (b.hasClass("like") && L.likeComment(b, c))
        }).on("tap", ".ref-comment", function(a) {
            var b = $(this).data("id")
              , c = ~~$(this).data("isdel");
            b && 1 !== c && (V("Clk_quote_layer"),
            u(b),
            a.stopImmediatePropagation())
        }),
        C.on("tap", "li", function(a) {
            var c = $(a.target)
              , d = $(this);
            c.is(b) || c.parents(b).length || (d.addClass("list-active"),
            setTimeout(function() {
                d.removeClass("list-active")
            }, 200))
        }),
        C.on("click", "li", function(c) {
            if (!Alert || !Alert.alertStatus) {
                var d = $(c.target)
                  , e = $(this)
                  , f = e.attr("cid")
                  , g = ~~e.data("lz")
                  , h = "commentReply"
                  , i = p(e.attr("nick_name"));
                d.is(b) || d.parents(b).length || (a.currentCommentFloor = g,
                a.currentCommentID = f,
                e.find(".ref-comment").length ? (V("Clk_reply_layer"),
                h = "refReply") : V("Clk_reply_own"),
                V("Clk_reply_one"),
                a.Publish.reply(f, h, i, ~~g))
            }
        }),
        D.on("tap", function(a) {
            var b = $(a.target);
            return b.closest(".user-avatar").length && V("layer_head"),
            b.closest(".audio").length ? void V("layer_voice") : b.closest(".music").length ? void V("layer_music") : b.closest(".post-video").length ? void V("layer_video") : void v()
        }),
        H.on("tap", function() {
            if ($(this).hide(),
            I.show(),
            F.show(),
            Y.off("scroll.down").off("scroll.up"),
            B.addClass("spinner").html("载入中，请稍候...").show(),
            "回到顶部" === $(this).text())
                V("return_top"),
                Y.scrollTop(0),
                J.melt(),
                G.empty(),
                0 === J.cgiCount && J.rock();
            else {
                var b = $.os.ios ? Y[0].scrollHeight - 100 : $("body")[0].scrollHeight - 160;
                V("return_floor"),
                Y.scrollTop(b),
                r(a.currentCommentFloor - 1, !0, function() {
                    J.melt(),
                    G.empty()
                })
            }
        }).on("touchend", function(a) {
            a.preventDefault()
        })
    }
    function r(a, b, c) {
        function d(a) {
            j = j || a,
            k = a;
            var f = a - j;
            m = h - f,
            l = e(f, n, o, h),
            b && (l = i - l),
            Y.scrollTop(l),
            h > f ? window.requestAnimationFrame(d) : (c && c(),
            console.log("scroll done! scrollTop:", l),
            imgHandle.lazy(C[0]))
        }
        function e(a, b, c, d) {
            return c * ((a = a / d - 1) * a * a + 1) + b
        }
        var f = $("#comment_" + a);
        if (f.length) {
            var g = f.position().top - 44
              , h = 1e3
              , i = Y.scrollTop()
              , j = null 
              , k = null 
              , l = null 
              , m = null 
              , n = i
              , o = g;
            return b && (o = i - g + 64,
            n = 0),
            Z ? (Y.scrollTop(g),
            c && c(),
            void imgHandle.lazy(C[0])) : void window.requestAnimationFrame(d)
        }
    }
    function s(a) {
        if (Z = !0,
        console.log("直接跳转到", a + 1, "楼"),
        a > 0 && R > a)
            J.cgiName = h(),
            J.myData = a,
            J.rock();
        else if (a >= R) {
            var b = parseInt((a - 1) / R);
            l(b, function(c, d) {
                return n(d, a) ? (J.freeze(),
                G.append(c),
                B.addClass("spinner").html("载入中，请稍候...").show(),
                I.hide(),
                F.hide(),
                void r(a, !1, function() {
                    t(a),
                    m(b),
                    H.text("回到顶部").show(),
                    d.result.isend && B.removeClass("spinner").html("已经到底了").show()
                })) : (I.show(),
                F.show(),
                Y.scrollTop(0),
                J.melt(),
                G.empty(),
                void (0 === J.cgiCount && J.rock()))
            })
        }
    }
    function t(a) {
        console.log(a),
        setTimeout(function() {
            var b = $("#comment_" + a);
            b.addClass("highlight")
        }, 150)
    }
    function u(b) {
        var c = aa[b];
        c && (a.isRefDlgOpen = !0,
        fa.data = c,
        fa.data.barId = O,
        fa.rock())
    }
    function v() {
        $(document).off("touchmove.dialog"),
        E.addClass("close-anim-out"),
        D.removeClass("fade-in").addClass("fade-out"),
        setTimeout(function() {
            $(".detail-main,.bottom-bar").removeClass("blur")
        }, 50),
        D.on("webkitAnimationEnd", function() {
            a.isRefDlgOpen = !1,
            D.hide().removeClass("fade-out"),
            E.removeClass("close-anim-out dlg-anim"),
            $(".comment-dialog-content .user-avatar,.comment-dialog-content .name-wrap").removeClass("dlg-anim"),
            D.off("webkitAnimationEnd")
        })
    }
    function w() {
        B = $("#js_comment_loading"),
        C = $("#js_detail_list"),
        D = $("#comment_dialog"),
        E = $(".comment-dialog-close"),
        F = $("#top_comment_wrapper"),
        G = $("#bottom_comment_wrapper"),
        I = $("#top_post_wrapper"),
        H = $("#back_to_top")
    }
    function x() {
        y = a.postType,
        z = a.postData.isposter,
        w(),
        $(".show-inturn").show(),
        (100 === y || 101 === y) && $("#qunact-reply").show(),
        "true" === a.getParam("nojump") && (W = !0),
        W ? i(X) : X ? (a.commentOrder = 0,
        $("#btnShowInturn").html("倒序查看"),
        $(".show-inturn").addClass("reverse"),
        s(X)) : i(0),
        q()
    }
    var y, z, A, B, C, D, E, F, G, H, I, J, K, L, M = scrollModel, N = renderModel, O = a.bid, P = a.pid, R = 20, S = 0, T = 0, U = Login.getUin(), V = a.report, W = !1, X = ~~a.getParam("lnum"), Y = $.os.ios ? $("#js_detail_main") : $(window), Z = !1, _ = Math.floor(($(document).width() - 75) / 3) - 4, aa = {}, ba = "_v2", ca = "/cgi-bin/bar/post/get_comment_by_page" + ba, da = "/cgi-bin/bar/post/get_comment_by_page_reverse" + ba, ea = "/cgi-bin/bar/post/get_comment_by_page_with_user" + ba;
    J = new M({
        comment: "comment_model",
        cgiName: ca,
        renderTmpl: b.normal,
        renderContainer: "#top_comment_wrapper",
        scrollEl: $.os.ios ? $("#js_detail_main") : $(window),
        scrollThreshold: .25,
        param: c(0),
        noCache: 1,
        renderTool: honourHelper,
        processData: g,
        events: function() {
            $("body").on("tap", "#reloadCommonets", function() {
                B.addClass("spinner").html("载入中，请稍候..."),
                J.rock()
            })
        },
        complete: function(a, b) {
            MediaPlayer.initVPlayerMuti(this.renderContainer, {
                width: $(window).width() - 75
            }),
            MediaPlayer.checkMusicState("top_comment_wrapper"),
            imgHandle.lazy(C[0]),
            a.result.isend ? (this.freeze(),
            1 !== b || 0 !== a.result.comments.length || S ? (B.removeClass("spinner").html("已经到底了").show(),
            $(".empty-comment").hide()) : (B.hide(),
            $(".empty-comment").show())) : a.result.comments.length < 10 && Q.monitor(609401),
            A && A(a);
            var c = this.myData;
            c && (r(this.myData, !1, function() {
                t(c)
            }),
            this.myData = null )
        },
        onreset: function() {
            $(this.renderContainer).empty(),
            B.addClass("spinner").html("载入中，请稍候...")
        },
        error: function(a) {
            B.removeClass("spinner").html("拉取失败[" + a.retcode + ']，<a href="javascript:;" id="reloadCommonets">点击重试</a>')
        }
    }),
    K = new N({
        comment: "prepage_comment_model",
        cgiName: "",
        noCache: 1,
        renderTmpl: b.normal,
        renderTool: honourHelper,
        renderContainer: $(document.createDocumentFragment()),
        processData: g,
        complete: function() {
            var a = $(J.renderContainer).find("li").first()
              , b = a.position().top
              , c = Y.scrollTop()
              , d = 0;
            this.renderContainer.prependTo(J.renderContainer),
            d = a.position().top,
            Y.scrollTop(c + d - b),
            imgHandle.lazy(C[0]),
            0 === S && $(".show-more-before").hide()
        }
    }),
    L = {
        error: function(a) {
            var b = "删除失败[" + a.retcode + "]";
            101e3 === a.retcode && (b = "此为管理员发表，无法删除"),
            Tip.show(b, {
                type: "warning"
            })
        },
        delWithConfirm: function(a, b, c, d) {
            var e = {
                bid: O,
                pid: P
            };
            return e = $.extend(e, b),
            c.hasClass("isAdmin") ? void window.setTimeout(function() {
                ActionSheet.show({
                    items: ["同时将该用户拉黑", "只删除不拉黑"],
                    onItemClick: function(b) {
                        switch (b) {
                        case 0:
                            e.black = 1;
                            break;
                        case 1:
                            e.black = 0
                        }
                        L[a](e, c, d)
                    }
                })
            }, 0) : (z ? V("del_other_com", {
                ver3: e.cid
            }) : V("del_own_com", {
                ver3: e.cid
            }),
            void L[a](e, c, d))
        },
        delComment: function(b, c, d) {
            DB.cgiHttp({
                type: "POST",
                url: "/cgi-bin/bar/post/del_comment",
                ssoCmd: "del_comment",
                param: b,
                succ: function() {
                    d.remove(),
                    mqq.dispatchEvent("delete_comment"),
                    z && V("del_reply", {
                        ver3: P
                    }),
                    a.openActReport("del_reply_suc")
                },
                err: L.error
            })
        },
        likeComment: function(a, b) {
            if (!a.hasClass("liked") && !a.hasClass("liked-active")) {
                var c = b.data("lz")
                  , d = b.attr("cid")
                  , e = b.find(".comment-user-info .user-nick").data("profile-uin");
                a.addClass("liked-active"),
                a.text(~~a.text() + 1),
                V("like_comment", {
                    obj1: P,
                    ver3: c
                }),
                DB.cgiHttp({
                    type: "POST",
                    url: "/cgi-bin/bar/post/like_comment",
                    ssoCmd: "like_comment",
                    param: {
                        bid: O,
                        pid: P,
                        cid: d,
                        tuin: e,
                        like: 1
                    }
                })
            }
        }
    };
    var fa = new N({
        comment: "ref_comment_dialog",
        renderTmpl: window.TmplInline_detail.ref_comment,
        renderContainer: "#comment_dialog_content",
        events: function() {
            bouncefix.add("comment-dialog")
        },
        complete: function() {
            $(".detail-main,.bottom-bar").addClass("blur"),
            D.show().addClass("fade-in"),
            E.addClass("dlg-anim"),
            setTimeout(function() {
                $(".comment-dialog-content .user-avatar,.comment-dialog-content .name-wrap").addClass("dlg-anim")
            }, 300),
            setTimeout(function() {
                $(".comment-dialog-content .content-wrapper").addClass("dlg-anim")
            }, 400),
            MediaPlayer.initVPlayerMuti(D[0]),
            MediaPlayer.checkMusicState("comment_dialog_content");
            var a = $(".comment-dialog");
            a[0].scrollHeight <= a.height() && $(document).on("touchmove.dialog", function(a) {
                a.preventDefault()
            })
        }
    });
    return {
        rock: x,
        refresh: i,
        loadPrevPage: e,
        afterReply: k,
        hideRefDialog: v
    }
});