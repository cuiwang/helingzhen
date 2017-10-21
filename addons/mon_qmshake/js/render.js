/**
 * 
 * @authors tianyanrong
 * @date    2014-11-14
 * @version 
 */
;(function($) {
	var Render = function(options) {
		this.ngRepeatElements = {};
		this.bindEvents = {}
	}
	Render.prototype = {
		getContainer: function() {
			return $('body');
		},
		bind: function(name, fn) {
			this.bindEvents[name] = fn;
		},
		trigger: function(name, data) {
			if(this.bindEvents[name]) {
				this.bindEvents[name](data);
			}
		},
		render: function(data) {
			var _this = this, key;
			
			for(key in this.ngRepeatElements) {
				this.renderNgRepeat(this.ngRepeatElements[key].$element, data);
			}

			var $ngRepeat = $('[ng-repeat]');
			$ngRepeat.each(function() {
				_this.renderNgRepeat($(this), data);
			});

			this.renderNg(this.getContainer(), data, 'ng-bind');
			this.renderNg(this.getContainer(), data, 'ng-src');
			this.renderNg(this.getContainer(), data, 'ng-href');
			this.renderNgShow(this.getContainer(), data);
			this.trigger('event-fetch-success', data);
		},
		getScopeData: function(keys, data) {
			if(!keys || !data) {
				return;
			}
			keys = keys.indexOf('.') ? keys.split('.') : [keys];
			var i, k;
			var scopeData = data;
			for(i = 0, k = keys.length; i < k; i++) {
				if(scopeData) {
					scopeData = scopeData[keys[i]];
				}
			}
			return scopeData;
		},
		renderNgShow: function($container, scopeData) {
			var _this = this;			
			var $element = $container.find('[ng-show]');
			$element.each(function() {
				var isShow = false;
				var data;
				var values = $(this).attr('ng-show');
				values = values.replace(/(^\s+)|(\s+$)/g,"");
				if(values.match(/^\!\w+$/)) {
					data = _this.getScopeData(values.replace('!', ''), scopeData);
					if(!data) {
						isShow = true;
					}
					if(isShow) {
						this.style.display = '';
					}
					else {
						this.style.display = 'none';
					}
				}
				else if(values.match(/^\w+$/)) {
					data = _this.getScopeData(values.replace('!', ''), scopeData);
					if(data) {
						isShow = true;
					}
					if(isShow) {
						this.style.display = '';
					}
					else {
						this.style.display = 'none';
					}
				}
				else {
					oldValues = values;
					values = values.split(/\s+/);
					var i = 0;
					var is_has_value = false;
					_this.each(values, function(value) {
						if(!is_has_value) {
							if(value.match(/^\w+$/) || 
								(value.match(/\./) && value.match(/\w/))) {
								data = _this.getScopeData(value, scopeData);
								
								if(data) {
									values[i] = data;
									i++;
									is_has_value = true;
								}
							}
						}
					})
					var newValues = values.join(' ');
					if(newValues !== oldValues) {						
						isShow = eval(newValues);
						if(isShow) {
							this.style.display = '';
						}
						else {
							this.style.display = 'none';
						}
					}
				}
				
			});
		},
		renderNg: function($container, scopeData, type) {
			var _this = this;
			var $element = $container.find('['+type+']');
			$element.each(function() {
				var key = $(this).attr(type);
				var data = _this.getScopeData(key, scopeData);
				switch(type) {
				case 'ng-bind':
					$(this).html(data);
					break;
				case 'ng-src':
					$(this).attr(type.replace('ng-', ''), data);
					break;
				case 'ng-href':
					$(this).attr(type.replace('ng-', ''), data);
					break;
				}
				if(data || data === 0) {
					this.removeAttribute(type);
				}
			})
		},
		renderNgRepeat: function($element, data) {
			var name = $element.attr('ng-repeat');
			var keys = name.split(/\s+in\s+/);
			if(!this.ngRepeatElements[name]) {
				$element[0].style.display = '';
				this.ngRepeatElements[name] = {
					$element: $element,
					$container: $element.parent()
				};
				$element.remove();
			}

			var repeatsData = this.getScopeData(keys[1], data);
			this.each(repeatsData, function(data) {
				var $newElement = this.ngRepeatElements[name].$element.clone();
				$newElement[0].removeAttribute('ng-repeat');
				var scopeData = {}
				scopeData[keys[0]] = data;
				this.renderNg($newElement, scopeData, 'ng-bind');
				this.renderNg($newElement, scopeData, 'ng-src');
				this.renderNg($newElement, scopeData, 'ng-href');
				this.ngRepeatElements[name].$container.append($newElement);
				this.renderNgShow($newElement, scopeData);
			});
		},
		each: function(data, fn) {
			var i, k;
			for(i = 0, k = data.length; i < k; i++) {
				fn.call(this, data[i]);
			}
		}
	}

	window.Render = Render;
	
})(Zepto);