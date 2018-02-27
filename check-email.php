<?php
$servername = "localhost";
$username = "root";
$password = "rq579687";
$dbname = "3dmarket";

session_start();

$email = $_GET['email'];


// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}

//验证内容是否与数据库的记录吻合。
$sql = "SELECT * FROM user WHERE (email='$email')";
//执行上面的sql语句并将结果集赋给result。
$result = $conn->query($sql);
//判断结果集的记录数是否大于0
if ($result->num_rows > 0) {
    echo "0";
} else {
    echo "1";
}

$conn->close();

?>