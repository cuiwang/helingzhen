jQuery(document).ready(function($){
								
			var $sections_overlay = $('#sections li div.sections-overlay');
			var $sections_overlay_single = $('.thumbnail-single div.sections-overlay');
			var $featured_button_prev = $("#featured-button div.prev-hover");
			var $featured_button_next = $("#featured-button div.next-hover");
			
		$('ul.superfish').superfish({ 
            delay:       150,                            // one second delay on mouseout 
            animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
            speed:       'fast',                          // faster animation speed 
            autoArrows:  true,                           // disable generation of arrow mark-up 
            dropShadows: false                            // disable drop shadows 
        }); 
		

	$("#categories-button").click(function(){
		$("#categories-dropdown").slideToggle("slow");
		$(this).toggleClass("active"); return false;
	});
	
		$("#home-wrapper div.sections-overlay").css({opacity: "0", backgroundPosition: "-40px -167px"});
		$("#home-wrapper div.thumbnail-div").hover(function () {
		$(this).stop().animate({marginTop: "-10px"}, 450);
		$(this).find("div.sections-overlay").stop().animate({opacity: "1", backgroundPosition:"(-40px 0px)"}, {duration:1000});
		},
		function () {
			$(this).stop().animate({marginTop: "0px"}, 450);
			$(this).find("div.sections-overlay").stop().animate({opacity: "0", backgroundPosition:"(0px -167px)"}, {duration:1000});
		});
		
		$sections_overlay.css("opacity","0");
		$sections_overlay.hover(function () {
			$(this).stop().animate({
				opacity: 1
			}, "slow");
		},
		function () {
			$(this).stop().animate({
				opacity: 0
			}, "slow");
		});
		
		$sections_overlay_single.css("opacity","0");
		$sections_overlay_single.hover(function () {
			$(this).stop().animate({
				opacity: 1
			}, "slow");
		},
		function () {
			$(this).stop().animate({
				opacity: 0
			}, "slow");
		});
	});