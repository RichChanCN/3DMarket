<?php session_start() ?>
<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- Head BEGIN -->
<head>
    <meta charset="utf-8">
    <title>快回家家居</title>

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
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css"><!--- fonts for slider on the index page -->
    <!-- Fonts END -->

    <!-- Global styles START -->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Global styles END -->

    <!-- Page level plugin styles START -->
    <link href="assets/pages/css/animate.css" rel="stylesheet">
    <link href="assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
    <link href="assets/plugins/owl.carousel/assets/owl.carousel.css" rel="stylesheet">
    <!-- Page level plugin styles END -->

    <!-- Theme styles START -->
    <link href="assets/pages/css/components.css" rel="stylesheet">
    <link href="assets/pages/css/slider.css" rel="stylesheet">
    <link href="assets/pages/css/style-shop.css" rel="stylesheet" type="text/css">
    <link href="assets/corporate/css/style.css" rel="stylesheet">
    <link href="assets/corporate/css/style-responsive.css" rel="stylesheet">
    <link href="assets/corporate/css/themes/red.css" rel="stylesheet" id="style-color">
    <link href="assets/corporate/css/custom.css" rel="stylesheet">
    <!-- Theme styles END -->

</head>
<!-- Head END -->

<!-- Body BEGIN -->
<body class="ecommerce">
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
                        require_once "my-tool.php";
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

<!-- BEGIN SLIDER -->
<div class="page-slider margin-bottom-35">
    <div id="carousel-example-generic" class="carousel slide carousel-slider">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
            <li data-target="#carousel-example-generic" data-slide-to="3"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <!-- First slide -->
            <div class="item carousel-item-four active">
                <div class="container">
                    <div class="carousel-position-four text-center">
                        <h2 class="margin-bottom-20 animate-delay carousel-title-v3 border-bottom-title text-uppercase" data-animation="animated fadeInDown">
                            遇 见 <br/><span class="color-red-v2">世界·好家具</span><br/>
                        </h2>
                        <p class="carousel-subtitle-v2" data-animation="animated fadeInUp">精挑细选，匠心质造，优质售后<br/>
                            打造世界家居网</p>
                    </div>
                </div>
            </div>

            <!-- Second slide -->
            <div class="item carousel-item-five">
                <div class="container">
                    <div class="carousel-position-four text-center">
                        <h2 class="animate-delay carousel-title-v4" data-animation="animated fadeInDown">
                            Unlimted
                        </h2>
                        <p class="carousel-subtitle-v2" data-animation="animated fadeInDown">
                            Layout Options
                        </p>
                        <p class="carousel-subtitle-v3 margin-bottom-30" data-animation="animated fadeInUp">
                            Fully Responsive
                        </p>
                        <a class="carousel-btn" href="#" data-animation="animated fadeInUp">See More Details</a>
                    </div>
                    <img class="carousel-position-five animate-delay hidden-sm hidden-xs" src="assets/pages/img/shop-slider/slide2/price.png" alt="Price" data-animation="animated zoomIn">
                </div>
            </div>

            <!-- Third slide -->
            <div class="item carousel-item-six">
                <div class="container">
                    <div class="carousel-position-four text-center">
                            <span class="carousel-subtitle-v3 margin-bottom-15" data-animation="animated fadeInDown">
                                Full Admin &amp; Frontend
                            </span>
                        <p class="carousel-subtitle-v4" data-animation="animated fadeInDown">
                            eCommerce UI
                        </p>
                        <p class="carousel-subtitle-v3" data-animation="animated fadeInDown">
                            Is Ready For Your Project
                        </p>
                    </div>
                </div>
            </div>

            <!-- Fourth slide -->
            <div class="item carousel-item-seven">
                <div class="center-block">
                    <div class="center-block-wrap">
                        <div class="center-block-body">
                            <h2 class="carousel-title-v1 margin-bottom-20" data-animation="animated fadeInDown">
                                The most <br/>
                                wanted bijouterie
                            </h2>
                            <a class="carousel-btn" href="#" data-animation="animated fadeInUp">But It Now!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control carousel-control-shop" href="#carousel-example-generic" role="button" data-slide="prev">
            <i class="fa fa-angle-left" aria-hidden="true"></i>
        </a>
        <a class="right carousel-control carousel-control-shop" href="#carousel-example-generic" role="button" data-slide="next">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
    </div>
</div>
<!-- END SLIDER -->

<?php
require_once 'my-tool.php';
echo "<script type=\"text/javascript\" src=\"assets/config/item_config.js\"></script>";
echo "<script type=\"text/javascript\" >clearMainPageItemList()</script>";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
if ($conn->connect_error) {
    die("连接数据库失败: " . $conn->connect_error);
}

global $main_page_item;
//验证内容是否与数据库的记录吻合。
$sql = "SELECT * FROM item WHERE (is_special='1')";
//执行上面的sql语句并将结果集赋给result。
$result = $conn->query($sql);
//判断结果集的记录数是否大于0
if ($result->num_rows > 0) {
    $item_count = 0;
    // 输出数据
    while($row = $result->fetch_assoc()) {
        $main_page_item[$item_count] = new Item($row["id"],$row["use_case"],$row["type"],$row["name"],
            $row["price"],$row["discount"],$row["length"],$row["width"],
            $row["height"],$row["area"],$row["brand"],$row["materials"],
            $row["description"],$row["made_in"],$row["stock"],$row["style_num"],$row["is_new"],$row["style"],$row["introduction"]);
        echo "<script type=\"text/javascript\">main_page_item_list[\"$item_count\"] = new ItemInfo(\"$row[id]\",\"$row[use_case]\",\"$row[type]\",\"$row[name]\",
                \"$row[price]\",\"$row[discount]\",\"$row[length]\",\"$row[width]\",
                \"$row[height]\",\"$row[area]\",\"$row[brand]\",\"$row[materials]\",
                \"$row[description]\",\"$row[made_in]\",\"$row[stock]\",\"$row[style_num]\",\"$row[is_new]\",\"$row[style]\",\"$row[introduction]\")</script>";
        $item_count += 1;
    }
}
$conn->close();

?>


<div class="main">
    <div class="container">
        <!-- BEGIN SALE PRODUCT & NEW ARRIVALS -->
        <div class="row margin-bottom-40">
            <!-- BEGIN SALE PRODUCT -->
            <div class="col-md-12 sale-product">
                <h2>新品上架</h2>
                <div class="owl-carousel owl-carousel5">
                    <?php
                    for ($x=5; $x<10; $x++) {
                        echo "
                            <div>
                                <div class=\"product-item\">
                                    <div class=\"pi-img-wrapper\">
                                        <img src=\"assets/pages/img/items/".$main_page_item[$x]->id.".jpg\" class=\"img-responsive\" alt=\"".$main_page_item[$x]->id."\">
                                        <div>
                                            <a href=\"#product-big-3d-view\" class=\"btn btn-default fancybox-button\" onclick=\"changeItemInfo(main_page_item_list[".$x."],'itemBigCanvas')\">预览</a>
                                            <a href=\"#product-pop-up\" class=\"btn btn-default fancybox-fast-view\" onclick=\"changeItemInfo(main_page_item_list[".$x."],'itemCanvas')\">详情</a>
                                        </div>
                                    </div>
                                    <h3><a href=\"shop-item.php?id=".$main_page_item[$x]->id."\">".$main_page_item[$x]->name."</a></h3>
                                    <div class=\"pi-price\">￥".$main_page_item[$x]->getCurrentPrice()."</div>
                                    <a href=\"javascript:addOrderFromList(".$main_page_item[$x]->id.",1,1);\" class=\"btn btn-default add2cart\">加入购物车</a>
                                    <div class=\"sticker sticker-new\"></div>
                                    <!--<div class=\"sticker sticker-sale\"></div>-->
                                </div>
                            </div>
                            ";
                    }
                    ?>
                </div>
            </div>
            <!-- END SALE PRODUCT -->
        </div>
        <!-- END SALE PRODUCT & NEW ARRIVALS -->

        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40 ">
            <!-- BEGIN SIDEBAR -->
            <div class="sidebar col-md-3 col-sm-4">
                <ul class="list-group margin-bottom-25 sidebar-menu">
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=现代"><i class="fa fa-angle-right"></i> 现代</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=复古"><i class="fa fa-angle-right"></i> 复古</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=创意"><i class="fa fa-angle-right"></i> 创意</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=儿童"><i class="fa fa-angle-right"></i> 儿童</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=奢华"><i class="fa fa-angle-right"></i> 奢华</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=简约"><i class="fa fa-angle-right"></i> 简约</a></li>
                    <li class="list-group-item clearfix"><a href="shop-product-list.php?type=其他"><i class="fa fa-angle-right"></i> 其他</a></li>
                </ul>
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="col-md-9 col-sm-8">
                <h2>客厅三件套</h2>
                <div class="owl-carousel owl-carousel3">
                    <?php
                    for ($x=0; $x<3; $x++) {
                        $sale_or_new = "";
                        if ($main_page_item[$x]->is_new)
                            $sale_or_new = $sale_or_new."<div class=\"sticker sticker-new\"></div>";
                        if ($main_page_item[$x]->discount<1)
                            $sale_or_new = $sale_or_new."<div class=\"sticker sticker-sale\"></div>";

                        echo "
                            <div>
                                <div class=\"product-item\">
                                    <div class=\"pi-img-wrapper\">
                                        <img src=\"assets/pages/img/items/".$main_page_item[$x]->id."_small.jpg\" class=\"img-responsive\" alt=\"".$main_page_item[$x]->id."\">
                                        <div>
                                            <a href=\"#product-big-3d-view\" class=\"btn btn-default fancybox-button\" onclick=\"changeItemInfo(main_page_item_list[".$x."],'itemBigCanvas')\">预览</a>
                                            <a href=\"#product-pop-up\" class=\"btn btn-default fancybox-fast-view\" onclick=\"changeItemInfo(main_page_item_list[".$x."],'itemCanvas')\">详情</a>
                                        </div>
                                    </div>
                                    <h3><a href=\"shop-item.php?id=".$main_page_item[$x]->id."\">".$main_page_item[$x]->name."</a></h3>
                                    <div class=\"pi-price\">￥".$main_page_item[$x]->getCurrentPrice()."</div>
                                    <a href=\"javascript:addOrderFromList(".$main_page_item[$x]->id.",1,1);\" class=\"btn btn-default add2cart\">加入购物车</a>
                                    ".$sale_or_new."
                                </div>
                            </div>
                            ";
                    }
                    ?>
                </div>
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->

        <!-- BEGIN TWO PRODUCTS & PROMO -->
        <div class="row margin-bottom-35 ">
            <!-- BEGIN TWO PRODUCTS -->
            <div class="col-md-6 two-items-bottom-items">
                <h2>卧室两件套</h2>
                <div class="owl-carousel owl-carousel2">
                    <?php
                    for ($x=3; $x<5; $x++) {
                        $sale_or_new = "";
                        if ($main_page_item[$x]->is_new)
                            $sale_or_new = $sale_or_new."<div class=\"sticker sticker-new\"></div>";
                        if ($main_page_item[$x]->discount<1)
                            $sale_or_new = $sale_or_new."<div class=\"sticker sticker-sale\"></div>";

                        echo "
                            <div>
                                <div class=\"product-item\">
                                    <div class=\"pi-img-wrapper\">
                                        <img src=\"assets/pages/img/items/".$main_page_item[$x]->id."_small.jpg\" class=\"img-responsive\" alt=\"".$main_page_item[$x]->id."\">
                                        <div>
                                            <a href=\"#product-big-3d-view\" class=\"btn btn-default fancybox-button\" onclick=\"changeItemInfo(main_page_item_list[".$x."],'itemBigCanvas')\">预览</a>
                                            <a href=\"#product-pop-up\" class=\"btn btn-default fancybox-fast-view\" onclick=\"changeItemInfo(main_page_item_list[".$x."],'itemCanvas')\">详情</a>
                                        </div>
                                    </div>
                                    <h3><a href=\"shop-item.php?id=".$main_page_item[$x]->id."\">".$main_page_item[$x]->name."</a></h3>
                                    <div class=\"pi-price\">￥".$main_page_item[$x]->getCurrentPrice()."</div>
                                    <a href=\"javascript:addOrderFromList(".$main_page_item[$x]->id.",1,1);\" class=\"btn btn-default add2cart\">加入购物车</a>
                                    ".$sale_or_new."
                                </div>
                            </div>
                            ";
                    }
                    ?>
                </div>
            </div>
            <!-- END TWO PRODUCTS -->
            <!-- BEGIN PROMO -->
            <div class="col-md-6 shop-index-carousel">
                <div class="content-slider">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#myCarousel" data-slide-to="1"></li>
                            <li data-target="#myCarousel" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="assets/pages/img/index-sliders/slider1.jpg" class="img-responsive" alt="Berry Lace Dress">
                            </div>
                            <div class="item">
                                <img src="assets/pages/img/index-sliders/slider2.jpg" class="img-responsive" alt="Berry Lace Dress">
                            </div>
                            <div class="item">
                                <img src="assets/pages/img/index-sliders/slider3.jpg" class="img-responsive" alt="Berry Lace Dress">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROMO -->
        </div>
        <!-- END TWO PRODUCTS & PROMO -->
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
        <div class="row" id="fast_page">
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
                        <p>库存: <strong id = "item_stock">充足</strong>&#12288;&#12288;风格: <strong id = "item_style">简约</strong></p>
                    </div>
                </div>
                <div class="description">
                    <p id = "item_description">这里是一些对家具的相关描述，比如产地，材料，等等一些东西</p>
                </div>
                <br>
                <div class="description">
                    <p>长度: <strong id = "item_length">200cm</strong>&#12288;&#12288;宽度: <strong id = "item_width">80cm</strong>&#12288;&#12288;高度: <strong id = "item_height">60cm </strong></p>
                    <p>占地: <strong id = "item_weight">9㎡</strong>&#12288;&#12288;材质: <strong id = "item_materials">棉，紫檀木</strong></p>
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
            <div id="sale_tag" class="sticker sticker-sale"></div>
        </div>
    </div>
</div>
<!-- END fast view of a product -->

<!-- Load javascripts at bottom, this will reduce page load time -->
<!-- BEGIN CORE PLUGINS (REQUIRED FOR ALL PAGES) -->
<!--[if lt IE 9]> -->
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
<script src="assets/pages/scripts/bs-carousel.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        Layout.init();
        Layout.initOWL();
        Layout.initImageZoom();
        Layout.initTouchspin();
        //Layout.initTwitter();
    });
</script>
<!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>