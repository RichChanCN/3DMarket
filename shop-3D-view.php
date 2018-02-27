<?php
session_start();
require_once 'my-tool.php';
echo "<script type=\"text/javascript\" src=\"assets/config/item_config.js\"></script>";
echo "<script type=\"text/javascript\" >clearSceneItemList()</script>";

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
        echo "<script type=\"text/javascript\">scene_item_list[\"$item_count\"] = new ItemInfo(\"$row[id]\",\"$row[use_case]\",\"$row[type]\",\"$row[name]\",
                \"$row[price]\",\"$row[discount]\",\"$row[length]\",\"$row[width]\",
                \"$row[height]\",\"$row[area]\",\"$row[brand]\",\"$row[materials]\",
                \"$row[description]\",\"$row[made_in]\",\"$row[stock]\",\"$row[style_num]\",\"$row[is_new]\",\"$row[style]\",\"$row[introduction]\")</script>";
        $item_count += 1;
    }
}
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>UGH-3D浏览</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
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
    <style>
        html, body {
            width: 100%;
            height: 100%;
        }
        body {
            background-color: #ffffff;
            margin: 0;
            overflow: hidden;
            font-family: arial;
        }
        #blocker {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        #instructions {
            width: 100%;
            height: 100%;
            display: -webkit-box;
            display: -moz-box;
            display: box;
            -webkit-box-orient: horizontal;
            -moz-box-orient: horizontal;
            box-orient: horizontal;
            -webkit-box-pack: center;
            -moz-box-pack: center;
            box-pack: center;
            -webkit-box-align: center;
            -moz-box-align: center;
            box-align: center;
            color: #ffffff;
            text-align: center;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div id="blocker">

    <div id="instructions">
        <span id="progress" style="font-size:40px">点击开始游览</span>
        <br />
        (W, A, S, D = 移动, 空格 = 选择, 鼠标 = 转动视角)
    </div>
</div>
<a id="show_fast_view" style="display: none;" href="#product-pop-up" class="btn btn-default fancybox-fast-view">详情</a>
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
                    <a href="javascript:goToItemPageFromFastView(true);" class="btn btn-default">更多详情</a>
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
<script type="text/javascript" src="assets/corporate/scripts/PointerLockControls.js"></script>
<script type="text/javascript" src="assets/corporate/scripts/3DScene.js"></script>
<script type="text/javascript" src="shopping-operate.js"></script>

<script src="assets/corporate/scripts/layout.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        Layout.init();
        Layout.initOWL();
        //Layout.initTwitter();
        Layout.initImageZoom();
        Layout.initTouchspin();
        Layout.initUniform();
        Layout.initSliderRange();
    });
</script>

</body>
</html>