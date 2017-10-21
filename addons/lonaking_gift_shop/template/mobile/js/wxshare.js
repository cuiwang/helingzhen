var shareUrl = document.getElementsByName("share_url")[0].getAttribute("content");
var imgUrl = document.getElementsByName("share_logo")[0].getAttribute("content");
var title = document.getElementsByName("share_title")[0].getAttribute("content");
var content = document.getElementsByName("share_content")[0].getAttribute("content");
wx.ready(function () {
    wx.onMenuShareAppMessage({
        title: title,
        desc:  content,
        link:  shareUrl,
        imgUrl: imgUrl,
        trigger: function (res) {},
        success: function (res) {
            shareCallback();
        },
        cancel: function (res) {},
        fail: function (res) {
            
        }
    });
    wx.onMenuShareTimeline({
        title: title,
        link: shareUrl,
        imgUrl: imgUrl,
        trigger: function (res) {},
        success: function (res) {
            shareCallback();
        },
        cancel: function (res) {},
        fail: function (res) {}
    });
    wx.onMenuShareQQ({
        title: title,
        desc:  content,
        link:  shareUrl,
        imgUrl: imgUrl,
        trigger: function (res) {},
        success: function (res) {
            shareCallback();
        },
        cancel: function (res) {},
        fail: function (res) {}
    });
    wx.onMenuShareWeibo({
        title: title,
        desc:  content,
        link:  shareUrl,
        imgUrl: imgUrl,
        trigger: function (res) {},
        success: function (res) {
            shareCallback();
        },
        cancel: function (res) {},
        fail: function (res) {}
    });
});
/**
 * 分享回调方法
 */
function shareCallback(){
    var url = $("html").attr("data-share-api");
    $.get(url,function(result){
        var json = eval('(' + result + ')');
        if(json.message != null){
            alert(json.message);
        }
    });
}