/**
 * Created by Richer on 2018/1/12.大的3d场景用的脚本
 */

var scene_objloader, scene_mtlloader, character_loader;
var scene_character, scene_arrow;
var mixer;
var raycaster_node;
var model_index = 147;
var cur_model_id;

var camera, scene, renderer, controls;
var objects = [];
var blocker = document.getElementById( 'blocker' );
var instructions = document.getElementById( 'instructions' );

var clock;
var animation_playing = 0;
var last_animation;
var loaded = false;
var sitted = false;
var lookforward = 1;
var raycaster_catch = new THREE.Raycaster();
var INTERSECTED;

// http://www.html5rocks.com/en/tutorials/pointerlock/intro/
var havePointerLock = 'pointerLockElement' in document || 'mozPointerLockElement' in document || 'webkitPointerLockElement' in document;
if ( havePointerLock ) {
    var element = document.body;
    var pointerlockchange = function ( event ) {
        if ( document.pointerLockElement === element || document.mozPointerLockElement === element || document.webkitPointerLockElement === element ) {
            controlsEnabled = true;
            controls.enabled = true;
            blocker.style.display = 'none';
        } else {
            controls.enabled = false;
            blocker.style.display = 'block';
            instructions.style.display = '';
        }
    };
    var pointerlockerror = function ( event ) {
        instructions.style.display = '';
    };
    // Hook pointer lock state change events
    document.addEventListener( 'pointerlockchange', pointerlockchange, false );
    document.addEventListener( 'mozpointerlockchange', pointerlockchange, false );
    document.addEventListener( 'webkitpointerlockchange', pointerlockchange, false );
    document.addEventListener( 'pointerlockerror', pointerlockerror, false );
    document.addEventListener( 'mozpointerlockerror', pointerlockerror, false );
    document.addEventListener( 'webkitpointerlockerror', pointerlockerror, false );
    instructions.addEventListener( 'click', function ( event ) {
        instructions.style.display = 'none';
        // Ask the browser to lock the pointer
        element.requestPointerLock = element.requestPointerLock || element.mozRequestPointerLock || element.webkitRequestPointerLock;
        element.requestPointerLock();
    }, false );
} else {
    instructions.innerHTML = 'Your browser doesn\'t seem to support Pointer Lock API';
}

init();
animate();
var controlsEnabled = false;
var all_model_loaded = false;
var moveForward = false;
var moveBackward = false;
var moveLeft = false;
var moveRight = false;
var canJump = false;
var prevTime = performance.now();
var velocity = new THREE.Vector3();
var direction = new THREE.Vector3();


function init() {
    initSceneLoader();
    camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 1000 );
    scene = new THREE.Scene();
    scene.background = new THREE.Color( 0xffffff );
    scene.fog = new THREE.Fog( 0xffffff, 0, 750 );

    var light = new THREE.HemisphereLight( 0xddddff, 0x555566, 1 );

    light.position.set( 0.5, 1, 0.75 );

    clock = new THREE.Clock();

    raycaster_node = new THREE.Object3D();
    scene.add(raycaster_node);

    scene.add( light );

    controls = new THREE.PointerLockControls( camera );
    scene.add( controls.getObject() );

    var onKeyDown = function ( event ) {
        switch ( event.keyCode ) {
            case 38: // up
            case 87: // w
                moveForward = true;
                if(1 != lookforward&&!(moveBackward||moveLeft||moveRight)){
                    scene_character.rotateY((lookforward-1)*Math.PI/2);
                    lookforward = 1;
                    scene_character.updateMatrix();
                }
                break;
            case 37: // left
            case 65: // a
                moveLeft = true;
                if(4 != lookforward&&!(moveBackward||moveForward||moveRight)){
                    scene_character.rotateY((lookforward-4)*Math.PI/2);
                    lookforward = 4;
                    scene_character.updateMatrix();
                }
                break;
            case 40: // down
            case 83: // s
                moveBackward = true;
                if(3 != lookforward&&!(moveLeft||moveRight||moveForward)){
                    scene_character.rotateY((lookforward-3)*Math.PI/2);
                    lookforward = 3;
                    scene_character.updateMatrix();
                }
                break;
            case 39: // right
            case 68: // d
                moveRight = true;
                if(2 != lookforward&&!(moveBackward||moveForward||moveLeft)){
                    scene_character.rotateY((lookforward-2)*Math.PI/2);
                    lookforward = 2;
                    scene_character.updateMatrix();
                }
                break;
            case 32: // space
                document.exitPointerLock = document.exitPointerLock || document.mozExitPointerLock ||
                    document.webkitExitPointerLock;
                document.exitPointerLock();
                pickitem();
                break;
            case 81://q
                sitted = !sitted;
                if(sitted)
                    controls.enabled = false;
                else
                    controls.enabled = true;
                break;
        }
    };
    var onKeyUp = function ( event ) {
        switch( event.keyCode ) {
            case 38: // up
            case 87: // w
                moveForward = false;
                break;
            case 37: // left
            case 65: // a
                moveLeft = false;
                break;
            case 40: // down
            case 83: // s
                moveBackward = false;
                break;
            case 39: // right
            case 68: // d
                moveRight = false;
                break;
        }
    };
    document.addEventListener( 'keydown', onKeyDown, false );
    document.addEventListener( 'keyup', onKeyUp, false );
    // floor
    var floorGeometry = new THREE.PlaneBufferGeometry( 2000, 2000, 100, 100 );
    floorGeometry.rotateX( - Math.PI / 2 );
    // for ( var i = 0, l = floorGeometry.vertices.length; i < l; i ++ ) {
    //     var vertex = floorGeometry.vertices[ i ];
    //     vertex.x += Math.random() * 20 - 10;
    //     vertex.y += Math.random() * 2;
    //     vertex.z += Math.random() * 20 - 10;
    // }
    // for ( var i = 0, l = floorGeometry.faces.length; i < l; i ++ ) {
    //     var face = floorGeometry.faces[ i ];
    //     face.vertexColors[ 0 ] = new THREE.Color().setHSL( 0, 0, 0.7 );
    //     face.vertexColors[ 1 ] = new THREE.Color().setHSL( 0, 0, 0.7 );
    //     face.vertexColors[ 2 ] = new THREE.Color().setHSL( 0, 0, 0.7 );
    // }
    var vertex = new THREE.Vector3();
    var position = floorGeometry.attributes.position;
    for ( var i = 0; i < position.count; i ++ ) {
        vertex.fromBufferAttribute( position, i );
        vertex.x += Math.random() * 20 - 10;
        vertex.y += 5;
        vertex.z += Math.random() * 20 - 10;
        position.setXYZ( i, vertex.x, vertex.y, vertex.z );
    }
    floorGeometry = floorGeometry.toNonIndexed(); // ensure each face has unique vertices
    var count = floorGeometry.attributes.position.count;
    var color = new THREE.Color();
    var colors = [];
    for ( var i = 0; i < count; i ++ ) {
        color.setHSL( Math.random() * 0.3 + 0.5, 0.75, Math.random() * 0.25 + 0.75 );
        colors.push( color.r, color.g, color.b );
    }
    floorGeometry.addAttribute( 'color', new THREE.Float32BufferAttribute( colors, 3 ) );


    var floorMaterial = new THREE.MeshBasicMaterial( { vertexColors: THREE.VertexColors } );
    var floor = new THREE.Mesh( floorGeometry, floorMaterial );
    floor.receiveShadow = true;
    scene.add( floor );
    // objects

    //loadNextModel();
    loadCharacter('assets/models/json/character.js');
    loadArrowModel();
    renderer = new THREE.WebGLRenderer({antialia:true});
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( window.innerWidth, window.innerHeight );
    renderer.shadowMapEnabled = true;
    document.body.appendChild( renderer.domElement );
    //
    window.addEventListener( 'resize', onWindowResize, false );
}
function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize( window.innerWidth, window.innerHeight );
}


function animate() {
    requestAnimationFrame( animate );
    if ( controlsEnabled === true ) {
        var time = performance.now();
        var delta = ( time - prevTime ) / 1000;
        velocity.x -= velocity.x * 10.0 * delta;
        velocity.z -= velocity.z * 10.0 * delta;
        velocity.y -= 9.8 * 100.0 * delta; // 100.0 = mass
        direction.z = Number( moveForward ) - Number( moveBackward );
        direction.x = Number( moveLeft ) - Number( moveRight );
        direction.normalize(); // this ensures consistent movements in all directions
        if ( moveForward || moveBackward ){
            velocity.z -= direction.z * 150.0 * delta;
            animation_playing = 2;
        }
        else if ( moveLeft || moveRight ){
            velocity.x -= direction.x * 150.0 * delta;
            animation_playing = 2;
        }
        else if(sitted){
            animation_playing = 1;
        }
        else{
            animation_playing = 0;
        }



        controls.getObject().translateX( velocity.x * delta );
        controls.getObject().translateY( velocity.y * delta );
        controls.getObject().translateZ( velocity.z * delta );

        if ( controls.getObject().position.y < 17 ) {
            velocity.y = 0;
            controls.getObject().position.y = 17;
        }
        prevTime = time;
    }

    raycaster_catch.setFromCamera(new THREE.Vector2(0,0),camera);
    var intersects = raycaster_catch.intersectObjects( raycaster_node.children,true );

    if ( intersects.length > 0 ) {
        if ( INTERSECTED != intersects[ 0 ].object) {
            cur_model_id = intersects[ 0 ].object.parent.name;
            if(cur_model_id != "" && cur_model_id != null) {
                scene_arrow.position.set(scene_item_info[cur_model_id].x,20+Math.sin(performance.now()/100),scene_item_info[cur_model_id].z);
            }
            else {
                scene_arrow.position.set(10000,-20,10000);
            }
        }
        else{
            cur_model_id = null;
            scene_arrow.position.set(10000,-20,10000);
        }


    } else {
        cur_model_id = null;
        INTERSECTED = null;
        if(scene_arrow != undefined)
        scene_arrow.position.set(10000,-20,10000);
    }

    if(animation_playing != last_animation && loaded){
        mixer.uncacheClip(scene_character.geometry.animations[ last_animation ], scene_character);
        mixer.clipAction( scene_character.geometry.animations[ animation_playing ], scene_character )
            .setDuration( 1 )			// one second
            .startAt(1)
            .play();
        last_animation = animation_playing;
    }

    mixer.update( clock.getDelta() );

    renderer.render( scene, camera );
}
//加载人物
function loadCharacter(str) {
    mixer = new THREE.AnimationMixer( scene );
    character_loader.setTexturePath(my_path.JsonPath);
    character_loader.load(
        str,

        function (geometry, materials) {
            var material = materials[ 0 ];
            material.morphTargets = true;
            material.color.setHex( 0xffffff );
            scene_character = new THREE.Mesh( geometry, materials );
            scene_character.scale.set(0.7,0.7,0.7);
            scene_character.position.set(0,-12,-8);
            scene_character.rotateY(Math.PI);
            scene_character.matrixAutoUpdate = false;
            scene_character.castShadow = true;
            scene_character.updateMatrix();
            controls.getObject().add(scene_character);
            mixer.clipAction( geometry.animations[ animation_playing ], scene_character )
                .setDuration( 1 )			// one second
                .startAt(1)
                .play();
            loadNextModel();
            last_animation = animation_playing;
            loaded = true;
        },
        // onProgress callback
        function ( xhr ) {
            console.log( (xhr.loaded / xhr.total * 100) + '% loaded' );
        },
        function( err ) {
            console.log( 'An error happened' );
            console.log( err );
        }
    );
}

//加载箭头
function loadArrowModel() {

    scene_mtlloader.setPath(my_path.MaterialPath);
    scene_mtlloader.setTexturePath(my_path.TexturePath);

    scene_mtlloader.load("arrow.mtl",mtl);
    function mtl(materials) {
        materials.preload();
        scene_objloader.setMaterials(materials)
    }

    scene_objloader.setPath(my_path.ModelPath);

    scene_objloader.load(
        "arrow.obj",

        function (obj) {
            obj.traverse(function (child) {
                if (child instanceof THREE.Mesh){
                    child.material.side = THREE.DoubleSide;
                }
            });
            obj.position.x = 10000;
            obj.position.y = 20;
            obj.position.z = 10000;
            obj.scale.set(1,1,1);
            scene_arrow = obj;
            scene.add(obj);
        }
    );

}
//加载模型
function loadNextModel() {
    if(model_index-147<10)
        document.getElementById("progress").innerHTML ="加载模型中..."+(model_index-147)+"/10";
    else
        document.getElementById("progress").innerHTML ="点击屏幕开始游览";
    if(model_index > 157)
    {
        all_model_loaded = true;
        return;
    }
    model_index+=1;
    loadSceneModels(model_index+".obj",model_index+"_1.mtl",model_index);

}

function loadSceneModels(obj_name,mtl_group,index) {

    scene_mtlloader.setPath(my_path.MaterialPath);
    scene_mtlloader.setTexturePath(my_path.TexturePath);

    scene_mtlloader.load(mtl_group,mtl);
    function mtl(materials) {
        materials.preload();
        scene_objloader.setMaterials(materials)
    }

    scene_objloader.setPath(my_path.ModelPath);

    scene_objloader.load(
        obj_name,

        function (obj) {
            obj.traverse(function (child) {
                if (child instanceof THREE.Mesh){
                    child.material.side = THREE.DoubleSide;
                }
            });
            obj.position.x = scene_item_info[index].x;
            obj.position.y = 5;
            obj.position.z = scene_item_info[index].z;
            obj.name = index;
            obj.scale.set(scene_item_info[index].scale_rate,scene_item_info[index].scale_rate,scene_item_info[index].scale_rate);
            obj.rotateY(scene_item_info[index].yaw);
            obj.castShadow = true;
            raycaster_node.add(obj);
            loadNextModel();
        },
        null,
        loadNextModel
    );

}

function initSceneLoader() {
    //创建一个模型加载器对象和材质加载器对象
    if (scene_objloader == null) {
        scene_objloader = new THREE.OBJLoader();
    }

    if (scene_mtlloader == null){
        scene_mtlloader = new THREE.MTLLoader();
    }

    if (character_loader == null){
        character_loader = new THREE.JSONLoader();
    }
}

function pickitem() {
    if(scene_item_list[cur_model_id-148] == null)
        return;
    console.log(cur_model_id-148);
    changeItemInfo(scene_item_list[cur_model_id-148],"itemCanvas");
    document.getElementById("show_fast_view").click();
}