<?php
session_start();
require_once 'system/define.php';
require_once 'system/functions.php';
$pdo = get_db_connect();

$data = array();
$user_name = isset($_SESSION['login_name']) ? $_SESSION['login_name']: NULL;

$data['user_name'] = $user_name;














$view = view('/cart.php',$data);

echo $view;
