var need_refresh;

function validate_required(field) {
    with (field) {
        if (value==null||value=="") {
            return false;
        }
        else {
            return true;
        }
    }
}

function searchCheck(thisform) {
    with (thisform) {
        if (validate_required(keyword) === false) {
            keyword.focus();
            return false;
        }
        else{
            thisform.submit();
        }
    }
}

function deleteOrder(id) {
    if (confirm("确定从购物车中删除该订单？")===true) {
        $.get("delete-order.php", {id: id}, function (txt) {
            if (txt === "0") {
                alert("请稍后再试！");
            }
            else {
                window.location.reload();
            }
        });
    }
}

function addOrderFromList(id, amount, style) {
    id = Number(id);
    $.get("add-order.php", {id: id, amount: amount, style: style}, function (txt) {
        if (txt === "0"){
            alert("请先登录！");
        }
        else if (txt === "1") {
            alert("商品已经添加到购物车！");
            window.location.reload();
        }
        else {
            alert("请稍后重试！");
        }
    });
}

function addOrderFromFastView(model_info) {
    model_info = model_info || cur_model_info;
    var id = model_info.id;
    var amount = document.getElementById("item_amount").value;
    var style = document.getElementById("cur_style").value;

    if(amount<1){
        alert("数量不能为0，请至少选择一件！");
        return;
    }

    $.get("add-order.php", {id: id, amount: amount, style: style}, function (txt) {
        if (txt === "0"){
            alert("请先登录！");
        }
        else if (txt === "1") {
            alert("商品已经添加到购物车！");
            window.location.reload();
        }
        else {
            alert("请稍后重试！");
        }
    });
}

function goToItemPageFromFastView(is_new_window) {
    if(is_new_window)
        window.open("shop-item.php?id="+cur_model_info.id);
    else
        window.location.href="shop-item.php?id="+cur_model_info.id;
}

function addReview(item_id) {
    if(item_id === "" || item_id === null)
        return;

    var review = document.getElementById("review").value;
    var anonymous = document.getElementById("anonymous").checked;
    var score = document.getElementById("review_score").value * 2;

    if(review === "" || review ==null){
        alert("评论内容不能为空！");
        document.getElementById("review").focus();
        return;
    }

    $.get("add-review.php", {item_id: item_id, anonymous: anonymous, review: review, score: score}, function (txt) {
        if (txt === "0"){
            alert("请先登录！");
        }
        else if (txt === "1") {
            alert("评论成功！");
            window.location.reload();
        }
        else if (txt === "2") {
            alert("请先购买该商品再进行评论！");
        }
        else {
            alert("请稍后重试！");
        }
    });

}


function payMoney(money) {

    $.get("pay-money.php", function (txt) {
        if (txt === "0"){
            alert("请先登录！");
        }
        else if (txt === "1") {
            need_refresh = true;
            var elText = "成功支付"+money+"元，感谢选择UGH购物！";
            document.getElementById('QRcode').innerHTML = '';
            var qrcode = new QRCode(document.getElementById("QRcode"), {
                width: 200,
                height: 200
            });
            qrcode.clear();
            qrcode.makeCode(elText);
        }
        else {
            alert("请稍后重试！");
            document.getElementById("QRcode").style.display = 'none';
        }
    });
}

function refreshPage() {
    if(need_refresh){
        need_refresh = false;
        window.location.reload();
    }
}