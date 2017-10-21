function gowxOauthInfo(vfId, vAct) {
    var vurl = window.location.href;
    var baseurl = "";
    baseurl = vurl;
    if (vurl.indexOf("#") > -1) {
        baseurl = vurl.substr(0, vurl.indexOf("#"))
    }
    var vcurrentUrl = "";
    if (baseurl.indexOf("?") > 0) {
        vcurrentUrl = baseurl + "&act=" + vAct
    } else {
        vcurrentUrl = baseurl + "?act=" + vAct
    }
    vcurrentUrl = (vcurrentUrl + "|" + Math.random().toString());
    var vredirectUrl = encodeURI($("#hidwsqurl").val() + "/wx/wxbackinfo-" + vfId);
    var vAppId = $("#hidwxappid").val();
    var oauthUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" + vAppId + "&redirect_uri=" + vredirectUrl + "&response_type=code&scope=snsapi_userinfo&state=" + vcurrentUrl + "#wechat_redirect";
    window.location.href = oauthUrl
}
function GoWxAuthRedirect(vfId, vAct) {
    var vurl = window.location.href;
    var baseurl = "";
    baseurl = vurl;
    if (vurl.indexOf("#") > -1) {
        baseurl = vurl.substr(0, vurl.indexOf("#"))
    }
    var vcurrentUrl = "";
    if (baseurl.indexOf("state=") > 0 || baseurl.indexOf("code=") > 0) {
        baseurl = baseurl.replace("state=", "temp_state=").replace("code=", "temp_code=")
    }
    if (baseurl.indexOf("?") > 0) {
        vcurrentUrl = baseurl + "&act=" + vAct + "&goauth=1"
    } else {
        vcurrentUrl = baseurl + "?act=" + vAct + "&goauth=1"
    }
    window.location.href = vcurrentUrl
}
function previewimgs(vcurrent, vurllist) {
    var urlsss = vurllist.split(",");
    wx.previewImage({
        current: vcurrent,
        urls: urlsss
    })
}
$(document).ready(function() {
    if (GetQueryString("act") != "") {
        $("#replybtn-" + GetQueryString("act")).click()
    }
    wx.ready(function() {
        if ($("#hidShareInfo").length > 0) {
            var vzanShareInfo = eval("(" + $("#hidShareInfo").val() + ")");
            wx.onMenuShareAppMessage({
                title: vzanShareInfo.title,
                desc: vzanShareInfo.desc,
                link: vzanShareInfo.link,
                imgUrl: vzanShareInfo.imgUrl,
                success: function() {
                    postsharedata($("#hiduserId").val(), $("#hidfId").val(), $("#hidArtId").val(), vzanShareInfo.link, "WeiXinApp")
                }
            });
            wx.onMenuShareTimeline({
                title: vzanShareInfo.title,
                link: vzanShareInfo.link,
                imgUrl: vzanShareInfo.imgUrl,
                success: function() {
                    postsharedata($("#hiduserId").val(), $("#hidfId").val(), $("#hidArtId").val(), vzanShareInfo.link, "WeiXinFriends")
                }
            });
            wx.onMenuShareQQ({
                title: vzanShareInfo.title,
                desc: vzanShareInfo.desc,
                link: vzanShareInfo.link,
                imgUrl: vzanShareInfo.imgUrl,
                success: function() {
                    postsharedata($("#hiduserId").val(), $("#hidfId").val(), $("#hidArtId").val(), vzanShareInfo.link, "QQApp")
                }
            });
            wx.onMenuShareQZone({
                title: vzanShareInfo.title,
                desc: vzanShareInfo.desc,
                link: vzanShareInfo.link,
                imgUrl: vzanShareInfo.imgUrl,
                success: function() {
                    postsharedata($("#hiduserId").val(), $("#hidfId").val(), $("#hidArtId").val(), vzanShareInfo.link, "QQZone")
                }
            });
            wx.onMenuShareWeibo({
                title: vzanShareInfo.title,
                desc: vzanShareInfo.desc,
                link: vzanShareInfo.link,
                imgUrl: vzanShareInfo.imgUrl,
                success: function() {
                    postsharedata($("#hiduserId").val(), $("#hidfId").val(), $("#hidArtId").val(), vzanShareInfo.link, "Weibo")
                }
            })
        }
    })
});
function postsharedata(vUserId, vFId, vArtId, vShareUrl, vPosition) {
    $.ajax({
        type: "POST",
        url: "/ajax/addshare",
        data: {
            UserId: vUserId,
            MinisnsId: vFId,
            ArticleId: vArtId,
            SharePosition: vPosition,
            ShareUrl: vShareUrl
        },
        dataType: "json",
        success: function(data) {
            if (data.code == 1) {
                maopaomsg("分享成功,积分+8")
            }
        }
    })
}
;