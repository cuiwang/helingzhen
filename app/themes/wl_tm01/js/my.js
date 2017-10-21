var yt = yt || {};

//加载图片
yt.loadImg = function ($imgs, time) {
    var _time = 0;
    time = time || 200;

    $imgs.each(function () {
        var $that = $(this);
        if ($that.data('hasload')) {
            return false;
        }
        setTimeout(function () {
            $that.fadeOut(0);
            $that.attr('src', $that.data('src'));
            $that.attr('data-hasload', 'true');
            $that.fadeIn(500);
        }, _time);
        _time += time;
    });
};

//wap端环境
yt.isWap = function () {
    var s = navigator.userAgent.toLowerCase();
    var ipad = s.match(/ipad/i) == "ipad"
		, iphone = s.match(/iphone os/i) == "iphone os"
		, midp = s.match(/midp/i) == "midp"
		, uc7 = s.match(/rv:1.2.3.4/i) == "rv:1.2.3.4"
		, uc = s.match(/ucweb/i) == "ucweb"
		, android = s.match(/android/i) == "android"
		, ce = s.match(/windows ce/i) == "windows ce"
		, wm = s.match(/windows mobile/i) == "windows mobile";
    if (iphone || midp || uc7 || uc || android || ce || wm || ipad) { return true; }
    return false;
};

//滑动绑定
yt.app = function () {
   
    var $swiperContainer = $("#swiper-container1"),
        $pages = $("#wrapper").children(),
        $as = $("#nav li a"),
        $lis = $("#nav li"),
        $win =$(window),
        slideCount = $pages.length,
        nowIndex = 0,
        acn = "animation",
        mySwiper;

    var params = {
        selectorClassName: "swiper-container",
        animationClassName: acn,
        animationElm: $("." + acn)
    };
    var setCssText = function (prop, value) {
        return prop + ': ' + value + '; ';
    };

    /*
	 * insertCss(rule)
	 * 向文档<head>底部插入css rule操作
	 * rule: 传入的css text
	 * */
    var insertCss = function (rule) {
        var head = document.head || document.getElementsByTagName('head')[0],
			style;

        if (!!head.getElementsByTagName('style').length) {
            style = head.getElementsByTagName('style')[0];
   
            if (style.styleSheet) {
                style.styleSheet.cssText = rule;
            } else {
                style.innerHTML = '';
                style.appendChild(document.createTextNode(rule));
            }
        } else {
            style = document.createElement('style');

            style.type = 'text/css';
            if (style.styleSheet) {
                style.styleSheet.cssText = rule;
            } else {
                style.appendChild(document.createTextNode(rule));
            }

            head.appendChild(style);
        }
    };

    var setAnimationStyle=function() {
        var cssText = '';

        cssText += '.' + params.animationClassName + '{' +
			setCssText('display', 'none') +
			'}' +
			'.touchstart .' + params.animationClassName + '{' +
			setCssText('-webkit-animation-duration', '0 !important') +
			setCssText('-webkit-animation-delay', '0 !important') +
			setCssText('-webkit-animation-iteration-count', '1 !important') +
			'}';
   
        var index = mySwiper.activeIndex,
            _index = index + 1,
            $ans = $pages.eq(index).find('.' + params.animationClassName);
        $ans.each(function () {
            var obj = $(this);
         
            _className = obj.attr('data-item'),
            _animation = obj.attr('data-animation'),
            _duration = ((obj.attr('data-duration') / 1000) || 1) + 's',
            _timing = obj.attr('data-timing-function') || 'ease',
            _delay = ((obj.attr('data-delay') || 0) / 1000) + 's',
            _count = obj.attr('data-iteration-count') || 1;

    
            var _t = '.' + params.selectorClassName +
                ' .page-' + _index +
                ' .' + _className;

            cssText += _t + '{' +
                setCssText('display', 'block !important') +
                setCssText('-webkit-animation-name', _animation) +
                setCssText('-webkit-animation-duration', _duration) +
                setCssText('-webkit-animation-timing-function', _timing) +
                setCssText('-webkit-animation-delay', _delay) +
                setCssText('-webkit-animation-fill-mode', 'both') +
                setCssText('-webkit-animation-iteration-count', _count) +
                '}';

        });

        return cssText;

    };

    //设置动画
    var setAms = function () {
        insertCss(setAnimationStyle());
    };

    //设置布局
    var setLayout = function () {
        var $wrapers = $("#swiper-container1 .wraper"),
            $wraper1 = $("#wraper1"),
       
            isWap=yt.isWap(),
            w = 720,
            h = 1135;
        var sl = function () {
            var _w = $wraper1.width(),
                h = $win.height(),
                _h = isWap && _w<h?$win.height():_w * 1135 / 720;
            $wrapers.height(_h);
			if($win.height()<300){
				$(".cn-slidetips").hide();
			}else{
				$(".cn-slidetips").show();
			}
        };
        sl();
        $win.resize(sl);
    };

    //滑动绑定函数
    var onSlideChangeTime = 0;

    var onSlideChange = function () {
        if (onSlideChangeTime>1) {
            return;
        }

      

       
        var index = mySwiper.activeIndex;
        if (nowIndex == index && mySwiper.touches['abs'] < 50) {
            return;
        }
        onSlideChangeTime = 20;
        setAms();
        nowIndex = index || 0;
        history.pushState(null, null, "/?p=" + (nowIndex + 1));
        //执行动画
        var timer=setInterval(function () {
            onSlideChangeTime -= 1;
            if (onSlideChangeTime == 0) {
                clearInterval(timer);
            }
        },1);
    };



    //触摸结束绑定
    var onTouchEnd = function () {
        var index = mySwiper.index;
        if (nowIndex == slideCount-1 && +mySwiper.touches['diff'] <-50) {

           
            return mySwiper.swipeTo(0);
        }
        onSlideChange();
        
    };



    //滑动结束绑定
    var onSlideChangeEnd = function () {
       
        onSlideChange();
    };

    //绑定滑动主函数
    var bindSwiper = function () {
        mySwiper = $swiperContainer.swiper({
            pagination: false,
            paginationClickable: false,
            onTouchEnd: onTouchEnd,
            onSlideChangeEnd: onSlideChangeEnd,
            mode: 'horizontal',
			mousewheelControl:true,
            grabCursor: true
        });
      
       


    };
    
    //滚到下一个屏
    var bindNext = function () {
        $("#arrow-h").on("touchstart", function () {
            mySwiper.activeIndex = mySwiper.activeIndex || 0;
            var index = mySwiper.activeIndex == slideCount - 1 ? 0 : (mySwiper.activeIndex||0) + 1;
       
            mySwiper.swipeTo(index);
        });
    };
    //获取地址参数
    var getParams = function () {
        var search = (window.location.search || "").replace('?', ''),
            params = search.split('&'),
           obj = {},
           len = params.length;
        if (len < 1) { return false;}
        while (len--) {
            var A=params[len].split('=');
            obj[A[0]] = A[1];
        }
        return obj;
    };
    //滚动到指定模块
    var goPageByParams = function (flag) {
        var params = getParams(),p='p';

        if(flag && window.location.search==''){
         
            return mySwiper.swipeTo(0,1);
        }
        if (!params && !params[p] && params[p] < 1 || params[p]>5) {
            return false;
        }
        //alert(params[p]);
        mySwiper.swipeTo(Math.floor(params[p])-1,1);
    };

	var check = function (){
		var mob = /^0{0,1}(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])[0-9]{8}$/;
		var company = $("#company");
		var name = $("#name");
		var phone = $("#phone");
		if(company.val() == ""){
			company.addClass("tips");
			setTimeout(function(){company.removeClass("tips")},1000);
			return false;
		}else if(name.val() == ""){
			name.addClass("tips");
			setTimeout(function(){name.removeClass("tips")},1000);
			return false;
		}else if(phone.val() == ""){
			phone.addClass("tips");
			setTimeout(function(){phone.removeClass("tips")},1000);
			return false;
		}else if(!mob.test(phone.val())){
			phone.addClass("tips");
			phone.attr("placeholder","请输入正确的手机号码！");
			setTimeOut(function(){phone.removeClass("tips")},1000);
			return false;
		}else{
			return true;
		}
		
	}
	
	var btn = function (){
		$(".pkBox .btn").click(function(){
			$(this).addClass("on");
			$(this).parent().siblings().find(".btn").removeClass("on");
			setTimeout(function(){
				mySwiper.swipeNext();
			},500);
		});
		$(".big_btn").click(function(){
			mySwiper.swipeNext();
		});
		$("#submit").click(function(){
			if(check()){
				var company = $("#company").val();
				var name = $("#name").val();
				var phone = $("#phone").val();
				var select = new Array();
				for(var i=0;i<3;i++){
					select[i] = $(".pkBox .on").eq(i).attr("name");
				}
			}
		});
		$("input").click(function(){
			$(this).focus();
		});
		$("#share_tips").click(function(){
			$(this).hide();
		});
	}
	
   //初始化
    bindSwiper();
    bindNext();
    setLayout();
    setAms();
    goPageByParams();
    window.addEventListener('popstate', function (e) {
        goPageByParams(true);
    });
	btn();
};
//初始化
yt.init = function () {

    window.onload = function () {
    
        $("#loading").hide();
        setTimeout(yt.app);
		
		
    };

 
};
yt.init();

