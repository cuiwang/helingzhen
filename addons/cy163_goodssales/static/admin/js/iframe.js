//window.onload表示页面加载完毕后执行
//window.onresize表示窗口触发事件的时候执行
//两个函数，用闭包包裹起来()()
window.onload = function () {(window.onresize = function () {
	//获取可见宽度
	var width = document.documentElement.clientWidth - 200;
	//获取可见高度
	var height = document.documentElement.clientHeight - 140;
	//如果有宽度，给值
	if (width >= 0) document.getElementById('main').style.width = width + 'px';
	//如果有高度，给值
	if (height >= 0) {
		document.getElementById('sidebar').style.height = height - 20 + 'px';
		document.getElementById('main').style.height = height + 'px';
		document.getElementById('footer').style.marginTop = height + 'px';
	}
})()};