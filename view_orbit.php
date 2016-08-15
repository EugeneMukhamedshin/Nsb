
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
		<title>three.js webgl - ShadowMapViewer example </title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<style>
			body {
				font-family: Monospace;
				background-color: #000;
				color: #fff;
				margin: 0px;
				overflow: hidden;
			}
			#info {
				position: absolute;
				top: 10px;
				width: 100%;
				text-align: center;
				z-index: 100;
				display:block;
			}
			#info a { color: #f00; font-weight: bold; text-decoration: underline; cursor: pointer }
		</style>
	</head>
	<body>
		<div id="info">
		</div>

		<script src="js/three.js"></script>
		<script src="js/OrbitControls.js"></script>
		<script src="js/Detector.js"></script>
		<script src="js/stats.min.js"></script>
		<script src="js/DDSLoader.js"></script>
		<script src="js/MTLLoader.js"></script>
		<script src="js/OBJLoader.js"></script>

		<script>

			if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

			var camera, scene, renderer, clock, stats;
			var dirLight, spotLight1, spotLight2, spotLight3, spotLight4 ;
			var torusKnot, cube;
			var dirLightShadowMapViewer, spotLightShadowMapViewer;

			init();
			animate();


			function init() {

				initScene();
				initMisc();

				document.body.appendChild( renderer.domElement );
				window.addEventListener( 'resize', onWindowResize, false );

			}

            function initLightShadow( light ) {
				light.castShadow = true;
				light.shadow.camera.near = 1;
				light.shadow.camera.far = 1500;
				light.shadow.camera.right = 150;
				light.shadow.camera.left = - 150;
				light.shadow.camera.top	= 150;
				light.shadow.camera.bottom = - 150;
				light.shadow.mapSize.width = 1024;
				light.shadow.mapSize.height = 1024;
            }

			function initScene() {

				camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 1000 );
				camera.position.set( 0, 150, 500 );

				scene = new THREE.Scene();

				// Lights

				ambLight = new THREE.AmbientLight( 0x404040 );
				scene.add( ambLight );

				dirLight1 = new THREE.DirectionalLight( 0xffffff, 0.3 );
				dirLight1.name = 'Dir. Light';
				dirLight1.position.set( 100, 400, 100 );
                initLightShadow(dirLight1);
				scene.add( dirLight1 );

				//scene.add( new THREE.CameraHelper( dirLight1.shadow.camera ) );

				dirLight2 = new THREE.DirectionalLight( 0xffffff, 0.3 );
				dirLight2.name = 'Dir. Light';
				dirLight2.position.set( 100, 400, -100 );
                initLightShadow(dirLight2);
				scene.add( dirLight2 );

				//scene.add( new THREE.CameraHelper( dirLight2.shadow.camera ) );

				dirLight3 = new THREE.DirectionalLight( 0xffffff, 0.3 );
				dirLight3.name = 'Dir. Light';
				dirLight3.position.set( -100, 400, 100 );
                initLightShadow(dirLight3);
				scene.add( dirLight3 );

				//scene.add( new THREE.CameraHelper( dirLight3.shadow.camera ) );

				dirLight4 = new THREE.DirectionalLight( 0xffffff, 0.3 );
				dirLight4.name = 'Dir. Light';
				dirLight4.position.set( -100, 400, -100 );
                initLightShadow(dirLight4);
				scene.add( dirLight4 );

				// //scene.add( new THREE.CameraHelper( dirLight4.shadow.camera ) );

				var geometry = new THREE.BoxGeometry( 100, 0.15, 100 );
				var material = new THREE.MeshPhongMaterial( {
					color: 0xa0adaf,
					shininess: 150,
					specular: 0xffffff,
					shading: THREE.SmoothShading
				} );

				var ground = new THREE.Mesh( geometry, material );
				ground.scale.multiplyScalar( 3 );
				ground.castShadow = false;
				ground.receiveShadow = true;
				scene.add( ground );

				// model

				var onProgress = function ( xhr ) {
					if ( xhr.lengthComputable ) {
						var percentComplete = xhr.loaded / xhr.total * 100;
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

                        scene.add( object );

					}, onProgress, onError );

				});

			}

			function initMisc() {

				renderer = new THREE.WebGLRenderer();
				renderer.setClearColor( 0x000000 );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.shadowMap.enabled = true;
				renderer.shadowMap.type = THREE.BasicShadowMap;

				// Mouse control
				controls = new THREE.OrbitControls( camera, renderer.domElement );
				controls.target.set( 0, 2, 0 );
				controls.update();

				// clock = new THREE.Clock();

				// stats = new Stats();
				// document.body.appendChild( stats.dom );

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );
			}

			function animate() {

				requestAnimationFrame( animate );
				controls.update();
				render();

				// stats.update();

			}

			function renderScene() {

				renderer.render( scene, camera );

			}

			function render() {

				// var delta = clock.getDelta();

				renderScene();
			}

		</script>
	</body>
</html>
