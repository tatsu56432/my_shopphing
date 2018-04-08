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
            <li class="cartItem">
                <div class="cartItem__inner">
                    <p class="thumbnail"><img src="/assets/img/uploads/denpa.jpg" alt=""></p>
                    <dl class="product--name">
                       <dt>商品名</dt>
                       <dd>
                           <p>電波女と青春男</p>
                       </dd>
                    </dl>
                    <dl class="product--price">
                        <dt>値段</dt>
                        <dd>
                            <p>1200円</p>
                        </dd>
                    </dl>
                    <dl class="product--amount">
                        <dt>購入数</dt>
                        <dd>
                            <div>
                            <form action="" method="post">
                                <select name="product--amount" id="product--amount">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                                <button type="submit" name="amount_change" class="amount_change_btn">数量を変更</button>
                            </form>
                            </div>
                        </dd>
                    </dl>
                    <div class="product--delete">
                        <form action="" method="post">
                            <button type="submit" class="product_change_btn" name="product_change">削除する</button>
                        </form>
                    </div>
                </div>
            </li>
            <li class="cartItem">
                <div class="cartItem__inner">
                    <p class="thumbnail"><img src="/assets/img/uploads/main_visual_sp.jpg" alt=""></p>
                    <dl class="product--name">
                        <dt>商品名</dt>
                        <dd>
                            <p>電波女と青春男</p>
                        </dd>
                    </dl>
                    <dl class="product--price">
                        <dt>値段</dt>
                        <dd>
                            <p>1200円</p>
                        </dd>
                    </dl>
                    <dl class="product--amount">
                        <dt>購入数</dt>
                        <dd>
                            <div>
                                <form action="" method="post">
                                    <select name="product--amount" id="product--amount">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                    <button type="submit" name="amount_change" class="amount_change_btn">数量を変更</button>
                                </form>
                            </div>
                        </dd>
                    </dl>
                    <div class="product--delete">
                        <form action="" method="post">
                            <button type="submit" class="product_change_btn" name="product_change">削除する</button>
                        </form>
                    </div>
                </div>
            </li>
            <li class="cartItem">
                <div class="cartItem__inner">
                    <p class="thumbnail"><img src="/assets/img/uploads/rizero.jpg" alt=""></p>
                    <dl class="product--name">
                        <dt>商品名</dt>
                        <dd>
                            <p>電波女と青春男</p>
                        </dd>
                    </dl>
                    <dl class="product--price">
                        <dt>値段</dt>
                        <dd>
                            <p>1200円</p>
                        </dd>
                    </dl>
                    <dl class="product--amount">
                        <dt>購入数</dt>
                        <dd>
                            <div>
                                <form action="" method="post">
                                    <select name="product--amount" id="product--amount">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                    <button type="submit" name="amount_change" class="amount_change_btn">数量を変更</button>
                                </form>
                            </div>
                        </dd>
                    </dl>
                    <div class="product--delete">
                        <form action="" method="post">
                            <button type="submit" class="product_change_btn" name="product_change">削除する</button>
                        </form>
                    </div>
                </div>
            </li>
        </ul>

        <div></div>


    </div>
</div>

</body>
</html>