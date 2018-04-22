<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";
session_regenerate_id(TRUE);

$login_flag = check_login();

if ($login_flag === false) {
    header('location:' . LOGIN_PAGE);
}

$data = array();
$login_name = isset($_SESSION['login_name']) ? $_SESSION['login_name'] : NULL;

//現在のカートの中の購入商品合計数を取得
$cart_sum_amount_result = get_cart_sum_amount($pdo, $login_name);
//現在のカートの中の購入点数を取得
$purchase_points = get_cart_record($pdo, $login_name);
//現在のカートの中の合計金額を取得
$cart_total_fee = get_cart_total_fee($pdo, $login_name);
$data['cart_sum_amount_result'] = $cart_sum_amount_result;
$data['purchase_points'] = $purchase_points;
$data['cart_total_fee'] = $cart_total_fee;

//カートの商品一覧の情報諸々を頑張って取得する。もっとスマートにできる？
$item_id = array();
$item_id = get_itemId_from_cart($pdo, $login_name);
$product_id = get_productId_array_from_stock($pdo, $item_id);
$product_ids = get_productIds($product_id);

$cart_list_info = array();
foreach ($product_ids as $product_id) {
    array_push($cart_list_info, get_cart_item_info($pdo, $login_name, $product_id));
}
$data['cart_list_info'] = $cart_list_info;


$_POST = escape($_POST);
$amount_change = isset($_POST['amount_change']) ? $_POST['amount_change'] : NULL;
$submit_delete = isset($_POST['product_delete']) ? $_POST['product_delete'] : NULL;
$product_amount = isset($_POST['product_amount']) ? $_POST['product_amount'] : NULL;

if ($amount_change) {
    $post_data['cart_id'] = $amount_change;
    $post_data['product_amount'] = $product_amount;
    $post_data['user_name'] = $login_name;
    $result = update_cart_info($pdo, $post_data);

    if ($result === true) {
        header('location:' . CART_PAGE);
    }
}

if ($submit_delete) {

    $check_delete_value = validate_delete_cart_value($pdo, $login_name, $submit_delete);

    if ($check_delete_value === true) {
        $result = delete_cart_item($pdo, $submit_delete);
        if ($result === true) {
            header('location:' . CART_PAGE);
        }
    } else {
        $data['delete_error'] = true;
    }

}

if(!isset($_SESSION['ticket'])){
    //$_SESSION['ticket']がセットされていなければ、トークンを生成して代入
    $_SESSION['ticket'] = sha1(uniqid(mt_rand(), TRUE));
}

$data['ticket'] = $_SESSION['ticket'];


$view = view('/cart.php', $data);

echo $view;
