<?php
session_start();

require_once 'system/functions.php';

$pdo = get_db_connect();
$_POST = escape($_POST);
$submit_login = isset($_POST['submit_login']) ? $_POST['submit_login'] : NULL ;
$data = array();

if($submit_login){

    $login_name = isset($_POST['login_name']) ? $_POST['login_name'] : NULL ;
    $password = isset($_POST['password']) ? $_POST['password'] : NULL ;

    $post_data = array(
        'login_name' => $login_name,
        'password' => $password,
    );

    $error = validate_ID_PASS($post_data);

    if(count($error) <= 0){

        $user_flag = check_login_user($pdo,$post_data);
        if($user_flag === true){
            $_SESSION['login_name'] = $login_name;
            header('location:' . TOP_PAGE);
        }else{
            $data['unmatch'] = "ユーザーIDもしくはパスワードが一致しません";
        }

    }else{
        $data['error'] = $error;
    }

}

$view = view('login.php',$data);

echo $view;
