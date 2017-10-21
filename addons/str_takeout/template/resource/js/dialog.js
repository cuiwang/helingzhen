$.fn.dialog = function(opts){
	function init(opts){
		var opts = $.extend({
			title: '',
			mask: true,
			closeBtn: false,
			closeCb: function(){},
			initDialog: false //初始化dialog -- 不显示
		}, opts);

		var el = $(this),
			_dialog,
			myScroller;

		function initScroller(){
			// scroller
			var maxH = $(window).height() - 40 - (opts.title ? 42 : 0),
				_scroller = el.parent();

			if(el.outerHeight(true) > maxH){
				_scroller.height(maxH);
				if(typeof Scroller != 'undefined'){
					myScroller = new Scroller(_scroller[0], {scrollX: false});
				}
			};
		}

		if(el.hasClass('dialog_content')){
			_dialog = $(this).parents('.dialog');

			_dialog.show();
			initScroller();
			return false;
		}

		function close(){
			_dialog.hide();
			el.parent().removeAttr('style');
			if(myScroller){
				myScroller.destory();
			}
			opts.closeCb();
		}

		el.addClass('dialog_content').wrap('<div class="dialog"><div class="dialog_wrap"><div class="dialog_scroller"></div></div></div>').show();

		_dialog = el.parents('.dialog');

		if(opts.initDialog){
			_dialog.hide();
		}
		if(opts.mask){
			_dialog.addClass('mask');
		}

		if(opts.title){
			el.parent().before('<div class="dialog_tt">'+ opts.title +'</div>');
		}
		if(opts.closeBtn){
			$('<a href="javascript:void(0);" class="dialog_close"></a>').insertBefore(el.parent()).bind('click', close);
		}

		_dialog.bind('click', close);
		_dialog.find('.dialog_wrap').click(function(e){
			e.stopPropagation();
		});

		initScroller();
		return _dialog;
	}

	
	if(opts === 'close'){
		var obj = $(this).hasClass('dialog_content') ? $(this).parents('.dialog') : $(this);
		obj.hide();
	}else{
		return init.call(this, opts);
	}
};