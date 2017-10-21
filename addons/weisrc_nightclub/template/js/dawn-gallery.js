/*
 * 黎明相册，仿手机QQ空间相册查看器V2.0
 *
 * Bright@soft.com
 *
 * 2014/7/11
 * 
 */

// 黎明相册尺寸自适应
function setGallerySize() {
	var windowInnerHeight = window.innerHeight;
	var windowInnerWidht = window.innerWidth;

	$('#slider-view').css({
		'height': windowInnerHeight,
		'width': windowInnerWidht
	});

	$('.slider-item').css({
		'height': windowInnerHeight,
		'width': windowInnerWidht,
		'lineHeight': windowInnerHeight + 'px'
	});
}

$(window).on('load', function() {
	setGallerySize();
});

$(window).on('resize', function() {
	setGallerySize();
});

// dawn-gallery，黎明相册，初始化
$('#slider-view').show();
window.dawnSwipe = new Swipe(document.getElementById('slider'), {
		continuous: false,
		disableScroll: true,
		callback: function(index, elem){
			$img = $(elem).children('img');
			if($img.attr('src') === "" ||
				$img.attr('src') === undefined){
				$('.slider-img-loading').show();
				$img.attr('src', $img.attr('data-img-src'));
				$img.load(function() {
					// Act on the event
					$('.slider-img-loading').hide();
				});
			}
		}
});

$('#slider-view').hide();

// 点击打开黎明相册，查看大图
$(".dawn-gallery").on('tap', ".dawn-gallery-item >img", function(event) {
	event.preventDefault();
	event.stopPropagation();

	$('#slider-view').show();
	$('nav.app').hide();
	// var index = $(this).index();
	// if($(this).parent().parent.hasClass('left')){
	// 	index = index*2;
	// }
	// if($(this).parent().hasClass('right')){
	// 	index = index*2 + 1;
	// }
	
	var index = $(this).parent().attr('data-id')-1;
    //alert(index);
	// console.log(index);

	dawnSwipe.slide(index, 0);
	dawnSwipe.slide(index, 0);
	setGallerySize();

	$imgFirst = $('.slider-item').eq(index).children('img');
	if($imgFirst.attr('src') === "" ||
		$imgFirst.attr('src') === undefined){
		$('.slider-img-loading').show();
		$imgFirst.attr('src', $imgFirst.attr('data-img-src'));
		$imgFirst.load(function() {
			/* Act on the event */
			$('.slider-img-loading').hide();
		});
	}
});

// 点击关闭黎明相册
$('#slider-view').on('tap', function() {
	$(this).fadeOut('fast');
	$('nav.app').show();
});