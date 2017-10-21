
var camera, scene, renderer;
var controls;

var objects = [];
var targets = { table: [], torus: [], sphere: [], helix: [], grid: [] };
var style = 'table',
	rotationY_add = 0,
	animate_start = 0;

function init() {
	/* 设置透视投影的相机,默认情况下相机的上方向为Y轴，右方向为X轴，沿着Z轴朝里（视野角：fov 纵横比：aspect 相机离视体积最近的距离：near 相机离视体积最远的距离：far */
	camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );				
	camera.position.z = 3000;

	/* 设置场景 */
	scene = new THREE.Scene();
	tableLine_odd = new THREE.Scene();
	tableLine_even = new THREE.Scene();

	// table
	for ( var i = 0; i < table.length; i ++ ) {

		var element = document.createElement( 'div' );
		element.className = 'element';
		
		var img = document.createElement( 'img' );
		img.src = table[ i ].src;
		/* 将名片显示需要的信息 添加到 dom树上 */
		img.setAttribute('bowtie', '');
		img.setAttribute('company', '');
		img.setAttribute('name', table[ i ].nick_name);
		img.setAttribute('text', '');
		img.setAttribute('imgId','');
		element.appendChild( img );

		var object = new THREE.CSS3DObject( element );
		/* 这里是设置 什么坐标？相机的？以及为什么这个设？ */
		object.position.x = Math.random() * 4000 - 2000;
		object.position.y = Math.random() * 4000 - 2000;
		object.position.z = Math.random() * 4000 - 2000;
		scene.add( object );
		objects.push( object );
		
		//
		var object = new THREE.Object3D();
		//object.position.x = (Math.random() * 2 * $(window).width())-$(window).width();
		//object.position.y = (Math.random() * 2 * $(window).height())-$(window).height();
		object.position.z = -30000;

		targets.table.push( object );					

	}

	// torus
	var vector = new THREE.Vector3();				

	for ( var i = 0, l = objects.length; i < l; i ++ ) {

		var object = new THREE.Object3D();
		
		object.position.x = 1200*Math.cos(-i);
		object.position.y = 1200*Math.sin(-i);
		object.position.z = 200-i*60*1.5;
		object.rotation.z = -i*0.03;

		//vector.copy( object.position ).multiplyScalar( 2 );

		object.lookAt( vector );

		targets.torus.push( object );

	}

	// sphere
	var vector = new THREE.Vector3();				

	for ( var i = 0, l = objects.length; i < l; i ++ ) {

		if (i == 0){
			var phi = Math.acos( -1 + ( 2 * i ) / l ) * 1.025;
			var theta = Math.sqrt( l * Math.PI ) * phi * 1.01;
		}else{
			var phi = Math.acos( -1 + ( 2 * i ) / l );
			var theta = Math.sqrt( l * Math.PI ) * phi;
		}

		var object = new THREE.Object3D();

		object.position.x = 750 * Math.cos( theta ) * Math.sin( phi );
		object.position.y = 750 * Math.sin( theta ) * Math.sin( phi );
		object.position.z = 750 * Math.cos( phi );

		vector.copy( object.position ).multiplyScalar( 2 );

		object.lookAt( vector );

		targets.sphere.push( object );

	}

	// helix
	var vector = new THREE.Vector3();

	for ( var i = 0, l = objects.length; i < l; i ++ ) {

		var phi = i * 0.175 + Math.PI;

		var object = new THREE.Object3D();

		object.position.x = 900 * Math.sin( phi );
		object.position.y = - ( i * 8 ) + 450;
		object.position.z = 900 * Math.cos( phi );

		vector.x = object.position.x * 2;
		vector.y = object.position.y;
		vector.z = object.position.z * 2;

		object.lookAt( vector );

		targets.helix.push( object );

	}

	renderer = new THREE.CSS3DRenderer();
	renderer.setSize( window.innerWidth, window.innerHeight );
	renderer.domElement.style.position = 'absolute';
	renderer.domElement.className = 'abc';
	document.getElementById( 'container' ).appendChild( renderer.domElement );
	
	window.addEventListener( 'resize', onWindowResize, false );

}

function transform( shape, duration, style, time ) {

	TWEEN.removeAll();
	scene_init();
	animate_start = 0;

	for ( var i = 0; i < objects.length; i ++ ) {

		var object = objects[ i ];
		var target = shape[ i ];

		new TWEEN.Tween( object.position )
			.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
			.easing( TWEEN.Easing.Exponential.InOut )						
			.start();

		if (style == 'table'){ 
			new TWEEN.Tween( object.position )
				.to( { x: target.position.x,z: -10000 }, Math.random() * 1000 + 2000 )
				.delay(Math.random() * 1000 + 7800)
				.start();
		}else if(style == 'torus'){
			new TWEEN.Tween( object.position )
				.to( {z: -300000}, Math.random() * 1000 + 3000 )
				.delay(Math.random() * 1000 + 27500)
				.start();
		}else if(style == 'sphere'){
			new TWEEN.Tween( object.position )
				.to( { y: -5000}, Math.random() * 1000 + 3000 )
				.delay(Math.random() * 1000 + 37500)
				.start();
		}else if(style == 'helix'){ 			
			new TWEEN.Tween( object.position )
				.to( { y: 3000,z: target.position.z }, Math.random() * 1000 + 2000 )
				.delay(Math.random() * 1000 + 18500)
				.start();
		}

		new TWEEN.Tween( object.rotation )
			.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
			.easing( TWEEN.Easing.Exponential.InOut )
			.start();
	}	

	new TWEEN.Tween( this )
		.to( {}, duration * time/2 )
		.onUpdate(function(){ 
			render(style);
		})
		.start();
}

function onWindowResize() {

	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();

	renderer.setSize( window.innerWidth, window.innerHeight );

	render();

}

function animate() {
	/* 这里的 requestAnimateFrame 是 自定义的 还是  使用的是  window.requestAnimateFrame */
	window.requestAnimationFrame( animate );

	TWEEN.update();
	
	//joint.update();

	//controls.update();

}
function scene_init() {
	scene.position.x = 0;
	scene.position.y = 0;
	scene.position.z = 0;
	scene.rotation.x = 0;
	scene.rotation.y = 0;
	scene.rotation.z = 0;
}

/* 这里是 做  动画函数的更新？ */
function update(){
  for ( var i = 0; i < objects.length; i ++ ) {
	var object = objects[ i ];
    object.position.z+=20;
    object.rotation.z+=i*1/1000;
    if(object.position.z>800)
    {
		object.position.z = 200-(objects.length-1)*60*1.5;
    }
    
  }
}						
function render(style) {
	if(style == 'table'){		//表格
						
	}
	if(style == 'torus'){		//蜘蛛网
		animate_start += 1;					
		scene.rotation.z += 0.008;
		if (animate_start > 300){
			update();						
		}
	}
	if(style == 'sphere'){		//球
		animate_start += 1;					
		// scene.rotation.y += 0.01;
		//console.log(animate_start);
		if (animate_start > 1600){
			if(scene.position.z >0){ 
				scene.rotation.y += 0.008;
				scene.position.z -= 7;							
			}						
		}else if (animate_start > 550){
			scene.rotation.y += 0.003;
			scene.position.z = scene.position.z;						
		}else if (animate_start > 350){
			scene.rotation.y += 0.008;
			scene.position.z += 7;
		} else {
			scene.rotation.y += 0.008;
		}
	}
	if(style == 'helix'){		//螺旋
		animate_start += 1;
		scene.rotation.y -= 0.01;
		if (animate_start > 600){
			if(scene.position.z >0) scene.position.z -= 7;						
		}else if (animate_start > 300){
			scene.position.z = scene.position.z;						
		}else if (animate_start > 150){
			scene.position.z += 7;
		}
	}
	/*if(style == 'garde'){		//网格					
		scene.rotation.y -= 0.02;
		scene.position.z -= 10;
	}*/

	renderer.render( scene, camera );

}