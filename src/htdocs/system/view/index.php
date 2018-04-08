<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src ="/assets/js/shared.js"></script>
    <link rel="stylesheet" href="/assets/css/style.css">

    <title>my shoppimg site</title>
</head>
<body class="index">

<header class="header">
    <div class="header__inner">
        <p class="header--ttl">my shopping site</p>

        <div class="userBlock">
            <p class="userName">ようこそ！<?php if(isset($_SESSION['login_name'])) echo $_SESSION['login_name'] ?>さん</p>
            <p class="icon--curt"><a href=""><img src="/assets/img/header/icon--cart.png" alt="カート"></a></p>
            <p class="logout"><a href="logout.php">ログアウト</a></p>
        </div>
    </div>

</header>
<div class="container">
    <div class="container__inner">

        <p class="">商品一覧</p>
        <div class="l-container--product">
            <ul class="productsItems">
                <li class="productsItem">
                    <div class="productsItem__inner">
                        <p class="thumbnail"><img src="/assets/img/uploads/main_visual_sp.jpg" alt=""></p>
                        <p class="product--name">slow start</p>
                        <p class="product--price">200円</p>
                        <p class="product--status is-soldout">売り切れ</p>
                    </div>
                </li>
                <li class="productsItem">
                    <div class="productsItem__inner">
                        <p class="thumbnail"><img src="/assets/img/uploads/kemono.jpg" alt=""></p>
                        <p class="product--name">けものふれんず</p>
                        <p class="product--price">200円</p>
                        <p class="product--price">input</p>
                    </div>
                </li>
                <li class="productsItem">
                    <div class="productsItem__inner">
                        <p class="thumbnail"><img src="/assets/img/uploads/main_visual_sp.jpg" alt=""></p>
                        <p class="product--name">slow start</p>
                        <p class="product--price">200円</p>
                        <p class="product--price">input</p>
                    </div>
                </li>
                <li class="productsItem">
                    <div class="productsItem__inner">
                        <p class="thumbnail"><img src="/assets/img/uploads/main_visual_sp.jpg" alt=""></p>
                        <p class="product--name">slow start</p>
                        <p class="product--price">200円</p>
                        <p class="product--price">input</p>
                    </div>
                </li>
                <li class="productsItem">
                    <div class="productsItem__inner">
                        <p class="thumbnail"><img src="/assets/img/uploads/main_visual_sp.jpg" alt=""></p>
                        <p class="product--name">slow start</p>
                        <p class="product--price">200円</p>
                        <p class="product--price">input</p>
                    </div>
                </li>
                <li class="productsItem">
                    <div class="productsItem__inner">
                        <p class="thumbnail"><img src="/assets/img/uploads/main_visual_sp.jpg" alt=""></p>
                        <p class="product--name">slow start</p>
                        <p class="product--price">200円</p>
                        <p class="product--price">input</p>
                    </div>
                </li>
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