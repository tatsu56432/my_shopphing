<?php
require_once '../system/define.php';
require_once '../system/functions.php';
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
    <title>admin product-list page top</title>
    <script src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.0.min.js"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="admin products_list">
<div class="container">
    <div class="container__inner">
        <h1 class="">商品一覧管理ページ</h1>

        <div class="formBlock">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="formBlock__item">
                    <label for="product_name">商品名</label>
                    <input type="text" name="product_name" value="<?php if (isset($_POST['product_name'])) {
                        echo $_POST['product_name'];
                    } ?>">
                    <?php if (isset($error['product_name'])) echo '<p class="error">' . $error['product_name'] . '</p>'; ?>
                </div>

                <div class="formBlock__item">
                    <label for="price">値段</label>
                    <input type="text" name="price" id="price" value="<?php if (isset($_POST['price'])) {
                        echo $_POST['price'];
                    } ?>">
                    <?php if (isset($error['price'])) echo '<p class="error">' . $error['price'] . '</p>'; ?>
                </div>

                <div class="formBlock__item">
                    <label for="num">個数</label>
                    <input type="text" name="num" id="num" value="<?php if (isset($_POST['num'])) {
                        echo $_POST['num'];
                    } ?>">
                    <?php if (isset($error['num'])) echo '<p class="error">' . $error['num'] . '</p>'; ?>
                </div>

                <div class="formBlock__item">
                    <label for="image">商品画像</label>
                    <input type="file" name="image" id="image">
                    <?php if (isset($error['image'])) echo '<p class="error">' . $error['image'] . '</p>'; ?>
                </div>

                <div class="formBlock__item">
                    <label for="status">商品ステータス</label>
                    <select name="status" id="status">
                        <option value="open" <?php if ($_POST['status'] === "open") echo "selected" ?> >公開</option>
                        <option value="hidden" <?php if ($_POST['status'] === "hidden") echo "selected" ?>>非公開
                        </option>
                    </select>

                </div>

                <div class="formBlock__item">
                    <input type="submit" id="formBlock" value="商品追加" name="submit">
                </div>
            </form>

        </div>

        <?php if(isset($success_message)) echo "<p style='text-align: center;color: green;margin-bottom: 30px'>". $success_message ."</p>" ;?>
        <?php if(isset($error['stock'])) echo "<p style='text-align: center;color: red;margin-bottom: 30px'>". $error['stock'] ."</p>" ;?>
        <ul class="productsItems">

            <?php

            $id_array = get_target_column($products_info, 'id');
            $name_array = get_target_column($products_info, 'name');
            $price_array = get_target_column($products_info, 'price');
            $drink_img_path_array = get_target_column($products_info, 'img');
            $status_array = get_target_column($products_info, 'status');
            display_productItem_admin($products_info, $id_array, $name_array, $price_array, $drink_img_path_array, $status_array);

            ?>
<!--            <li class="productsItem {$status_class[$i]}">-->
<!--                <dl>-->
<!--                    <dt>商品画像</dt>-->
<!--                    <dd class="thumbnail">-->
<!--                        <p class="thumbnail js-thumbnail"><img src="/assets/img/uploads/rizero.jpg" alt=""></p>-->
<!--                    </dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>商品名</dt>-->
<!--                    <dd><p>Ｒｅ：ゼロから始める異世界生活</p></dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>価格</dt>-->
<!--                    <dd><p>200円</p></dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>在庫数</dt>-->
<!--                    <dd>-->
<!--                        <div class="stock">-->
<!--                            <form action="" method="post">-->
<!--                                <p>-->
<!--                                    <input type="hidden" name="product_stock_id" value="{$id_vars[$i]}">-->
<!--                                    <input type="text" name="num_of_stock_changed" value="">個-->
<!--                                </p>-->
<!---->
<!--                                <input type="submit" name="submit_stock" class="submit_stock" value="在庫数更新">-->
<!--                            </form>-->
<!--                        </div>-->
<!--                    </dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>ステータス</dt>-->
<!--                    <dd>-->
<!--                        <div class="status">-->
<!--                            <form action="" method="post">-->
<!--                                <p>-->
<!--                                    <input type="hidden" name="product_status_id" value="{$id_vars[$i]}">-->
<!--                                    <input type="hidden" name="product_status_value" value="{$status_reverse_value[$i]}">-->
<!--                                </p>-->
<!--                                <p>-->
<!--                                    <button type="submit" name="submit_status" value="submit_status" class="status_btn">公開→非公開</button>-->
<!--                                </p>-->
<!--                            </form>-->
<!--                        </div>-->
<!--                    </dd>-->
<!--                </dl>-->
<!--            </li>-->

        </ul>

    </div>
</div>


</body>
</html>

