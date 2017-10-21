/***********************
* 函数：判断滚轮滚动方向 
* 参数：event 
* 返回：滚轮方向 1：向上 -1：向下 
*************************/
var scrollFunc = function (e) {
	var direct = 0;
 	e = e || window.event;
 	if (e.wheelDelta) {
 		direct = e.wheelDelta > 0 ? 1 : -1;
	} 
	else if (e.detail) {
 		direct = e.detail < 0 ? 1 : -1;
	}
	isShow(direct);
}

function isShow(direct) {
 	if (direct == 1) {
		//向上显示
		$("#foot").removeClass("foot_hover");
	} 
	else {
		//向下隐藏
		$("#foot").addClass("foot_hover");
	}
}

//全局变量，触摸开始位置
var startX = 0, startY = 0;

//touchstart事件
function touchSatrtFunc(evt) {
	try {
 		var touch = evt.touches[0]; //获取第一个触点
 		var x = Number(touch.pageX); //页面触点X坐标
 		var y = Number(touch.pageY); //页面触点Y坐标
		//记录触点初始位置
	 	startX = x;
 		startY = y;
		}
 	catch (e) {
		alert('touchSatrtFunc：' + e.message);
	}
}

//touchmove事件，这个事件无法获取坐标
function touchMoveFunc(evt) {
 	try {
 		//evt.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等
 		var touch = evt.touches[0]; //获取第一个触点
	 	var x = Number(touch.pageX); //页面触点X坐标
	 	var y = Number(touch.pageY); //页面触点Y坐标
	 	if (y - startY > 0) {
			//向下滑
			$("#foot").removeClass("foot_hover");
		} 
		else {
			//向上滑
			$("#foot").addClass("foot_hover");
		}
	}
 	catch (e) {
 		alert('touchMoveFunc：' + e.message);
	}
}

//touchend事件
function touchEndFunc(evt) {
 	try {
 		//evt.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等

 		//var text = 'TouchEnd事件触发';
 		//document.getElementById("result").innerHTML = text;
	}
 	catch (e) {
 		alert('touchEndFunc：' + e.message);
	}
}

//绑定事件
function bindEvent() {
 	document.addEventListener('touchstart', touchSatrtFunc, false);
 	document.addEventListener('touchmove', touchMoveFunc, false);
 	document.addEventListener('touchend', touchEndFunc, false);
}

//判断是否支持触摸事件
function isTouchDevice() {
 	try {
		document.createEvent("TouchEvent");
 		bindEvent(); //绑定事件
	}
 	catch (e) {
		//不支持TouchEvent事件！
 		if (document.addEventListener) {
 			document.addEventListener('DOMMouseScroll', scrollFunc, false);
		}//W3C
 		window.onmousewheel = document.onmousewheel = scrollFunc; //IE/Opera/Chrome 
	}
}