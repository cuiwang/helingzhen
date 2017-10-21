if (!window.console) {
    var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml", "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];
    window.console = {};
    for (var i = 0; i < names.length; ++i) window.console[names[i]] = function () { }
};
var G = {
    ui: {},
    logic: {},
    util: {},
    data: {},
    domain: {
        w: 'weimob.com',
        t: 'http://stc.weimob.com',
        k: 'http://www.weimob.com/wm-xin-a'
    },
    set: {
        KindEditor_seting: {
            themeType: "simple",
            resizeType: 1,
            syncType: "",
            allowPreviewEmoticons: false,
            items: ['source', 'undo', 'redo', 'plainpaste', 'plainpaste', 'wordpaste', 'clearhtml', 'quickformat', 'selectall', 'fullscreen', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'hr', 'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|', 'emoticons', 'image', 'link', 'unlink', 'preview'],
            allowFileManager: true,
            height: "300px",
            width: 700,
            minWidth: 610,
            afterCreate: function () {
                this.sync()
            },
            afterBlur: function () {
                this.sync()
            }
        },
        dataTables: {
            bProcessing: true,
            bServerSide: true,
            sPaginationType: "full_numbers",
            sAjaxSource: "/plus/page/server.php",
            "oLanguage": {
                "sLengthMenu": " <span>每页显示</span>_MENU_ <span>条记录</span> ",
                "sZeroRecords": "对不起，查询不到任何相关数据",
                "sInfo": "当前显示 _START_ 到 _END_ 条，共 _TOTAL_ 条记录",
                "sInfoEmtpy": "找不到相关数据",
                "sInfoFiltered": "数据表中共为 _MAX_ 条记录)",
                "sProcessing": "正在加载中...",
                "sSearch": "<span>搜索:</span> ",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "第一页",
                    "sPrevious": " 上一页 ",
                    "sNext": " 下一页 ",
                    "sLast": " 最后一页 "
                }
            }
        }
    }
};
(function (n) {
    var f = {
        buttons: {
            button1: {
                text: "OK",
                danger: !1,
                onclick: function () {
                    n.fallr("hide")
                }
            }
        },
        icon: "check",
        content: "Hello",
        position: "top",
        closeKey: !0,
        closeOverlay: !1,
        useOverlay: !0,
        autoclose: !1,
        easingDuration: 300,
        easingIn: "swing",
        easingOut: "swing",
        height: "auto",
        width: "360px",
        zIndex: 100,
        bound: window,
        afterHide: function () { },
        afterShow: function () { }
    },
		t, e, i = n(window),
		u = {
		    hide: function (f, o, s) {
		        if (u.isActive()) {
		            n("#fallr-wrapper").stop(!0, !0);
		            var h = n("#fallr-wrapper"),
						a = h.css("position"),
						c = a === "fixed",
						l = 0;
		            switch (t.position) {
		                case "bottom":
		                case "center":
		                    l = (c ? i.height() : h.offset().top + h.outerHeight()) + 10;
		                    break;
		                default:
		                    l = (c ? -1 * h.outerHeight() : h.offset().top - h.outerHeight()) - 10
		            }
		            h.animate({
		                top: l,
		                opacity: c ? 1 : 0
		            }, t.easingDuration || t.duration, t.easingOut, function () {
		                n.browser.msie ? n("#fallr-overlay").css("display", "none") : n("#fallr-overlay").fadeOut("fast"), h.remove(), clearTimeout(e), o = typeof o == "function" ? o : t.afterHide, o.call(s)
		            }), n(document).unbind("keydown", r.enterKeyHandler).unbind("keydown", r.closeKeyHandler).unbind("keydown", r.tabKeyHandler)
		        }
		    },
		    resize: function (t, i, f) {
		        var e = n("#fallr-wrapper"),
					o = parseInt(t.width, 10),
					s = parseInt(t.height, 10),
					h = Math.abs(e.outerWidth() - o),
					c = Math.abs(e.outerHeight() - s);
		        u.isActive() && (h > 5 || c > 5) && (e.animate({
		            width: o
		        }, function () {
		            n(this).animate({
		                height: s
		            }, function () {
		                r.fixPos()
		            })
		        }), n("#fallr").animate({
		            width: o - 94
		        }, function () {
		            n(this).animate({
		                height: s - 116
		            }, function () {
		                typeof i == "function" && i.call(f)
		            })
		        }))
		    },
		    show: function (o, s, h) {
		        var a;
		        if (u.isActive()) n("body", "html").animate({
		            scrollTop: n("#fallr").offset().top
		        }, function () {
		            n.fallr("shake")
		        }), n.error("Can't create new message with content: \"" + o.content + '", past message with content "' + t.content + '" is still active');
		        else {
		            t = n.extend({}, f, o), n('<div id="fallr-wrapper"><\/div>').appendTo("body"), t.bound = n(t.bound).length > 0 ? t.bound : window;
		            var c = n("#fallr-wrapper"),
						v = n("#fallr-overlay"),
						l = t.bound === window;
		            c.css({
		                width: t.width,
		                height: t.height,
		                position: "absolute",
		                top: "-9999px",
		                left: "-9999px"
		            }).html('<div id="fallr-icon"><\/div><div id="fallr"><\/div><div id="fallr-buttons"><\/div>').find("#fallr-icon").addClass("icon-msg-" + t.icon).end().find("#fallr").html(t.content).css({
		                height: t.height == "auto" ? "auto" : c.height() - 116,
		                width: c.width() - 94
		            }).end().find("#fallr-buttons").html(function () {
		                var i = "",
							n;
		                for (n in t.buttons) t.buttons.hasOwnProperty(n) && (i = i + '<a href="#" class="fallr-button ' + (t.buttons[n].danger ? "fallr-button-danger" : "") + '" id="fallr-button-' + n + '">' + t.buttons[n].text + "<\/a>");
		                return i
		            }()).find(".fallr-button").bind("click", function () {
		                var i = n(this).attr("id").substring(13),
							r;
		                return typeof t.buttons[i].onclick == "function" && t.buttons[i].onclick != !1 ? (r = n("#fallr"), t.buttons[i].onclick.apply(r)) : u.hide(), !1
		            }), a = function () {
		                c.show();
		                var y = l ? (i.width() - c.outerWidth()) / 2 + i.scrollLeft() : (n(t.bound).width() - c.outerWidth()) / 2 + n(t.bound).offset().left,
							r, f, a = i.height() > c.height() && i.width() > c.width() && l ? "fixed" : "absolute",
							o = a === "fixed";
		                switch (t.position) {
		                    case "bottom":
		                        r = l ? o ? i.height() : i.scrollTop() + i.height() : n(t.bound).offset().top + n(t.bound).outerHeight(), f = r - c.outerHeight();
		                        break;
		                    case "center":
		                        r = l ? o ? -1 * c.outerHeight() : v.offset().top - c.outerHeight() : n(t.bound).offset().top + n(t.bound).height() / 2 - c.outerHeight(), f = r + c.outerHeight() + ((l ? i.height() : c.outerHeight() / 2) - c.outerHeight()) / 2;
		                        break;
		                    default:
		                        f = l ? o ? 0 : i.scrollTop() : n(t.bound).offset().top, r = f - c.outerHeight()
		                }
		                c.css({
		                    left: y,
		                    position: a,
		                    top: r,
		                    "z-index": 999999
		                }).animate({
		                    top: f
		                }, t.easingDuration, t.easingIn, function () {
		                    s = typeof s == "function" ? s : t.afterShow, s.call(h), t.autoclose && (e = setTimeout(u.hide, t.autoclose))
		                })
		            }, t.useOverlay ? n.browser.msie && n.browser.version < 9 ? (v.css({
		                display: "block",
		                "z-index": t.zIndex
		            }), a()) : v.css({
		                "z-index": t.zIndex
		            }).fadeIn(a) : a(), n(document).bind("keydown", r.enterKeyHandler).bind("keydown", r.closeKeyHandler).bind("keydown", r.tabKeyHandler), n("#fallr-buttons").children().eq(-1).bind("focus", function () {
		                n(this).bind("keydown", r.tabKeyHandler)
		            }), c.find(":input").bind("keydown", function (t) {
		                r.unbindKeyHandler(), t.keyCode === 13 && n(".fallr-button").eq(0).trigger("click")
		            })
		        }
		    },
		    set: function (n, i, r) {
		        for (var u in n) f.hasOwnProperty(u) && (f[u] = n[u], t && t[u] && (t[u] = n[u]));
		        typeof i == "function" && i.call(r)
		    },
		    isActive: function () {
		        return !!(n("#fallr-wrapper").length > 0)
		    },
		    blink: function () {
		        n("#fallr-wrapper").fadeOut(100, function () {
		            n(this).fadeIn(100)
		        })
		    },
		    shake: function () {
		        n("#fallr-wrapper").stop(!0, !0).animate({
		            left: "+=20px"
		        }, 50, function () {
		            n(this).animate({
		                left: "-=40px"
		            }, 50, function () {
		                n(this).animate({
		                    left: "+=30px"
		                }, 50, function () {
		                    n(this).animate({
		                        left: "-=20px"
		                    }, 50, function () {
		                        n(this).animate({
		                            left: "+=10px"
		                        }, 50)
		                    })
		                })
		            })
		        })
		    }
		},
		r = {
		    fixPos: function () {
		        var r = n("#fallr-wrapper"),
					e = r.css("position"),
					f, u;
		        if (i.width() > r.outerWidth() && i.height() > r.outerHeight()) {
		            f = (i.width() - r.outerWidth()) / 2, u = i.height() - r.outerHeight();
		            switch (t.position) {
		                case "center":
		                    u = u / 2;
		                    break;
		                case "bottom":
		                    break;
		                default:
		                    u = 0
		            }
		            e == "fixed" ? r.animate({
		                left: f
		            }, function () {
		                n(this).animate({
		                    top: u
		                })
		            }) : r.css({
		                position: "fixed",
		                left: f,
		                top: u
		            })
		        } else f = (i.width() - r.outerWidth()) / 2 + i.scrollLeft(), u = i.scrollTop(), e != "fixed" ? r.animate({
		            left: f
		        }, function () {
		            n(this).animate({
		                top: u
		            })
		        }) : r.css({
		            position: "absolute",
		            top: u,
		            left: f > 0 ? f : 0
		        })
		    },
		    enterKeyHandler: function (t) {
		        t.keyCode === 13 && (n("#fallr-buttons").children().eq(0).focus(), r.unbindKeyHandler())
		    },
		    tabKeyHandler: function (t) {
		        t.keyCode === 9 && (n("#fallr-wrapper").find(":input, .fallr-button").eq(0).focus(), r.unbindKeyHandler(), t.preventDefault())
		    },
		    closeKeyHandler: function (n) {
		        n.keyCode === 27 && t.closeKey && u.hide()
		    },
		    unbindKeyHandler: function () {
		        n(document).unbind("keydown", r.enterKeyHandler).unbind("keydown", r.tabKeyHandler)
		    }
		};
    n(document).ready(function () {
        n("body").append('<div id="fallr-overlay"><\/div>'), n("#fallr-overlay").bind("click", function () {
            t.closeOverlay ? u.hide() : u.blink()
        })
    }), n(window).resize(function () {
        u.isActive() && t.bound === window && r.fixPos()
    }), n.fallr = function (t, i, r) {
        var f = window;
        typeof t == "object" && (i = t, t = "show"), u[t] ? (typeof i == "function" && (r = i, i = null), u[t](i, r, f)) : n.error('Method "' + t + '" does not exist in $.fallr')
    }
})(jQuery);
jQuery.fn.extend({
    autoscroll: function () {
        var _self = $(this);
        $('html,body').animate({
            scrollTop: _self.offset().top
        }, 800)
    }
});
(function (a, b) {
    "use strict";
    var c = "undefined" != typeof Element && "ALLOW_KEYBOARD_INPUT" in Element,
		d = function () {
		    for (var a, c, d = [
				["requestFullscreen", "exitFullscreen", "fullscreenElement", "fullscreenEnabled", "fullscreenchange", "fullscreenerror"],
				["webkitRequestFullscreen", "webkitExitFullscreen", "webkitFullscreenElement", "webkitFullscreenEnabled", "webkitfullscreenchange", "webkitfullscreenerror"],
				["webkitRequestFullScreen", "webkitCancelFullScreen", "webkitCurrentFullScreenElement", "webkitCancelFullScreen", "webkitfullscreenchange", "webkitfullscreenerror"],
				["mozRequestFullScreen", "mozCancelFullScreen", "mozFullScreenElement", "mozFullScreenEnabled", "mozfullscreenchange", "mozfullscreenerror"]
		    ], e = 0, f = d.length, g = {}; f > e; e++) if (a = d[e], a && a[1] in b) {
		        for (e = 0, c = a.length; c > e; e++) g[d[0][e]] = a[e];
		        return g
		    }
		    return !1
		}(),
		e = {
		    request: function (a) {
		        var e = d.requestFullscreen;
		        a = a || b.documentElement, /5\.1[\.\d]*Safari/.test(navigator.userAgent) ? a[e]() : a[e](c && Element.ALLOW_KEYBOARD_INPUT)
		    },
		    exit: function () {
		        b[d.exitFullscreen]()
		    },
		    toggle: function (a) {
		        this.isFullscreen ? this.exit() : this.request(a)
		    },
		    onchange: function () { },
		    onerror: function () { },
		    raw: d
		};
    return d ? (Object.defineProperties(e, {
        isFullscreen: {
            get: function () {
                return !!b[d.fullscreenElement]
            }
        },
        element: {
            enumerable: !0,
            get: function () {
                return b[d.fullscreenElement]
            }
        },
        enabled: {
            enumerable: !0,
            get: function () {
                return !!b[d.fullscreenEnabled]
            }
        }
    }), b.addEventListener(d.fullscreenchange, function (a) {
        e.onchange.call(e, a)
    }), b.addEventListener(d.fullscreenerror, function (a) {
        e.onerror.call(e, a)
    }), a.screenfull = e, void 0) : a.screenfull = !1
});
if (!String.prototype.format) {
    String.prototype.format = function () {
        var args = arguments;
        return this.replace(/{(\d+)}/g, function (match, number) {
            return typeof args[number] != 'undefined' ? args[number] : match
        })
    }
};
if (!Date.prototype.format) {
    Date.prototype.format = function (format) {
        var o = {
            "M+": this.getMonth() + 1,
            "d+": this.getDate(),
            "h+": this.getHours(),
            "m+": this.getMinutes(),
            "s+": this.getSeconds(),
            "q+": Math.floor((this.getMonth() + 3) / 3),
            "S": this.getMilliseconds()
        };
        if (/(y+)/.test(format)) format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o) if (new RegExp("(" + k + ")").test(format)) format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
        return format
    }
};
//Array.prototype.remove = function (b) {
//    var a = this.indexOf(b);
//    if (a >= 0) {
//        this.splice(a, 1);
//        return true;
//    }
//    return false;
//};
//Array.prototype.clean = function (deleteValue) {
//    for (var i = 0; i < this.length; i++) {
//        if (this[i] == deleteValue) {
//            this.splice(i, 1);//返回指定的元素  
//            i--;
//        }
//    }
//    return this;
//};
G.string = {
    Empty: ""
};
G.ui.tips = {
    err: function (t, u) {
        this._set(t, u, {
            useOverlay: true,
            position: "center",
            icon: "error",
            autoclose: null,
            closeOverlay: true,
            buttons: {
                button1: {
                    text: '确定',
                    danger: false,
                    onclick: function () {
                        $.fallr('hide')
                    }
                }
            }
        }), afterHide = function () {
            if (u) {
                window.location = u
            }
        }
    },
    info: function (t, u) {
        this._set(t, u, {
            useOverlay: true,
            position: "center",
            icon: "info",
            autoclose: null,
            closeOverlay: true,
            buttons: u ? {
                button1: {
                    text: '确定',
                    onclick: function () {
                        window.location = u
                    }
                }
            } : {
                button1: {
                    text: '确定',
                    danger: false,
                    onclick: function () {
                        $.fallr('hide')
                    }
                }
            }
        })
    },
    suc: function (t, u) {
        this._set(t, u, {
            useOverlay: true,
            position: null,
            autoclose: 1000,
            icon: "check",
            closeOverlay: true,
            buttons: null
        })
    },
    upload: function (t, u) {
        this._set(t, u, {
            useOverlay: true,
            position: null,
            autoclose: false,
            icon: "check",
            closeOverlay: true,
            buttons: null
        })
    },
    _set: function (str, url, opt) {
        opt = $.extend({
            useOverlay: true,
            position: null,
            icon: null,
            autoclose: null,
            closeOverlay: true,
            buttons: {},
            afterHide: function () { }
        }, opt || {});
        if (url) {
            opt.afterHide = function () {
                window.location = url
            }
        } else {
            opt.afterHide = function () { }
        }
        $.fallr('show', {
            content: str,
            autoclose: opt.autoclose,
            useOverlay: opt.useOverlay,
            position: opt.position,
            icon: opt.icon,
            closeOverlay: opt.closeOverlay,
            buttons: opt.buttons,
            afterHide: opt.afterHide
        })
    },
    confirm: function (t, u) {
        $.fallr('show', {
            buttons: {
                button1: {
                    text: '确定',
                    danger: true,
                    onclick: function () {
                        window.location = u
                    }
                },
                button2: {
                    text: '取消'
                }
            },
            content: '<p>' + t + '</p>',
            icon: 'trash'
        })
    },
    confirm_flag: function (t, f) {
        $.fallr('show', {
            buttons: {
                button1: {
                    text: '确定',
                    danger: true,
                    onclick: f
                },
                button2: {
                    text: '取消'
                }
            },
            content: '<p>' + t + '</p>',
            icon: 'trash'
        })
    },
    confirm_tips: function (u, t) {
        $.fallr('show', {
            position: 'center',
            buttons: {
                button1: {
                    text: '验证',
                    danger: true,
                    onclick: function () {
                        window.location = u
                    }
                },
                button2: {
                    text: '继续使用'
                }
            },
            content: t,
            icon: 'info'
        })
    },
    iframe: function (t, u, w, h) {
        if (!w) w = 500;
        if (!h) h = 300;
        console.log(    w);
        $.fallr('show', {
            content: '<h2>' + t + '</h2>' + '<iframe width="' + w + '" height="' + h + '" src="' + u + '" frameborder="0" allowfullscreen></iframe>',
            width: w + 130,
            icon: null,
            closeOverlay: true,
            position: 'center',
            buttons: {
                button1: {
                    text: '关闭'
                }
            }
        })
    },
    iframe2: function (t, u, w, h) {
        if (w) w = 500;
        if (h) h = 300;
        $.fallr('show', {
            content: '<h2>' + t + '</h2>' + '<iframe width="' + w + '" height="' + h + '" src="' + u + '" frameborder="0" allowfullscreen></iframe>',
            width: w + 130,
            icon: null,
            closeOverlay: true,
            position: 'center',
            buttons: {
                button1: {
                    text: '关闭'
                }
            }
        })
    },
    html: function (t, u) {
        var b = {};
        if (u) {
            b = {
                button1: {
                    text: '确定',
                    onclick: function () {
                        $.fallr('hide')
                    }
                }
            }
        }
        $.fallr('show', {
            content: '' + t + '',
            position: 'center',
            buttons: b,
            width: 500,
            afterHide: function () {
                if (u) {
                    window.location = u
                }
            }
        })
    },
    up: function (t, v, u) {
        var c = $.cookie("up_tips");
        if (!c || c != v) {
            var b = {
                button1: {
                    text: '关闭',
                    onclick: function () {
                        $.cookie("up_tips", v, {
                            expires: 365,
                            path: "/"
                        });
                        $.fallr('hide')
                    }
                },
                button2: {
                    text: '查看详情',
                    onclick: function () {
                        $.cookie("up_tips", v, {
                            expires: 365,
                            path: "/"
                        });
                        window.location = u;
                        $.fallr('hide')
                    }
                }
            };
            $.fallr('show', {
                content: '' + t + '',
                position: 'center',
                buttons: b,
                icon: 'up',
                width: '400px',
                height: '230px'
            })
        }
    },
    confirm_t: function (t, u) {
        $.fallr('show', {
            buttons: {
                button1: {
                    text: '确定',
                    danger: true,
                    onclick: function () {
                        window.location = u
                    }
                },
                button2: {
                    text: '取消'
                }
            },
            content: '<p>' + t + '</p>'
        })
    },
    confirm_c: function (t, u) {
        $.fallr('show', {
            position: 'center',
            buttons: {
                button1: {
                    text: '确定',
                    danger: true,
                    onclick: function () {
                        window.location = u;
                    }
                },
                button2: {
                    text: '取消'
                }
            },
            content: t,
            icon: 'info'
        });
    }
};
G.logic.form = {
    tip: function (data) {
        if (data.errno == 0) {
            if (data.tip) {
                G.ui.tips.html(data.tip, data.url)
            } else if(data.prev){
            	G.ui.tips.iframe("预览", data.prev,"810px","400px")
            }else if(data.debug){
            	debugResult(data.debug);
            }else if(data.upload){
            	$.fallr("hide",function(){
            		G.ui.tips.info(data.upload, data.url)
            	})
            	
            }else{
                G.ui.tips.suc(data.error, data.url)
            }
        } else {
            G.ui.tips.err(data.error, data.url)
        }
    },
    validate: function () {
        var $from = $("form.form-validate");
        var is_modal = $from.hasClass("form-modal");
        var modal = null;
        if (is_modal) {
            modal = $from.parents(".modal");
            modal.on('hidden', function () {
                $from.find("div.control-group").removeClass("error success");
                $from.find("span.error").removeClass("error").text("")
            })
        };
        if ($from.length > 0) {
            $from.each(function () {
                $(this).validate({
                    errorElement: "span",
                    errorClass: "help-block error",
                    errorPlacement: function (e, t) {
                        var p = t.parents(".controls");
                        if (p.length > 0) {
                            p.append(e)
                        } else {
                            t.addClass("error");  
                        }
                    },
                    highlight: function (e) {
                        $(e).removeClass("error success").addClass("error");
                        //$(e).closest(".control-group").removeClass("error success").addClass("error");
                    },
                    success: function (e) {
                        e.addClass("valid").closest(".control-group").removeClass("error success")
                    },
                    submitHandler: function (form) {
                        var _sb = true;
                        if (typeof KindEditor !== "undefined" && KindEditor.instances) {
                            $.each(KindEditor.instances, function () {
                                this.sync();
                                var $element = $(this.srcElement[0]);
                                var r = $element.attr("data-rule-required"),
									m = $element.attr("data-msg-required"),
									e = $element.attr("data-rule-rangelength"),
									em = $element.attr("data-msg-rangelength");
                                var v = $.trim($element.val()).replace(/(&nbsp;)|\s|\u00a0/g, '');
                                if (r) {
                                    if (!$element.prop("disabled")&&v.length == 0) {
                                        var msg = m;
                                        G.ui.tips.info(msg ? msg : "内容不能为空");
                                        _sb = false;
                                        return false
                                    }
                                };
                                if (e) {
                                    e = eval(e);
                                    var min = e[0];
                                    var max = e[1];
                                    var vv = $element.val();
                                    if (!$element.prop("disabled")&&vv.length <= min || vv.length >= max) {
                                        G.ui.tips.info(em ? em : "内容不能小于{0}且大于{1}".format(min, max));
                                        _sb = false;
                                        return false
                                    }
                                }
                            })
                        }
                        if (_sb) {
                            var btn = $("button[type='submit']", form); 
                            btn.button('loading'); 
                            $(form).ajaxSubmit({
                                dataType: 'json',
                                success: function (d) {
                                    btn.button('reset');
                                    if (modal) modal.modal('hide');
                                    G.logic.form.tip(d)
                                },

                                error: function (d) {
                                    btn.button('reset');
                                    if (modal) modal.modal('hide');
                                    G.ui.tips.info(d.responseText)
                                }
                            })
                        }
                    }
                })
            })
        }
    },
    ajax_modal: function () {
        var $from = $("form.form-modal");
        var btn = $("button[type='submit']", $from);
        var is_validate = $from.hasClass("form-validate");
        if ($from.length > 0 && btn.length > 0 && !is_validate) {
        	 btn.button('loading');
        	 console.log("..ajax_modal........")
            btn.click(function () {
                $from.ajaxSubmit({
                    dataType: 'json',
                    success: function (d) {
                        btn.button('reset');
                        btn.parents(".modal").modal('hide');
                        G.logic.form.tip(d)
                    },
                    error: function (d) {
                        btn.button('reset');
                        btn.parents(".modal").modal('hide');
                        G.ui.tips.info(d.responseText)
                    }
                });
                return false
            })
        }
    },
    wizard: function () {
        $(".form-wizard").length > 0 && $(".form-wizard").formwizard({
            validationEnabled: !0,
            formPluginEnabled: !0,
            focusFirstInput: !0,
            disableUIStyles: false,
            validationOptions: {
                errorElement: "span",
                errorClass: "help-block error",
                errorPlacement: function (e, t) {
                    t.parents(".controls").append(e)
                },
                highlight: function (e) {
                    $(e).closest(".control-group").removeClass("error success").addClass("error")
                },
                success: function (e) {
                    e.addClass("valid").closest(".control-group").removeClass("error success")
                }
            },
            formOptions: {
                dataType: 'json',
                success: function (d) {
                    G.logic.form.tip(d)
                },
                beforeSubmit: function (d) {
                    var f = true;
                    if (typeof KindEditor !== "undefined" && KindEditor.instances) {
                        $.each(KindEditor.instances, function () {
                            this.sync();
                            var $element = $(this.srcElement[0]);
                            var r = $element.attr("data-rule-required"),
								m = $element.attr("data-msg-required"),
								e = $element.attr("data-rule-range"),
								em = $element.attr("data-msg-range");
                            var v = $.trim($element.val()).replace(/(&nbsp;)|\s|\u00a0/g, '');
                            if (r) {
                                if (!$element.prop("disabled")&&v.length == 0) {
                                    G.ui.tips.info(m ? m : "内容不能为空");
                                    f = false;
                                    $(".form-actions input").removeAttr("disabled");
                                    return false
                                }
                            }
                            if (e) {
                                e = eval(e);
                                var min = e[0];
                                var max = e[1];
                                var vv = $element.val();
                                if (!$element.prop("disabled")&&vv.length <= min || vv.length >= max) {
                                    G.ui.tips.info(em ? em : "内容不能小于{0}且大于{1}".format(min, max));
                                    f = false;
                                    $(".form-actions input").removeAttr("disabled");
                                    return false
                                }
                            }
                        })
                    }
                    if (typeof is_test_image !== "undefined" && f) {
                        if (!$("li.imgbox").length) {
                            G.ui.tips.info(is_test_image);
                            return false
                        }
                    }
                    return f;
                },
                error: function (d) {
                    G.ui.tips.info(d.responseText)
                }
            }
        })
    },
    init: function () {
        this.validate();
        this.wizard();
        this.ajax_modal()
    }
};
G.logic.page = {
    skn: function () {
        var tm = $.cookie('data-theme');
        if (tm) $("body").attr('class', tm).attr("data-theme", tm)
    },
    table: function () {
        var $datatabletool = $("div.datatabletool");
        if ($datatabletool) {
            $datatabletool.hide()
        }
        var $allCheck = $("#listTable input.check_all");
        var $listTableTr = $("#listTable tr:gt(0)");
        var $idsCheck = $("#listTable input[name='check']");
        $allCheck.click(function () {
            var $this = $(this);
            if ($this.attr("checked")) {
                $idsCheck.attr("checked", true);
                $datatabletool.show();
                $listTableTr.addClass("checked")
            } else {
                $idsCheck.attr("checked", false);
                $datatabletool.hide();
                $listTableTr.removeClass("checked")
            }
        });
        $idsCheck.click(function () {
            var $this = $(this);
            if ($this.attr("checked")) {
                $this.parent().parent().addClass("checked");
                $datatabletool.show()
            } else {
                $this.parent().parent().removeClass("checked")
            }
            var $idsChecked = $("#listTable input[name='check']:checked");
            if ($idsChecked.size() > 0) {
                $datatabletool.show()
            } else {
                $datatabletool.hide()
            }
        });
        var ajaxtable = $('table.ajax-dataTables');
        if (ajaxtable.length > 0) {
            ajaxtable.each(function () {
                var $this = $(this),
					$url = $this.data("url");
                if ($url) G.set.dataTables.sAjaxSource = $this.data("url");
                $this.dataTable(G.set.dataTables)
            });
            $(".dataTables_length select").wrap("<div class='input-mini'></div>").chosen({
                disable_search_threshold: 9999999
            })
        };
        var $talbecheckbox = $("table.ajax-checkbox");
        $talbecheckbox.length > 0 && $("table.ajax-checkbox input[type='checkbox'][name='show']").click(function (this_e) {
            this_e.stopPropagation();
            var t = $(this),
				e = t.attr("checked"),
				n = $talbecheckbox.attr("ajax-length"),
				i = t.attr("data-id"),
				r = $talbecheckbox.attr("ajax-url"),
				u, f;
            if (u = $("table.ajax-checkbox input[type='checkbox'][name='show']:checked").length, n && n != 0 && (f = "最多勾选{0}个", u > n)) return G.ui.tips.info(f.format(n)), !1;
            this_e.preventDefault();
            $.ajax(r, {
                type: "post", dataType: "json",
                data: { "id": i, "ck": e ? 1 : 0 }
            }).done(function (d) {
                G.logic.form.tip(d);
                t.attr("checked", d.errno == 0 ? Boolean(e) : Boolean(!e));
            }).fail(function () { G.ui.tips.err("网络异常 请重试") });
        });
        var tablecell = $("table.tablecell ");
        if (tablecell.length > 0) {
            var tharr = new Array();
            $("table.tablecell th.hide").each(function (i, v) { 
                tharr.push($(v).text());
            })
          
            var tmp = '<dl class="dl-horizontal inline"><dt>{0}</dt> <dd>{1}</dd></dl>';
            $("a.expandcell", tablecell).on("click", function () {
                var str = '<tr class="cell" ><td colspan="{0}">{1}</td><tr>';
                var $this = $(this), $tr = $this.parents("tr"),
                    $ntr = $tr.next(), $ctr = $("table.tablecell tr.cell:not(.hide)");
                var $hide_value = $tr.find("td.hide");
                if ($hide_value.length == tharr.length) {
                    if (!$this.hasClass("closed")) {
                        var $trs = G.string.Empty;
                        var $nth = $("table.tablecell th").not(".hide").length; 
                        if (!$ntr.hasClass("cell")) {
                            $hide_value.each(function (i, v) {
                                $trs += tmp.format(tharr[i], $($hide_value[i]).text());
                            });
                            str = str.format($nth, $trs); 
                            $tr.after(str);
                        } else {
                            $ntr.show();
                        }
                        $(this).html('<i class="icon-minus"></i>');
                        $this.addClass("closed");
                    } else {
                        $ntr.hide();
                        $(this).html('<i class="icon-plus"></i>');
                        $this.removeClass("closed");
                    }
                }


            });
        }
    },
    common: function () {
        $(".chosen-select").length > 0 && $(".chosen-select").each(function () {
            var t = $(this),
				r = t.attr("data-nosearch") === "true" ? !0 : !1,
				n = {},
				i = t.data("maxlength");
            i && (n.max_selected_options = i);
            r && (n.disable_search_threshold = 9999999);
            n.no_results_text = "找不到";
            t.chosen(n)
        });
        
         var $datetimepicker = $("div.datetimepicker");
        if ($datetimepicker.length > 0) {
            $datetimepicker.each(function () {
                var $this = $(this),$input = $this.find("input"),
                srco=$input.prev("span.add-on").length>0?$input.prev("span.add-on"):$input.next("span.add-on");
                $this.datetimepicker({
                    language: "cn"
                });
                $input.on("click", function () {
                    srco.trigger("click");
                });
            });
        }
        $(".timepicker").length > 0 && $(".timepicker").timepicker({
            showMeridian: !1
        });
        var $daterangepick = $(".daterangepick");
        if ($daterangepick.length > 0) {
            $daterangepick.each(function () {
                var $this = $(this);
                $this.daterangepicker({
                    timePicker: !0,
                    format: "YYYY/MM/DD HH:mm"
                });
                var next = $this.next("span.add-on");
                var prev = $this.prev("span.add-on");
                next.add(prev).on("click", function () {
                    $this.trigger("click");
                });

            });

        }
        $(".datepick").length > 0 && $(".datepick").datepicker();
        $(".gototop").click(function (n) {
            n.preventDefault();
            $("html, body").animate({
                scrollTop: 0
            }, 600)
        });

        $("div.location_box").length > 0 && $("div.location_box").empty().append(G.util.location.locSelection($("div.location_box").attr("data-district")));
        $("a.anchor").length > 0 && $("a.anchor").click(function (e) {
            var rid = $(this).attr("href");
            $(rid).autoscroll();
            e.preventDefault()
        });
        $("a.audio").length > 0 && $("a.audio").mb_miniPlayer({
            skin: "blue",
            inLine: true,
            showRew: false
        });
        $("a[data-toggle=popover]").length>0&&$("a[data-toggle=popover]").popover().click(function (e) {
               e.preventDefault()
           });
        if ($.browser.mozilla) $(document).on("click", "label", function (n) {
            return ($t = n.target.attributes.type, $n = n.target.localName, $n == "select") ? !1 : typeof $t != "undefined" && $t.value != "radio" && $t.value != "checkbox" ? !1 : void 0
        });

        $("button._back").add("a._back").on("click", function () {
            var u = $(this).data("url");
            if (u) {
                window.location = u;
            } else {
                window.history.go(-1);
            }
        });
        //var $timepicker = $(".timepicker");
        //if ($timepicker.length > 0) {
        //    $timepicker.timepicker();
        //}
    },
    tabs: function () {
        var hash = window.location.hash;
        hash && $('ul.nav a[href="' + hash + '"]').tab('show')
    },
    copy: function () {
        ZeroClipboard.setDefaults({
          moviePath: G.domain.k + "/swf/zeroclipboard.swf"
        });
        $(".copy_text").each(function (i) {
            var $id = "copy_button{0}".format(i),
				_tmp = '  <button class="btn copy" id="{0}" type="button" data-clipboard-target="{2}" data-clipboard-text="{1}"><i class="icon-copy"><\/i>复制<\/button>  <span class="alert copy-success help-inline alert-success hide "  >复制成功,请粘帖到您需要的地方<\/span>',
				$this = $(this),
				v = $this.text(),
				$iid = $this.attr("id");
            if (/^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(v)) {
                var _atmp = '<a href="{0}" target="_blank" title="点击新窗口打开">{0}<\/a>';
                $this.html(_atmp.format(v))
            };
            if ($iid) {
                $this.after(_tmp.format($id, "", $iid))
            } else {
                $this.append(_tmp.format($id, v))
            };
            var el = $("#" + $id);
            var clip = new ZeroClipboard(el);
            var success = function (el) {
                var span = el.nextAll("span.copy-success");
                span.show();
                setTimeout(function () {
                    span.hide()
                }, 2e3)
            };
            $("button.copy").click(function () {
                var $this = $(this),
					v = $this.data("clipboard-text").trim();
                if ($.browser.msie) {
                    window.clipboardData.setData("Text", v);
                    success($this)
                } else {
                    prompt('按下 Ctrl+C 复制到剪贴板', v)
                }
            });
            clip.on('complete', function (client, args) {
                success($(client.options.button))
            })
        })
    },
    intpm:function () {
    
    },
    input_text_clear: function () {
        $("input[type=text]").not(".no-clear").unbind("clear-focus").bind("clear-focus", (function () {
            if ($(this).data("clear-button")) return;
            var x = $("<a class='clear-text' style='cursor:pointer;color:#000;font-size:14px;text-decoration:none'><i class='icon-remove'></i></a>");
            $(x).data("text-box", this);
            $(x).mouseover(function () {
                $(this).addClass("over")
            }).mouseleave(function () {
                $(this).removeClass("over")
            });
            $(this).data("clear-button", x);
            $(x).css({
                "position": "absolute",
                "left": ($(this).position().right),
                "top": $(this).position().top + 2,
                "margin": "3px 0px 0px -20px"
            });
            $(this).after(x)
        })).unbind("clear-blur").bind("clear-blur", (function (e) {
            var x = $(this).data("clear-button");
            if (x) {
                if ($(x).hasClass("over")) {
                    $(x).removeClass("over");
                    $(x).hide().remove();
                    $(this).val("");
                    $(this).removeData("clear-button");
                    var txt = this;
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    setTimeout($.proxy(function () {
                        $(this).trigger("focus")
                    }, txt), 50);
                    return false
                }
            }
            if (x && !$(x).hasClass("over")) {
                $(this).removeData("clear-button");
                $(x).remove()
            }
        }));
        $("input[type=text].clear_text").on("focus", function () {
            var $this = $(this),
				$l = $this.val().length;
            if ($l > 0) {
                $this.trigger("clear-focus")
            };
            $this.on("keyup", function () {
                if ($this.val().length > 0) {
                    $this.trigger("clear-focus")
                } else {
                    $this.trigger("clear-blur")
                }
            })
        }).on("blur", function () {
            $(this).trigger("clear-blur")
        })
    },
    init: function () {
        this.skn();
        if ($("span.copy_text").length > 0) this.copy();
        if ($(".clear_text").length > 0) this.input_text_clear();
        if ($("table.dataTable").length > 0) this.table();
        if ($("table.draggable").length > 0) $("table.draggable tbody").sortable({
            cursor: "move",
            opacity: 0.5
        });
        this.tabs();
        this.common()
    }
};
G.util.location = {
    _loc: false,
    getLocInfo: function (district) {
        if (!G.util.area) return false;
        var self = G.util.location;
        if (self._loc === false) {
            self._loc = {};
            $.each(G.util.area, function (pid, pinfo) {
                $.each(pinfo.l, function (cid, cinfo) {
                    $.each(cinfo.l, function (did, dname) {
                        self._loc[did] = [dname, pid, pinfo.n, cid, cinfo.n]
                    })
                })
            })
        }
        var loc = self._loc[district];
        if (!loc) return false;
        return {
            name: loc[0],
            provinceId: loc[1],
            provinceName: loc[2],
            cityId: loc[3],
            cityName: loc[4]
        }
    },
    locSelection: function (district) {
        var htm = ['<select name="province" data-rule-required="true"><option value="" >请选择</option>'];
        $.each(G.util.area, function (pid, pinfo) {
            htm.push('<option value="' + pid + '">' + pinfo.n + '</option>')
        });
        htm.push('</select> <select name="city" data-rule-required="true"><option value="" >请选择</option></select> <select name="district" data-rule-required="true"><option value="" >请选择</option></select>');
        var j = $(htm.join(''));
        var p = j.filter('select:eq(0)');
        var c = j.filter('select:eq(1)');
        var d = j.filter('select:eq(2)');
        p.change((function (_, __, ___) {
            return function () {
                var pid = _.val();
                var cHtm = ['<option value="">请选择</option>'];
                if (G.util.area[pid]) {
                    $.each(G.util.area[pid].l, function (cid, cinfo) {
                        cHtm.push('<option value="' + cid + '">' + cinfo.n + '</option>')
                    })
                }
                __.empty().html(cHtm.join(''));
                __.hide()[0].style.display = '';
                __.change()
            }
        })(p, c, d));
        c.change((function (_, __, ___) {
            return function () {
                var pid = _.val();
                var cid = __.val();
                var dHtm = ['<option value="">请选择</option>'];
                if (G.util.area[pid] && G.util.area[pid].l[cid]) {
                    var r = $.extend({}, G.util.area[pid].l[cid].l);
                    var l = [];
                    $.each(r, function (k, v) {
                        l.push({
                            id: k,
                            name: v
                        })
                    });
                    l.sort(function (a, b) {
                        return a.name.toString().localeCompare(b.name.toString())
                    });
                    $.each(l, function (kk, dInfo) {
                        dHtm.push('<option value="' + dInfo.id + '">' + dInfo.name + '</option>')
                    });
                    l = null
                }
                ___.empty().html(dHtm.join(''));
                ___.hide()[0].style.display = ''
            }
        })(p, c, d));
        j.setLocation = function (dist) {
            var p = $(this).filter('select:eq(0)'),
				c = $(this).filter('select:eq(1)'),
				d = $(this).filter('select:eq(2)');
            var ddInfo = G.util.location.getLocInfo(dist);
            if (ddInfo !== false) {
                p.val(ddInfo.provinceId).change();
                setTimeout(function () {
                    c.val(ddInfo.cityId).change();
                    setTimeout(function () {
                        d.val(dist)
                    }, 1)
                }, 1)
            } else {
                p.val(0);
                p.change()
            }
        };
        j.getLocId = function () {
            return $(this).filter('select:eq(2)').val()
        };
        j.setLocation(district);
        return j
    },
    getLocName: function (district) {
        var self = G.util.location;
        var dInfo = self.getLocInfo(district);
        if (dInfo === false) return '';
        return dInfo.provinceName + dInfo.cityName + dInfo.name
    }
};
G.util.scrollLoading = function (e, options) {
    var defaults = {
        attr: "data-url",
        container: $(window),
        callback: $.noop
    };
    var params = $.extend({}, defaults, options || {});
    params.cache = [];
    $(e).each(function () {
        var node = this.nodeName.toLowerCase(),
            url = $(this).attr(params["attr"]);
        //重组
        var data = {
            obj: $(this),
            tag: node,
            url: url
        };
        params.cache.push(data);
    });

    var callback = function (call) {
        if ($.isFunction(params.callback)) {
            params.callback.call(call.get(0));
        }
    };
    //动态显示数据
    var loading = function () {

        var contHeight = params.container.height();
        if ($(window).get(0) === window) {
            contop = $(window).scrollTop();
        } else {
            contop = params.container.offset().top;
        }

        $.each(params.cache, function (i, data) {
            var o = data.obj,
                tag = data.tag,
                url = data.url,
                post, posb;

            if (o) {
                post = o.offset().top - contop, post + o.height();

                if ((post >= 0 && post < contHeight) || (posb > 0 && posb <= contHeight)) {
                    if (url) {
                        //在浏览器窗口内
                        if (tag === "img") {
                            //图片，改变src
                            callback(o.attr("src", url));
                        } else {
                            o.load(url, {}, function () {
                                callback(o);
                            });
                        }
                    } else {
                        // 无地址，直接触发回调
                        callback(o);
                    }
                    data.obj = null;
                }
            }
        });
    };

    //事件触发
    //加载完毕即执行
    loading();
    //滚动执行
    params.container.bind("scroll", loading);
};
G.util.formatUrl = function (url, mode, host, pathname) {
    function _undef(val, defaultVal) {
        return val === undefined ? defaultVal : val;
    };
    function _inArray(val, arr) {
        for (var i = 0, len = arr.length; i < len; i++) {
            if (val === arr[i]) {
                return i;
            }
        }
        return -1;
    }
    mode = _undef(mode, '').toLowerCase();
    if (url.substr(0, 5) != 'data:') {
        url = url.replace(/([^:])\/\//g, '$1/');
    }
    if (_inArray(mode, ['absolute', 'relative', 'domain']) < 0) {
        return url;
    }
    host = host || location.protocol + '//' + location.host;
    if (pathname === undefined) {
        var m = location.pathname.match(/^(\/.*)\//);
        pathname = m ? m[1] : '';
    }
    var match;
    if ((match = /^(\w+:\/\/[^\/]*)/.exec(url))) {
        if (match[1] !== host) {
            return url;
        }
    } else if (/^\w+:/.test(url)) {
        return url;
    }

    function getRealPath(path) {
        var parts = path.split('/'),
            paths = [];
        for (var i = 0, len = parts.length; i < len; i++) {
            var part = parts[i];
            if (part == '..') {
                if (paths.length > 0) {
                    paths.pop();
                }
            } else if (part !== '' && part != '.') {
                paths.push(part);
            }
        }
        return '/' + paths.join('/');
    }
    if (/^\//.test(url)) {
        url = host + getRealPath(url.substr(1));
    } else if (!/^\w+:\/\//.test(url)) {
        url = host + getRealPath(pathname + '/' + url);
    }

    function getRelativePath(path, depth) {
        if (url.substr(0, path.length) === path) {
            var arr = [];
            for (var i = 0; i < depth; i++) {
                arr.push('..');
            }
            var prefix = '.';
            if (arr.length > 0) {
                prefix += '/' + arr.join('/');
            }
            if (pathname == '/') {
                prefix += '/';
            }
            return prefix + url.substr(path.length);
        } else {
            if ((match = /^(.*)\//.exec(path))) {
                return getRelativePath(match[1], ++depth);
            }
        }
    }
    if (mode === 'relative') {
        url = getRelativePath(host + pathname, 0).substr(2);
    } else if (mode === 'absolute') {
        if (url.substr(0, host.length) === host) {
            url = url.substr(host.length);
        }
    }
    return url;
};
G.util.select = function (options) {
    var bindEls = [],
		items = {},
		settings = {
		    data: {},
		    file: null,
		    root: "0",
		    ajax: null,
		    timeout: 30,
		    method: "post",
		    field_name: null,
		    auto: !1,
		    default_text: "请选择"
		};
    options && jQuery.extend(settings, options);
    items = settings.data;

    function _bind(element, value) {
        var $e = $(element).data("selected");
        $e && (value = $e);
        var key = bindEls.length ? bindEls[bindEls.length - 1].key + ',' + bindEls[bindEls.length - 1].value : settings.root;
        bindEls.push({
            element: element,
            key: key,
            value: value
        });
        var item_count = 0;
        for (var i in items) {
            item_count++
        };
        for (var el_id in bindEls) {
            if (bindEls[el_id].element == element) {
                var self_id = parseInt(el_id)
            }
        };
        for (var el_id in bindEls) {
            if (el_id < self_id) {
                bindEls[el_id].element.change(function () {
                    _fill(element)
                })
            }
        };
        if (self_id > 0) {
            bindEls[self_id - 1].element.change(function () {
                _fill(element, bindEls[self_id].key)
            })
        };
        element.change(function () {
            var self_key = bindEls[self_id - 1] ? bindEls[self_id].key + ',' + $(this).val() : '0,' + $(this).val();

            if (typeof bindEls[self_id + 1] != 'undefined') {
                bindEls[self_id + 1].key = self_key
            };
            if (settings.field_name) {
                $(settings.field_name).val($(this).val())
            };
            if (settings.auto) {
                if (typeof bindEls[self_id + 1] == 'undefined') {
                    _find(self_key, function (key, json) {
                        if (json) {
                            var el = $('<select></select>');
                            element.after(el);
                            _bind(el, '');
                            _fill(bindEls[self_id + 1].element, key, json)
                        }
                    })
                }
            }
        });
        _fill(element, key, value)
    };

    function _fill(n, t, i) {
        var r, e, u, f, s, o, h;
        if (n.empty(), n.append('<option value="">{0}<\/option>'.format(settings.default_text)), r = _find(t, function () {
			_fill(n, t, i)
        }), !r) return settings.auto && n.hide(), !1;
        n.show();
        var arr = new Array();
        for (var x in r) {
            arr.push(x);
        };
        if (arr.length == 0) n.hide();
        e = 1;
        u = 0;
        for (f in r) if (!r.hasOwnProperty(r[f])) s = r[f], o = "", f == i && (u = e, o = 'selected="selected"'), h = $('<option value="' + f + '" ' + o + ">" + s + "<\/option>"), n.append(h), e++;
        n[0] && (setTimeout(function () {
            n[0].options[u].selected = !0
        }, 0), n[0].selectedIndex = 0, n.attr("selectedIndex", u));
        n.width(n.width())
    };

    function _find(n, t) {
        var i, r;
        if (typeof n == "undefined" || n[n.length - 1] == ",") return null;
        if (typeof items[n] == "undefined") {
            i = 0;
            for (r in items) {
                i++;
                break
            }
            settings.ajax ? $.getJSON(settings.ajax, {
                key: n
            }, function (i) {
                items[n] = i;
                t(n, i)
            }) : settings.file && i == 0 && $.getJSON(settings.file, function (i) {
                items = i;
                t(n, i)
            })
        }
        return items[n]
    };

    function _getEl(n) {
        return typeof n == "string" ? $(n) : n
    };

    return {
        bind: function (n, t) {
            typeof n != "object" && (n = _getEl(n));
            t = t ? t : "";
            _bind(n, t)
        },
        find: function () {
            var a = arguments;
            if (a.length > 0) {
                var p = items["0"][a[0]]; 
                var c = items["0,{0}".format(a[0])][a[1]];
                var d = items["0,{0},{1}".format(a[0], a[1])][a[2]]; 
            } 
            return {
                p: p,
                c: c,
                d: d,
                toString: function () {
                    d = (typeof d == "undefined" ? G.string.Empty : d);
                    return p + c + d;
                }
            }
        }
    }
};
G.util.table = {
    checked: function () {
        var $idsCheck = $("#listTable input[name='check']:checked");
        var selectedItems = new Array();
        $idsCheck.each(function () { selectedItems.push($(this).val()); });
        return selectedItems;
    }
};
G.logic.uploadify = {
    op: null,
    live: function () {
        $("textarea.bewrite").live("focus ", function () {
            $(this).parents("span").addClass("on")
        });
        $("textarea.bewrite").live("blur ", function () {
            $(this).parents("span").removeClass("on")
        });
        $("a.item_close").live("click ", function (n) {
            var t = G.logic.uploadify,
				i = G.logic.uploadify.op;
            $.fallr("show", {
                buttons: {
                    button1: {
                        text: "确定",
                        danger: !0,
                        onclick: function () {
                            var r = $(n.target).closest("li.imgbox"),
								u;
                            $.post(t.op.del_url, {
                                id: r.data("postId"),
                                url: r.data("url")
                            });
                            t.op.count - $("li.imgbox").length >= 0 && (u = $("#file_upload-button"), u.removeClass("disabled").attr("style", ""), u.html('<i class="icon-plus-sign"><\/i> 继续上传'));
                           
                           	i.el.uploadify("settings", "uploadLimit", ++i.count);
                            r.remove();
                            $.fallr("hide")
                        }
                    },
                    button2: {
                        text: "取消"
                    }
                },
                content: "<p>你确定要删除这张图片吗？<\/p>",
                icon: "trash"
            })
        });
        $(".ipost-list").sortable({
            opacity: .8
        })
    },
    add: function (data,ocount) {
        var _tmp = ' <li class="imgbox" data-post-id="{0}" data-url="{3}"><a class="item_close" href="javascript:void(0)" title="删除"><\/a>  <input type="hidden" value="{0}" name="phout_list[]" /> <input type="hidden" value="{3}" name="phout_url[{0}][]" /> <span class="item_box"><img src="{1}" /><\/span> <span class="item_input"> <textarea name="imagestexts[{0}][]" class="bewrite" cols="3" rows="4" style="resize: none" data-rule-maxlength="150" placeholder="图片描述...">{2}<\/textarea><i class="shadow hc"><\/i><\/span><\/li>';
        $("#fileList").append(_tmp.format(data.id, data.thm_url, data.title, data.url));
        $.browser.msie && eval(parseInt($.browser.version)) < 10 && $("textarea[placeholder]").watermark();
		if (ocount - $("li.imgbox").length <= 0) {
            var n = $("#file_upload-button");
            n.addClass("disabled").attr("style", "z-index: 999;");
            n.html("上传已达限制...")
        }
			
    },
    set: function () {
        var _self = this;
        _self.op.count=_self.op.count || 1;
        var	ocount=_self.op.count || 1,
			c = _self.op.count - $("li.imgbox").length,
			max_size = _self.op.data.type_id > 0 ? "300kb" : "600kb";
        _self.op.el.uploadify({
            swf: _self.op.swf,
            uploader: _self.op.url,
            cancelImage: "uploadify-cancel.png",
            buttonClass: "btn pl_add btn-primary",
            removeTimeout: 0,
            fileSizeLimit: typeof _self.op.maxlength == "undefined" ? max_size : _self.op.maxlength,
            buttonText: '<i class="icon-plus-sign"><\/i> 添加图片',
            formData: _self.op.data,
            buttonCursor: "pointer",
            fileTypeDesc: "图片格式",
            fileTypeExts: "*.jpg;*.bmp;*.png; *.jpeg",
            queueSizeLimit: 100,
            uploadLimit: c || 1,
            onUploadError: function (n) {
                alert(n.name + "上传失败!")
            },
            onUploadStart: function () {
                $("#file_upload-button").html('<i class="icon-plus-sign"><\/i> 继续上传')
            },
            onInit: function () {
                if (_self.op.count - $("li.imgbox").length <= 0) {
                    var n = $("#file_upload-button");
                    n.addClass("disabled").attr("style", "z-index: 999;");
                    n.html("上传已达限制...")
                }
            },
            onUploadSuccess: function (n, t) {
                var i = $.parseJSON(t);
                console.log(i.result !== "SUCCESS")
                if (i.result !== "SUCCESS") {
                    G.ui.tips.info(i.message || t);
                    return
                }
                typeof G.logic.uploadify.Callback != "undefined" && G.logic.uploadify.Callback(i.image);
                _self.add(i.image,ocount)
            }
        })
    },
    init: function (op) {
        this.op = op;
        this.set();
        this.live()
    }
};
if (!((/weimob.com/i.test(window.location)))) G.domain.t = "/static", G.domain.k = "/wm-xin-a";
$(function () {
    G.logic.page.init();
    G.logic.form.init()
});

/*作者: wang_.long@qq.com  */
