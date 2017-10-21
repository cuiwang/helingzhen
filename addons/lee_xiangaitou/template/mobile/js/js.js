 //JavaScript Document
//我的奖品--1等奖
$(function(){
var w1=document.documentElement.clientWidth;
var h1=document.documentElement.clientHeight;
var img_h=1040*(w1/640);
document.getElementById("bg_1deng").style.top= img_h*0+ 'px';
document.getElementById("bg_1deng").style.left= w1*0+ 'px';
document.getElementById("tk_1deng").style.top= img_h*0.33+ 'px';
document.getElementById("ljfs_1deng").style.top= img_h*0.75+ 'px';
document.getElementById("dhm_1deng").style.top= img_h*0.663+ 'px';
document.getElementById("btn_return1").style.top= img_h*0.92+ 'px';

 });

//我的奖品--2等奖
$(function(){
var w1=document.documentElement.clientWidth;
var h1=document.documentElement.clientHeight;
var img_h=1040*(w1/640);
document.getElementById("bg_2deng").style.top= img_h*0+ 'px';
document.getElementById("bg_2deng").style.left= w1*0+ 'px';
document.getElementById("tk_2deng").style.top= img_h*0.33+ 'px';
document.getElementById("ljfs_2deng").style.top= img_h*0.75+ 'px';
document.getElementById("dhm_2deng").style.top= img_h*0.663+'px';
document.getElementById("btn_return2").style.top= img_h*0.92+ 'px';
 });

//我的奖品--3等奖
$(function(){
var w1=document.documentElement.clientWidth;
var h1=document.documentElement.clientHeight;
var img_h=1040*(w1/640);
document.getElementById("bg_3deng").style.top= img_h*0+ 'px';
document.getElementById("bg_3deng").style.left= w1*0+ 'px';
document.getElementById("tk_3deng").style.top= img_h*0.33+ 'px';
document.getElementById("ljfs_3deng").style.top= img_h*0.75+ 'px';
document.getElementById("dhm_3deng").style.top= img_h*0.665+ 'px';
document.getElementById("btn_return3").style.top= img_h*0.92+ 'px';
 });

//我的奖品--4等奖
$(function(){
var w1=document.documentElement.clientWidth;
var h1=document.documentElement.clientHeight;
var img_h=1040*(w1/640);
document.getElementById("bg_4deng").style.top= img_h*0+ 'px';
document.getElementById("bg_4deng").style.left=w1*0+ 'px';
document.getElementById("tk_4deng").style.top= img_h*0.33+ 'px';
document.getElementById("ljfs_4deng").style.top= img_h*0.75+ 'px';
document.getElementById("dhm_4deng").style.top= img_h*0.663+ 'px';
document.getElementById("btn_return4").style.top= img_h*0.92+ 'px';
 });

//我的奖品--5等奖
$(function(){
var w1=document.documentElement.clientWidth;
var h1=document.documentElement.clientHeight;
var img_h=1040*(w1/640);
document.getElementById("bg_5deng").style.top= img_h*0+ 'px';
document.getElementById("bg_5deng").style.left= w1*0+ 'px';
document.getElementById("tk_5deng").style.top= img_h*0.33+ 'px';
document.getElementById("ljfs_5deng").style.top= img_h*0.75+ 'px';
document.getElementById("dhm_5deng").style.top= img_h*0.663+ 'px';
document.getElementById("btn_return5").style.top= img_h*0.92+ 'px';
 });

//分享页面-村花
$(function(){
var w1=document.documentElement.clientWidth;
var h1=document.documentElement.clientHeight;
var img_h=1040*(w1/640);
document.getElementById("bg_cunhua").style.top= img_h*0+ 'px';
document.getElementById("bg_cunhua").style.left= w1*0+ 'px';
document.getElementById("dhm_cunhua").style.top= img_h*0.55+ 'px';
});

//分享页面-凤姐
$(function(){
var w1=document.documentElement.clientWidth;
var h1=document.documentElement.clientHeight;
var img_h=1040*(w1/640);
document.getElementById("bg_fengjie").style.top= img_h*0+ 'px';
document.getElementById("bg_fengjie").style.left= w1*0+ 'px';
document.getElementById("dhm_fengjie").style.top= img_h*0.534+ 'px';
});

//分享页面-龙女
$(function(){
var w1=document.documentElement.clientWidth;
var h1=document.documentElement.clientHeight;
var img_h=1040*(w1/640);
document.getElementById("bg_longnv").style.top= img_h*0+ 'px';
document.getElementById("bg_longnv").style.left= w1*0+ 'px';
document.getElementById("dhm_longnv").style.top= img_h*0.551+ 'px';
});

//分享页面-如花
$(function(){
var w1=document.documentElement.clientWidth;
var h1=document.documentElement.clientHeight;
var img_h=1040*(w1/640);
document.getElementById("bg_ruhua").style.top= img_h*0+ 'px';
document.getElementById("bg_ruhua").style.left= w1*0+ 'px';
document.getElementById("dhm_ruhua").style.top= img_h*0.55+ 'px';
});

//分享页面-女神
$(function(){
var w1=document.documentElement.clientWidth;
var h1=document.documentElement.clientHeight;
var img_h=1040*(w1/640);
document.getElementById("bg_nvshen").style.top= img_h*0+ 'px';
document.getElementById("bg_nvshen").style.left= w1*0+ 'px';
document.getElementById("dhm_nvshen").style.top= img_h*0.55+ 'px';
});
//判断客户终端是ios 还是android 控制字号大小
var browser={
    versions:function(){
        var u = navigator.userAgent, app = navigator.appVersion;
        return {
            trident: u.indexOf('Trident') > -1, //IE内核
            presto: u.indexOf('Presto') > -1, //opera内核
            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,//火狐内核
            mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
            android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
            iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
            iPad: u.indexOf('iPad') > -1, //是否iPad
            webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
            weixin: u.indexOf('MicroMessenger') > -1, //是否微信 （2015-01-22新增）
            qq: u.match(/\sQQ/i) == " qq" //是否QQ
        };
    }(),
    language:(navigator.browserLanguage || navigator.language).toLowerCase()
}

//判断是否是ios系统
if(browser.versions.gecko){
   // alert("is huohu");
	document.getElementById("dhm_1deng").style.fontSize=18+'px';
	document.getElementById("dhm_cunhua").style.fontSize=15+'px';
	document.getElementById("dhm_fengjie").style.fontSize=15+'px';
	document.getElementById("dhm_longnv").style.fontSize=15+'px';
	document.getElementById("dhm_ruhua").style.fontSize=15+'px';
	document.getElementById("dhm_nvshen").style.fontSize=15+'px';
	
}
//判断是否是ios系统
if(browser.versions.ios){
    //alert("is ios");
	document.getElementById("dhm_1deng").style.fontSize=17+'px';
	document.getElementById("dhm_2deng").style.fontSize=17+'px';
	document.getElementById("dhm_3deng").style.fontSize=17+'px';
	document.getElementById("dhm_4deng").style.fontSize=17+'px';
	document.getElementById("dhm_5deng").style.fontSize=17+'px';
/*	document.getElementById("dhm_1deng").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_2deng").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_3deng").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_4deng").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_5deng").style.lineHeight=img_h*0.2+ 'px';
*/	document.getElementById("dhm_cunhua").style.fontSize=14+'px';
	document.getElementById("dhm_fengjie").style.fontSize=14+'px';
	document.getElementById("dhm_longnv").style.fontSize=14+'px';
	document.getElementById("dhm_ruhua").style.fontSize=14+'px';
	document.getElementById("dhm_nvshen").style.fontSize=14+'px';
/*	document.getElementById("dhm_cunhua").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_fengjie").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_longnv").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_ruhua").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_nvshen").style.lineHeight=img_h*0.2+ 'px';
*/}


//判断是否安卓系统
if(browser.versions.android){
    //alert("is android");
	document.getElementById("dhm_1deng").style.fontSize=24+'px';
	document.getElementById("dhm_2deng").style.fontSize=24+'px';
	document.getElementById("dhm_3deng").style.fontSize=24+'px';
	document.getElementById("dhm_4deng").style.fontSize=24+'px';
	document.getElementById("dhm_5deng").style.fontSize=24+'px';
/*	document.getElementById("dhm_1deng").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_2deng").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_3deng").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_4deng").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_5deng").style.lineHeight=img_h*0.2+ 'px';
*/	document.getElementById("dhm_cunhua").style.fontSize=24+'px';
	document.getElementById("dhm_fengjie").style.fontSize=24+'px';
	document.getElementById("dhm_longnv").style.fontSize=24+'px';
	document.getElementById("dhm_ruhua").style.fontSize=24+'px';
	document.getElementById("dhm_nvshen").style.fontSize=24+'px';
/*	document.getElementById("dhm_cunhua").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_fengjie").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_longnv").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_ruhua").style.lineHeight=img_h*0.2+ 'px';
	document.getElementById("dhm_nvshen").style.lineHeight=img_h*0.2+ 'px';
*/	
}
