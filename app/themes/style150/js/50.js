/*
** 变量值
*/
	/* 
	** 页面切换的效果控制 
	*/
var Msize = $(".m-page").size(), 	//页面的数目
	page_n			= 1,			//初始页面位置
	initP			= null,			//初值控制值
	moveP			= null,			//每次获取到的值
	firstP			= null,			//第一次获取的值
	newM			= null,			//重新加载的浮层
	p_b				= null,			//方向控制值
	indexP			= null, 		//控制首页不能直接找转到最后一页
	move			= null,			//触摸能滑动页面
	start			= true, 		//控制动画开始
	startM			= null,			//开始移动
	position		= null,			//方向值
	DNmove			= false,		//其他操作不让页面切换
	mapS			= null,			//地图变量值
	
	/*
	** 声音功能的控制
	*/
	audio_switch_btn= true,			//声音开关控制值
	audio_btn		= true,			//声音播放完毕
	audio_loop		= false,		//声音循环
	audioTime		= null,         //声音播放延时
	audio_interval	= null,			//声音循环控制器
	audio_start		= null,			//声音加载完毕
	audio_stop		= null,			//声音是否在停止
	mousedown		= null;			//PC鼠标控制鼠标按下获取值


/* 
** 单页切换 各个元素fixed 控制body高度 
*/
	var v_h	= null;		//记录设备的高度
	
	function init_pageH(){
		var fn_h = function() {
			if(document.compatMode == "BackCompat")
				var Node = document.body;
			else
				var Node = document.documentElement;
			 return Math.max(Node.scrollHeight,Node.clientHeight);
		}
		var page_h = fn_h();
		var m_h = $(".m-page").height();
		page_h >= m_h ? v_h = page_h : v_h = m_h ;
		
		//设置各种模块页面的高度，扩展到整个屏幕高度
		$(".m-page").height(v_h); 	
		$(".p-index").height(v_h);
		
	}(init_pageH());
	
/*
**模版切换页面的效果
*/
	//绑定事件
	$(".m-page").on('mousedown touchstart',page_touchstart);
	$(".m-page").on('mousemove touchmove',page_touchmove);
	$(".m-page").on('mouseup touchend mouseout',page_touchend);

	//触摸（鼠标按下）开始函数
	function page_touchstart(e){
		if (e.type == "touchstart") {
			initP = window.event.touches[0].pageY;
		} else {
			initP = e.y || e.pageY;
			mousedown = true;
		}
		firstP = initP;	
	};
	
	//插件获取触摸的值
	function V_start(val){
		initP = val;
		mousedown = true;
		firstP = initP;		
	};
	
	//触摸移动（鼠标移动）开始函数
	function page_touchmove(e){
		e.preventDefault();

		//判断是否开始或者在移动中获取值
		if(start||startM){
			startM = true;
			if (e.type == "touchmove") {
				moveP = window.event.touches[0].pageY;
			} else { 
				if(mousedown) moveP = e.y || e.pageY;
			}
			page_n == 1 ? indexP = false : indexP = true ;	//false 为不是第一页 true为第一页
		}
		
		//设置一个页面开始移动
		if(moveP&&startM){
			
			//判断方向并让一个页面出现开始移动
			if(!p_b){
				p_b = true;
				position = moveP - initP > 0 ? true : false;	//true 为向下滑动 false 为向上滑动
				if(position){
				//向下移动
					if(indexP){								
						if( page_n == 1 ) newM = Msize ;
						else newM = page_n - 1 ;
						$(".m-page").eq(newM-1).addClass("active").css("top",-v_h)
						move = true ;
					}else{
						move = false;
					}
							
				}else{
				//向上移动
					if( page_n == Msize ) newM = 1 ;
					else newM = page_n + 1 ;
					$(".m-page").eq(newM-1).addClass("active").css("top",v_h);
					move = true ;
				} 
			}
			
			//根据移动设置页面的值
			if(!DNmove){
				//滑动带动页面滑动
				if(move){				
					
					//开启声音
					if($("#car_audio").length>0&&audio_switch_btn&&Math.abs(moveP - firstP)>100){
						$("#car_audio")[0].play();
						audio_loop = true;
					}
				
					//移动中设置页面的值（top）
					start = false;
					var topV = parseInt($(".m-page").eq(newM-1).css("top"));
					$(".m-page").eq(newM-1).css({'top':topV+moveP-initP});	
					initP = moveP;
				}else{
					moveP = null;	
				}
			}else{
				moveP = null;	
			}
		}
	};

	//触摸结束（鼠标起来或者离开元素）开始函数
	function page_touchend(e){	
			
		//结束控制页面
		startM =null;
		p_b = false;
		
		//关闭声音
		 audio_close();
		
		//判断移动的方向
		var move_p;	
		position ? move_p = moveP - firstP > 100 : move_p = firstP - moveP > 100 ;
		if(move){
			//切画页面(移动成功)
			if( move_p && Math.abs(moveP) >5 ){	
				$(".m-page").eq(newM-1).animate({'top':0},300,"easeOutSine",function(){
					/*
					** 切换成功回调的函数
					*/
					success();
				})
			//返回页面(移动失败)
			}else if (Math.abs(moveP) >=5){	//页面退回去
				position ? $(".m-page").eq(newM-1).animate({'top':-v_h},100,"easeOutSine") : $(".m-page").eq(newM-1).animate({'top':v_h},100,"easeOutSine");
				$(".m-page").eq(newM-1).removeClass("active");
				start = true;
			}
		}
		/* 初始化值 */
		initP		= null,			//初值控制值
		moveP		= null,			//每次获取到的值
		firstP		= null,			//第一次获取的值
		mousedown	= null;			//取消鼠标按下的控制值
	};
/*
** 切换成功的函数
*/
	function success(){
		/*
		** 切换成功回调的函数
		*/							
		//设置页面的出现
		$(".m-page").eq(page_n-1).removeClass("show active").addClass("hide");
		$(".m-page").eq(newM-1).removeClass("active hide").addClass("show");
		
		
		//重新设置页面移动的控制值
		page_n = newM;
		start = true;
		
		//地图重置
		if($(".m-page").eq(page_n-1).find(".ylMap").length>0&&!mapS){
			if(!mapS) mapS = new ylmap.init;
		}
		
		//txt富文本自适应伸缩
		txtExtand();
	
		//页面切换视频播放停止
		if($('.m-video').find("video")[0]!=undefined){$('.m-video').find("video")[0].pause()};
		
		//文本缩回
		$(".m-txt").removeClass("open");	
	}

	//关闭声音
		function audio_close(){
			if(audio_btn&&audio_loop){
				audio_btn =false;
				audioTime = Number($("#car_audio")[0].duration-$("#car_audio")[0].currentTime)*1000;	
				if(audioTime<0) audioTime=0
				
				if(!isNaN(audioTime)&&audioTime!=0){
					setTimeout(
						function(){	
							audioTime = null;
							$("#car_audio")[0].pause();
							$("#car_audio")[0].currentTime = 0;
							audio_btn = true;	
						},audioTime);
				}else{
					audio_interval = setInterval(function(){
						if(!isNaN($("#car_audio")[0].duration)){
							if($("#car_audio")[0].currentTime == $("#car_audio")[0].duration){
								$("#car_audio")[0].currentTime = 0;	
								clearInterval(audio_interval);
								audio_btn = true;
							}
						}
					},200)	
				}
				
			}
		}
	
	//页面声音播放
	$(function(){
		//获取声音元件
		var btn_au = $(".fn-audio").find(".btn");
		
		//绑定点击事件
		btn_au.on('click touchstart',audio_switch);
		function audio_switch(evt){
			evt.preventDefault();
			evt.stopPropagation();
			if($("#car_audio")==undefined){
				return;
			}
			if(audio_switch_btn){
				//关闭声音
				$("#car_audio")[0].pause();
				audio_switch_btn = false;
				$("#car_audio")[0].currentTime = 0;
				btn_au.find("img").eq(0).css("display","none");
				btn_au.find("img").eq(1).css("display","inline");
			}
			//开启声音
			else{
				audio_switch_btn = true;
				btn_au.find("img").eq(1).css("display","none");
				btn_au.find("img").eq(0).css("display","inline");
			}
		}
	})

/*
**文本展开效果
*/
	//判断富文本是否展开
	function txtExtand(){
		$(".m-txt").each(function(){
			var txt 	= $(this).find(".wtxt");
			var txtH	= txt.height();
			var hH		= parseInt(txt.children().eq(0).height())+parseInt(txt.children().eq(0).css("margin-bottom"));
			var pH		= txt.find("p").height();
			if(pH+hH<txtH){
				$(this).addClass("hide_poniter");
			}else{
				$(this).removeClass("hide_poniter");
			}
		})
	}(txtExtand());

	//富文本切换
	$(function(){
		$(".m-txt").on('click',extant)
		function extant(){
			if(!$(this).hasClass("hide_poniter")) $(this).toggleClass("open");
		}	
	});



/*
**设备旋转提示
*/
/*	$(function(){
		var bd = $(document.body);
		window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', _orientationchange, false);
		function _orientationchange() {
			scrollTo(0, 1);
			switch(window.orientation){
				case 0:		//横屏
					$(function(){
						bd.addClass("landscape").removeClass("portrait");
						init_pageH();
					})
					break;
				case 180:	//横屏
					$(function(){
						bd.addClass("landscape").removeClass("portrait");	
						init_pageH();
					})
					break;
				case -90: 	//竖屏
					$(function(){
						init_pageH();
						bd.addClass("portrait").removeClass("landscape");
						if($(".m-video video")[0].paused)
							alert("请竖屏查看页面，效果更佳");
					})
					break;
				case 90: 	//竖屏
					$(function(){
						init_pageH();
						bd.addClass("portrait").removeClass("landscape");
						if($(".m-video video")[0].paused)
							alert("请竖屏查看页面，效果更佳");
					})
					break;
			}
		}
		window.onload = _orientationchange();
	});
*/

/*
** 页面内容加载loading显示
*/
	//显示
	function loadingPageShow(){
		$('.pageLoading').show();	
	}
	//隐藏
	function loadingPageHide(){
		$('.pageLoading').hide();	
	}

/*
** 页面加载初始化
*/
	function initPage(){
		//初始化一个页面
		$(".m-page").addClass("hide").eq(page_n-1).addClass("show").removeClass("hide");
		
		//初始化地图
		if($(".m-page").eq(page_n-1).find(".ylMap").length>0&&!mapS){
			mapS = new ylmap.init;
		}
		
		//判断声音是否加载
		if($("#car_audio").length>0&&!isNaN($("#car_audio")[0].currentTime)){
			audio_start = true;
		}
		
		//PC端图片点击不产生拖拽
		$(document.body).find("img").on("mousedown",function(e){
			e.preventDefault();
		})	
		
		//按钮点击的变化
		$('.btn-boder-color').click(function(){
			if(!$(this).hasClass("open"))	$(this).addClass('open');
			setTimeout(function(){
				$('.btn-boder-color').removeClass('open');	
			},600)
			
		})
		
	}(initPage());



/**
 * loading图标设置
 * string type loading:出现加载图片；end 结束加载图片
 */
function loading(type){
	if('loading'==type){
		$('.loading').css({display:'block'});
	}else if('end'==type){
		$('.loading').css({display:'none'});
	}
}