<?php
$servername = "localhost";
$username = "root";
$password = "rq579687";
$dbname = "3dmarket";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}

$data[0] = $_POST["nickname"];
$data[1] = $_POST["email"];
$data[2] = $_POST["password"];
$data[3] = $_POST["telephone"];
$data[4] = $_POST["country"];
$data[5] = $_POST["province"];
$data[6] = $_POST["city"];
$data[7] = $_POST["address"];
$data[8] = $_POST["post_code"];

//$sql = "INSERT INTO user (nickname, email, password, telephone, country, province, city, address, post_code)
//VALUES ($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8])";

$sql = "INSERT INTO user (nickname, email, password, telephone, country, province, city, address, post_code)
VALUES ('$_POST[nickname]','$_POST[email]', '$_POST[password]', '$_POST[telephone]', '$_POST[country]', '$_POST[province]', '$_POST[city]', '$_POST[address]', '$_POST[post_code]')";

if ($conn->query($sql) === TRUE) {
    echo "注册成功，即将为您跳转到登录页面...<br>";
    header("Refresh:3;url=shop-login.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>