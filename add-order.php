<?php
require_once "my-tool.php";

session_start();

$id = $_GET['id'];
$amount = $_GET['amount'];
$style = $_GET['style'];

if($_SESSION['is_login']){
    // 创建连接
    $conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
    if ($conn->connect_error) {
        die("连接数据库失败: " . $conn->connect_error);
    }

    $add_sql = "INSERT INTO shopping_order (user_id, item_id, amount, style) 
VALUES ('$_SESSION[user_id]','$id','$amount', '$style')";
//执行上面的sql语句并将结果集赋给result。
    $result = $conn->query($add_sql);
//判断结果集的记录数是否大于0
    if ($result) {
        echo "1";
    } else {
        echo "2";
    }
    $conn->close();
}
else{
    echo "0";
}
