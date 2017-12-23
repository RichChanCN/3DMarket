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

var item_list = {};

item_list[1] = new Item(1, 5, Type.type1, 100, "assets/models/5.obj", null, "assets/models/5.jpg");
item_list[2] = new Item(2, 5, Type.type2, 100, "assets/models/sofa1.obj", "assets/models/sofa1.mtl", null);

function Item(id, name, type, price, model_url, material_url, texture_url) {
    this.id = id;
    this.name = name;
    this.type = type;
    this.price = price;
    this.model_url = model_url;
    this.material_url = material_url;
    this.texture_url = texture_url;
}