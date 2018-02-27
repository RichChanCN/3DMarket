/**
 * Created by Richer on 2018/1/12.
 */

var scene_objloader, scene_mtlloader;
var scene_items = [];
var model_index = 147;
var cur_model_id;

var camera, scene, renderer, controls;
var objects = [];
var raycaster;
var blocker = document.getElementById( 'blocker' );
var instructions = document.getElementById( 'instructions' );

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

var spotLight;

function init() {
    initSceneLoader();
    camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 1000 );
    scene = new THREE.Scene();
    scene.background = new THREE.Color( 0xffffff );
    scene.fog = new THREE.Fog( 0xffffff, 0, 750 );

    var light = new THREE.HemisphereLight( 0xccccdd, 0x555566, 0.75 );

    spotLight = new THREE.SpotLight( 0xffffff );
    spotLight.position.set( 0, 30, 0 );
    spotLight.angle = 0.2;
    spotLight.penumbra = 0.5

    light.position.set( 0.5, 1, 0.75 );

    scene.add( light );
    scene.add(spotLight);

    controls = new THREE.PointerLockControls( camera );
    scene.add( controls.getObject() );

    var onKeyDown = function ( event ) {
        switch ( event.keyCode ) {
            case 38: // up
            case 87: // w
                moveForward = true;
                break;
            case 37: // left
            case 65: // a
                moveLeft = true; break;
            case 40: // down
            case 83: // s
                moveBackward = true;
                break;
            case 39: // right
            case 68: // d
                moveRight = true;
                break;
            case 32: // space
                document.exitPointerLock = document.exitPointerLock || document.mozExitPointerLock ||
                    document.webkitExitPointerLock;
                document.exitPointerLock();
                pickitem();
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
    raycaster = new THREE.Raycaster( new THREE.Vector3(), new THREE.Vector3( 0, - 1, 0 ), 0, 10 );
    // floor
    var floorGeometry = new THREE.PlaneGeometry( 2000, 2000, 100, 100 );
    floorGeometry.rotateX( - Math.PI / 2 );
    for ( var i = 0, l = floorGeometry.vertices.length; i < l; i ++ ) {
        var vertex = floorGeometry.vertices[ i ];
        vertex.x += Math.random() * 20 - 10;
        vertex.y += Math.random() * 2;
        vertex.z += Math.random() * 20 - 10;
    }
    for ( var i = 0, l = floorGeometry.faces.length; i < l; i ++ ) {
        var face = floorGeometry.faces[ i ];
        face.vertexColors[ 0 ] = new THREE.Color().setHSL( 0, 0, 0.7 );
        face.vertexColors[ 1 ] = new THREE.Color().setHSL( 0, 0, 0.7 );
        face.vertexColors[ 2 ] = new THREE.Color().setHSL( 0, 0, 0.7 );
    }
    var floorMaterial = new THREE.MeshBasicMaterial( { vertexColors: THREE.VertexColors } );
    var floor = new THREE.Mesh( floorGeometry, floorMaterial );
    scene.add( floor );
    // objects

    loadNextModel();

    renderer = new THREE.WebGLRenderer();
    renderer.setPixelRatio( window.devicePixelRatio );
    renderer.setSize( window.innerWidth, window.innerHeight );
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
        raycaster.ray.origin.copy( controls.getObject().position );
        raycaster.ray.origin.y -= 10;
        var intersections = raycaster.intersectObjects( scene_items );
        var onObject = intersections.length > 0;
        var time = performance.now();
        var delta = ( time - prevTime ) / 1000;
        velocity.x -= velocity.x * 10.0 * delta;
        velocity.z -= velocity.z * 10.0 * delta;
        velocity.y -= 9.8 * 100.0 * delta; // 100.0 = mass
        direction.z = Number( moveForward ) - Number( moveBackward );
        direction.x = Number( moveLeft ) - Number( moveRight );
        direction.normalize(); // this ensures consistent movements in all directions
        if ( moveForward || moveBackward ) velocity.z -= direction.z * 100.0 * delta;
        if ( moveLeft || moveRight ) velocity.x -= direction.x * 100.0 * delta;
        if ( onObject === true ) {
            velocity.y = Math.max( 0, velocity.y );
            canJump = true;
        }
        controls.getObject().translateX( velocity.x * delta );
        controls.getObject().translateY( velocity.y * delta );
        controls.getObject().translateZ( velocity.z * delta );
        if ( controls.getObject().position.y < 10 ) {
            velocity.y = 0;
            controls.getObject().position.y = 10;
            canJump = true;
        }
        prevTime = time;
    }

    raycaster_catch.setFromCamera(new THREE.Vector2(0,0),camera);
    var intersects = raycaster_catch.intersectObjects( scene.children,true );

    if ( intersects.length > 0 ) {
        if ( INTERSECTED != intersects[ 0 ].object) {
            cur_model_id = intersects[ 0 ].object.parent.name;
            if(cur_model_id != "" && cur_model_id != null) {
                spotLight.angle = 0.2;
                spotLight.position.x = scene_item_info[cur_model_id].x;
                spotLight.position.z = scene_item_info[cur_model_id].z;
                spotLight.target = intersects[ 0 ].object.parent;
            }
            else {
                spotLight.angle = 0;
            }
        }
        else{
            cur_model_id = null;
        }


    } else {
        cur_model_id = null;
        spotLight.angle = 0;
        INTERSECTED = null;
    }

    renderer.render( scene, camera );
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
            scene.add(obj);
            scene_items.push(obj);
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
}

function pickitem() {
    if(scene_item_list[cur_model_id-148] == null)
        return;
    console.log(cur_model_id-148);
    changeItemInfo(scene_item_list[cur_model_id-148],"itemCanvas");
    document.getElementById("show_fast_view").click();
}

function fireKeyEvent(el, evtType, keyCode){
    var doc = el.ownerDocument,
        win = doc.defaultView || doc.parentWindow,
        evtObj;
    if(doc.createEvent){
        if(win.KeyEvent) {
            evtObj = doc.createEvent('KeyEvents');
            evtObj.initKeyEvent( evtType, true, true, win, false, false, false, false, keyCode, 0 );
        }
        else {
            evtObj = doc.createEvent('UIEvents');
            Object.defineProperty(evtObj, 'keyCode', {
                get : function() { return this.keyCodeVal; }
            });
            Object.defineProperty(evtObj, 'which', {
                get : function() { return this.keyCodeVal; }
            });
            evtObj.initUIEvent( evtType, true, true, win, 1 );
            evtObj.keyCodeVal = keyCode;
            if (evtObj.keyCode !== keyCode) {
                console.log("keyCode " + evtObj.keyCode + " 和 (" + evtObj.which + ") 不匹配");
            }
        }
        el.dispatchEvent(evtObj);
    }
    else if(doc.createEventObject){
        evtObj = doc.createEventObject();
        evtObj.keyCode = keyCode;
        el.fireEvent('on' + evtType, evtObj);
    }
}