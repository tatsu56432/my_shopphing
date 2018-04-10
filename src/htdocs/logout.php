<?php
require_once  $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";

   $_SESSION = array();
   session_destroy();

   $login_flag =  check_login();
   if($login_flag === false){
       header('location:'. LOGIN_PAGE);
   }

