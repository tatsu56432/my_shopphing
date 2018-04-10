<?php
require_once  $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";

$login_flag = check_login();

if($login_flag === false){
    header('location:' . LOGIN_PAGE);
}

$data = array();
$user_name = isset($_SESSION['login_name']) ? $_SESSION['login_name']: NULL;


$data['user_name'] = $user_name;

$amount_change = isset($_POST['amount_change']) ? $_POST['amount_change'] : NULL;

if($amount_change){

    echo "購入数が変更するボタンが押されました。";

}













$view = view('/cart.php',$data);

echo $view;
