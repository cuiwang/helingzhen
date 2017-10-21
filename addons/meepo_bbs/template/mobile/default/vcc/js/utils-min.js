function ajaxOper(url, type, data, dataType, success, async) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: dataType,
        success: success,
        async: async
    })
}
function GetQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null ) {
        return unescape(r[2])
    }
    return ""
}
function Popup(state, msg) {
    var img = "http://i.pengxun.cn/content/images/cg.jpg";
    if (state == "0") {
        img = "http://i.pengxun.cn/content/images/cw.gif"
    }
    layer.open({
        content: '<div style="height:30px;line-height:30px;color:#666">' + '<img src="' + img + '" width="30" height="30" style="float:left" />' + msg + "</div>",
        style: " border:none;",
        time: 1
    })
}
function WeUIPopMsg(state, msg) {
    var temp = "";
    if (1 == state) {
        temp = "操作成功";
        statetext = "success"
    } else {
        temp = "操作失败";
        statetext = "error"
    }
    layer.open({
        content: '<div class="weui_msg"><div class="weui_icon_area"><i class="weui_icon_' + statetext + ' weui_icon_msg"></i></div>' + '<div class="weui_text_area"><h2 class="weui_msg_title">' + temp + "</h2>" + '<p class="weui_msg_desc">' + msg + "</p></div>" + '<div class="weui_opr_area"><p class="weui_btn_area"><a href="javascript:;" class="weui_btn weui_btn_primary">确定</a></p></div><div="weui_extra_area">' + "</div></div>",
        style: " border:none;",
        time: 2
    })
}
function GoHref(vUrl) {
    location.href = vUrl
}
function showdiv(obj) {
    $("#" + obj).show()
}
function showOrHide(obj) {
    $("#" + obj).toggle()
}
function GetCookies(name, value, options) {
    if (typeof value != "undefined") {
        options = options || {};
        if (value === null ) {
            value = "";
            options.expires = -1
        }
        var expires = "";
        if (options.expires && (typeof options.expires == "number" || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == "number") {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000))
            } else {
                date = options.expires
            }
            expires = "; expires=" + date.toUTCString()
        }
        var path = options.path ? "; path=" + options.path : "";
        var domain = options.domain ? "; domain=" + options.domain : "";
        var secure = options.secure ? "; secure" : "";
        document.cookie = [name, "=", encodeURIComponent(value), expires, path, domain, secure].join("")
    } else {
        var cookieValue = null ;
        if (document.cookie && document.cookie != "") {
            var cookies = document.cookie.split(";");
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                if (cookie.substring(0, name.length + 1) == (name + "=")) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break
                }
            }
        }
        return cookieValue
    }
}
function layerClose() {
    layer.closeAll()
}
function isWeiXin() {
    var ua = window.navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == "micromessenger") {
        return true
    } else {
        return false
    }
}
function showLoadingUI(vText) {
    layer.open({
        type: 2,
        content: vText
    })
}
function vzanShowTips(vtitle, vmsg) {
    layer.open({
        title: [vtitle, "background-color:red; color:#fff;"],
        content: vmsg
    })
}
function vzanPostIM(jsonData) {
    $.ajax({
        type: "POST",
        url: "/msg/rmsg",
        data: {
            uid: jsonData.uid,
            tuid: jsonData.tuid,
            cts: jsonData.cts,
            id: jsonData.id,
            tid: jsonData.tid,
            siteid: jsonData.siteid,
            types: jsonData.types,
            stype: jsonData.stypes,
            title: jsonData.title,
            openid: jsonData.msgopenId,
            unionid: jsonData.unionId,
            mtips: jsonData.mtips
        },
        dataType: "json",
        success: function(data) {}
    })
}
function vzanPostIMDels(siteid, atid, tpid, types, uid) {
    $.ajax({
        type: "POST",
        url: "/msg/dels",
        data: {
            uid: uid,
            siteid: siteid,
            atid: atid,
            types: types,
            tpid: tpid
        },
        dataType: "json",
        success: function(data) {}
    })
}
function IsNullOrEmpty(str) {
    if (str == "" || str == null  || str == undefined || str == "undefined") {
        return true
    } else {
        return false
    }
}
function getimgsize(vurl, vsize) {
    if (vurl.indexOf("wx.qlogo") > -1) {
        return vurl.substring(0, vurl.lastIndexOf("/") + 1) + vsize
    } else {
        return vurl
    }
}
function maopaomsg(vMsg) {
    layer.open({
        shade: false,
        time: 1,
        content: vMsg,
        style: "color:forestgreen;text-align:center;font-size:20px;"
    })
}
;