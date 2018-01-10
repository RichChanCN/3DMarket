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


var item_list = {};

item_list[1] = new Item(
    1, Type.type1, UseCase.Other,       //id, 类型, 适用于
    "宜家",                              //品牌
    "喷火龙",                            //名称
    100, 95,                            //原价格, 当前价格
    "assets/models/5.obj",              //模型资源路径
    null,                               //材质路径
    "assets/models/5.jpg",              //贴图路径
    110, 90, 70, 120,                   //长(cm)宽(cm)高(cm)重(kg)
    "龙肉",                              //构成材质
    "这是一条萌萌哒喷火龙！",               //描述
    "日本",                              //产地
    StockState[0]                       //库存
);

item_list[2] = new Item(
    1, Type.type1, UseCase.Livingroom,  //id, 类型， 适用于
    "宜家",                              //品牌
    "沙发",                              //名称
    100, 95,                            //原价格, 当前价格
    "assets/models/sofa1.obj",          //模型资源路径
    "assets/models/sofa1.mtl",          //材质路径
    null,                               //贴图路径
    110, 90, 70, 80,                    //长(cm)宽(cm)高(cm)重(kg)
    "棉，麻",                            //构成材质
    "这是一款舒适的沙发！",                 //描述
    "中国",                              //产地
    StockState[1]                       //库存
);

item_list[3] = new Item(
    1, Type.type1, UseCase.Studyroom,      //id, 类型, 适用于
    "宜家",                                 //品牌
    "学习桌",                                //名称
    100, 95,                                //原价格, 当前价格
    "assets/models/ModernDeskOBJ.obj",      //模型资源路径
    "assets/models/ModernDeskOBJ.mtl",      //材质路径
    null,                                   //贴图路径
    110, 50, 45, 100,                       //长(cm)宽(cm)高(cm)重(kg)
    "紫檀木，不锈钢",                          //构成材质
    "这是一张学习用的桌子！",                    //描述
    "中国",                                   //产地
    StockState[2]                           //库存
);


item_list[4] = new Item(
    1, Type.type1, UseCase.Studyroom,      //id, 类型, 适用于
    "宜家",                                 //品牌
    "学习桌",                                //名称
    100, 95,                                //原价格, 当前价格
    "assets/models/json/chaji.json",        //模型资源路径
    null,      //材质路径
    null,                                   //贴图路径
    110, 50, 45, 100,                       //长(cm)宽(cm)高(cm)重(kg)
    "紫檀木，不锈钢",                          //构成材质
    "这是一张学习用的桌子！",                    //描述
    "中国",                                   //产地
    StockState[2]                           //库存
);


function Item(id, type, use_case, brand, name, price, cur_price, model_url, material_url, texture_url, length, width, height, weight, materials, description, made_in, stock) {
    this.id = id;
    this.type = type;
    this.use_case = use_case;
    this.brand = brand;
    this.name = name;
    this.price = price;
    this.cur_price = cur_price;
    this.model_url = model_url;
    this.material_url = material_url;
    this.texture_url = texture_url;
    this.length = length;
    this.width = width;
    this.height = height;
    this.weight = weight;
    this.materials = materials;
    this.description = description;
    this.made_in = made_in;
    this.stock = stock;
}