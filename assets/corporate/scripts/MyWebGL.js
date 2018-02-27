var item_scene={},item_renderer={},objloader,mtlloader,
    item_camera={},item_mesh={},item_stat={},item_drawID={},item_canvas={},item_controls={},
    touch_flag={},item_canvas_ID={};

var item_light1={},item_light2={};

var cur_model_info = {};

function changeWebGLState(index, canvas) {
    cur_model_info = item_list[index];
    cur_model_info.canvas_id = canvas;
}

function changeItemInfo(info, canvas) {
    cur_model_info = {};
    cur_model_info = info;
    cur_model_info.canvas_id = canvas;
}

function initFastViewInfo() {
    if (cur_model_info == null || cur_model_info.name == null || cur_model_info.name === "")
        return;


    if(cur_model_info.discount < 1)
        document.getElementById("sale_tag").style.display = 'block';
    else
        document.getElementById("sale_tag").style.display = 'none';

    document.getElementById("item_name").innerHTML = cur_model_info.name;
    document.getElementById("item_description").innerHTML = cur_model_info.description;
    document.getElementById("item_price").innerHTML = cur_model_info.price;
    document.getElementById("item_cur_price").innerHTML = "<span>￥</span>" + cur_model_info.cur_price;
    document.getElementById("item_length").innerHTML = cur_model_info.length + "cm";
    document.getElementById("item_width").innerHTML = cur_model_info.width + "cm";
    document.getElementById("item_height").innerHTML = cur_model_info.height + "cm";
    document.getElementById("item_weight").innerHTML = cur_model_info.area + "㎡";
    document.getElementById("item_stock").innerHTML = cur_model_info.stock;
    document.getElementById("item_style").innerHTML = cur_model_info.style;
    document.getElementById("item_materials").innerHTML = cur_model_info.materials;
    document.getElementById("item_use_case").innerHTML = cur_model_info.use_case;
    document.getElementById("item_brand").innerHTML = cur_model_info.brand;
    document.getElementById("item_made_in").innerHTML = cur_model_info.made_in;
}

function initWebGL(obj_name,mtl_group,canvas_id,index) {
    index = index || 1;
    //初始化状态监测
    //statInit();
    //创建一个渲染器对象
    item_renderer = new THREE.WebGLRenderer({
        canvas: document.getElementById(canvas_id)
    });
    item_canvas_ID[index] = canvas_id;

    //清除画布内容
    clearCanvas(item_canvas_ID[index]);

    //初始化
    initProgressBar(index);
    initScene(index);
    initCamera(index);
    initControl(canvas_id,index);
    initLight(index);
    initLoader();
    //加载模型
    loadModels(obj_name,mtl_group,index)

}

//初始化进度条
function initProgressBar(index) {
    document.getElementById(item_canvas_ID[index]+"_progress_div").style.display = 'block';
    document.getElementById(item_canvas_ID[index]+"_progress").value = 0;
}

//创建一个场景对象
function initScene(index) {
    if (item_scene[index] != null)
        return;
    item_scene[index] = new THREE.Scene();
    item_scene[index].background = new THREE.Color( 0xcccccc );
}

//创建一个摄像机对象，并且设置相关属性
function initCamera(index) {
    var ratio = document.getElementById(item_canvas_ID[index]).width/document.getElementById(item_canvas_ID[index]).height;

    if (item_camera[index] != null){
        item_scene[index].remove(item_camera[index]);
        item_camera[index] = new THREE.PerspectiveCamera(45,ratio,1,1000);
    }
    else
        item_camera[index] = new THREE.PerspectiveCamera(45,ratio,1,1000);

    item_camera[index].position.set(0,6,10);
    item_camera[index].lookAt(new THREE.Vector3(0,2,0));

    //把摄像机添加到场景中
    item_scene[index].add(item_camera[index]);
}

//上帝说要有光
function initLight(index) {
    if (item_light1[index] == null){
        item_light1[index] = new THREE.PointLight(0xffffff, 2, 100);
        item_scene[index].add(item_light1[index]);
    }

    if (item_light2[index] == null){
        item_light2[index] = new THREE.PointLight(0xffffff, 2, 100);
        item_scene[index].add(item_light2[index]);
    }

    item_light1[index].position.set(0,30,20);
    item_light2[index].position.set(0,-10,-20);
}

function initLoader() {
    //创建一个模型加载器对象和材质加载器对象
    if (objloader == null) {
        objloader = new THREE.OBJLoader();
    }

    if (mtlloader == null){
        mtlloader = new THREE.MTLLoader();
    }
}

//加载模型
function loadModels(obj_name,mtl_group,index) {
    if(item_mesh[index] != null)
        item_scene[index].remove(item_mesh[index]);

    mtlloader.setPath(my_path.MaterialPath);
    mtlloader.setTexturePath(my_path.TexturePath);

    mtlloader.load(mtl_group[0],mtl);
    function mtl(materials) {
        materials.preload();
        objloader.setMaterials(materials)
    }

    objloader.setPath(my_path.ModelPath);

    objloader.load(
        obj_name,

        function (obj) {
            obj.traverse(function (child) {
                if (child instanceof THREE.Mesh){
                    child.material.side = THREE.DoubleSide;
                }
            });
            item_mesh[index] = obj;
            item_scene[index].add(obj);
            item_renderer.render(item_scene[index],item_camera[index]);
            beginTheFirstFrame(index);
        },

        function ( xhr ) {
            document.getElementById(item_canvas_ID[index]+"_progress").value = ( xhr.loaded / xhr.total * 100 ).toFixed(0);
        }
    );

}

function changeModelMtl(mtl_index,index) {
    index = index || 1;
    if (cur_model_info.material_group[mtl_index-1] == null || cur_model_info.material_group[mtl_index-1] == ""){
        var options = document.getElementById("cur_style").options;
        options[0].selected = true;
        changeModelMtl(1);
        alert("暂时没有该种搭配！");
        return;
    }

    mtlloader.setPath(my_path.MaterialPath);
    mtlloader.setTexturePath(my_path.TexturePath);

    mtlloader.load(cur_model_info.material_group[mtl_index-1],mtl);
    function mtl(materials) {
        item_mesh[index].traverse(function (child) {
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

function changeMainModelMtl(model_info,index,mtl_index) {
    index = index || 1;
    if (model_info.material_group[mtl_index-1] == null || model_info.material_group[mtl_index-1] == ""){
        var options = document.getElementById("item_style").options;
        options[0].selected = true;
        changeMainModelMtl(model_info,index,1)
        alert("暂时没有该种搭配！");
        return;
    }

    mtlloader.setPath(my_path.MaterialPath);
    mtlloader.setTexturePath(my_path.TexturePath);

    mtlloader.load(model_info.material_group[mtl_index-1],mtl);
    function mtl(materials) {
        item_mesh[index].traverse(function (child) {
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
function beginTheFirstFrame(index) {
    document.getElementById(item_canvas_ID[index]+"_progress_div").style.display = 'none';
    draw(index);
}

//绘制函数开始
function draw(index) {
    //item_stat.begin();
    if (touch_flag != 1)
        item_mesh[index].rotation.y = (item_mesh[index].rotation.y+0.01)%(Math.PI*2);

    item_controls.update();

    item_renderer.render(item_scene[index],item_camera[index]);

    item_drawID[index] = requestAnimationFrame(function(){
        draw(index)
    });

    //item_stat.end();
}

//动画停止函数
function itemDrawStop(index) {
    index = index || 1;
    if(item_drawID[index] != null){
        cancelAnimationFrame(item_drawID[index]);
        item_drawID[index] = {};
    }
    touch_flag = null;
    //THREE.Cache.clear()
    clearCanvas(item_canvas_ID[index])
}

function resetScene(index) {
    index = index || 1;
    item_camera[index].position.set(0,12,10);
    item_camera[index].lookAt(new THREE.Vector3(0,2,0));

    touch_flag = null;

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

//---------------------------------控制部分------------------------------------
function initControl(canvas_id,index) {
    item_canvas[index] = document.getElementById(canvas_id);

    item_controls = new THREE.OrbitControls(item_camera[index], item_canvas[index]);

    //旋转的中心点
    item_controls.target.set(0, 2, 0);

    item_controls.update();

    item_canvas[index].addEventListener('mousedown', mymousedown, false);
}

function mymousedown(){
    touch_flag = 1;
}