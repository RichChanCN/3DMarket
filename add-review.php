<?php
require_once "my-tool.php";

session_start();

$item_id = $_GET['item_id'];
$anonymous = $_GET['anonymous'];
$review = $_GET['review'];
$score = $_GET['score'];


if($_SESSION['is_login']){
    // 创建连接
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 检测连接
    if ($conn->connect_error) {
        die("连接数据库失败: " . $conn->connect_error);
    }

    //判断是否匿名
    if ($anonymous=="true")
        $user_name = "匿名";
    else
        $user_name = $_SESSION['user_name'];

    $search_sql = "SELECT * FROM shopping_order WHERE (item_id='$item_id') and (user_id='$_SESSION[user_id]') and (is_paid=1)";

    $search_result = $conn->query($search_sql);
    if ($search_result->num_rows>0){

        $add_sql = "INSERT INTO item_reviews (item_id, user_id, user_name, time, review, score) VALUES ('$item_id','$_SESSION[user_id]','$user_name',NOW(),'$review', '$score')";
        //执行上面的sql语句并将结果集赋给result。
        $result = $conn->query($add_sql);
        //判断结果集的记录数是否大于0
        if ($result) {
            echo "1";
        } else {
            echo "3";
        }

    }
    else
        echo "2";
    $conn->close();
}
else{
    echo "0";
}