<?php
require_once  $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";

$login_flag = check_login();

if($login_flag === false){
    header('location:' . LOGIN_PAGE);
}

$_POST = escape($_POST);
$purchase_btn = isset($_POST['purchase_btn']) ? $_POST['purchase_btn'] : NULL;
$login_name = isset($_SESSION['login_name']) ? $_SESSION['login_name'] : NULL;

if($purchase_btn){

    $post_data=array(
        'login_name'=>$login_name,
        'product_id'=>$purchase_btn
    );

    insert_or_update_cart($pdo,$post_data);

}

$view = view('index.php',$data);

echo $view;