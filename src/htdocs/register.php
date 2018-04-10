<?php
require_once  $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";


$_POST = escape($_POST);
$submit_register = isset($_POST['submit_register']) ? $_POST['submit_register'] : NULL;
$data = array();

if ($submit_register) {

    $login_name = isset($_POST['login_name']) ? $_POST['login_name'] : NULL;
    $password = isset($_POST['password']) ? $_POST['password'] : NULL;


    $post_data = array(
        'login_name' => $login_name,
        'password' => $password
    );

    $error = validate_ID_PASS($post_data);
//    var_dump($error);

    if (count($error) <= 0) {
        $register_result = register_user($pdo, $post_data);
        if($register_result === true){
            $data['register_result'] = true;
        }else{
            $data['register_result'] = false;
        }
    } else {
        $data['error'] = $error;
    }
}

$view = view('register.php', $data);

echo $view;
