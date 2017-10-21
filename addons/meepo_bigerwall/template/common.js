$(function(){
	var sh;
	$('body').mouseover(function() {
		$('.side_div').slideDown('normal');
	});
	$('.side_div').mouseover(function() {
		clearInterval(sh);
	});
	$('.side_div').mouseleave(function() {
		sh=setInterval(function(){$('.side_div').slideUp('normal');},5000);
	});
	sh=setInterval(function(){$('.side_div').slideUp('normal');},5000);
});