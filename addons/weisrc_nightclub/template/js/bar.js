$(document).ready(function() {

	var startIndex = $('#nav-slider li.actived').parent().index();
	if(startIndex === -1){
		startIndex = 0;
	}
	//初始化底部导航栏swipe控件
	window.navSwipe = new Swipe(document.getElementById('nav-slider'), {
		continuous: false,
		// 从第二屏开始
		startSlide: startIndex
	});

	// 底部菜单向前一屏
	$("nav.app").on('tap', 'span.nav-prev', function(event) {
		event.preventDefault();
		navSwipe.prev();
	});

	// 顶部菜单向后一屏
	$("nav.app").on('tap', 'span.nav-next', function(event) {
		event.preventDefault();
		navSwipe.next();
	});
});

// 提示错误消息
function showToastInfo(info) {
    if ($("label.error").length === 0) {
        $("<label class='error'></label>").text(info).appendTo($("body")).show().delay(1000).fadeOut(600);
    } else {
        $("label.error").text(info).show().delay(1000).fadeOut(600);
    }
}

//// 提示错误消息
function showToastErr(info) {
    if ($("label.error").length == 0) {
        $("<label class='error'></label>").text(info).appendTo($("body")).show().delay(1000).fadeOut(600);
    } else {
        $("label.error").text(info).appendTo($("body")).show().delay(1000).fadeOut(600);
    }
}