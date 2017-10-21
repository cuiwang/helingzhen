(function(b) {
    var a = {
        init: function() {
            var c = this;
            this.content = b(".content");
            this.scrollWrapper = b(".scroll-wrap");
            this.cow = b(".cow");
            this.cowSwitch = b(".cow .switch");
            this.logo = b(".logo");
            this.timeWrap = b(".timecount");
            this.countWrap = b(".countdownnum");
            this.titles = b(".titles");
            this.ruleBtn = b(".rulebtn");
            this.guiderules = b(".guide-rules");
            this.startBtn = b(".start");
            this.tryagain = b(".tryagain");
            this.invite = b(".invite");
            this.score = b(".score");
            this.resultnum = b(".result-num");
            this.sure = b(".sure");
            this.phone = b(".phone-num");
            this.share = b(".share");
            this.startBlow = b(".start-blow");
            this.bottomBlow = b(".bottom-blow");
            this.userList = b(".userlist tbody");
            this.dialog = b(".dialog");
            this.loadingbg = b(".loading-bg");
            this.audio = document.getElementById("bgSound");
            this.ratio = b(".ratio");
            this.importantImg = [
            	"http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/images/logo.png", 
            	"http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/images/yun.png", 
            	"http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/images/bottom.png", 
            	"http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/images/countbg.png", 
            	"http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/images/cow.png", 
            	"http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/images/start-blow-left.png",
				"http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/images/start-blow-right.png", 
            	"http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/images/blow-down-left.png", 
            	"http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/images/blow-down-right.png", 
            	"http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/images/gewala.png"
            ];
            this.animationControl(true);
            this.scrollResetTimer = null;
            this.gameCountTimer = null;
            this.scrollValue = 0;
            this.content.y = 0;
            this.high = 0;
            this.timeCount = 30;
            this.imgLoadTimer = null;
            this.isStartDrag = false;
            this.isGameOver = false;
            this.isSend = false;
            this.token = null;
            this.top = [];
            this.scrollStartValue = Math.max(document.documentElement.clientHeight, window.innerHeight || 0) - 380;
            this.loading();
            this.listenEvents();
            this.getTop()
        },
        loading: function() {
            var e = this,
            c = 0,
            d = this.importantImg.length;
            this.imgLoadTimer = setInterval(function() {
                if (c === d) {
                    clearInterval(e.imgLoadTimer);
                    e.loadingbg.hide()
                } else {
                    e.importantImg.forEach(function(h, g) {
                        var f = new Image();
                        f.src = h;
                        if (f.complete) {
                            e.importantImg.splice(g, 1);
                            c++;
                            e.ratio.html(c + "/" + 10)
                        }
                    })
                }
            },
            50)
        },
        animationControl: function(c) {
            if (c) {
                this.startBlow.addClass("start-blow-animation");
                this.bottomBlow.addClass("bottom-blow-animation");
                this.cow.css({
                    "-webkit-animation-play-state": "running",
                    "animation-play-state": "running"
                });
                b(".switch").css({
                    "-webkit-animation-play-state": "running",
                    "animation-play-state": "running"
                })
            } else {
                this.startBlow.removeClass("start-blow-animation");
                this.bottomBlow.removeClass("bottom-blow-animation");
                this.cow.css({
                    "-webkit-animation-play-state": "paused",
                    "animation-play-state": "paused"
                });
                b(".switch").css({
                    "-webkit-animation-play-state": "paused",
                    "animation-play-state": "paused"
                })
            }
        },
        listenEvents: function() {
            var g = this,
            f = [],
            e = [],
            d = 0,
            c;
            this.ruleBtn.on("touchend.rule mouseup.rule",
            function(h) {
                h.preventDefault();
                h.stopPropagation();
                b(this).hide();
                g.rulePanel = g.bgLayer(b(".guide-rules"), "over-layer")
            });
            this.startBtn.on("touchend.start mouseup.start",
            function(h) {
                h.preventDefault();
                h.stopPropagation();
                g.audio.play();
                g.audio.pause();
                g.ruleBtn.hide();
                g.titles.hide();
                g.logo.hide();
                g.isStartDrag = true;
                g.tipPanel = g.bgLayer(b(".start-tips"), "start-layer");
                g.animateNumber(g.countWrap, 3);
                b.ajax({
                    url: "http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/start",
                    type: "get"
                })
            });
            this.content.on("touchmove.drag mousermove.drag",
            function(h) {
                h.preventDefault();
                if (!g.isStartDrag || g.isGameOver) {
                    return
                }
                clearInterval(g.scrollResetTimer);
                if (!g.cow.hasClass("highCow")) {
                    g.cow.removeClass("dropCow").addClass("highCow")
                }
                h.originalEvent.touches = h.originalEvent.touches ? h.originalEvent.touches: [h.originalEvent];
                if (h.originalEvent.touches[0]) {
                    e[0] = {
                        x: h.originalEvent.touches[0].pageX,
                        y: h.originalEvent.touches[0].pageY
                    };
                    if (e[0].y < 30) {
                        g.drop()
                    }
                    if (f[0] == null) {
                        f[0] = e[0]
                    }
                    d = e[0].y - f[0].y;
                    if (Math.abs(e[0].x - f[0].x) > 40) {
                        d = 0
                    }
                    g.content.y += g.rangeLimit(g.content.y, d);
                    f[0] = e[0]
                } else {
                    e[0] = {
                        x: null,
                        y: null
                    }
                }
                g.scrollAnimation()
            });
            this.content.on("touchend.drag mouseup.drag",
            function(h) {
                h.preventDefault();
                h.stopPropagation();
                g.audio.play();
                if (!g.isStartDrag) {
                    return
                }
                clearInterval(g.scrollResetTimer);
                g.dragList = [];
                h.originalEvent.touches = h.originalEvent.touches ? h.originalEvent.touches: [h.originalEvent];
                if (h.originalEvent.touches.length === 0 || h.type === "mouseup" && g.isStartDrag) {
                    g.drop()
                }
            });
            this.tryagain.on("touchend mouseup",
            function(h) {
                h.preventDefault();
                h.stopPropagation();
                location.reload()
            });
            this.invite.on("touchend mouseup",
            function(h) {
                h.preventDefault();
                h.stopPropagation();
                g.resultPanel.clear();
                g.score.hide();
                g.timeWrap.hide();
                g.share.show()
            });
            this.sure.on("touchend mouseup",
            function(i) {
                var j = g.phone.val(),
                h;
                if (!j) {
                    g.dialogPanel = g.dialogShow("手机号码能为空");
                    return
                }
                h = !!j.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/);
                if (j && !h) {
                    g.dialogPanel = g.dialogShow("手机号码不合法");
                    return
                }
                if (!g.isSend) {
                    if (c) {
                        c.abort()
                    }
                    c = b.ajax({
                        url: "/players",
                        type: "post",
                        data: {
                            authenticity_token: g.token,
                            player: {
                                name: "",
                                phone_number: j,
                                score: g.high
                            }
                        }
                    }).done(function(k) {
                        if (k.result == "success") {
                            g.isSend = true;
                            g.dialogPanel = g.dialogShow(k.msg)
                        }
                    })
                }
            });
            this.guiderules.on("touchend mouseup",
            function(h) {
                h.preventDefault();
                h.stopPropagation();
                g.rulePanel.clear();
                g.ruleBtn.show()
            });
            this.dialog.find(".confirm").on("touchend mouseup",
            function(h) {
                h.preventDefault();
                h.stopPropagation();
                g.dialogPanel.clear()
            })
        },
        rangeLimit: function(d, c) {
            d = d > 20 ? 20 : (d < -1800 ? -1800 : d);
            c *= 0.3;
            return d < 0 ? c * b.easing.easeOutSine(0, d, 1, -0.9, -1800) : c * b.easing.easeOutSine(0, d, 1, -1, 100)
        },
        scrollAnimation: function() {
            var c = this;
            this.cow.css("transform", "translate3d(0," + this.content.y + "px,0)");
            this.cow.css("transition", "none");
            this.scrollValue = -this.content.y - this.scrollStartValue;
            if (this.content.y < -this.scrollStartValue) {
                this.scrollWrapper.css({
                    transform: "translate3d(0," + this.scrollValue + "px,0)",
                    transition: "none"
                })
            } else {
                this.scrollWrapper.css({
                    transform: "translate3d(0,0,0)",
                    transition: "none"
                })
            }
            this.updateScore()
        },
        updateScore: function() {
            var c = this.content.y;
            c = parseInt( - c / 3);
            this.score.html(c + "m");
            this.high = c > this.high ? c: this.high
        },
        drop: function() {
            var i = this.content.y,
            d = 0,
            h = 100,
            f = 0,
            g = this;
            if (i != 0) {
                this.scrollResetTimer = setInterval(function() {
                    if (d > h) {
                        clearInterval(g.scrollResetTimer);
                        g.content.y = 0;
                        g.content.css("transform", "translate3d(0,0,0)");
                        if (g.isGameOver) {
                            g.score.hide();
                            g.timeWrap.hide();
                            g.resultnum.html(g.high)
                        }
                    } else {
                        g.content.y = b.easing.easeOutBounce(0, d, i, f - i, h);
                        d++;
                        g.scrollAnimation()
                    }
                    if (!g.cow.hasClass("dropCow")) {
                        g.cow.removeClass("highCow").addClass("dropCow")
                    }
                },
                16)
            } else {
                if (g.isGameOver) {
                    g.score.hide();
                    g.timeWrap.hide();
                    g.resultnum.html(g.high)
                }
            }
        },
        animateNumber: function(d, c) {
            var e, f = this;
            d.parent().show();
            e = setInterval(function() {
                if (c < 1) {
                    clearInterval(e);
                    f.timeWrap.show();
                    f.timeWrap.html(f.timeCount + "s");
                    f.timeLimit();
                    f.score.show();
                    f.tipPanel.clear()
                } else {
                    d.html(c)
                }
                c--
            },
            400)
        },
        bgLayer: function(e, d) {
            var c = b("<div/>").addClass("" + d + " bg-layer");
            e ? c.insertBefore(e) : c.appendTo("body");
            e.show();
            c.clear = function() {
                c.remove();
                e.hide()
            };
            b("body").height(b(window).height());
            return c
        },
        timeLimit: function() {
            var c = this;
            this.gameCountTimer = setInterval(function() {
                if (c.timeCount < 0) {
                    clearInterval(c.gameCountTimer);
                    c.timeWrap.html("0s");
                    c.timeCount = 30;
                    c.isGameOver = true;
                    c.isStartDrag = false;
                    c.drop();
                    c.noticeStop()
                } else {
                    c.timeWrap.html(c.timeCount--+"s")
                }
            },
            1000)
        },
        noticeStop: function() {
            var c = this;
            b.ajax({
                url: "http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/stop",
                type: "get"
            }).done(function(d) {
                if (d) {
                    c.token = d.token;
                    if (d.prize) {
                        b(".result-page").addClass("hasprize")
                    }
                }
                c.resultPanel = c.bgLayer(b(".result-page"), "over-layer")
            })
        },
        getTop: function() {
            var c = this;
            b.ajax({
                url: "http://wqtim.sinaapp.com/addons/tim_cow/template/mobile/top",
                type: "get",
            }).done(function(d) {
                if (d.result == "success") {
                    c.top = d.data;
                    c.renderList(d.data)
                }
            })
        },
        renderList: function(c) {
            var d = this;
            if (c.length == 0) {
                this.userList.find("tbody").html('<tr style="text-align:center">暂时没有排行</tr>');
                b(".ranklist").css({
                    height: "2rem"
                })
            } else {
                c.forEach(function(f) {
                    f.score = f.score ? f.score: 0;
                    f.phone_number = f.phone_number.substr(0, 11);
                    var e = b("<tr><td>用户</td><td>" + f.phone_number + '</td><td class="division"></td><td>' + f.score + "米</td></tr>");
                    d.userList.append(e)
                })
            }
        },
        dialogShow: function(d) {
            var c = this.dialog;
            c.find(".text").html(d);
            return this.bgLayer(c, "dialog-bg-layer")
        },
        fixCssAnimation: function(d, e, c) {
            d.css(e);
            setTimeout(function() {
                d.css(c)
            },
            100)
        }
    };
    a.init()
})(jQuery);