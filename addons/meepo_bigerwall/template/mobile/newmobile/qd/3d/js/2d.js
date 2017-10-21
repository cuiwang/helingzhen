var S = {
  init: function () {
    S.Drawing.init('.canvas');
    S.ShapeBuilder.init();
  }
};
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

   

    adjustCanvas: function () {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    },

    clearFrame: function () {
      context.clearRect(0, 0, canvas.width, canvas.height);
    },

    getArea: function () {
      return { w: canvas.width, h: canvas.height };
    }
  };
}());
var fontSize = 800;
S.ShapeBuilder = (function () {
	  
      shapeCanvas = document.createElement('canvas'),
      shapeContext = shapeCanvas.getContext('2d'),
	  
      fontFamily = 'Times New Roman';

  function fit() {
    shapeCanvas.width = Math.floor(window.innerWidth / gap) * gap;
    shapeCanvas.height = Math.floor(window.innerHeight / gap) * gap;
    shapeContext.fillStyle = 'red';
    shapeContext.textBaseline = 'middle';
    shapeContext.textAlign = 'center';
  }
  function errorCanvas(obj){
	return obj;
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
	console.log(gap);
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
	shapeContext.font = 'bold ' + s + 'px ' + fontFamily;
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
        shapeContext.drawImage(this, 0, 0, a.h * 0.5, a.h * 0.5);
        callback(processCanvas());
      };

      image.onerror = function () {
		var error = {'error':'-1'};
        callback(errorCanvas(error));
      };
	 //image.crossOrigin = '';
      image.src = url;
    },
	letter: function (l) {
       var s = 0;

      setFontSize(fontSize);
      s = Math.min(fontSize,
                  (shapeCanvas.width / shapeContext.measureText(l).width) * 0.8 * fontSize, 
                  (shapeCanvas.height / fontSize) * (isNumber(l) ? 1 : 0.45) * fontSize);
      setFontSize(s);
	  console.log(shapeCanvas.width+'|'+shapeCanvas.height);
      shapeContext.clearRect(0, 0, shapeCanvas.width, shapeCanvas.height);
	  if(l.toString().indexOf('/') != -1){
		  
		  shapeContext.fillText(l.substr(0,l.toString().indexOf('/')), shapeCanvas.width / 2, shapeCanvas.height * 0.3);
		  shapeContext.fillText(l.substr(l.toString().indexOf('/')+1), shapeCanvas.width / 2, shapeCanvas.height * 0.7);
	  }else{
		  shapeContext.fillText(l, shapeCanvas.width / 2, shapeCanvas.height / 2);  
	  }
      return processCanvas();

     
    },

  };
}());
S.Point = function (args) {
  this.x = args.x;
  this.y = args.y;
  this.z = args.z;
  this.a = args.a;
  this.h = args.h;
};