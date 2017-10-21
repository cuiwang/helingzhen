(function (window, document, undefined) {
	
	// 常用方法
	var utils = {
			addEvent: function(el, type, fn, capture){
				el.addEventListener(type, fn, !!capture);
			},
			removeEvent: function(el, type, fn, capture){
				el.removeEventListener(type, fn, !!capture);
			},
			each: function(obj, callback){
				var i = 0,
					name,
					isArray = Object.prototype.toString.call(obj) === '[object Array]';
				
				if(isArray){
					for(; i < obj.length;){
						callback(i, obj[i++]);
					}
				}else{
					for(name in obj){
						callback(name, obj[name]);
					}
				}
			},
			prefixStyle: function(style){
				var _elementStyle = document.createElement('div').style,
					_vendor = (function () {
						var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
							transform,
							i = 0,
							l = vendors.length;

						for (; i < l; i++) {
							transform = vendors[i] + 'ransform';
							if (transform in _elementStyle) return vendors[i].substr(0, vendors[i].length-1);
						}

						return false;
					})();

				if (_vendor === false) return false;
				if (_vendor === '') return style;
				return _vendor + style.charAt(0).toUpperCase() + style.substr(1);
			},
			//滑动趋势
			momentum: function (current, start, time, lowerMargin, wrapperSize, deceleration) {
				var distance = current - start,
					speed = Math.abs(distance) / time,
					destination,
					duration;

				deceleration = deceleration === undefined ? 0.0006 : deceleration;

				destination = current + ( speed * speed ) / ( 2 * deceleration ) * ( distance < 0 ? -1 : 1 );
				duration = speed / deceleration;

				if ( destination < lowerMargin ) {
					destination = wrapperSize ? lowerMargin - ( wrapperSize / 2.5 * ( speed / 8 ) ) : lowerMargin;
					distance = Math.abs(destination - current);
					duration = distance / speed;
				} else if ( destination > 0 ) {
					destination = wrapperSize ? wrapperSize / 2.5 * ( speed / 8 ) : 0;
					distance = Math.abs(current) + destination;
					duration = distance / speed;
				}

				return {
					destination: Math.round(destination),
					duration: duration
				};
			}
		},
		support = (window.Modernizr && Modernizr.touch === true) || (function () {
	        return !!(('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch);
	    })(),
	    eventName = {
	    	start: support ? 'touchstart.sc' : 'mousedown.sc',
	    	move: support ? 'touchmove.sc' : 'mousemove.sc',
	    	end: support ? 'touchend.sc' : 'mouseup.sc'
	    };

	function Scroller(el, options){
		//default options
		this.options = {
			scrollX: true,
			scrollY: true
		}
		for(var i in options){
			this.options[i] = options[i];
		}
		
		this.init(el);
	}
	
	Scroller.prototype = {
		init: function(el){
			this.wrapper = typeof el === 'string' ? document.querySelector(el) : el;
			this.scroller = this.wrapper.children[0];
			this.scrollerStyle = this.scroller.style;
			this.x = 0;
			this.y = 0;
	
			// 获取隐藏元素的宽高
			var styles = getComputedStyle(this.wrapper, null),
				isHide = styles.display == 'none',
				props = {position: 'absolute', visibility: 'hidden', display: 'block'},
				oldStyle = {};

			if(isHide){
				for ( var name in props ) {
					oldStyle[name] = styles[name];
					this.wrapper.style[name] = props[name];
				}
			}

			this.wrapperWidth = this.wrapper.clientWidth;
			this.wrapperHeight = this.wrapper.clientHeight;
			this.scrollerWidth = this.scroller.offsetWidth;
			this.scrollerHeight	= this.scroller.offsetHeight;
			this.maxScrollX	= this.wrapperWidth - this.scrollerWidth;
			this.maxScrollY	= this.wrapperHeight - this.scrollerHeight;

			if(isHide){
				for(var n in props){
					this.wrapper.style[n] = oldStyle[n];
				}
			}

			if(this.scrollerWidth < this.wrapperWidth) this.options.scrollX = false;
			if(this.scrollerHeight < this.wrapperHeight) this.options.scrollY = false;

			// utils.addEvent(this.wrapper, eventName.start, this);

			var _this = this;
			$(this.wrapper).on(eventName.start, function(){
				_this.start(event);
			});
		},
		start: function(e){
			var touches = support ? e.touches[0] : e;
			this.data = {
				startX: touches.pageX,
				startY: touches.pageY,
				distX: 0, // 移动距离
				distY: 0,
				time: Date.now()
			}

			// utils.addEvent(this.wrapper, eventName.move, this);
			// utils.addEvent(this.wrapper, eventName.end, this);
			var _this = this;
			$(this.wrapper).on(eventName.move, function(e){
				_this.move(e.originalEvent);
			});
			$(this.wrapper).on(eventName.end, function(e){
				_this.end(e.originalEvent);
			});
		},
		move: function(e){
			var touches = support ? e.touches[0] : e,
				data = this.data;
			
			data.distX = touches.pageX - data.startX;
			data.distY = touches.pageY - data.startY;
			var x = this.options.scrollX ? this.x + data.distX : 0,
				y = this.options.scrollY ? this.y + data.distY : 0;
				
			this.translate(x, y);
			e.preventDefault();
		},
		end: function(e){
			var data = this.data,
				duration = Date.now() - data.time,
				newX,
				newY,
				scrollX = this.options.scrollX,
				scrollY = this.options.scrollY,
				cBoundaries,
				momentumX,
				momentumY;
			
			
			newX = scrollX ? this.x + data.distX : 0;
			newY = scrollY ? this.y + data.distY : 0;
			
			cBoundaries = this.checkBoundaries(newX, newY);
			newX = cBoundaries.x;
			newY = cBoundaries.y;
			
			if(cBoundaries.exceed){
				this.animate(newX, newY, 600);
			}else{
				// 判断是否需要继续滑动
				if(duration < 300){
					momentumX = scrollX ? utils.momentum(newX, newX - data.distX, duration, this.maxScrollX, this.wrapperWidth) : { destination: newX, duration: 0 };
					momentumY = scrollY ? utils.momentum(newY, newY - data.distY, duration, this.maxScrollY, this.wrapperHeight) : { destination: newY, duration: 0 };
					newX = momentumX.destination;
					newY = momentumY.destination;
					time = Math.max(momentumX.duration, momentumY.duration);
					this.animate(newX, newY, time, true, 'cubic-bezier(0.25, 0.46, 0.45, 0.94)');
				}else{
					this.x = newX;
					this.y = newY;
				}
			}

			//console.log('end: ' + (touches.pageX - this.data.startX));
			
			// e.preventDefault();

            // utils.removeEvent(this.wrapper, eventName.move, this);
            // utils.removeEvent(this.wrapper, eventName.end, this);
            $(this.wrapper).off(eventName.move);
            $(this.wrapper).off(eventName.end);
		},
		//判断是否超出边界
		checkBoundaries: function(x, y){
			var exceed;
			if ( x > 0 || x < this.maxScrollX ) {
				x = x > 0 ? 0 : this.maxScrollX;
				exceed = true;
			}
			if ( y > 0 || y < this.maxScrollY ) {
				y = y > 0 ? 0 : this.maxScrollY;
				exceed = true;
			}
			return {
				x: x,
				y: y,
				exceed: exceed
			}
		},
		translate: function(x, y, time){
			this.scrollerStyle[utils.prefixStyle('transitionDuration')] = (time || 0) + 'ms';
			this.scrollerStyle[utils.prefixStyle('transform')] = 'translate(' + x + 'px,' + y + 'px)';
		},
		animate: function(x, y, time, hasCall, easing){
			var _this = this,
				cBoundaries;
			
			_this.scrollerStyle[utils.prefixStyle('transitionTimingFunction')] = easing || 'cubic-bezier(0.1, 0.57, 0.1, 1)';
			_this.translate(x, y, time);
			_this.x = x;
			_this.y = y;
			
			//滑动后判断是否需要复位 
			if(hasCall){
				cBoundaries = _this.checkBoundaries(x, y);
				x = cBoundaries.x;
				y = cBoundaries.y;
				
				if(cBoundaries.exceed){
					setTimeout(function(){
						_this.animate(x, y, 600);
					}, time);
				}
			}
		},
		handleEvent: function(e){
			switch(e.type){
    			case 'touchstart':
    			case 'mousedown':
    				this.start(e);
    				break;
    			case 'touchmove':
    			case 'mousemove':
    				this.move(e);
    				break;
    			case 'touchend':
    			case 'mouseup':
    				this.end(e);
    				break;
    		}
		},
		destory: function(){
			this.scrollerStyle[utils.prefixStyle('transitionDuration')] = '';
			this.scrollerStyle[utils.prefixStyle('transition')] = '';
			this.scrollerStyle.webkitTransform = '';

			// utils.removeEvent(this.wrapper, eventName.start, this);

			$(this.wrapper).off('.sc');
		}
	};

	window.Scroller = Scroller;
	
})(window, document, undefined);