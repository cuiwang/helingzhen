
var shareModel;
var aid;
var config;
(function () {
    aid = location.href.split("/vshop/")[1].split("/")[0];
    if (Number(aid).toString() == "NaN")
        aid = "68680";
    FXhide();
    $.ajax({
        type: "POST",
        url: "/vshop/" + aid + "/Ajax/Post/GetJSAPIConfig",
        timeout: 30000,
        data: {
            "url": window.location.href
        },
        async: false,
        success: function (res) {
            if (res.Status == 0) {
                wxConfig(res.Data.Config);
                config = res.Data.Config;
                shareModel = res.Data.ShareModel;
                FXShow();
            }
        },
        dataType: "json"
    })
})();

wx.ready(function () {
    //这个img用来如果数据库里没有填写图片链接,则用第一张<img>标签的图片
    var imgs = $("img");
    var img = "";
    if (imgs && imgs.length > 0 && imgs[0]) {
        img = $(imgs[0]).attr("src");
    }
    shareModel.APPShareImg = shareModel.APPShareImg || img;
    shareModel.FriendShareImg = shareModel.FriendShareImg || img;
    shareModel.FriendShareImg = shareModel.QQShareImg || img;
    shareModel.FriendShareImg = shareModel.WeiboShareImg || img;
});

//配置微信
function wxConfig(conf) {
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: conf.AppId, // 必填，公众号的唯一标识
        timestamp: conf.Timestamp, // 必填，生成签名的时间戳
        nonceStr: conf.NonceStr, // 必填，生成签名的随机串
        signature: conf.Signature,// 必填，签名，见附录1
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
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
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
}

function FXhide() {
    wx.ready(function () {
        wx.hideOptionMenu();
    });
}

function FXShow() {
    wx.ready(function () {
        wx.showOptionMenu();
    });
}

function shareApp(shareModel) {
    wx.ready(function () {
        wx.onMenuShareAppMessage({
            title: shareModel.APPShareTitle, // 分享标题
            desc: shareModel.APPShareDes, // 分享描述
            link: shareModel.AppShareLink, // 分享链接
            imgUrl: shareModel.APPShareImg, // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function (res) {
                // 用户确认分享后执行的回调函数
                weiKeShare();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
}

function shareFr(shareModel) {
    wx.ready(function () {
        //分享到朋友圈
        wx.onMenuShareTimeline({
            title: shareModel.FriendShareTitle, // 分享标题
            link: shareModel.FriendShareLink, // 分享链接
            imgUrl: shareModel.FriendShareImg || img, // 分享图标
            success: function (res) {
                // 用户确认分享后执行的回调函数
                weiKeShare();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
}

function shareQQ(shareModel) {
    wx.ready(function () {
        //分享给QQ
        wx.onMenuShareQQ({
            title: shareModel.QQShareTitle, // 分享标题
            desc: shareModel.QQShareDesc, // 分享描述
            link: shareModel.QQShareLink, // 分享链接
            imgUrl: shareModel.QQShareImg || img, // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                weiKeShare();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
}

function shareWeibo(shareModel) {
    wx.ready(function () {
        //发送到腾迅微博
        wx.onMenuShareWeibo({
            title: shareModel.WeiboShareTitle, // 分享标题
            desc: shareModel.WeiboShareDes, // 分享描述
            link: shareModel.WeiboShareLink, // 分享链接
            imgUrl: shareModel.WeiboShareImg || img, // 分享图标
            success: function () {
                weiKeShare();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
}

function weiKeShare() {
    var id = window.goodsId || "0";
    if (shareModel && config.IsWeike) {
        $.ajax({
            type: "POST",
            url: "/vshop/" + aid + "/Ajax/Post/WeikeShare",
            timeout: 30000,
            data: {
                "goodsId": id
            },
            async: false,
            success: function (res) {
                if (res.Status == 0) {
                    wxConfig(res.Data.Config);
                    shareModel = res.Data.ShareModel;
                    FXShow();
                }
            },
            dataType: "json"
        })
    }
}

function wxPay(model, func, orderInfo) {
    wx.chooseWXPay({
        timestamp: model.timestamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
        nonceStr: model.nonceStr, // 支付签名随机串，不长于 32 位
        package: model.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
        signType: model.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
        paySign: model.paySign, // 支付签名
        success: function (res) {
            // 支付成功后的回调函数
            func(orderInfo);
        }
    });
}