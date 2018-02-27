<?php
$servername = "localhost";
$username = "root";
$password = "rq579687";
$dbname = "3dmarket";

session_start();

$id = $_GET["id"];

require_once 'my-tool.php';
echo "<script type=\"text/javascript\" src=\"assets/config/item_config.js\"></script>";

if($id != "" or $id != null){
    // 创建连接
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 检测连接
    if ($conn->connect_error) {
        die("连接数据库失败: " . $conn->connect_error);
    }

    global $item_data;
    global $reviews_data;
    global $reviews_count;
    //验证内容是否与数据库的记录吻合。
    $item_sql = "SELECT * FROM item WHERE (id='$id')";
    //执行上面的sql语句并将结果集赋给result。
    $item_result = $conn->query($item_sql);
    //判断结果集的记录数是否大于0
    if ($item_result->num_rows > 0) {
        // 输出数据
        $row = $item_result->fetch_assoc();
        $item_data = new Item($row["id"],$row["use_case"],$row["type"],$row["name"],
                $row["price"],$row["discount"],$row["length"],$row["width"],
                $row["height"],$row["area"],$row["brand"],$row["materials"],
                $row["description"],$row["made_in"],$row["stock"],$row["style_num"],$row["is_new"],$row["style"],$row["introduction"]);
        echo "<script type=\"text/javascript\">item_data = new ItemInfo(\"$row[id]\",\"$row[use_case]\",\"$row[type]\",\"$row[name]\",
                \"$row[price]\",\"$row[discount]\",\"$row[length]\",\"$row[width]\",
                \"$row[height]\",\"$row[area]\",\"$row[brand]\",\"$row[materials]\",
                \"$row[description]\",\"$row[made_in]\",\"$row[stock]\",\"$row[style_num]\",\"$row[is_new]\",\"$row[style]\")</script>";
    }

    $reviews_sql = "SELECT * FROM item_reviews WHERE (item_id='$id') ORDER BY time DESC limit 100 ";
    $reviews_result = $conn->query($reviews_sql);
    if ($reviews_result->num_rows > 0) {
        //初始化数据
        unset($reviews_data);
        Review::clearAmountScore();
        // 输出数据
        $reviews_count = 0;
        while($row = $reviews_result->fetch_assoc()){
            $reviews_data[$reviews_count] = new Review($row["id"],$row["item_id"],$row["user_id"],$row["user_name"],
                $row["time"],$row["review"],$row["score"]);

            $reviews_count+=1;
        }
    }
    else
        $reviews_count = 0;

    $conn->close();
}
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- Head BEGIN -->
<head>
    <meta charset="utf-8">
    <title>UGH|商品详情</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta content="Metronic Shop UI description" name="description">
    <meta content="Metronic Shop UI keywords" name="keywords">
    <meta content="keenthemes" name="author">

    <meta property="og:site_name" content="-CUSTOMER VALUE-">
    <meta property="og:title" content="-CUSTOMER VALUE-">
    <meta property="og:description" content="-CUSTOMER VALUE-">
    <meta property="og:type" content="website">
    <meta property="og:image" content="-CUSTOMER VALUE-"><!-- link to image for socio -->
    <meta property="og:url" content="-CUSTOMER VALUE-">

    <link rel="shortcut icon" href="favicon.ico">

    <!-- Fonts START -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|PT+Sans+Narrow|Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css">
    <!-- Fonts END -->

    <!-- Global styles START -->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Global styles END -->

    <!-- Page level plugin styles START -->
    <link href="assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
    <link href="assets/plugins/owl.carousel/assets/owl.carousel.css" rel="stylesheet">
    <link href="assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
    <link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"><!-- for slider-range -->
    <link href="assets/plugins/rateit/src/rateit.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin styles END -->

    <!-- Theme styles START -->
    <link href="assets/pages/css/components.css" rel="stylesheet">
    <link href="assets/corporate/css/style.css" rel="stylesheet">
    <link href="assets/pages/css/style-shop.css" rel="stylesheet" type="text/css">
    <link href="assets/corporate/css/style-responsive.css" rel="stylesheet">
    <link href="assets/corporate/css/themes/red.css" rel="stylesheet" id="style-color">
    <link href="assets/corporate/css/custom.css" rel="stylesheet">
    <!-- Theme styles END -->
</head>
<!-- Head END -->

<!-- Body BEGIN -->
<body class="ecommerce" onload="initWebGL(item_data.model_name,item_data.material_group,'MainCanvas',2)">
<!-- BEGIN STYLE CUSTOMIZER -->
<div class="color-panel hidden-sm">
    <div class="color-mode-icons icon-color" onclick="window.open('shop-3D-view.php')"></div>
</div>
<!-- END BEGIN STYLE CUSTOMIZER -->

<!-- BEGIN TOP BAR -->
<div class="pre-header">
    <div class="container">
        <div class="row">
            <!-- BEGIN TOP BAR LEFT PART -->
            <div class="col-md-6 col-sm-6 additional-shop-info">
                <ul class="list-unstyled list-inline">
                    <li><i class="fa fa-phone"></i><span>1234567</span></li>
                    <!-- BEGIN CURRENCIES -->
                    <li class="shop-currencies">
                        <a href="javascript:void(0);" class="current">￥</a>
                        <a href="javascript:void(0);">$</a>
                    </li>
                    <!-- END CURRENCIES -->
                    <!-- BEGIN LANGS -->
                    <li class="langs-block">
                        <a href="javascript:void(0);" class="current">简体中文</a>
                        <div class="langs-block-others-wrapper"><div class="langs-block-others">
                                <a href="javascript:void(0);">French</a>
                                <a href="javascript:void(0);">Germany</a>
                                <a href="javascript:void(0);">English</a>
                            </div></div>
                    </li>
                    <!-- END LANGS -->
                </ul>
            </div>
            <!-- END TOP BAR LEFT PART -->
            <!-- BEGIN TOP BAR MENU -->
            <div class="col-md-6 col-sm-6 additional-nav">
                <ul class="list-unstyled list-inline pull-right">
                    <li><a href="javascript:;">账户</a></li>
                    <li><a href="shop-shopping-cart.php">我的订单</a></li>
                    <li><a href="logout.php">注销</a></li>
                    <?php
                    if (!$_SESSION['is_login'])
                        echo "<li><a href='shop-login.php'>登录</a></li>";
                    else
                        echo "<li><a href='shop-account.php'>$_SESSION[user_name]</a></li>";
                    ?>
                </ul>
            </div>
            <!-- END TOP BAR MENU -->
        </div>
    </div>
</div>
<!-- END TOP BAR -->

<!-- BEGIN HEADER -->
<div class="header">
    <div class="container">
        <a class="site-logo" href="shop-index.php">UGH Furniture</a>
        <a href="javascript:void(0);" class="mobi-toggler"><i class="fa fa-bars"></i></a>

        <!-- BEGIN CART -->
        <div class="top-cart-block">
            <div class="top-cart-info">
                <?php
                require_once "my-tool.php";



                global $my_orders;
                // 创建连接
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($_SESSION['user_name'] == "" || $_SESSION['user_name'] == null){
                    echo "<a href=\"javascript:void(0);\" class=\"top-cart-info-count\">0件物品</a>";
                    echo "<a href=\"javascript:void(0);\" class=\"top-cart-info-value\">￥0</a>";
                }
                else{
                    $order_sql = "SELECT * FROM shopping_order WHERE (user_id='$_SESSION[user_id]') AND (is_paid='0')";
                    //执行上面的sql语句并将结果集赋给result。
                    $order_result = $conn->query($order_sql);
                    $order_counts = $order_result->num_rows;
                    $order_price = 0;
                    echo "<a href=\"javascript:void(0);\" class=\"top-cart-info-count\">".$order_counts."份订单</a>";
                    if($order_counts>0){
                        // 输出数据
                        $count = 0;
                        while($order_row = $order_result->fetch_assoc()) {
                            $item_id = $order_row["item_id"];
                            $item_sql = "SELECT * FROM item WHERE (id='$item_id')";
                            //执行上面的sql语句并将结果集赋给result。
                            $item_result = $conn->query($item_sql);
                            if ($item_result->num_rows > 0) {
                                $item_row = $item_result->fetch_assoc();
                                $order_price += $item_row["price"]*$item_row["discount"]*$order_row["amount"];

                                $my_orders[$count] = new Order($order_row["id"],$order_row["item_id"],$item_row["name"],$item_row["price"],$order_row["amount"],$item_row["discount"],$item_row["style"]);
                                $count += 1;
                            }
                        }
                    }
                    echo "<a href=\"javascript:void(0);\" class=\"top-cart-info-value\">￥".$order_price."</a>";
                }

                $conn->close();
                ?>
            </div>
            <i class="fa fa-shopping-cart"></i>

            <div class="top-cart-content-wrapper">
                <div class="top-cart-content">
                    <ul class="scroller" style="height: 250px;">
                        <?php
                        if (!$_SESSION['is_login'])
                            echo "<li><a href='shop-login.php'>请您先登录！</a></li>";
                        else{
                            foreach ($my_orders as $temp) {
                                echo "
                                    <li>
                                        <a href=\"shop-item.php?id=".$temp->item_id."\"><img src=\"assets/pages/img/items/".$temp->item_id."_small.jpg\" alt=\"assets/pages/img/".$temp->item_id."_small.jpg\" width=\"37\" height=\"34\"></a>
                                        <span class=\"cart-content-count\">x ".$temp->amount."</span>
                                        <strong><a href=\"shop-item.php?id=".$temp->item_id."\">".$temp->name."</a></strong>
                                        <em>￥".$temp->getOrderPrice()."</em>
                                        <a href=\"javascript:deleteOrder(".$temp->id.");\" class=\"del-goods\">&nbsp;</a>
                                    </li>
                                 ";
                            }
                        }
                        ?>
                    </ul>
                    <div class="text-right">
                        <?php
                        if($_SESSION['is_login'])
                            echo "<a href=\"shop-shopping-cart.php\" class=\"btn btn-primary\">去结算</a>"
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!--END CART -->

        <!-- BEGIN NAVIGATION -->
        <div class="header-navigation">
            <ul>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="shop-product-list.php?type=客厅">
                        客厅

                    </a>
                    <!-- BEGIN DROPDOWN MENU -->
                    <ul class="dropdown-menu">
                        <li><a href="shop-product-list.php?type=沙发">沙发</a></li>
                        <li><a href="shop-product-list.php?type=茶几">茶几</a></li>
                        <li><a href="shop-product-list.php?type=电视柜">电视柜</a></li>
                    </ul>
                    <!-- END DROPDOWN MENU -->
                </li>
                <li class="dropdown dropdown-megamenu">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="shop-product-list.php?type=卧室" href="shop-product-list.php?type=卧室">
                        卧室

                    </a>
                    <!-- BEGIN DROPDOWN MENU -->
                    <ul class="dropdown-menu">
                        <li><a href="shop-product-list.php?type=床铺">床铺</a></li>
                        <li><a href="shop-product-list.php?type=衣柜">衣柜</a></li>
                    </ul>
                    <!-- END DROPDOWN MENU -->
                </li>
                <li>
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="shop-product-list.php?type=书房" href="shop-product-list.php?type=书房">
                        书房

                    </a>
                    <!-- BEGIN DROPDOWN MENU -->
                    <ul class="dropdown-menu">
                        <li><a href="shop-product-list.php?type=书柜">书柜</a></li>
                        <li><a href="shop-product-list.php?type=书桌">书桌</a></li>
                        <li><a href="shop-product-list.php?type=电脑桌">电脑桌</a></li>
                    </ul>
                    <!-- END DROPDOWN MENU -->
                </li>
                <li class="dropdown dropdown-megamenu">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="shop-product-list.php?type=餐厅" href="shop-product-list.php?type=餐厅">
                        餐厅

                    </a>
                    <!-- BEGIN DROPDOWN MENU -->
                    <ul class="dropdown-menu">
                        <li><a href="shop-product-list.php?type=饭桌">饭桌</a></li>
                        <li><a href="shop-product-list.php?type=椅子">椅子</a></li>
                    </ul>
                    <!-- END DROPDOWN MENU -->
                </li>
                <li class="dropdown dropdown-megamenu">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="shop-product-list.php?type=厨房" href="shop-product-list.php?type=厨房">
                        厨房

                    </a>
                    <!-- BEGIN DROPDOWN MENU -->
                    <ul class="dropdown-menu">
                        <li><a href="shop-product-list.php?type=碗柜">碗柜</a></li>
                    </ul>
                    <!-- END DROPDOWN MENU -->
                </li>
                <li class="dropdown dropdown-megamenu">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="shop-product-list.php?type=卫生间" href="shop-product-list.php?type=卫生间">
                        卫生间

                    </a>
                    <!-- BEGIN DROPDOWN MENU -->
                    <ul class="dropdown-menu">
                        <li><a href="shop-product-list.php?type=洗漱台">洗漱台</a></li>
                        <li><a href="shop-product-list.php?type=马桶">马桶</a></li>
                    </ul>
                    <!-- END DROPDOWN MENU -->
                </li>
                <li class="dropdown dropdown-megamenu">
                    <a class="dropdown-toggle" data-toggle="dropdown" data-target="shop-product-list.php?type=其他" href="shop-product-list.php?type=其他">
                        其他

                    </a>
                </li>

                <!-- BEGIN TOP SEARCH -->
                <li class="menu-search">
                    <span class="sep"></span>
                    <i class="fa fa-search search-btn"></i>
                    <div class="search-box">
                        <form action="shop-search-result.php" method="get" onsubmit="return searchCheck(this)">
                            <div class="input-group">
                                <input type="text" placeholder="关键词" name="keyword" id="keyword" class="form-control">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">搜索</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </li>
                <!-- END TOP SEARCH -->
            </ul>
        </div>
        <!-- END NAVIGATION -->
    </div>
</div>
<script type="text/javascript" src="shopping-operate.js"></script>
<!-- Header END -->
<div class="main">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="shop-index.php">主页</a></li>
            <?php
            echo "<li><a href=\"shop-product-list.php?type=".$item_data->type."\">$item_data->type</a></li>";
            echo "<li class=\"active\">$item_data->name</li>";
            ?>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
            <!-- BEGIN SIDEBAR -->
            <div class="sidebar col-md-3 col-sm-5">
                <ul class="list-group margin-bottom-25 sidebar-menu">
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=现代"><i class="fa fa-angle-right"></i> 现代</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=复古"><i class="fa fa-angle-right"></i> 复古</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=创意"><i class="fa fa-angle-right"></i> 创意</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=儿童"><i class="fa fa-angle-right"></i> 儿童</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=奢华"><i class="fa fa-angle-right"></i> 奢华</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=简约"><i class="fa fa-angle-right"></i> 简约</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=其他"><i class="fa fa-angle-right"></i> 其他</a></li>
                </ul>
<!--热卖商品推荐部分-->
<!--                <div class="sidebar-products clearfix">-->
<!--                    <h2>热卖商品</h2>-->
<!--                    <div class="item">-->
<!--                        <a href="shop-item.html"><img src="assets/pages/img/products/k1.jpg" alt="Some Shoes in Animal with Cut Out"></a>-->
<!--                        <h3><a href="shop-item.html">Some Shoes in Animal with Cut Out</a></h3>-->
<!--                        <div class="price">$31.00</div>-->
<!--                    </div>-->
<!--                    <div class="item">-->
<!--                        <a href="shop-item.html"><img src="assets/pages/img/products/k4.jpg" alt="Some Shoes in Animal with Cut Out"></a>-->
<!--                        <h3><a href="shop-item.html">Some Shoes in Animal with Cut Out</a></h3>-->
<!--                        <div class="price">$23.00</div>-->
<!--                    </div>-->
<!--                    <div class="item">-->
<!--                        <a href="shop-item.html"><img src="assets/pages/img/products/k3.jpg" alt="Some Shoes in Animal with Cut Out"></a>-->
<!--                        <h3><a href="shop-item.html">Some Shoes in Animal with Cut Out</a></h3>-->
<!--                        <div class="price">$86.00</div>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
            <!-- END SIDEBAR -->

            <!-- BEGIN CONTENT -->
            <div class="col-md-9 col-sm-7">
                <div class="product-page">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="product-main-image">
                                <canvas id = "MainCanvas" width="395px" height="512px"></canvas>
                                <div id = "MainCanvas_progress_div" style="z-index: 2; position:absolute; left:120px; top:160px;">
                                    <h2>Loading Model...</h2><progress id = "MainCanvas_progress" value="0" max="100"></progress>
                                </div>
                            </div>
<!--                            样式预览框-->
<!--                            <div class="product-other-images">-->
<!--                                <a href="assets/pages/img/products/model3.jpg" class="fancybox-button" rel="photos-lib"><img alt="Berry Lace Dress" src="assets/pages/img/products/model3.jpg"></a>-->
<!--                                <a href="assets/pages/img/products/model4.jpg" class="fancybox-button" rel="photos-lib"><img alt="Berry Lace Dress" src="assets/pages/img/products/model4.jpg"></a>-->
<!--                                <a href="assets/pages/img/products/model5.jpg" class="fancybox-button" rel="photos-lib"><img alt="Berry Lace Dress" src="assets/pages/img/products/model5.jpg"></a>-->
<!--                            </div>-->
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <?php
                            echo "<h1>$item_data->name</h1>";
                            ?>
                            <div class="price-availability-block clearfix">
                                <div class="price">
                                    <?php
                                    echo "<strong><span>￥</span>$item_data->current_price</strong>";
                                    echo "<em>￥<span>$item_data->price</span></em>";
                                    ?>
                                </div>
                                <div class="availability">
                                    <?php
                                    echo "库存：<strong>$item_data->stock</strong>";
                                    ?>
                                </div>
                            </div>
                            <div class="description">
                                <?php echo "<p>$item_data->description</p>"; ?>
                            </div>
                            <div class="product-page-options">
                                <div class="pull-left">
                                    <label class="control-label">颜色搭配:</label>
                                    <select id="item_style" class="form-control input-sm">
                                        <option value="1" onclick="changeMainModelMtl(item_data,2,1)">搭配1</option>
                                        <option value="2" onclick="changeMainModelMtl(item_data,2,2)">搭配2</option>
                                        <option value="3" onclick="changeMainModelMtl(item_data,2,3)">搭配3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="product-page-cart">
                                <div class="product-quantity">
                                    <input id="item_amount" type="text" value="1" readonly class="form-control input-sm">
                                </div>
                                <button class="btn btn-primary" type="submit" onclick="addOrderFromFastView(item_data)">加入购物车</button>
                            </div>
                            <div class="review">
                                <?php
                                echo "<div class=\"rateit\" data-rateit-value=\"".round(Review::getAverageScore()/2,1)."\" data-rateit-readonly=\"true\"  data-rateit-ispreset=\"true\" data-rateit-min=\"0\" data-rateit-max=\"5\"></div>";

                                if($reviews_count<100)
                                    echo "<a href=\"#Reviews\">共 ".$reviews_count." 条评论</a>";
                                else
                                    echo "<a href=\"#Reviews\">共 99+ 条评论</a>";
                                ?>
                                &nbsp;&nbsp;|&nbsp;&nbsp;<a href="#WriteReview">写评论</a>
                            </div>
                            <ul class="social-icons">
                                <li><a class="facebook" data-original-title="facebook" href="javascript:;"></a></li>
                                <li><a class="twitter" data-original-title="twitter" href="javascript:;"></a></li>
                                <li><a class="googleplus" data-original-title="googleplus" href="javascript:;"></a></li>
                                <li><a class="evernote" data-original-title="evernote" href="javascript:;"></a></li>
                                <li><a class="tumblr" data-original-title="tumblr" href="javascript:;"></a></li>
                            </ul>
                            <br>
                            <br>
                            <br>
                            <button class="btn btn-success" onclick="resetScene(2)">重置模型</button>
                        </div>

                        <div class="product-page-content">
                            <ul id="myTab" class="nav nav-tabs">
                                <li><a href="#Description" data-toggle="tab">商品详情</a></li>
                                <li class="active"><a href="#Information" data-toggle="tab">规格</a></li>
                                <?php
                                if ($reviews_count>10)
                                    $reviews_count = 10;
                                echo "<li><a href=\"#Reviews\" data-toggle=\"tab\">最新评论 (".$reviews_count.")</a></li>"
                                ?>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div class="tab-pane fade" id="Description">
                                    <?php echo "<p>$item_data->introduction</p>"?>
                                </div>
                                <div class="tab-pane fade in active" id="Information">
                                    <table class="datasheet">
                                        <tr>
                                            <th colspan="2">相关信息</th>
                                        </tr>
                                        <tr>
                                            <td class="datasheet-features-type">品牌</td>
                                            <?php echo "<td>$item_data->brand</td>"?>
                                        </tr>
                                        <tr>
                                            <td class="datasheet-features-type">长度</td>
                                            <?php echo "<td>".$item_data->length."cm</td>"?>
                                        </tr>
                                        <tr>
                                            <td class="datasheet-features-type">宽度</td>
                                            <?php echo "<td>".$item_data->width."cm</td>"?>
                                        </tr>
                                        <tr>
                                            <td class="datasheet-features-type">高度</td>
                                            <?php echo "<td>".$item_data->height."cm</td>"?>
                                        </tr>
                                        <tr>
                                            <td class="datasheet-features-type">占地面积</td>
                                            <?php echo "<td>".$item_data->area."m²</td>"?>
                                        </tr>
                                        <tr>
                                            <td class="datasheet-features-type">材质</td>
                                            <?php echo "<td>$item_data->materials</td>"?>
                                        </tr>
                                        <tr>
                                            <td class="datasheet-features-type">产地</td>
                                            <?php echo "<td>$item_data->made_in</td>"?>
                                        </tr>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="Reviews">
                                    <?php
                                    if($reviews_count<1)
                                        echo "<p>该件商品暂无评论</p>";
                                    else{
                                        for($x=0;$x<$reviews_count;$x++){
                                            echo "
                                            <div class=\"review-item clearfix\">
                                                <div class=\"review-item-submitted\">
                                                    <strong>".$reviews_data[$x]->user_name."</strong>
                                                    <em>".$reviews_data[$x]->time."</em>
                                                    <div class=\"rateit\" data-rateit-value=\"".round($reviews_data[$x]->score/2,1)."\" data-rateit-ispreset=\"true\" data-rateit-readonly=\"true\"></div>
                                                </div>
                                                <div class=\"review-item-content\">
                                                    <p>".$reviews_data[$x]->review."</p>
                                                </div>
                                            </div>
                                            ";
                                        }
                                    }
                                    ?>

                                    <!-- BEGIN FORM-->
                                    <form class="reviews-form" role="form" id="WriteReview">
                                        <h2>写下你的评论！</h2><p>(需要登录之后并且购买过该商品才可评论)</p>
                                        <div class="form-group">
                                            <label for="review">评论内容 <span class="require">*</span></label>
                                            <textarea class="form-control" rows="8" id="review"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">评分</label>
                                            <input type="range" id="review_score" value="4" step="0.25">
                                            <div class="rateit" data-rateit-backingfld="#review_score" data-rateit-resetable="false"  data-rateit-ispreset="true" data-rateit-min="0" data-rateit-max="5">
                                            </div>
                                        </div>
                                        <label>
                                            <input id="anonymous" type="checkbox"> 匿名评论
                                        </label>
                                        <div class="padding-top-20 pull-right">
                                            <?php echo "<a class=\"btn btn-primary\" href=\"javascript:addReview(".$item_data->id.");\">提交评论</a>"?>
                                        </div>
                                    </form>
                                    <!-- END FORM-->
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($item_data->is_new)
                            $sale_or_new = $sale_or_new."<div class=\"sticker sticker-new\"></div>";
                        if ($item_data->discount<1)
                            $sale_or_new = $sale_or_new."<div class=\"sticker sticker-sale\"></div>";

                        echo "$sale_or_new";
                        ?>
                        <div class="sticker sticker-sale"></div>
                    </div>
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->

        <!-- BEGIN SIMILAR PRODUCTS -->
<!--        相似商品，猜你喜欢部分-->
<!--        <div class="row margin-bottom-40">-->
<!--            <div class="col-md-12 col-sm-12">-->
<!--                <h2>大家最爱</h2>-->
<!--                <div class="owl-carousel owl-carousel4">-->
<!--                    <div>-->
<!--                        <div class="product-item">-->
<!--                            <div class="pi-img-wrapper">-->
<!--                                <img src="assets/pages/img/products/k1.jpg" class="img-responsive" alt="Berry Lace Dress">-->
<!--                                <div>-->
<!--                                    <a href="assets/pages/img/products/k1.jpg" class="btn btn-default fancybox-button">Zoom</a>-->
<!--                                    <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <h3><a href="shop-item.html">Berry Lace Dress</a></h3>-->
<!--                            <div class="pi-price">$29.00</div>-->
<!--                            <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>-->
<!--                            <div class="sticker sticker-new"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
        <!-- END SIMILAR PRODUCTS -->
    </div>
</div>

<!-- BEGIN BRANDS -->
<div class="brands">
    <div class="container">
        <div class="owl-carousel owl-carousel6-brands">
            <a href="shop-product-list.php?type=百得胜家居"><img src="assets/pages/img/brands/BSD.png" alt="BSD" title="BSD"></a>
            <a href="shop-product-list.php?type=香港皇朝家居"><img src="assets/pages/img/brands/XGHC.png" alt="XGHC" title="XGHC"></a>
            <a href="shop-product-list.php?type=奥兰斯丁"><img src="assets/pages/img/brands/ALSD.png" alt="ALSD" title="ALSD"></a>
            <a href="shop-product-list.php?type=林外林"><img src="assets/pages/img/brands/LWL.png" alt="LWL" title="LWL"></a>
            <a href="shop-product-list.php?type=玛沃家具"><img src="assets/pages/img/brands/MF.png" alt="MF" title="MF"></a>
            <a href="shop-product-list.php?type=尚品宅配"><img src="assets/pages/img/brands/SPZP.png" alt="SPZP" title="SPZP"></a>
        </div>
    </div>
</div>
<!-- END BRANDS -->

<!-- BEGIN STEPS -->
<div class="steps-block steps-block-red">
    <div class="container">
        <div class="row">
            <div class="col-md-4 steps-block-col">
                <i class="fa fa-truck"></i>
                <div>
                    <h2>送货上门</h2>
                    <em>国内一周以内送达</em>
                </div>
                <span>&nbsp;</span>
            </div>
            <div class="col-md-4 steps-block-col">
                <i class="fa fa-gift"></i>
                <div>
                    <h2>购物礼包</h2>
                    <em>下单实付金额超过2000即可抽奖</em>
                </div>
                <span>&nbsp;</span>
            </div>
            <div class="col-md-4 steps-block-col">
                <i class="fa fa-phone"></i>
                <div>
                    <h2>477 505 8877</h2>
                    <em>每周7天24小时不间断客服</em>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END STEPS -->

<!-- BEGIN big 3d view of a product -->

<div id="product-big-3d-view" style="display: none">
    <div>
        <div id = "itemBigCanvas_progress_div" style="z-index: 2; position:absolute; left:340px; top:250px;">
            <h2>Loading Model...</h2><progress id = "itemBigCanvas_progress" value="0" max="100"></progress>
        </div>
        <canvas id = "itemBigCanvas" width="800px" height="600px"></canvas>
    </div>
</div>
<!-- END big 3d view of a product -->

<!-- BEGIN fast view of a product -->
<div id="product-pop-up" style="display: none; width: 700px;">
    <div class="product-page product-pop-up">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-3">
                <!--<div class="product-main-image">-->
                <!--<img src="assets/pages/img/products/model7.jpg" alt="Cool green dress with red bell" class="img-responsive">-->
                <!--</div>-->

                <!--WebGL模型加载-->
                <div>
                    <canvas id = "itemCanvas" width="340px" height="400px">
                    </canvas>
                    <div id = "itemCanvas_progress_div" style="z-index: 2; position:absolute; left:120px; top:160px;">
                        <h2>Loading Model...</h2><progress id = "itemCanvas_progress" value="0" max="100"></progress>
                    </div>
                </div>

                <!--<div class="product-other-images">-->
                <!--<a href="javascript:" class="active"><img alt="Berry Lace Dress" src="assets/pages/img/products/model3.jpg"></a>-->
                <!--<a href="javascript:"><img alt="Berry Lace Dress" src="assets/pages/img/products/model4.jpg"></a>-->
                <!--<a href="javascript:"><img alt="Berry Lace Dress" src="assets/pages/img/products/model5.jpg"></a>-->
                <!--</div>-->
            </div>
            <div class="col-md-6 col-sm-6 col-xs-9">
                <h2 id = "item_name">名称</h2>
                <div class="price-availability-block clearfix">
                    <div class="price">
                        <strong id = "item_cur_price"><span>￥</span>47.00</strong>
                        <em>￥<span id = "item_price">62.00</span></em>
                    </div>
                    <div class="availability">
                        <p>品牌: <strong id = "item_brand">宜家</strong>&#12288;&#12288;产地: <strong id = "item_made_in">中国</strong></p>
                        <p>库存: <strong id = "item_stock">充足</strong></p>
                    </div>
                </div>
                <div class="description">
                    <p id = "item_description">这里是一些对家具的相关描述，比如产地，材料，等等一些东西</p>
                </div>
                <br>
                <div class="description">
                    <p>长度: <strong id = "item_length">200cm</strong>&#12288;&#12288;宽度: <strong id = "item_width">80cm</strong>&#12288;&#12288;高度: <strong id = "item_height">60cm </strong></p>
                    <p>重量: <strong id = "item_weight">90kg</strong>&#12288;&#12288;材质: <strong id = "item_materials">棉，紫檀木</strong></p>
                    <p>适用于: <strong id = "item_use_case">客厅</strong></p>
                </div>

                <div class="product-page-options">
                    <div class="pull-left">
                        <label class="control-label">颜色搭配:</label>
                        <select id="cur_style" class="form-control input-sm">
                            <option value="1" onclick="changeModelMtl(1)">搭配1</option>
                            <option value="2" onclick="changeModelMtl(2)">搭配2</option>
                            <option value="3" onclick="changeModelMtl(3)">搭配3</option>
                        </select>
                    </div>
                </div>
                <div class="product-page-cart">
                    <div class="product-quantity">
                        <input id="item_amount" type="text" value="1" readonly name="product-quantity" class="form-control input-sm">
                    </div>
                    <button class="btn btn-primary" type="submit" onclick="addOrderFromFastView()">加入购物车</button>
                    <a href="javascript:goToItemPageFromFastView();" class="btn btn-default">更多详情</a>
                </div>
            </div>

            <div class="sticker sticker-sale"></div>
            <!--<img src="" onload="alert('aaaaa')">-->
        </div>
    </div>
</div>
<!-- END fast view of a product -->

<!-- Load javascripts at bottom, this will reduce page load time -->
<!-- BEGIN CORE PLUGINS(REQUIRED FOR ALL PAGES) -->
<!--[if lt IE 9]>
<script src="assets/plugins/respond.min.js"></script>
<![endif]-->
<script src="assets/plugins/jquery.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/corporate/scripts/back-to-top.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
<script src="assets/plugins/fancybox/source/jquery.fancybox.js" type="text/javascript"></script><!-- pop up -->
<script src="assets/plugins/owl.carousel/owl.carousel.min.js" type="text/javascript"></script><!-- slider for products -->
<script src='assets/plugins/zoom/jquery.zoom.min.js' type="text/javascript"></script><!-- product zoom -->
<script src="assets/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script><!-- Quantity -->
<script src="assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="assets/plugins/rateit/src/jquery.rateit.js" type="text/javascript"></script>

<!-- WebGl Scripts START -->
<script type="text/javascript" src="assets/corporate/scripts/three.js"></script>
<script type="text/javascript" src="assets/corporate/scripts/stats.min.js"></script>
<script type="text/javascript" src="assets/corporate/scripts/Detector.js"></script>
<script type="text/javascript" src="assets/corporate/scripts/OrbitControls.js"></script>
<script type="text/javascript" src="assets/corporate/scripts/MTLLoader.js"></script>
<script type="text/javascript" src="assets/corporate/scripts/OBJLoader.js"></script>
<script type="text/javascript" src="assets/corporate/scripts/MyWebGL.js"></script>
<!-- WebGl Scripts END -->

<script src="assets/corporate/scripts/layout.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        Layout.init();
        Layout.initOWL();
        //Layout.initTwitter();
        Layout.initImageZoom();
        Layout.initTouchspin();
        Layout.initUniform();
    });
</script>
<!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>