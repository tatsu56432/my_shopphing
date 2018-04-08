<?php
require_once 'system/define.php';
require_once 'system/functions.php';
$pdo = get_db_connect();
$products_info = get_product_info($pdo);
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="/assets/js/shared.js"></script>
    <link rel="stylesheet" href="/assets/css/style.css">

    <title>my shoppimg site</title>
</head>
<body class="index">

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/system/view/header.php';?>

<div class="container">
    <div class="container__inner">

        <div class="l-container--product">
            <ul class="productsItems">

                <?php
                $id_array = get_target_column($products_info, 'id');
                $name_array = get_target_column($products_info, 'name');
                $price_array = get_target_column($products_info, 'price');
                $drink_img_path_array = get_target_column($products_info, 'img');
                $status_array = get_target_column($products_info, 'status');
                $num_of_stock = get_target_column($products_info, 'stock');
                display_productItem_index($products_info, $id_array, $name_array, $price_array, $drink_img_path_array, $status_array, $num_of_stock);
                ?>
            </ul>

        </div>
    </div>
</div>


<footer class="footer">
    <div class="footer__inner">


    </div>
</footer>

</body>
</html>