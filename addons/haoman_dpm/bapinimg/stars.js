var initEffect = function(box){
	var box = box;
	var c = document.createElement('canvas');
    var w = c.width = window.innerWidth,
    h = c.height = window.innerHeight,

    spawnProb = 0.02,
    absoluteDelay = 50,
    minSize = 5,
    maxSize = 10,
    minRot = .01,
    maxRot = .1,
    minDelay = 3,
    maxDelay = 5,
    minSpeed = 1,
    maxSpeed = 5,

    radSlice = Math.PI * 2 / 5,
    frame = 0,
    vertices = [];
	box.appendChild(c);
    var ctx = c.getContext('2d');
	if(window.innerWidth>500&&window.innerHeight>350)
		minSpeed = 5,maxSpeed = 10;
	else
		maxSpeed = 5;
	function rand(min, max) {
		return Math.random() * (max - min) + min;
	}
	var getRandom = function(begin,end){
		return parseInt(Math.random()*((end>begin?end-begin:begin-end)+1)+(end>begin?begin:end));	
	}
	function getRandomColor(){ 
		return "#"+("00000"+((Math.random()*16777215+0.5)>>0).toString(16)).slice(-6);
	} 
	function genStar() {
		var size = rand(minSize, maxSize),
			rot = (rand(0, 1) > .5 ? -1 : 1) * rand(minRot, maxRot),
			delay = rand(minDelay, maxDelay),
			x = rand(0, w),
			y = rand(0, h),
			s = rand(minSpeed, maxSpeed),
			dir = rand(0, Math.PI * 2),
			vx = Math.cos(dir) * s,
			vy = Math.sin(dir) * s,
			pii = getRandom(50,100)+'%';
			//color = 'hsla(hue, 80%, 50%, alp)'.replace('hue', frame % 360);
			//color = 'hsla(hue, 80%, '+pi+', alp)'.replace('hue', getRandom(0,360));
			color = 'hsla(hue, 80%, diaosi, alp)'.replace('hue', frame % 360).replace('diaosi', pii);
		for (var i = 0; i < 5; ++i) {
			vertices.push(new Vertex(size, rot, dir + radSlice * i, delay * i,
				  x + Math.cos(radSlice * i) * size,
				  y + Math.sin(radSlice * i) * size,
								 vx, vy, x, y, color))
		}
	}
	function Vertex(size, rot, dir, delay, x, y, vx, vy, ox, oy, color) {
		this.size = size;
		this.rotSpeed = rot;
		this.rot = dir;
		this.delay = absoluteDelay + delay;
		this.life = 0;
		this.ox = ox;
		this.oy = oy;
		this.x;
		this.y;
		this.vx = vx;
		this.vy = vy;
		this.color = color;
	}
	Vertex.prototype.use = function () {
		++this.life;
		if (this.life >= this.delay) {
			this.x += Math.cos(this.rot) * 3
			this.y += Math.sin(this.rot) * 3;

		} else {
			if (this.life <= absoluteDelay) {
				this.rot += this.rotSpeed;
				this.ox += this.vx;
				this.oy += this.vy;
			}
			this.x = this.ox + Math.cos(this.rot) * this.size;
			this.y = this.oy + Math.sin(this.rot) * this.size;
		}

		var alpha = Math.min(this.life, absoluteDelay) / absoluteDelay;
		if (this.life > 100) alpha = (200 - this.life) / 100;

		ctx.lineWidth = this.size / 3;
		ctx.strokeStyle = this.color.replace('alp', alpha);
		ctx.translate(this.x, this.y);
		ctx.rotate(this.rot);
		ctx.beginPath();
		ctx.moveTo(-this.size / 3, -this.size / 2);
		ctx.lineTo(this.size, 0);
		ctx.lineTo(-this.size / 3, this.size / 2);
		ctx.stroke();
		ctx.rotate(-this.rot);
		ctx.translate(-this.x, -this.y);
	}

	function anim(){
		window.requestAnimationFrame(anim);

		++frame;
		
		ctx.clearRect(0,0,window.innerWidth,window.innerHeight);	

		if (Math.random() < spawnProb) genStar();

		for (var i = 0; i < vertices.length; ++i) {
			var vert = vertices[i];
			vert.use();

			if (vert.x < 0 || vert.x > w ||
			   vert.y < 0 || vert.y > h ||
				vert.life > 200) {
				vertices.splice(i, 1);
				--i;
			}
		}
	}

	anim();
       
	$(window).bind('resize',function(){
		if(window.innerWidth>500&&window.innerHeight>350)
			minSpeed = 5,maxSpeed = 10;
		else
			maxSpeed = 4;
	   	w = c.width = window.innerWidth;
    	h = c.height = window.innerHeight;
		ctx.clearRect(0,0,window.innerWidth,window.innerHeight);	
	})	
}