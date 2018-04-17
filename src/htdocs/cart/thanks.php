<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";

$login_flag = check_login();

if ($login_flag === false) {
    header('location:' . LOGIN_PAGE);
}

$login_name = isset($_SESSION['login_name']) ? $_SESSION['login_name'] : NULL;

$item_id = array();
$item_id = get_itemId_from_cart($pdo, $login_name);
$product_id = get_productId_array_from_stock($pdo, $item_id);
$product_ids = get_productIds($product_id);

$cart_list_info = array();
foreach ($product_ids as $product_id) {
    array_push($cart_list_info, get_cart_item_info($pdo, $login_name, $product_id));
}
$data['cart_list_info'] = $cart_list_info;

$result = insert_purchase_history($pdo,$login_name);

if($result === true){
    echo "success insert";
}


$view = view('/thanks.php', $data);

echo $view;