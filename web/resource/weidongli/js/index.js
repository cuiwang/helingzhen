
xiaoyu(window).scroll(function(){
	if(xiaoyu(window).height() + xiaoyu(window).scrollTop() - xiaoyu("#project").offset().top > 100 && xiaoyu(window).height() + xiaoyu(window).scrollTop() - xiaoyu("#project").offset().top < xiaoyu(window).height() -30){
		xiaoyu(".project_title_more").stop().animate({"left":"50%"},500);
	}else{
		xiaoyu(".project_title_more").stop().animate({"left":"100%"},500);
	}
	if(xiaoyu(window).height() + xiaoyu(window).scrollTop() - xiaoyu(".about_int").offset().top > 100 && xiaoyu(window).height() + xiaoyu(window).scrollTop() - xiaoyu(".about_int").offset().top < xiaoyu(window).height() -30){
		xiaoyu(".about_btn").stop().animate({"left":"50%"},500);
	}else{
		xiaoyu(".about_btn").stop().animate({"left":"-5%"},500);
	}
	index1 = xiaoyu("#services").offset().top;
	index2 = xiaoyu("#project").offset().top;
	index3 = xiaoyu("#about").offset().top;
	index4 = xiaoyu("#news").offset().top;
	index5 = xiaoyu("#contact").offset().top;
	var scrolltop = xiaoyu(window).scrollTop() + 120;
	if(scrolltop < index1){
		indexnum = 0;
	}else if(index1 < scrolltop && scrolltop < index2){
		indexnum = 1;
	}else if(index2 < scrolltop && scrolltop < index3){
		indexnum = 2;
	}else if(index3 < scrolltop && scrolltop < index4){
		indexnum = 3;
	}else if(index4 < scrolltop && scrolltop < index5){
		indexnum = 4;
	}else if(scrolltop > index5){
		indexnum = 5;
	}
	xiaoyu("#nav li").removeClass("active").eq(indexnum).addClass("active");
})

function navmove(id,index){
	if(id == "#index"){ var headheight = 0; }else{ var headheight = 70; }
	var offsettop = xiaoyu(id).offset().top - headheight;
	xiaoyu('html,body').stop().animate({scrollTop: offsettop},1500, 'easeInOutQuint');
	return false;
}

function selfmove(){
	var navId = "";
	if(window.location.href.indexOf("#services") > 0 ){navId = "#services";}
	if(window.location.href.indexOf("#project") > 0 ){navId = "#project";}
	if(window.location.href.indexOf("#about") > 0 ) {navId = "#about";}
	if(window.location.href.indexOf("#news") > 0 ){navId = "#news";}
	if(window.location.href.indexOf("#contact") > 0 ){navId = "#contact";}
	if (navId != ""){
		xiaoyu(window).scrollTop(0);
		xiaoyu('html,body').animate({scrollTop:xiaoyu(navId).offset().top - 70},2000, 'easeInOutQuint');
	}
}

function LoadingHidden(){
	xiaoyu("#loading").animate({"opacity":"0"},500,function(){ xiaoyu("#loading").css({"left":"100%"})});
}

xiaoyu(function(){
	xiaoyu(".in_banner").hover(function(){
		xiaoyu(".slide_left",this).stop().animate({"left":"0"},300)
		xiaoyu(".slide_right",this).stop().animate({"right":"0"},300)
	},function(){
		xiaoyu(".slide_left",this).stop().animate({"left":"-50px"},300)
		xiaoyu(".slide_right",this).stop().animate({"right":"-50px"},300)
	})
})

xiaoyu(function(){
	xiaoyu(".infocus").focus();
	xiaoyu(".infocus").hover(function(){
		xiaoyu(".left_btn",this).stop().animate({"left":"0"},300)
		xiaoyu(".right_btn",this).stop().animate({"right":"0"},300)
	},function(){
		xiaoyu(".left_btn",this).stop().animate({"left":"-50px"},300)
		xiaoyu(".right_btn",this).stop().animate({"right":"-50px"},300)
	})
})
xiaoyu(function(){
	xiaoyu("#services_ul li").hover(function(){
		xiaoyu(".services_ico div",this).stop().animate({"opacity":"1"},300);
	},function(){
		xiaoyu(".services_ico div",this).stop().animate({"opacity":"0"},300);
	})
	xiaoyu("#services_con").mousemove(function(e) {
		if(xiaoyu(window).width() > xiaoyu(this).width()){
			var leftWidth = (xiaoyu(window).width() - xiaoyu(this).width())/2;
		}else{
			var leftWidth = 0;
		}
		var offset=e.clientX - leftWidth;
		var x=0;
		var y=0;
		xiaoyu("#services_ul",this).css({"margin-left": -( (xiaoyu("#services_ul",this).width() - xiaoyu(this).width()) / xiaoyu(this).width())*offset +"px"});
	});
	xiaoyu(".services_popclose").click(function(){
		xiaoyu(this).parents("#services_pop").slideUp(200);
	})
})
function services_tab(popindex,slide){
	xiaoyu(".services_poptabcon li").css("zIndex","1").hide().eq(popindex).css("zIndex","2").show();
	xiaoyu(".services_poptabbtn a").removeClass("active").eq(popindex).addClass("active");
	if(slide == "true"){
		xiaoyu("#services_pop").slideDown(200);
	}
}

xiaoyu(function(){
	xiaoyu(".project_pic li a").hover(function(){
		xiaoyu(".project_pop",this).stop(false,true).slideDown("fast");
	},function(){
		xiaoyu(".project_pop",this).stop(false,true).slideUp("fast");
	})
})
function project_tab(popindex){
	xiaoyu(".project_pic li").hide().eq(popindex).fadeIn(300);
	xiaoyu(".project_btn a").removeClass("active").eq(popindex).addClass("active");
}

xiaoyu(function(){
	xiaoyu(".about_btn").hover(function(){
		xiaoyu("span",this).stop().animate({"opacity":"1"},300);
	},function(){
		xiaoyu("span",this).stop().animate({"opacity":"0"},300);
	})
	xiaoyu(".about_btn").click(function(){
		xiaoyu(".about_int").addClass("about_intbg");
		xiaoyu(".about_int").stop().animate({"height":"245"},1000,function(){
			xiaoyu("#about_pop").slideDown(500);
			xiaoyu(".about_int").stop().animate({"height":"1"},500,function(){
				xiaoyu(".about_int").removeClass("about_intbg");
				var abouttop = xiaoyu("#about_pop").offset().top - 70;
				xiaoyu('html,body').stop().animate({scrollTop: abouttop},1000, 'easeInQuint');
			});
		});

	})
	xiaoyu(".about_popclose").click(function(){
		xiaoyu("#about_pop").slideUp(500);
		abouttop = xiaoyu("#about").offset().top - 70;
		xiaoyu('html,body').animate({scrollTop: abouttop},1200, 'easeInOutQuint',function(){
			xiaoyu(".about_int").stop().animate({"height":"45"},1000)
		});
	})
})

function aboutpop_tab(popindex){
	xiaoyu(".about_pop_con li").slideUp(300).eq(popindex).slideDown(300);
	xiaoyu(".about_pop_tab li").removeClass("active").eq(popindex).addClass("active");
}



xiaoyu(function(){
	xiaoyu(".workpopl").click(function(){
		xiaoyu(document).attr("title",xiaoyu(this).attr("title"));
		xiaoyu("#workpop").show();
		url = xiaoyu(this).attr("href");
		LoadingLeftShow(url);
		return false;
	});
	xiaoyu(".workpopr").click(function(){
		xiaoyu(document).attr("title",xiaoyu(this).attr("title"));
		xiaoyu("#workpop").show();
		url = xiaoyu(this).attr("href");
		LoadingRightShow(url);
		return false;
	});
	xiaoyu("#news li a").click(function(){
		xiaoyu(document).attr("title",xiaoyu(this).attr("title"));
		xiaoyu("#workpop").show();
		url = xiaoyu(this).attr("href");
		LoadingLeftShow(url);
		return false;
	});
})
