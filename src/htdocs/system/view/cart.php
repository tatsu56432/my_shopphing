<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>cart page</title>
    <!--Express Checkoutのフロントエンドの処理は書きのcheckout.jsより提供される-->
    <script src="//www.paypalobjects.com/api/checkout.js"></script>
</head>
<body class="cart ">

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/system/view/header.php'; ?>



<div class="container">
    <div class="container__inner">
        <h1>カートの商品一覧</h1>
        <?php if($delete_error === true) echo '<p class="error">送信された値が不正です。</p>' ; ?>
        <ul class="cartItems">
            <?php display_cart_item($cart_list_info); ?>
        </ul>

        <?php  display_cart_result($purchase_points,$cart_sum_amount_result,$cart_total_fee);?>

    </div>
</div>

<?php paypal_settlemen($cart_total_fee); ?>

</body>
</html>