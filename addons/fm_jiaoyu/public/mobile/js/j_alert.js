// jQuery Alert Dialogs Plugin

// 插件适用于新版的jquery（比如1.10.1） 版本
// Usage:
//		jAlert( message, [title, callback] )
//		jConfirm( message, [title, callback] )
//		jPrompt( message, [value, title, callback] )

//
(function($) {
	
	$.alerts = {
		
		// These properties can be read/written by accessing $.alerts.propertyName from your scripts at any time
		
		verticalOffset: -75,                // vertical offset of the dialog from center screen, in pixels
		horizontalOffset: 0,                // horizontal offset of the dialog from center screen, in pixels/
		repositionOnResize: true,           // re-centers the dialog on window resize
		overlayOpacity: .01,                // transparency level of overlay
		overlayColor: '#FFF',               // base color of overlay
		draggable: true,                    // make the dialogs draggable (requires UI Draggables plugin)
		okButton: '&nbsp;好&nbsp;',         // text for the OK button
		cancelButton: '&nbsp;否&nbsp;', // text for the Cancel button
		dialogClass: null,                  // if specified, this class will be applied to all dialogs
		
		
		//Jtips 参数
		bgColor : 'rgba(0,0,0,.7)',         //默认背景颜色
		fontColor : '#fff',				//默认字体颜色
		showTime : '2',				//默认显示时间（秒）
		
		
		// Public methods
		
		alert: function(message, title, callback) {
			if( title == null ) title = '提示';
			$.alerts._show(title, message, null, 'alert', function(result) {
				if( callback ) callback(result);
			});
		},
		
		confirm: function(message, title, callback) {
			if( title == null ) title = 'Confirm';
			$.alerts._show(title, message, null, 'confirm', function(result) {
				if( callback ) callback(result);
			});
		},
			
		prompt: function(message, value, title, callback) {
			if( title == null ) title = 'Prompt';
			$.alerts._show(title, message, value, 'prompt', function(result) {
				if( callback ) callback(result);
			});
		},
		
		tips: function(message, callback, color , value) {
			$.alerts._show(value, message, color, 'tips', function(result) {
				if( callback ) callback(result);
			});
		},
		
		// Private methods
		
		_show: function(title, msg, value, type, callback) {
			
			$.alerts._hide();
			$.alerts._overlay('show');
			
			$("BODY").append(
			  '<div class="dialog_bg"></div><div id="popup_container">' +
			    '<div id="popup_content">' +
			      '<div id="popup_message"></div>' +
				'</div>' +
			  '</div>');
			
			if( $.alerts.dialogClass ) $("#popup_container").addClass($.alerts.dialogClass);
			
			// IE6 Fix
			//var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed'; 
			var pos = ('undefined' == typeof (document.body.style.maxHeight)) ? 'absolute' : 'fixed';

			$("#popup_container").css({
				position: pos,
				zIndex: 999999,
				padding: 0,
				margin: 0
			});
			
			//$("#popup_title").text(title);
			$("#popup_content").addClass(type);
			$("#popup_message").text(msg);
			$("#popup_message").html( $("#popup_message").text().replace(/\n/g, '<br />') );
			
			$("#popup_container").css({
				minWidth: $("#popup_container").outerWidth(),
				maxWidth: $("#popup_container").outerWidth()
			});
			
			$.alerts._reposition();
			$.alerts._maintainPosition(true);
			
			switch( type ) {
				case 'alert':
					$("#popup_message").after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" class="w_100" /></div>');
					$("#popup_ok").click( function() {
						$.alerts._hide();
						callback(true);
					});
					$("#popup_ok").focus().keypress( function(e) {
						if( e.keyCode == 13 || e.keyCode == 27 ) $("#popup_ok").trigger('click');
					});
				break;
				case 'confirm':
					$("#popup_message").after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /><span class="line_s"> </span><input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /></div>');
					$("#popup_ok").click( function() {
						$.alerts._hide();
						if( callback ) callback(true);
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback(false);
					});
					$("#popup_ok").focus();
					$("#popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
				break;
				case 'prompt':
					$("#popup_message").append('<br /><input type="text" size="30" id="popup_prompt" />').after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /><span class="line_s"> </span> <input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /></div>');
					$("#popup_prompt").width( $("#popup_message").width() );
					$("#popup_ok").click( function() {
						var val = $("#popup_prompt").val();
						$.alerts._hide();
						if( callback ) callback( val );
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback( null );
					});
					$("#popup_prompt, #popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
					if( value ) $("#popup_prompt").val(value);
					$("#popup_prompt").focus().select();
				break;
				case 'tips':
					if('undefined' == typeof(title)){
						title = '3';  //默认显示时间
					}
					
//					if('undefined' != typeof(value.bgColor)){
//						$.alerts.bgColor = value.bgColor;   //默认背景颜色
//		
//					}
//					if('undefined' != typeof(value.fontColor)){
//		
//						$.alerts.fontColor = value.fontColor;   //默认背景颜色
//					}
					
					if(typeof(value) != 'undefined'){
						$.alerts._setOption(value);     //初始化参数
					}

					$(".dialog_bg").hide();
					$("#popup_message").css({
						'color':$.alerts.fontColor,
						'background':$.alerts.bgColor,
						'border-radius': '5px',
						'font-weight':'normal',
						'position': 'fixed',
						'width': '140px',
						'left': '50%',
						'margin-left': '-95px',
						'font-size': '14px',
						'top': '50%',
						'margin-top': '-25px',
						'z-index': '1000',	
						'padding': '10px 20px',
                      
						});
	
					
					
					setTimeout(function(){
						$.alerts._hide();
						$("." + type).fadeOut('slow');
						if( callback ) callback( callback );
					},$.alerts.showTime + '000');				
				break;
			}
			
			// Make draggable
			if( $.alerts.draggable ) {
				try {
					//$("#popup_container").draggable({ handle: $("#popup_title") });
					//$("#popup_title").css({ cursor: 'move' });
				} catch(e) { /* requires jQuery UI draggables */ }
			}
		},
		
		_hide: function() {
			$(".dialog_bg").remove();
			$("#popup_container").remove();
			$.alerts._overlay('hide');
			$.alerts._maintainPosition(false);
		},
		
		_overlay: function(status) {
			switch( status ) {
				case 'show':
					$.alerts._overlay('hide');
					$("BODY").append('<div id="popup_overlay"></div>');
					$("#popup_overlay").css({
						position: 'absolute',
						zIndex: 99998,
						top: '0px',
						left: '0px',
						width: '100%',
						height: $(document).height(),
						background: $.alerts.overlayColor,
						opacity: $.alerts.overlayOpacity
					});
				break;
				case 'hide':
					$("#popup_overlay").remove();
				break;
			}
		},
		
		_reposition: function() {
			var top = (($(window).height() / 2) - ($("#popup_container").outerHeight() / 2)) + $.alerts.verticalOffset;
			var left = (($(window).width() / 2) - ($("#popup_container").outerWidth() / 2)) + $.alerts.horizontalOffset;
			if( top < 0 ) top = 0;
			if( left < 0 ) left = 0;
			
			// IE6 fix
			if ('undefined' == typeof (document.body.style.maxHeight)) top = top + $(window).scrollTop();
			
			$("#popup_container").css({
				top: top + 'px',
				left: left + 'px'
			});
			$("#popup_overlay").height( $(document).height() );
		},
		
		_maintainPosition: function(status) {
			if( $.alerts.repositionOnResize ) {
				switch(status) {
					case true:
						$(window).bind('resize', function() {
							$.alerts._reposition();
						});
					break;
					case false:
						$(window).unbind('resize');
					break;
				}
			}
		},
		
		_setOption: function(options){
			$.alerts.fontColor = options.fontColor || $.alerts.fontColor; 
			$.alerts.bgColor = options.bgColor || $.alerts.bgColor;
			$.alerts.showTime = options.showTime || $.alerts.showTime;
		}
		
	}
	
	// Shortuct functions
	jAlert = function(message, title, callback) {
		$.alerts.alert(message, title, callback);
	}
	
	jConfirm = function(message, title, callback) {
		$.alerts.confirm(message, title, callback);
	};
		
	jPrompt = function(message, value, title, callback) {
		$.alerts.prompt(message, value, title, callback);
	};
	
	jTips = function(message, value, title, callback) {
		$.alerts.tips(message, value, title, callback);
	};
	
	jWarn=function(message){
		$("BODY").append(
			  '<div class="dialog_bg"></div><div id="popup_container" class="popup_container">' +
			    '<div id="popup_content">' +
			      '<div id="popup_message">'+ message +'</div>' +
				'</div>' +
			  '</div>');
			  			
			var left = (($(window).width() / 2) - ($("#popup_container").width() / 2)) ;
			if( left < 0 ) left = 0;
			left=left+"px";
			$(".popup_container").css({"left":left});
		}
	
})(jQuery);