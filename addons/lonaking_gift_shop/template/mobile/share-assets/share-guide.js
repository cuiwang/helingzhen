/**
 * Created by leon on 15/9/28.
 */

function button1(){
    $("#mcover").css("display","block")    // 分享给好友按钮触动函数
}
function button2(){
    $("#mcover").css("display","block")  // 分享给好友圈按钮触动函数
}
function weChat(){
    $("#mcover").css("display","none");  // 点击弹出层，弹出层消失
}
$(function(){
    setTimeout(function () {
        $("#mcover").show();}, 6000);   // 6000时毫秒是弹出层
    setTimeout(function () {
        $("#mcover").hide(); }, 8000);    //8000毫秒是隐藏层
})