<?php
$servername = "localhost";
$username = "root";
$password = "rq579687";
$dbname = "3dmarket";

session_start();

$email = $_POST['email_login'];
$pwd = $_POST['password_login'];


// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}

//验证内容是否与数据库的记录吻合。
$sql = "SELECT * FROM user WHERE (email='$email') AND (password='$pwd')";
//执行上面的sql语句并将结果集赋给result。
$result = $conn->query($sql);
//判断结果集的记录数是否大于0
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user_name'] = $row['nickname'];
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['is_login'] = true;
    $_SESSION['user_address'] = $row['country'].$row['province'].$row['city'].$row['address'].$row['post_code'];
    // 输出每行数据
    echo "<script>history.go(-2)</script>";
} else {
    echo "没有您要的信息";
}


$conn->close();

?>
