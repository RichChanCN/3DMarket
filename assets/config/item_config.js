/**
 * Created by Richer on 2017/12/12.
 */

var Type = {};
Type.type1 = 0;
Type.type2 = 1;
Type.type3 = 2;
Type.type4 = 3;
Type.type5 = 4;
Type.type6 = 5;
Type.type7 = 6;

var StockState = {};
StockState[0] = "没货";
StockState[1] = "少量";
StockState[2] = "充足";

var UseCase = {};
UseCase.Livingroom = "客厅";
UseCase.Bedroom = "卧室";
UseCase.Washroom = "卫生间";
UseCase.Kitchen = "厨房";
UseCase.Diningroom = "餐厅";
UseCase.Studyroom = "书房";
UseCase.Other = "其他";

var my_path = {};
my_path.JsonPath = "assets/models/json/";
my_path.ModelPath = "assets/models/obj/";
my_path.MaterialPath = "assets/models/mtl/";
my_path.TexturePath = "assets/models/tex/";

//最先使用的物品list
var item_list = {};

//单个物品页面的物品信息
var item_data;

//分类物品展示的物品列表
var product_list = {};

//主页展示的物品
var main_page_item_list = {};

//大场景里面的物品列表
var scene_item_list = {};

function ItemInfo(id, use_case, type, name, price, discount, length, width, height, area, brand, materials, description, made_in, stock, style_num, is_new, style, introduction) {

    this.material_group = {};
    for (var i=0;i<style_num;i++)
    {
        this.material_group[i] = id.toString()+"_"+(i+1)+".mtl";
    }

    this.id = id;
    this.type = type;
    this.use_case = use_case;
    this.brand = brand;
    this.name = name;
    this.price = price;
    this.discount = discount;
    this.cur_price = (price * discount).toFixed(0);
    this.model_name = id.toString()+".obj";

    this.length = length;
    this.width = width;
    this.height = height;
    this.area = area;
    this.materials = materials;
    this.description = description;
    this.made_in = made_in;
    this.stock = stock;
    this.is_new = is_new;
    this.style = style;
    this.introduction = introduction;
}

function clearProductList() {
    product_list = {};
}
function clearMainPageItemList() {
    product_list = {};
}

function clearSceneItemList() {
    scene_item_list = {};
}

var scene_item_info = {};
scene_item_info[148] = {};
scene_item_info[149] = {};
scene_item_info[150] = {};
scene_item_info[151] = {};
scene_item_info[152] = {};
scene_item_info[153] = {};
scene_item_info[154] = {};
scene_item_info[155] = {};
scene_item_info[156] = {};
scene_item_info[157] = {};

scene_item_info[148].x =  200;scene_item_info[148].z = 0;scene_item_info[148].scale_rate = 3;scene_item_info[148].yaw = 1;
scene_item_info[149].x =  160;scene_item_info[149].z = 0;scene_item_info[149].scale_rate = 3;scene_item_info[149].yaw = 1;
scene_item_info[150].x =  120;scene_item_info[150].z = 0;scene_item_info[150].scale_rate = 3;scene_item_info[150].yaw = 1;
scene_item_info[151].x =  80;scene_item_info[151].z = 0;scene_item_info[151].scale_rate = 3;scene_item_info[151].yaw = 1;
scene_item_info[152].x =  40;scene_item_info[152].z = 0;scene_item_info[152].scale_rate = 3;scene_item_info[152].yaw = 1;
scene_item_info[153].x = -40;scene_item_info[153].z = 0;scene_item_info[153].scale_rate = 3;scene_item_info[153].yaw = 1;
scene_item_info[154].x = -80;scene_item_info[154].z = 0;scene_item_info[154].scale_rate = 3;scene_item_info[154].yaw = 1;
scene_item_info[155].x = -120;scene_item_info[155].z = 0;scene_item_info[155].scale_rate = 3;scene_item_info[155].yaw = 1;
scene_item_info[156].x = -160;scene_item_info[156].z = 0;scene_item_info[156].scale_rate = 3;scene_item_info[156].yaw = 1;
scene_item_info[157].x = -200;scene_item_info[157].z = 0;scene_item_info[157].scale_rate = 3;scene_item_info[157].yaw = 1;


