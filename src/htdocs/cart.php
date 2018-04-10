<?php
require_once  $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";

$login_flag = check_login();

if($login_flag === false){
    header('location:' . LOGIN_PAGE);
}

$data = array();
$user_name = isset($_SESSION['login_name']) ? $_SESSION['login_name']: NULL;


//カートの商品一覧の情報諸々を頑張って取得する。もっとスマートにできる？
$item_id = array();
$item_id = get_itemId_from_cart($pdo,$user_name);
$product_id = get_productId_from_stock($pdo,$item_id);
$cart_list_info = array();
foreach ($product_id as $num){
    foreach ($num as $id){
        array_push($cart_list_info ,get_cart_item_info($pdo,$id));
    }
}

$data['cart_list_info'] = $cart_list_info;

$amount_change = isset($_POST['amount_change']) ? $_POST['amount_change'] : NULL;

if($amount_change){

    echo "購入数が変更するボタンが押されました。";

}













$view = view('/cart.php',$data);

echo $view;
