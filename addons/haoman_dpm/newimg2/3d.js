
var camera, scene, renderer;
var controls;

var oss = [];
var targets = { table: [], torus: [], sphere: [], helix: [], grid: [] };
var style = 'table',
	rotationY_add = 0,
	animate_start = 0;

function init() {
	camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 100000 );				
	camera.position.z = 2800;

	scene = new THREE.Scene();
	tableLine_odd = new THREE.Scene();
	tableLine_even = new THREE.Scene();

	// table
	for ( var i = 0; i < table.length; i ++ ) {

		var element = document.createElement( 'div' );
		element.className = 'element';
		
		var img = document.createElement( 'img' );
		img.src = table[ i ].src;
		img.setAttribute('bowtie', table[ i ].bowtie);
		img.setAttribute('company', table[ i ].company);
		img.setAttribute('name', table[ i ].name);
		img.setAttribute('text', table[ i ].text);
		img.setAttribute('imgId', table[ i ].id);
		element.appendChild( img );

		var ott = new THREE.CSS3DObject( element );
		ott.position.x = Math.random() * 4000 - 2000;
		ott.position.y = Math.random() * 4000 - 2000;
		ott.position.z = Math.random() * 4000 - 2000;

		// ott.position.x = 4e3 * Math.random() - 2e3;
		// ott.position.y = 4e3 * Math.random() - 2e3;
		// ott.position.z = 4e3 * Math.random() - 2e3;
		scene.add( ott );
		oss.push( ott );
		
		var ott = new THREE.Object3D();
		ott.position.z = -30000;

		targets.table.push( ott );					

	}

	

	// sphere
	var vector = new THREE.Vector3();				

	for ( var i = 0, l = oss.length; i < l; i ++ ) {

		if (i == 0){
			var phi = Math.acos( -1 + ( 2 * i ) / l ) * 1.025;
			var theta = Math.sqrt( l * Math.PI ) * phi * 1.01;
		}else{
			var phi = Math.acos( -1 + ( 2 * i ) / l );
			var theta = Math.sqrt( l * Math.PI ) * phi;
		}

		var ott = new THREE.Object3D();

		ott.position.x = 750 * Math.cos( theta ) * Math.sin( phi );
		ott.position.y = 750 * Math.sin( theta ) * Math.sin( phi );
		ott.position.z = 750 * Math.cos( phi );

		vector.copy( ott.position ).multiplyScalar( 2 );

		ott.lookAt( vector );

		targets.sphere.push( ott );

	}

	// torus
	var vector = new THREE.Vector3();				

	for ( var i = 0, l = oss.length; i < l; i ++ ) {

		var ott = new THREE.Object3D();
		
		// ott.position.x = 1200*Math.cos(-i);
		// ott.position.y = 1200*Math.sin(-i);

		ott.position.x = ((i % 5) * 600) - 800;
        ott.position.y = (-(Math.floor(i / 5) % 5) * 400) + 800;
		
		ott.position.z = 200-i*60*1.5;
		ott.rotation.z = -i*0.03;



		ott.lookAt( vector );

		targets.torus.push( ott );

	}


	// grid
	var vector = new THREE.Vector3();				

	for ( var i = 0, l = oss.length; i < l; i ++ ) {

		var ott = new THREE.Object3D();

		ott.position.x = ((i % 5) * 600) - 1200;
        // ott.position.y = (-(Math.floor(i / 5) % 5) * 400) + 800;
        ott.position.y = (-(Math.floor(i / 5) % 5) * 400) + 800;
        // ott.position.z = (Math.floor(i / 25)) * 1000 - 2000;
        ott.position.z = 200-i*60*1.5;
		ott.rotation.z = -i*0.03;

        vector.x = ott.position.x;
		vector.y = ott.position.y;
		vector.z = ott.position.z;

		ott.lookAt( vector );

		targets.grid.push( ott );

	}

	// helix
	var vector = new THREE.Vector3();

	for ( var i = 0, l = oss.length; i < l; i ++ ) {

		var phi = i * 0.175 + Math.PI;

		var ott = new THREE.Object3D();

		ott.position.x = 900 * Math.sin( phi );
		ott.position.y = - ( i * 8 ) + 450;
		ott.position.z = 900 * Math.cos( phi );

		vector.x = ott.position.x ;
		vector.y = ott.position.y;
		vector.z = ott.position.z ;
		ott.lookAt( vector );

		targets.helix.push( ott );

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

	for ( var i = 0; i < oss.length; i ++ ) {

		var ott = oss[ i ];
		var target = shape[ i ];

		new TWEEN.Tween( ott.position )
			.to( { x: target.position.x, y: target.position.y, z: target.position.z }, duration )
			.easing( TWEEN.Easing.Exponential.InOut )						
			.start();

		if (style == 'table'){ 
			$("div").remove(".element");
		}else if(style == 'torus'){
			new TWEEN.Tween( ott.position )
				.to( {x: target.position.x, y: target.position.y,z: -300000}, Math.random() * 1000 + 3000 )
				.delay(Math.random() * 1000 + 20000)
				.start();
		}else if(style == 'sphere'){
			new TWEEN.Tween( ott.position )
				.to( { y: -5000}, Math.random() * 1000 + 3000 )
				.delay(Math.random() * 1000 + 19500)
				.start();
		}else if(style == 'grid'){
			// console.log(212);
			new TWEEN.Tween( ott.position )
				.to( { x: target.position.x, y: target.position.y, z: target.position.z}, 1000 )
				.delay(195000)
				.start();
			
		}else if(style == 'helix'){ 			
			new TWEEN.Tween( ott.position )
				.to( { y: 3000,z: target.position.z }, Math.random() * 1000 + 2000 )
				.delay(Math.random() * 1000 + 18500)
				.start();
		}

		new TWEEN.Tween( ott.rotation )
			.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
			.easing( TWEEN.Easing.Exponential.InOut )
			.start();
	}	

	new TWEEN.Tween( this )
		.to( {}, 1000000 )
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
	window.requestAnimationFrame( animate );
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

function update(){
  for ( var i = 0; i < oss.length; i ++ ) {
	var ott = oss[ i ];
    ott.position.z+=20;
    ott.rotation.z+=i*1/1000;
    if(ott.position.z>800)
    {
		ott.position.z = 200-(oss.length-1)*60*1.5;
    }
    
  }
}

function update2(){
  for ( var i = 0; i < oss.length; i ++ ) {
	var ott = oss[ i ];
    ott.position.z+=180;
    if(ott.position.z>800)
    {

		ott.position.z = 200-(oss.length-1)*60*1.5;
    }
    
  }
}



function render(style) {
	
	if(style == 'torus'){		//蜘蛛网
		animate_start += 1;					
		scene.rotation.z += 0.05;
		if (animate_start > 300){
			update();						
		}
	}

	if(style == 'grid'){
		// animate_start += 1;					
		// scene.position.z += 15;
		// if (animate_start > 150){
			update2();						
		// }
	// update2()	//球
		// animate_start += 1;
		// scene.position.z -= 0.03;
		// scene.position.z += 15;
		// if (animate_start > 50){
		// 	update2();						
		// }
		// if (animate_start > 600){
		// 	if(scene.position.z >0) scene.position.z -= 15;						
		// }

		// else if (animate_start > 300){
		// 	scene.position.z = scene.position.z;						
		// }

		// else if (animate_start > 150){
		// 	scene.position.z += 15;
		// }
	}

	if(style == 'sphere'){		//球
		animate_start += 1;					
		if (animate_start > 900){
			if(scene.position.z >0){ 
				scene.rotation.y += 0.005;
				scene.position.z -= 5;							
			}						
		}else if (animate_start > 650){
			scene.rotation.y += 0.001;
			scene.position.z = scene.position.z;
		}else if (animate_start > 400){
			scene.rotation.y += 0.003;
			scene.position.z += 5;
		} else {
			scene.rotation.y += 0.005;
		}
	}

	if(style == 'helix'){		//螺旋
		animate_start += 1;
		scene.rotation.y -= 0.02;
		if (animate_start > 600){
			if(scene.position.z >0) scene.position.z -= 7;						
		}else if (animate_start > 300){
			scene.position.z = scene.position.z;						
		}else if (animate_start > 150){
			scene.position.z += 7;
		}
	}

	renderer.render( scene, camera );

}