<?php

session_start();
require_once 'system/define.php';
require_once 'system/functions.php';


if (isset($_SESSION['login_name'])) {

    $_SESSION = array();
    session_destroy();
    check_login();

}
