<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/system/init.php";

$ticket = (isset($_POST['ticket'])) ? $_POST['ticket'] : 'no ticket';

//$check_csrf_result = check_csrf();
//if($check_csrf_result === true){
//header('location:' . THANKS_PAGE);
//}

//$computedString = "ticket is, " . $ticket;
$array = ['ticket' => $ticket];
echo json_encode($array);