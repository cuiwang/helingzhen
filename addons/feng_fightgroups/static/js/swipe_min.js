/*
 * Swipe 1.0
 *
 * Brad Birdsall, Prime
 * Copyright 2011, Licensed GPL & MIT
 *
*/

window.Swipe = function(element, options) {
  var isTouch = 'ontouchstart' in window;
  if (!element) return null;

  var _this = this;

  // retreive options
  this.options = options || {};
  this.index = this.options.startSlide || 0;
  this.speed = this.options.speed || 300;
  this.callback = this.options.callback || function() {};
  this.delay = this.options.auto || 0;
  this.unresize = this.options.unresize; //anjey
  this.loop = this.options.loop||false;//anjey
  this.indicate = this.options.indicate||null;//anjey

  // reference dom elements
  this.container = element;
  this.element = this.container.children[0]; // the slide pane
  this.length = this.element.children.length;

  this.element.style.listStyle = 'none';
  if(this.loop){
    var this_eles = this.element.children;
    if(this_eles.length>=2){
      this.element.innerHTML = ['<!--before-->','<!--center-->','<!--after-->','<!--469078010@qq.com-->'].join(this.element.innerHTML.toString() );
      this.index = this.length*1+this.index;
    }
  }

  // trigger slider initialization
  this.setup();
  // begin auto slideshow
  this.begin();

  // add event listeners
  if (this.element.addEventListener) {
  	//by anjey
  	this.element.addEventListener(isTouch?'touchstart':'mousedown', this, false);
    
    this.element.addEventListener('webkitTransitionEnd', this, false);
    this.element.addEventListener('transitionend', this, false);
    if(!this.unresize){ // anjey
    	window.addEventListener(isTouch?'orientationchange':'resize', this, false);
    }
  }

};

Swipe.prototype = {

  setup: function() {
    var that = this;
    this.slides = this.element.children;
    if (this.length < 2) return null;
    this.width = this.container.getBoundingClientRect().width || this.width; //anjey
    if (!this.width) return null;
    this.container.style.visibility = 'hidden';

    this.element.style.width = (this.slides.length * this.width) + 'px';
    var index = this.slides.length;
    while (index--) {
      var el = this.slides[index];
      el.style.width = this.width + 'px';
      el.style.display = 'table-cell';
      el.style.verticalAlign = 'top';
    }
    if(this.indicate && !this.indicater_s){
      this.indicater = document.querySelector(this.indicate);
      if(this.indicater){
        var arr = new Array(this.length);
        arr.splice(this.index%this.length,1,'<span class="on">&nbsp;</span>');
        this.indicater.innerHTML = arr.join('<span>&nbsp;</span>');
        this.indicater_s = this.indicater.children;
      }else{
        this.indicate = false;
      }
    }
    setTimeout(function(){
      that.slide(that.index, 0);
      that.container.style.visibility = 'visible';
    }, 100);
  },

  slide: function(index, duration) {
    var style = this.element.style;

    if (duration == undefined) {
        duration = this.speed;
    }
    style.webkitTransition = "-webkit-transform "+duration+"ms";
    style.MozTransform = style.webkitTransform = 'translate3d(' + -(index * this.width) + 'px,0,0)';
    this.index = index;
  },

  prev: function(delay) {

    // cancel next scheduled automatic transition, if any
    this.delay = delay || 0;
    clearTimeout(this.interval);

    if(this.loop){
       this.index = this.length + this.index%this.length-1;
    }else{
       this.index = (this.length+this.index-1)%this.length;
    }
    this.slide(this.index, this.speed);

  },

  next: function(delay) {
    var that = this;
    // cancel next scheduled automatic transition, if any
    this.delay = delay || 0;
    clearTimeout(this.interval);

   
    if(this.loop){
       this.index = this.length + this.index%this.length+1;
    }else{
       this.index = (this.index+1)%this.length;
    }
    
    this.slide(this.index, this.speed);
  },

  begin: function() {

    var _this = this;

    this.interval = (this.delay)
      ? setTimeout(function() { 
        _this.next(_this.delay);
      }, this.delay)
      : 0;
  
  },
  
  stop: function() {
    this.delay = 0;
    clearTimeout(this.interval);
  },
  
  resume: function() {
    this.delay = this.options.auto || 0;
    this.begin();
  },

  handleEvent: function(e) {
  	var that = this;
  	if(!e.touches){
  		e.touches = new Array(e);
  		e.scale = false;
  	}
    switch (e.type) {
      // by anjey
      case 'mousedown': (function(){
      	that.element.addEventListener('mousemove', that, false);
   			that.element.addEventListener('mouseup', that, false);
   			that.element.addEventListener('mouseout', that, false);
      	that.onTouchStart(e);
      })(); break;
      case 'mousemove': this.onTouchMove(e); break;
      case 'mouseup': (function(){
	      that.element.removeEventListener('mousemove', that, false);
	   		that.element.removeEventListener('mouseup', that, false);
	   		that.element.removeEventListener('mouseout', that, false);
	      	that.onTouchEnd(e);
      })(); break;
     case 'mouseout': (function(){
      	that.element.removeEventListener('mousemove', that, false);
   			that.element.removeEventListener('mouseup', that, false);
   			that.element.removeEventListener('mouseout', that, false);
      	that.onTouchEnd(e);
      })(); break;
    	
      case 'touchstart':
        that.element.addEventListener('touchmove', this, false);
        that.element.addEventListener('touchend', this, false);
        this.onTouchStart(e); break;
      case 'touchmove': this.onTouchMove(e); break;
      case 'touchend':
        that.element.removeEventListener('touchmove', this, false);
        that.element.removeEventListener('touchend', this, false);
      this.onTouchEnd(e); break;
      case 'webkitTransitionEnd':
      case 'msTransitionEnd':
      case 'oTransitionEnd':
      case 'transitionend': this.transitionEnd(e); break;
	  case 'orientationchange':
      case 'resize': this.setup(); break;
    }
  },

  transitionEnd: function(e) {
    e.preventDefault();
    if (this.delay) this.begin();
    if(this.loop){
      var toIndex = this.length + this.index%this.length;
      (toIndex!=this.index)&&(this.index=toIndex)&&this.slide(this.index,0);
    }
    if(this.indicate){
      var that = this;
      [].forEach.call(this.indicater_s, function(ci,i){
        ci.classList.remove("on");
        (i==that.index%that.length)&&ci.classList.add("on");
      });
    }

    this.callback(e, this.index%this.length, this.slides[this.index]);

  },

  onTouchStart: function(e) {
    this.start = {

      // get touch coordinates for delta calculations in onTouchMove
      pageX: e.touches[0].pageX,
      pageY: e.touches[0].pageY,

      // set initial timestamp of touch sequence
      time: Number( new Date() )

    };

    // used for testing first onTouchMove event
    this.isScrolling = undefined;
    
    // reset deltaX
    this.deltaX = 0;

    // set transition time to 0 for 1-to-1 touch movement
    this.element.style.MozTransitionDuration = this.element.style.webkitTransitionDuration = 0;

  },

  onTouchMove: function(e) {

    // ensure swiping with one touch and not pinching
    if(e.touches.length > 1 || e.scale && e.scale !== 1) return;

    this.deltaX = e.touches[0].pageX - this.start.pageX;

    // determine if scrolling test has run - one time test
    if ( typeof this.isScrolling == 'undefined') {
      this.isScrolling = !!( this.isScrolling || Math.abs(this.deltaX) < Math.abs(e.touches[0].pageY - this.start.pageY) );
    }

    // if user is not trying to scroll vertically
    if (!this.isScrolling) {

      // prevent native scrolling 
      e.preventDefault();

      // cancel slideshow
      clearTimeout(this.interval);

      // increase resistance if first or last slide
      this.deltaX = 
        this.deltaX / 
          ( (!this.index && this.deltaX > 0               // if first slide and sliding left
            || this.index == this.length - 1              // or if last slide and sliding right
            && this.deltaX < 0                            // and if sliding at all
          ) ?                      
          ( Math.abs(this.deltaX) / this.width + 1 )      // determine resistance level
          : 1 );                                          // no resistance if false
      
      // translate immediately 1-to-1
      if(this.loop){
        this.index = this.length + this.index%this.length;
     }
      this.element.style.MozTransform = this.element.style.webkitTransform = 'translate3d(' + (this.deltaX - this.index * this.width) + 'px,0,0)';

    }

  },

  onTouchEnd: function(e) {

    // determine if slide attempt triggers next/prev slide
    var isValidSlide = 
          Number(new Date()) - this.start.time < 250      // if slide duration is less than 250ms
          && Math.abs(this.deltaX) > 20                   // and if slide amt is greater than 20px
          || Math.abs(this.deltaX) > this.width/2,        // or if slide amt is greater than half the width

    // determine if slide attempt is past start and end
        isPastBounds = 
          !this.index && this.deltaX > 0                          // if first slide and slide amt is greater than 0
          || this.index == this.length - 1 && this.deltaX < 0;    // or if last slide and slide amt is less than 0

    // if not scrolling vertically
    if (!this.isScrolling) {

      // call slide function with slide end value based on isValidSlide and isPastBounds tests
      this.slide( this.index + ( isValidSlide && !isPastBounds ? (this.deltaX < 0 ? 1 : -1) : 0 ), this.speed );

    }

  }

};
