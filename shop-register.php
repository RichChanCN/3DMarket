<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- Head BEGIN -->
<head>
    <meta charset="utf-8">
    <title>UGH新用户注册</title>

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

<div class="main">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="shop-index.php">主页</a></li>
            <li class="active">注册</li>
        </ul>
        <form action="register-result.php" id="register_form" method="post">
            <div id="payment-address-content" class="panel-collapse collapse in">
                <div class="panel-body row">
                    <div class="col-md-6 col-sm-6">
                        <h3>账户信息</h3>
                        <div class="form-group">
                            <label for="nickname">昵称 <span class="require">*</span></label>
                            <input type="text" id="nickname" name="nickname" maxlength="20" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">邮箱 <span class="require">*</span></label>
                            <input type="email" id="email" name="email" maxlength="30" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="telephone">手机 <span class="require">*</span></label>
                            <input type="text" id="telephone" name="telephone" maxlength="11" class="form-control">
                        </div>

                        <h3>登录密码</h3>
                        <div class="form-group">
                            <label for="password">密码 <span class="require">*</span></label>
                            <input type="password" id="password" name="password" maxlength="20" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">密码确认 <span class="require">*</span></label>
                            <input type="password" id="password_confirm" name="password_confirm" maxlength="20" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <h3>地址信息</h3>
                        <div class="form-group">
                            <label for="country">国家 <span class="require">*</span></label>
                            <select class="form-control input-sm" name="country" id="country">
                                <option value="中国">中国</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="province">省份 <span class="require">*</span></label>
                            <select class="form-control input-sm" name="province" id="province">
                                <option value="">请选择省份</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city">城市 <span class="require">*</span></label>
                            <select class="form-control input-sm" name="city" id="city">
                                <option value="">请选择城市</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address">详细地址 <span class="require">*</span></label>
                            <input type="text" id="address" name="address" maxlength="200" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="post_code">邮编</label>
                            <input type="text" id="post_code" name="post_code" maxlength="10" class="form-control">
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> 我希望收到UGH的新品邮件通知
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" checked="checked">我的送货地址和订单地址一致
                            </label>
                        </div>
                        <button class="btn btn-primary  pull-right" type="button" data-toggle="collapse" data-parent="#checkout-page" data-target="#shipping-address-content" id="button-payment-address" onclick="check_register(this.form)">注册</button>
                        <div class="checkbox pull-right">
                            <label>
                                <input type="checkbox">我已阅读并同意<a title="Privacy Policy" href="shop-privacy-policy.html">UGH相关协议规定</a> &nbsp;&nbsp;&nbsp;
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

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
<script src="assets/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->
<script src="assets/plugins/owl.carousel/owl.carousel.min.js" type="text/javascript"></script><!-- slider for products -->
<script src='assets/plugins/zoom/jquery.zoom.min.js' type="text/javascript"></script><!-- product zoom -->
<script src="assets/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script><!-- Quantity -->
<script src="assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>

<script src="assets/corporate/scripts/layout.js" type="text/javascript"></script>
<script src="assets/pages/scripts/checkout.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        Layout.init();
        Layout.initOWL();
        //Layout.initTwitter();
        Layout.initImageZoom();
        Layout.initTouchspin();
        Layout.initUniform();
        Checkout.init();
    });
</script>


<!--行政划分脚本开始-->
<script src="assets/plugins/jquery.min.js"></script>
<script type="text/javascript">
    //填充省份
    $.ajax({
        //OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77
        type : 'GET',//请求类型
        url : 'https://apis.map.qq.com/ws/district/v1/list?key=6WCBZ-J2S3D-6CC4M-HRGUR-MXWVS-H5FBQ&output=jsonp&callback=?',//请求地址，这里的key参数需要自行注册获取应用key，注册地址（https://lbs.qq.com/webservice_v1/guide-region.html）
        data : '',//请求数据，String型
        async : true,//是否为异步
        cache : false,//是否缓存
        dataType:'jsonp',//指定jsonp类型
        success : function(data){
            var json = data['result'][0];
            var provinceHtml = "<option value=''>请选择省份</option>";
            $.each(json, function(idx, obj) {
                provinceHtml += "<option value="+obj.fullname+" province-id="+obj.id+">"+obj.fullname+"</option>";
            });
            $('#province').html(provinceHtml);
        }
    });

    //点击省份填充城市
    $('#province').click(function(){
        var provinceId = $('#province').find("option:selected").attr('province-id');
        $.ajax({
            type : 'GET',//请求类型
            url : 'https://apis.map.qq.com/ws/district/v1/getchildren?key=6WCBZ-J2S3D-6CC4M-HRGUR-MXWVS-H5FBQ&id='+provinceId+'&output=jsonp&callback=?',//请求地址
            data : '',//请求数据，String型
            async : true,//是否为异步
            cache : false,//是否缓存
            dataType:'jsonp',//指定jsonp类型
            success : function(data){
                var json = data['result'][0];

                var provinceHtml = "";
                $.each(json, function(idx, obj) {
                    provinceHtml += "<option value="+obj.fullname+">"+obj.fullname+"</option>";
                });
                $('#city').html(provinceHtml);
                $('#post_code').html(obj.id);
            }
        });
    })

</script>

<script type="text/javascript">
    function validate_required(field,alerttxt) {
        with (field) {
            if (value==null||value=="") {
                alert(alerttxt);
                return false;
            }
            else {
                return true;
            }
        }
    }

    function validate_telephone(field,alerttxt){
        with(field) {
            if(!/^[1][3,4,5,7,8][0-9]{9}$/.test(value)){
                alert(alerttxt);
                return false;
            }
            else{
                return true;
            }
        }
    }

    function validate_password(field1, field2,alerttxt){
        var pwd1,pwd2;
        with(field1) {
            pwd1 = value;
        }
        with(field2){
            pwd2 = value;
            if(pwd1 ==="" || pwd2 ==="")
                alert("请填写密码和确认密码!");
            else if(!(pwd1 === pwd2)){
                alert(alerttxt);
                return false;
            }
            else{
                return true;
            }

        }
    }

    function validate_email(field,alerttxt) {
        with (field) {
            apos=value.indexOf("@")
            dotpos=value.lastIndexOf(".")
            if (apos<1||dotpos-apos<2) {
                alert(alerttxt);
                return false;
            }
            else {
                return true;
            }
        }
    }

    function validate_email_repeat(thisform,field,alerttxt) {
        with (field) {
            $.get("check-email.php",{email: value},function(txt){
                if(txt == "0"){
                    alert(alerttxt);
                    return false;
                }
                else {
                    thisform.submit();
                    return true;
                }
            });
        }
    }

    function check_register(thisform) {
        with (thisform) {
            if (validate_required(nickname,"请填写昵称！") == false) {
                nickname.focus();
                return false;
            }
            else if (validate_email(email,"不是一个有效的邮箱地址！") == false) {
                email.focus();
                return false;
            }
            else if (validate_telephone(telephone,"不是一个有效的手机号码！") == false) {
                telephone.focus();
                return false;
            }
            else if (validate_password(password,password_confirm,"两次密码不一致，请重新输入！") == false) {
                document.getElementById("password").value = "";
                document.getElementById("password_confirm").value = "";
                password.focus();
                return false;
            }
            else if (validate_required(address,"请填写详细地址！") == false) {
                address.focus();
                return false;
            }
            else if (validate_email_repeat(thisform, email, "该邮箱已经被占用！") == false) {
                email.focus();
                return false;
            }

        }
    }

</script>
<!--行政划分脚本结束-->

<!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>