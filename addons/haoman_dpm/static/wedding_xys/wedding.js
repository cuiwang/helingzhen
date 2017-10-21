(function (a) {
    if (a.xys) {
        return
    }
    var b = a.xys = {
        _allPoints: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"],
        _fixInitPoints: [],
        _fixPoints: [],
        _msgList: [],
        _msgPlayedList: [],
        _msgPlayedNum: 0,
        _msgPreList: [],
        _isPlaying: false,
        _pullMsgTimer: null,
        _pullMsgTime: 500,
        _isNewMsgPlaying: false,
        _nesMsgPlayState: "notPlay",
        _avaChangeTime: 500,
        _msgTotal: 0,
        _textRollSpeed: 90,
        _pullAvaScroll: null,
        _pullAvaScrollTime: 1000,
        _keyMap: null
    };

    b.bindEvent = function () {

        // if (!a.APP) {
        //     return
        // }
        //
        // APP.on("show", function () {
        //     b.playPull()
        // });
        // APP.on("hide", function () {
        //     b.playPullStop()
        // });
        b._keyMap = keyWord;

        b.initChangeClass();

                setInterval(function() {
                    n()
                    b.play()
                },3000)

      //  n();

        // APP.on("message.newMsgReceived", function (c, d) {
        //     if (!b._msgList) {
        //         b._msgList = []
        //     }
        //     if (isTestTime && b._msgPlayedNum > msglimitNum) {
        //         return
        //     } else {
        //         b._msgList = b._msgList.concat(d)
        //     }
        // })
    };
    var last_id = 0;
    function n() {
        $.getJSON(dpm_dm_getmessages, {
                last_id: last_id
            },
            function(C) {
                if (C && C.ret == 1 && $.isArray(C.data)) {
                    if (C.data.length > 0) {
                        b._msgList = C.data.concat(b._msgList);
                        last_id = C.data[0].id;
                    }
                }
            })
    }
    b.playPull = function () {
        var c = this;
        this.playPullStop();
        c._pullMsgTimer = setTimeout(function () {
            c.play()
        }, c._pullMsgTime)
    };
    b.playPullStop = function () {
        clearTimeout(this._pullMsgTimer);
        this._pullTimer = null;
        return this
    };
    b.offWall = function (e) {
        var c = e.length;
        for (var d = 0; d < c; d++) {
            if ($(".jsAvatarList li[data-id='" + e[d] + "']")) {
                $(".jsAvatarList li[data-id='" + e[d] + "']").remove()
            }
        }
    };
    b.play = function () {
        var f = this;

        var g = $(".jsAvatarList");
        if (!f._msgList.length) {
            f.avaScroll();
            f.playPull();
            return
        }
        if (isTestTime && b._msgPlayedNum > msglimitNum) {
            f.playPullStop();
            return
        }
        if (f._isPlaying == true) {
            f.playPull();
            f._nesMsgPlayState = "prePlay";
            return
        }
        f._nesMsgPlayState = "playing";
        var e = f._msgList.splice(0, 1);
        var c = f.getRenderHtml(e[0]);
        g.append(c);
        b.regKeyWord(e[0].word);
        b._msgPlayedNum += 1;
        var d = g.find(".ava-new:last");
        f.textRoll(d, function () {
            setTimeout(function () {
                f.findPosition(d, function () {
                    inner
                })
            }, 2000)
        })
    };
    b.getRenderHtml = function (d) {
        var c = '<li class="ava-new" data-id="' + d.id + '"><div class="con clearfix"><div class="box left"><div class="box-inner"><div class="box1"><img src="' + d.avatar + '"></div><div class="box2"><img src="' + d.avatar + '"></div><div class="box3"><img src="' + d.avatar + '"></div></div></div><div class="txt left"><em></em><div class="inner jsInner"><div><h4 class="jsMsText">' + d.word + "</h4></div></div></div></div></li>";
        return c
    };
    b.initChangeClass = function () {
        if ($(".jsAvatarList li").length > 0) {

            $(".ava-new").each(function (d, c) {
                d++;
                b._fixInitPoints.push(d);
                b._allPoints.splice(d, 1);
                $(this).removeClass("ava-new").addClass("ava" + d).addClass("js_perAva")
            })
        }
    };
    b.findPosition = function (f, c) {
        var e = this;
        var d = 0;
        if (e._allPoints.length > 0) {
            d = e.rand(1, e._allPoints.length - 1)
        } else {
            d = e.rand(1, e._fixInitPoints.length - 1)
        }
        if (e._fixInitPoints.indexOf(d) == -1) {
            e._fixInitPoints.push(d)
        }
        if ($(".ava" + d).length > 0) {
            $(".ava" + d).remove()
        }
        f.removeClass("ava-new").addClass("ava" + d);
       // c()
    };
    b.avaScroll = function () {
        var f = this;
        if (f._isPlaying == true || f._isNewMsgPlaying == true) {
            return
        }
        if (f._fixInitPoints.length < 1) {

            return
        }
        f._isPlaying = true;
        var c = f.rand(0, f._fixInitPoints.length - 1), g = f._fixInitPoints[c], e = $(".ava" + g), d = e.find(".jsMsText").html();
        e.addClass("on");
        f.textRoll(e, function () {
            setTimeout(function () {
                e.removeClass("on");
                if (Modernizr.csstransitions) {
                    e.find(".jsMsText").css({x: 0})
                } else {
                    e.find(".jsMsText").css({marginLeft: 0 + "px"})
                }
                f._isPlaying = false
            }, 2000);
            setTimeout(function () {
                if (f._isPlaying == false && f._nesMsgPlayState == "notPlay") {
                    f.avaScroll()
                }
            }, 1000)
        })
    };
    b.textRoll = function (h, g) {
        var d = h.find(".jsMsText");
        var e = d.text();
         var k = b.fontNum(e);
        var c = h.find(".jsInner").width();
        var j = parseInt(d.css("fontSize"));

// var k =1;
        if (k * j <= c) {
            setTimeout(function () {
                g()
            }, 2000);
            return
        }
        if (Modernizr.csstransitions) {
            d.css({x: 120})
        } else {
            d.css({marginLeft: "120px"})
        }
        var f = Math.abs(k * j - c);
        f += 120;
        var i = Math.ceil(f / (j * 2));
        if (Modernizr.csstransitions) {
            d.transition({
                x: -f, duration: i * 1000, easing: "linear", complete: function () {
                    g()
                }
            })
        } else {
            d.animate({marginLeft: -f + "px"}, i * 1000, "linear", function () {
                g()
            })
        }
    };
    b.fontNum = function (a) {
        if (!a || !a.length)return 0;
        var b = 0, c = /[\u4E00-\u9FA5\uF900-\uFA2D]/g, d = /[\uFF00-\uFFEF]|[\u3000-\u303F]|[\u2E80-\u2EFF]|[\u31C0-\u31EF]|[\u2F00-\u2FDF]|[\u2FF0-\u2FFF]|[\u3100-\u312F]|[\u31A0-\u31BF]|[\uFE10-\uFE1F]|[\uFE30-\uFE4F]/g, e = /[A-Za-z]/g, f = 1.1, g = a.match(c);
        return g && (b += g.length, a = a.replace(c, "")), g = a.match(d), g && (b += g.length, a = a.replace(d, "")), g = a.match(e), g && (b += g.length * f / 2, a = a.replace(e, "")), b += a.length * f / 2, Math.ceil(b)
    }

    b.regKeyWord = function (c) {
        c = c || "";
        c="我爱你们"
        b._keyMap.forEach(function (g, d) {
            var f = g.key_word.split(",");

            for (var e = 0; e < f.length; e++) {
                if (c.search(f[e]) != -1) {

                    b.effect(g.effect);
                    break
                }
            }
        })
    };
    b.effect = function (e) {
        e = e || 1;
        e = parseInt(b.rand(1, 4));
        $(".js_effectBox").html("");
        if (1 == e) {
            for (var f = 0; f < 60; f++) {
                var d = $('<span class="lover"><img src="../addons/haoman_dpm/static/wedding_xys/lover/heart'+ parseInt(b.rand(1, 3)) + '.png"/></span>');
                $(".js_effectBox").prepend(d);
                var c = b.rand(0.3, 1.1);
                d.css({
                    background: "url('../addons/haoman_dpm/static/wedding_xys/lover/lover-shadow'" + parseInt(b.rand(1, 2)) + "'.png') center center no-repeat",
                    left: 80 * parseInt(b.rand(1, 18)) + "px",
                    top: 70 * parseInt(b.rand(1, 10)) + "px",
                    "-webkit-transform": "scale(" + c + "," + c + ")",
                    "-webkit-animation": "lover 1s " + 30 * f + "ms 1",
                    animation: "lover 1s " + 30 * f + "ms 1"
                })
            }
        } else {
            if (2 == e) {
                for (var f = 0; f < 15; f++) {
                    var j = $('<img src="../addons/haoman_dpm/static/wedding_xys/gift/gift' + parseInt(b.rand(1, 6)) + '.png" class="gift"/>');
                    j.css({
                        left: 80 * b.rand(1, 18) + "px",
                        "-webkit-animation": "gift 2s " + 100 * f + "ms 1",
                        animation: "gift 2s " + 100 * f + "ms 1"
                    });
                    $(".js_effectBox").prepend(j)
                }
            } else {
                if (3 == e) {
                    for (var f = 0; f < 15; f++) {
                        var h = $('<span class="rose"></span>');
                        h.css({
                            left: 80 * b.rand(1, 18) + "px",
                            "-webkit-transform": "rotateZ(" + 30 * f + "deg)",
                            "-webkit-animation": "rose 2s " + 100 * f + "ms 1",
                            animation: "rose 2s " + 100 * f + "ms 1"
                        });
                        $(".js_effectBox").prepend(h)
                    }
                } else {
                    if (4 == e) {
                        var g = $('<span class="cake1 cake"></span>');
                        $(".js_effectBox").prepend(g);
                        $(".cake1").css({"-webkit-animation": "cake 2s 1", animation: "cake 2s 1"})
                    } else {
                        for (var f = 0; f < 50; f++) {
                            var k = $('<span class="star"></span>');
                            var c = b.rand(0.3, 1);
                            k.css({
                                background: "url('../addons/haoman_dpm/static/wedding_xys/star/star" + parseInt(b.rand(1, 6)) + ".png') no-repeat",
                                left: 80 * parseInt(b.rand(1, 18)) + "px",
                                top: 70 * parseInt(b.rand(1, 10)) + "px",
                                "-webkit-animation": "star 1s " + 30 * f + "ms 2",
                                animation: "star 1s " + 30 * f + "ms 2"
                            });
                            $(".js_effectBox").prepend(k)
                        }
                    }
                }
            }
        }
    };
    b.rand = function (d, c) {
        return Math.floor(Math.random() * (c - d + 1)) + d
    }
})(window);
xys.bindEvent();
