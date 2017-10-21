

 /**
  * 提示框
  * @param title 标题
  * @param message 提示内容
  * @param func 确定执行方法
  */
 function confirmModal(title, message, func){
	 $.confirm({
			'title'		: title,
			'message'	: message,
			'buttons'	: {
				'确定'	: {
					'class'	: 'blue',
					'action': function(){
						func();
						}
				},
				'取消'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
 }

(function($){
	$("body").append("<section class=\"pop\" style=\"display: none;\">");
	$.confirm = function(params){
		if($('#confirmOverlay').length){
			// A confirm is already shown on the page:
			return false;
		}
		
		var buttonHTML = '';
		$.each(params.buttons,function(name,obj){
			
			// Generating the markup for the buttons:
			
//			buttonHTML += '<a href="#" class="button '+obj['class']+'">'+name+'<span></span></a>';
			buttonHTML += '<li><a href="#0">'+name+'</a></li>';
			if(!obj.action){
				obj.action = function(){};
			}
		});
		
		var markup = [
		    /*'<div class="pop-content">',
        	'	<div class="pop-title">',
        	'		主题：攀爬凤攀爬凤凰山',
        	'		<p>发布时间：2015-6-26 25:11</p>',
        	'	</div>',
            '	<div class="pop-button">',
            '		<a href="#" class="pop-confirm">确定</a>',
            '		<a href="#" class="pop-cancel">取消</a>',
            '	</div>',
            '	<a href="#" class="pop-close">×</a>',
            '</div>'*/
			'<div class="cd-popup is-visible" role="alert" id="confirmOverlay">',
	        '<div class="cd-popup-container" id="confirmBox">',
	        '<p>'+params.message+'</p>',
	        '<ul class="cd-buttons" id="confirmButtons">',
	        buttonHTML,
	        '</ul>',
	        '<a class="cd-popup-close img-replace">关闭</a>',
	        '</div></div>'
		].join('');
		
		$(markup).hide().appendTo('body').fadeIn();
		
		var buttons = $('#confirmBox li'),
			i = 0;
		
		$('.cd-popup-close').click(function(){
			$(this).parents("#confirmOverlay").remove();
		});
		
		$.each(params.buttons,function(name,obj){
			buttons.eq(i++).click(function(){
				
				// Calling the action attribute when a
				// click occurs, and hiding the confirm.
				
				obj.action();
				$.confirm.hide();
				return false;
			});
		});
	}

	$.confirm.hide = function(){
		$('#confirmOverlay').fadeOut(function(){
			$(this).remove();
		});
	}
	
})(jQuery);