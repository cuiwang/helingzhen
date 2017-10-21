$(document).ready(function() {
	var refid = getRefid();

	if (refid == 128922994) {
		//头部广告
		$('.pbngdda-nsnwbwl').addClass('none');
		//返回页面
		//$('.page-header .page-back').addClass('none');
		//去除底部文字 s+ 11.24
		$('.page-footer .nav a[title="同程旅游"]').remove();
		$('.page-footer .nav a[title="帮助中心"]').remove();
		$('.page-footer .nav a[title="意见反馈"]').remove();
		//头部景点团购
		$('.scenery_tuan').parent().addClass('none');
		//去掉首页
		$("body").delegate(".header-menu-btn", "click", function() {
			$('.header-menu .home').remove();
			//s+ 11.23
			$('.my').html('<i></i>我的订单').attr('href','/hotel/orderlist.html');
		});
		//app预定，返现立提
		$('.apptb_box').remove();
	};
	if(refid == 43277484){
		$('.pbngdda-nsnwbwl').addClass('none');
		$('.header-menu-btn').addClass('none');
	}
});