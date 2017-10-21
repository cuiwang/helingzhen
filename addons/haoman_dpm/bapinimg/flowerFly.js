(function(){

Particle3D=function(material){
	THREE.Particle.call(this,material);
	var speed = getRandom(8,20)*-1;
	this.velocity=new THREE.Vector3(4,speed,0);
	this.velocity.rotateX(randomRange(-25,25));
	this.velocity.rotateY(randomRange(0,120));
	this.gravity=new THREE.Vector3(0,0,0);
	this.drag=1;
};
Particle3D.prototype=new THREE.Particle();
Particle3D.prototype.constructor=Particle3D;
Particle3D.prototype.updatePhysics=function(){
	this.velocity.multiplyScalar(this.drag);
	this.velocity.addSelf(this.gravity);
	this.position.addSelf(this.velocity);
}
var TO_RADIANS=Math.PI/180;
THREE.Vector3.prototype.rotateY=function(angle){
	cosRY=Math.cos(angle*TO_RADIANS);
	sinRY=Math.sin(angle*TO_RADIANS);
	var tempz=this.z;
	var tempx=this.x;
	//this.x=(tempx*cosRY)+(tempz*sinRY);
	this.z=(tempx*-sinRY)+(tempz*cosRY);
}
THREE.Vector3.prototype.rotateX=function(angle){
	cosRY=Math.cos(angle*TO_RADIANS);
	sinRY=Math.sin(angle*TO_RADIANS);
	var tempz=this.z;
	var tempy=this.y;
	this.y=(tempy*cosRY)+(tempz*sinRY);
	this.z=(tempy*-sinRY)+(tempz*cosRY);
}
THREE.Vector3.prototype.rotateZ=function(angle){
	cosRY=Math.cos(angle*TO_RADIANS);
	sinRY=Math.sin(angle*TO_RADIANS);
	var tempx=this.x;
	var tempy=this.y;
	this.y=(tempy*cosRY)+(tempx*sinRY);
	//this.x=(tempy*-sinRY)+(tempx*cosRY);
}
function randomRange(min,max){
	return((Math.random()*(max-min))+ min);
}
var getRandom = function(begin,end){
	return parseInt(Math.random()*((end>begin?end-begin:begin-end)+1)+(end>begin?begin:end));	
}

window.flowerFly = function(box,num,srcList,_src){
	window.canFly = true;
	var SCREEN_WIDTH = window.innerWidth;
	var SCREEN_HEIGHT = window.innerHeight;
	
	var container;	
	var particle = [];	
	var camera;
	var scene;
	var renderer;
	
	var windowHalfX = window.innerWidth / 2;
	var windowHalfY = window.innerHeight / 2;	
	
	var particles = []; 
	var flowerImage = [];
	var material = [];
	 //new THREE.ParticleBasicMaterial( { map: new THREE.Texture(particleImage) } );
	for(var x=0;x<srcList.length;x++){
		var img = new Image();
		img.src = _src+srcList[x];
		var mater = new THREE.ParticleBasicMaterial( { map: new THREE.Texture(img) } );
		flowerImage.push(img);
		material.push(mater);
	}
	
	container = box;
	camera = new THREE.PerspectiveCamera( 45, SCREEN_WIDTH / SCREEN_HEIGHT, 1);
	camera.position.z = 900;
	
	scene = new THREE.Scene();
	scene.add(camera);
	renderer = new THREE.CanvasRenderer();
	renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
	//var material = new THREE.ParticleBasicMaterial( { map: new THREE.Texture(particleImage) } );
	for (var i = 0; i < num; i++) {
		var newImg = getRandom(0,material.length-1);
		//var material = new THREE.ParticleBasicMaterial( { map: new THREE.Texture(newImg) } );
		particle = new Particle3D(material[newImg]);

		particle.position.x = Math.random() * 2000 - 1000;
		particle.position.y = Math.random() * 2000 - 1000;
		particle.position.z = Math.random() * 2000 - 1000;

		particle.scale.x = particle.scale.y =  1;
		scene.add( particle );
		particles.push(particle); 
	}
	container.appendChild( renderer.domElement );
	var animate = function(){
		for(var i = 0; i<particles.length; i++)
		{
			var particle = particles[i]; 
			particle.updatePhysics(); 

			with(particle.position)
			{
				if(y<-1000) y+=2000; 
				if(x>1000) x-=2000; 
				else if(x<-1000) x+=2000; 
				if(z>1000) z-=2000; 
				else if(z<-1000) z+=2000; 
			}				
		} 
		renderer.render( scene, camera );
	}	
	
	window.flyFrame = function(){	
		animate();
		if(!window.canFly){
			try{
				cancelFrame(flyFrame);	
			}catch(ex){}
			return;
		}
		nextFrame(flyFrame);
	}
	flyFrame();
}
window.canFly = false;
window.flowerFlyClose = function(){
	window.canFly = false;
	try{
		cancelFrame(flyFrame);	
	}catch(ex){}
}

})();

