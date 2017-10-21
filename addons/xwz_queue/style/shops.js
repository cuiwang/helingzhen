$(function() {
    function i() {
        WeixinJSBridge.call("hideOptionMenu")
    }
    if (typeof WeixinJSBridge == "undefined") {
        if (document.addEventListener) {
            document.addEventListener("WeixinJSBridgeReady", i, false)
        } else {
            if (document.attachEvent) {
                document.attachEvent("WeixinJSBridgeReady", i);
                document.attachEvent("onWeixinJSBridgeReady", i)
            }
        }
    } else {
        i()
    }
    var h = "",
    s = "",
    m = 1,
    e = "",
    j = "",
    l = "",
    c = 0;
    reload = false;
    var d = Backbone.Router.extend({
        routes: {
            "": "index",
            index: "index",
            detail: "detail",
            lineset: "lineset"
        },
        index: function() {
            console.log("进入首页");
            reload = true;
            q()
        },
        detail: function() {
            console.log("进入排号详情页");
            reload = false;
            if (j != "") {
                p(j)
            } else {
                window.location.href = "#index"
            }
        },
        lineset: function() {
            console.log("进入排号设置页");
            reload = false;
            n()
        }
    });
    var r = new d;
    Backbone.history.start();
    function q() {
        $.ajax({
            type: "get",
            url: window.moduleurl + "type",
            dataType: "json",
            beforeSend: function() {
                $.showOpenBox.show()
            },
            success: function(u) {
                if (u) {
                    console.log(u);
                    var v = '<p class="con-title">全部排号类型</p>';
                    $(u.data).each(function() {
                        v += '<div class="class-list"><p class="class-list-line"></p><div class="class-list-c box"><div class="class-cont boxflex" data-id="' + this.id + '" data-count="' + this.count + '"><div class="box">';
                        if (this.count == 0) {
                            v += '<div class="class-number">无人排号</div>'
                        } else {
                            v += '<div class="class-number">' + this.type + this.current + "</div>"
                        }
                        v += '<div class="class-info boxflex"><p>' + this.type_name + "</p><p>" + this.count + '人在排队</p></div></div></div><div class="class-more"><a class="shops_classinfo" data-type="' + this.typeid + '" href="#detail">详情</a></div></div></div>'
                    });
                    var t = '<div style="background-color: #f5f5f5;"><div class="admin-top box"><div class="admin-top-t boxflex">排号功能</div><div class="admin-top-s"><a id="shops_set" href="#lineset">设置</a></div></div></div><div class="content">' + v + "</div>";
                    $("#page1").html("").append(t);
                    $(".pt-page").removeClass("current");
                    $(".pt-page").eq(0).addClass("current");
                    $(".shops_classinfo").on("click",
                    function(w) {
                        j = $(this).data("type");
                        k(0, 1)
                    });
                    $("#shops_set").click(function() {
                        k(0, 2)
                    });
                    $(".class-cont").on("click",
                    function(w) {
                        h = $(this).data("id");
                    
                        console.log(h);
                        s = $(this).find(".class-number").html();
                        l = "page1";
                        c = $(this).data("count");
                        if (c != 0) {
                            $(".windowshow-linenum").html(s);
                            f($("#set-linebox"))
                        } else {
                            g("当前没有排队用户")
                        }
                    });
                    setInterval(function() {
                        if (reload) {
                            window.location.reload()
                        }
                    },
                    30000)
                }
            },
            complete: function() {
                $.showOpenBox.close()
            }
        })
    }
    function p(t) {
        $.ajax({
            type: "get",
            url: window.moduleurl + "lineups",
            dataType: "json",
            data: {
                type: t
            },
            beforeSend: function() {
                $.showOpenBox.show()
            },
            success: function(v) {
                console.log("lineups 加载成功");
                if (v) {
                    var w = "";
                    if (v._count != 0) {
                        w += '<p class="class-list-line"></p><div class="class-list"><div class="content-c">';
                        $(v.list).each(function() {
                            w += '<p data-id="' + this.id + '" class="class-item box"><span class="class-item-num">' + this.type + this.number + '</span><span class="class-item-time boxflex">已等待' + this.is_waiting + "</span></p>"
                        });
                        w += "</div></div>"
                    }
                    var u = '<div style="background-color: #f5f5f5;"><div class="admin-top"><div class="admin-top-t">' + v.type_name + ",共" + v.member_count + '人在排队...</div></div></div><div class="content">' + w + "</div>";
                    $("#page2").html("").append(u);
                    $(".class-item").on("click",
                    function(x) {
                        h = $(this).data("id");
                        
                        s = $(this).find(".class-item-num").html();
                        l = "page2";
                        $(".windowshow-linenum").html(s);
                        f($("#set-linebox"))
                    })
                }
            },
            complete: function() {
                $.showOpenBox.close()
            }
        })
    }
    function n() {
        $.ajax({
            type: "get",
            url: window.moduleurl + "setting",
            dataType: "json",
            beforeSend: function() {
                $.showOpenBox.show()
            },
            success: function(t) {
                console.log("lineup 加载成功");
                $(".loading").remove();
                if (t.ret == 7002) {
                    $("#lineup-set").html("已暂停");
                    m = 2
                }
                if (t.ret == 0) {
                    e = t.data.id;
                    if (t.data.status == 1) {
                        $("#lineup-set").html("已开启");
                        m = 1
                    }
                    if (t.data.status == 2) {
                        $("#lineup-set").html("已暂停");
                        m = 2
                    }
                }
                k(0, 2)
            },
            complete: function() {
                $.showOpenBox.close()
            }
        })
    }
    $("#lineup-set").click(function(t) {
        console.log(m);
        if (m == 1) {
            f($("#close-linebox"))
        } else {
            f($("#open-linebox"))
        }
    });
    $("#lineup-reset").click(function(t) {
        f($("#reset-linebox"))
    });
    $("#set-lineok").click(function(t) {
        $.ajax({
            type: "get",
            url: window.moduleurl + "used",
            dataType: "json",
            data: {
                id: h
            },
            beforeSend: function() {
                $.showOpenBox.show()
            },
            success: function(u) {
                if (u.status == true) {
                    g("使用成功");
                    if (l == "page1") {
                        q()
                    } else {
                        p(j)
                    }
                    o()
                } else {
                    alert(u.msg);
                    window.location.reload()
                }
            },
            error: function(u) {
                console.log(u)
            },
            complete: function() {
                $.showOpenBox.close()
            }
        })
    });
    $("#set-lineover").click(function(t) {
        $.ajax({
            type: "get",
            url: window.moduleurl + "faild",
            dataType: "json",
            data: {
                id: h
            },
            beforeSend: function() {
                $.showOpenBox.show()
            },
            success: function(u) {
                if (u.status == true) {
                    g("操作成功");
                    if (l == "page1") {
                        q()
                    } else {
                        p(j)
                    }
                    o()
                } else {
                    alert(u.msg);
                    window.location.reload()
                }
            },
            complete: function() {
                $.showOpenBox.close()
            }
        })
    });
    $("#set-lineopen,#set-lineclose").click(function(u) {
        var t = m == 1 ? 2 : 1;
        $.ajax({
            type: "get",
            url: window.moduleurl + "switch",
            dataType: "json",
            data: {
                status: t
            },
            beforeSend: function() {
                $.showOpenBox.show()
            },
            success: function(v) {
                if (v.status == true) {
                    g("操作成功");
                    if (t == 1) {
                        $("#lineup-set").html("已开启");
                        m = 1
                    } else {
                        $("#lineup-set").html("已暂停");
                        m = 2
                    }
                    o()
                } else {
                    alert(v.msg);
                    window.location.reload()
                }
            },
            complete: function() {
                $.showOpenBox.close()
            }
        })
    });
    $("#set-linereset").click(function(t) {
        $.ajax({
            type: "get",
            url: window.moduleurl + "reset",
            dataType: "json",
            beforeSend: function() {
                $.showOpenBox.show()
            },
            success: function(u) {
                if (u.status == true) {
                    g("操作成功");
                    setTimeout(function() {
                        $(".getok-linebox").hide()
                    },
                    1000);
                    o()
                } else {
                    alert(u.msg);
                    window.location.reload()
                }
            },
            complete: function() {
                $.showOpenBox.close()
            }
        })
    });
    $(".windowshow-bg,.windowshow-cancel").click(function() {
        o()
    });
    function b(t) {
        $(".class-list .class-item").each(function(u, v) {
            var w = $(this).data("id");
            if (t == w) {
                $(this).remove()
            }
        })
    }
    function g(u) {
        $(".getok-linebox").html(u).show();
        var t = $(".getok-linebox").width();
        $(".getok-linebox").css("margin-left", -t / 2);
        setTimeout(function() {
            $(".getok-linebox").hide()
        },
        1000)
    }
    function f(t) {
        $(".windowshow-bg").css("display", "block");
        $(".windowshow-box").removeClass("moveToTop moveToBottom");
        t.addClass("moveToTop")
    }
    function o() {
        $(".windowshow-box").removeClass("moveToTop").addClass("moveToBottom");
        $(".windowshow-bg").css("display", "none")
    }
    function k(u, t) {
        $(".page-main .pt-page").eq(u).addClass("pt-page-fade");
        $(".page-main .pt-page").eq(t).addClass("current pt-page-moveFromRight outop");
        setTimeout(function() {
            $(".page-main .pt-page").eq(u).removeClass("current pt-page-fade");
            $(".page-main .pt-page").eq(t).removeClass("pt-page-moveFromRight outop")
        },
        700)
    }
    function a(u, t) {
        $(".page-main .pt-page").eq(u).addClass("pt-page-fade");
        $(".page-main .pt-page").eq(t).addClass("current pt-page-moveFromLeft outop");
        setTimeout(function() {
            $(".page-main .pt-page").eq(u).removeClass("current pt-page-fade");
            $(".page-main .pt-page").eq(t).removeClass("pt-page-moveFromLeft outop")
        },
        700)
    }
});