
//上传单图
function UpFile(fileid) {
    //console.log(fileid);
    cleanalert("alertjs");
    showload("<img src='/Template/Common/images/load.gif'><br>正在努力上传图片。。", "upfileload");
    $.ajaxFileUpload({
        url: '/Receive/UpfileImage?sid=' + pagecon.sid,
        secureuri: false,
        fileElementId: fileid,
        dataType: 'json',
        beforeSend: function () {
        },
        complete: function () {
            //$("#upfileload").remove();
        },
        success: function (data, status) {
            cleanalert("upfileload");
            if (data == "-1") myalert("错误信息", "请选择上传文件！");
            if (typeof (data.error) != 'undefined') {
                if (data.error != '') {
                    myalert("错误信息", data.error);
                } else {
                    $("#" + fileid).attr('file', data.file);
                    $("#" + fileid).addClass("none_ok");
                    $("#" + fileid + "_img").removeClass("none_ok");
                    //myalert("提示信息", data.msg);
                }
            }
        },
        error: function (data, status, e) {
        }
    });
    $("#" + fileid).change(handleFileSelect);
    return false;
}
function handleFileSelect(evt) {
    var files = evt.target.files; //文件列表对象（经测试，即使"input"标签没设置“multiple”，返回的files还是数组）
    var f = files[0];
    //console.log(f.type);
    if (f.size > 2100000) {
        myalert("提示信息", "您的文件太大了，请先压缩到2M以内。");
        return;
    }
    if (f.type == "image/gif" || f.type == "image/jpg" || f.type == "image/jpeg" || f.type == "image/png" || f.type == "image/bmp") {
        var upfileid = $(this).attr("id");
        var imgusrl = getObjectURL(f);
        $("#" + upfileid + "_img img").attr("src", imgusrl);
        myconfirm("上传图片", "<img src='" + imgusrl + "' class='showimg'>", function() {
            UpFile(upfileid);
        });
    } else {
        myalert("提示信息", "只能上传图片！<br>扩展名为.jpg，.gif，.png和.bmp的文件");
    }
}
//建立一個可存取到該file的url，用于预览
function getObjectURL(file) {
    var url = null;
    if (window.createObjectURL != undefined) { // basic
        url = window.createObjectURL(file);
    } else if (window.URL != undefined) { // mozilla(firefox)
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}
//重新上传
function reupfile(fileid) {
    $("#" + fileid + "_img").addClass("none_ok");
    $("#" + fileid).removeClass("none_ok");
}
//初始化用户资料
function setmember(objid) {
    if(pagecon.mid!=0) {
        $.get("/PageSection/GetMemberEdit", { sid: pagecon.sid }, function (dt) {
            if (dt != "-1") {
                var m = JSON.parse(dt);
                //console.log(m.realname);
                if (m.realname != "") $(objid).find("#name").val(m.realname);
                if (m.mobile != "") $(objid).find("#phonenumber").val(m.mobile);
                if (m.address != "") $(objid).find("#address").val(m.address);
                if (m.sex == "1") $(objid).find("#sex option[value=男]").attr("selected", true);
                if (m.sex == "2") $(objid).find("#sex option[value=女]").attr("selected", true);
            }
        });
    }
}