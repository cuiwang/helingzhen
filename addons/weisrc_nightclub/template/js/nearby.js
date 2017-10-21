$(document).ready(function() {
	//初始化底部导航栏swipe控件
	window.navSwipe = new Swipe(document.getElementById('nav-slider'), {
		continuous: false,
	});

	var isInSeat = $("#inseat").val();
	if (isInSeat == 1) {
	    $("#wish-form textarea#my-wish").attr('disabled', false);
	    $("#wish-form textarea#my-wish").attr('placeholder', '已经就座，赶紧发表心语吧！');
	}

	// 底部菜单向前一屏
	$("nav.app").on('tap', 'span.nav-prev', function(event) {
		event.preventDefault();
		navSwipe.prev();
	});

	// 底部菜单向后一屏
	$("nav.app").on('tap', 'span.nav-next', function(event) {
		event.preventDefault();
		navSwipe.next();
	});

	// 初始化已就坐用户头像swipe控件
	window.headimgSwipe = new Swipe(document.getElementById('headimg-slider'), {
		continuous: false,
	});

	// 已就坐用户头像swipe向前一屏
	$("ul.controller").on('tap', 'li.to-left', function(event) {
		event.preventDefault();
		headimgSwipe.prev();
	});

	// 已就坐用户头像swipe向后一屏
	$("ul.controller").on('tap', 'li.to-right', function(event) {
		event.preventDefault();
		headimgSwipe.next();
	});

	// 点击“我要就坐”弹出表单
	$("ul.controller").on('click', 'li.add', function (event) {
	    var inseat = $("#inseat").val();

		if(inseat == 1){
			showToastInfo("您已就坐！");
			return false;
		}

		$("#sitting-form").modal({
			overlayClose: true,
			opacity: 80
		});
	});

	// 就坐
	$("#sitting-form").on("submit", function( event ){
		event.preventDefault();

		var telRegPattern = /^1[3-8]+\d{9}$/;

		if($("input#tel").val() === ""){
			showToastInfo("请填写手机号！");
			return false;
		} else if($("input[name=tel]").val().match(telRegPattern) === null){
			showToastInfo("请填写正确的11位手机号！");
			return false;
		}

		if($("input#wx-id").val() === ""){
			showToastInfo("请填写微信号！");
			return false;
		}
        
		var phone = encodeURIComponent($("input#tel").val());
		var weixin = encodeURIComponent($("input#wx-id").val());
		var openid = $("input#openid").val();
		var oid = $("input#oid").val();

		var url = "InSeat.aspx";
		var data = "phone=" + phone + "&weixin=" + weixin + "&openId=" + openid + "&oid=" + oid;

        // 提交到服务器
		$.post(url, data, function (jd) {
		    if (jd.IsSuccess) {
		        // 验证通过则关闭模态窗口
		        $.modal.close();

		        //// 将用户头像和昵称置入头像slider
		        //$("#headimg-slider li:eq(0) >img").attr('src', 'images/headimg/head-me.jpg');
		        //$("#headimg-slider li:eq(0) >p").text('Bright');

		        // 使发表心语的文本域可用
		        //$("#wish-form textarea#my-wish").attr('disabled', false);
		        // 更新文本域的提示
		        //$("#wish-form textarea#my-wish").attr('placeholder', '成功就坐，赶紧发表心语吧！');

		        // 设置本地存储，将用户就坐标志设为真
		        // sessionStorage.isUsrSitted = true;
		        showToastInfo("就坐成功！");
		        setTimeout("window.location.reload()", 1500);	        

		    } else {
		        showToastInfo(jd.Message);
		    }
		});

		return false;
	});


	// 发表心语
	$("#wish-form").on("submit", function( event ){
		event.preventDefault();

		if($("textarea#my-wish").val() === ""){
			showToastInfo("请输入内容！");
			return false;
		}

		var openid = $("input#openid").val();        
		var content = $("textarea#my-wish").val();
		var oid = $("input#oid").val();
		var url = "PostSeatComment.aspx";
		var data = "openid=" + openid + "&content=" + encodeURIComponent(content) + "&oid=" + oid;
        
		$.post(url, data, function (jd) {
		    if (jd.IsSuccess) {

		        // 验证通过后立即发表心语
		        var usrNickName = $("#wx-nickname").val();
		        var usrImgUrl = $("#wx-headimg").val();

		        var $newWishArticle = $('<article></article>');
		        var $img = $("<img class='headimg'" + 'src=' + usrImgUrl + " />");

		        var textString = "<div class='text'>" +
                                    "<p class='caption'>" +
                                    "<!-- 昵称 -->" +
                                        "<span class='nickname'>" +
                                            usrNickName +
                                        "</span>" +
                                        "<span>，刚刚</span>" +
                                    "</p>" +
                                    "<!-- 心语正文 -->" +
                                    "<p class='content'>" +
                                        content +
                                    "</p>" +
                                "</div>";

		        $img.appendTo($newWishArticle);
		        $(textString).appendTo($newWishArticle);
		        $newWishArticle.insertAfter('#wish-form').hide().slideDown('500');
		        $("textarea#my-wish").val("");
		    } else {
		        showToastInfo(jd.Message);
		    }
		});	
	});

	// 提示错误消息
	function showToastInfo(info){
		if($("label.error").length === 0){
			$("<label class='error'></label>").text(info).appendTo($("body")).show().delay(1000).fadeOut(600);
		} else{
			$("label.error").text(info).show().delay(1000).fadeOut(600);
		}
	}

	// 文本输入框禁用提示
	$("#wish-form").on('tap', 'textarea[disabled]', function(event) {
		event.preventDefault();
		showToastInfo("就坐后才可以发心语哦！");
	});

	// 点击在线用户头像获取联系方式
	$('#headimg-slider').on('tap', 'li:not(".gone") img', function(event) {
	    event.preventDefault();

	    var inseat = $("#inseat").val();
		
	    if (inseat == 1) {
	        var weixinId = $(this).attr("data-wxid");
	        var phone = $(this).attr("data-phone");
            
	        $('#usr-id').text(weixinId);
	        $('#usr-phone').text(phone);

			$('#usr-contact').addClass('show');
			setTimeout("$('#usr-contact').removeClass('show')", 3000);
		} else{
			showToastInfo('就坐后才能联系Ta哦！');
		}
		
	});

	// 用户离线提示
	$('#headimg-slider').on('tap', 'li.gone img', function(event) {
		event.preventDefault();
		
		showToastInfo('用户已经离开酒吧！');
	});

	// 滑到页面底部自动加载下一页心语
	$(window).on('scroll', function(){
		var windowInnerHeight = window.innerHeight,
			bodyOuterHeight = $('body').outerHeight(),
			windowScrollTop = $(window).scrollTop();
		if( windowInnerHeight+windowScrollTop >= bodyOuterHeight ){
			$('.loading').show();
			
			$newWishList = $('<div></div>');
			$newWishList.html($('#wish-article-template').html());

			$.getJSON('wish.json?id='+Math.random(), function(data){
				$.each(data.wish, function(wishItemIndex, wishItem){
					var $tmpItem = $newWishList.find('article').first().clone();
					$tmpItem.find('img').attr('src', wishItem.imgSrc);
					$tmpItem.find('.nickname').text(wishItem.usrNickName);
					$tmpItem.find('span:eq(1)').text(wishItem.time);
					$tmpItem.find('.content').text(wishItem.content);

					$tmpItem.appendTo($newWishList);
					$tmpItem = null;
				});
				$newWishList.find('article').eq(0).remove();
				$('.loading').hide();
				$newWishList.find('article').appendTo($('.article-wrap')).show();
			});
		}
	});
});