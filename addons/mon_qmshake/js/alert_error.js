/**
 * 
 * @authors jRx (you@example.org)
 * @date    2015-01-20 16:08:18
 * @version $Id$
 */
(function($){	
	var AlertError = function() {
		this.setTemplate();
	};
	AlertError.prototype = {
		setTemplate: function() {
			this.$el = $('<div class="alert_error_box" style="display:none;"><div class="imgs"></div><div class="info tx_info"></div><button type="button" class="buttonDefault" style="display:none;">重新获取位置</button></div>');
			$('body').append(this.$el);
			this.$info = this.$el.find('.tx_info');
			this.$button = this.$el.find('button');
			this.$button.click(function() {
				window.location.reload();
			});
		},
		show: function(msg, isShowButton) {
			this.$info.html(msg);
			this.$el.show();
			if(isShowButton) {
				this.$button.show()
			}else{
				this.$button.hide()
			}
		}
	};
	window.alertError = new AlertError();
})(Zepto);
