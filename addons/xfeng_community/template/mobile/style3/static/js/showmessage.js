/**
 * Created by zhoufeng on 16/8/31.
 */
/*$(function(){
 message.init();
 });

 var message = {
 init:function(){
 var element = document.createElement("div").setAttribute("class", "added");
 $("body").append(element);
 }

 };
 function showMessage(message){
 $("div.added").html("<p>"+message+"</p>");
 $("div.added").show();
 setTimeout(function(){
 $("div.added:first").remove();
 },1000);
 }*/

function showMessage(message){
    $("body").append("<div class='added' style=\"z-index:99999;\"><p>"+message+"<p></div>");
    setTimeout(function(){$("div.added:first").remove();},1000);
}

function showNoData(message){
    $(".unhappy").remove();
    $("body").append("<div class=\"unhappy\"><i></i><p>"+message+"</p></div>");
    $("li").click(function(){
        $(".unhappy").remove();
    });
}

function removeShowNoData(){
    $(".unhappy").remove();
}


/**
 * 公共方法
 * 获取两位小数
 */
function fixed(num){
    if(num == null || num == "" || isNaN(num)){
        return "0.00";
    }
    return num.toFixed(2);
}

/**
 * 电话验证
 * @param phone
 * @returns {Boolean}
 */
function isPhone(phone){
    //var mobile = /^0?1[3|4|5|6|7|8][0-9]\d{8}$/;
    //var mobile = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
    var mobile = /^[1-9]\d{10}$/;
    if(mobile.test(phone)){
        return true;
    }
    return false;
}
/**
 * 电话验证
 * @param phone
 * @returns {Boolean}
 */
function isNotPhone(phone){
    return !isPhone(phone);
}


