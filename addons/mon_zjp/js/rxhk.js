//移动端版本兼容
if (/Android (\d+\.\d+)/.test(navigator.userAgent)) {
	var version = parseFloat(RegExp.$1);
	if (version > 2.3) {
		var phoneScale = parseInt(window.screen.width) / 720;
		document.write('<meta name="viewport" content="width=720, minimum-scale = '
						+ phoneScale
						+ ', maximum-scale = '
						+ phoneScale
						+ ', target-densitydpi=device-dpi">');
	} else {
		document.write('<meta name="viewport" content="width=720, target-densitydpi=device-dpi">');
	}
} else {
	document.write('<meta name="viewport" content="width=720, user-scalable=no, target-densitydpi=device-dpi">');
}
// 微信去掉下方刷新栏
if (RegExp("MicroMessenger").test(navigator.userAgent)) {
	document.addEventListener('WeixinJSBridgeReady', function() {
		// WeixinJSBridge.call('hideToolbar');
	});
}