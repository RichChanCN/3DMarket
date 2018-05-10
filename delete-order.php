<?php
require_once "my-tool.php";

session_start();

$id = $_GET['id'];


// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}

$delete_sql = "DELETE FROM shopping_order WHERE (id='$id')";
//执行上面的sql语句并将结果集赋给result。
$result = $conn->query($delete_sql);
//判断结果集的记录数是否大于0
if ($result) {
    echo "1";
} else {
    echo "0";
}

$conn->close();