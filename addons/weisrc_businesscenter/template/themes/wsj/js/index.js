//电话
var issignin = 0;
function shownotel(t) {
    if (t == 1)
        myalert("提示信息", "商家还没有设置电话哦！");
    else
        myalert("提示信息", "获取商家电话失败，请重试！");
}
//地址(坐标，标题，地址)
function getadd(l, title, content) {
    if (l != "") {
        var latlan = l.split(",");
        return "http://api.map.baidu.com/marker?location=" + latlan[1] + "," + latlan[0] + "&title=" + title + "&content=" + content + "&output=html";
    } else {
        return "javascript:showadd(\"" + content + "\");";
    }
}

function showadd(m) {
    if(m!="")
        myalert("提示信息", "商家未设置坐标，暂时不能查看地图哦！<br>商家地址：" + m);
    else
        myalert("提示信息", "商家未设置坐标，暂时不能查看地图哦！");
}
function showqq(t) {
    if (t == 0)
        myalert("提示信息", "商家未设置客服QQ，无法在线联系商家！");
    else
        myalert("提示信息", "商家客服QQ设置错误，请用其他方法联系商家！");
}
//签到
function signin() {
    if (pagecon.mid == 0) {
        myalert("提示信息", "请先登陆！");
        return;
    }
    if (issignin == 1) {
        myalert("提示信息", "您已经签到过了！");
        return;
    }
    if (issignin == 2) {
        myalert("提示信息", "商家配置错误，请联系商家！");
        return;
    }
    if (issignin == 3) {
        myalert("提示信息", "今日签到已满，请明天赶早^_^！");
        return;
    }
    if (issignin == 4) {
        myalert("提示信息", "会员信息有误，请重新关注！");
        return;
    }
    if (issignin == -1) {
        myalert("提示信息", "签到失败！");
        return;
    }
    $.get("/Receive/SignIn", { mid: pagecon.mid, sid: pagecon.sid, pid: pagecon.pid }, function (data) {
        if (parseInt(data) >= 0) {
            myalert("提示信息", "签到成功！");
            issignin = 1;
            return;
        }
        if (parseInt(data) == -1) {
            myalert("签到失败", "您已经签到过了！");
            issignin = 1;
            return;
        }
        if (parseInt(data) == -2) {
            myalert("签到失败", "商家配置错误，请联系商家！");
            issignin = 2;
            return;
        }
        if (parseInt(data) == -3) {
            myalert("签到失败", "今日签到已满，请明天赶早^_^！");
            issignin = 3;
            return;
        }
        if (parseInt(data) == -4) {
            myalert("签到失败", "会员信息有误，请重新关注！");
            issignin = 3;
            return;
        }
        myalert("提示信息", "签到失败！");
        issignin = -1;
    });
}