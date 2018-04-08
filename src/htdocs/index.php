<?php
session_start();
session_regenerate_id(TRUE);
require 'system/define.php';
require 'system/functions.php';

$pdo = get_db_connect();

check_login();
if (!isset($_SESSION["visited"])) {
    $_SESSION["visited"] = 1;
} else {
    $visited = $_SESSION["visited"];
    $visited++;
    $_SESSION["visited"] = $visited;
}

$data['visited'] = isset($_SESSION['visited']) ? $_SESSION['visited']: NULL;

$_POST = escape($_POST);
$purchase_btn = isset($_POST['purchase_btn']) ? $_POST['purchase_btn'] : NULL;
$login_name = isset($_SESSION['login_name']) ? $_SESSION['login_name'] : NULL;

if($purchase_btn){

    var_dump($_SESSION['login_name']);

    $post_data=array(
        'login_name'=>$login_name,
        'product_id'=>$purchase_btn
    );

    insert_cart($pdo,$post_data);


}


$view = view('index.php',$data);

echo $view;