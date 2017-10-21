
'use strict';

var S = {
  init: function () {
    var action = window.location.href,
        i = action.indexOf('?a=');

    S.Drawing.init('.canvas');
    S.ShapeBuilder.init();
    S.UI.init();
    //document.body.classList.add('body--ready');

    /*if (i !== -1) {
      S.UI.simulate(decodeURI(action).substring(i + 3));
    } else {
      opening();
    }*/

    S.Drawing.loop(function () {
      S.Shape.render();
    });
  }
};


/*window.addEventListener('load', function () {
  S.init();
});*/


S.Drawing = (function () {
  var canvas,
      context,
      renderFn,
      requestFrame = window.requestAnimationFrame       ||
                     window.webkitRequestAnimationFrame ||
                     window.mozRequestAnimationFrame    ||
                     window.oRequestAnimationFrame      ||
                     window.msRequestAnimationFrame     ||
                     function (callback) {
                        window.setTimeout(callback, 1000 / 60);
                      };

  return {
    init: function (el) {
      canvas = document.querySelector(el);
      context = canvas.getContext('2d');
      this.adjustCanvas();

      window.addEventListener('resize', function () {
        S.Drawing.adjustCanvas();
      });
    },

    loop: function (fn) {
      renderFn = !renderFn ? fn : renderFn;
      this.clearFrame();
      renderFn();
      requestFrame.call(window, this.loop.bind(this));
    },

    adjustCanvas: function () {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    },

    clearFrame: function () {
      context.clearRect(0, 0, canvas.width, canvas.height);
    },

    getArea: function () {
      return { w: canvas.width, h: canvas.height };
    },

    drawCircle: function (p, c,x) {
      var image = new Image();
	    image.src = x;
	    context.drawImage(image,p.x,p.y,p.z*3,p.z*3);
    }
  };
}());


S.Point = function (args) {
  this.x = args.x;
  this.y = args.y;
  this.z = args.z;
  this.a = args.a;
  this.h = args.h;
};


S.Color = function (r, g, b, a) {
  this.r = r;
  this.g = g;
  this.b = b;
  this.a = a;
};

S.Color.prototype = {
  render: function () {
    return 'rgba(' + this.r + ',' + this.g + ',' + this.b + ',' + this.a + ')';
  }
};


S.UI = (function () {
  var input = document.querySelector('.ui-input'),
      ui = document.querySelector('.ui'),
      canvas = document.querySelector('.canvas'),
      interval,
	  timeout,
	  downinterval,
      currentAction = 0,
      resizeTimer,
      time,
      maxShapeSize = 30,
      firstAction = true,
      sequence = [],
	  d_time = 10000,
      cmd = '#',
      can = 0;

  function formatTime(date) {
    var h = date.getHours(),
        m = date.getMinutes();

    m = m < 10 ? '0' + m : m;
    return h + ':' + m;
  }

  function getValue(value) {
    return value && value.split(' ')[1];
  }

  function getAction(value) {
    value = value && value.split(' ')[0];
    return value && value[0] === cmd && value.substring(1);
  }
  
  function timedAction(fn, delay, max,circulation) {
    clearTimeout(timeout);	
    if (!max || (currentAction <= max)) {		
		if (max && currentAction == max && signwall_show_str.indexOf('#countdown') != -1) {	
			currentAction = 1;
		}else if (max && currentAction == max && signwall_show_str.indexOf('#countdown') == -1) {
			currentAction = 0;
		}
		fn(currentAction);
		timeout = setTimeout(function(){
			currentAction = currentAction + 1;
			timedAction(fn, d_time, sequence.length, circulation);			
		},d_time);
    }	
  }
  function downAction(fn, delay, maxDown) {		//倒计时  
    clearInterval(downinterval);
    fn(maxDown);
    if (maxDown > 0) {
      downinterval = setInterval(function () {
		clearTimeout(timeout);
        maxDown = maxDown - 1;
        fn(maxDown);
        if (maxDown === 0) {			
          clearInterval(downinterval);
        }
      }, delay);
    }
  }

  function reset(destroy) {
    clearTimeout(timeout);
    clearInterval(downinterval);
    sequence = [];
    time = null;

    if (destroy) {
      S.Shape.switchShape(S.ShapeBuilder.letter(''));
    }
  }

  function performAction(value,circulation) {
    var action;
    var current;
    sequence = typeof(value) === 'object' ? value : sequence.concat(value.split('|'));
    timedAction(function (index) {
      circulation = sequence.length==1 ? 1 : index + 1;
      current = sequence[circulation-1];
      action = getAction(current);
      value = getValue(current);
	  $('#container').hide();
	  $('.canvas').show();

      switch (action) {
      case 'countdown':
      	can = 1;
        value = parseInt(value, 10) || 10;
        value = value > 0 ? value : 10;
        downAction(function (index) {
          if (index == 0) {
            if (sequence.length == 0) {			  
              S.Shape.switchShape(S.ShapeBuilder.letter(''));
            } else {
			  currentAction += 1;
              performAction(sequence,circulation);
            }
          } else {
            S.Shape.switchShape(S.ShapeBuilder.letter(index), true);
          }
        }, 1200, value);
		d_time = 10000;
        break;

      case 'rectangle':
      	can = 1;
        value = value && value.split('x');
        value = (value && value.length === 2) ? value : [maxShapeSize, maxShapeSize / 2];

        S.Shape.switchShape(S.ShapeBuilder.rectangle(Math.min(maxShapeSize, parseInt(value[0], 10)), Math.min(maxShapeSize, parseInt(value[1], 10))));
		d_time = 10000
        break;

      case 'circle':
      	can = 1;
        value = parseInt(value, 10) || maxShapeSize;
        value = Math.max(value, maxShapeSize);
        S.Shape.switchShape(S.ShapeBuilder.circle(value));
		d_time = 10000
        break;

      case 'time':
      	can = 1;
        var t = formatTime(new Date());

        if (sequence.length > 0) {
          S.Shape.switchShape(S.ShapeBuilder.letter(t));
        } else {
          timedAction(function () {
            t = formatTime(new Date());
            if (t !== time) {
              time = t;
              S.Shape.switchShape(S.ShapeBuilder.letter(time));
            }
          }, 1000);
        }
		d_time = 10000
        break;

      case 'icon':
      	can = 1;
        S.ShapeBuilder.imageFile(value, function (obj) {
          S.Shape.switchShape(obj);
        });
		d_time = 10000
        break;

      case 'torus':	  	
	  	if (can == 1){
	  		S.Shape.switchShape(S.ShapeBuilder.letter(''));
			setTimeout(function(){
				$('.canvas').fadeOut(2000);	
			},3000);
			d_time = 34000;
			setTimeout(function(){
				S.Shape.clearCanvas();
				$('#container').show();
				style = 'torus';
				transform( targets.torus, 2000, 'torus', 32 );
			},3000);
		}else{ 
			d_time = 32000;
			$('#container').show();
			style = 'torus';
			transform( targets.torus, 2000, 'torus', 32 );
		}
		can = 0;
        break;
		
	  case 'sphere':
	  	if (can == 1){
		  	S.Shape.switchShape(S.ShapeBuilder.letter(''));	  	
  			setTimeout(function(){
  				$('.canvas').fadeOut(2000);	
  			},3000);
  			d_time = 44000;
  			setTimeout(function(){
  				S.Shape.clearCanvas();
  				$('#container').show();
  				style = 'sphere';
  				transform( targets.sphere, 2000, 'sphere', 40 );
  			},4000);
  		}else{
  			d_time = 40000; 
  			$('#container').show();
  			style = 'sphere';
  			transform( targets.sphere, 2000, 'sphere', 40 );
  		}
  		can = 0;
      break;
		
	  case 'helix':
	  	if (can == 1){
		  	S.Shape.switchShape(S.ShapeBuilder.letter(''));	  	
			setTimeout(function(){
				$('.canvas').fadeOut(2000);	
			},3000);
			d_time = 24000;
			setTimeout(function(){
				S.Shape.clearCanvas();
				$('#container').show();
				style = 'helix';
				transform( targets.helix, 2000, 'helix', 24 );
			},4000);
		}else{ 
			d_time = 20000;
			$('#container').show();
			style = 'helix';
			transform( targets.helix, 2000, 'helix', 20 );
		}
		can = 0;
        break;

      default:
      	can = 1;
        S.Shape.switchShape(S.ShapeBuilder.letter(current[0] === cmd ? 'What?' : current));
		d_time = 10000
      }
    }, d_time, sequence.length, circulation);
  }

 

  function bindEvents() {	//事件绑定

    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        S.Shape.shuffleIdle();
        //reset(true);
      }, 500);
    });

   
  }

  return {
    init: function () {
      bindEvents();
      input.focus();
    },

    simulate: function (action) {
      performAction(action);
    }
  };
}());

S.Dot = function (x, y) {
  this.p = new S.Point({
    x: x,
    y: y,
    z: 5,
    a: 1,
    h: 0
  });

  this.e = 0.07;
  this.s = true;

  this.c = new S.Color(255, 255, 255, this.p.a);

  this.t = this.clone();
  this.q = [];
};

S.Dot.prototype = {
  clone: function () {
    return new S.Point({
      x: this.x,
      y: this.y,
      z: this.z,
      a: this.a,
      h: this.h
    });
  },

  _draw: function (x) {
    this.c.a = this.p.a;
    S.Drawing.drawCircle(this.p, this.c,x);
  },

  _moveTowards: function (n) {
    var details = this.distanceTo(n, true),
        dx = details[0],
        dy = details[1],
        d = details[2],
        e = this.e * d;

    if (this.p.h === -1) {
      this.p.x = n.x;
      this.p.y = n.y;
      return true;
    }

    if (d > 1) {
      this.p.x -= ((dx / d) * e);
      this.p.y -= ((dy / d) * e);
    } else {
      if (this.p.h > 0) {
        this.p.h--;
      } else {
        return true;
      }
    }

    return false;
  },

  _update: function () {
    var p,
        d;

    if (this._moveTowards(this.t)) {
      p = this.q.shift();

      if (p) {
        this.t.x = p.x || this.p.x;
        this.t.y = p.y || this.p.y;
        this.t.z = p.z || this.p.z;
        this.t.a = p.a || this.p.a;
        this.p.h = p.h || 0;
      } else {
        if (this.s) {
          this.p.x -= Math.sin(1 * 3.142);
          this.p.y -= Math.sin(1 * 3.142);
        } else {
          this.move(new S.Point({
            x: this.p.x + (Math.random() * 50) - 25,
            y: this.p.y + (Math.random() * 50) - 25,
          }));
        }
      }
    }

    d = this.p.a - this.t.a;
    this.p.a = Math.max(0.5, this.p.a - (d * 0.05));
    d = this.p.z - this.t.z;
    this.p.z = Math.max(1, this.p.z - (d * 0.05));
  },

  distanceTo: function (n, details) {
    var dx = this.p.x - n.x,
        dy = this.p.y - n.y,
        d = Math.sqrt(dx * dx + dy * dy);

    return details ? [dx, dy, d] : d;
  },

  move: function (p, avoidStatic) {
    if (!avoidStatic || (avoidStatic && this.distanceTo(p) > 1)) {
      this.q.push(p);
    }
  },

  render: function (x) {
    this._update();
    this._draw(x);
  }
};


S.ShapeBuilder = (function () {
  var gap = 15,
      shapeCanvas = document.createElement('canvas'),
      shapeContext = shapeCanvas.getContext('2d'),
      fontSize = 650,
      fontFamily = 'Avenir, Helvetica Neue, Helvetica, Arial, microsoft yahei, sans-serif';

  function fit() {
    shapeCanvas.width = Math.floor(window.innerWidth / gap) * gap;
    shapeCanvas.height = Math.floor(window.innerHeight / gap) * gap;
    shapeContext.fillStyle = 'red';
    shapeContext.textBaseline = 'middle';
    shapeContext.textAlign = 'center';
  }

  function processCanvas() {
    var pixels = shapeContext.getImageData(0, 0, shapeCanvas.width, shapeCanvas.height).data,
        dots = [],
        x = 0,
        y = 0,
        fx = shapeCanvas.width,
        fy = shapeCanvas.height,
        w = 0,
        h = 0;

    for (var p = 0; p < pixels.length; p += (4 * gap)) {
      if (pixels[p + 3] > 0) {
        dots.push(new S.Point({
          x: x,
          y: y
        }));

        w = x > w ? x : w;
        h = y > h ? y : h;
        fx = x < fx ? x : fx;
        fy = y < fy ? y : fy;
      }

      x += gap;

      if (x >= shapeCanvas.width) {
        x = 0;
        y += gap;
        p += gap * 4 * shapeCanvas.width;
      }
    }
    return { dots: dots, w: w + fx, h: h + fy };
  }

  function setFontSize(s) {
    //shapeContext.font = 6*hFont*s + 'px ' + fontFamily;	//设置字体大小
	 shapeContext.font = "bold " + s + "px " + fontFamily;
  }

  function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
  }

  return {
    init: function () {
      fit();
      window.addEventListener('resize', fit);
    },

    imageFile: function (url, callback) {
      var image = new Image(),
          a = S.Drawing.getArea();

      image.onload = function () {
        shapeContext.clearRect(0, 0, shapeCanvas.width, shapeCanvas.height);
        shapeContext.drawImage(this, 0, 0, a.h*0.8 , a.h*0.8);
        callback(processCanvas());
      };

      image.onerror = function () {
        callback(S.ShapeBuilder.letter('What?'));
      };

      image.src = url;
    },

    circle: function (d) {
      var r = Math.max(0, d) / 2;
      shapeContext.clearRect(0, 0, shapeCanvas.width, shapeCanvas.height);
      shapeContext.beginPath();
      shapeContext.arc(r * gap, r * gap, r * gap, 0, 2 * Math.PI, false);
      shapeContext.fill();
      shapeContext.closePath();

      return processCanvas();
    },

    letter: function (l) {
      /*var s = 1;
      setFontSize(1);
      shapeContext.clearRect(0, 0, shapeCanvas.width, shapeCanvas.height);
      console.log(hFont);
      if(l.length>=4){ 
      	  setFontSize(0.5);
      }else if(l.length == 3) { 
      	  setFontSize(0.75);
      }
      
	  if(l.toString().indexOf('/') != -1){
		  shapeContext.fillText(l.substr(0,l.toString().indexOf('/')), shapeCanvas.width / 2, shapeCanvas.height * 0.15);
		  shapeContext.fillText(l.substr(l.toString().indexOf('/')+1), shapeCanvas.width / 2, shapeCanvas.height * 0.6);
	  }else{
		  shapeContext.fillText(l, shapeCanvas.width / 2, shapeCanvas.height / 2);  
	  }

      return processCanvas();*/
	  
            var s = 0;
            setFontSize(fontSize);
            s = Math.min(fontSize, shapeCanvas.width / shapeContext.measureText(l).width * 1 * fontSize, shapeCanvas.height / fontSize * (isNumber(l) ? 1 : .45) * fontSize);
            setFontSize(s);
            shapeContext.clearRect(0, 0, shapeCanvas.width, shapeCanvas.height);
            if(l.toString().indexOf('/') != -1){
			  shapeContext.fillText(l.substr(0,l.toString().indexOf('/')), shapeCanvas.width / 2, shapeCanvas.height * 0.15);
			  shapeContext.fillText(l.substr(l.toString().indexOf('/')+1), shapeCanvas.width / 2, shapeCanvas.height * 0.6);
			}else{
			  shapeContext.fillText(l, shapeCanvas.width / 2, shapeCanvas.height / 2);  
		  }
            return processCanvas();
       
    },

    rectangle: function (w, h) {
      var dots = [],
          width = gap * w,
          height = gap * h;

      for (var y = 0; y < height; y += gap) {
        for (var x = 0; x < width; x += gap) {
          dots.push(new S.Point({
            x: x,
            y: y,
          }));
        }
      }

      return { dots: dots, w: width, h: height };
    }
  };
}());


S.Shape = (function () {
  var dots = [],
      width = 0,
      height = 0,
      cx = 0,
      cy = 0;

  function compensate() {
    var a = S.Drawing.getArea();

    cx = a.w / 2 - width / 2;
    cy = a.h / 2 - height / 2;
  }; 

  return {
    shuffleIdle: function () {
      var a = S.Drawing.getArea();

      for (var d = 0; d < dots.length; d++) {
        if (!dots[d].s) {
          dots[d].move({
            x: Math.random() * a.w,
            y: Math.random() * a.h
          });
        }
      }
    },
	
	clearCanvas: function(){
		dots = [];	
	},

    switchShape: function (n, fast) {
      var size,
          a = S.Drawing.getArea(),
          d = 0,
          i = 0;

      width = n.w;
      height = n.h;

      compensate();     

      if (n.dots.length > dots.length) {
        size = n.dots.length - dots.length;
        for (d = 1; d <= size; d++) {
          dots.push(new S.Dot(a.w / 2, a.h / 2));
        }
      }

      d = 0;

      while (n.dots.length > 0) {
        i = Math.floor(Math.random() * n.dots.length);
        dots[d].e = fast ? 0.65 : (dots[d].s ? 0.54 : 0.51);

        if (dots[d].s) {
          dots[d].move(new S.Point({
            z: Math.random() * 2,
            a: Math.random(),
            h: 8
          }));
        } else {
          dots[d].move(new S.Point({
            z: Math.random() * 2,
            h: fast ? 3 : 5
          }));
        }

        dots[d].s = true;
        dots[d].move(new S.Point({
          x: n.dots[i].x + cx,
          y: n.dots[i].y + cy,
          a: 1,
          z: 3,
          h: 0
        }));

        n.dots = n.dots.slice(0, i).concat(n.dots.slice(i + 1));
        d++;
      }

      for (i = d; i < dots.length; i++) {
        if (dots[i].s) {
          dots[i].move(new S.Point({
            z: Math.random() * 2,
            a: Math.random(),
            h: 2
          }));

          dots[i].s = false;
          dots[i].e = 0.04;
          dots[i].move(new S.Point({ 
            x: Math.random() * a.w,
            y: Math.random() * a.h,
            a: 0.3, //.4
            z: Math.random() * 4,
            h: 0
          }));
        }
      }
    },
    render: function () {
      	for (var d = 0; d < dots.length; d++) {
      		if (return_array.length > 0)
      	    {
      			var j = d%return_array.length;
    			  dots[d].render(return_array[j].avatar);
      	    }
      		else
      	    {
      			if (placeholder_image_arr.length > 0)
      		    {
      				var k = d%placeholder_image_arr.length;
        			dots[d].render(placeholder_image_arr[k]);
      		    }
      			else
      		    {
      				dots[d].render(default_placeholder_image);
      		    }
      	    }
			
			
			
		}
    }
  };
}());