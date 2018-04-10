<?php
require_once  $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";

$login_flag = check_login();
if($login_flag === false){
    header('location:' . LOGIN_PAGE);
}

$data = array();


$view = view('/admin/user.php',$data);

echo $view;
