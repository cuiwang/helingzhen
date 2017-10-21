!function(a, b) {
    var c = a.Detail;
    c.Events = b(c)
}(this, function(a) {
    function b() {
        c(),
        $("#js_detail_main").on("tap", ".img-box img,.richpost-new img", function() {
            if (Q.monitor(648125),
            !window.ImageView)
                return Tip.show("图片查看器加载中，请稍后点击图片重试", {
                    type: "warning"
                }),
                void Q.monitor(648126);
            var a, b, c, d, e, f = $(this), g = [], h = !1, i = !1;
            if (f.closest(".richpost-new").length && (h = !0),
            f.closest("#detail_top_info").length && (i = !0),
            a = h ? $(".richpost-new") : f.parent(),
            a.length) {
                b = h ? a.find("img") : a.closest(".content-wrapper").find(".img-box img"),
                c = 0,
                b.each(function(a) {
                    this === f[0] && (c = a),
                    d = $(this).data("src") || $(this).attr("lazy-src") || this.src,
                    h ? g.push({
                        name: "",
                        mbimg: d
                    }) : (d.indexOf("?") > -1 && (d = d.slice(0, d.indexOf("?"))),
                    g.push({
                        name: "",
                        mbimg: $(this).attr("data-src") ? $(this).attr("data-src") + "1000" : d
                    }))
                }),
                e = {
                    useNavigate: !0,
                    maxZoom: 3,
                    onClose: function() {
                        Refresh.melt()
                    }
                };
                var j = J.admin_ext
                  , k = 2 === (2 & j) || 4 === (4 & j);
                i && !h && k && (e = $.extend(e, {
                    onLongTap: function(a) {
                        ActionSheet.show({
                            items: ["添加至部落相册"],
                            onItemClick: function() {
                                var b = g[a].mbimg;
                                /\/1000$/.test(b) && (b = b.replace(/\/1000$/, "/")),
                                DB.cgiHttp({
                                    url: "/cgi-bin/bar/photo/add",
                                    type: "POST",
                                    ssoCmd: "add_photo",
                                    param: {
                                        bid: F,
                                        flag: 0,
                                        pid: G,
                                        url: b
                                    },
                                    succ: function(a) {
                                        a.result && 999 === a.result.errCode ? Tip.show("图片已在相册中") : Tip.show("已加入部落相册")
                                    },
                                    err: function() {
                                        Tip.show("操作失败", {
                                            type: "warning"
                                        })
                                    }
                                }),
                                H("one_album")
                            }
                        })
                    }
                })),
                ImageView.init(g, c, e),
                Refresh.freeze(),
                H("Clk_big_pic")
            }
        })
    }
    function c() {
        setTimeout(function() {
            window.ImageView && ImageView.onLongTap && (N = !0);
            var a = J.admin_ext;
            2 !== (2 & a) && 4 !== (4 & a) || N || (N = !0,
            loadjs.loadModule("image_view", function() {}))
        }, 500)
    }
    function d() {
        if (mqq && mqq.ui && !a.isInYyb) {
            var b = {
                isDetail: !0,
                iconID: "4",
                type: "more"
            };
            ActionButton.build(b, function() {
                f(),
                e(),
                H("Clk_right")
            }),
            ActionButton.setCallback(a.Post.Normal.triggerUploading)
        }
    }
    function e() {
        var b, c = [], d = [], e = J.uin === a.myuin, f = J.post.status, q = J.admin_ext;
        a.postData.bar_class !== L.qunSubscription && c.push({
            text: "查看部落",
            img: O.tribe,
            onTap: g
        }),
        d.push(g),
        "detail" === a.getTplName(I) && (c.push({
            text: 1 === a.commentType ? "只看楼主" : "查看全部",
            img: O.poster,
            onTap: h
        }),
        d.push(h)),
        c.push({
            text: a.commentOrder ? "顺序查看" : "倒序查看",
            img: a.commentOrder ? O.asc : O.desc,
            onTap: i
        }),
        d.push(i),
        a.isOpenAct && 2 !== f && e && (0 === f && (c.push({
            text: "编辑活动",
            img: O.join,
            onTap: k
        }),
        d.push(k)),
        c.push({
            text: "取消活动",
            img: O.unjoin,
            onTap: l
        }),
        d.push(l)),
        !e && 2 !== f && $(".bottom-bar").hasClass("has-joined") && (c.push({
            text: "取消报名",
            img: O.unjoin,
            onTap: j
        }),
        d.push(j)),
        e || (c.push({
            text: "投诉举报",
            img: O.report,
            onTap: m
        }),
        d.push(m)),
        (1 === (1 & q) || 2 === (2 & q)) && (0 === a.isBest ? (c.push({
            text: "加精话题",
            img: O.best,
            onTap: n
        }),
        d.push(n)) : (c.push({
            text: "取消加精",
            img: O.unbest,
            onTap: n
        }),
        d.push(n)),
        c.push({
            text: a.isSticky ? "取消置顶" : "置顶",
            img: O.sticky,
            onTap: o
        }),
        d.push(o)),
        (2 === (2 & q) || 4 === (4 & q)) && J.post.pic_list && J.post.pic_list[0] && (c.push({
            text: "添加至相册",
            img: O.addphoto,
            onTap: p
        }),
        d.push(p)),
        200 === I && $.os.ios && (b = document.querySelector("video"),
        b && (b.style.display = "none")),
        RichShare.build(c),
        x()
    }
    function f() {
        $(document).off("touchmove", function(a) {
            a.preventDefault()
        }),
        window.Publish && Publish.destroy(),
        $("#join_activity_win").hide(),
        $("#quit_activity_win").hide(),
        $("video").show(),
        window.location.hash.indexOf("imageview") > -1 && window.history.go(-1),
        window.location.hash.indexOf("peopleliked") > -1 && window.history.go(-1),
        window.location.hash.indexOf("peoplejoined") > -1 && window.history.go(-1),
        window.location.hash.indexOf("poi") > -1 && window.history.go(-1)
    }
    function g() {
        H("Clk_more_tribe"),
        Util.openUrl(a.base + "barindex.html#bid=" + F + "&scene=detail_share", !0)
    }
    function h() {
        var b = "";
        1 === a.commentType ? (a.commentType = 2,
        b = "查看全部",
        H("Clk_onlyhost")) : (a.commentType = 1,
        b = "只看楼主"),
        $(this).find("p").text(b),
        a.Comment.refresh()
    }
    function i() {
        var b, c, d = $("#btnShowInturn");
        a.commentOrder ? (a.commentOrder = 0,
        b = "倒序查看",
        c = O.desc,
        H("Clk_inturn")) : (a.commentOrder = 1,
        b = "顺序查看",
        c = O.asc,
        H("Clk_reverse")),
        d.text(b),
        $(this).find("img").attr("src", c),
        $(this).find("p").text(b),
        a.Comment.refresh()
    }
    function j() {
        ActionSheet.hide(!0),
        a.Join.quitAct(),
        K("Clk_quit")
    }
    function k() {
        K("Clk_edit_local"),
        +new Date + 1728e5 > J.post.start ? (Alert.show("", "同城活动在开始前2天内无法编辑！"),
        K("refuse_edit_local")) : Util.openUrl("http://qqweb.qq.com/m/qunactivity/form.html?type=modify&atvid=" + J.post.openact_id + "&_wv=7&_bid=244&open=1", !0)
    }
    function l() {
        Alert.show("取消活动", "活动取消后将无法重新开启，确定要取消活动吗？", {
            confirm: "确定",
            cancel: "取消",
            callback: function() {
                DB.cgiHttp({
                    url: "http://qqweb.qq.com/cgi-bin/qqactivity/close_activity",
                    type: "POST",
                    param: {
                        id: J.post.openact_id,
                        type: 1
                    },
                    succ: function() {
                        Tip.show("取消活动成功"),
                        K("Clk_un_suc4open"),
                        K(435889),
                        a.Join.syncActivity("cancel"),
                        setTimeout(function() {
                            window.mqq && mqq.ui.popBack()
                        }, 1500)
                    },
                    err: function() {
                        Tip.show("取消活动失败", {
                            type: "warning"
                        }),
                        K(435937)
                    }
                })
            }
        }),
        K("Clk_un")
    }
    function m() {
        var b = J.uin;
        return isNaN(Number(b)) || Number(b) <= 0 ? void Alert.show("", "楼主帐号异常，无法举报", {
            confirm: "我知道了"
        }) : (H("Clk_report"),
        void window.jubaokit.init({
            barId: F,
            pid: G,
            eviluin: b,
            impeachuin: a.myuin
        }))
    }
    function n() {
        var b = {}
          , c = this;
        0 === a.isBest ? (b = {
            title: "确认加精"
        },
        H("right_top")) : b = {
            title: "确认取消",
            bestFlag: 0
        },
        ActionSheet.show({
            items: [b.title],
            onItemClick: function() {
                var d = {
                    bid: F,
                    pid: G
                };
                0 === b.bestFlag && (d.isbest = 0),
                DB.cgiHttp({
                    url: "/cgi-bin/bar/post/best",
                    type: "POST",
                    ssoCmd: "post_best",
                    param: d,
                    succ: function() {
                        if (0 === b.bestFlag)
                            $(".best").remove(),
                            a.isBest = 0,
                            Tip.show("取消加精成功", {
                                type: "ok"
                            }),
                            c.find("p").html("加精话题"),
                            c.find("img")[0].src = O.best;
                        else {
                            var d = document.createElement("label");
                            d.className = "best",
                            d.innerHTML = "精",
                            $(".post-title").prepend(d),
                            a.isBest = 1,
                            Tip.show("加精成功", {
                                type: "ok"
                            }),
                            c.find("p").html("取消加精"),
                            c.find("img")[0].src = O.unbest
                        }
                    },
                    err: function() {
                        Tip.show("操作失败", {
                            type: "warning"
                        })
                    }
                })
            }
        })
    }
    function o() {
        var b = {
            title: a.isSticky ? "确认取消置顶" : "确认置顶"
        }
          , c = this;
        ActionSheet.show({
            items: [b.title],
            onItemClick: function() {
                var b = {
                    bid: F,
                    pid: G
                };
                mqq.dispatchEvent("event_post_sticky", {}, {
                    domains: ["*.qq.com"]
                });
                var d = 1 === a.isSticky ? "/cgi-bin/bar/post/del_top" : "/cgi-bin/bar/post/add_top";
                1 !== a.isSticky && H("right_esse"),
                DB.cgiHttp({
                    url: d,
                    type: "POST",
                    param: b,
                    succ: function(b) {
                        0 === b.retcode && (1 === a.isSticky ? (a.isSticky = 0,
                        Tip.show("取消置顶成功", {
                            type: "ok"
                        }),
                        c.find("p").html("置顶")) : (a.isSticky = 1,
                        Tip.show("置顶成功", {
                            type: "ok"
                        }),
                        c.find("p").html("取消置顶")),
                        mqq.dispatchEvent("event_post_sticky", {}, {
                            domains: ["*.qq.com"]
                        }))
                    },
                    err: function(a) {
                        var b = "操作失败,错误码:" + a.retcode;
                        a.retcode + "" == "100225" && (b = "置顶已达上限"),
                        Tip.show(b, {
                            type: "warning"
                        })
                    }
                })
            }
        })
    }
    function p() {
        ActionSheet.show({
            items: ["添加至部落相册"],
            onItemClick: function() {
                var a = {
                    bid: F,
                    flag: 1,
                    pid: G
                };
                DB.cgiHttp({
                    url: "/cgi-bin/bar/photo/add",
                    type: "POST",
                    ssoCmd: "add_photo",
                    param: a,
                    succ: function(a) {
                        a.result && 999 === a.result.errCode ? Tip.show("图片已在相册中") : Tip.show("已加入部落相册")
                    },
                    err: function() {
                        Tip.show("操作失败", {
                            type: "warning"
                        })
                    }
                }),
                H("all_album")
            }
        })
    }
    function q() {
        var a, b = "", c = "w:65&h:65&notFeed:true", d = !1;
        if (J.post.pic_list && J.post.pic_list.length > 0) {
            var e = J.post.pic_list[0];
            if (e.w && e.h) {
                var f = imgHandle.formatThumb([e], !0, 75, 75, !0)[0];
                a = e.url,
                b = "width:" + f.width + "px; height:" + f.height + "px;margin-left:" + f.marginLeft + "px; margin-top:" + f.marginTop + "px;"
            } else
                a = e.url || e,
                d = !0
        } else
            J.post.image1 ? (a = J.post.image1,
            d = !0) : J.post.cover && (a = J.post.cover,
            d = !0);
        return a ? {
            url: imgHandle.getThumbUrl(a, 1e3),
            style: b,
            nosize: d ? c : ""
        } : null 
    }
    function r() {
        if (mqq && parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0)
            return void Alert.show("", "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用", {
                cancel: "确认",
                confirm: "立即升级",
                confirmAtRight: !0,
                callback: function() {
                    mqq.ui.openUrl({
                        url: "http://im.qq.com/immobile/index.html",
                        target: 1,
                        style: 3
                    })
                }
            });
        if (a.isUploading)
            return void Tip.show("视频上传及转码中，暂不能转发", {
                type: "warning"
            });
        H("Clk_repost", {
            module: "post_detail"
        }),
        101 === I && a.openActNewReport("Clk_like"),
        J.thumbImg = q();
        var b = "";
        201 === I && (b = '<div class="c-img-mask"><i class="c-type-icon c-video"></i></div>'),
        (J.post.qqmusic_list || J.post.audio_list) && (b = '<div class="c-img-mask"><i class="c-type-icon c-music"></i></div>');
        var c = '<div class="th-cover" soda-if="thumbImg" ><img lazy-src="{{thumbImg.url}}" nosize="{{thumbImg.nosize}}" style="{{thumbImg.style}}">' + b + '</div><div class="th-text" ><h4 soda-bind-html="title|plain2rich"></h4><p soda-if="post.content" soda-bind-html="post.content|plain2rich"></p></div>';
        Tmpl.addTmpl("forward_template", c),
        Alert.textarea("转发到兴趣圈", Tmpl("forward_template", J).toString(), {
            placeholder: "说说转发理由…",
            confirm: "取消",
            cancel: "发送",
            preventAutoHide: !0,
            onTap: function(b, c, d) {
                var e = $(d).find(".edit").val();
                "right" === b ? (DB.cgiHttp({
                    url: "http://buluo.qq.com/cgi-bin/bar/impt/forward",
                    type: "POST",
                    param: {
                        pid: G,
                        bid: Number(F),
                        content: e
                    },
                    ssoCmd: "forward",
                    succ: function(b) {
                        var c = $("#js-qunact-forward").length ? $("#js-qunact-forward") : $("#to_forward")
                          , d = ~~c.text()
                          , e = b.result && b.result.add_credits
                          , f = function(f) {
                            Tip.show("转发成功" + (e > 0 ? "，经验值+" + e : "")),
                            c.html(d + 1),
                            b.result.new_level && (window.UpgradeTip && window.UpgradeTip.show({
                                level: b.result.new_level,
                                level_title: b.result.new_title
                            }),
                            a.UI.updateLevel(b.result.new_level, b.result.new_title));
                            var g = $.extend({
                                bid: F
                            }, b.result.level);
                            mqq.dispatchEvent("event_tribe_credit_change", g, {
                                domains: ["*.qq.com"]
                            }),
                            f ? f.post && a.Comment.afterReply(f) : b.result.post && a.Comment.afterReply(b.result),
                            window.mqq && mqq.dispatchEvent && (b.result.bar_name = a.postData.bar_name,
                            b.result.post ? (b.result.post.post = JSON.parse(b.result.post.post),
                            mqq.dispatchEvent("forword_complete_back", b.result, {
                                domains: ["*.qq.com"]
                            })) : s(b.result))
                        }
                        ;
                        b.result && b.result.__vcode_flag ? Checkcode.show("verify_v2", function(a) {
                            f(a)
                        }, {
                            type: 4,
                            code: b.result.code
                        }) : f()
                    },
                    err: function(a) {
                        return 100006 === a.retcode && mqq && parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0 ? void Alert.show("", "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用", {
                            cancel: "确认",
                            confirm: "立即升级",
                            confirmAtRight: !0,
                            callback: function() {
                                mqq.ui.openUrl({
                                    url: "http://im.qq.com/immobile/index.html",
                                    target: 1,
                                    style: 3
                                })
                            }
                        }) : void Tip.show(a.msg ? a.msg : "转发失败: 错误码" + a.retcode, {
                            type: "warning"
                        })
                    }
                }),
                H("repost_sure"),
                101 === I && a.openActNewReport("Clk_like_sure")) : H("repost_cancel")
            },
            renderSuccess: function() {
                var a, b, c, d = this;
                imgHandle.lazy($(".th-cover")[0]),
                $(this).find(".edit").on("focus", function() {
                    $(d).find(".a-forwards").css({
                        top: 5
                    })
                }),
                101 === I && (c = $(this).find(".th-text p"),
                a = /<p[^>]*>(.*?)<\/p>/g,
                b = c.html().replace(/&lt;p&gt;/g, "<p>"),
                b = b.replace(/&lt;\/p&gt;/g, "</p>"),
                c.html(b.replace(a, ""))),
                $(this).find(".edit").on("blur", function() {
                    $(d).find(".a-forwards").css({
                        top: 100
                    }),
                    $(document.body).hide(),
                    $(document.body).show()
                })
            }
        })
    }
    function s(b) {
        window.mqq.data.getUserInfo(function(c) {
            c.nick && (b.nick = c.nick,
            b.post = {},
            b.post.post = a.postData.post,
            b.post.title = a.postData.title,
            b.post.pid = a.pid,
            b.uin = a.myuin,
            b.pid = a.pid,
            b.bid = a.postData.bid,
            b.post.bid = a.postData.bid,
            b.bar_name = a.postData.bar_name,
            b.post.post.vid && (b.post.type = 201),
            b.comment = '{"content":""}\n',
            mqq.dispatchEvent("forword_complete_back", b, {
                domains: ["*.qq.com"]
            }))
        })
    }
    function t() {
        $("#to_like").tap(u),
        $("#js-qunact-like").tap(u),
        $("#js-qunact-forward").tap(r),
        $("#to_join").tap(v),
        $("#to_reply").click(w),
        $("#js-qunact-reply").click(w),
        $("#to_forward").tap(r),
        $("#to_share").tap(x),
        $("#js-qunact-share").tap(x),
        $("#js_detail_main").on("tap", 'a[rel="showProfile"]', function() {
            y($(this))
        }).on("tap", 'a[rel="openUrl"]', function() {
            z($(this))
        }).on("tap", ".l-level", function() {
            Util.openUrl(a.base + "bar_level_rank.html?bid=" + F, !0)
        })
    }
    function u() {
        if (!a.showLockTip()) {
            var b = $("#js-qunact-like").length ? $("#js-qunact-like") : $("#to_like");
            if (a.isUploading)
                return void Tip.show("视频上传及转码中，暂不能点赞", {
                    type: "warning"
                });
            if (!b.hasClass("disabled") && !b.hasClass("liked")) {
                if (mqq && parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0)
                    return void Alert.show("", "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用", {
                        cancel: "确认",
                        confirm: "立即升级",
                        confirmAtRight: !0,
                        callback: function() {
                            mqq.ui.openUrl({
                                url: "http://im.qq.com/immobile/index.html",
                                target: 1,
                                style: 3
                            })
                        }
                    });
                b.addClass("liked animating"),
                window.mqq && mqq.dispatchEvent && mqq.dispatchEvent("detailDoLike", {
                    bid: F,
                    pid: G
                }, {
                    domains: ["*.qq.com"]
                }),
                b.html(~~b.html() + 1),
                setTimeout(function() {
                    b.removeClass("animating")
                }, 1e3),
                DB.cgiHttp({
                    url: "/cgi-bin/bar/post/like",
                    type: "POST",
                    ssoCmd: "like",
                    param: {
                        bid: +F,
                        pid: G,
                        like: 1
                    },
                    succ: function() {},
                    err: function(a) {
                        var c = "顶失败了，麻烦再试一次吧"
                          , d = ~~b.html() - 1;
                        return 100006 === a.retcode ? (b.html(d || 0),
                        b.removeClass("liked animating"),
                        void (parseInt(mqq.QQVersion) > 0 && mqq.compare("5.2") < 0 ? (c = "抱歉！由于您的手机QQ版本过低，\n敬请升级后再使用",
                        Alert.show("", c, {
                            cancel: "确认",
                            confirm: "立即升级",
                            confirmAtRight: !0,
                            callback: function() {
                                mqq.ui.openUrl({
                                    url: "http://im.qq.com/immobile/index.html",
                                    target: 1,
                                    style: 3
                                })
                            }
                        })) : Alert.show("", c, {
                            confirm: "好"
                        }))) : (b.html(d || 0),
                        b.removeClass("liked animating"),
                        Alert.show("", c, {
                            confirm: "好"
                        }),
                        void 0)
                    }
                }),
                H("Clk_like", {
                    ver3: I
                })
            }
        }
    }
    function v() {
        if (!a.showLockTip()) {
            if (a.isUploading)
                return void Tip.show("视频上传及转码中，暂不能报名", {
                    type: "warning"
                });
            if (a.purchaseLink)
                return void (B(a.purchaseLink) ? Util.openUrl(a.purchaseLink, !0) : Tip.show("暂时不能购票", {
                    type: "warning"
                }));
            a.Join.joinAct(),
            H(200 === I ? "Clk_video" : "Clk_activity"),
            K("Clk_join4open")
        }
    }
    function w(b) {
        if (!(a.showLockTip() || Alert && Alert.alertStatus)) {
            if (a.isUploading)
                return void Tip.show("视频上传及转码中，暂不能评论", {
                    type: "warning"
                });
            if (!a.isRefDlgOpen) {
                b.preventDefault();
                var c = $("#top_comment_wrapper ul").last().find("li").first();
                c.length && (a.currentCommentFloor = c.data("lz"),
                a.currentCommentID = c.attr("cid")),
                a.Publish.reply(),
                101 === a.postData.type && a.openActNewReport("comment"),
                H("Clk_reply"),
                K("Clk_reply")
            }
        }
    }
    function x() {
        return a.isUploading ? void Tip.show("视频上传及转码中，暂不能分享", {
            type: "warning"
        }) : void a.Share.callHandler()
    }
    function y(a) {
        var b = a.attr("code")
          , c = ~~a.attr("type");
        c = Number(c),
        b && c && (1 === c ? (mqq.ui.showProfile({
            uin: b,
            uinType: 1
        }),
        H("Clk_grpuin", {
            obj1: b
        })) : 2 === c ? (mqq.ui.showProfile({
            uin: b
        }),
        H("Clk_uin")) : 3 === c && ActionSheet.show({
            items: ["加入群组", "加为好友"],
            onItemClick: function(a) {
                0 === a ? (mqq.ui.showProfile({
                    uin: b,
                    uinType: 1
                }),
                H("Clk_joingrp", {
                    obj1: b
                })) : 1 === a && (mqq.ui.showProfile({
                    uin: b
                }),
                H("Clk_friend"))
            }
        })),
        "atvCreater" === a.attr("id") && K("Clk_create_uin")
    }
    function z(a) {
        if (!a.hasClass("disabled")) {
            if (a.hasClass("link")) {
                if (a.closest(".second-comment").length)
                    return;
                A(a)
            }
            Util.openUrl(a.attr("url"), !0, 2)
        }
    }
    function A(a) {
        if (a.hasClass("link-keyword")) {
            var b = a.data("bid")
              , c = a.text();
            b && H("Clk_keyword", {
                ver3: b,
                ver4: c
            })
        } else {
            var d = "";
            d = a.hasClass("add-group") ? 1 : a.hasClass("tribe") ? 2 : a.hasClass("post") ? 4 : 5,
            H("Clk_link", {
                ver3: d,
                ver4: 1
            })
        }
    }
    function B(a) {
        return a.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/)
    }
    function C() {
        mqq.addEventListener("qbrowserTitleBarClick", function() {
            var a = $("#js_detail_main")
              , b = a.scrollTop()
              , c = $("#js_detail_scroll_top");
            Util.scrollElTop(b, a, c)
        }),
        mqq.addEventListener("personel_edit_complete", function(b) {
            a.postData.uin.toString() === a.myuin && $(".user-avatar img").attr("src", b.headimgurl)
        })
    }
    function D() {
        Refresh && Refresh.init({
            dom: a.isIOS ? $("#js_detail_main")[0] : document.body,
            reload: function() {
                H("Clk_refresh"),
                Q.monitor(475347);
                var b = 1;
                return a.Comment.refresh(0, function() {
                    b && (Refresh.hide(),
                    pollRefreshUi.reset(),
                    b = 0)
                }),
                H("visit", {
                    obj1: G,
                    ver3: a.postType,
                    ver4: a.gid,
                    ver5: 2
                }),
                window.setTimeout(function() {
                    Refresh.hide()
                }, 1e4),
                !0
            },
            usingPollRefresh: 1
        })
    }
    function E() {
        J = a.postData,
        I = a.postType,
        b(),
        C(),
        t(),
        d(),
        MediaPlayer.attachPlayer("detail_body"),
        MediaPlayer.checkMusicState("detail_body"),
        D()
    }
    var F = a.bid
      , G = a.pid
      , H = a.report
      , I = 0
      , J = {}
      , K = a.openActReport
      , L = window.BARTYPE.BARCLASS
      , M = "http://pub.idqqimg.com/qqun/xiaoqu/mobile"
      , N = !1
      , O = {
        tribe: M + "/img/share/tribe.png",
        poster: M + "/img/share/poster.png",
        asc: M + "/img/share/asc.png",
        desc: M + "/img/share/desc.png",
        report: M + "/img/share/report.png",
        best: M + "/img/share/best.png",
        unbest: M + "/img/share/unbest.png",
        join: M + "/img/share/join.png",
        unjoin: M + "/img/share/unjoin.png",
        fav: M + "/img/share/fav.png",
        addphoto: M + "/img/share/addphoto.png",
        copy: M + "/img/share/copy.png",
        sticky: M + "/img/share/sticky.png"
    };
    return {
        init: E,
        show: e,
        postSticky: o
    }
});