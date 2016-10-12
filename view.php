<?php
header('Content-type: text/html; charset=utf-8');
?>

<?php

include 'config.php';

$id = intval($_GET['id']);

$config = new config();
// Create connection
$conn = new mysqli($config->serverName, $config->username, $config->password, $config->dbName);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT m.id, m.name, m.description, m.link, mf_obj.translit_file_name obj_filename, mf_mtl.translit_file_name mtl_filename, m.add_ground, m.enable_shadows
  FROM models m
  INNER JOIN model_files mf_obj ON m.id = mf_obj.model_id AND mf_obj.file_type = 1
  INNER JOIN model_files mf_mtl ON m.id = mf_mtl.model_id AND mf_mtl.file_type = 2
  WHERE m.id = " . $id;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $obj_filename = $row['obj_filename'];
    $mtl_filename = $row['mtl_filename'];
    $description = $row['description'];
    $link = $row['link'];
    $addGround = $row['add_ground'];
    $enable_shadows = $row['enable_shadows'];
} else {
    echo "0 results";
}
$conn->close();

?>

<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <title><?= $name ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.min.css">
    <script src="dist/jszip/jszip.js"></script>
    <script src="dist/jszip/jszip-utils.js"></script>
    <script src="dist/threejs/three.js"></script>
    <script src="dist/threejs/DDSLoader.js"></script>
    <script src="dist/threejs/Detector.js"></script>
    <script src="dist/threejs/MTLLoader.js"></script>
    <script src="dist/threejs/OBJLoader.js"></script>
    <script src="dist/threejs/OrbitControls.js"></script>

    <style>
        body {
            background-color: #ffffff;
            margin: 0px;
            font-family: Helvetica;
            font-size: 15px;
            line-height: 18px;
            overflow: hidden;
            color: #999;
        }

        #info {
            position: relative;
            border: 0px;
            left: 310px;
            width: 500px;
            height: 35px;
            overflow: auto;
            margin: 10px;
            z-index: 200;
            text-align: center;
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

        .panel-contacts {
            display: block;
            font-size: 12px;
            vertical-align: middle;
            background-color: #FDFDFD;
            border-bottom: 1px solid #E1E1E1;
            text-align: left;
            padding: 5px 5px 5px 15%;
            min-width: 600px;
        }

        .panel-logo {
            display: block;
            text-align: left;
            padding: 15px 15px 15px 15%;
        }

        .panel-logo img {
            height: 30px;
        }

        .panel-info {
            display: block;
            font-size: 16px;
            background-color: #FDFDFD;
            border: 1px solid #E1E1E1;
            text-align: left;
            padding: 10px 5px 10px 15%;
            min-width: 900px;
        }

        .panel-info .info-name {
            margin-left: 50px;
            padding-bottom: 4px;
            border-bottom: 2px solid #F07F3B;
        }

        .panel-info .info-link {
            padding-left: 50px;
        }

        .panel-view {
            display: block;
        }

        #view-container {
            position: relative;
            /* Firefox */
            height: -moz-calc(100% - 200px);
            /* WebKit */
            height: -webkit-calc(100% - 200px);
            /* Opera */
            height: -o-calc(100% - 200px);
            /* Standard */
            height: calc(100% - 200px);
            overflow: auto;
            z-index: 100;
            margin: 10px 50px;
        }

        #view {
            position: absolute;
            overflow: auto;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }

        #btnZoomIn {
            position: absolute;
            left: 10px;
            top: 10px;
            width: 30px;
            height: 30px;
            background-image: url("textures/zoom-in.png");
            background-size: cover;
        }

        #btnZoomOut {
            position: absolute;
            left: 43px;
            top: 10px;
            width: 30px;
            height: 30px;
            background-image: url("textures/zoom-out.png");
            background-size: cover;
        }

        #btnRotateLeft {
            position: absolute;
            left: 10px;
            top: 43px;
            width: 30px;
            height: 30px;
            background-image: url("textures/update-arrow-left.png");
            background-size: cover;
        }

        #btnRotateRight {
            position: absolute;
            left: 43px;
            top: 43px;
            width: 30px;
            height: 30px;
            background-image: url("textures/update-arrow-right.png");
            background-size: cover;
        }

        .panel-partners {
            padding: 10px 5px 10px 15%;
        }

        .panel-partners img {
            height: 30px;
            margin-left: 50px;
        }

        #panel-popup {
            width: 100%;
            height: 2000px;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: hidden;
            position: fixed;
            top: 0px;
            left: 0px;
        }

        #panel-popup .panel-popup-content {
            margin: 150px auto 0px auto;
            width: 10%;
            padding: 30px;
            background-color: #c5c5c5;
            border-radius: 5px;
            box-shadow: 0px 0px 20px #000;
            color: #272727;
            text-align: center;
        }

    </style>
</head>

<body>

<div class="container">
    <div class="panel-contacts">
        <i class="fa fa-envelope" style="margin-right: 5px;margin-left: 5px;"></i>Email: <a
            href="mailto:info@otadoya.ru">info@fun-terra.ru</a>
        <i class="fa fa-phone" style="margin-right: 5px;margin-left: 5px;"></i>Телефон: <a href="tel:88007778209">8
            (800) 777-82-09</a> (звонок по России бесплатный)
    </div>
    <div class="panel-logo">
        <img src="textures/logo.png"/>
    </div>
    <div class="panel-info">
        <span class="info-desc"><?= $description ?></span>
        <span class="info-name"><?= $name ?></span>
        <a class="info-link" href="<?= $link ?>">Узнать стоимость</a>
    </div>
    <div class="panel-view">
        <div id="panel-popup" style="visibility: hidden;">
            <div class="panel-popup-content">
                <i class="fa fa-cog fa-spin fa-3x fa-fw"></i><br>
                <span>Пожалуйста, подождите...</span>
            </div>
        </div>
        <div id="view-container">
            <span id="info"></span>
            <div id="view"></div>
            <span type="button" id="btnZoomIn"></span>
            <span type="button" id="btnZoomOut"></span>
            <span type="button" id="btnRotateLeft"></span>
            <span type="button" id="btnRotateRight"></span>
        </div>
    </div>
    <div class="panel-partners">
        <img src="textures/otadoya.jpg"/>
        <img src="textures/jumanji.png"/>
        <img src="textures/parcobello.png"/>
        <img src="textures/termoles.jpg"/>
        <img src="textures/logo.png"/>
    </div>
</div>

<!--<div id="panel">-->
<!--    <img src="textures/logo.png" id="logo"/>-->
<!--    <h1>--><? //= $name ?><!--</h1>-->
<!--</div>-->

<!--<div id="view-container">-->
<!--    <span id="info"></span>-->
<!--    <div id="view"></div>-->
<!--    <input type="button" id="btnZoomIn">-->
<!--    <input type="button" id="btnZoomOut">-->
<!--    <input type="button" id="btnRotateLeft">-->
<!--    <input type="button" id="btnRotateRight">-->
<!--</div>-->

<script>

    if (!Detector.webgl) Detector.addGetWebGLMessage();

    var container;
    var camera, scene, renderer, controls;

    var infoBox = document.getElementById("info");

    var start = new Date();
    var zoomIn, zoomOut, rotateLeft, rotateRight;

    var textureLoaded = false;
    var objectAdded = false;

    loadObject();
    animate();

    document.getElementById('btnZoomIn').onmousedown = function () {
        zoomIn = true;
        zoomOut = false;
    };
    document.getElementById('btnZoomIn').onmouseup = function () {
        zoomIn = false;
    };
    document.getElementById('btnZoomIn').onmouseout = function () {
        zoomIn = false;
    };
    document.getElementById('btnZoomOut').onmousedown = function () {
        zoomOut = true;
        zoomIn = false;
    };
    document.getElementById('btnZoomOut').onmouseup = function () {
        zoomOut = false;
    };
    document.getElementById('btnZoomOut').onmouseout = function () {
        zoomOut = false;
    };
    document.getElementById('btnRotateLeft').onmousedown = function () {
        rotateLeft = true;
        rotateRight = false;
    };
    document.getElementById('btnRotateLeft').onmouseup = function () {
        rotateLeft = false;
    };
    document.getElementById('btnRotateLeft').onmouseout = function () {
        rotateLeft = false;
    };
    document.getElementById('btnRotateRight').onmousedown = function () {
        rotateRight = true;
        rotateLeft = false;
    };
    document.getElementById('btnRotateRight').onmouseup = function () {
        rotateRight = false;
    };
    document.getElementById('btnRotateRight').onmouseout = function () {
        rotateRight = false;
    };

    function loadObject() {
        document.getElementById("panel-popup").style.visibility = "visible";

        var onProgress = function (xhr) {
            if (xhr.lengthComputable) {
                var percentComplete = xhr.loaded / xhr.total * 100;
                //infoBox.innerText = Math.round(percentComplete, 2) + '% скачано';
                console.log(Math.round(percentComplete, 2) + '% downloaded');
            }
        };

        var onError = function (xhr) {
        };

        THREE.Loader.Handlers.add(/\.dds$/i, new THREE.DDSLoader());

        var filePath = 'uploads/<?= $id ?>/<?= $obj_filename ?>';
        console.log(filePath);
        JSZipUtils.getBinaryContent('uploads/<?= $id ?>/<?= $obj_filename ?>', function (err, data) {
            try {
                JSZip.loadAsync(data)
                    .then(function (zip) {
                        //infoBox.innerText = 'Downloaded ' + (new Date() - start);
                        return zip.file('<?= $obj_filename ?>').async('string');
                    })
                    .then(function success(text) {
                        //infoBox.innerText = 'Unzipped ' + (new Date() - start);
                        parse(text);
                        text = null;
                    }, function error(e) {
                        //infoBox.innerText = e;
                    });
            } catch (e) {
                //infoBox.innerText = e;
            }
        });
    }

    function parse(content) {
        var onProgress = function (xhr) {
            if (xhr.lengthComputable) {
                var percentComplete = xhr.loaded / xhr.total * 100;
                //infoBox.innerText = Math.round(percentComplete, 2) + '% скачано';
                console.log(Math.round(percentComplete, 2) + '% downloaded');
            }
        };

        var onError = function (xhr) {
        };

        var mtlLoader = new THREE.MTLLoader();
        mtlLoader.setPath('uploads/<?= $id ?>/');
        mtlLoader.load('<?= $mtl_filename ?>', function (materials) {

            //infoBox.innerText = 'Materials loaded ' + (new Date() - start);
            materials.preload();

            var objLoader = new THREE.OBJLoader();
            objLoader.setMaterials(materials);
            var object = objLoader.parse(content);
            //infoBox.innerText = 'Object parsed ' + (new Date() - start);
            object.traverse(function (node) {
                if (node instanceof THREE.Mesh) {
                    node.castShadow = true;
                    node.receiveShadow = true;
                }
            });

            init(object);
            object = null;
            //infoBox.innerText = 'Scene initialized ' + (new Date() - start);
            //infoBox.style.visibility = 'hidden';
        });
    }

    function init(object) {

        container = document.getElementById('view');

        // scene

        scene = new THREE.Scene();

        var bbox = new THREE.Box3().setFromObject(object);
        var bsphere = bbox.getBoundingSphere();

        var factor = 10 / bsphere.radius;
        object.scale.set(factor, factor, factor);

        bbox = new THREE.Box3().setFromObject(object);
        bsphere = bbox.getBoundingSphere();

        var fovG = 45;
        var oL;
        var FOV = fovG * (Math.PI / 180);
        var objectLocation = oL = bsphere.center;
        var objectRadius = bsphere.radius;
        var cL = {x: 60000, y: 30000, z: 100000};

        var currentDistToObject = Math.sqrt(Math.pow(oL.x - cL.x, 2) + Math.pow(oL.y - cL.y, 2) + Math.pow(oL.z - cL.z, 2));
        var requiredDistToObject = objectRadius * 0.8 / Math.sin(FOV / 2);
        var coeff = requiredDistToObject / currentDistToObject;

        cL.x *= coeff;
        cL.y *= coeff;
        cL.z *= coeff;

        var nearPlane = (requiredDistToObject - objectRadius) * 0.1;
        var farPlane = requiredDistToObject + objectRadius * 4;

        //object.position = objectLocation;
        object.position.x = -bsphere.center.x;
        object.position.y = -bsphere.center.y * 0.75;
        object.position.z = -bsphere.center.z;

        <?php
        if ($addGround == 1) {
        ?>
        // ground

        var loader = new THREE.TextureLoader();

        var groundTexture = loader.load('textures/grasslight-big.jpg', function () {
            textureLoaded = true;
        });
        groundTexture.wrapS = groundTexture.wrapT = THREE.RepeatWrapping;
        groundTexture.repeat.set(4, 4);
        groundTexture.anisotropy = 16;

        loader = null;

        var groundMaterial = new THREE.MeshPhongMaterial({color: 0xe4e4e4, map: groundTexture});

        var mesh = new THREE.Mesh(new THREE.CircleGeometry(bsphere.radius * 1.5, 64), groundMaterial);
        mesh.position.y = -bsphere.center.y * 0.75;
        mesh.rotation.x = -Math.PI / 2;
        mesh.receiveShadow = true;
        scene.add(mesh);

        <?php
        }
        ?>

        scene.add(object);

        // camera

        camera = new THREE.PerspectiveCamera(fovG, container.clientWidth / container.clientHeight, nearPlane, farPlane);
        camera.position.x = cL.x;
        camera.position.y = cL.y;
        camera.position.z = cL.z;
        scene.add(camera);

        // lights

        var light;
        scene.add(new THREE.AmbientLight(0x666666));

        light = new THREE.DirectionalLight(0xdfebff, 2);

        light.position.set(objectRadius * 0.2, objectRadius * 1.2, objectRadius * 0.2);

        light.castShadow = true;

        light.shadow.mapSize.width = 4096;
        light.shadow.mapSize.height = 4096;
        light.shadow.bias = 0;

        var d = objectRadius;

        light.shadow.camera.left = -d;
        light.shadow.camera.right = d;
        light.shadow.camera.top = d;
        light.shadow.camera.bottom = -d;
        light.shadow.type = THREE.PCFSoftShadowMap;
        light.shadowMapSoft = true;

        light.shadow.camera.far = objectRadius * 2;
        light.shadow.camera.near = objectRadius * 0.2;

        scene.add(light);

        // renderer

        renderer = new THREE.WebGLRenderer({antialias: true});
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setClearColor(0xF5F5F5);

        container.appendChild(renderer.domElement);

        renderer.gammaInput = true;
        renderer.gammaOutput = true;

        <?php
        if ($enable_shadows == 1) {
        ?>
        renderer.shadowMap.enabled = true;
        renderer.shadowMap.autoUpdate = false;
        renderer.shadowMap.needsUpdate = true;
        renderer.shadowMapSoft = true;
        renderer.shadowMap.Type = THREE.PCFShadowMap;
        <?php
        }
        ?>

        // controls
        controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.maxPolarAngle = Math.PI * 0.5;
        controls.minDistance = objectRadius * 1.2;
        controls.maxDistance = requiredDistToObject * 1.6;
        controls.enablePan = false;

        objectAdded = true;

        window.addEventListener('resize', onWindowResize, false);
    }

    function onWindowResize() {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();

        renderer.setSize(container.clientWidth, container.clientHeight);
    }

    function animate() {
        requestAnimationFrame(animate);
        if (!textureLoaded || !objectAdded)
            return;
        else
            document.getElementById("panel-popup").style.visibility = "hidden";
        if (zoomIn)
            controls.zoomIn(0.99);
        if (zoomOut)
            controls.zoomOut(0.99);
        if (rotateLeft)
            controls.rotateLeft(-0.017);
        if (rotateRight)
            controls.rotateLeft(0.017);
        render();
    }

    function render() {
        if (renderer != null)
            renderer.render(scene, camera);
    }

</script>
</body>
</html>