<?php
require_once "my-tool.php";

session_start();

$type = $_GET['type'];
$use_case = $_GET['use_case'];
$name = $_GET['name'];
$price = $_GET['price'] === ''?0:$_GET['price'];
$discount = $_GET['discount'] === ''?1:$_GET['discount'];
$length = $_GET['length'] === ''?0:$_GET['length'];
$width = $_GET['width'] === ''?0:$_GET['width'];
$height = $_GET['height'] === ''?0:$_GET['height'];
$area = $_GET['area'] === ''?0:$_GET['area'];
$brand = $_GET['brand'];
$materials = $_GET['materials'];
$description = $_GET['description'];
$made_in = $_GET['made_in'];
$stock = $_GET['stock'] === ''?0:$_GET['stock'];
$style_num = $_GET['style_num'];
$is_new = $_GET['is_new'];
$style = $_GET['style'];
$introduction = $_GET['introduction'];
$is_special = $_GET['is_special'];

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}
$check_id = "SELECT MAX(id) FROM item";
$check_result = $conn->query($check_id)->fetch_assoc();

$new_id = $check_result["MAX(id)"]+1;

$add_sql = "INSERT INTO item (id,use_case,type,name,price,discount,length,width,height,area,brand,materials,description,made_in,stock,style_num,is_new,style,introduction,is_special) 
VALUES ('$new_id','$use_case','$type','$name', '$price','$discount','$length','$width','$height','$area','$brand','$materials','$description','$made_in','$stock','$style_num','$is_new','$style','$introduction','$is_special')";
//执行上面的sql语句并将结果集赋给result。
$result = $conn->query($add_sql);
//判断结果集的记录数是否大于0
if ($result === TRUE) {
    echo "商品添加成功，3秒后刷新界面...<br>";
    header("Refresh:3;url=shop-add-item.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>