jQuery(function($){
	var u = navigator.userAgent; // 客户端环境信息
	//判断电脑系统，如果是电脑浏览，添加”pc“ class
	if( ! u.match(/AppleWebKit.*Mobile.*/i) && ! u.match(/Android.*/i) ){
		$('body, #footer, .share_box').addClass('pc');
		$('#footer').width(640);
		$('header').addClass('pc');
		$('body').css('background-color', '#333333');
		$('h1.title').addClass('title_pc');
	}
    //新版权信息4.0原生浏览器bug
    if(u.match(/Android\s4\.0.*/i) && !u.match(/.*Chrome.*/i) && !u.match(/.*MicroMessenger.*/i)){
        $('#yl-copyright dl dd').css('line-height','10px');
    }
    //4.0原生&&微信下bar高度bug
    if(u.match(/Android\s4\.0.*/i) && !u.match(/.*Chrome.*/i)){
        var viewHeight = window.innerHeight;
        $('#footer').length > 0 ? removeHeight = 150 : removeHeight = 95;//根据容器调整上拉BOX高度
        var nHeight=viewHeight - removeHeight;
        $('#box').css('top', nHeight);
    }

    $(document).ready(function () {
        var top = $('#footer').length > 0 ? window.innerHeight - 150 : window.innerHeight - 105;
        $('#box').css('top', top);
        var bgUrl = $('#bg').attr('src');
        $('body').css('background-image', 'url('+bgUrl+')');
    });
        
	$(window).on('load', function(){
		if(window.innerHeight){
			var removeHeight = 0;
			// 顶部上拉箭头图标占用高度
			//removeHeight += $('#scrollBtn').height();
			
			// 顶部上拉标题占用高度
			//removeHeight += $('.page-header').height()+36;
			
            $('#footer').length > 0 ? removeHeight = 150 : removeHeight = 105;//根据容器调整上拉BOX高度
			
			// 如果主背景图高度，小于窗口可视高度
			var viewHeight = window.innerHeight;
			var imageHeight = $('#bg').height();
			imageHeight = imageHeight ? imageHeight : 0;

			if(imageHeight < (viewHeight-removeHeight)){
				viewHeight = imageHeight > 20 ? imageHeight - 20 : imageHeight;
				
				// 避免浏览器有时，检测不到主图片高度的情况
				if(viewHeight < 50){
					viewHeight = window.innerHeight;
				}
			}
			
                        $('#footer').length > 0 ? removeHeight = 150 : removeHeight = 95;//根据容器调整上拉BOX高度
			var height = removeHeight ? viewHeight - removeHeight : 200;
			height  = height > 0 ? height : 0;
		}else{
			var height = 600;
		}
		
		/*主图兼容Android4.1系统*/
		if(u.match(/Android\s*4\.1.*/i)){
			$('header').css({
				'background-image' : 'url('+$('#bg').attr('src')+')',
				'background-position' : 'center top',
				'background-size' : 'contain',
				'background-repeat' : 'no-repeat'
			});
			
			// 主要避免内容层高度发生变化时，主图header容器位移发生变化，导致主图不见
			$('body').click(function(){
				$('header').css('top', '0');
			});
			
			// 避免上下滑动时，未知原因导致主图下移或下移至消失
			$(window).scroll(function(){
				$('header').css('top', '0');
	        });
		}
		
		// 主图背景兼容UC浏览器
		if(u.match(/UCBrowser.*/i) || u.match(/UC\sAppleWebkit.*/i)){
			var bodys = $('body');
			bodys.css({
				'background-image' : 'url('+$('#bg').attr('src')+')',
				'background-position' : 'center top',
				'background-size' : 'contain',
				'background-repeat' : 'round'
			});
			$('header').remove();
			
			// background fixed 和下面这个效果差，暂弃用
			//$(window).scroll(function(){
			//	bodys.css({'background-position': 'center '+$(document).scrollTop()+'px'});
	        //});
		}
		if(u.match(/Android.*/i)){
			$('#scrollBtn').css('top', '27px');
		}
		
		$('#box').css('top', height); // 设置上拉位置
		
		// 如果是电脑端，增加 上拉点击事件
		if( ! u.match(/AppleWebKit.*Mobile.*/i) && ! u.match(/Android.*/i) ){
			$('#scrollBtn').click(function(){
				if( parseInt($('#box').css('top')) < 10 ){
					$('#box').animate({ top: height }, 500);
				}else{
					$('#box').animate({ top: 0 }, 500);
					$(window).scrollTop(10)
				}
			});
		}
	});
}); 
function dialogClose() {
	scrollStart();
	$('#dialog').hide();
	$('#dialog-overlay').hide();
}
function dialogShow(title,content) {
	setTimeout(function(){
		var dialog = $('#dialog');
		if (title == '') {
			title = '温馨提示';
		}
		dialog.find('.title').html(title);
		dialog.find('.modal-body').html(content);
		var left = ($(window).width() - 270) / 2;
		var top = ($(window).height() - 90) / 3;
		dialog.css({top:top,left:left});
		$('#dialog-overlay').height($(document).height());
		$('#dialog-overlay').show();
		dialog.show();
	}, 300);
	
}

//禁止滚动条
function scrollStop(){
	//锁定页面高度
	$(document.body).addClass("fixed");

	//禁止滚动
	$(window).on('touchmove',function(e){e.preventDefault()});
}
	
//启动滚动条
function scrollStart(){		
	//解除页面高度锁定
	$(document.body).removeClass("fixed");	
	
	//开启屏幕禁止
	$(window).off('touchmove');
}

/**
 *  底部电话模块（点击input输入）让电话模块变成absolute
 */
$(function(){
	if(RegExp("iPhone").test(navigator.userAgent)||RegExp("iPod").test(navigator.userAgent)||RegExp("iPad").test(navigator.userAgent)){
		$('.module').find('input').on('focus',function(e){
			e.stopPropagation();
			$('#footer').css('position','absolute');
		});
		$('.module').find('select').on('focus',function(e){
			e.stopPropagation();
			$('#footer').css('position','absolute');
		});
		$('.module').find('input').on('blur',function(e){
			e.stopPropagation();
			$('#footer').css('position','fixed');
		});
		$('.module').find('select').on('blur',function(e){
			e.stopPropagation();
			$('#footer').css('position','fixed');
		});
		$('.module').find('input').on('click',function(e){
			e.stopPropagation();
		})
		$(window).on('resize',function(){
			if($('.module').find('input:focus').length>=1||$('.module').find('select:focus').length>=1){
				$('#footer').css('position','fixed');
			}else{
				$('#footer').css('position','absolute');
			}
		});
	}
})
/**
 *  图片延迟加载
 */
$(function(){
	$("img.lazy-image").lazyload({
	    failure_limit	: 10,
	    threshold		: 400
	});
})