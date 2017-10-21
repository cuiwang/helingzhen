// callWeixinJSapi.js

var WxModule = function () {

    var Wx = function () {
        // private data
        var support, config = {}, shareData;

        function checkJSApi() { // 判断当前版本是否支持指定 JS 接口
            wx.checkJsApi({
                jsApiList: [
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'onMenuShareQQ',
                    'onMenuShareWeibo',
                    'hideOptionMenu'
                ],
                success: function (res) {
                    support = res;
//                    alert(JSON.stringify(support));
                }
            });
        }

        function readConfig() {
            if (window.pageConfig && window.pageConfig.supportShare !== undefined) {
                config.supportShare = window.pageConfig.supportShare;
            } else {
                config.supportShare = true;
            }
        }

        function initShare() {

//            alert("initShare");

            shareData = {
                title: document.getElementById("share-title").value,
                desc: document.getElementById("share-description").value,
                link: document.getElementById("share-link").value,
                imgUrl: document.getElementById("share-cover").src
            };

//            alert(JSON.stringify(shareData));

            // 朋友圈
            wx.onMenuShareTimeline(shareData);

            // 分享给朋友
            wx.onMenuShareAppMessage(shareData);

            // 分享到QQ
            wx.onMenuShareQQ(shareData);

            // 分享到微博
            wx.onMenuShareWeibo(shareData);
        }

        /*function noop() {
            // 空函数
        }*/

        var init = function () {

            wx.error(errorHandler);

            checkJSApi();

            readConfig();

            if (config.supportShare) {
                initShare();
            } else {
//                alert("else!");
                // 不允许转发，影藏右上角按钮
                if (support["hideOptionMenu"]) {
                    wx.hideOptionMenu();
                }
            }
        };

        var errorHandler = function (error) {
            var request = new XMLHttpRequest();
            request.open("POST", "/mobile/JSSDKError?m=jssdk_error&_xsrf="+Utility.getCookie("_xsrf") + "&wechat_signature=" +
                            document.getElementById("wechat_signature").value);
            request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            request.send(JSON.stringify({jssdk_error: error}));
        };

        return {
            init: function () {
                wx.ready(init);
                wx.error(errorHandler);
            }
        };
    }();

    return Wx;

}();

WxModule.init();