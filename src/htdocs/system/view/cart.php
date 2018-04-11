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

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/system/view/header.php'; ?>

<div class="container">
    <div class="container__inner">
        <h1>カートの商品一覧</h1>
        　　　　<?php
        if(isset($change_amount)) echo $change_amount;
            ?>
        <ul class="cartItems">
            <?php display_cart_item($cart_list_info); ?>
        </ul>

        <div class="purchaseBlock">
            <button type="submit" class="" name="">購入する</button>
        </div>


    </div>
</div>

</body>
</html>