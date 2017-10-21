$(function() {
    var today = new Date();
    var todayStr = today.getFullYear() + "-" + today.getMonth() + "-" + today.getDate();
    var host = $("#host").val();
    if (getCookie($("#invitoropenid").val() + "|" + todayStr) == "1") {
        $("#rePrizeErrorDiv").show()
    } else {
        $("#Close").click(function() {
            $(".maskTip").hide()
        });
        $("#randomPrize").click(function() {
            $("#shareToFriends").show()
        });
        function init() {
            $(".tipClick,.rotatoText,.resultText,.shareAll").hide();
            $(".cardList .card").removeClass("flip");
            setTimeout(function() {
                $(".tipClick").fadeIn(200, function() {
                    setTimeout(function() {
                        $(".tipClick").fadeOut(300)
                    }, 1000)
                })
            }, 1000)
        }
        var rotatoResult;
        function getMoneyResult() {
            setTimeout(function() {
                clearInterval(rotatoResult);
                $(".flipover").addClass("flip");
                $(".rotatoText").hide();
                $(".resultText,.shareAll").show(400);
                $("#randomPrize").removeClass("prizing")
            }, 2000)
        }
        $(".cardList .card").click(function() {
            if ($("body").hasClass("done")) {
                $("#rePrizeErrorDiv").show();
                return
            } else {
                if ($(this).hasClass("flipover")) {
                    return
                }
                $(this).addClass("flipover");
                var _self = $(this);
                setTimeout(function() {
                    $(".cardList .card").each(function() {
                        if (!$(this).hasClass("flipover")) {
                            $(this).fadeOut(200)
                        }
                    });
                    _self.addClass("togetherResult").find(".back").css({width: 110,height: 155}, 600);
                    $(".rotatoText").show();
                    rotatoResult = setInterval(function() {
                        _self.addClass("rotatoOver");
                        setTimeout(function() {
                            _self.removeClass("rotatoOver")
                        }, 150)
                    }, 300)
                }, 500);
				
                $.ajax({type: "POST",url: host + "/promotion/doubleeleven/randomprize",data: {invitor: $("#invitoropenid").val(),helperOpenId: $("#helperopenid").val(),helperNickName: $("#helpernickname").val()},dataType: "json",async: true,timeout: 3000,success: function(data) {
                        if (data.success) {
                            $(".cardList .price").removeClass("price_50 price_100 price_150 price_200 price_300 price_500");
                            $(".cardList .price").addClass("price_" + data.result);
                            $("#prizeResult").html(data.result);
                            $("#prizeMoney").html(data.result);
                            getMoneyResult();
                            var today = new Date();
                            var todayStr = today.getFullYear() + "-" + today.getMonth() + "-" + today.getDate();
                            setCookie($("#invitoropenid").val() + "|" + $("#helperopenid").val() + "|" + todayStr, "1")
                        } else {
                            $("#rePrizeErrorDiv").show()
                        }
                    },error: function(xhr, type) {
                        alert("发送网络请求失败！")
                    }})

            }

            $("body").addClass("done")
        });
        
        
        init()
    }
    
    
    var totalPrize = parseInt($("#myTotalPrize").val());
    var sharetxt = ["求你了，就戳一下，这里iPhone6白送！", "我要脱光，是朋友就帮我攒红包，带TA去旅行。", "是时候来一场高逼格的旅行了！戳一下免费去夏威夷。", "我错了，再也不耍帅到没朋友。帮我戳一下，攒钱脱光去旅行！", "我攒了" + totalPrize + "元，离免费夏威夷双人游一步之遥！", "我攒了" + totalPrize + "元，iPhone6，白送啦！", "我攒了" + totalPrize + "元，澳乐产品随便换啦！"];
    var s1 = 4 + parseInt(Math.random() * 3);
    if (s1 > 6) {
        s1 = 6
    }
    var s2 = parseInt(Math.random() * 4);
    if (s2 > 3) {
        s2 = 3
    }
    
    var imgUrl = "http://pic.aoliday.com/weixin/images/doubleeleven_front.jpg";
    var lineLink = host + "/promotion/doubleeleven/index?openid=" + $("#helperopenid").val() + "&time=" + new Date().getTime();
    var descContent = "澳乐网回馈粉丝，一亿红包助力境外游。是时候来一次高逼格的旅游了。";
    var shareTitle = totalPrize > 0 ? sharetxt[s1] : sharetxt[s2];
    var appid = "wxeb70abdb955d0fe8";
    
    function shareFriend() {
        WeixinJSBridge.invoke("sendAppMessage", {appid: appid,img_url: imgUrl,img_width: "640",img_height: "640",link: lineLink,desc: descContent,title: shareTitle}, function(res) {
            _report("send_msg", res.err_msg)
        })
    }
    function shareTimeline() {
        WeixinJSBridge.invoke("shareTimeline", {img_url: imgUrl,img_width: "640",img_height: "640",link: lineLink,desc: descContent,title: shareTitle}, function(res) {
            _report("timeline", res.err_msg)
        })
    }
    function shareWeibo() {
        WeixinJSBridge.invoke("shareWeibo", {content: descContent,url: lineLink,}, function(res) {
            _report("weibo", res.err_msg)
        })
    }
    function setCookie(key, value) {
        document.cookie = key + "=" + value
    }
    function getCookie(key) {
        var strCookie = document.cookie;
        var arrCookie = strCookie.split(";");
        for (var i = 0; i < arrCookie.length; i++) {
            var arr = arrCookie[i].split("=");
            if (key == arr[0]) {
                return arr[1]
            }
        }
        return null
    }
    document.addEventListener("WeixinJSBridgeReady", function onBridgeReady() {
        WeixinJSBridge.on("menu:share:appmessage", function(argv) {
            shareFriend()
        });
        WeixinJSBridge.on("menu:share:timeline", function(argv) {
            shareTimeline()
        });
        WeixinJSBridge.on("menu:share:weibo", function(argv) {
            shareWeibo()
        })
    }, false)
});
