var rankTopTen = [];
var RanksPosition = [];
var Players = {};
var Cutmusic,
Newmusic,
Outmusic,
Overmusic;
function mess(b) {
    var c = Math.floor,
    h = Math.random,
    a = b.length,
    f,
    e,
    d,
    g = c(a / 2) + 1;
    while (g--) {
        f = c(h() * a);
        e = c(h() * a);
        if (f !== e) {
            d = b[f];
            b[f] = b[e];
            b[e] = d
        }
    }
    return b
}


(function(b) {
    var c = 0;
    var d = ["ms", "moz", "webkit", "o"];
    for (var a = 0; a < d.length && !b.requestAnimationFrame; ++a) {
        b.requestAnimationFrame = b[d[a] + "RequestAnimationFrame"];
        b.cancelAnimationFrame = b[d[a] + "CancelAnimationFrame"] || b[d[a] + "CancelRequestAnimationFrame"]
    }
    if (!b.requestAnimationFrame) {
        b.requestAnimationFrame = function(i, f) {
            var e = new Date().getTime();
            var g = Math.max(0, 16 - (e - c));
            var h = b.setTimeout(function() {
                i(e + g)
            },
            g);
            c = e + g;
            return h
        }
    }
    if (!b.cancelAnimationFrame) {
        b.cancelAnimationFrame = function(e) {
            clearTimeout(e)
        }
    }
})(window); 

(function(a) {
    a.GameTimer = function(b, c) {
        this.__fn = b;
        this.__timeout = c;
        this.__running = false;
        this.__lastTime = Date.now();
        this.__stopcallback = null
    };
    a.GameTimer.prototype.__runer = function() {
        if (Date.now() - this.__lastTime >= this.__timeout) {
            this.__lastTime = Date.now();
            this.__fn.call(this)
        }
        if (this.__running) {
            a.requestAnimationFrame(this.__runer.bind(this))
        } else {
            if (typeof this.__stopcallback === "function") {
                a.setTimeout(this.__stopcallback, 100)
            }
        }
    };
    a.GameTimer.prototype.start = function() {
        this.__running = true;
        this.__runer()
    };
    a.GameTimer.prototype.stop = function(b) {
        this.__running = false;
        this.__stopcallback = b
    }
})(window);
var tick = 1000;
var LineLength = $(window).width();
var PlayStep = 16;
var flgGameStop = false;
var mainTick = new GameTimer(function() {
    $.each(RanksPosition, 
    function(c, d) {
        var e = d + PlayStep;
        if (c == 0 || e < RanksPosition[c - 1] - size * 3 / 4) {
            RanksPosition[c] = e
        }
    });
    if (flgGameStop) {
        window.clearTimeout(tmr_GameDataLoad);
        mainTick.stop();
        var b = $(".tracklist").width() - size + diff;
        for (var a = 0; a < RanksPosition.length; a++) {
            RanksPosition[a] = b
        }
    }
},tick);

var tmr_GameDataLoad;
var topnum = 0;
var showGameResultOk = false;
var gameTimeRun = function() {

 
  $.getJSON(GAME_RUNING, {
        topnum: topnum,
    },function(d) {
        // console.log(d);
        if (d.flag == 1 && d.data) {
            if ($.isArray(d.data["players"])) {
                rankTopTen = d.data["players"].slice(0, SHAKE_LINE);
                var a = rankTopTen.length;
                
				var s;
				for(i=0;i<a;i++){
					if(i<3&&rankTopTen[i]["progress"]>=100){
						$("#tx"+i).attr("src","../addons/haoman_dpm/img10/p"+i+".jpg");
					}else{
						$("#tx"+i).attr("src",rankTopTen[i]["avatar"]);
					}
					$("#nc"+i).html(rankTopTen[i]["nickname"]);
					s =parseInt(rankTopTen[i]["mid"])%10;
					$("#ph"+i).animate({width:rankTopTen[i]["progress"]+'%'});
					
					$("#ph"+i).css("background","url("+d.ma+") no-repeat right center");
					$("#pxh"+i).animate({width:rankTopTen[i]["progress"]+'%'});;
					// topnum = topnum + rankTopTen[i]["point"];
				}
 
            }
    
            if (d.data["status"] == -1) {
				
				window.clearInterval(tmr_GameDataLoad);
				
                var e = $(".tracklist").width() - size + diff;
                for (var b = 0; b < RanksPosition.length; b++) {
                    RanksPosition[b] = e
                }
                window.setTimeout(function() {
                    showGameResult();
                },660)
            }
        } else {
			 
        }
    })
};
function showGameResult() {
    if(showGameResultOk){
        console.log(1)
        return;
    }
    console.log(2)
    showGameResultOk = true;
    var b = $(".result-layer").show();
    var d = $(".result-label", b).show().addClass("pulse");
    var a = $(".result-cup", b).hide();
    var c = 3;
    if (Overmusic) {
        Overmusic.play()
    }
    window.setTimeout(function() {
        d.fadeOut(function() {
            a.show(function() {
                if (c >= 1 && rankTopTen[0]) {
                    window.setTimeout(function() {
                        var e = $PlayeSeed.clone().addClass("result").css({
                            left: "50%",
                            "margin-left": "-65px",
                            width: "160px",
                            height: "160px",
                            bottom: "150px"
                        });
                        e.find(".head").css({
                            "background-image": "url(" + rankTopTen[0]["avatar"] + ")"
                        }).addClass("shake");
                        e.find(".nickname").html(rankTopTen[0]["nickname"]);
                        e.appendTo(a).addClass("bounce")
                    },
                    800)
                }
                if (c >= 2 && rankTopTen[1]) {
                    window.setTimeout(function() {
                        var e = $PlayeSeed.clone().addClass("result").css({
                            left: "40px",
                            width: "100px",
                            height: "100px",
                            bottom: "120px"
                        });
                        e.find(".head").css({
                            "background-image": "url(" + rankTopTen[1]["avatar"] + ")"
                        }).addClass("shake");
                        e.find(".nickname").html(rankTopTen[1]["nickname"]);
                        e.appendTo(a).addClass("bounce")
                    },
                    1800)
                }
                if (c >= 3 && rankTopTen[2]) {
                    window.setTimeout(function() {
                        var e = $PlayeSeed.clone().addClass("result").css({
                            right: "30px",
                            width: "70px",
                            height: "70px",
                            bottom: "100px"
                        });
                        e.find(".head").css({
                            "background-image": "url(" + rankTopTen[2]["avatar"] + ")"
                        }).addClass("shake");
                        e.find(".nickname").html(rankTopTen[2]["nickname"]);
                        e.appendTo(a).addClass("bounce")
                    },
                    2800)
                }
            })

        }).removeClass("pulse")
    },1000)
    window.setTimeout(function() {
        showGameResultOk = false;
    },5000) 
}
var $PlayeSeed,
lineHeight,
diff = 10;
var size;
var resizePart = window.WBActivity.resize = function() {
    var b = $(".Panel.Track"),
    a = b.find(".tracklist").children();
    size = lineHeight = b.height() / SHAKE_LINE;
    roundLength = $(".Panel.Track .tracklist").width() - size;
    a.each(function() {
        $(this).css({
            height: size,
            "line-height": size + "px",
            "font-size": size * 3 / 5 + "px"
        }).find(".track-start,.track-end").css({
            width: size + "px",
            height: size + "px"
        })
    });
    $PlayeSeed = $('<div class="player"><div class="head"></div><div class="nickname"></div></div>').css({
        width: size - diff * 2,
        height: size - diff * 2
    })
};
var start = window.WBActivity.start = function() {
    window.WBActivity.hideLoading();
    var d = document.getElementById("Cutmusic");
    if (d.play) {
        Cutmusic = d
    }
    var c = document.getElementById("Newmusic");
    if (c.play) {
        Newmusic = c
    }
    var b = document.getElementById("Outmusic");
    if (b.play) {
        Outmusic = b
    }
    var a = document.getElementById("Overmusic");
    if (a.play) {
        Overmusic = a
    }
    $(".Panel.Top").css({
        top: 0
    });
    $(".Panel.Bottom").css({
        bottom: 0
    });
    $(".Panel.Track").css({
        display: "block",
        opacity: 1
    });
    resizePart();
    window.setTimeout(function() {
        nextRound()
    },2000);
    $(".startcenter .button-start").on("click", 
    function() {
        $(".startcenter").slideUp(function() {
            cutdown_start()
        })
        
    });
    $(".button.allresult").on("click", function() {
        showScore()
    });
    $(".button.reset").on("click", function() {
        nextRound()
    })
};
var tmr_cutdown_start;
var cutdown_start = function() {
    var a = $(".cutdown-start"),
    b = READY_TIME + 1;
    a.html("").show().css({
        "margin-left": -a.width() / 2 + "px",
        "margin-top": -a.height() / 2 + "px",
        "font-size": a.height() * 0.7 + "px",
        "line-height": a.height() + "px"
    }).addClass("cutdownan-imation");
    tmr_cutdown_start = window.setInterval(function() {
        b--;
        if (b == 0) {
            $.getJSON(GAME_START, {
                type: "start"
            },function(c) {
                if (c.flag == 1) {

                    a.html("GO!")
                } else {
                    alert(c.msg);
                    window.location.reload()
                }
            }).fail(function() {
                alert("无法连接游戏服务器，请刷新重新开始");
                window.location.reload()
            })
        } else {
            if (b < 0) {
                window.clearInterval(tmr_cutdown_start);
                a.hide();
				 $(".top_title").hide();
              tmr_GameDataLoad=window.setInterval('gameTimeRun()',1500);
            } else {
                Cutmusic.play();
                a.html(b)
            }
        }
    },
    1000)
};

var cutdown_startb = function() {
    var a = $(".cutdown-start"),
    b =  1;
    a.html("").show().css({
        "margin-left": -a.width() / 2 + "px",
        "margin-top": -a.height() / 2 + "px",
        "font-size": a.height() * 0.7 + "px",
        "line-height": a.height() + "px"
    }).addClass("cutdownan-imation");
    tmr_cutdown_start = window.setInterval(function() {
        b--;
        if (b == 0) {

            // $.getJSON(GAME_START, {
            //     type: "start"
            // },function(c) {
            //     if (c.flag == 1) {
            //         a.html("GO!")
            //     } else {
            //         alert(c.msg);
            //         window.location.reload()
            //     }
            // }).fail(function() {
            //     alert("无法连接游戏服务器，请刷新重新开始");
            //     window.location.reload()
            // })
        } else {
            if (b < 0) {
                window.clearInterval(tmr_cutdown_start);
                a.hide();
				 $(".top_title").hide();
              tmr_GameDataLoad=window.setInterval('gameTimeRun()',1500);
            } else {
                Cutmusic.play();
                a.html(b)
            }
        }
    },1000)
};
var roundTime,
roundLength;
var nextRound = function() {
	resethd();
    flgGameStop = false;
    Players = {};
    RanksPosition = [];
    for (var a = 0; a < SHAKE_LINE; a++) {
        RanksPosition[a] = 0
    }
    roundTime = CUTDOWN_TIME * 1000;
    PlayStep = roundLength / ((roundTime / tick) >> 0);
    $(".Panel.Track .tracklist").find(".player").remove();
    $(".result-layer").hide().find(".player").remove();
	if(window.startfrerpaoma==1){
     cutdown_startb();
	 
	 
	}else{
        // $(".startcenter").slideDown();
		$(".startcenter").show();
	}
};

function showScore(b) {
    var a = GAME_RANK;
    
    $.showPage(a)
}

function resethd(){
	for(i=0;i<10;i++){
		$("#tx"+i).attr("src",'../addons/haoman_dpm/img10/touxiang.jpg');
		$("#nc"+i).html('');
		$("#ph"+i).animate({width:'0%'});
		$("#pxh"+i).animate({width:'0%'});;
		
	}
}