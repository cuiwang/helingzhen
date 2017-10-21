!
function(a) {
    var b = {};
    b.dialog = function(a, b, c, d) {
        d || (d = {}),
        d.containerName || (d.containerName = "modal-message");
        var e = $("#" + d.containerName);
        0 == e.length && ($(document.body).append('<div id="' + d.containerName + '" class="modal animated" tabindex="-1" role="dialog" aria-hidden="true"></div>'), e = $("#" + d.containerName));
        var f = '<div class="modal-dialog modal-sm">	<div class="modal-content">';
        if (a && (f += '<div class="modal-header">	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>	<h3>' + a + "</h3></div>"), b && (f += $.isArray(b) ? '<div class="modal-body">正在加载中</div>': '<div class="modal-body">' + b + "</div>"), c && (f += '<div class="modal-footer">' + c + "</div>"), f += "	</div></div>", e.html(f), b && $.isArray(b)) {
            var g = function(a) {
                e.find(".modal-body").html(a)
            };
            2 == b.length ? $.post(b[0], b[1]).success(g) : $.get(b[0]).success(g)
        }
        return e
    },
    b.image = function(c, d, e) {
        require(["webuploader", "cropper", "previewer"],
        function(f) {
            var g, h, i, j = b.querystring("i"),
            k = b.querystring("j");
            defaultOptions = {
                pick: {
                    id: "#filePicker",
                    label: "点击选择图片",
                    multiple: !1
                },
                auto: !0,
                swf: "./resource/componets/webuploader/Uploader.swf",
                server: "./index.php?i=" + j + "&j=" + k + "&c=utility&a=file&do=upload&type=image&thumb=0",
                chunked: !1,
                compress: !1,
                fileNumLimit: 1,
                fileSizeLimit: 4194304,
                fileSingleSizeLimit: 4194304,
                crop: !1,
                preview: !1
            },
            "android" == b.agent() && (defaultOptions.sendAsBinary = !0),
            e = $.extend({},
            defaultOptions, e),
            c && (c = $(c), e.pick = {
                id: c,
                multiple: e.pick.multiple
            }),
            e.multiple && (e.pick.multiple = e.multiple, e.fileNumLimit = 8),
            e.crop && (e.auto = !1, e.pick.multiple = !1, e.preview = !1, f.Uploader.register({
                "before-send-file": "cropImage"
            },
            {
                cropImage: function(a) {
                    if (!a || !a._cropData) return ! 1;
                    var b, c, d = a._cropData;
                    return a = this.request("get-file", a),
                    c = f.Deferred(),
                    b = new f.Lib.Image,
                    c.always(function() {
                        b.destroy(),
                        b = null
                    }),
                    b.once("error", c.reject),
                    b.once("load",
                    function() {
                        b.crop(d.x, d.y, d.width, d.height, d.scale)
                    }),
                    b.once("complete",
                    function() {
                        var d, e;
                        try {
                            d = b.getAsBlob(),
                            e = a.size,
                            a.source = d,
                            a.size = d.size,
                            a.trigger("resize", d.size, e),
                            c.resolve()
                        } catch(f) {
                            c.resolve()
                        }
                    }),
                    a._info && b.info(a._info),
                    a._meta && b.meta(a._meta),
                    b.loadFromBlob(a.source),
                    c.promise()
                }
            })),
            h = f.create(e),
            c.data("uploader", h),
            e.preview && (i = mui.previewImage({
                footer: a.util.templates["image.preview.html"]
            }), $(i.element).find(".js-cancel").click(function() {
                i.close()
            }), $(document).on("click", ".js-submit",
            function(a) {
                var b = $(i.element).find(".mui-slider-group .mui-active").index();
                if (i.groups.__IMG_UPLOAD && i.groups.__IMG_UPLOAD[b] && i.groups.__IMG_UPLOAD[b].el) {
                    var c = "./index.php?i=" + j + "&j=" + k + "&c=utility&a=file&do=delete&type=image",
                    d = $(i.groups.__IMG_UPLOAD[b].el).data("id");
                    $.post(c, {
                        id: d
                    },
                    function(a) {
                        var a = $.parseJSON(a);
                        $(i.groups.__IMG_UPLOAD[b].el).remove(),
                        i.close()
                    })
                }
                return a.stopPropagation(),
                !1
            })),
            h.on("fileQueued",
            function(a) {
                b.loading().show(),
                e.crop && h.makeThumb(a,
                function(b, c) {
                    h.file = a,
                    b || g.preview(c)
                },
                1, 1)
            }),
            h.on("uploadSuccess",
            function(a, c) {
                if (c.error && c.error.message) b.toast(c.error.message, "error");
                else {
                    b.loading().close(),
                    h.reset(),
                    g.reset();
                    var f = $('<img src="' + c.url + '" data-preview-src="" data-preview-group="' + e.preview + '" />');
                    e.preview && i.addImage(f[0]),
                    $.isFunction(d) && d(c)
                }
            }),
            h.onError = function(a) {
                return g.reset(),
                b.loading().close(),
                "Q_EXCEED_SIZE_LIMIT" == a ? void b.toast("错误信息: 图片大于 4M 无法上传.") : "Q_EXCEED_NUM_LIMIT" == a ? void b.toast("单次最多上传8张") : void b.toast("错误信息: " + a)
            },
            g = function() {
                var c, d;
                return {
                    preview: function(f) {
                        return c = $(a.util.templates["avatar.preview.html"]),
                        c.css("height", $(a).height()),
                        $(document.body).prepend(c),
                        d = c.find("img"),
                        d.attr("src", f),
                        d.cropper({
                            aspectRatio: e.aspectRatio ? e.aspectRatio: 1,
                            viewMode: 1,
                            dragMode: "move",
                            autoCropArea: 1,
                            restore: !1,
                            guides: !1,
                            highlight: !1,
                            cropBoxMovable: !1,
                            cropBoxResizable: !1
                        }),
                        c.find(".js-submit").on("click",
                        function() {
                            var a = d.cropper("getData"),
                            b = g.getImageSize().width / h.file._info.width;
                            a.scale = b,
                            h.file._cropData = {
                                x: a.x,
                                y: a.y,
                                width: a.width,
                                height: a.height,
                                scale: a.scale
                            },
                            h.upload()
                        }),
                        c.find(".js-cancel").one("click",
                        function() {
                            c.remove(),
                            h.reset()
                        }),
                        b.loading().close(),
                        this
                    },
                    getImageSize: function() {
                        var a = d.get(0);
                        return {
                            width: a.naturalWidth,
                            height: a.naturalHeight
                        }
                    },
                    reset: function() {
                        return $(".js-avatar-preview").remove(),
                        h.reset(),
                        this
                    }
                }
            } ()
        })
    },
    b.toast = function(a, b, c) {
        if (c && "success" != c) {
            if ("error" == c) var d = mui.toast('<div class="mui-toast-icon"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-tishi"></use></svg></div>' + a)
        } else var d = mui.toast('<div class="mui-toast-icon"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-tishi"></use></svg></div>' + a);
        if (b) var e = 3,
        f = setInterval(function() {
            return 0 >= e ? (clearInterval(f), void(location.href = b)) : void e--
        },
        1e3);
        return d
    },
    b.loading = function(a) {
        var a = a ? a: "show",
        b = {},
        c = $(".js-toast-loading");
        if (c.size() <= 0) var c = $('<div class="mui-toast-container mui-active js-toast-loading"><div class="mui-toast-message"><div class="mui-toast-icon"><span class="weui-loading f30"></span></div>加载中</div></div>');
        return b.show = function() {
            document.body.appendChild(c[0])
        },
        b.close = function() {
            c.remove()
        },
        b.hide = function() {
            c.remove()
        },
        "show" == a ? b.show() : "close" == a && b.close(),
        b
    },
    b.message = function(b, c, d, e) {
        var f = $("<div>" + a.util.templates["message.html"] + "</div>");
        if (f.attr("class", "mui-content fadeInUpBig animated " + mui.className("backdrop")), f.on(mui.EVENT_MOVE, mui.preventDefault), f.css("background-color", "#efeff4"), e && f.find(".mui-desc").html(e), c) {
            var g = c.replace("##auto");
            if (f.find(".mui-btn-success").attr("href", g), c.indexOf("##auto") > -1) var h = 5,
            i = setInterval(function() {
                return 0 >= h ? (clearInterval(i), void(location.href = g)) : (f.find(".mui-btn-success").html(h + "秒后自动跳转"), void h--)
            },
            1e3)
        }
        f.find(".mui-btn-success").click(function() {
            if (c) {
                var a = c.replace("##auto");
                location.href = a
            } else history.go( - 1)
        }),
        d && "success" != d ? (d = "error") && (f.find(".title").html(b), f.find(".mui-message-icon span").attr("class", "mui-msg-error")) : (f.find(".title").html(b), f.find(".mui-message-icon span").attr("class", "mui-msg-success")),
        document.body.appendChild(f[0])
    },
    b.alert = function(a, b, c, d) {
        return mui.alert(a, b, c, d)
    },
    b.confirm = function(a, b, c, d) {
        return mui.confirm(a, b, c, d)
    },
    b.poppicker = function(a, b) {
        require(["mui.datepicker"],
        function() {
            mui.ready(function() {
                var c = new mui.PopPicker({
                    layer: a.layer || 1
                });
                c.setData(a.data),
                c.show(function(a) {
                    $.isFunction(b) && b(a),
                    c.dispose()
                })
            })
        })
    },
    b.districtpicker = function(a) {
        require(["mui.districtpicker"],
        function(c) {
            mui.ready(function() {
                var d = {
                    layer: 3,
                    data: c
                };
                b.poppicker(d, a)
            })
        })
    },
    b.datepicker = function(a, b) {
        require(["mui.datepicker"],
        function() {
            mui.ready(function() {
                var c;
                c = new mui.DtPicker(a),
                c.show(function(a) {
                    $.isFunction(b) && b(a),
                    c.dispose()
                })
            })
        })
    },
    b.querystring = function(a) {
        var b = location.search.match(new RegExp("[?&]" + a + "=([^&]+)", "i"));
        return null == b || b.length < 1 ? "": b[1]
    },
    b.tomedia = function(b, c) {
        if (!b) return "";
        if (0 == b.indexOf("./addons")) return a.sysinfo.siteroot + b.replace("./", ""); - 1 != b.indexOf(a.sysinfo.siteroot) && -1 == b.indexOf("/addons/") && (b = b.substr(b.indexOf("images/"))),
        0 == b.indexOf("./resource") && (b = "app/" + b.substr(2));
        var d = b.toLowerCase();
        return - 1 != d.indexOf("http://") || -1 != d.indexOf("https://") ? b: b = c || !a.sysinfo.attachurl_remote ? a.sysinfo.attachurl_local + b: a.sysinfo.attachurl_remote + b
    },
    b.loading1 = function() {
        var a = "modal-loading",
        b = $("#" + a);
        return 0 == b.length && ($(document.body).append('<div id="' + a + '" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>'), b = $("#" + a), html = '<div class="modal-dialog">	<div style="text-align:center; background-color: transparent;">		<img style="width:48px; height:48px; margin-top:100px;" src="../attachment/images/global/loading.gif" title="正在努力加载...">	</div></div>', b.html(html)),
        b.modal("show"),
        b.next().css("z-index", 999999),
        b
    },
    b.loaded1 = function() {
        var a = "modal-loading",
        b = $("#" + a);
        b.length > 0 && b.modal("hide")
    },
    b.cookie = {
        prefix: "",
        set: function(a, b, c) {
            expires = new Date,
            expires.setTime(expires.getTime() + 1e3 * c),
            document.cookie = this.name(a) + "=" + escape(b) + "; expires=" + expires.toGMTString() + "; path=/"
        },
        get: function(a) {
            for (cookie_name = this.name(a) + "=", cookie_length = document.cookie.length, cookie_begin = 0; cookie_begin < cookie_length;) {
                if (value_begin = cookie_begin + cookie_name.length, document.cookie.substring(cookie_begin, value_begin) == cookie_name) {
                    var b = document.cookie.indexOf(";", value_begin);
                    return - 1 == b && (b = cookie_length),
                    unescape(document.cookie.substring(value_begin, b))
                }
                if (cookie_begin = document.cookie.indexOf(" ", cookie_begin) + 1, 0 == cookie_begin) break
            }
            return null
        },
        del: function(a) {
            new Date;
            document.cookie = this.name(a) + "=; expires=Thu, 01-Jan-70 00:00:01 GMT; path=/"
        },
        name: function(a) {
            return this.prefix + a
        }
    },
    b.agent = function() {
        var a = navigator.userAgent,
        b = a.indexOf("Android") > -1 || a.indexOf("Linux") > -1,
        c = !!a.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
        return b ? "android": c ? "ios": "unknown"
    },
    b.removeHTMLTag = function(a) {
        return "string" == typeof a ? (a = a.replace(/<script[^>]*?>[\s\S]*?<\/script>/g, ""), a = a.replace(/<style[^>]*?>[\s\S]*?<\/style>/g, ""), a = a.replace(/<\/?[^>]*>/g, ""), a = a.replace(/\s+/g, ""), a = a.replace(/&nbsp;/gi, "")) : void 0
    },
    "function" == typeof define && define.amd ? define(function() {
        return b
    }) : a.util = b
} (window),
function(a, b) {
    a["avatar.preview.html"] = '<div class="fadeInDownBig animated js-avatar-preview avatar-preview" style="position:relative; width:100%;z-index:9999"><img src="" alt="" class="cropper-hidden"><div class="bar-action mui-clearfix"><a href="javascript:;" class="mui-pull-left js-cancel">取消</a> <a href="javascript:;" class="mui-pull-right mui-text-right js-submit">选取</a></div></div>',
    a["image.preview.html"] = '<div class="bar-action mui-clearfix"><a href="javascript:;" class="mui-pull-left js-cancel">取消</a> <a href="javascript:;" class="mui-pull-right mui-text-right js-submit">删除</a></div>',
    a["message.html"] = '<div class="mui-content-padded"><div class="mui-message"><div class="mui-message-icon"><span></span></div><h4 class="title"></h4><p class="mui-desc"></p><div class="mui-button-area"><a href="javascript:;" class="mui-btn mui-btn-success mui-btn-block">确定</a></div></div></div>'
} (this.window.util.templates = this.window.util.templates || {});