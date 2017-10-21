var camera, scene, renderer;
var controls;
var objects = [];
var targets = { table: [], sphere: [], helix: [], grid: [] ,logo:[]};
var style=0,
	rotationY_add = 0,
	animate_start = 0;
var type;
var avatar_num = 200;
function init(type,obj) {
	type = type;

	png_point  = obj;
	camera = new THREE.PerspectiveCamera( 50, window.innerWidth / window.innerHeight, 1, 10000 );				
	camera.position.z = 3000;

	scene = new THREE.Scene();
	tableLine_odd = new THREE.Scene();
	tableLine_even = new THREE.Scene();
	var tmp_point = png_point.dots;
	//console.log(png_point);
	var tmp_length = tmp_point.length;
	if(tmp_length<sign_persons){
		alert('logo或者文字总圆点数少于签到人数、请重试设置！');
		return;
	}
	for ( var i = 0; i < tmp_length; i ++ ) {
		var element = document.createElement( 'div' );
		element.className = 'element';
		var img = document.createElement( 'img' );
		if(typeof(table[i]) == "undefined" ){
				img.src = table[Math.floor(Math.random() * table.length)].src;
		}else{
				img.src = table[i].src;
		}
		element.appendChild( img );
		var object = new THREE.CSS3DObject( element );
		object.position.x = Math.random() * 4000 - 2000;
		object.position.y = Math.random() * 4000 - 2000;
		object.position.z = Math.random() * 4000 - 2000;
		scene.add( object );
		objects.push( object );
		
		
	
		var object = new THREE.Object3D();
		if(type=='logo'){
			object.position.x =  (tmp_point[i].x * 5) - ((window.innerWidth)/2) - (png_point.w/2);//logo
			object.position.y =  -(tmp_point[i].y * 5 ) +  ((window.innerHeight)/2) + png_point.h; //logo
		}else{
			object.position.x =  (tmp_point[i].x * 5) - (window.innerWidth*2.5);//font
			object.position.y =  -(tmp_point[i].y * 5 ) +  window.innerHeight*2;//font
		}
		targets.table.push( object );
	}

	// sphere

	var vector = new THREE.Vector3();
	avatar_num = personArray.length < sign_persons ? sign_persons : personArray.length;
	for ( var i = 0, l =avatar_num; i < l; i ++ ) {			
		var phi = Math.acos( -1 + ( 2 * i ) / l ); 
		var theta = Math.sqrt( l * Math.PI ) * phi;		
		var object = new THREE.Object3D();
		if(sign_persons <= 500){
			var sphere_pi = 1000;
		}else if(sign_persons > 500 && sign_persons <= 800){
			var sphere_pi = 1200;
		}else{
			var sphere_pi = 1500;
		}
		object.position.x = sphere_pi * Math.cos( theta ) * Math.sin( phi );
		object.position.y = sphere_pi * Math.sin( theta ) * Math.sin( phi );
		object.position.z = sphere_pi * Math.cos( phi );
		vector.copy( object.position ).multiplyScalar( 2 );
		object.lookAt( vector );
		targets.sphere.push( object );

	}

	// helix

	var vector = new THREE.Vector3();

	for ( var i = 0, l = avatar_num; i < l; i ++ ) {

		var phi = i * 0.175 + Math.PI;

		var object = new THREE.Object3D();
		if(sign_persons <= 500){
			var helix_pi = 900;
		}else if(sign_persons > 500 && sign_persons <= 800){
			var helix_pi = 1200;
		}else{
			var helix_pi = 1500;
		}
		object.position.x = helix_pi * Math.sin( phi );
		object.position.y = - ( i * 8 ) + (helix_pi/2);
		object.position.z = helix_pi * Math.cos( phi );

		vector.x = object.position.x * 2;
		vector.y = object.position.y;
		vector.z = object.position.z * 2;

		object.lookAt( vector );

		targets.helix.push( object );

	}

	// grid

	for ( var i = 0; i < avatar_num; i ++ ) {

		var object = new THREE.Object3D();
		if(sign_persons <= 500){
			var grid_pi = 900;
		}else if(sign_persons > 500 && sign_persons <= 800){
			var grid_pi = 1500;
		}else{
			var grid_pi = 2000;
		}
		object.position.x = ( ( i % 5 ) * (grid_pi/2) ) - grid_pi;
		object.position.y = ( - ( Math.floor( i / 5 ) % 5 ) * (grid_pi/2) ) + grid_pi;
		object.position.z = ( Math.floor( i / 25 ) ) * grid_pi - (grid_pi*2);

		targets.grid.push( object );
	}
	

	renderer = new THREE.CSS3DRenderer();
	renderer.setSize( window.innerWidth, window.innerHeight );
	renderer.domElement.style.position = 'absolute';
	renderer.domElement.className = 'abc';
	document.getElementById( 'container' ).appendChild( renderer.domElement );
	window.addEventListener( 'resize', onWindowResize, false );

}
var baseNum = 60,
	multiple = Math.floor(personArray.length/baseNum),
	round = 1;
function avatarSwitch(){
	console.log(multiple,round);
	console.log(personArray.length/baseNum);	
	if(multiple > 1){
		for (var i = round * baseNum; i < (round+1) * baseNum; i++){
			avatar[i % baseNum] = personArray[i].image;
		}
		$('.element').each(function(){
			var index = $(this).index();
			$(this).find('img').attr('src',avatar[index]);
		});
		round = round==multiple - 1 ? 0 : round + 1;
	}
}

function transform( shape, duration, style, time ) {
	TWEEN.removeAll();
	scene_init();
	if(style == 0){
		if(line==0){
			$('.element').width(30);
			$('.element').height(30);
		}else{
			$('.element').width(20);
			$('.element').height(20);
		}
		
		if($('.element').length > avatar_num){
			$('.element').show();
		}
		for ( var i = 0; i < objects.length; i ++ ) {

			var object = objects[ i ];
			var target = shape[ i ];

			new TWEEN.Tween( object.position )
				.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
				.easing( TWEEN.Easing.Exponential.InOut )						
				.start();

			
				new TWEEN.Tween( object.position )
					.to( { x: -9500,z: target.position.z }, Math.random() * 1000 + 2000 )
					.delay(Math.random() * 1000 + (time*1000-2500))
					.start();
			
			new TWEEN.Tween( object.rotation )
				.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
				.easing( TWEEN.Easing.Exponential.InOut )
				.start();
		}
	}else{
		if($('.element').length > avatar_num){
			$('.element').slice(avatar_num).hide();//.hide()
		}
		for ( var i = 0; i < avatar_num; i ++ ) {

			var object = objects[ i ];
			var target = shape[ i ];

			new TWEEN.Tween( object.position )
				.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
				.easing( TWEEN.Easing.Exponential.InOut )						
				.start();

			if(style == 1){ 
				new TWEEN.Tween( object.position )
					.to( { y: -5000,z: target.position.z }, Math.random() * 1000 + 2000 )
					.delay(Math.random() * 1000 + (time*1000-2500))
					.start();
			}else if(style == 2){ 
				new TWEEN.Tween( object.position )
					.to( { y: 3000,z: target.position.z }, Math.random() * 1000 + 2000 )
					.delay(Math.random() * 1000 + (time*1000-4000))
					.start();
			}else if(style == 3){ 
				new TWEEN.Tween( object.position )
					.to( { y: 3000,z: target.position.z }, Math.random() * 1000 + 2000 )
					.delay(Math.random() * 1000 + (time*1000-4000))
					.start();
			}

			new TWEEN.Tween( object.rotation )
				.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
				.easing( TWEEN.Easing.Exponential.InOut )
				.start();
		}
	}
	new TWEEN.Tween( this )
		.to( {}, duration * time/2 )
		.onUpdate(function(){ 
			render(style);
		})
		.start();

	setTimeout(function(){ 
		rotationY_add = 0;
		animate_start = 0;
		style += 1;
		avatarSwitch();
		if (style == 4){
			style=0;
			scene_init();
		}
		if(style==0){
			if(line==0){
				$('.element').width(30);
				$('.element').height(30);
			}else{
				$('.element').width(20);
				$('.element').height(20);
			}
		}else{
			$('.element').width(120);
			$('.element').height(120);
		}
		switch(style){
			case 0:							
				transform( targets.table, 2000, 0, table_time );
				break;
			case 1:
				transform( targets.sphere, 2000, 1, sphere_time );
				break;
			case 2:
				transform( targets.helix, 2000, 2, helix_time );
				break;
			case 3:
				transform( targets.grid, 2000, 3, grid_time );
				break;
		}
	},time * 1000);

}

function onWindowResize() {

	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();

	renderer.setSize( window.innerWidth, window.innerHeight );

	render();

}

function animate() {
	requestAnimationFrame( animate );
	TWEEN.update();
}
function scene_init() {
	scene.position.x = 0;
	scene.position.y = 0;
	scene.position.z = 0;
	scene.rotation.x = 0;
	scene.rotation.y = 0;
	scene.rotation.z = 0;
}						
function render(style) {
	if(style == 0){		
		animate_start += 1;					
		if (animate_start > 1600){
			if(scene.position.z >0){ 
				scene.position.z -= 7;							
			}						
		}else if (animate_start > 900){
			scene.position.z = scene.position.z;						
		}else if (animate_start > 200){
			scene.position.z += 7;
		}				
	}
	if(style == 1){
		animate_start += 1;					
		scene.rotation.y += 0.01;
		
		if (animate_start > 1600){
			if(scene.position.z >0){ 
				scene.position.z -= 7;							
			}						
		}else if (animate_start > 600){
			scene.position.z = scene.position.z;						
		}else if (animate_start > 200){
			scene.position.z += 10;
		}
	}
	if(style == 2){	
		animate_start += 1;	
		scene.rotation.y -= 0.01;
		
		if (animate_start > 1600){
			if(scene.position.z >0) scene.position.z -= 7;						
		}else if (animate_start > 600){
			scene.position.z = scene.position.z;						
		}else if (animate_start > 100){
			scene.position.z += 7;
		}
	}
	if(style == 3){						
		animate_start += 1;					
		if (animate_start > 1000){
			if(scene.position.z >0){ 
				scene.position.z -= 5;							
			}						
		}else if (animate_start > 600){
			scene.position.z = scene.position.z;						
		}else if (animate_start > 200){
			scene.position.z += 7;
		}	
	}
	
	renderer.render( scene, camera );

}