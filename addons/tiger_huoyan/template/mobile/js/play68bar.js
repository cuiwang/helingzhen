var SHARE_DESC=33;
var SHARE_TITLE=11;
var SITE_TITLE=55;
var GAME_URL='http://';
var RESOURCE_IMG_PATH='/assets/images/';
var COVER_SHOW_TIME=1000;
//var HORIZONTAL="";
function isWeixin(){
	var ua = navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i)=="micromessenger") {
		return true;
	} else {
		return false;
	}
}

function toggle(id) {
var el = document.getElementById(id);
var img = document.getElementById("arrow");
var box = el.getAttribute("class");
if(box == "hide"){
	el.setAttribute("class", "show");
	delay(img, RESOURCE_IMG_PATH + "arrowright.png", 400);
}
else{
	el.setAttribute("class", "hide");
	delay(img, RESOURCE_IMG_PATH + "arrowleft.png", 400);
}
}

function delay(elem, src, delayTime){
	window.setTimeout(function() {elem.setAttribute("src", src);}, delayTime);
}

function show_share(){
//	show_share_page();	
	//toggle('play68box');
};

function box_show_share(){
	show_share_page();	
	//toggle('play68box');
};

function show_share_page(title, desc ){
   
	SHARE_TITLE=title;
	wxqrP3.innerHTML = "&quot;" + SHARE_TITLE + "&quot;";
	wxqrImg.src = GAME_ICON;
	config_jiathis_config();
	
	if(isWeixin() == true) {
	
		document.getElementById("share-wx").style.display = 'block';
	}
	else {
	
		document.getElementById("wx-qr").style.display = 'block';
	}

}

function closeshare(){
	document.getElementById("share-wx").style.display = 'none';
}
function closewx(){
	document.getElementById("wx-qr").style.display = 'none';
}

function addShareWX() {
    var title=SHARE_TITLE;
   
 	var shareDiv = document.createElement("div");
	shareDiv.id = "share-wx";
	shareDiv.style.cssText = "background:rgba(0,0,0,0.8); position:fixed;top:0px; left:0px; width:100%; height:100%; z-index:10000; display:none;"	
	shareDiv.onclick= closeshare;
	document.body.appendChild(shareDiv);
	
		var shareP = document.createElement("p");
    	shareP.style.cssText = "text-align:right;padding-left:10px; width:100%; height:100%; z-index:10000; display:none;";
	//	shareP.style.cssText = "background:rgba(0,0,0,0.8); position:fixed;top:0px; left:0px; width:100%; height:100%; z-index:10000; display:none;"
		shareDiv.appendChild(shareP);
	
			var shareImg = document.createElement("img");
		//	shareImg.src = RESOURCE_IMG_PATH + "share.png";
		shareImg.src = "./share.png";
			shareImg.id = "share-wx-img";
			shareImg.style.cssText = "max-width:280px;padding-right:18px;";
			shareP.appendChild(shareImg);

	
			
		addShareButtons(shareDiv,title);

}

function showShareWXCallback() {
	var share_div = document.getElementById("share-wx");
	if( share_div ) { share_div.style.display = "none"; }
	var div = document.getElementById("share-wx-callback");
	if ( div ) {
		div.style.display = 'block';
		return;
	}
	var div = document.createElement("div");
	div.style.cssText = "background:rgba(0,0,0,0.8); position:fixed;top:0px; left:0px; width:100%; height:" + document.height + "px; z-index:10000;"+
		"text-align: center;font-family: Helvetica, Arial, Verdana, Microsoft Yahei, 微软雅黑, STXihei, 华文细黑, sans-serif;";
	div.id = "share-wx-callback";
	div.onclick = function(){ div.style.display = 'none'; };

	var title = document.createElement("h1");
	title.innerText = "分享成功";
	title.style.cssText = "color:#FF5F44;margin-top:1em;";
	div.appendChild(title);

	var desc = document.createElement("h3");
	desc.innerHTML = "你的朋友很快就会来<br />和你一起玩！"; 
	desc.style.cssText = "color:#FFFFFF;margin:0.5em";
	div.appendChild(desc);

	//var btn1 = document.createElement("input");
	//btn1.setAttribute("value", "关注我");
	//btn1.setAttribute("type", "button");
	var btn1 = document.createElement("div");
	btn1.innerHTML = "<h3>关注我们</h3>";
	btn1.style.cssText = "border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px;width: 12em;height:3em;line-height:3em;"
		+ "text-align: center;margin:1.5em auto;background: #da4453;color: #FFFFFF;";
	btn1.onclick = function(){ window.location.href = FOLLOW_URL; };
	div.appendChild(btn1);

	var btn2 = document.createElement("div");
	btn2.innerHTML = "<h3>更多游戏</h3>";
	btn2.style.cssText = "border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;width: 12em;height:3em;line-height:3em;"
		+ "text-align: center;margin:1.5em auto;background: #f6bb42;color: #FFFFFF;";
	btn2.onclick = function(){ window.location.href = HOME_PATH; };
	div.appendChild(btn2);	

	var btn3 = document.createElement("div");
	btn3.innerHTML = "<h3>继续游戏</h3>";
	btn3.style.cssText = "border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;width: 14em;height:3em;line-height:3em;"
		+ "text-align: center;margin:3em auto;background: #37bc9b;color: #FFFFFF;";
	btn3.onclick = function(){ div.style.display = 'none'; };
	div.appendChild(btn3);

	document.body.appendChild(div);
}

function addWXQR(title,desc) {
 
	var wxqrDiv = document.createElement("div");
//	wxqrDiv.style.cssText = "background:rgba(0,0,0,0.8); position:fixed;top:0px; left:0px; width:100%;border:1px solid yellow; height:" + document.height + "px; z-index:10000; display:none;"
	wxqrDiv.style.cssText = "background:rgba(0,0,0,0.8); position:fixed;top:0px; left:0px; width:100%; height:100%; z-index:10000; display:none;"	
	wxqrDiv.id = "wx-qr";
	wxqrDiv.onclick = closewx;
	document.body.appendChild(wxqrDiv);
	
		/* var */ wxqrP1 = document.createElement("p");
		wxqrP1.style.cssText = "text-align:center;width:220px;color:#fff;margin:50px auto 0px auto;font:bold 16px Arial,Helvetica,Microsoft Yahei,微软雅黑,STXihei,华文细黑,sans-serif;";
		wxqrP1.innerHTML = "分享给朋友一起玩！";
		wxqrDiv.appendChild(wxqrP1);

		addShareButtons(wxqrDiv,title);
}

function addShareButtons(divToAddTo,title) {
   var wei_xin = document.createElement("img");
   wei_xin.src = "/addons/tiger_huoyan/template/mobile/js";
   wei_xin.id = "wei_img";
   wei_xin.style.cssText = "max-width:300px;padding-right:25px;";
   	divToAddTo.appendChild(wei_xin);
	var wxqrP2 = document.createElement("p");
		wxqrP2.style.cssText = "text-align:center;margin:16px;";
		divToAddTo.appendChild(wxqrP2);
		
		
			/* var */ wxqrImg = document.createElement("img");
			wxqrImg.src = GAME_ICON;
		
			wxqrImg.id = "wx-qr-img";
			wxqrImg.style.cssText = "max-width:75px;";
	//		if(!HORIZONTAL || !isMobile()) {
		//		wxqrP2.appendChild(wxqrImg);
	//	}
				wxqrP2.appendChild(wxqrImg);
		wxqrP3 = document.createElement("p");
		var shareTextWidth = "210px";
	//	if(HORIZONTAL == true) {
		//	shareTextWidth = "400px";
//	}
		wxqrP3.style.cssText = "text-align:center;width:" + shareTextWidth + ";color:#fff;padding-top:5px;margin:0 auto;font: bold 20px Arial, Helvetica, Microsoft Yahei, 微软雅黑, STXihei, 华文细黑, sans-serif";
		wxqrP3.innerHTML = "&quot;" + title + "&quot;";
		divToAddTo.appendChild(wxqrP3);
		
		wxqrP4 = document.createElement("p");
		if(!isMobile()) {
			wxqrP4.style.cssText = "text-align:center;width:265px;padding-top:20px;margin:0 auto;font: bold 20px Arial, Helvetica, sans-serif";
		}
		else {
			wxqrP4.style.cssText = "text-align:center;width:212px;padding-top:20px;margin:0 auto;font: bold 20px Arial, Helvetica, sans-serif";
		}
		divToAddTo.appendChild(wxqrP4);
		
		//JiaThis Button BEGIN
		var jiathisDiv = document.createElement("div");
		jiathisDiv.className = "jiathis_style_32x32";
		wxqrP4.appendChild(jiathisDiv);
	/*	
			if(!isMobile()) {
				var sharelink1 = document.createElement("a");
				sharelink1.className = "jiathis_button_weixin";
				sharelink1.innerHTML = "&nbsp";
				jiathisDiv.appendChild(sharelink1);
			}
		
			var sharelink2 = document.createElement("a");
			sharelink2.className = "jiathis_button_tsina";
			sharelink2.innerHTML = "&nbsp";
			jiathisDiv.appendChild(sharelink2);
			
			var sharelink3 = document.createElement("a");
			sharelink3.className = "jiathis_button_qzone";
			sharelink3.innerHTML = "&nbsp";
			jiathisDiv.appendChild(sharelink3);
			
			var sharelink4 = document.createElement("a");
			sharelink4.className = "jiathis_button_tqq";
			sharelink4.innerHTML = "&nbsp";
			jiathisDiv.appendChild(sharelink4);
			
			var sharelink5 = document.createElement("a");
			sharelink5.className = "jiathis_button_renren";
			sharelink5.innerHTML = "&nbsp";
			jiathisDiv.appendChild(sharelink5);
	*/
				var btn1 = document.createElement("div");
	btn1.innerHTML = "<h3>关注我们</h3>";
	btn1.style.cssText = "border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px;width: 11em;height:2em;line-height:2em;position:relative;top:-15px;"
		+ "text-align: center;margin:1.5em auto;background: #da4453;color: #FFFFFF;";
	btn1.onclick = function(){ window.location.href = FOLLOW_URL; };
		    jiathisDiv.appendChild(btn1);
		var jiathisJS = document.createElement("script");
		jiathisJS.type = "text/javascript";
		jiathisJS.src = "http://v3.jiathis.com/code/jia.js?uid=1399105943150378"
		jiathisJS.charset = "utf-8";
		document.body.appendChild(jiathisJS);
		
		config_jiathis_config();

		//JiaThis Button END
}

function config_jiathis_config() {
	jiathis_config={
		summary: SHARE_DESC,
		title: SHARE_TITLE + " #"+SITE_TITLE+"#",
		url: GAME_URL,
		pic: GAME_ICON,
		ralateuid:{
			"tsina":"5133079826"
		},
		shortUrl:false,
		hideMore:true
	}
}

function addSidebar() {
	var play68boxDiv = document.createElement("div");
	play68boxDiv.id = "play68box";
	play68boxDiv.className = "hide";
	document.body.appendChild(play68boxDiv);
	
		var play68boxUl = document.createElement("ul");
		play68boxUl.id = "tab";
		play68boxDiv.appendChild(play68boxUl);
		
			var play68boxLi = document.createElement("li");
			play68boxUl.appendChild(play68boxLi);
		
				var play68boxArrowImg = document.createElement("img");
				play68boxArrowImg.id = "arrow";
				play68boxArrowImg.onclick = function() { toggle('play68box'); };
				play68boxArrowImg.src = RESOURCE_IMG_PATH + "arrowleft.png";
				play68boxLi.appendChild(play68boxArrowImg);
		
		var play68boxLinksDiv = document.createElement("div");
		play68boxLinksDiv.id = "links";
		play68boxDiv.appendChild(play68boxLinksDiv);
		
			var play68boxDecoDiv = document.createElement("div");
			play68boxDecoDiv.id = "deco";
			play68boxLinksDiv.appendChild(play68boxDecoDiv);
				
				var play68boxLogoImg = document.createElement("img");
				play68boxLogoImg.src = RESOURCE_IMG_PATH + "play68.png";
				play68boxLogoImg.style.cssText = "margin:0 15px; width:71px";
				play68boxDecoDiv.appendChild(play68boxLogoImg);
				
				var play68boxBt1Div = document.createElement("div");
				play68boxBt1Div.className = "bt";
				play68boxDecoDiv.appendChild(play68boxBt1Div);
					
					var play68boxBt1A = document.createElement("a");
					//play68boxBt1A.href = "#";
					play68boxBt1A.onclick = function() { toggle('play68box'); };
					play68boxBt1A.innerHTML = "继续游戏";
					play68boxBt1Div.appendChild(play68boxBt1A);
					
				var play68boxBt2Div = document.createElement("div");
				play68boxBt2Div.className = "bt";
				play68boxDecoDiv.appendChild(play68boxBt2Div);
					
					var play68boxBt2A = document.createElement("a");
					//play68boxBt2A.href = "#";
					play68boxBt2A.onclick = box_show_share;
					play68boxBt2A.innerHTML = "分享";
					play68boxBt2Div.appendChild(play68boxBt2A);
					
				var play68boxBt3Div = document.createElement("div");
				play68boxBt3Div.className = "bt";
				play68boxDecoDiv.appendChild(play68boxBt3Div);
					
					var play68boxBt3A = document.createElement("a");
					play68boxBt3A.href = HOME_PATH;
					play68boxBt3A.innerHTML = "更多游戏";
					play68boxBt3Div.appendChild(play68boxBt3A);
	
}

/* function addDisableSavePage() {
	var noscriptNode = document.createElement("noscript");
	document.body.appendChild(noscriptNode);
		
		var iframeNode = document.createElement("iframe");
		iframeNode.src = '*.html';
		noscriptNode.appendChild(iframeNode);
} */

function isMobile(){
	return navigator.userAgent.match(/android|iphone|ipod|blackberry|meego|symbianos|windowsphone|ucbrowser/i);
}

function isIOS() {
	return navigator.userAgent.match(/iphone|ipod|ios/i);
}

(function(){

if(typeof play68_init == 'function') {
	play68_init();
}

if(!isMobile()) return;
var coverNode = document.createElement("div");
coverNode.style.cssText = "position:absolute;z-index:1000000;left:0;top:0;background:#e9573f url("+RESOURCE_IMG_PATH+"cover.png) no-repeat center center;background-size: 50%;width:"+window.innerWidth+"px;height:"+Math.max(window.innerHeight,window.document.documentElement.offsetHeight)+"px";
coverNode.className = "common_cover";
document.body.appendChild(coverNode);
setTimeout(function(){coverNode.parentNode.removeChild(coverNode)},COVER_SHOW_TIME);

//document.addEventListener("touchmove",function(e){e.preventDefault();},false);
var noticeNode = document.createElement("div");
noticeNode.className = "common_notice";
noticeNode.style.cssText = "position:absolute;z-index:999999;left:0;top:0;background:#e9573f url("+RESOURCE_IMG_PATH+"rotate_tip.png) no-repeat center center;background-size: 50%;";
document.body.appendChild(noticeNode);


function checkCover(){
	window.scroll(0,0);
	var horizontal;
	if(window.orientation == 0 || window.orientation == 180){
		horizontal = false;
	}else if (window.orientation == -90 || window.orientation == 90){
		horizontal = true;
	}
	if(horizontal == HORIZONTAL){
		noticeNode.style.display = "none";
	}else{
		setTimeout(function(){
			ajustWidthHeight();
			noticeNode.style.width = window.innerWidth+"px";
			noticeNode.style.display = "block";
		},(isIOS() ? 0 : 600));
	}
	if(HORIZONTAL == true && isWeixin() && !isIOS()) {
		WeixinJSBridge.call('hideToolbar');
	}
	var _h = window.orientation;
	//GameFrame.style.cssText = "transform: rotateX("+_h+"deg);-moz-transform: rotateX("+_h+"deg);-webkit-transform: rotateX("+_h+"deg);";
	GameFrame.contentWindow.postMessage({orientation:window.orientation}, '*');
}

function ajustWidthHeight(){
	coverNode.style.height=window.innerHeight+"px";
	coverNode.style.width=window.innerWidth+"px";
	noticeNode.style.height=window.innerHeight+"px";
}

window.addEventListener("orientationchange",checkCover);
window.addEventListener("load",checkCover);
window.addEventListener("scroll",ajustWidthHeight);
})();

//addSidebar();

//disable right click
document.oncontextmenu=function() {return false}; 
//disable save page
//addDisableSavePage();

if(isWeixin()) {

	addShareWX();
}
else {

	addWXQR();

}

