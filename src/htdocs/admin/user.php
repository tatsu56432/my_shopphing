<?php

require_once '../system/define.php';
require_once '../system/functions.php';

$data = array();




$view = view('/admin/user.php',$data);

echo $view;
