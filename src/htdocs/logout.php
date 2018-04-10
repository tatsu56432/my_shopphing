<?php

session_start();
session_regenerate_id(TRUE);
require_once 'system/define.php';
require_once 'system/functions.php';

   $_SESSION = array();
   session_destroy();

   $login_flag =  check_login();
   if($login_flag === false){
       header('location :'. LOGIN_PAGE);
   }

