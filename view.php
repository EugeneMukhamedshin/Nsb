<?php
    header('Content-type: text/html; charset=utf-8');
?>

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
				background-color: #ffffff;
				margin: 0px;
				height: 100%;
				color: #555;
				font-family: 'inconsolata';
				font-size: 15px;
				line-height: 18px;
				overflow: hidden;
			}
			#info {
				color: #000;
				width: 100%;
				text-align: center;
				z-index: 100;
				display:block;
				font-weight: bold; 
				text-decoration: underline; 
				cursor: pointer;
			}
			#panel {
				position: fixed;
				left: 0px;
				width: 310px;
				height: 100%;
				overflow: auto;
				background: #fafafa;
			}
			#panel.span {
				font-size: 24px;
				color: black;
				margin: 10px;
			}
			#panel.h1 {
				margin: 10px;
				font-size: 25px;
				font-weight: normal;
			}
			#view {
				position: absolute;
				border: 0px;
				left: 310px;
				width: calc(100% - 310px);
				height: 100%;
				overflow: auto;
				margin: 10px;
			}
			#logo {
				width: 280px;
				margin: 10px;
			}
		</style>
	</head>

	<body>

		<div id="panel">
			<img src="textures/logo.png" id="logo"/>
			<h1><?= $name ?></h1>
		</div>

		<div id="view">
			<center>
				<span id="info"></span>
			</center>
		</div>

		<script src="js/three.js"></script>

		<script src="js/DDSLoader.js"></script>
		<script src="js/MTLLoader.js"></script>
		<script src="js/OBJLoader.js"></script>

		<script src="js/OrbitControls.js"></script>
		<script src="js/Detector.js"></script>
		<script src="js/stats.min.js"></script>
		<script src="dist/jszip.js"></script>
		<script src="dist/jszip-utils.js"></script>

		<script>

			if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

			var container, stats;
			var camera, scene, renderer;

			var infoBox = document.getElementById("info");

			var start = new Date();
			
			loadObject();

			function loadObject() {
				var onProgress = function ( xhr ) {
					if ( xhr.lengthComputable ) {
						var percentComplete = xhr.loaded / xhr.total * 100;
						infoBox.innerText = Math.round(percentComplete, 2) + '% скачано';
						console.log( Math.round(percentComplete, 2) + '% downloaded' );
					}
				};

				var onError = function ( xhr ) { };

				THREE.Loader.Handlers.add( /\.dds$/i, new THREE.DDSLoader() );

				var filePath = 'content/<?= $id ?>/<?= $obj_filename ?>';
				console.log(filePath);
				JSZipUtils.getBinaryContent('content/<?= $id ?>/<?= $obj_filename ?>', function(err, data) {
					try {
						JSZip.loadAsync(data)
						.then(function(zip) {
							infoBox.innerText = 'Downloaded ' + (new Date() - start);
							return zip.file('<?= $obj_filename ?>').async('string');
						})
						.then(function success(text) {
							infoBox.innerText = 'Unzipped ' + (new Date() - start);
							parse(text);
						}, function error(e) {
							infoBox.innerText = e;
						});
						} catch(e) {
							infoBox.innerText = e;
						}
				});
				// var mtlLoader = new THREE.MTLLoader();
				// mtlLoader.setPath( 'content/<?= $id ?>/' );
				// mtlLoader.load( '<?= $mtl_filename ?>', function( materials ) {

				// 	materials.preload();

				// 	var objLoader = new THREE.OBJLoader();
				// 	objLoader.setMaterials( materials );
				// 	objLoader.setPath( 'content/<?= $id ?>/' );
				// 	objLoader.load( '<?= $obj_filename ?>', function ( object ) {
                //         object.traverse( function(node) {
                //             if (node instanceof THREE.Mesh) {
                //                  node.castShadow = true;
                //                  node.receiveShadow = true;
 				// 			 	// var geometry = new THREE.Geometry().fromBufferGeometry( node.geometry );
				// 			 	// geometry.computeFaceNormals();
				// 			 	// geometry.mergeVertices();
				// 			 	// geometry.computeVertexNormals();
				// 			 	// node.geometry = new THREE.BufferGeometry().fromGeometry( geometry );
                //             }
                //         });

				// 		init( object );
				// 		animate();
				// 	}, onProgress, onError );

				// });			
			}

			function parse(content)
			{
				var onProgress = function ( xhr ) {
					if ( xhr.lengthComputable ) {
						var percentComplete = xhr.loaded / xhr.total * 100;
						infoBox.innerText = Math.round(percentComplete, 2) + '% скачано';
						console.log( Math.round(percentComplete, 2) + '% downloaded' );
					}
				};

				var onError = function ( xhr ) { };

				var mtlLoader = new THREE.MTLLoader();
				mtlLoader.setPath( 'content/<?= $id ?>/' );
				mtlLoader.load( '<?= $mtl_filename ?>', function( materials ) {

					infoBox.innerText = 'Materials loaded ' + (new Date() - start);
					materials.preload();

					var objLoader = new THREE.OBJLoader();
					objLoader.setMaterials( materials );
					var object = objLoader.parse( content );
					infoBox.innerText = 'Object parsed ' + (new Date() - start);
                    object.traverse( function(node) {
						if (node instanceof THREE.Mesh) {
							node.castShadow = true;
							node.receiveShadow = true;
							// var geometry = new THREE.Geometry().fromBufferGeometry( node.geometry );
							// geometry.computeFaceNormals();
							// geometry.mergeVertices();
							// geometry.computeVertexNormals();
							// node.geometry = new THREE.BufferGeometry().fromGeometry( geometry );
						}
                    });

					init( object );
					animate();
					infoBox.innerText = 'Scene initialized ' + (new Date() - start);
				});			
			}

			function init(object) {

				// container = document.createElement( 'div' );
				// document.body.appendChild( container );

				container = document.getElementById('view');

				// scene

				scene = new THREE.Scene();

// 				http://stackoverflow.com/questions/34098571/fit-3d-object-collada-file-within-three-js-canvas-on-initial-load

 				var bbox = new THREE.Box3().setFromObject( object );
				var bsphere = bbox.getBoundingSphere();

				var fovG = 30
 				var oL,cL; // for the math to make it readable
 				var FOV = fovG * (Math.PI / 180); // convert to radians
 				var objectLocation = oL = bsphere.center;
 				var objectRadius = cf = bsphere.radius;
 				var cameraLocation = cL = {x : 60000, y : 30000, z : 100000};
 				var farPlane = 10000;
 				var nearPlane = 9999;

				var currentDistToObject = ac = Math.sqrt(Math.pow(oL.x - cL.x, 2) + Math.pow(oL.y - cL.y, 2) + Math.pow(oL.z - cL.z, 2));
				var requiredDistToObject = dc = cf * 0.8 / Math.sin(FOV / 2);
				var coeff = requiredDistToObject / currentDistToObject;

				cL.x *= coeff;
				cL.y *= coeff;
				cL.z *= coeff;

				nearPlane = (requiredDistToObject - objectRadius) * 0.2; 
				farPlane = requiredDistToObject + objectRadius * 4; 

				object.position.x = -bsphere.center.x;
				object.position.y = -bsphere.center.y;
				object.position.z = -bsphere.center.z;

				var loader = new THREE.TextureLoader();
				// ground

				var groundTexture = loader.load( 'textures/grasslight-big.jpg' );
				groundTexture.wrapS = groundTexture.wrapT = THREE.RepeatWrapping;
				groundTexture.repeat.set( 4, 4 );
				groundTexture.anisotropy = 16;

				var groundMaterial = new THREE.MeshPhongMaterial( { color: 0xf0f0f0, specular: 0x111111, map: groundTexture } );

				var mesh = new THREE.Mesh( new THREE.CircleGeometry( bsphere.radius * 1.5, 64 ), groundMaterial );
				mesh.position.y = -bsphere.center.y;
				mesh.rotation.x = - Math.PI / 2;
				mesh.receiveShadow = true;
				scene.add( mesh );

		        scene.add( object );

				// var groundTexture1 = loader.load( 'textures/road_brick_9.jpg' );
				// groundTexture1.wrapS = groundTexture1.wrapT = THREE.RepeatWrapping;
				// groundTexture1.repeat.set( 80, 80 );
				// groundTexture1.anisotropy = 16;

				// var groundMaterial1 = new THREE.MeshPhongMaterial( { color: 0xf5f5f5, specular: 0x111111 , map: groundTexture1 } );

				// var mesh = new THREE.Mesh( new THREE.PlaneBufferGeometry( bsphere.radius * 30, bsphere.radius * 30 ), groundMaterial1 );
				// mesh.position.y = -bsphere.center.y - 5;
				// mesh.rotation.x = - Math.PI / 2;
				// mesh.receiveShadow = true;
				// scene.add( mesh );

				// camera

				camera = new THREE.PerspectiveCamera( fovG, container.clientWidth / container.clientHeight, nearPlane, farPlane );
				camera.position.x = cL.x;
				camera.position.y = cL.y;
				camera.position.z = cL.z;
				scene.add( camera );

				// lights

				var light, materials;

				scene.add( new THREE.AmbientLight( 0x666666 ) );

				light = new THREE.DirectionalLight( 0xdfebff, 1.25 );

				light.position.set( objectRadius * 0.2, objectRadius * 1.2, objectRadius * 0.2 );
				//light.position.multiplyScalar( 1 / coeff );

				//light.castShadow = true;

				//light.shadow.mapSize.width = 4096;
				//light.shadow.mapSize.height = 4096;

				//var d = objectRadius;

				//light.shadow.camera.left = - d;
				//light.shadow.camera.right = d;
				//light.shadow.camera.top = d;
				//light.shadow.camera.bottom = - d;
				//light.shadow.type = THREE.PCFSoftShadowMap;
				//light.shadowMapSoft = true;

				//light.shadow.camera.far = objectRadius * 2;
				//light.shadow.camera.near = objectRadius * 0.2;

				scene.add( light );

				// renderer

				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( container.clientWidth, container.clientHeight );
				renderer.setClearColor( 0xF5F5F5 );

				container.appendChild( renderer.domElement );

				renderer.gammaInput = true;
				renderer.gammaOutput = true;

				//renderer.shadowMap.enabled = true;
				//renderer.shadowMapSoft = true;
				//renderer.shadowMap.Type = THREE.PCFShadowMap;

				// controls
				var controls = new THREE.OrbitControls( camera, renderer.domElement );
				controls.maxPolarAngle = Math.PI * 0.5;
				controls.minDistance = objectRadius * 1.5;
				controls.maxDistance = requiredDistToObject * 1.6;
				controls.enablePan = false;

				window.addEventListener( 'resize', onWindowResize, false );
			}

			//

			function onWindowResize() {
				camera.aspect = container.clientWidth / container.clientHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( container.clientWidth, container.clientHeight );
			}

			//

			function animate() {
				requestAnimationFrame( animate );
				render();
			}

			function render() {
				renderer.render( scene, camera );
			}

		</script>

	</body>
</html>