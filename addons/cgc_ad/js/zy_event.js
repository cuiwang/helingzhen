	var $support = {
		transform3d: ('WebKitCSSMatrix' in window),
		touch: ('ontouchstart' in window)
	};

	var $E = {
		start: $support.touch ? 'touchstart' : 'mousedown',
		move: $support.touch ? 'touchmove' : 'mousemove',
		end: $support.touch ? 'touchend' : 'mouseup'
	};
	function getPage (event, page) {
		return $support.touch ? event.changedTouches[0][page] : event[page];
	}
	var zyEvent = function(selector,etype,endFun){
		var self = this;
		var max = 50;
		self.Max = {X:max,Y:max};
		self.endFun = endFun || {};
		self.type = etype;
		self.start = false;
		self.isevent = false;
		if(typeof selector == 'object')
			self.element = selector;
		else if(typeof selector == 'string')
			self.element = document.getElementById(selector);
		self.element.addEventListener($E.start, self, false);
		self.element.addEventListener($E.move, self, false);
		document.addEventListener($E.end, self, false);

		return self;
	}

	zyEvent.prototype = {
		handleEvent: function(event) {
			var self = this;

			switch (event.type) {
				case $E.start:
					self._touchStart(event);
					break;
				case $E.move:
					self._touchMove(event);
					break;
				case $E.end:
					self._touchEnd(event);
					break;
				case 'click':
					self._click(event);
					break;
			}
		},
        _touchStart: function(event) {
            var self = this;
            self.start = true;
			self.isevent = false;
            if (!$support.touch) {
                event.preventDefault();
            }
            self.startPageX = getPage(event, 'pageX');
            self.startPageY = getPage(event, 'pageY');
            self.startTime = event.timeStamp;
        },
        _touchMove: function(event) {
            var self = this;
			if(!self.start)
				return;
				
            var pageX = getPage(event, 'pageX'),
                pageY = getPage(event, 'pageY'),
                deltaX = pageX - self.startPageX,
                deltaY = pageY - self.startPageY;
			switch(self.type){
				case 'left':
					if(Math.abs(deltaX)> Math.abs(deltaY) && deltaX<-self.Max.X)
						self.isevent = true;
					break;
				case 'right':
					if(Math.abs(deltaX)> Math.abs(deltaY) && deltaX>self.Max.X)
						self.isevent = true;
					break;
				case 'up':
					if(Math.abs(deltaX)< Math.abs(deltaY) && deltaY<-self.Max.Y)
						self.isevent = true;
					break;
				case 'down':
					if(Math.abs(deltaX)< Math.abs(deltaY) && deltaY>self.Max.Y)
						self.isevent = true;
					break;
				case 'drag':
					break;
				
			}

        },
        _touchEnd: function(event) {
            var self = this;
			if(self.isevent){
            	setTimeout(self.endFun, 1);
            	self.start = false;
            	self.isevent = false;
			}
        },
        _click: function(event) {
            var self = this;

            event.stopPropagation();
            event.preventDefault();
        },
        destroy: function() {
            var self = this;

            self.element.removeEventListener(touchStartEvent, self);
            self.element.removeEventListener(touchMoveEvent, self);
            document.removeEventListener(touchEndEvent, self);
        }
	}


