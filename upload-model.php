<?php
require_once "my-tool.php";
session_start();
// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}

$check_id = "SELECT MAX(id) FROM item";
$check_result = $conn->query($check_id)->fetch_assoc();

$new_id = $check_result["MAX(id)"]+1;

if (($_FILES["file"]["type"] == "application/octet-stream")
    && ($_FILES["file"]["size"] < 50000000))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
    else
    {
        $_FILES["file"]["name"] = $new_id.".obj";
        if (file_exists("upload/" . $_FILES["file"]["name"]))
        {
            echo "<script> alert('文件已经存在！') </script>";
            echo "<script>history.go(-1)</script>";
        }
        else
        {
            move_uploaded_file($_FILES["file"]["tmp_name"],
                "assets/models/obj/" . $_FILES["file"]["name"]);
            echo "<script> alert('上传成功！') </script>";
            echo "<script>history.go(-1)</script>";
        }
    }
}
else
{
    echo "<script> alert('请选择.obj类型的文件') </script>";
    echo "<script>history.go(-1)</script>";
}
?>