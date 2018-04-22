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

$purchased_items = get_purchased_item($pdo,$login_name);

$data['purchased_items'] = $purchased_items;


$data['cart_list_info'] = $cart_list_info;

$result = insert_purchase_history($pdo,$login_name);

if($result === true){
    $purchase_complete = delete_user_cart_item($pdo,$login_name);
    if($purchase_complete === true){
        get_purchased_item($pdo,$login_name);
        //$data['thanks_message'] = 'ご購入ありがとうございました。';
    }
}


$view = view('/thanks.php', $data);

echo $view;