
		
	//carousel
	!$( '#carouselBox' ).length > 0 || function(){ 
		
		var obj1 = new touch('carouselBox');
		obj1.autoScroll();
			
	}();
	
	//tab
	$.jqTab = function(tabTit,tabCon) {
		$(tabTit+" li").each(function(index){
			$(this).click(function(){
				$(tabTit+" li").removeClass("on");	
				$(this).addClass("on");
				$(tabCon).hide();
				$(tabCon).eq(index).show();
				return false;	
			});
		});
		
	};
	$.jqTab("#picTextList .tabTit","#picTextList .tabCon");
	
	$('#fontSize').toggle(
		function(){
			$(this).text('大字');
			$('#newsArticle').css('font-size','20px');
		},
		function(){
			$(this).text('小字');
			$('#newsArticle').css('font-size','14px');
		},
		function(){
			$(this).text('中字');
			$('#newsArticle').css('font-size','17px');
		}
	); 
	
