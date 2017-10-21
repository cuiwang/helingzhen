(function(window){
	window.touchEvent = function(el, option){
		return new Touchevent(el, option);
	}
	
	var utils = {
		    addEvent: function(el, type, fn, capture){
				el.addEventListener(type, fn, !!capture);
			},
			removeEvent: function(el, type, fn, capture){
				el.removeEventListener(type, fn, !!capture);
			},
			noop: function(){}
		},
		support = (window.Modernizr && Modernizr.touch === true) || (function () {
	        return !!(('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch);
	    })(),
	    eventName = {
	    	start: support ? 'touchstart' : 'mousedown',
	    	move: support ? 'touchmove' : 'mousemove',
	    	end: support ? 'touchend' : 'mouseup'
	    };


	function Touchevent(el, option){
		this.el = typeof el === 'string' ? document.querySelector(el) : el;

		this.startCb = option.startCb || utils.noop;
		this.moveCb = option.moveCb || utils.noop;
		this.endCb = option.endCb || utils.noop;

		if(!this.el){
			throw Error('element is not defined..');
			return;
		}
		this.init();
	}

	Touchevent.prototype = {
		init: function(){
			utils.addEvent(this.el, eventName.start, this);
		},
		start: function(e){
			var touches = support ? e.touches[0] : e;
		
			this.data = {
				startX: touches.pageX,
				startY: touches.pageY,
				distX: 0, // 移动距离
				distY: 0
			}

			utils.addEvent(this.el, eventName.move, this);
			utils.addEvent(this.el, eventName.end, this);

			this.startCb.call(this, e, this.data.startX, this.data.startY);
		},
		move: function(e){
			var touches = support ? e.touches[0] : e,
				data = this.data;
			
			data.distX = touches.pageX - data.startX;
			data.distY = touches.pageY - data.startY;

			this.moveCb.call(this, e, data.distX, data.distY);
		},
		end: function(e){
			var data = this.data;

			this.endCb.call(this, data.distX, data.distY);
			utils.removeEvent(this.el, eventName.move, this);
			utils.removeEvent(this.el, eventName.end, this);
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
		}
	}
})(window);