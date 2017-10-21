//预约模板一 Tid=17
var eid = 0,
    canuser = 0,
    isenroll = 0; //预约次数 -1已报不能再预约 0不限制次数 >0次数
var starttime;
var endtime;

//提交表单
function checkform() {
    if (isenroll == -1) {
        myalert("提示信息", "您已经预约过了，不能重复预约！");
        return false;
    }
    var file = $(".need");
    var msg = "";
    var ok = 1;
    var number = $("input[type=number]");
    var regnum = /^\d+$/;
    $.each(number, function (i, o) {
        if ($(o).val() != "") {
            if (!$(o).val().match(regnum)) {
                msg = msg + "请输入大于0的整数<br>";
                $(o).addClass("inputred");
                ok = 0;
            }
        }
    });
    $.each(file, function (i, o) {
        if ($(o).attr("type") == 2) {
            if ($(o).text() == "" || $(o).text() == $(o).attr("placeholder")) {
                //msg = msg + $(o).attr("placeholder") + "<br>";
                $(o).addClass("inputred");
                $(o).text($(o).attr("placeholder"));
                ok = 0;
                $(o).focus();
                return;
            }
        } else if ($(o).attr("type") == 5) {
            if ($(o).find(":checked").length == 0) {
                //msg = msg + $(o).attr("placeholder") + "<br>";
                $(o).addClass("inputred");
                ok = 0;
            }
        } else if ($(o).attr("type") == "file") {
            if ($(o).attr("file") == undefined || $(o).attr("file") == "") {
                //msg = msg + $(o).attr("placeholder") + "<br>";
                $(o).addClass("inputred");
                ok = 0;
            }
        } else {
            if ($(o).val() == "") {
                //msg = msg + $(o).attr("placeholder") + "<br>";
                $(o).addClass("inputred");
                ok = -1;
                $(o).focus();
                return false;
            }
        }
    });
    if ($("#tel").val()) {
        var telz = /^((\+?86)|(\(\+86\)))?0\d{2,3}-?\d{7,8}(-\d{3,4})?$/;
        var mobz = /^((\+?86)|(\(\+86\)))?(13[0-9]|15[0-9]|18[0-9]|17[0-9]|14[57])[0-9]{8}$/;
        if (telz.test($("#tel").val()) || mobz.test($("#tel").val())) {
        }
        else {
            msg = msg + "请输入正确的电话号码！<br>";
            $("#tel").addClass("inputred");
            ok = 0;
        }
    }
    if (ok == -1) return false;
    if (ok == 0) {
        if (msg != "") {
            myalert("提示信息", msg);
            return false;
        }
    } else {
        var postdata = {
            edid: pagecon.tid,
            eid: eid,
            mid: 0,
            sid: pagecon.sid,
            name: "",
            sex: "0",
            phonenumber: "",
            address: "",
            enrolldate: "",
            enrolltime: "",
            productname: "",
            data: ""
        };

        if (postdata.sex == "男") postdata.sex = "1";
        if (postdata.sex == "女") postdata.sex = "2";
        if (data.length > 2) postdata.data = "{" + data.substring(0, data.length - 1) + "}";
        isenroll--;
        if (isenroll == 0) isenroll = -1;

        return true;
//        $.post("/Receive/AddEnrollData", postdata, function (dat1) {
//            if (dat1 > 0) {
//                myalerttoone("提示信息", "恭喜您，预约成功！");
//            } else {
//                if (dat1 == 0)
//                    myalert("提示信息", "Sorry，您预约失败了！<br>请重试或联系商家！");
//                else if (dat1 == -1)
//                    myalert("提示信息", "Sorry，预约失败，预约已结束！");
//                else if(dat1 == -2)
//                    myalert("提示信息", "Sorry，预约失败，预约尚未开始！");
//                else if (dat1 == -3)
//                    myalert("提示信息", "Sorry，预约失败，预约未上线！");
//                else if (dat1 == -4)
//                    myalert("提示信息", "Sorry，预约失败，预约只允许会员参加！");
//                else if (dat1 == -5)
//                    myalert("提示信息", "Sorry，预约失败，预约已经达到最大次数！");
//            }
//        });
    }
}