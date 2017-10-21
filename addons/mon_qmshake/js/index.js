/**
 * 
 * @authors tianyanrong
 * @date    2015-1-21
 * @version 
 */
;(function($) {
	/*地区*/

	//API
	var Api = {
		cacheData: null,
		slotLoading: 0,
		$submit: $('#startBtn'),
		getSMi: function() {
			return false;
		},
		loading: function(isShow) {
			if(isShow) {
				window.loading.show();
			}
			else {
				window.loading.hide();
			}
		},
		setSlotStop: function(data, slot) {

		},
		setBtnText: function(freeTimes,leftShare,awardCount) {

			this.$submit.text("今天还有"+freeTimes+"次机会！");

			if(freeTimes) {
				$('#gesture').show();
				$('#gesture2').hide();
			} else {
				if (leftShare ==0) {
					$('#gesture').hide();
					$('#gesture2').show();
					this.$submit.text("下次再来玩吧！");
				}
				if (leftShare >0 ) {
					$('#gesture').show();
					$('#gesture2').hide();
					shareDialogMsg("您还有"+leftShare+"次分享奖励机会，每次分享奖励"+awardCount+"抽奖机会");
					this.$submit.text("还有奖励抽奖机会！");
				}

			}

		},
		triggerSlotStart: function() {
			// 触发摇奖功能
			var data = this.cacheData;
		
			var shakePrize = new ShakePrize({
				$btn: this.$submit,
				$zoomIn: $('#zoomIn'),
				$bar: $('#slot_bar'),
				$dotDefault: $('#dotDefault'),
				$dotHighlight: $('#dotHighlight'),
				$gesture: $('#gesture'),
				$doorL: $('#doorL'),
				$doorR: $('#doorR'),
				$ruleBtn: $("#ruleLink"),
				$dialogCove: $('#dialog_cove'),
				$linkItem: $(".linkItem"),
				$winPrize: $("#win_prize"),//no
				$successDialog: $('#success_dialog'),
				$endErrorDialog: $('#end_error_dialog'),
				$errorDialog: $('#error_dialog'),
				$btnReset: $('#btn_reset'),
				audios: {
					$startShake: $('#audio_start_shake'),
					$startWin: $('#audio_start_win')
				},
				api: Api
			});
			this.setBtnText(data.free,data.leftShare,data.awardCount);
			if(false === this.setSlotStop(data, shakePrize)) {
				return false;
			}

			shakePrize.free = data.free;
			shakePrize.start();
		},
		getPageUrl: function(url) {
			return url;
		},
		fetchData: function() {
			var winnerList = new window.List({
				Render: window.Render,
				ScrollLoading: window.ScrollLoading,
				$loading: $('.tx_loading'),
				keyName: 'datas'
			});
		},
		getGiftSubmit: function(winFn, notWinFn) {

            var _this = this;
			$.ajax({
				url: PrizeUrl,
				type: 'POST',
				dataType:'json',
				success: function(data) {
					if (data.code == 200) {
						if (data.flag == 1) {
							winFn(data);
						} else {
							Api.setBtnText(data.leftCount,data.leftShare,data.awardCount);
                                if (data.leftCount > 0) {
									notWinFn();
								}


						}
					} else {
						//notWinFn();
						if (data.code == 506) {
							shareDialogMsg(data.msg);
						} else {
							dialogMsg(data.msg);
						}

						//winMsg(data.msg);
					}

					//if(data.apistatus === 1 && data.result.flag) {
					//	if(data.result.img) {
					//		data.result.img = 'data:image/png;base64,'+data.result.img;
					//	}
					//	winFn(data.result);
					//}
					//else {
					//	notWinFn(data.result);
					//}
					//
				},
				error: function() {
					//notWinFn();
					dialogMsg("服务器稍微忙稍后再试!");
				}
			})
		},
		parseData: function(data) {
			data.result.isCityShow = true;
			return data;
		}
	};


	if(Api.getSMi()) {
		Api.parseData = function(data) {
			data.result.city = [];
			data.result.title = data.result.title;
			data.result.isCityShow = false;
			if(data.result.slot_machine.items) {
				var i, k;
				for(i = 0, k = data.result.slot_machine.items.length; i < k; i++) {
					data.result.slot_machine.items[i].remain = '';
				}
			}
			return data;
		};
		Api.getPageUrl = function(url) {
			return url;
		};
	}


	/*活动规则*/
	$('#btn_active_rule').click(function() {
		$('.active_rule_box').css({
			display: 'block'
		});
		$('.dialog_cover').css({
			display: 'block'
		})
	});


	$('.active_rule_box .close').click(function() {
		$('.active_rule_box').css({
			display: 'none'
		});
		$('.dialog_cover').css({
			display: 'none'
		})
	});
	$('#tx_btnCloseProduct').click(function() {
		$('.prodoct_box').remove();
	})

	var winerList = function($currentUl, allLong, time) {
		if(!$currentUl) {
			var $box = $('.winner_list');
			var $ul = $('.winner_list ul');
			var $el = $('.winner_list ul li');
			
			$currentUl = $ul.eq(0);			
			allLong = 0;
			var ulWidth = $box.width();
			$el.each(function() {
				allLong += $(this).width();
			});
			time = parseInt(allLong/ulWidth, 10)*30;

			if(!$el.length || ($el.length && $el.length < 2)) {
				return;
			}
		}
		var $clone = $('<ul></ul>');
		$clone.html($currentUl.html());
		$currentUl.after($clone);
		var $next = $currentUl.next();

		$currentUl.css({
			"transition":"margin "+time+"s linear",
			"-moz-transition":"margin "+time+"s linear",
			"-webkit-transition":"margin "+time+"s linear"
		})
		$currentUl.css({
			"margin": "0 0 0 -"+ (allLong+20) + 'px'
		});
		setTimeout(function() {
			winerList($next, allLong, time);
			$currentUl.remove();

		}, time*800)
	}

	/**/
	window.List.prototype.isLoadMoreData = false;
	window.List.prototype.triggerEvents = function(data) {
		window.isApiLoaded = true;
		window.loading.hide();
		Api.cacheData = data;

		//开始老虑机
		if(false === Api.triggerSlotStart()) {
			return false;
		}

		//预加载老虑机结果跳转页
		//$('#end_error_dialog').attr('src', SuccessUrl);
		//$('#success_dialog').attr('src', SuccessUrl);
		
		//执行中奖名单轮播效果
		setTimeout(function() {
			winerList()
		}, 2000);
	 }

	window.List.prototype.getResultData = function(data) {

		data = Api.parseData(data);
		data.result.slot_machine = data.result.slot_machine || {}
		var i, k;
		if(data.result.slot_machine.items) {
			data.result.isHasItems = true;
			for(i = 0, k = data.result.slot_machine.items.length; i < k; i++) {
				if('' !== data.result.slot_machine.items[i].remain) {
					data.result.slot_machine.items[i].remain = '数量：'+ data.result.slot_machine.items[i].remain;
				}
				data.result.slot_machine.items[i].goods_img = IMG_PRE+data.result.slot_machine.items[i].goods_img;
				data.result.slot_machine.items[i].tgs = "提供商:<a href='"+data.result.slot_machine.items[i].tgs_url+"' style='font-size:1em'>" +data.result.slot_machine.items[i].tgs +"<a/>" ;

			}
		}

		else {
			data.result.slot_machine.items = [];
		}

		data.result.nextCursor =-1;
		data.result.is_show_lucky_guy_list = true;
		

		if(this.isLoadMoreData) {
			data.result.city = []
			data.result.lucky_guy_list = [];
			data.result.is_show_lucky_guy_list = this.is_show_lucky_guy_list;
		}
		else {
			this.triggerEvents(data.result);
			if(!data.result.lucky_guy_list) {
				data.result.lucky_guy_list = [];
			}
			if(!data.result.lucky_guy_list.length) {
				data.result.is_show_lucky_guy_list = false;
			}
			this.is_show_lucky_guy_list = data.result.is_show_lucky_guy_list;
		}
		this.isLoadMoreData = true;
		return data;
	};
	window.List.prototype.parseData =  function(data) {
		return data;
	}
	Api.fetchData();
	
})(Zepto);



