// "分享好友"按钮事件
$(".share-btn").click(function(){
	// 如果已锁定触摸，不允许执行
	if(typeof(touchLock) != 'undefined' && touchLock){
		return false;
	}
	
	$(".share_box").show().animate({bottom: "0px"}, 600);
	
	// 弹出分享后，禁止滑动页面
	$("body").bind("touchmove", function(event) {
		if(event.preventDefault){
			event.preventDefault();// 阻止浏览器默认事件
		}
	});
});

// "取消"按钮事件
$(".share-cancel-btn").click(function(){
	$(".share_box").animate({bottom: "-160px"}, 600, 'swing', function(){$(".share_box").hide();});
	
	// 取消分享后，解除滑动
	$("body").unbind("touchmove");
});

// 按钮点击状态样式
$(".footerbar a").bind('touchstart mousedown', function(){
	$(this).addClass('active');
	return true;
}).bind('touchend mouseup', function(){
	$(this).removeClass('active');
	return true;
});


$('.share_box a').each(function(){
	var href=$(this).attr('href');
	if(href.indexOf("weibo")!=-1){
		var host="http://"+window.location.host;
		var imgsrc=$('.main-pic-img').attr('src')
		if(typeof(imgsrc)=='undefined'){
			var imgsrc=$('#bg').attr('src')
		}
		if(typeof(imgsrc)=='undefined'){
			var imgsrc=$('img').eq(0).attr('src')
		}
	$(this).attr('href',href+'&pic='+host+imgsrc)
	}
})
