xiaoyu.fn.slide = function(options) {
	var defaults = {
		type:         'fade',
		btn:          '.slide_btn',
		leftBtn:      '.slide_left',
		rightBtn:     '.slide_right',
		btnActive:    'click',
		picBox:       '.slide_pic',
		num:          '1',
		conWidth:     '100%',
		conHeidth:    '100%',
		time:         '3000',
		speed:        '500',
		play:         '1',
		percent:      '0'
	};
	var
		obj       =     xiaoyu.extend(defaults,options),
		self      =     xiaoyu(this),
		picUl     =     self.find(obj.picBox+">ul"),
		picLi     =     self.find(obj.picBox+">ul>li"),
		btnLi     =     self.find(obj.btn+">ul>li"),
		leftBtn   =     self.find(obj.leftBtn),
		rightBtn  =     self.find(obj.rightBtn),
		type      =     obj.type,
		conWidth  =     obj.conWidth,
		conHeight  =    obj.conHeight,
		speed     =     obj.speed,
		percent   =     obj.percent,
		len       =     Math.ceil(picLi.length/obj.num),
		index     =     0,
		lose = 0,
		timer;
	/*=========动作初始化对象属性=========*/
	var elementInit = {
		".opacity":{
			opacity: 0
		},
		".banner1_img": {
			top:250,
			left:820
		},
		".banner1_text": {
			top:120,
			left:468
		},
		".banner1_btn": {
			top:450,
			left:380
		},
		".banner2_img1": {
			bottom:-150,
			left:460
		},
		".banner2_img2": {
			top:80,
			left:625
		},
		".banner2_title": {
			top:210,
			left:1238
		},
		".banner2_text1": {
			top:340,
			left:1238
		},
		".banner2_text2": {
			top:410,
			left:1238
		},
		".banner2_text3": {
			top:470,
			left:1238
		},
		".banner3_img": {
			bottom:-50,
			left:500
		},
		".banner3_text": {
			top:215,
			right:468
		},
		".banner3_btn": {
			bottom:0,
			right:548
		},
		".banner4_img": {
			top:102,
			left:528
		},
		".banner4_title1": {
			top:165,
			left:1030
		},
		".banner4_title2": {
			top:165,
			left:1188
		},
		".banner4_title3": {
			top:165,
			left:1350
		},
		".banner4_text": {
			top:308,
			left:960
		}
	}
	for (var i in elementInit) {
		var xiaoyui = picLi.find(i);
		for (var j in elementInit[i]) {
			xiaoyui.css(j, elementInit[i][j]);
		}
	}
	In_1();
	
	/*=========点击触发动作=========*/
	leftBtn.click(function(){
		if(lose == 0){
		index==0 ? index=len-1 : index--
		goanimate(index);
		}
	})

	rightBtn.click(function(){
		if(lose == 0){
		index==len-1 ? index=0 : index++;
		goanimate(index);
		}
	})

	/*=========自动播放=========*/
	if(obj.play==1){
		self.hover(function(){
			clearInterval(timer);
		},function(){
			clearInterval(timer);
			timer = setInterval(function(){
				index==len-1 ? index=0 : index++;
				goanimate(index);
			},obj.time);
		}).trigger("mouseleave");
	}

	var goanimate = function(index){
			if(index == 0){
				Out_4();
				In_1();
				Out_2();
				
			}else if(index == 1){
				Out_1();
				In_2();
				Out_3();
			}else if(index == 2){
				Out_2();
				In_3();
				Out_4();
			}else if(index == 3){
				Out_3();
				In_4();
				Out_1();
			}
		btnLi.removeClass("active").eq(index).addClass("active");
	}
	function In_1(){
		lose = 1;
		picLi.find("a").hide();
		picLi.eq(index).find("a").show();
		setTimeout(function() {
			xiaoyu(".banner_bg1").stop().animate({opacity: 1},function(){
				setTimeout(function() {
					xiaoyu(".banner1_img").stop().animate({"top":"196px", "left":"710px", opacity: 1},1000);
					setTimeout(function() {
						xiaoyu(".banner1_text").stop().animate({"top":"210px", "left":"468px", opacity: 1},700);
						setTimeout(function() {
							xiaoyu(".banner1_btn").stop().animate({"top":"450px", "left":"470px", opacity: 1},700,function(){
								lose = 0;
							}).addClass("anima");
						},500);
					},300);
				},0);
			});
		},200);
	}
	function Out_1(){
		setTimeout(function() {
			xiaoyu(".banner1_img").stop().animate({"top":"220px", "left":"750px", opacity: 0},1000);
			setTimeout(function() {
				xiaoyu(".banner1_text").stop().animate({"top":"180px", "left":"468px", opacity: 0},700);
				setTimeout(function() {
					xiaoyu(".banner1_btn").stop().animate({"top":"450px", "left":"420px", opacity: 0},700).removeClass("anima");
					xiaoyu(".banner_bg1").stop().animate({opacity: 0});
				},500);
			},300);
		},0);
	}
	
	function In_2(){
		lose = 1;
		picLi.find("a").hide();
		picLi.eq(index).find("a").show();
		setTimeout(function() {
			xiaoyu(".banner_bg2").stop().animate({opacity: 1},function(){
				setTimeout(function() {
					xiaoyu(".banner2_img1").stop().animate({"bottom":"0", "left":"460px", opacity: 1},800);
					setTimeout(function() {
						xiaoyu(".banner2_img2").stop().animate({"top":"118px", "left":"625px", opacity: 1},1000);
						setTimeout(function() {
							xiaoyu(".banner2_title").stop().animate({"top":"210px", "left":"938px", opacity: 1},800);
							setTimeout(function() {
								xiaoyu(".banner2_text1").stop().animate({"top":"340px", "left":"938px", opacity: 1},800);
								setTimeout(function() {
									xiaoyu(".banner2_text2").stop().animate({"top":"410px", "left":"938px", opacity: 1},800);
									setTimeout(function() {
										xiaoyu(".banner2_text3").stop().animate({"top":"470px", "left":"938px", opacity: 1},800,function(){
											lose = 0;
										});
									},150);
								},150);
							},150);
						},150);
					},300);
				},0);
			});
		},500);
	}
	function Out_2(){
		setTimeout(function() {
			xiaoyu(".banner2_img1").stop().animate({"bottom":"-150px", "left":"460px", opacity: 0},800);
			setTimeout(function() {
				xiaoyu(".banner2_img2").stop().animate({"top":"80px", "left":"625px", opacity: 0},1000);
				setTimeout(function() {
					xiaoyu(".banner2_title").stop().animate({"top":"210px", "left":"1238px", opacity: 0},800);
					setTimeout(function() {
						xiaoyu(".banner2_text1").stop().animate({"top":"340px", "left":"1238px", opacity: 0},800);
						setTimeout(function() {
							xiaoyu(".banner2_text2").stop().animate({"top":"410px", "left":"1238px", opacity: 0},800);
							setTimeout(function() {
								xiaoyu(".banner2_text3").stop().animate({"top":"470px", "left":"1238px", opacity: 0},800);
								xiaoyu(".banner_bg2").stop().animate({opacity: 0});
							},150);
						},150);
					},150);
				},150);
			},300);
		},0);
	}

	function In_3(){
		lose = 1;
		picLi.find("a").hide();
		picLi.eq(index).find("a").show();
		setTimeout(function() {
			xiaoyu(".banner_bg3").stop().animate({opacity: 1},function(){
				setTimeout(function() {
					xiaoyu(".banner3_img").stop().animate({"bottom":"0", "left":"500px", opacity: 1},1000);
					setTimeout(function() {
						xiaoyu(".banner3_text").stop().animate({"top":"245px", "right":"468px", opacity: 1},700);
						setTimeout(function() {
							xiaoyu(".banner3_btn").stop().animate({"bottom":"60px", "right":"488px", opacity: 1},700,function(){
								lose = 0;
							});
						},250);
					},250);
				},0);
			});
		},500);
	}
	function Out_3(){
		setTimeout(function() {
			xiaoyu(".banner3_img").stop().animate({"bottom":"-50px", "left":"500px", opacity: 0},1000);
			setTimeout(function() {
				xiaoyu(".banner3_text").stop().animate({"top":"215px", "right":"468px", opacity: 0},700);
				setTimeout(function() {
					xiaoyu(".banner3_btn").stop().animate({"bottom":"0", "right":"548px", opacity: 0},700);
					xiaoyu(".banner_bg3").css({opacity: 0});
				},250);
			},250);
		},0);
	}
	function In_4(){
		lose = 1;
		picLi.find("a").hide();
		picLi.eq(index).find("a").show();
		setTimeout(function() {
			xiaoyu(".banner_bg4").stop().animate({opacity: 1},function(){
				xiaoyu(".banner4_img").css({"top":"262px", "left":"378px",opacity: 0});
				xiaoyu(".banner4_img").stop().animate({"top":"102px", "left":"528px",opacity: 1},2000,'easeOutQuart')
			});
			setTimeout(function() {
				xiaoyu(".banner4_title1").stop().animate({"top":"235px", "left":"960px",opacity: 1},700)
				setTimeout(function() {
					xiaoyu(".banner4_title2").stop().animate({"top":"235px", "left":"1118px",opacity: 1},700)
					setTimeout(function() {
						xiaoyu(".banner4_title3").stop().animate({"top":"235px", "left":"1280px",opacity: 1},700)
						xiaoyu(".banner4_text").stop().animate({"top":"328px", "left":"960px",opacity: 1},1000,function(){
							lose = 0;
						})
					},200);
				},200);
			},200);
		},500);
	}
	function Out_4(){
		setTimeout(function() {
			xiaoyu(".banner4_img").stop().animate({"top":"262px", "left":"378px"},300,function(){
				xiaoyu(".banner4_img").stop().animate({"top":"-302px", "left":"928px", opacity: 0},800,function(){
					xiaoyu(".banner4_img").css({"top":"132px", "left":"498px"});
				});
			});
			xiaoyu(".banner4_title1").stop().animate({"top":"255px", "left":"960px",opacity: 0},700)
			xiaoyu(".banner4_title2").stop().animate({"top":"255px", "left":"1118px",opacity: 0},700)
			xiaoyu(".banner4_title3").stop().animate({"top":"255px", "left":"1280px",opacity: 0},700)
			xiaoyu(".banner4_text").stop().animate({"top":"348px", "left":"960px",opacity: 0},1000)
			xiaoyu(".banner_bg4").stop().animate({opacity: 0});
		},200);
	}
}
