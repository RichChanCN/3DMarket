var item_scene,item_renderer,objloader,mtlloader,
    item_camera,item_mesh,item_stat,item_drawID,item_canvas,
    touch_flag;

var item_light1,item_light2;

function init(obj_url,mtl_url,image_url) {
    //初始化状态监测
    statInit();
    //创建一个渲染器对象
    item_renderer = new THREE.WebGLRenderer({
        canvas: document.getElementById("mainCanvas")
    });

    //初始化
    initControl();
    initScene();
    initCamera();
    initLight();
    initLoader();
    //加载模型
    loadModels(obj_url,mtl_url,image_url)


}
//创建一个场景对象
function initScene() {
    item_scene = new THREE.Scene();
    item_scene.background = new THREE.Color( 0x999999 );
}

//创建一个摄像机对象，并且设置相关属性
function initCamera() {
    item_camera = new THREE.PerspectiveCamera(45,4/3,1,1000);
    item_camera.position.set(0,50,50);
    item_camera.lookAt(new THREE.Vector3(0,0,0));

    //把摄像机添加到场景中
    item_scene.add(item_camera);
}

//上帝说要有光
function initLight() {
    item_light1 = new THREE.PointLight(0xffffff, 2, 100);
    item_light1.position.set(0,30,0);
    item_scene.add(item_light1);

    item_light2 = new THREE.PointLight(0xffffff, 2, 100);
    item_light2.position.set(0,0,20);
    item_scene.add(item_light2);
}

function initLoader() {
    //创建一个模型加载器对象和材质加载器对象
    objloader = new THREE.OBJLoader();
    mtlloader = new THREE.MTLLoader();
}

//加载模型
function loadModels(obj_url,mtl_url,image_url) {

    if (image_url != null){
        var texture = THREE.ImageUtils.loadTexture(image_url,{});

        objloader.load(
            obj_url,

            function (obj) {
                obj.traverse(function (child) {
                    if (child instanceof THREE.Mesh){
                        child.material.side = THREE.DoubleSide;
                        child.material.map = texture;
                    }
                });
                item_mesh = obj;
                item_scene.add(obj);
                item_renderer.render(item_scene,item_camera);
                draw();
            },

            function ( xhr ) {
                document.getElementById("progressBar").innerHTML=( xhr.loaded / xhr.total * 100 ).toFixed(1) + '% loaded';
                //console.log( ( xhr.loaded / xhr.total * 100 ) + '% loaded' );
            }
        );
    }
    else if (mtl_url != null) {
        mtlloader.load(mtl_url,mtl);

        function mtl(material) {
            objloader.setMaterials(material)
        }

        objloader.load(
            obj_url,

            function (obj) {
                obj.traverse(function (child) {
                    if (child instanceof THREE.Mesh){
                        child.material.side = THREE.DoubleSide;
                    }
                });
                item_mesh = obj;
                item_scene.add(obj);
                item_renderer.render(item_scene,item_camera);
                draw();
            },

            function ( xhr ) {
                document.getElementById("progressBar").innerHTML=( xhr.loaded / xhr.total * 100 ).toFixed(1) + '% loaded';
                //console.log( ( xhr.loaded / xhr.total * 100 ) + '% loaded' );
            }
        );
    }

}

//绘制函数开始
function draw() {
    item_stat.begin();
    if (touch_flag != 1)
        item_mesh.rotation.y = (item_mesh.rotation.y+0.01)%(Math.PI*2);

    item_renderer.render(item_scene,item_camera);

    item_drawID = requestAnimationFrame(draw);

    item_stat.end();
}
//物品旋转更新函数
function updateMeshRotation() {
    item_mesh.rotateOnWorldAxis(new THREE.Vector3( 1, 0, 0 ),xRot/40);
    item_mesh.rotateOnWorldAxis(new THREE.Vector3( 0, 1, 0 ),yRot/40);
    item_mesh.rotateOnWorldAxis(new THREE.Vector3( 0, 0, 1 ),zRot/8);
    yRot = xRot = zRot = 0;

}
//物品移动更新函数
function updateMeshTransform(ds) {
    item_mesh.translateX(ds);
}
//摄像机更新函数
function updateCamera(ds) {
    item_camera.position.y += ds;
    item_camera.position.z += ds;
}
//动画停止函数
function stop() {
    if(item_drawID != null){
        cancelAnimationFrame(item_drawID)
        item_drawID = null;
    }
}

//帧数检测函数
function statInit() {
    item_stat = new Stats();
    item_stat.domElement.style.position = 'absolute';
    item_stat.domElement.style.left = '900px';
    item_stat.domElement.style.top = '0px';

    document.body.appendChild(item_stat.domElement);
}

//控制摄像头的函数
function moveCamera(){
    var x;
    if(window.event) // IE8 以及更早版本
    {
        x=event.keyCode;
    }
    else if(event.which) // IE9/Firefox/Chrome/Opera/Safari
    {
        x=event.which;
    }
    var keychar=String.fromCharCode(x);

    if(keychar == 'W'){
        item_camera.position.y+=0.3;
    }
    else if(keychar == 'S'){
        item_camera.position.y-=0.3;
    }
    else if(keychar == 'D'){
        item_camera.position.x+=0.3;
    }
    else if(keychar == 'A'){
        item_camera.position.x-=0.3;
    }
}
//---------------------------------控制部分------------------------------------
var pi180 = 180/Math.PI,
    transl = -1, rTouch, fiTouch, idTouch0,
    xRot = 0,yRot = 0,zRot = 0,
    xOffs = 0, yOffs = 0, drag = 0;

function startTouch(evt) {
    var evList = evt.touches;
    if(evList.length == 1){
        xOffs = evList[0].pageX;  yOffs = evList[0].pageY;
        drag = 1;}
    else if(evList.length == 2){
        idTouch0 = evList[0].identifier;
        var dx = evList[1].pageX - evList[0].pageX;
        var dy = evList[1].pageY - evList[0].pageY;
        rTouch = Math.sqrt(dx*dx + dy*dy);
        fiTouch = Math.atan2(dy, dx);
        drag = 2;}
    evt.preventDefault();

}
function continueTouch(evt) {
    if(drag == 1){
        var x = evt.touches[0].pageX,  y = evt.touches[0].pageY;
        yRot = x - xOffs;  xRot = y - yOffs;
        xOffs = x;  yOffs = y;
        updateMeshRotation();}
    else if(drag == 2){
        var dx = evt.touches[1].pageX - evt.touches[0].pageX;
        var dy = evt.touches[1].pageY - evt.touches[0].pageY;
        var r = Math.sqrt(dx*dx + dy*dy);
        var fi;
        if( idTouch0 == evt.touches[0].identifier ) fi = Math.atan2(dy, dx);
        else fi = Math.atan2(-dy, -dx);
        transl *= rTouch / r;
        zRot = pi180*(fiTouch - fi);
        rTouch = r;  fiTouch = fi;
        updateMeshRotation();
    }
}
function stopTouch() {
    drag = 0;
}
function mymousedown( ev ){
    touch_flag = 1;
    drag  = 1;
    xOffs = ev.clientX;  yOffs = ev.clientY;
}
function mymouseup( ev ){
    drag  = 0;
}
function mymousemove( ev ){
    if ( drag == 0 ) return;
    if ( ev.shiftKey ) {
        transl *= 1 + (ev.clientY - yOffs)/1000;
        zRot = (xOffs - ev.clientX)*.3; }
    else {
        yRot = - xOffs + ev.clientX;  xRot = - yOffs + ev.clientY; }
    xOffs = ev.clientX;   yOffs = ev.clientY;
    updateMeshRotation();
}
function wheelHandler(ev) {
    var del = 1;
    if (ev.shiftKey) del = 0.1;
    var ds = ((ev.detail || ev.wheelDelta) > 0) ? del : -del;
    ds *= transl;
    ev.preventDefault();
    updateCamera(ds);
}
function initControl(){
    item_canvas = document.getElementById("mainCanvas");
    item_canvas.addEventListener('DOMMouseScroll', wheelHandler, false);
    item_canvas.addEventListener('mousewheel', wheelHandler, false);
    item_canvas.addEventListener('mousedown', mymousedown, false);
    item_canvas.addEventListener('mouseup', mymouseup, false);
    item_canvas.addEventListener('mousemove', mymousemove, false);
    item_canvas.addEventListener('touchstart', startTouch, false);
    item_canvas.addEventListener('touchmove', continueTouch, false);
    item_canvas.addEventListener('touchend', stopTouch, false);
}