<?php
session_start();
require_once 'system/define.php';
require_once 'system/functions.php';

$data = array();




$view = view('/cart.php',$data);

echo $view;
