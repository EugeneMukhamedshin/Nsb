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
				position: absolute;
				border: 0px;
				left: 310px;
				// width: calc(100% - 310px);
				// height: 100%;
				width: 500px;
				height: 350px;
				overflow: auto;
				margin: 10px;
				z-index: 100;
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
			#view_canvas {
				position: absolute;
				border: 1px;
				left: 310px;
				width: calc(100% - 310px);
				height: 100%;
				overflow: auto;
				margin: 10px;
				z-index: 1000;
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

		<div id="view"><canvas id="view_canvas"/></div>

		<span id="info"></span>

		<script src="js/babylon/babylon.2.4.max.js"></script>
		<script src="js/babylon/babylon.objFileLoader.js"></script>
		
		<script src="dist/jszip.js"></script>
		<script src="dist/jszip-utils.js"></script>

		<script>

var createScene = function(engine) {
   // This creates a basic Babylon Scene object (non-mesh)
    var scene = new BABYLON.Scene(engine);

   	new BABYLON.HemisphericLight("light1", new BABYLON.Vector3(0, 1, 0), scene);
    var cam = new BABYLON.ArcRotateCamera("ArcRotateCamera", 0, 0, 5, new BABYLON.Vector3(0, 3, 0), scene);
    cam.attachControl(canvas);

    var loader = new BABYLON.AssetsManager(scene);

    var position = -5;
    var pos = function(t) {
        t.loadedMeshes.forEach(function(m) {
            m.position.x -= position;
        });
        position += 5;
    };

    var obj = loader.addMeshTask("bane", "", 'content/<?= $id ?>/', '<?= $obj_filename ?>');
    obj.onSuccess = pos;
    // var batman = loader.addMeshTask("batman", "", "https://dl.dropboxusercontent.com/u/17799537/objFileLoader/Batman/", "Batman_Injustice.obj");
    // batman.onSuccess = pos;
    // var penguin = loader.addMeshTask("penguin", "", "https://dl.dropboxusercontent.com/u/17799537/objFileLoader/Penguin/", "Penguin.obj");
    // penguin.onSuccess = pos;

    loader.onFinish = function() {
        engine.runRenderLoop(function () {
            scene.render();
        });
    };

    loader.load();

    return scene;

}

var canvas = document.getElementById('view_canvas');
var engine = new BABYLON.Engine(canvas, true);
var scene = createScene(engine);

			// if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

			// var container, stats;
			// var camera, scene, renderer;

			// var infoBox = document.getElementById("info");

			// var start = new Date();
			
			// loadObject();
			// animate();

			// function loadObject() {
			// 	var onProgress = function ( xhr ) {
			// 		if ( xhr.lengthComputable ) {
			// 			var percentComplete = xhr.loaded / xhr.total * 100;
			// 			infoBox.innerText = Math.round(percentComplete, 2) + '% скачано';
			// 			console.log( Math.round(percentComplete, 2) + '% downloaded' );
			// 		}
			// 	};

			// 	var onError = function ( xhr ) { };

			// 	THREE.Loader.Handlers.add( /\.dds$/i, new THREE.DDSLoader() );

			// 	var filePath = 'content/<?= $id ?>/<?= $obj_filename ?>';
			// 	console.log(filePath);
			// 	JSZipUtils.getBinaryContent('content/<?= $id ?>/<?= $obj_filename ?>', function(err, data) {
			// 		try {
			// 			JSZip.loadAsync(data)
			// 			.then(function(zip) {
			// 				infoBox.innerText = 'Downloaded ' + (new Date() - start);
			// 				return zip.file('<?= $obj_filename ?>').async('string');
			// 			})
			// 			.then(function success(text) {
			// 				infoBox.innerText = 'Unzipped ' + (new Date() - start);
			// 				parse(text);
			// 				text = null;
			// 			}, function error(e) {
			// 				infoBox.innerText = e;
			// 			});
			// 			} catch(e) {
			// 				infoBox.innerText = e;
			// 			}
			// 	});	
			// }

			// function parse(content)
			// {
			// 	var onProgress = function ( xhr ) {
			// 		if ( xhr.lengthComputable ) {
			// 			var percentComplete = xhr.loaded / xhr.total * 100;
			// 			infoBox.innerText = Math.round(percentComplete, 2) + '% скачано';
			// 			console.log( Math.round(percentComplete, 2) + '% downloaded' );
			// 		}
			// 	};

			// 	var onError = function ( xhr ) { };

			// 	var mtlLoader = new THREE.MTLLoader();
			// 	mtlLoader.setPath( 'content/<?= $id ?>/' );
			// 	mtlLoader.load( '<?= $mtl_filename ?>', function( materials ) {

			// 		infoBox.innerText = 'Materials loaded ' + (new Date() - start);
			// 		materials.preload();

			// 		var objLoader = new THREE.OBJLoader();
			// 		objLoader.setMaterials( materials );
			// 		var object = objLoader.parse( content );
			// 		infoBox.innerText = 'Object parsed ' + (new Date() - start);
            //         object.traverse( function(node) {
			// 			if (node instanceof THREE.Mesh) {
			// 				node.castShadow = true;
			// 				//node.receiveShadow = true;
			// 			}
            //         });

			// 		init( object );
			// 		object = null;
			// 		infoBox.innerText = 'Scene initialized ' + (new Date() - start);
			// 	});			
			// }

			// function init(object) {

			// 	container = document.getElementById('view');

			// 	// scene

			// 	scene = new THREE.Scene();

 			// 	var bbox = new THREE.Box3().setFromObject( object );
			// 	var bsphere = bbox.getBoundingSphere();

			// 	var factor = 10 / bsphere.radius;
			// 	object.scale.set(factor, factor, factor);

 			// 	bbox = new THREE.Box3().setFromObject( object );
			// 	bsphere = bbox.getBoundingSphere();

			// 	var fovG = 45
 			// 	var oL,cL; 
 			// 	var FOV = fovG * (Math.PI / 180); 
 			// 	var objectLocation = oL = bsphere.center;
 			// 	var objectRadius = cf = bsphere.radius;
 			// 	var cameraLocation = cL = {x : 60000, y : 30000, z : 100000};
 			// 	var farPlane = 10000;
 			// 	var nearPlane = 9999;

			// 	var currentDistToObject = ac = Math.sqrt(Math.pow(oL.x - cL.x, 2) + Math.pow(oL.y - cL.y, 2) + Math.pow(oL.z - cL.z, 2));
			// 	var requiredDistToObject = dc = cf * 0.8 / Math.sin(FOV / 2);
			// 	var coeff = requiredDistToObject / currentDistToObject;

			// 	cL.x *= coeff;
			// 	cL.y *= coeff;
			// 	cL.z *= coeff;

			// 	nearPlane = (requiredDistToObject - objectRadius) * 0.1; 
			// 	farPlane = requiredDistToObject + objectRadius * 4; 

			// 	object.position = objectLocation;
			// 	object.position.x = -bsphere.center.x;
			// 	object.position.y = -bsphere.center.y * 0.2;
			// 	object.position.z = -bsphere.center.z;

			// 	var loader = new THREE.TextureLoader();

			// 	// ground

			// 	var groundTexture = loader.load( 'textures/grasslight-big.jpg' );
			// 	groundTexture.wrapS = groundTexture.wrapT = THREE.RepeatWrapping;
			// 	groundTexture.repeat.set( 4, 4 );
			// 	groundTexture.anisotropy = 16;

			// 	loader = null;

			// 	var groundMaterial = new THREE.MeshPhongMaterial( { color: 0xe4e4e4, map: groundTexture } );

			// 	var mesh = new THREE.Mesh( new THREE.CircleGeometry( bsphere.radius * 1.5, 64 ), groundMaterial );
			// 	mesh.position.y = -bsphere.center.y * 0.2;
			// 	mesh.rotation.x = - Math.PI / 2;
			// 	mesh.receiveShadow = true;
			// 	scene.add( mesh );

		    //     scene.add( object );

			// 	// camera

			// 	camera = new THREE.PerspectiveCamera( fovG, container.clientWidth / container.clientHeight, nearPlane, farPlane );
			// 	camera.position.x = cL.x;
			// 	camera.position.y = cL.y;
			// 	camera.position.z = cL.z;
			// 	scene.add( camera );

			// 	// lights

			// 	var light, materials;

			// 	scene.add( new THREE.AmbientLight( 0x666666 ) );

			// 	light = new THREE.DirectionalLight( 0xdfebff, 2 );

			// 	light.position.set( objectRadius * 0.2, objectRadius * 1.2, objectRadius * 0.2 );

			// 	light.castShadow = true;

			// 	light.shadow.mapSize.width = 512;
			// 	light.shadow.mapSize.height = 512;
			// 	light.shadow.bias = 0;

			// 	var d = objectRadius;

			// 	light.shadow.camera.left = - d;
			// 	light.shadow.camera.right = d;
			// 	light.shadow.camera.top = d;
			// 	light.shadow.camera.bottom = - d;
			// 	light.shadow.type = THREE.PCFSoftShadowMap;
			// 	light.shadowMapSoft = true;

			// 	light.shadow.camera.far = objectRadius * 2;
			// 	light.shadow.camera.near = objectRadius * 0.2;

			// 	scene.add( light );

			// 	// renderer

			// 	renderer = new THREE.WebGLRenderer( { antialias: true } );
			// 	renderer.setPixelRatio( window.devicePixelRatio );
			// 	renderer.setSize( container.clientWidth, container.clientHeight );
			// 	renderer.setClearColor( 0xF5F5F5 );

			// 	container.appendChild( renderer.domElement );

			// 	renderer.gammaInput = true;
			// 	renderer.gammaOutput = true;

			// 	renderer.shadowMap.enabled = true;
			// 	renderer.shadowMapSoft = true;
			// 	renderer.shadowMap.Type = THREE.PCFShadowMap;

			// 	// controls
			// 	var controls = new THREE.OrbitControls( camera, renderer.domElement );
			// 	controls.maxPolarAngle = Math.PI * 0.5;
			// 	controls.minDistance = objectRadius * 1.2;
			// 	controls.maxDistance = requiredDistToObject * 1.6;
			// 	controls.enablePan = false;

			// 	window.addEventListener( 'resize', onWindowResize, false );
			// }

			// function onWindowResize() {
			// 	camera.aspect = container.clientWidth / container.clientHeight;
			// 	camera.updateProjectionMatrix();

			// 	renderer.setSize( container.clientWidth, container.clientHeight );
			// }

			// function animate() {
			// 	requestAnimationFrame( animate );
			// 	render();
			// }

			// function render() {
			// 	if (renderer != null)
			// 		renderer.render( scene, camera );
			// }

		</script>
	</body>
</html>