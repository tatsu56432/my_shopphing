<?php
session_start();
require_once 'system/define.php';
require_once 'system/functions.php';
$pdo = get_db_connect();

$data = array();
$user_name = isset($_SESSION['login_name']) ? $_SESSION['login_name']: NULL;

$cart_info = array();
$cart_info = get_cart_info($pdo,$user_name);

//var_dump($cart_info);







$view = view('/cart.php',$data);

echo $view;
