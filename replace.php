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