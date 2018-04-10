<?php
require_once 'system/define.php';
require_once 'system/functions.php';
$pdo = get_db_connect();

$item_id = array();
$item_id = get_itemId_from_cart($pdo,$user_name);
$product_id = get_productId_from_stock($pdo,$item_id);
$cart_list_info = array();
foreach ($product_id as $num){
    foreach ($num as $id){
        array_push($cart_list_info ,get_cart_item_info($pdo,$id));
    }
}


?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>cart page</title>
</head>
<body class="cart ">

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/system/view/header.php';?>

<div class="container">
    <div class="container__inner">
        <h1>カートの商品一覧</h1>

        <ul class="cartItems">
            <?php display_cart_item($cart_list_info);?>
        </ul>

        <div class="purchaseBlock">
            <button type="submit" class="" name="">購入する</button>
        </div>


    </div>
</div>

</body>
</html>