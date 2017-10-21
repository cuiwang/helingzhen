jQuery.fn.jTabs = function(options) {
	
	var defaults = {
    };
	var settings = $.extend({}, options, defaults);
	var tabNo = settings.tabNumber;

	return this.each(function(){

		var tabContainers = $(this).children("div");
		
		$(this).find("li a").click(function (){
					
			tabContainers.hide().filter(this.hash).fadeIn(300);
			
			$(this).parent().parent().find("li").removeClass("selected");
			$(this).parent().addClass("selected");
			
			return false;
			
		}).filter(':eq('+tabNo+')').click();
		
	});
		
}