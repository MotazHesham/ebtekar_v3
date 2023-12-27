<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ebtekar Web-Xr</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <style>
        body {
            background-color: aqua;
            color: #fff;
            font-family: "Lato", sans-serif;
        }

        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidenav a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            color: #f1f1f1;
        }

        .sidenav .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }

        #place-button {
            position: absolute;
            bottom: 20px;
            left: calc(50% - 50px);
            width: 100px;
            height: 35px;
            display: none;
        }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body style="background: white">

    <div id="content"> 
        <div id="container" style="position: fixed;"></div> 
        <button type="button" id="place-button">PLACE</button>
    </div> 

    @if($product->object_3d)
        <script type="module">
            import * as THREE from '../../../public/threejs/build/three.module.js';
            import {
                ARButton
            } from '../../../public/threejs/jsm/webxr/ARButton.js'; 

            import {
                OrbitControls
            } from '../../../public/threejs/jsm/controls/OrbitControls.js';
            import {
                GLTFLoader
            } from '../../../public/threejs/jsm/loaders/GLTFLoader.js';
            import {
                RGBELoader
            } from '../../../public/threejs/jsm/loaders/RGBELoader.js';
            import {
                RoughnessMipmapper
            } from '../../../public/threejs/jsm/utils/RoughnessMipmapper.js';

            var container;
            var camera, scene, renderer;
            var controller;

            var reticle, pmremGenerator, current_object, controls, isAR, envmap;

            var hitTestSource = null;
            var hitTestSourceRequested = false;

            init();
            animate();

            $(".ar-object").click(function() {
                if (current_object != null) {
                    scene.remove(current_object);
                }

                loadModel($(this).attr("id"));
            });

            
            
            $(document).ready(function() { 
                loadModel();
            });

            $("#ARButton").click(function() {
                current_object.visible = false;
                isAR = true;
            }); 

            $("#place-button").click(function() {
                arPlace();
            });

            function arPlace() {
                if (reticle.visible) {
                    current_object.position.setFromMatrixPosition(reticle.matrix);
                    current_object.visible = true;
                }
            }

            function loadModel(model = null) {

                new RGBELoader()
                    .setDataType(THREE.UnsignedByteType)
                    .setPath('../../../public/threejs/textures/')
                    .load('photo_studio_01_1k.hdr', function(texture) {

                        envmap = pmremGenerator.fromEquirectangular(texture).texture;

                        scene.environment = envmap;
                        texture.dispose();
                        pmremGenerator.dispose();
                        render();

                        var loader = new GLTFLoader().setPath('../../../public/storage/{{$product->object_3d->id}}/');
                        loader.load('{{$product->object_3d->file_name}}', function(glb) {

                            current_object = glb.scene;
                            scene.add(current_object);

                            arPlace();

                            var box = new THREE.Box3();
                            box.setFromObject(current_object);
                            box.getCenter(controls.target);

                            controls.update();
                            render();
                        });
                    });
            }

            function init() {

                container = document.createElement('div');
                document.getElementById("container").appendChild(container);

                scene = new THREE.Scene();
                window.scene = scene;

                camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 0.001, 200);

                var directionalLight = new THREE.DirectionalLight(0xdddddd, 1);
                directionalLight.position.set(0, 0, 1).normalize();
                scene.add(directionalLight);

                var ambientLight = new THREE.AmbientLight(0x222222);
                scene.add(ambientLight);

                //

                renderer = new THREE.WebGLRenderer({
                    antialias: true,
                    alpha: true
                });
                renderer.setPixelRatio(window.devicePixelRatio);
                renderer.setSize(window.innerWidth, window.innerHeight);
                renderer.xr.enabled = true;
                container.appendChild(renderer.domElement);

                pmremGenerator = new THREE.PMREMGenerator(renderer);
                pmremGenerator.compileEquirectangularShader();

                controls = new OrbitControls(camera, renderer.domElement);
                controls.addEventListener('change', render);
                controls.minDistance = 2;
                controls.maxDistance = 10;
                controls.target.set(0, 0, -0.2);
                controls.enableDamping = true;
                controls.dampingFactor = 0.05;


                //AR SETUP

                let options = {
                    requiredFeatures: ['hit-test'],
                    optionalFeatures: ['dom-overlay'],
                }

                options.domOverlay = {
                    root: document.getElementById('content')
                };

                document.body.appendChild(ARButton.createButton(renderer, options));

                //document.body.appendChild( ARButton.createButton( renderer, { requiredFeatures: [ 'hit-test' ] } ) );

                //

                reticle = new THREE.Mesh(
                    new THREE.RingBufferGeometry(0.15, 0.2, 32).rotateX(-Math.PI / 2),
                    new THREE.MeshBasicMaterial()
                );
                reticle.matrixAutoUpdate = false;
                reticle.visible = false;
                scene.add(reticle);

                //

                window.addEventListener('resize', onWindowResize, false);

                renderer.domElement.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    touchDown = true;
                    touchX = e.touches[0].pageX;
                    touchY = e.touches[0].pageY;
                }, false);

                renderer.domElement.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    touchDown = false;
                }, false);

                renderer.domElement.addEventListener('touchmove', function(e) {
                    e.preventDefault();

                    if (!touchDown) {
                        return;
                    }

                    deltaX = e.touches[0].pageX - touchX;
                    deltaY = e.touches[0].pageY - touchY;
                    touchX = e.touches[0].pageX;
                    touchY = e.touches[0].pageY;

                    rotateObject();

                }, false);

            }

            var touchDown, touchX, touchY, deltaX, deltaY;

            function rotateObject() {
                if (current_object && reticle.visible) {
                    current_object.rotation.y += deltaX / 100;
                }
            }

            function onWindowResize() {

                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();

                renderer.setSize(window.innerWidth, window.innerHeight);

            }

            //

            function animate() {

                renderer.setAnimationLoop(render);
                requestAnimationFrame(animate);
                controls.update();

            }

            function render(timestamp, frame) {

                if (frame && isAR) {

                    var referenceSpace = renderer.xr.getReferenceSpace();
                    var session = renderer.xr.getSession();

                    if (hitTestSourceRequested === false) {

                        session.requestReferenceSpace('viewer').then(function(referenceSpace) {

                            session.requestHitTestSource({
                                space: referenceSpace
                            }).then(function(source) {

                                hitTestSource = source;

                            });

                        });

                        session.addEventListener('end', function() {

                            hitTestSourceRequested = false;
                            hitTestSource = null;

                            isAR = false;

                            reticle.visible = false;

                            var box = new THREE.Box3();
                            box.setFromObject(current_object);
                            box.center(controls.target);

                            document.getElementById("place-button").style.display = "none";

                        });

                        hitTestSourceRequested = true;

                    }

                    if (hitTestSource) {

                        var hitTestResults = frame.getHitTestResults(hitTestSource);

                        if (hitTestResults.length) {

                            var hit = hitTestResults[0];

                            document.getElementById("place-button").style.display = "block";

                            reticle.visible = true;
                            reticle.matrix.fromArray(hit.getPose(referenceSpace).transform.matrix);

                        } else {

                            reticle.visible = false;

                            document.getElementById("place-button").style.display = "none";

                        }

                    }

                }

                renderer.render(scene, camera);

            }
        </script>
    @endif
</body>

</html>
