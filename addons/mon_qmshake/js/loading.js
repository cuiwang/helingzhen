/**
 * 
 * @authors tianyanrong
 * @date    2014-12-22
 * @version 
 */
;(function() {
	var Loading = function() {
		this.render();
		this.bindEvents();
		this.show();
	};
	Loading.prototype = {
		/*
		* @description 绑定事件方法(兼容浏览器方案)
		*/
		addEvent: function(target, type, method) {
			target = target || document;
			if(target.addEventListener) {
				target.addEventListener(type, method, false);
			}
			else if (target.attachEvent) {
				target.attachEvent('on'+type, method);
			}
		},
		bindEvents: function() {
			this.addEvent(this.element, 'touchmove', function(event) {
				if(window.event) {
					window.event.preventDefault();
				}
				event.stopPropagation();	
			});
			this.addEvent(this.element, 'mousemove', function(event) {
				if(window.event) {
					window.event.preventDefault();
				}				
				event.stopPropagation();	
			});
		},
		getTemplate: function() {
			this.element = document.getElementById('tx_pageLoading');
			if(this.element) {
				this.varElement = document.getElementById('loading_var');
				return;
			}
			this.element = document.createElement('div');
			this.element.style.display = 'none';
			this.element.className = 'loading tx_pageLoading';
			this.element.innerHTML = '<center><var id="loading_var">0%</var><sub></sub></center>';
			document.body.appendChild(this.element);
			this.varElement = document.getElementById('loading_var');
		},
		render: function() {
			this.getTemplate();
		},
		start: function(value) {
			var _this = this, newValue;
			if(this.timeValue) {
				clearTimeout(this.timeValue);
				this.timeValue = null;
			}
			this.timeValue = setTimeout(function() {
				clearTimeout(_this.timeValue);
				_this.timeValue = null;
				newValue = value + _this.step;
				if(newValue > 100) {
					newValue = 100;
				}
				_this.varElement.innerHTML = newValue + '%';
				
				if(newValue < 100) {
					_this.start(newValue);
				}
				else{
					_this.element.style.display = 'none';
				}
			}, this.time);
			
		},
		show: function(msg) {
			window.scrollTo(0,0);
			this.step = 5;
			this.time = 600;
			this.element.style.display = '';
			this.varElement.innerHTML = '0%';
			this.start(0);
		},
		hide: function() {
			this.time = 60;
		}
	};
	
	document.addEventListener( "DOMContentLoaded", function(){
		window.loading = new Loading();
	}, false );
	
})();