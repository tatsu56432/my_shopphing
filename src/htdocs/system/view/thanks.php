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
<body class="thanks">

<div class="container">
    <div class="container__inner">
        <h1>ご購入ありがとうございました</h1>

        <p class="ttl">購入した商品一覧</p>
        <ul class="purchasedItems">
            <?php display_purchased_item($purchased_items);?>
        </ul>

        <p class="btn_to_top"><a href="/">トップへ戻る</a></p>

<!--        <div class="purchasedResultBox">-->
<!--            <div class="purchasedResultBox__inner">-->
<!--                <p class="purchase_points">購入点数:0商品</p>-->
<!--                <p class="total_purchase_points">購入商品合計数:0点</p>-->
<!--                <p class="total_fee">-->
<!--                    合計金額:0円-->
<!--                </p>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>

</body>
</html>