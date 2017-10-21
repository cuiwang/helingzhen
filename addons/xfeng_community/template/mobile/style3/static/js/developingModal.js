/**
 * Created by zhoufeng on 16/8/31.
 */
/**
 * Created by Administrator on 2016/5/26.
 */

$(function () {
    devModal.bindEvent();
    devModal.bindAppDownload();
});
/*功能正在开发中弹出层*/
var devModal = {
    bindEvent: function () {
        $(".cd-popupclose,.shade,.cd-popup-container a,#wait_a_while").unbind("click").click(function () {
            $(".shade").filter(".dev").hide();
            $(".cd-popup-container").filter(".dev").hide();
        });

        $(".developing").unbind("click").click(function () {
            $(".shade").filter(".dev").show();
            $(".cd-popup-container").filter(".dev").show();
        });
    },
    bindAppDownload: function () {
        $(".app-download").unbind("click").click(function () {
            window.location.href = path + "/app/appDownloadPage.do";
        });
    }
}


