$(function(){
	
 	var isChrome = navigator.userAgent.indexOf('Chrome') != -1;
	
	if(isChrome){
	   $('.chromeTip').hide();
	}else{
		setTimeout(function(){
			$('.chromeTip').slideDown();
		},500)
		
		setTimeout(function(){
			$('.chromeTip').slideUp();
		},10500)
		
	}
	
	$('.chromeTip').find('.closeIcon').click(function(){
		$('.chromeTip').slideUp();
	})
})
