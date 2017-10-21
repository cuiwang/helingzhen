jQuery(document).ready(function() {
	
	function uplightbox() {
	
	jQuery("a[rel^='prettyPhoto']").prettyPhoto({
	animationSpeed:'fast',
	slideshow:5000,
	theme:'light_square',
	show_title:false,
	overlay_gallery: false
	});
	
	}
	
	if(jQuery().prettyPhoto) {
	
	uplightbox(); 
	
	}

});
$(window).load(function() {
	$('.icons_nav').flexslider({
	animation: "slide",
	directionNav: true, <!--Set this to false if you want to remove the arrows navigation of the menu-->
	animationLoop: false,
	controlNav: false, 
	slideshow: false,
	animationDuration: 300
	});	
	
	var divh = document.getElementById('header').offsetHeight;
	var divp = divh + divh;

		$('#menu_open').click(function(){
				$('#pages_nav').animate({'top':divh +"px"},500);
				$('.content').animate({'margin-top':divp +"px"},500);
				$('#menu_open').hide();
				$('#menu_close').show();

		});
		$('#menu_close').click(function(){
				$('#pages_nav').animate({'top':'-200px'},400);
				$('.content').animate({'margin-top':'2%'},500);
				$('#menu_open').show();
				$('#menu_close').hide();

		});
});