<?php
session_start();
session_regenerate_id(TRUE);

require_once 'system/functions.php';

$data['visited'] = isset($_SESSION['visited']) ? $_SESSION['visited']: NULL;

$view = view('login.php',$data);

echo $view;
