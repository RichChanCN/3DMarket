var item_scene,item_renderer,objloader,mtlloader,jsonloader,objectloader,
    item_camera,item_mesh,item_stat,item_drawID,item_canvas,
    touch_flag,item_canvasID;

var item_light1,item_light2;

var cur_model_info;

function changeWebGLState(index, canvas) {
    cur_model_info = item_list[index];
    cur_model_info.canvas_id = canvas;
}

function initFastViewInfo() {
    document.getElementById("item_name").innerHTML = cur_model_info.name;
    document.getElementById("item_description").innerHTML = cur_model_info.description;
    document.getElementById("item_price").innerHTML = cur_model_info.price;
    document.getElementById("item_cur_price").innerHTML = "<span>￥</span>" + cur_model_info.cur_price;
    document.getElementById("item_length").innerHTML = cur_model_info.length + "cm";
    document.getElementById("item_width").innerHTML = cur_model_info.width + "cm";
    document.getElementById("item_height").innerHTML = cur_model_info.height + "cm";
    document.getElementById("item_weight").innerHTML = cur_model_info.weight + "kg";
    document.getElementById("item_stock").innerHTML = cur_model_info.stock;
    document.getElementById("item_materials").innerHTML = cur_model_info.materials;
    document.getElementById("item_use_case").innerHTML = cur_model_info.use_case;
    document.getElementById("item_brand").innerHTML = cur_model_info.brand;
    document.getElementById("item_made_in").innerHTML = cur_model_info.made_in;

}

function initWebGL(obj_url,mtl_url,image_url,canvas_id) {
    //初始化状态监测
    statInit();
    //创建一个渲染器对象
    item_renderer = new THREE.WebGLRenderer({
        canvas: document.getElementById(canvas_id)
    });
    item_canvasID = canvas_id;

    //清除画布内容
    clearCanvas(item_canvasID);

    //初始化
    initProgressBar();
    initControl(canvas_id);
    initScene();
    initCamera();
    initLight();
    initLoader();
    //加载模型
    loadModels(obj_url,mtl_url,image_url)


}
//初始化进度条
function initProgressBar() {
    document.getElementById(item_canvasID+"_progress_div").style.display = 'block';
    document.getElementById(item_canvasID+"_progress").value = 0;
}

//创建一个场景对象
function initScene() {
    if (item_scene != null)
        return;
    item_scene = new THREE.Scene();
    item_scene.background = new THREE.Color( 0xcccccc );
}

//创建一个摄像机对象，并且设置相关属性
function initCamera() {
    var ratio = document.getElementById(item_canvasID).width/document.getElementById(item_canvasID).height;

    if (item_camera != null){
        item_scene.remove(item_camera);
        item_camera = new THREE.PerspectiveCamera(45,ratio,1,1000);
    }
    else
        item_camera = new THREE.PerspectiveCamera(45,ratio,1,1000);

    item_camera.position.set(0,12,10);
    item_camera.lookAt(new THREE.Vector3(0,2,0));

    //把摄像机添加到场景中
    item_scene.add(item_camera);
}

//上帝说要有光
function initLight() {
    if (item_light1 == null){
        item_light1 = new THREE.PointLight(0xffffff, 2, 100);
        item_scene.add(item_light1);
    }

    if (item_light2 == null){
        item_light2 = new THREE.PointLight(0xffffff, 2, 100);
        item_scene.add(item_light2);
    }

    item_light1.position.set(0,30,0);
    item_light2.position.set(0,0,20);
}

function initLoader() {
    //创建一个模型加载器对象和材质加载器对象
    if (objloader == null)
        objloader = new THREE.OBJLoader();

    if (jsonloader == null)
        jsonloader = new THREE.JSONLoader();

    if (objectloader == null)
        objectloader = new THREE.ObjectLoader();

    if (mtlloader == null)
        mtlloader = new THREE.MTLLoader();
}

//加载模型
function loadModels(obj_url,mtl_url,image_url) {
    if(item_mesh != null)
        item_scene.remove(item_mesh);

    // objectloader.load(obj_url,
    //     function( obj ) {
    //         item_mesh = obj
    //         item_mesh.scale.x = item_mesh.scale.y = item_mesh.scale.z =1;
    //         item_scene.add(item_mesh);
    //         item_renderer.render(item_scene,item_camera);
    //         beginTheFirstFrame();
    //     },
    //
    //     function ( xhr ) {
    //         document.getElementById(item_canvasID+"_progress").value = ( xhr.loaded / xhr.total * 100 ).toFixed(0);
    //         console.log( ( xhr.loaded / xhr.total * 100 ) + '% loaded' );
    //     });

    // jsonloader.load(obj_url,
    //     function( geometry, materials ) {
    //
    //         item_mesh = new THREE.Mesh( geometry, materials);
    //         item_mesh.position.x = 0;
    //         item_mesh.position.y = 0;
    //         item_mesh.position.z = 0;
    //         item_mesh.scale.x = item_mesh.scale.y = item_mesh.scale.z =1;
    //         item_scene.add(item_mesh);
    //         item_renderer.render(item_scene,item_camera);
    //         beginTheFirstFrame();
    //     },
    //
    //     function ( xhr ) {
    //         document.getElementById(item_canvasID+"_progress").value = ( xhr.loaded / xhr.total * 100 ).toFixed(0);
    //         console.log( ( xhr.loaded / xhr.total * 100 ) + '% loaded' );
    //     });

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
                item_scene.add(item_mesh);
                item_renderer.render(item_scene,item_camera);
                beginTheFirstFrame();
            },

            function ( xhr ) {
                document.getElementById(item_canvasID+"_progress").value = ( xhr.loaded / xhr.total * 100 ).toFixed(0);
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
                beginTheFirstFrame();
            },

            function ( xhr ) {
                document.getElementById(item_canvasID+"_progress").value = ( xhr.loaded / xhr.total * 100 ).toFixed(0);
                //document.getElementById("progressBar").innerHTML=( xhr.loaded / xhr.total * 100 ).toFixed(1) + '% loaded';
                //console.log( ( xhr.loaded / xhr.total * 100 ) + '% loaded' );
            }
        );
    }
}

function changeModelMtl(index) {
    mtlloader.load("assets/models/sofa2.mtl",mtl);
    function mtl(materials) {
        item_mesh.traverse(function (child) {
            if (child instanceof THREE.Mesh){
                var createdMaterials = [];

                for ( var mi = 0, miLen = child.material.length; mi < miLen; mi ++ ) {

                    var sourceMaterial = child.material[ mi ];
                    var material = undefined;

                    if ( materials !== null ) {

                        material = materials.create( sourceMaterial.name );

                    }

                    if ( ! material ) {

                        material = new THREE.MeshPhongMaterial();
                        material.name = sourceMaterial.name;

                    }

                    material.flatShading = !sourceMaterial.smooth;

                    createdMaterials.push( material );

                }
                child.material = createdMaterials;
            }
        });
    }
}

//开始第一帧渲染前的操作
function beginTheFirstFrame() {
    document.getElementById(item_canvasID+"_progress_div").style.display = 'none';
    draw();
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
function itemDrawStop() {
    if(item_drawID != null){
        cancelAnimationFrame(item_drawID);
        item_drawID = null;
    }
    touch_flag = null;
    //THREE.Cache.clear()
    clearCanvas(item_canvasID)
}
function clearCanvas(canvas_id)
{
    var c=document.getElementById(canvas_id);
    var gl = c.getContext('webgl') || c.getContext("experimental-webgl");
    gl.clearColor(1,1,1,1);
    gl.clear(gl.COLOR_BUFFER_BIT);
}

//帧数检测函数
function statInit() {
    if(item_stat != null)
        return;

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
function initControl(canvas_id){
    item_canvas = document.getElementById(canvas_id);
    item_canvas.addEventListener('DOMMouseScroll', wheelHandler, false);
    item_canvas.addEventListener('mousewheel', wheelHandler, false);
    item_canvas.addEventListener('mousedown', mymousedown, false);
    item_canvas.addEventListener('mouseup', mymouseup, false);
    item_canvas.addEventListener('mousemove', mymousemove, false);
    item_canvas.addEventListener('touchstart', startTouch, false);
    item_canvas.addEventListener('touchmove', continueTouch, false);
    item_canvas.addEventListener('touchend', stopTouch, false);
}