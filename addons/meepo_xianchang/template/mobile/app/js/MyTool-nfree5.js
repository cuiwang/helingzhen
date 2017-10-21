var MyTool = { ID: "MyTool_", Root: "/js/MyTool/" };
MyTool.ShadowID = MyTool.ID + "shadow";
MyTool.LoadingID = MyTool.ID = "dlgLoading";

MyTool.DlgBoxHtml = function (id, ctHtml) {
    return ('<div id="' + id + '" style="font-size: 1rem;display:block;position:fixed;width:100%;height:100%;z-index:99010;left:0;top:0;text-align:center;" >' + ctHtml + '</div>')
};
MyTool.DlgContentHtml = function (ctHtml) {
    var opacity = 0.7;
    if ($(".dlg_ct_box_area").length > 0) {
        opacity = 1;
    }
    var resHtml = '<div class="dlg_ct_box_area" style="border: solid 1px;padding: 20px;color: #fff;position: absolute;display: inline-block;top: 0;left: 0;margin-top: -30px;margin-left: -55px;border-radius: 5px;max-width:80%;">'
                + '<div style="position:absolute;z-index:-1;width:100%;height:100%;left:0;top:0;background-color:rgb(45,45,45);filter:alpha(opacity=' + (opacity * 100) + ');-moz-opacity:' + opacity + ';-khtml-opacity:' + opacity + ';opacity:' + opacity + ';"></div>'
                + ctHtml + '</div>';
    return resHtml;
};
MyTool.ShadowShow = function () {
    var $Html = $('<div id="' + this.ShadowID + '" style="position:fixed;width:100%;height:100%;left:0;top:0;z-index:99000;filter:alpha(opacity=50);-moz-opacity:0.5;-khtml-opacity: 0.5;opacity: 0.5;background-color:#000;"></div>');
    $("body").append($Html);
};
MyTool.ShadowClose = function () {
    $("#" + this.ShadowID).remove();
}
MyTool.LoadingShow = function () {
    var ctHtml = '<img style="top:50%;margin-top:-64px;left:50%;margin-left:-64px;position:absolute;" src="' + MyTool.Root + 'alert-loading.gif" />';
    var $dlgHtml = $(this.DlgBoxHtml(this.LoadingID, ctHtml));
    MyTool.ShadowShow();
    $("body").append($dlgHtml);
    return $dlgHtml;
};
MyTool.LoadingClose = function () {
    this.ShadowClose();
    $("#" + this.LoadingID).remove();
};
MyTool.Alert = function (msg, func_yes) {
    var alertID = this.ID + "dlgAlert";
    var ctHtml = '<div style="padding:10px 10px 20px 10px;min-width:100px;">' + msg + '</div><button type="button" class="dlg_bt_sure" style="display:inline-block;border:solid 1px;padding: 5px 20px;cursor: pointer;border-radius: 5px;" >确定</button>';
    var $dlgHtml = $(this.DlgBoxHtml(alertID, this.DlgContentHtml(ctHtml)));
    this.ShadowShow();
    this.AppendDlg($dlgHtml);
    $dlgHtml.find(".dlg_bt_sure").focus();
    $dlgHtml.find(".dlg_bt_sure").click(function () {
        if (typeof (func_yes) == "function") {
            var res = func_yes();
            if (res || res == undefined) {
                MyTool.ShadowClose();
                $dlgHtml.remove();
            }
        } else {
            MyTool.ShadowClose();
            $dlgHtml.remove();
        }

    });
};
MyTool.AlertRedirect = function (msg, second, url) {
    var alertID = this.ID + "dlgAlertRedirect";
    var ctHtml = '<div style="padding:10px 10px 20px 10px;min-width:100px;">' + msg + '<span class="dlg_msg_second" style="display:inline-block;width:30px;">' + second + '</span>秒后关闭</div>'
                + '<button type="button" class="dlg_bt_sure" style="display:inline-block;border:solid 1px;padding: 5px 20px;cursor: pointer;border-radius: 5px;" >确定</button>';
    var $dlgHtml = $(this.DlgBoxHtml(alertID, this.DlgContentHtml(ctHtml)));
    this.ShadowShow();
    this.AppendDlg($dlgHtml);
    $dlgHtml.find(".dlg_bt_sure").focus();
    $dlgHtml.find(".dlg_bt_sure").click(function () {
        window.location = url;
    });
    var $second = $dlgHtml.find(".dlg_msg_second");
    var tid = setInterval(function () {
        if (second <= 1) {
            clearInterval(tid);
            window.location = url;
        } else {
            second--;
            $second.text(second);
        }

    }, 1000);
};
MyTool.IframeClose = function () { };
MyTool.IframeShow = function (width, height, url, scroll) {
    var dlgID = this.ID + "dlgIframe";
    var scrollType = scroll ? "auto" : "no";
    var ctHtml = '<div style="color:#fff;position:absolute;display:inline-block;top:50%;margin-top:-' + (height / 2 + 30) + 'px;left:50%;margin-left:-' + (width / 2 + 30) + 'px;border-radius:5px;" >'
                + '<div style="position:absolute;z-index:-1;width:100%;height:100%;left:0;top:0;background-color:rgb(45,45,45);filter:alpha(opacity=70);-moz-opacity:0.7;-khtml-opacity: 0.7;opacity: 0.7;"></div>'
                + '<div style="position: relative; height: 30px;line-height: 30px;"><span style="position: absolute;right:10px;">[<a class="dlg_btn_close" style="cursor: pointer;color: #fff;text-decoration: none;display: inline-block;">&nbsp;关闭&nbsp;</a>]</span></div>'
                + '<div style="padding:10px;min-width:100px;"><iframe src="' + url + '" width="' + width + '" height="' + height + '" scrolling="' + scrollType + '"></iframe></div></div>';
    var $dlgHtml = $(this.DlgBoxHtml(dlgID, ctHtml));
    this.ShadowShow();
    $("body").append($dlgHtml);

    $dlgHtml.find(".dlg_btn_close").click(function () {
        MyTool.IframeClose();
    });
    this.IframeClose = function () {
        MyTool.ShadowClose();
        $dlgHtml.remove();
    }
};
MyTool.Confirm = function (msg, func_yes, func_no) {
    var dlgID = this.ID + "dlgConfirm";
    var ctHtml = '<div style="padding:10px 10px 20px 10px;min-width:100px;">' + msg + '</div>'
                + '<button type="button" class="dlg_bt_sure" style="display:inline-block;border:solid 1px;padding: 5px 20px;cursor: pointer;border-radius: 5px;margin:0 5px 0 5px;" >确定</button>'
                + '<button type="button" class="dlg_bt_cancel" style="display:inline-block;border:solid 1px;padding: 5px 20px;cursor: pointer;border-radius: 5px;margin:0 5px 0 5px;" >取消</button>';
    var $dlgHtml = $(this.DlgBoxHtml(dlgID, this.DlgContentHtml(ctHtml)));
    this.ShadowShow();
    this.AppendDlg($dlgHtml);
    $dlgHtml.find(".dlg_bt_sure").click(function () {
        if (typeof (func_yes) == "function") {
            func_yes();
        }
        MyTool.ShadowClose();
        $dlgHtml.remove();
    });
    $dlgHtml.find(".dlg_bt_cancel").click(function () {
        if (typeof (func_no) == "function") {
            func_no();
        }
        MyTool.ShadowClose();
        $dlgHtml.remove();
    });
};
MyTool.AppendDlg = function ($dlgHtml) {
    $("body").append($dlgHtml);
    var $Box = $dlgHtml.find(".dlg_ct_box_area");
    var m_l = -$Box.outerWidth() / 2;
    var m_t = -$Box.outerHeight() / 2;
    $Box.css({ "left": "50%", "top": "50%", "margin-left": m_l, "margin-top": m_t });
}