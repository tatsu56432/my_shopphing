<?php
session_start();
session_regenerate_id(TRUE);
require_once  $_SERVER['DOCUMENT_ROOT'] . '/system/define.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/functions.php';
$pdo = get_db_connect();