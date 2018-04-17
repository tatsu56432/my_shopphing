<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>thanks page</title>
</head>
<body class="cart ">


<div class="container">
    <div class="container__inner">
        <h1>購入した商品一覧</h1>
        <ul class="cartItems">
            <?php display_cart_item($cart_list_info); ?>
        </ul>

    </div>
</div>

</body>
</html>