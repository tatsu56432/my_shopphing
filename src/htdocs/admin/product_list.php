<?php

require_once '../system/define.php';
require_once '../system/functions.php';

$data = array();


$view = view('/admin/product_list.php',$data);

echo $view;
