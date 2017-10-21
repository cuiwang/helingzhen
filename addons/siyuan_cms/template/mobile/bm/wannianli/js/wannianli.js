// JavaScript Document
function slidedata(i){
	return '<div class="swiper-slide"><div class="wannianli-card"><div class="wannianli-info">'+GetCurrentDateTime(i)+showCal(i)+'</div><div class="wannianli-other"><table><tbody><tr><td><div>宜<i></i></div></td><td><span>造车器、纳采、订盟、祭祀、祈福、求嗣、移徙、出行、开市、出火、入宅、立券、交易、入宅、安门、安床、安葬、谢土</span></td></tr></tbody></table><table><tbody><tr><td><div>忌<i></i></div></td><td><span>开光、造屋、动土、作灶、栽种</span></td></tr></tbody></table></div><i class="wannianli-ico wannianli-lt"></i><i class="wannianli-ico wannianli-rt"></i><i class="wannianli-ico wannianli-rb"></i></div></div>';
	}

var mySwiper = new Swiper('.swiper-container', {
    roundLengths : true, 
	initialSlide :2,
	speed:600,
	slidesPerView:"auto",
	centeredSlides : true,
	followFinger : false,
})
today=0;//默认显示今天
pre=2;
next=2;
mySwiper.appendSlide(slidedata(today));
mySwiper.appendSlide(slidedata(today+1));
mySwiper.appendSlide(slidedata(today+2));
mySwiper.prependSlide(slidedata(today-1));
mySwiper.prependSlide(slidedata(today-2));
mySwiper.on('slideChangeStart',function(swiper){
	//swiper.params.allowSwipeToPrev = false;
	swiper.lockSwipes();

})

mySwiper.on('slideChangeEnd',function(swiper){
	//alert(swiper.activeIndex);
swiper.unlockSwipes();		

	if(swiper.activeIndex==1){		
		pre++;
	    swiper.prependSlide(slidedata(today-pre));
	}
		if(swiper.activeIndex==0){		
		pre++;
	    swiper.prependSlide(slidedata(today-pre));
		pre++;
	    swiper.prependSlide(slidedata(today-pre));
	}

	if(swiper.activeIndex==swiper.slides.length-2){
		next++;
		swiper.appendSlide(slidedata(today+next));
			}
//swiper.params.allowSwipeToPrev = true;
	})
