<?php

	$id = intval($_GET['id']);
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "nsb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    $sql = "SELECT id, name, obj_filename, mtl_filename FROM models WHERE id=" . $id;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        $row = $result->fetch_assoc();
		$name = $row['name'];
		$obj_filename = $row['obj_filename'];
		$mtl_filename = $row['mtl_filename'];

		echo $name;
		echo $obj_filename;
		echo $mtl_filename;
    } else {
        echo "0 results";
    }
    $conn->close();

?>

<html lang="en">
	<head>
		<title>three.js webgl - OBJLoader + MTLLoader</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<style>
			body {
				font-family: Monospace;
				background-color: #fff;
				color: #fff;
				margin: 0px;
				overflow: hidden;
			}
			#info {
				background-color: #fff;
				color: #000;
				position: absolute;
				top: 10px;
				width: 100%;
				text-align: center;
				z-index: 100;
				display:block;
				font-weight: bold; 
				text-decoration: underline; 
				cursor: pointer;
			}
		</style>
	</head>

	<body>
		<div id="info">
		</div>

		<script src="js/three.js"></script>

		<script src="js/DDSLoader.js"></script>
		<script src="js/MTLLoader.js"></script>
		<script src="js/OBJLoader.js"></script>

		<script src="js/OrbitControls.js"></script>
		<script src="js/Detector.js"></script>
		<script src="js/stats.min.js"></script>

		<script>

			if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

			var container, stats;
			var camera, scene, renderer;

			var clothGeometry;
			var sphere;
			var object;

			loadObject();
			
			function loadObject() {
				var infoBox = document.getElementById( 'info' );
				var onProgress = function ( xhr ) {
					if ( xhr.lengthComputable ) {
						var percentComplete = xhr.loaded / xhr.total * 100;
						infoBox.innerHtml = Math.round(percentComplete, 2) + '% downloaded' ;
						console.log( Math.round(percentComplete, 2) + '% downloaded' );
					}
				};

				var onError = function ( xhr ) { };

				THREE.Loader.Handlers.add( /\.dds$/i, new THREE.DDSLoader() );

				var mtlLoader = new THREE.MTLLoader();
				mtlLoader.setPath( 'content/<?= $id ?>/' );
				mtlLoader.load( '<?= $mtl_filename ?>', function( materials ) {

					materials.preload();

					var objLoader = new THREE.OBJLoader();
					objLoader.setMaterials( materials );
					objLoader.setPath( 'content/<?= $id ?>/' );
					objLoader.load( '<?= $obj_filename ?>', function ( object ) {

                        object.traverse( function(node) {
                            if (node instanceof THREE.Mesh) {
                                node.castShadow = true;
                                node.receiveShadow = true;
                            }
                        });

						init( object );
						animate();
					}, onProgress, onError );

				});			}

			function init(object) {

				container = document.createElement( 'div' );
				document.body.appendChild( container );

				// scene

				scene = new THREE.Scene();
				scene.fog = new THREE.Fog( 0xcce0ff, 500, 5000 );

				var loader = new THREE.TextureLoader();

				// ground

				var groundTexture = loader.load( 'textures/grasslight-big.jpg' );
				groundTexture.wrapS = groundTexture.wrapT = THREE.RepeatWrapping;
				groundTexture.repeat.set( 25, 25 );
				groundTexture.anisotropy = 16;

				var groundMaterial = new THREE.MeshPhongMaterial( { color: 0xffffff, specular: 0x111111, map: groundTexture } );

				var mesh = new THREE.Mesh( new THREE.PlaneBufferGeometry( 20000, 20000 ), groundMaterial );
				mesh.position.y = - 1;
				mesh.rotation.x = - Math.PI / 2;
				mesh.receiveShadow = true;
				scene.add( mesh );

				object.position.x = 0;
				object.position.y = 0;
				object.position.z = 0;
		        scene.add( object );
				
// 				http://stackoverflow.com/questions/34098571/fit-3d-object-collada-file-within-three-js-canvas-on-initial-load

// 				var bbox = new THREE.Box3().setFromObject( object );

// 				var oL,cL; // for the math to make it readable
// 				var FOV = 45 * (Math.PI / 180); // convert to radians
// 				var objectLocation = oL = object.position;
// 				var objectRadius = bbox.getBoundingSphere().radius;
// 				var cameraLocation = cL = {x : 1000, y : 150, z : 1000};
// 				var farPlane = 10000;
// 				var nearPlane = 1;
// 				var displayWidth = 1600;
// 				var displayHeight = 1000;

// 				// Get the distance from camera to object
// 				var distToObject = Math.sqrt(Math.pow(oL.x - cL.x, 2) + Math.pow(oL.y - cL.y, 2) + Math.pow(oL.z - cL.z, 2));

// 				// trig inverse tan of opposite over adjacent.
// 				var objectAngularSize = Math.atan( (objectRadius) / distToObject ) * 2;
// 				var objectView = objectAngularSize / FOV;
// 				var objectPixelSize = objectView * displayWidth;

// 				// Approx size in pixels you want the object to occupy
// 				var requieredObjectPixelSize = 900;

// 				// camera distance to object
// 				var distToObject = Math.sqrt(Math.pow(oL.x - cL.x, 2) + Math.pow(oL.y - cL.y, 2) + Math.pow(oL.z - cL.z, 2));

// 				// get the object's angular size.
// 				var objectAngularSize = Math.atan( (objectRadius) / distToObject ) * 2;

// 				// get the fraction of the FOV the object must occupy to be 900 pixels
// 				var scaling = requieredObjectPixelSize / displayWidth;

// 				// get the angular size the object has to be
// 				var objectAngularSize = FOV * scaling;

// 				// use half the angular size to get the distance the camera must be from the object
// 				distToObject = objectRadius / Math.tan(objectAngularSize / 2);				
				
// 				// Get the vector from the object to the camera
// 				var toCam = {
// 					x : cL.x - oL.x,
// 					y : cL.y - oL.y,
// 					z : cL.z - oL.z,
// 				}
// 				// First length
// var len = Math.sqrt(Math.pow(toCam.x, 2) + Math.pow(toCam.y, 2) + Math.pow(toCam.z, 2));
// // Then divide to normalise (you may want to test for divide by zero)
// toCam.x /= len;
// toCam.y /= len;
// toCam.z /= len;
// toCam.x *= distToObject;
// toCam.y *= distToObject;
// toCam.z *= distToObject;
// cL.x = oL.x + toCam.x;
// cL.y = oL.y + toCam.y;
// cL.z = oL.z + toCam.z;

				// camera

				camera = new THREE.PerspectiveCamera( 30, window.innerWidth / window.innerHeight, 1, 10000 );
				camera.position.x = 1000;
				camera.position.y = 150;
				camera.position.z = 1000;
				scene.add( camera );

				// lights

				var light, materials;

				scene.add( new THREE.AmbientLight( 0x666666 ) );

				light = new THREE.DirectionalLight( 0xdfebff, 1.75 );
				light.position.set( 50, 200, 100 );
				light.position.multiplyScalar( 1.3 );

				light.castShadow = true;

				light.shadow.mapSize.width = 1024 * 2;
				light.shadow.mapSize.height = 1024 * 2;

				var d = 1000;

				light.shadow.camera.left = - d;
				light.shadow.camera.right = d;
				light.shadow.camera.top = d;
				light.shadow.camera.bottom = - d;

				light.shadow.camera.far = 10000;

				scene.add( light );

				// renderer

				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.setClearColor( scene.fog.color );

				container.appendChild( renderer.domElement );

				renderer.gammaInput = true;
				renderer.gammaOutput = true;

				renderer.shadowMap.enabled = true;

				// controls
				var controls = new THREE.OrbitControls( camera, renderer.domElement );
				controls.maxPolarAngle = Math.PI * 0.5;
				controls.minDistance = 500;
				controls.maxDistance = 2000;
				controls.enablePan = false;

				// performance monitor

				// stats = new Stats();
				// container.appendChild( stats.dom );

				//

				window.addEventListener( 'resize', onWindowResize, false );

				// sphere.visible = ! true

			}

			//

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

			//

			function animate() {

				requestAnimationFrame( animate );

				var time = Date.now();

				// windStrength = Math.cos( time / 7000 ) * 20 + 40;
				// windForce.set( Math.sin( time / 2000 ), Math.cos( time / 3000 ), Math.sin( time / 1000 ) ).normalize().multiplyScalar( windStrength );

				// simulate( time );
				render();
				//stats.update();

			}

			function render() {

				// var p = cloth.particles;

				// for ( var i = 0, il = p.length; i < il; i ++ ) {

				// 	clothGeometry.vertices[ i ].copy( p[ i ].position );

				// }

				// clothGeometry.computeFaceNormals();
				// clothGeometry.computeVertexNormals();

				// clothGeometry.normalsNeedUpdate = true;
				// clothGeometry.verticesNeedUpdate = true;

				// sphere.position.copy( ballPosition );

				//camera.lookAt( scene.position );

				renderer.render( scene, camera );

			}

		</script>

	</body>
</html>