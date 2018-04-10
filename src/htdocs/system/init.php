<?php
//設定ファイル/session/DB接続などアプリケーションinit時に必要なものをここに記述,必要なら適宜各ページで読み込む
session_start();
session_regenerate_id(TRUE);
require_once  $_SERVER['DOCUMENT_ROOT'] . '/system/define.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/system/functions.php';
$pdo = get_db_connect();
