<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";

$login_flag = check_login();

if ($login_flag === false) {
    header('location:' . LOGIN_PAGE);
}

$data = array();
$user_name = isset($_SESSION['login_name']) ? $_SESSION['login_name'] : NULL;


//カートの商品一覧の情報諸々を頑張って取得する。もっとスマートにできる？
$item_id = array();
$item_id = get_itemId_from_cart($pdo, $user_name);
$product_id = get_productId_array_from_stock($pdo, $item_id);
$product_ids = get_productIds($product_id);

$cart_list_info = array();
foreach ($product_ids as $product_id){
    array_push($cart_list_info,get_cart_item_info($pdo, $user_name,$product_id));
}

$data['cart_list_info'] = $cart_list_info;

$_POST = escape($_POST);
$amount_change = isset($_POST['amount_change']) ? $_POST['amount_change'] : NULL;
$product_amount = isset($_POST['product_amount']) ? $_POST['product_amount'] : NULL;
if ($amount_change) {

    $post_data['stock_id'] = $amount_change;
    $post_data['product_amount'] = $product_amount;
    $post_data['user_name'] = $user_name;

    $result = update_cart_info($pdo, $post_data);

    if ($result === true) {
        echo "データの更新に成功しました。";
    }


}


$view = view('/cart.php', $data);

echo $view;
