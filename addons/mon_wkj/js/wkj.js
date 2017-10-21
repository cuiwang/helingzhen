$(document).ready(function () {
    $("#loading").css("opacity", "0");


    window.setTimeout(function () {
        $("#loading").css("display", "none")
    }, 1500);


    if (kj_status == "WXbangkanindex" || kj_status == "SCzikanindex") {
        //cheat("starttime", _speedMark, 0)
    }
});


function cheat(a, b, d) {
    var c = new Date().getTime() + "." + parseInt(Math.random() * 1000);
    $.ajax({
        url: "cheat.php?starttime=" + b.getTime() + "&endtime=" + d + "&action=" + a + "&gid=" + gid + "&openid=" + openid + "&promotionopenid=" + promotionopenid + "&jid=" + jid + "&rnd=" + c,
        dataType: "json"
    }).done(function (e) {
    }).error(function () {
    })
}


var timeDiff = null;
function ShowCountDown(l, k, n, e, d, h) {
    var b = new Date();
    var c = new Date(endTimeJieshu * 1000).getTime();
    var j = new Date(startTimeKaishi * 1000).getTime();
    if (timeDiff == null) {
        timeDiff = b.getTime() - j
    }
    var a = c - b.getTime() + timeDiff;
    var c = new Date(l, k - 1, n, e, d);
    var q = parseInt(a / 1000);
    var p = Math.floor(q / (60 * 60 * 24));
    var f = Math.floor((q - p * 24 * 60 * 60) / 3600);
    var g = Math.floor((q - p * 24 * 60 * 60 - f * 3600) / 60);
    var i = Math.floor(q - p * 24 * 60 * 60 - f * 3600 - g * 60);
    var m = document.getElementById(h);
    var o = $("#wo").text();
    $(m).html(o + '的活动倒计时：<span style="margin-left:0;">' + p + "</span>天<span>" + f + "</span>时<span>" + g + "</span>分<span>" + i + "</span>秒");
    if (p == 0 && f == 0 && g == 0 && i == 0) {
        clearInterval(qingchuTime);
        $("#daijishi").text("活动已结束")
    }
    if (p < 0 || f < 0 || g < 0) {
        $("#daijishi").text("当前状态：活动已结束")
    }
}

function sortNumber(d, c) {
    return d - c
}
function sortNumberdx(d, c) {
    return c - d
}
function canyuZongShu() {
    var a = new Date().getTime() + "." + parseInt(Math.random() * 1000);
    $.ajax({url: "getpv.php", data: {rnd: a}}).done(function (e) {
        var c = String(e).split("");
        var d = "";
        for (var b = 0; b < c.length; b++) {
            d += "<span>" + c[b] + "</span>"
        }
        $(".gyStyle .topImg .fangwenliang .spanrong").html(d);
        window.setTimeout(canyuZongShu, 5000)
    }).error(function () {
        alert("网络异常。。")
    })
}



if ($(".gyStyle .topImg .fangwenliang").get(0)) {
    window.setTimeout(canyuZongShu, 5000)
}


if ($(".daijishi").get(0)) {
    var interval = 1000;
    var dqDate = new Date(), dqyear = dqDate.getFullYear(), dqmonth = dqDate.getMonth() + 1, dqdate = dqDate.getDate(), dqhoutes = dqDate.getHours(), dqMinutes = dqDate.getMinutes(), dqseconds = dqDate.getSeconds();


    if (joinstatus != "正常" || dqyear > year || (dqyear == year && dqmonth > yue) || (dqyear == year && dqmonth == yue && dqdate > date) || (dqyear == year && dqmonth == yue && dqdate == date && dqhoutes > shi) || (dqyear == year && dqmonth == yue && dqdate == date && dqhoutes == shi && dqMinutes > fen)) {
        if (joinstatus != "正常") {
            $("#daijishi").text("当前状态:  " + joinstatus)
        } else {
            $("#daijishi").text("活动时间已结束")
        }
    } else {
        var qingchuTime = window.setInterval(function () {
            ShowCountDown(year, yue, date, shi, fen, "daijishi")
        }, interval)
    }
}


if ($(".kanjialist").get(0)) {
    var paiXuZHuangTai = true, paiXuHou;
    $(".gyStyle2").on("click", function (j) {
        var h = j.target, l = h.tagName.toLowerCase();
        if ($(h).parent().hasClass("kandiaojine") || $(h).hasClass("kandiaojine")) {
            var a = $(".listx .kanhoujia span");
            var m = $(".listx .ul1 li");
            var g = new Array(), b = new Array();
            for (var f = 0; f < a.length; f++) {
                g[f] = $(a[f]).text().split("￥")[1];
                b[f] = $(m[f])
            }
            if (paiXuZHuangTai) {
                paiXuHou = g.sort(sortNumber);
                $(".gyStyle2 .kandiaojine img").css({"-webkit-transform": "rotate(180deg)"});
                paiXuZHuangTai = false
            } else {
                paiXuHou = g.sort(sortNumberdx);
                $(".gyStyle2 .kandiaojine img").css({"-webkit-transform": "rotate(0)"});
                paiXuZHuangTai = true
            }
            $(".listx .ul1").html("");
            for (var c = 0; c < b.length; c++) {
                for (var d in b) {
                    if ($(b[d]).children(".kanhoujia").text().split("￥")[1] == paiXuHou[c]) {
                        $(".listx .ul1").append(b[d])
                    }
                }
            }
        }
    })
}


function tanchukGy(a) {

    $("#tanchuk, #zhegaik").css({display: "block"});
    window.setTimeout(function () {
        $("#tanchuk p").text(a);
        $("#zhegaik").css({opacity: 0.7});
        $("#tanchuk").css({top: "50px", opacity: 1})
    }, 50)
}


$(document.body).on("click", function (c) {
    var a = c.target, b = a.tagName.toLowerCase();
    if ($(a).parents(".bangkan").hasClass("bangkan") || $(a).hasClass("bangkan")) {
        ajax("kanjia", promotionopenid, openid)
    }
    if ($(a).hasClass("quxiaok")) {
        $("#zhegaik").css({opacity: 0});
        $("#tanchuk").css({opacity: 0, top: "-60px"});
        window.setTimeout(function () {
            $("#tanchuk, #zhegaik").css({display: "none"})
        }, 300)
    }


    if ($(".gyStyle").get(0) && $(a).parents(".fenxiangDiv").hasClass("fenxiangDiv") || $(a).hasClass("fenxiangDiv")) {
        if ($(a).hasClass("hongsea")) {
            $("#tanchuk").css({opacity: 0, top: "-60px"});
            window.setTimeout(function () {
                $("#tanchuk").css({display: "none"})
            }, 300)
        }
        $(".fenxiangImgk, #zhegaik").css({display: "block"});
        window.setTimeout(function () {
            $("#zhegaik").css({opacity: 0.9});
            $(".fenxiangImgk").css({opacity: 1})
        }, 50)
    }
    if ($(".gyStyle").get(0) && $(a).parents(".bangkanyidao").hasClass("bangkanyidao")) {
        window.setTimeout(function () {
            $(".gyStyle .infoks .ycwenzik").css("display", "none");
            $(".gyStyle .infoks .zikanhou").css("display", "block")
        }, 50)
    }
});


$("#zhegaik").click(function () {
    $("#zhegaik").css({opacity: 0});
    $("#huodongWuPin").css({bottom: "-280px"});
    $("#tanchuk").css({opacity: 0, top: "-60px"});
    $(".jizanimg").css("opacity", "0");
    $(".fenxiangImgk").css("opacity", "0");
    $(".guanzhuan").css({opacity: "0", bottom: "-50px"});
    window.setTimeout(function () {
        $(".jizanimg").css("display", "none");
        $("#huodongWuPin").css({display: "none", opacity: 0});
        $("#zhegaik").css({display: "none"});
        $("#tanchuk").css({display: "none"});
        $(".fenxiangImgk").css({display: "none"});
        $(".guanzhuan").css("display", "none")
    }, 300)
});



function yanzhengk(a) {
    $("#yanzhengk").css({display: "block"});
    window.setTimeout(function () {
        $("#yanzhengk strong").text(a);
        $("#yanzhengk").css({opacity: 1});
        window.setTimeout(function () {
            $("#yanzhengk").css({opacity: 0});
            window.setTimeout(function () {
                $("#yanzhengk").css({display: "none"})
            }, 300)
        }, 2000)
    }, 50)
}


var isOK = {}, submitCheckEvent = {};
function shouHuoRenCheck(c) {
    var a = document.getElementById(c);
    isOK.content = false;
    submitCheckEvent.content = b;
    function b() {
        isOK.content = false;
        if (!a.value) {
            yanzhengk("收货地址不能为空");
            return
        }
        isOK.content = true
    }
}


function selectValue(d, e, b) {
    var a = document.getElementById(d);
    isOK.content = false;
    submitCheckEvent.content = c;
    function c() {
        isOK.content = false;
        if (a.value == e) {
            yanzhengk("请选择收货省份");
            return
        }
        isOK.content = true
    }
}



function lianXiFangShiCheck(c) {
    var a = document.getElementById(c);
    isOK.lianXiFangShi = false;
    submitCheckEvent.lianXiFangShi = b;
    function b() {
        isOK.lianXiFangShi = false;
        if (!a.value) {
            yanzhengk("联系方式不能为空");
            return
        }
        if (!/1[3-8]+\d{9}/.test(a.value)) {
            yanzhengk("请输入正确的电话号码");
            return
        }
        isOK.lianXiFangShi = true
    }
}


function xiangXiDiZhiCheck(c) {
    var a = document.getElementById(c);
    isOK.xiangXiDiZhi = false;
    submitCheckEvent.xiangXiDiZhi = b;
    function b() {
        isOK.xiangXiDiZhi = false;
        if (!a.value) {
            yanzhengk("详细地址不能为空");
            return
        }
        isOK.xiangXiDiZhi = true
    }
}



$("#replyForm").submit(function (c) {
    var b = true;
    for (var a in submitCheckEvent) {
        submitCheckEvent[a]()
    }
    for (var a in isOK) {
        if (!isOK[a]) {
            b = false;
            break
        }
    }
    if (!b) {
        if (c && c.preventDefault) {
            c.preventDefault()
        } else {
            window.event.returnValue = false
        }
    }
});



if ($(".dingdan").get(0)) {
    shouHuoRenCheck("shouhuoren");
    lianXiFangShiCheck("lianxifangshi");
    xiangXiDiZhiCheck("xiangxidizhi")
}



function shareajax() {
    ajax("share", promotionopenid, openid)
}



function del(a) {
    ajax("del", promotionopenid, a)
}



function taozhuang(a) {
   // window.location.href = a + "?agentcode=" + agentcode + "&hid=" + hid + "&gid=" + gid + "&promotionopenid=" + promotionopenid


    window.location.href = a;
}



$("#woyaocanyu").click(function () {
    subscribe("canyu")
});



function subscribe(b) {
    var a = new Date().getTime() + "." + parseInt(Math.random() * 1000);
    $.ajax({
        url: "subscribe.php",
        data: {
            action: b,
            openid: openid,
            hid: hid,
            gid: gid,
            agentcode: agentcode,
            promotionopenid: promotionopenid,
            rnd: a
        },
        dataType: "json",
    }).done(function (c) {
        if (b == "bangkan" && c.subscribe == 1) {
            cheat("endtime", _speedMark, new Date().getTime());
            ajax("kanjia", promotionopenid, openid)
        } else {
            if (c.subscribe == 1) {
                location.href = "index.php?gid=" + gid
            } else {
                window.location.href = c.back
            }
        }
    }).error(function () {
        alert("网络异常")
    })
}


$("#goumai").click(function () {
    tanchukGy("你确定要购买么？确认后不能再进行砍价的哦！");
});
$("#passgoumai").click(function () {
     ajax("passgoumai")
});

$("#kanjia").click(function () {

    ajax("kanjia");
});
$("#mykanjia").click(function () {
    ajax("kanjia")
});
function sharetipmsg() {
    ajax("sharetipmsg")
}
function ajax(a) {

    $.ajax({
        url: ajaxUrl,
        data: {action: a},
        dataType: "json",
        success: function (e) {

            if(e.code!=200){
                alert(e.msg);
                return ;
            }

            if (e.action == "kanjia") {
                $(".tanchuk .tanchukMain .jianqiank strong").text("减" + e.kanjiaPrice);
                tanchukGy(e.msg);
                var rank_name="查看排行榜";
                if(firend){
                    rank_name="查看排行榜";
                }

                rankingUrl=rankingUrl+"&uid="+ e.uid;

                uid= e.uid;

                initShare();

                $(".gyStyle .infoks .anniouk .a2 ").html('<a href="javascript:;" onclick="taozhuang(rankingUrl)" ><img class="wdhuodong" src="'+res_path+'images/futou.png" alt="图片"><span>'+rank_name+'</span></a>');


                if (e.ret == 0) {
                    $(".tanchuk .tanchukMain .jianqiank").hide()
                }
                window.setTimeout(function () {
                    $(".gyStyle .infoks .zikanhou").css("display", "block");
                    $(".gyStyle .infoks .ycwenzik").css("display", "none");
                    $(".gyStyle .infoks .anniouk").css("margin-top", "33px");
                    $(".gyStyle .infoks .qingchuFloat").css("float", "left");
                    $(".gyStyle .infoks .yincangY").css("display", "block")
                }, 50)
            } else {
                if (e.action == "passgoumai") {
                    if (e.status == 1) {
                        window.location.href =orderUrl;
                    } else {
                        alert(e.msg)
                    }
                } else {
                    if (e.action == "sharetipmsg") {
                        wxData.desc = e.msg
                    }
                }
            }
        }
    })
};