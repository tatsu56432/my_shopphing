<?php

session_start();

session_regenerate_id(TRUE);

require 'system/functions.php';

check_login();

if (!isset($_SESSION["visited"])) {
    $_SESSION["visited"] = 1;
} else {
    $visited = $_SESSION["visited"];
    $visited++;
    $_SESSION["visited"] = $visited;
}

$data['visited'] = isset($_SESSION['visited']) ? $_SESSION['visited']: NULL;

$view = view('index.php',$data);

echo $view;