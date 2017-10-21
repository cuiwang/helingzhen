
'use strict';

var QD = {
  init: function () {
    var an = window.location.href,
        i = an.indexOf('?a=');

    QD.Dc.init('.canvas');
    QD.SBCS.init();
    QD.UI.init();
    QD.Dc.loop(function () {
      QD.Sp.render();
    });
  }
};

QD.Dc = (function () {
  var hb,
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
      hb = document.querySelector(el);
      context = hb.getContext('2d');
      this.cs();

      window.addEventListener('resize', function () {
        QD.Dc.cs();
      });
    },

    loop: function (fn) {
      renderFn = !renderFn ? fn : renderFn;
      this.clf();
      renderFn();
      requestFrame.call(window, this.loop.bind(this));
    },

    cs: function () {
      hb.width = window.innerWidth;
      hb.height = window.innerHeight;
    },

    clf: function () {
      context.clearRect(0, 0, hb.width, hb.height);
    },

    gaa: function () {
      return { w: hb.width, h: hb.height };
    },

    dle: function (p, c,x) {

      var image = new Image();
	    image.src = x;
	    context.drawImage(image,p.x,p.y,p.z*3,p.z*3);
    }
  };
}());


QD.P = function (args) {
  this.x = args.x;
  this.y = args.y;
  this.z = args.z;
  this.a = args.a;
  this.h = args.h;
};


QD.C = function (r, g, b, a) {
  this.r = r;
  this.g = g;
  this.b = b;
  this.a = a;
};

QD.C.prototype = {
  render: function () {
    return 'rgba(' + this.r + ',' + this.g + ',' + this.b + ',' + this.a + ')';
  }
};


QD.UI = (function () {
  var input = document.querySelector('.ui-input'),
      ui = document.querySelector('.ui'),
      hb = document.querySelector('.canvas'),
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


  function gVe(value) {
    return value && value.split(' ')[1];
  }

  function gAe(value) {
    value = value && value.split(' ')[0];
    return value && value[0] === cmd && value.substring(1);
  }
  
  function tAe(fn, delay, max,circulation) {
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
  			tAe(fn, d_time, sequence.length, circulation);			
  		},d_time);
    }	
  }


  function downAction(fn, delay, maxDown) {   //倒计时  
    clearInterval(downinterval);
    fn(maxDown);
    if (maxDown > 0) {
      downinterval = setInterval(function () {
    clearTimeout(timeout);
        maxDown = maxDown - 1;
        fn(maxDown);
        if (maxDown === 0) {  
        document.getElementById("daojishimusic").pause(); 
        document.getElementById("media").play();   
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
      QD.Sp.ssp(QD.SBCS.letter(''));
    }
  }

  function performAction(value,circulation) {
    var an,
        current;
    sequence = typeof(value) === 'object' ? value : sequence.concat(value.split('|'));

    // if(!$(".canvas").is(":hidden")){

    //     $('.canvas').hide();
    // }
    tAe(function (index) {
       // console.log($(".canvas").is(":hidden"))
      circulation = sequence.length==1 ? 1 : index + 1;
      current = sequence[circulation-1];
      an = gAe(current);
      value = gVe(current);
      $('#container').hide();
      $("div").remove(".element");
  	  $('.canvas').show();

      switch (an) {

        case 'countdown':
          can = 1;
          value = parseInt(value, 10) || 10;
          value = value > 0 ? value : 10;
          document.getElementById("daojishimusic").play();
          downAction(function (index) {
            if (index == 0) {
              if (sequence.length == 0) {       
                QD.Sp.ssp(QD.SBCS.letter(''));
              } else {
                currentAction += 1;
                performAction(sequence,circulation);
              }
            } else {
              QD.Sp.ssp(QD.SBCS.letter(index), true);
            }
          }, 1000, value);
          d_time = 10000;
          break;

        case 'icon':
        	can = 1;
          // QD.Sp.cls();
          // console.log(index+'|')
          QD.SBCS.imageFile(value, function (obj) {
            QD.Sp.ssp(obj);
            // $('.canvas').fadeOut(2000);
          });
    	    d_time = 6000;

          break;

        case 'torus':	  	
    	  	if (can == 1){
    	  		QD.Sp.ssp(QD.SBCS.letter(''));
      			setTimeout(function(){
      				$('.canvas').fadeOut(2000);	
      			},3000);
      			d_time = 20000;
      			setTimeout(function(){
      				QD.Sp.cls();
      				$('#container').show();
      				style = 'torus';
      				transform( targets.torus, 2000, 'torus', 20 );
      			},3000);
      		}else{ 
      			d_time = 20000;
      			$('#container').show();
      			style = 'torus';
      			transform( targets.torus, 2000, 'torus', 20 );
      		}
      		can = 0;
          break;

    		
    	  case 'sphere':
    	  	if (can == 1){
    		  	QD.Sp.ssp(QD.SBCS.letter(''));	  	
      			setTimeout(function(){
      				$('.canvas').fadeOut(2000);	
      			},3000);
      			d_time = 22000;
      			setTimeout(function(){
      				QD.Sp.cls();
      				$('#container').show();
      				style = 'sphere';
      				transform( targets.sphere, 2000, 'sphere', 27 );
      			},4000);
      		}else{
      			d_time = 22000; 
      			$('#container').show();
      			style = 'sphere';
      			transform( targets.sphere, 2000, 'sphere', 27 );
      		}
      		can = 0;
          break;

        case 'grid':
          if (can == 1){
            QD.Sp.ssp(QD.SBCS.letter(''));     
            setTimeout(function(){
              $('.canvas').fadeOut(2000); 
            },3000);
            d_time = 20000;
            setTimeout(function(){
              QD.Sp.cls();
              $('#container').show();
              style = 'grid';
              transform( targets.grid, 2000, 'grid', 27 );
            },4000);
          }else{
            d_time = 20000; 
            $('#container').show();
            style = 'grid';
            transform( targets.grid, 2000, 'grid', 27 );
          }
          can = 0;
          break;
    		
    	  case 'helix':
    	  	if (can == 1){
            
    		  	QD.Sp.ssp(QD.SBCS.letter(''));	  	
      			setTimeout(function(){
      				$('.canvas').fadeOut(2000);	
      			},3000);
      			d_time = 18500;
      			setTimeout(function(){
      				QD.Sp.cls();
      				$('#container').show();
      				style = 'helix';
      				transform( targets.helix, 2000, 'helix', 24 );
      			},4000);
      		}else{ 

      			d_time = 18500;
      			$('#container').show();
      			style = 'helix';
      			transform( targets.helix, 2000, 'helix', 20 );
      		}
    		  can = 0;
          break;

        case 'gameOver':
        QD.Sp.ssp(QD.SBCS.letter(''));  
          $('.canvas').fadeOut(2000);
          setTimeout(function(){
          QD.Sp.cls();
        },2500);
          can = 0;
          d_time = 3500;
          break;

        default:
        	can = 1;
          QD.Sp.ssp(QD.SBCS.letter(current[0] === cmd ? 'welcome' : current));
    	    d_time = 6000
      }
    }, d_time, sequence.length, circulation);
  }


  function bindEvents() {	//事件绑定

    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () {
        QD.Sp.sle();
      }, 500);
    });

  }

  return {
    init: function () {
      bindEvents();
    },

    simulate: function (an) {
      performAction(an);
    }
  };
}());

QD.Dot = function (x, y) {
  this.p = new QD.P({
    x: x,
    y: y,
    z: 5,
    a: 1,
    h: 0
  });

  this.e = 0.07;
  this.s = true;

  this.c = new QD.C(255, 255, 255, this.p.a);

  this.t = this.clone();
  this.q = [];
};



QD.Dot.prototype = {
  clone: function () {
    return new QD.P({
      x: this.x,
      y: this.y,
      z: this.z,
      a: this.a,
      h: this.h
    });
  },

  _draw: function (x) {
    this.c.a = this.p.a;
    QD.Dc.dle(this.p, this.c,x);
  },

  _moveTowards: function (n) {
    // console.log(222222);
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
          this.move(new QD.P({
            // x: this.p.x + (Math.random() * 50) - 25,
            x: this.p.x,
            // y: this.p.y + (Math.random() * 50) - 25,
            y: this.p.y,
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
    // console.log(p)
    if (!avoidStatic || (avoidStatic && this.distanceTo(p) > 1)) {
      this.q.push(p);
    }
  },

  render: function (x) {
    this._update();
    this._draw(x);
  }
};


QD.SBCS = (function () {
  var sCss = document.createElement('canvas'),
      sCtt = sCss.getContext('2d'),
      fontSize = 500,
      fontFamily = 'Avenir, Helvetica Neue, Helvetica, Arial, microsoft yahei, sans-serif';

  function fit() {
    sCss.width = Math.floor(window.innerWidth / gap) * gap;
    sCss.height = Math.floor(window.innerHeight / gap) * gap;
    sCtt.fillStyle = 'red';
    sCtt.textBaseline = 'middle';
    sCtt.textAlign = 'center';
  }

  function pcs() {
    var pixels = sCtt.getImageData(0, 0, sCss.width, sCss.height).data,
        dots = [],
        x = 0,
        y = 0,
        fx = sCss.width,
        fy = sCss.height,
        w = 0,
        h = 0;

    for (var p = 0; p < pixels.length; p += (4 * gap)) {
      if (pixels[p + 3] > 0) {
        dots.push(new QD.P({
          x: x,
          y: y
        }));

        w = x > w ? x : w;
        h = y > h ? y : h;
        fx = x < fx ? x : fx;
        fy = y < fy ? y : fy;
      }

      x += gap;

      if (x >= sCss.width) {
        x = 0;
        y += gap;
        p += gap * 4 * sCss.width;
      }
    }
    return { dots: dots, w: w + fx, h: h + fy };
  }

  function sfzz(s) {
    sCtt.font = 6*hFont*s + 'px ' + fontFamily;	
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
          a = QD.Dc.gaa();

      image.onload = function () {
        sCtt.clearRect(0, 0, sCss.width, sCss.height);
        sCtt.drawImage(this, 0, 0, a.h*1 , a.h*1);
        callback(pcs());
      };

      image.onerror = function () {
        callback(QD.SBCS.letter('welcome'));
      };

      image.src = url;
    },

    letter: function (l) {
      var s = 1;

      sfzz(1);

      sCtt.clearRect(0, 0, sCss.width, sCss.height);
      if(l.length>=4){ 
      	  sfzz(0.5);
      }else if(l.length == 3) { 
      	  sfzz(0.75);
      }
     
	  if(l.toString().indexOf('/') != -1){

		  sCtt.fillText(l.substr(0,l.toString().indexOf('/')), sCss.width / 2, sCss.height * 0.15);
		  sCtt.fillText(l.substr(l.toString().indexOf('/')+1), sCss.width / 2, sCss.height * 0.6);
	  }else{

		  sCtt.fillText(l, sCss.width / 2, sCss.height / 2);  
	  }

      return pcs();
    },
   
  };
}());


QD.Sp = (function () {
  var dots = [],
      width = 0,
      height = 0,
      cx = 0,
      cy = 0;

  function compensate() {
    var a = QD.Dc.gaa();

    cx = a.w / 2 - width / 2;
    cy = a.h / 2 - height / 2;
  }; 

  return {
    sle: function () {
      var a = QD.Dc.gaa();

      for (var d = 0; d < dots.length; d++) {
        if (!dots[d].s) {
          dots[d].move({
            x: Math.random() * a.w,
            y: Math.random() * a.h
          });
        }
      }
    },
	
	cls: function(){
		dots = [];	
	},

    ssp: function (n, fast) {
      var size,
          a = QD.Dc.gaa(),
          d = 0,
          i = 0;

      width = n.w;
      height = n.h;

      compensate();     

      if (n.dots.length > dots.length) {
        size = n.dots.length - dots.length;
        for (d = 1; d <= size; d++) {
          dots.push(new QD.Dot(a.w / 2, a.h / 2));
        }
       
      }

      d = 0;

      while (n.dots.length > 0) {

        i = Math.floor(Math.random() * n.dots.length);
        dots[d].e = fast ? 0.65 : (dots[d].s ? 0.54 : 0.51);
        // console.log(dots[d].s)

        if (dots[d].s) {
         
          dots[d].move(new QD.P({
            // z: Math.random() * 20 + 10, 控制不断变小的效果    
            z: 5,
            // a: Math.random(), 控制不断变小的效果
            a: 1,
            h: 5
          }));
        } else {

          dots[d].move(new QD.P({
            // z: Math.random() * 5 + 5,控制不断变小的效果
            z: 5, 
            h: fast ? 4 : 10
          }));
        }

        dots[d].s = true;
        dots[d].move(new QD.P({
          x: n.dots[i].x + cx,
          y: n.dots[i].y + cy,
          a: 1,
          z: 5,
          h: 0
        }));

        n.dots = n.dots.slice(0, i).concat(n.dots.slice(i + 1));
        d++;
      }


      for (i = d; i < dots.length; i++) {
        if (dots[i].s) {
          dots[i].move(new QD.P({

            // z: Math.random() * 20 + 10,  
            z: 5,
            // a: Math.random(),
            a: 1,
            h: 2
          }));

          dots[i].s = false;
          dots[i].e = 0.04;
          dots[i].move(new QD.P({ 
            x: Math.random() * a.w,
            y: -a.h,
            // y: Math.random() * a.h,
            a: 0.4, //.4
            z: 5,
            h: 0
          }));
          
        }
      }
    },
    render: function () {
      	for (var d = 0; d < dots.length; d++) {
      		if (return_array.length > 0){
            // console.log(1)
      			var j = d%return_array.length;
    			  dots[d].render(return_array[j].thumb_image_46);
      	  }else{
            // console.log(2)
      			// if (placeholder_image_arr.length > 0){
      				var k = d%placeholder_image_arr.length;
        			dots[d].render(placeholder_image_arr[k]);
      		  // }else{
    				  // dots[d].render(default_placeholder_image);
      		  // }
      	  }
		    }
    }
  };
}());