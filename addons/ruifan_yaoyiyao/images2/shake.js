$(function() {
    var x = [];
    var b = false;
    function a(D) {
        var C = '<li class="item">      <div class="info">          <img src="' + D.header + '">            <span>' + D.name + '</span>     </div>      <div class="prize">         <span>获得<span class="bold">' + D.prizeName + "</span</span>     </div>  </li>";
        var E = $(C);
        $(".container .list ul").append(E)

    }

    function d() {
        setInterval(function() {

            if (r) {
                return
            }
            try {
                if (x.length == 0) {
                    return
                }

                var E = x.pop();
                a(E);
                var C = $(".container .list ul");
                if ($(".container .list ul li").length > 3) { C.css("margin-top", (parseFloat(C.css("margin-top")) - 131) + "px") }
            } catch (D) {}
        }, 1000)
    }
    $(".container .begin .button span").click(function() {

        if (shake.timeStart > 0) {
            $(".container>div").addClass("Hidden");
            $(".container>div.countdown").removeClass("Hidden")
        }
        setTimeout(function() {
            f(shake.timeStart, function() {
                var setshow = $("#setshow").val();
                $.get(setshow, function(data) {
                var success = data.success;

                }, "json")
                $(".container>div").addClass("Hidden");
                $(".container>div.ing").removeClass("Hidden");
            j();

                i(shake.timeEnd, function() {
                    b = true;
                    r = true;
                var setnoshow = $("#setnoshow").val();
                $.get(setnoshow, function(data) {
                var success = data.success;
                
                }, "json")

                });
                g()
            })
        }, 200)
    });
    var m;
    var h = $(".container .ing span.second");
    var p = $(".container .ing .time .left");

    function i(C, D) {
        if (i < 0) {
            alert(323213);
            return
        }
        C = parseInt(C);
        if (C < 100000) { h.html(C) } else { p.html(shake.welcome) }
        m = setInterval(function() {
            C--;
            if (C <= 0) {
                clearInterval(m);
                h.html(0);
                if (D && typeof D == "function") { D() }
                return
            }
            if (C < 100000) { h.html(C) } else { p.html(shake.welcome) }
        }, 1000)
    }
    var k = 10;

    function f(E, H) {
        if (E <= 0) {
            if (H && typeof H == "function") { H() }
            return
        }
        var C = k - E;
        var D = $(".container .countdown .wrap ul li");
        D.removeClass("Show");
        D.eq(C).addClass("Show");
        setTimeout(function() { D.eq(C).addClass("anm") }, 500);
        var G = D.eq(C);
        var F = setInterval(function() {
            if (G.next().length > 0) {
                G.removeClass("Show");
                G.next().addClass("Show");
                var I = G.next();
                setTimeout(function() { I.addClass("anm") }, 500);
                G = G.next()
            } else {
                clearInterval(F);
                if (H && typeof H == "function") { H() }
            }
        }, 1000)
    }


    function j() {
        var getrpurl = $("#getrpurl").val();
            $.get(getrpurl, function(J) {
                var I = J.total;
                $(".container .ing .prizeCount").html(I);
                var D = J.record;
                for (var F = D.length - 1; F >= 0; F--) {
                    var H = D[F];
                    var E = {};
                    E = { header: H.avatar, name: H.fname, prizeName: H.prizetype };
                    x.unshift(E)
                }
                var G = $(".container .list ul");
                d()
        }, "json");
    }
    var r = false;

    function g() {
        setInterval(function() {
            if (r) {
                return
            }
            var getrpurl = $("#getrpurl").val();
            $.get(getrpurl, function(H) {
                    var G = H.total;
                    $(".container .ing .prizeCount").html(G);
                    var C = H.record;
                    for (var E = 0; E < C.length; E++) {
                        var F = C[E];
                        var D = {};
                        D = { header: F.avatar, name: F.fname, prizeName: F.prizetype };
                        x.unshift(D)
                    }

            }, "json")
        }, 3000)
    }
   
    $(".container .end .wrap .winner .nav_left").click(function() {
        var C = $(".container .end .wrap .winner .main ul.active");
        var D = C.prev();
        if (D && D.length > 0) {
            C.removeClass("active");
            D.addClass("active")
        }
        e()
    });
    $(".container .end .wrap .winner .nav_right").click(function() {
        var D = $(".container .end .wrap .winner .main ul.active");
        var C = D.next();
        if (C && C.length > 0) {
            D.removeClass("active");
            C.addClass("active")
        }
        e()
    });

    function e() {
        var C = $(".container .end .wrap .winner .main ul.active");
        if (C.next().length == 0) { $(".container .end .wrap .winner .nav_right").css("opacity", 0) } else { $(".container .end .wrap .winner .nav_right").css("opacity", 1) }
        if (C.prev().length == 0) { $(".container .end .wrap .winner .nav_left").css("opacity", 0) } else { $(".container .end .wrap .winner .nav_left").css("opacity", 1) }
    }

    function n() {
        b = true;
        r = true;
        $.get("/screen/getShakePrize", { aidStr: aidStr, shakeId: shake.shakeId }, function(F) {
            if (F.ret == 0) {
                var E = F.model.total;
                $(".container .ing .prizeCount").html(E);
                var C = F.model;
                for (var D = 0; D < prizeJson.length; D++) { prizeJson[D].prizerlist = C[prizeJson[D].awardName].results }
                $(".container>div").addClass("Hidden");
                $(".container>div.end").removeClass("Hidden");
                q.eq(0).click()
            }
        }, "json")
    }

});
