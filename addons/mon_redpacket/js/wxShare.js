$(function() {

    $("#shareGuid").click(function() {
        $(".maskTip").show()
    });
    $("#Close").click(function() {
        $(".maskTip").hide()
    });


    function shareFriend() {
        WeixinJSBridge.invoke("sendAppMessage", {appid: Share.appid,img_url: Share.imgUrl,img_width: "640",img_height: "640",link: Share.lineLink,desc: Share.descContent,title: Share.shareTitle}, function(res) {
            _report("send_msg", res.err_msg)
        })
    }
    function shareTimeline() {
        WeixinJSBridge.invoke("shareTimeline", {img_url: Share.imgUrl,img_width: "640",img_height: "640",link: Share.lineLink,desc: Share.descContent,title: Share.shareTitle}, function(res) {
            _report("timeline", res.err_msg)
        })
    }
    function shareWeibo() {
        WeixinJSBridge.invoke("shareWeibo", {content: Share.descContent,url: Share.lineLink,}, function(res) {
            _report("weibo", res.err_msg)
        })
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
