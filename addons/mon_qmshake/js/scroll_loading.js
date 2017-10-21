/**
 * 
 * @authors tianyanrong
 * @date    2014-11-14
 * @version 
 */
;(function($) {
	var ScrollLoading = function(options) {
		this.$window = $(window);
		this.bindEvents = {};
		this.minHeight = 40;
	}
	ScrollLoading.prototype = {
		bind: function(name, fn) {
			this.bindEvents[name] = fn;
		},
		trigger: function(name, data) {
			if(this.bindEvents[name]) {
				this.bindEvents[name](data);
			}
		},
		init: function() {
			var _this = this;
			this.$window.bind('scroll', function() {
				_this.scroll()
			})
		},
		scroll: function() {
			var docHeight = document.body.clientHeight, //页面总高度
				viewHeight = window.scrollY+window.screen.height; //页面已显示的高度 

			if(this.minHeight > (docHeight-viewHeight)){
				this.$window.unbind('scroll')
				this.trigger('event-fetch');
			}
		}
	}

	window.ScrollLoading = ScrollLoading;
	
})(Zepto);