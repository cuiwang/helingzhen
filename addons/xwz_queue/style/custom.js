$(function() {
    function o() {
        WeixinJSBridge.call("hideOptionMenu")
    }
    if (typeof WeixinJSBridge == "undefined") {
        if (document.addEventListener) {
            document.addEventListener("WeixinJSBridgeReady", o, false)
        } else {
            if (document.attachEvent) {
                document.attachEvent("WeixinJSBridgeReady", o);
                document.attachEvent("onWeixinJSBridgeReady", o)
            }
        }
    } else {
        o()
    }
    var g = "",
    l = "",
    h = "",
    j = "",
    d = "",
    c = false;
    $.ajax({
        type: "get",
        url: window.moduleurl + "number",
        dataType: "json",
        beforeSend: function() {
            $.showOpenBox.show()
        },
        success: function(r) {
            if (r) {
                g = r.shop_name;
                l = r.tel;
                if (r.ret != 7002) {
                    h = r.config.intro == undefined ? "": r.config.intro;
                    j = r.config.num == undefined ? 0 : r.config.num
                }
                switch (r.ret) {
                case 0:
                    a(1);
                    c = true;
                    b(r);
                    break;
                case 403:
                    q(r.ret);
                    break;
                case 7001:
                    q(r.ret);
                    break;
                case 7002:
                    q(r.ret);
                    break;
                case 7003:
                    q(r.ret);
                    break;
                case 7101:
                    c = false;
                    i();
                    break;
                case 7105:
                    i();
                    break
                }
            }
        },
        complete: function() {
            $.showOpenBox.close()
        }
    });
    function i() {
        $.ajax({
            type: "get",
            url:  window.moduleurl + "type",
            dataType: "json",
            beforeSend: function() {
                $.showOpenBox.show()
            },
            success: function(s) {
                if (s.ret != 7003) {
                    var t = "";
                    $(s.data).each(function(u, v) {
                        t += '<li class="class-item"><em class="icon"></em><div class="class-item-c clearfix"><span class="class-info fl">' + this.type_name + '</span><span class="class-num fr">' + this.count + '位</span></div><a data-type="' + this.typeid + '" class="class-item-but" href="javascript:;">取号</a></li>'
                    });
                    var r = '<div class="header"><p class="header-name">' + g + '</p><p class="header-info">' + h + '</p></div><div class="content"><p class="con-title">选择排号类型</p><p class="class-list-line"></p><div class="class-list"><ul>' + t + "</ul></div></div>";
                    $("#page1").html("").append(r);
                    $(".class-item-but").on("click",
                    function(v) {
                        var u = $(this).data("type");
                        $.ajax({
                            type: "get",
                            url: window.moduleurl + "lineup",
                            dataType: "json",
                            data: {
                                type: u
                            },
                            beforeSend: function() {
                                $.showOpenBox.show()
                            },
                            success: function(w) {
                       
                                k("取号成功");
                                c = true;
                                b(w)
                            },
                            complete: function() {
                                $.showOpenBox.close()
                            }
                        })
                    });
                    $(".class-item").click(function(u) {
                        $(".class-item").removeClass("selected");
                        $(this).addClass("selected")
                    });
                    $(".pt-page").removeClass("current");
                    $(".pt-page").eq(0).addClass("current")
                } else {
                    q(s.ret)
                }
            },
            complete: function() {
                $.showOpenBox.close()
            }
        })
    }
    function b(u) {
        d = u.data.typeid;
        var t = "",
        r = "",
        v = "",
        s = "";

        if (u.data.status == 0) {
            if (u.data.before_count == 0) {
                r = "恭喜您，轮到您的号码了。"
            } else {
                r = "您前面还有" + u.data.before_count + "位在排队..."
            }
            v = '<a herf="javascript:;" id="back-line" class="alink">放弃排号</a>'
        }
        if (u.data.status == 1) {
            r = "号码已经使用，请重新排号.";
            v = '<a herf="javascript:;" id="reset-line" class="alink">重新排号</a>'
        }
        if (u.data.status == 2) {
            if (u.data.after_count > j) {
                r = "您已过号" + j + "位，号码失效";
                v = '<a herf="javascript:;" id="reset-line" class="alink">重新排号</a>'
            } else {
                r = "您已过号" + u.data.after_count + "位...";
                v = '<a herf="javascript:;" id="back-line" class="alink">放弃排号</a>'
            }
        }
        if (j != 0) {
            s += "<p>● 过号不作废，过号" + j + "位内还可使用</p>"
        }
        if (h != "") {
            s += "<p>● " + h + "</p>"
        }
        t = '<div class="header"><p class="header-name">' + g + '</p></div><div class="number"><p class="number-nub">' + u.data.type + u.data.number + '</p><p class="number-class">' + u.data.type_name + '</p><p id="number-before" class="number-before">' + r + '</p><p><div class="bottom-but">' + v + '</div></div><p class="class-list-line"></p><div class="class-list"><div class="number-con"><p class="number-info first clearfix"><span class="fl">取号时间</span><span class="fr">' + u.data.dt_add + '</span></p><p class="number-info clearfix"><span class="fl">已等待</span><span id="wait-time" class="fr">' + u.data.waiting_time + '分钟</span></p><p class="number-info end clearfix"><span class="fl">商家电话</span><span class="fr"><a href="tel:' + l + '">' + l + '</a></span></p></div></div><div class="shop-info">' + s + '</div>';
        $("#page2").html("").append(t);
        $("#page2").scrollTop(0, 0);
        m(0);
        setInterval(function() {
            if (c) {
                f()
            }
        },
        30000);
        $("#back-line").click(function(w) {
            n($("#back-linebox"))
        });
        $("#reset-line").click(function(w) {
            p(1);
            i()
        })
    }
    function q(s) {
        var t = "";
        switch (s) {
        case 403:
            t = "抱歉，您已被商家列入黑名单！";
            break;
        case 7001:
            t = "参数错误！";
            break;
        case 7002:
            t = "抱歉，排号功能暂未开启！";
            break;
        case 7003:
            t = "抱歉，排号未开始！";
            break;
        case 7105:
            t = "抱歉，您号码已失效，请重新扫码！";
            break
        }
        var r = '<div class="header">                        <p class="header-name">' + g + '</p>                    </div>                    <div class="status">                        <p class="status-info">' + t + '</p>                        <p class="shop-tel"><a href="tel:' + l + '">' + l + "</a></p>                    </div>";
        $("#page3").html("").append(r);
        m(1)
    }
    $("#backbut-line").click(function() {
        $.ajax({
            type: "get",
            url: window.moduleurl + "giveup",
            data: {
                type: d
            },
            beforeSend: function() {
                $.showOpenBox.show()
            },
            success: function(r) {
                var r = $.parseJSON(r);
                if (r.ret == 0) {
                    c = false;
                    e();
                    p(1);
                    i()
                }
            },
            complete: function() {
                $.showOpenBox.close()
            }
        })
    });
    $(".windowshow-bg,.windowshow-cancel").click(function(r) {
        e()
    });
    function f() {
        $.ajax({
            type: "get",
            url: window.moduleurl + "number",
            dataType: "json",
            success: function(r) {
                if (r) {
                    $("#wait-time").html(r.data.waiting_time + "分钟");
                    if (r.data.status == 0) {
                        if (r.data.before_count == 0) {
                            $("#number-before").html("恭喜您，轮到您的号码了。")
                        } else {
                            $("#number-before").html("您前面还有" + r.data.before_count + "位在排队...")
                        }
                        $(".bottom-but").html("").append('<a herf="javascript:;" id="back-line" class="alink">放弃排号</a>')
                    }
                    if (r.data.status == 1) {
                        $("#number-before").html("号码已经使用，请重新排号.");
                        $(".bottom-but").html("").append('<a herf="javascript:;" id="reset-line" class="alink">重新排号</a>')
                    }
                    if (r.data.status == 2) {
                        if (r.data.after_count > j || r.data.after_count == j) {
                            $("#number-before").html("您已过号" + j + "位，号码失效");
                            $(".bottom-but").html("").append('<a herf="javascript:;" id="reset-line" class="alink">重新排号</a>')
                        } else {
                            $("#number-before").html("您已过号" + r.data.after_count + "位...");
                            $(".bottom-but").html("").append('<a herf="javascript:;" id="back-line" class="alink">放弃排号</a>')
                        }
                    }
                    $("#back-line").click(function(s) {
                        n($("#back-linebox"))
                    });
                    $("#reset-line").click(function(s) {
                        p(1);
                        i()
                    })
                }
            }
        })
    }
    function a(r) {
        $(".pt-page").removeClass("current");
        $(".pt-page").eq(r).addClass("current")
    }
    function n(r) {
        $(".windowshow-bg").css("display", "block");
        $(".windowshow-box").removeClass("moveToTop moveToBottom");
        r.addClass("moveToTop")
    }
    function e() {
        $(".windowshow-box").removeClass("moveToTop").addClass("moveToBottom");
        $(".windowshow-bg").hide()
    }
    function m(r) {
        $(".page-main .pt-page").eq(r).addClass("pt-page-fade");
        $(".page-main .pt-page").eq(r + 1).addClass("current pt-page-moveFromRight outop");
        setTimeout(function() {
            $(".page-main .pt-page").eq(r).removeClass("current pt-page-fade");
            $(".page-main .pt-page").eq(r + 1).removeClass("pt-page-moveFromRight outop")
        },
        700)
    }
    function p(r) {
        $(".page-main .pt-page").eq(r).addClass("pt-page-fade");
        $(".page-main .pt-page").eq(r - 1).addClass("current pt-page-moveFromLeft outop");
        setTimeout(function() {
            $(".page-main .pt-page").eq(r).removeClass("current pt-page-fade");
            $(".page-main .pt-page").eq(r - 1).removeClass("pt-page-moveFromLeft outop")
        },
        700)
    }
    function k(s) {
        $(".getok-linebox").html(s).show();
        var r = $(".getok-linebox").width();
        $(".getok-linebox").css("margin-left", -r / 2);
        setTimeout(function() {
            $(".getok-linebox").hide()
        },
        800)
    }
});