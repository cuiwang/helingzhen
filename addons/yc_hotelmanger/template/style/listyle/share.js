/*from tccdn minify at 2016-7-7 17:21:53,file：/cn/train/wxhotel/160319/common/share.js*/
window.wxsharemodel = {
    config: null
};
var shareUtil = function (opt) {
    var parseArgs = function (arg, dft) {
        for (var key in dft) {
            if (typeof arg[key] == 'undefined') {
                arg[key] = dft[key];
            }
        }
        return arg;
    }
    _default = {
        title: "微信酒店来了！",
        desc: "以优雅的姿势预订酒店，无与伦比的方便快捷，独孤求败的物美价廉。你还等什么？",
        link: "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3827070276e49e30&redirect_uri=http://wx.17u.cn/flight/getopenid.html?url=http://wx.17u.cn/hotel/&showwxpaytitle=1&response_type=code&scope=snsapi_base&state=123#wechat_redirect",
        imgUrl: "https://wx.40017.cn/touch/weixin/flight/img/tclogo.png?v=1",
        type: '',
        dataUrl: '',
        hideMenuList: [],
        callBack: new Function,
        locationType: 'wgs84',
        wz: new Function
    }
    var opt = parseArgs(opt || {}, this._default)

    var onBridgeReady = function () {
        WeixinJSBridge.call('showOptionMenu');
    }
    if (document.addEventListener) {
        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
    } else if (document.attachEvent) {
        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
    }
    var domain = location.href;
    $.ajax({
        url: "http://wx.17u.cn/hotel/wechatapisha.html?url=" + encodeURIComponent(domain.split('#')[0]),
        type: 'get',
        dataType: 'jsonp',
        success: function (res) {
            var jsonstr = typeof res;
            if (jsonstr == "string") {
                wxsharemodel.config = JSON.parse(res);
            } else {
                wxsharemodel.config = res;
            }
            wxshareconfig();
        }
    });
    wx.ready(function () {
        wx.onMenuShareTimeline({
            title: opt.desc, // 分享标题
            link: opt.link, // 分享链接
            imgUrl: opt.imgUrl, // 分享图标
            success: function (res) {
                // 用户确认分享后执行的回调函数
                opt.callBack(res);
            },
            cancel: function (res) {
                // 用户取消分享后执行的回调函数
                opt.callBack(res);
            }
        });
        wx.onMenuShareAppMessage({
            title: opt.title, // 分享标题
            desc: opt.desc, // 分享描述
            link: opt.link, // 分享链接
            imgUrl: opt.imgUrl, // 分享图标
            type: opt.type, // 分享类型,music、video或link，不填默认为link
            dataUrl: opt.dataUrl, // 如果type是music或video，则要提供数据链接，默认为空
            success: function (res) {
                // 用户确认分享后执行的回调函数
                opt.callBack(res);
            },
            cancel: function (res) {
                // 用户取消分享后执行的回调函数
                opt.callBack(res);
            }
        });
        //wx.getLocation({
        //    type: opt.locationType, // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
        //    success: function (res) {
        //         var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
        //         var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
        //         var speed = res.speed; // 速度，以米/每秒计
        //         var accuracy = res.accuracy; // 位置精度
        //         opt.callBack(res);
        //        opt.wz(res);
        //    },
        //    cancel: function (res) {
        //        alert('用户拒绝授权获取地理位置');
        //    }
        //});
        wx.onMenuShareQQ({
            title: opt.title, // 分享标题
            desc: opt.desc, // 分享描述
            link: opt.link, // 分享链接
            imgUrl: opt.imgUrl, // 分享图标
            success: function (res) {
                // 用户确认分享后执行的回调函数
                opt.callBack(res);
            },
            cancel: function (res) {
                // 用户取消分享后执行的回调函数
                opt.callBack(res);
            }
        });
        wx.hideMenuItems({
            menuList: opt.hideMenuList // 要隐藏的菜单项，所有menu项见附录3
        });
    })
    function wxshareconfig() {
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: "wx3827070276e49e30", // 必填，公众号的唯一标识
            timestamp: wxsharemodel.config.TimeStamp, // 必填，生成签名的时间戳
            nonceStr: wxsharemodel.config.NonceStr, // 必填，生成签名的随机串
            signature: wxsharemodel.config.AddrSign,// 必填，签名，见附录1
            jsApiList: [
                'chooseWXPay',
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                //'onMenuShareQQ',
                //'onMenuShareWeibo',
                'hideMenuItems',
                'showMenuItems',
                'hideAllNonBaseMenuItem',
                'showAllNonBaseMenuItem',
                'translateVoice',
                'startRecord',
                'stopRecord',
                'onRecordEnd',
                'playVoice',
                'pauseVoice',
                'stopVoice',
                'uploadVoice',
                'downloadVoice',
                'chooseImage',
                'previewImage',
                'uploadImage',
                'downloadImage',
                'getNetworkType',
                'openLocation',
                'getLocation',
                'hideOptionMenu',
                'showOptionMenu',
                'closeWindow',
                'scanQRCode',
                'openProductSpecificView',
                'addCard',
                'chooseCard',
                'openCard'
            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
    }
};