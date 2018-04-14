<?php
require_once  $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";

$login_flag = check_login();

if($login_flag === false){
    header('location:' . LOGIN_PAGE);
}

$products_info = get_product_info($pdo);
$data['products_info'] = $products_info;



$_POST = escape($_POST);
$purchase_btn = isset($_POST['purchase_btn']) ? $_POST['purchase_btn'] : NULL;
$login_name = isset($_SESSION['login_name']) ? $_SESSION['login_name'] : NULL;

//現在のカートの中の購入数を表示
$cart_sum_amount_result = get_cart_sum_amount($pdo,$login_name);
$data['cart_sum_amount_result'] = $cart_sum_amount_result;

if($purchase_btn){
    $post_data=array(
        'login_name'=>$login_name,
        'product_id'=>$purchase_btn
    );

    $insert_or_update_result = insert_or_update_cart($pdo,$post_data);
    //カートに入れるボタンを押したらカート表示を上書き
    $cart_sum_amount_result = get_cart_sum_amount($pdo,$login_name);
    $data['cart_sum_amount_result'] = $cart_sum_amount_result;
//    if($insert_or_update_result===true){
//        header('location:'.TOP_PAGE);
//    }

}

$view = view('index.php',$data);

echo $view;