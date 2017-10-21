$(document).ready(function(){

	/*tab*/
	showTabs();
	/**/

	$('#tab_my').addClass("current");
	
	/**/
	$('#tab ul li').click(function(){
		if (this.id=="tab_my"){
			$('#currentPage').attr("value",1);
			window.sessionStorage.setItem("currentPage",$('#currentPage').attr("value")); 

			window.sessionStorage.setItem("scrollTop",0); 
		}
	});
	
	$(window).scroll(function () {
        var scrollTop = $(window).scrollTop();
        window.sessionStorage.setItem("scrollTop",scrollTop); 
    });
	
	initSession();
	
	var currentPage = $('#currentPage').attr("value");
	var countPerPage = $('#countPerPage').attr("value");
	loadActivity(1,countPerPage*currentPage);

	$('#loadMore').click(function(){
		
		if ($('#loadMore').hasClass('noData')==false){
			
			$('#loadMore').removeClass();
			$('#loadMore').addClass("isLoading");
			$('#loadMore p').html("正在加载...");

			var currentPage = $('#currentPage').attr("value");
			var newPage = parseInt(currentPage)+1;
			var countPerPage = $('#countPerPage').attr("value");
			loadActivity(newPage,countPerPage);
		}
		
	});
	/**/
	
});

function showTabs(){
	var actType = $('#actType').attr("value");
	$('.tabs').find("li").removeClass("current");
	$('#tab-'+actType).addClass("current");
}

function initSession(){
	var currentPage = window.sessionStorage.getItem("currentPage");
	if (currentPage){
		$('#currentPage').attr("value",currentPage)
	}
	else{
		window.sessionStorage.setItem("currentPage",$('#currentPage').attr("value")); 
	}
}



function loadPager(){
	
	var countRecords = parseInt($('#countRecords').attr("value"));
	if (countRecords>0){
		$('#loadMore').show();
		var currentPage = parseInt($('#currentPage').attr("value"));
		var countPerPage = parseInt($('#countPerPage').attr("value"));
		var countPage =  Math.ceil(countRecords / countPerPage);
		if (currentPage<countPage){
			$('#loadMore').removeClass();
			$('#loadMore p').html("加载更多");
		}
		else{
			$('#loadMore').removeClass();
			$('#loadMore').addClass("noData");
			$('#loadMore p').html("没有更多数据了");
		}
	}
	else{
		$('#loadMore').hide();
	}
}
