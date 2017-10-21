
$(document).ajaxSend(function(){
    if($("#loading-mask").length == 0){
        $("body").append('<div id="loading-mask" loadNum="0" style="z-index: 99999;width: 100%;height: 100%;position: fixed;background: rgba(0,0,0,0);z-index: 1000;left: 0px;top: 0px;"><div class="t-center" style="text-align: center;width: 100px;padding: 10px;position:absolute;top: 50%;margin-top: -72px;left: 50%;margin-left: -60px;background: rgba(0,0,0,0.5);color:#fff;border-radius:5px;-webkit-border-radius:5px;-moz-border-radius:5px;z-index: 1001;"><img src="'+resroot+'images/loading.gif" style="display: inline-block;width: auto;"><p style="color: #FAF0F0;position: static;">正在加载...</p></div></div>');
    }else{
        $("#loading-mask").show();
    }
    $("#loading-mask").attr("loadNum",parseInt($("#loading-mask").attr("loadNum")) + 1);
}).ajaxComplete(function(){
    var loadNum = parseInt($("#loading-mask").attr("loadNum")) - 1;
    if(loadNum == 0){
        $("#loading-mask").hide();
    }
    $("#loading-mask").attr("loadNum", loadNum );
}).ajaxError(function(e, xhr, settings, exception) {
//	showMsg({"msg": "ajaxError"});
    xh_ajaxError();
});

function xh_ajaxError(){
//    $("body").append('<div class="error" style="position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 10000;text-align: center;background:rgba(19,19,19,0.5) none repeat scroll !important;"><section class="img" style="width: 100%;position: fixed;top: 45%;margin-top: -90px;background-color:#fff"><h2 style="text-align:left;font-size:15px;font-weight:normal;border-bottom:2px solid #2d7afe;height:40px;line-height:40px;margin:0;padding:0 10px;">温馨提示</h2><img src="'+resroot+'images/error.jpg" alt="" style="width: 160px;height: 30px;margin:45px auto 60px;"/></section></div>');
}

//通用提示信息js
function showMsg(setting){
    var msg = setting.msg || "",
        afterFn = setting.afterFn || null,
        closeTime = setting.closeTime || 3000,
        buttom = setting.buttom || "40px";
    if($("#xhPopup").length<=0){
	    $("body").append('<div id="xhPopup" style="z-index:1091;left:0px; bottom: 60px; position: fixed;width:60%"><div></div></div>');
	    //$("body").append('<div id="xhPopup" style="-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box;position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 10000;text-align: center;"></div>');
    }
    var xhPopupitem = $('<div style="-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box;padding:0 5%;text-align:center;width: 100%;"><label style="display: inline-block;padding: 10px 20px;font-size: 15px;background:rgba(19,19,19,0.88) none repeat scroll !important;color: #fff;line-height:22px;border-radius:5px;-webkit-border-radius:5px;-moz-border-radius:5px;width:100%;">'+msg+'</label></div>');
    
    var xhPopupitems=$("#xhPopup").children();
   	xhPopupitem.insertBefore(xhPopupitems[0]);
    xhPopupitem.fadeIn(500);
    setTimeout(function(){
        xhPopupitem.fadeOut(500, function(){
            xhPopupitem.remove();
            if(afterFn != null) afterFn();
        });
    },closeTime);
}
var errorCodeData = {
    100:"您已无抽奖机会",
    101:"活动还在建设中",
    102:"未找到指定活动",
    103:"本活动未开始或已过期",
    104:"本次活动需要使用串码",
    105:"串码抽奖活动已失效",
    106:"pwdCode无效",

    201:"串码无效",
    202:"批次无效",
    203:"串码活动已结束，请重新输入",
    204:"串码已被领取",
    205:"串码尚未领取，无法使用",
    206:"串码已使用",

    301:"无法获得会员身份",
    306:"此手机号码已经领取过会员卡了",

    801:"已有尚未处理交换名片请求",
    802:"交换名品请求已被拒绝",
    803:"已经交换过名片",
    804:"无处理交换名片请求",
    805:"无法和自己交换名片"
};
function errorCode(code){
    var msg = errorCodeData[code] || "系统繁忙请稍后再试";
    return msg;
}

function shortenURL(account,url){
    var shortUrl = url;
    $.ajax({
        type: "post",
        async:false,
        url: "/as/"+account+"/shortenURL.json",
        data: {"account": account,"url":url},
        success: function (jsonData) {
            shortUrl = jsonData.shortUrl || url;
        }
    });
    return shortUrl;
}

function reloadImg(doc) {
    doc.each(function (i, e) {
        $(e).attr("src", $(e).attr("data-src")).fadeIn(1000);
    });
}