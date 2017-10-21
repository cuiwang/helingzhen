$(function() {
	FastClick.attach(document.body);
})

	
//保存图片
var su_arr = [];
$(function() {
	//轮播图、广告
	$(".swiper-container").swiper({
		loop: true,
		autoplay: 3000
	});

	//file浏览改变时触发
	$('#uploaderInput').on("click", function() {
		ifImgFive();
	});
	$('#uploaderInput').on("change", function() {
		readAsDataURL();
	});

	//移除图片
	$('#uploaderFiles').on("touchstart", ".liclose", function() {
		
		su_arr.splice($(this).parent().index(), 1);
		$(this).parent().remove();
		ifImgFive();
		return false;
	})

	//图片放大及消失
	$('#uploaderFiles').on("touchstart", ".weui-uploader__file_status", function() {
		$('.su_bg img').attr('src', su_arr[$(this).index()]);
		$('.su_bg').fadeIn(400);
	})
	$('.su_bg').click(function() {
		$('.su_bg img').attr('src', "");
		$(this).fadeOut(400);
	})
	//跳转链接
	$('.btn_01').on("touchstart",function(){
		window.location.href = "detail.html";
	});
});





