(function(){

var richCanvas1 = function(canvas){
	var ctx,
		width,
		height,
		size,
		lines,
		tick;
	
	function line() {
		this.path = [];
		this.speed = rand(10, 20);
		this.count = randInt(10, 30);
		this.x = width / 2, +1;
		this.y = height / 2 + 1;
		this.target = {
			x: width / 2,
			y: height / 2
		};
		this.dist = 0;
		this.angle = 0;
		this.hue = tick / 5;
		this.life = 1;
		this.updateAngle();
		this.updateDist();
	}
	
	line.prototype.step = function(i) {
		this.x += Math.cos(this.angle) * this.speed;
		this.y += Math.sin(this.angle) * this.speed;
	
		this.updateDist();
	
		if (this.dist < this.speed) {
			this.x = this.target.x;
			this.y = this.target.y;
			this.changeTarget();
		}
	
		this.path.push({
			x: this.x,
			y: this.y
		});
		if (this.path.length > this.count) {
			this.path.shift();
		}
	
		this.life -= 0.001;
	
		if (this.life <= 0) {
			this.path = null;
			lines.splice(i, 1);
		}
	};
	
	line.prototype.updateDist = function() {
		var dx = this.target.x - this.x,
			dy = this.target.y - this.y;
		this.dist = Math.sqrt(dx * dx + dy * dy);
	}
	
	line.prototype.updateAngle = function() {
		var dx = this.target.x - this.x,
			dy = this.target.y - this.y;
		this.angle = Math.atan2(dy, dx);
	}
	
	line.prototype.changeTarget = function() {
		var randStart = randInt(0, 3);
		switch (randStart) {
			case 0: // up
				this.target.y = this.y - size;
				break;
			case 1: // right
				this.target.x = this.x + size;
				break;
			case 2: // down
				this.target.y = this.y + size;
				break;
			case 3: // left
				this.target.x = this.x - size;
		}
		this.updateAngle();
	};
	
	line.prototype.draw = function(i) {
		ctx.beginPath();
		var rando = rand(0, 10);
		for (var j = 0, length = this.path.length; j < length; j++) {
			ctx[(j === 0) ? 'moveTo' : 'lineTo'](this.path[j].x + rand(-rando, rando), this.path[j].y + rand(-rando, rando));
		}
		ctx.strokeStyle = 'hsla(' + rand(this.hue-50, this.hue + 50) + ', 50%, 35%, ' + (this.life / 3) + ')';
		ctx.lineWidth = rand(0.1, 2);
		ctx.stroke();
	};
	
	function rand(min, max) {
		return Math.random() * (max - min) + min;
	}
	
	function randInt(min, max) {
		return Math.floor(min + Math.random() * (max - min + 1));
	};
	
	function init() {
		canvasRichBg.richCanvasOpen = true;
		ctx = canvas.getContext('2d');
		size = 30;
		lines = [];
		reset();
		loop();
	}
	
	function reset() {
		width = Math.ceil(canvas.width / 2) * 2;
		height = Math.ceil(canvas.height / 2) * 2;
		tick = 0;
	
		lines.length = 0;
		canvas.width = width;
		canvas.height = height;
	}
	
	function create() {
		if (tick % 10 === 0) {
			lines.push(new line());
		}
	}
	
	function step() {
		var i = lines.length;
		while (i--) {
			lines[i].step(i);
		}
	}
	
	function clear() {
		ctx.globalCompositeOperation = 'destination-out';
		ctx.fillStyle = 'hsla(0, 0%, 0%, 0.1';
		ctx.fillRect(0, 0, width, height);
		ctx.globalCompositeOperation = 'lighter';
	}
	
	function draw() {
		ctx.save();
		ctx.translate(width / 2, height / 2);
		ctx.rotate(tick * 0.001);
		var scale = 0.8 + Math.cos(tick * 0.02) * 0.2;
		//ctx.scale(scale, scale);
		ctx.translate(-width / 2, -height / 2);
		var i = lines.length;
		while (i--) {
			lines[i].draw(i);
		}
		ctx.restore();
	}
	
	function loop() {
		if(!canvasRichBg.richCanvasOpen)
			return;
		window.requestAnimationFrame(loop);
		create();
		step();
		clear();
		draw();
		tick++;
	}
	
	function onresize() {
		reset();
	}
	
	init();			
}

var richCanvas2 = function(canvas){
	var ctx = canvas.getContext('2d'),
		w = canvas.width,
		h = canvas.height,	
		hue = 217,	
		stars = [],
		count = 0,
		maxStars = 1300;
		
	var canvas2 = document.createElement('canvas'),

	ctx2 = canvas2.getContext('2d');
	canvas2.width = 100;
	canvas2.height = 100;
	var half = canvas2.width / 2,
	gradient2 = ctx2.createRadialGradient(half, half, 0, half, half, half);
	gradient2.addColorStop(0.025, '#CCC');
	gradient2.addColorStop(0.1, 'hsl(' + hue + ', 61%, 33%)');
	gradient2.addColorStop(0.25, 'hsl(' + hue + ', 64%, 6%)');
	gradient2.addColorStop(1, 'transparent');
	ctx2.fillStyle = gradient2;
	ctx2.beginPath();
	ctx2.arc(half, half, half, 0, Math.PI * 2);
	ctx2.fill();
	
	var random = function(min, max){
		if (arguments.length < 2) {
			max = min;
			min = 0;
		}
		
		if (min > max) {
			var hold = max;
			max = min;
			min = hold;
		}	
		return Math.floor(Math.random() * (max - min + 1)) + min;	
	}
	
	function maxOrbit(x, y) {
		var max = Math.max(x, y),
		diameter = Math.round(Math.sqrt(max * max + max * max));
		return diameter / 2;
		//星星移动范围，值越大范围越小，
	}
	
	var Star = function() {
		this.orbitRadius = random(maxOrbit(w, h));
		this.radius = random(60, this.orbitRadius) / 8; 
		//星星大小
		this.orbitX = w / 1.5;
		this.orbitY = h / 1.5;
		this.timePassed = random(0, maxStars);
		this.speed = random(this.orbitRadius) / 5000; 
		//星星移动速度
		this.alpha = random(2, 10) / 10;
		
		count++;
		stars[count] = this;
	}
	Star.prototype.draw = function() {
		var x = Math.sin(this.timePassed) * this.orbitRadius + this.orbitX,
		y = Math.cos(this.timePassed) * this.orbitRadius + this.orbitY,
		twinkle = random(10);
		
		if (twinkle === 1 && this.alpha > 0) {
		this.alpha -= 0.05;
		} else if (twinkle === 2 && this.alpha < 1) {
		this.alpha += 0.05;
		}
		
		ctx.globalAlpha = this.alpha;
		ctx.drawImage(canvas2, x - this.radius / 2, y - this.radius / 2, this.radius, this.radius);
		this.timePassed += this.speed;
	}
	
	for (var i = 0; i < maxStars; i++) {
	  new Star();
	}
	
	function animation() {
		if(!canvasRichBg.richCanvasOpen)
			return;
		ctx.globalCompositeOperation = 'source-over';
		ctx.globalAlpha = 0.5; //尾巴
		ctx.fillStyle = 'hsla(' + hue + ', 64%, 6%, 2)';
		ctx.fillRect(0, 0, w, h)
		
		ctx.globalCompositeOperation = 'lighter';
		for (var i = 1, l = stars.length; i < l; i++) {
		stars[i].draw();
		};
		
		window.requestAnimationFrame(animation);
	}
	canvasRichBg.richCanvasOpen = true;
	animation();			
}

var richCanvas3 = function(canvas){

	var c = canvas;
	var ctx = c.getContext("2d");
	var w = c.width;
	var h = c.height;
	var clearColor = 'rgba(0, 0, 0, .1)';
	var max = 30;
	var drops = [];
	
	function random(min, max) {
		return Math.random() * (max - min) + min;
	}
	
	function O() {}
	
	O.prototype = {
		init: function() {
			this.x = random(0, w);
			this.y = 1;
			this.color = 'hsl(180, 100%, 50%)';
			this.w = 2;
			this.h = 1;
			this.vy = random(9,10);
			this.vw = 6;
			this.vh = 1;
			this.size = 2;
			this.hit = random(h * .8, h * .9);
			this.a = 1;
			this.va = .96;
		},
		draw: function() {
			if (this.y > this.hit) {
				ctx.beginPath();
				ctx.moveTo(this.x, this.y - this.h / 2);
	
				ctx.bezierCurveTo(
					this.x + this.w / 2, this.y - this.h / 2,
					this.x + this.w / 2, this.y + this.h / 2,
					this.x, this.y + this.h / 2);
	
				ctx.bezierCurveTo(
					this.x - this.w / 2, this.y + this.h / 2,
					this.x - this.w / 2, this.y - this.h / 2,
					this.x, this.y - this.h / 2);
	
				ctx.strokeStyle = 'hsla(180, 100%, 50%, '+this.a+')';
				ctx.stroke();
				ctx.closePath();
				
			} else {
				ctx.fillStyle = this.color;
				ctx.fillRect(this.x, this.y, this.size, this.size * 5);
			}
			this.update();
		},
		update: function() {
			if(this.y < this.hit){
				this.y += this.vy;
			} else {
				if(this.a > .03){
					this.w += this.vw;
					this.h += this.vh;
					if(this.w > 100){
						this.a *= this.va;
						this.vw *= .98;
						this.vh *= .98;
					}
				} else {
					this.init();
				}
			}
			
		}
	}
	
	function resize(){
		w = c.width = c.parentNode.offsetWidth;
		h = c.height = c.parentNode.offsetHeight;
	}
	
	function setup(){
		canvasRichBg.richCanvasOpen = true;
		for(var i = 0; i < max; i++){
			(function(j){
				setTimeout(function(){
					var o = new O();
					o.init();
					drops.push(o);
				}, j * 200)
			}(i));
		}
	}
	
	
	function anim() {
		if(!canvasRichBg.richCanvasOpen)
			return;
		ctx.fillStyle = clearColor;
		ctx.fillRect(0,0,w,h);
		for(var i in drops){
			drops[i].draw();
		}
		requestAnimationFrame(anim);
	}

	
	setup();
	anim();

}

window.canvasRichBg = {
	animate:[richCanvas1,richCanvas3],
	richCanvasOpen:true,
	close:function(){
		this.richCanvasOpen = false;	
	}
}
	
})();