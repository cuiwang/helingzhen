(function(){
	
var MAX_LIFE = 40;
var PI_2 = Math.PI / 2;
var PI_180 = Math.PI / 180;

var Random = {
	between: function(min, max) {
		return min + (Math.random() * (max - min));
	}
}

function Vector(x, y) {
	this._x = x || 0;
	this._y = y || 0;
}

Vector.create = function(x, y) {
	return new Vector(x, y);
};

Vector.add = function(a, b) {
	return new Vector(a.x + b.x, a.y + b.y);
};

Vector.subtract = function(a, b) {
	return new Vector(a.x - b.x, a.y - b.y);
};

Vector.random = function(range) {
	var v = new Vector();
	v.randomize(range);
	return v;
};

Vector.distanceSquared = function(a, b) {
	var dx = a.x - b.x;
	var dy = a.y - b.y;
	return dx * dx + dy * dy;
};

Vector.distance = function(a, b) {
	var dx = a.x - b.x;
	var dy = a.y - b.y;
	return Math.sqrt(dx * dx + dy * dy);
};

Vector.prototype = {
	get x() {
		return this._x;
	},
	get y() {
		return this._y;
	},
	set x(value) {
		this._x = value;
	},
	set y(value) {
		this._y = value;
	},
	get magnitudeSquared() {
		return this._x * this._x + this._y * this._y;
	},
	get magnitude() {
		return Math.sqrt(this.magnitudeSquared);
	},
	get angle() {
		return Math.atan2(this._y, this._x) * 180 / Math.PI;
	},
	clone: function() {
		return new Vector(this._x, this._y);
	},
	add: function(v) {
		this._x += v.x;
		this._y += v.y;
	},
	subtract: function(v) {
		this._x -= v.x;
		this._y -= v.y;
	},
	multiply: function(value) {
		this._x *= value;
		this._y *= value;
	},
	divide: function(value) {
		this._x /= value;
		this._y /= value;
	},
	normalize: function() {
		var magnitude = this.magnitude;
		if (magnitude > 0) {
			this.divide(magnitude);
		}
	},
	limit: function(treshold) {
		if (this.magnitude > treshold) {
			this.normalize();
			this.multiply(treshold);
		}
	},
	randomize: function(amount) {
		amount = amount || 1;
		this._x = amount * 2 * (-.5 + Math.random());
		this._y = amount * 2 * (-.5 + Math.random());
	},
	rotate: function(degrees) {
		var magnitude = this.magnitude;
		var angle = ((Math.atan2(this._x, this._y) * PI_HALF) + degrees) * PI_180;
		this._x = magnitude * Math.cos(angle);
		this._y = magnitude * Math.sin(angle);
	},
	flip: function() {
		var temp = this._y;
		this._y = this._x;
		this._x = temp;
	},
	invert: function() {
		this._x = -this._x;
		this._y = -this._y;
	},
	toString: function() {
		return this._x + ', ' + this._y;
	}
}

function Particle(id, group, position, velocity, size, life, behavior) {

	this._id = id || 'default';
	this._group = group || 'default';

	this._position = position || new Vector();
	this._velocity = velocity || new Vector();
	this._size = size || 1;
	this._life = Math.round(life || 0);

	this._behavior = behavior || [];

}

Particle.prototype = {
	get id() {
		return this._id;
	},
	get group() {
		return this._group;
	},
	get life() {
		return this._life;
	},
	get size() {
		return this._size;
	},
	set size(size) {
		this._size = size;
	},
	get position() {
		return this._position;
	},
	get velocity() {
		return this._velocity;
	},
	update: function(stage) {

		this._life++;

		var i = 0;
		var l = this._behavior.length;

		for (; i < l; i++) {
			this._behavior[i].call(stage, this);
		}

	},
	toString: function() {
		return 'Particle(' + this._id + ') ' + this._life + ' pos: ' + this._position + ' vec: ' + this._velocity;
	}
}

window.bpFontAnimate = {
	burst:function(intensity,x,y){
		var behavior = [
			this.behavior.cohesion(),
			this.behavior.move()
		];
		var size = 1;
		var force = .5;
		var lifeMin = 0;
		var rangeMin = x-25;
		var rangeMax = x+25;
		this.spray(intensity, function() {
			return [
				null, null,
				Vector.create(
					Random.between(x - 35, x + 35),
					Random.between(y + 65, y - 45)
				),
				Vector.random(force),
				size + Math.random(),
				Random.between(lifeMin, 0), behavior
			]
		});
	
		this.spray(intensity * .5, function() {
			return [
				null, null,
				Vector.create(
					Random.between(rangeMin-35, rangeMax+35),
					y+20
				),
				Vector.random(force),
				size + Math.random(),
				Random.between(lifeMin, 0), behavior
			]
		});
	
		this.spray(intensity * .5, function() {
			return [
				null, null,
				Vector.create(
					Random.between(rangeMin, rangeMax),
					y
				),
				Vector.random(force),
				size + Math.random(),
				Random.between(lifeMin, 0), behavior
			]
		});
	},
	draw:function(num,x,y){
		num = num==null?14:num;
		this.burst(num,x,y);	
	},
	init:function(color){
		this.close();
		this.isStop = false;
		this.fontColor = color;
		this.particles = [];
		this.destroyed = [];
		this.update = function() {};
		this.stage = function() {};
		this.canvas = document.createElement('canvas');
		//this.canvas.id = 'fontCanvas';
		$(this.canvas).css({position:'absolute',left:0,top:0,'z-index':999});
		document.body.appendChild(this.canvas);
		window.addEventListener('resize', bpFontAnimate.fitCanvas);
		this.fitCanvas();
		this.context = this.canvas.getContext('2d');	
		this.tick();
	},
	fitCanvas:function(){
		bpFontAnimate.canvas.width = window.innerWidth;
		bpFontAnimate.canvas.height = window.innerHeight;			
	},
	ff:function(particle){
		var p = particle.position;
		var s = particle.size;
		var o = 1 - (particle.life / MAX_LIFE);
		this.paint.circle(p.x, p.y, s, 'rgba(255,255,255,' + o + ')');
		this.paint.circle(p.x, p.y, s + 2, this.fontColor + (o * .25) + ')');				
	},
	act:function(){
		var self = bpFontAnimate;
		var i = 0;
		var l = bpFontAnimate.particles.length;
		var p;
		for (; i < l; i++) {
			self.particles[i].update(this);
		}
		
		while (p = self.destroyed.pop()) {

			do {

				if (p !== self.particles[i]) {
					continue;
				}

				self.particles.splice(i, 1);

			} while (i-- >= 0)
		}
		
		i = 0;
		l = self.particles.length;
		
		for (; i < l; i++) {
			self.ff(self.particles[i])
		}			
	},
	tick:function(){
		var self = bpFontAnimate;
		self.clear();
		if(self.isStop||!self.particles)
			return;
		var len = self.particles.length;	
		for(var x=0;x<len;x++){
			if(self.particles[x]._life>=MAX_LIFE)
				self.destroy(self.particles[x]);	
		}
		self.act();
		window.requestAnimationFrame(arguments.callee);	
	},
	close:function(){
		this.isStop = true;
		this.particles = [];
		this.destroyed = [];
		var self = this;
		window.removeEventListener('resize', bpFontAnimate.fitCanvas);
		try{
			document.body.removeChild(self.canvas);	
		}catch(ex){}
	},
	clear:function(){
		this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
	},
	destroy:function(particle){
		this.destroyed.push(particle);
	},
	add:function(id, group, position, velocity, size, life, behavior) {
		this.particles.push(new Particle(id, group, position, velocity, size, life, behavior));
	},
	spray:function(amount, config) {
		var i = 0;
		for (; i < amount; i++) {
			this.add.apply(this,config());
		}
	},
	paint:{
		circle: function(x, y, size, color) {
			var context = bpFontAnimate.context;
			context.beginPath();
			context.arc(x, y, size, 0, 2 * Math.PI, false);
			context.fillStyle = color;
			context.fill();
		},
		square: function(x, y, size, color) {
			var context = bpFontAnimate.context;
			context.beginPath();
			context.rect(x - (size * .5), y - (size * .5), size, size);
			context.fillStyle = color;
			context.fill();
		}
	},
	behavior:{
		cohesion: function(range, speed) {
			range = Math.pow(range || 100, 2);
			speed = speed || .001;
			return function(particle) {
				var particles = bpFontAnimate.particles;
				var center = new Vector();
				var i = 0;
				var l = particles.length;
				var count = 0;

				if (l <= 1) {
					return;
				}

				for (; i < l; i++) {

					if (particles[i] === particle || Vector.distanceSquared(particles[i].position, particle.position) > range) {
						continue;
					}

					center.add(Vector.subtract(particles[i].position, particle.position));
					count++;
				}

				if (count > 0) {

					center.divide(count);

					center.normalize();
					center.multiply(particle.velocity.magnitude);

					center.multiply(.05);
				}

				particle.velocity.add(center);

			}
		},
		move: function() {
			return function(particle) {
				particle.position.add(particle.velocity);
			}
		}
	}

}
	
})();