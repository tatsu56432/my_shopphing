<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>my shoppimg site</title>
</head>
<body>

<header class="header">
    <div class="header__inner">
        <p class="header--ttl">my shopping site</p>

        <div class="userBlock">
            <p class="userName"><?php if(isset($_SESSION['login_name'])) echo $_SESSION['login_name'] ?></p>
            <p class="icon--curt"><a href=""></a></p>
            <p class="logout"></p>
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
                        <p class="thumbbail"><img src="" alt=""></p>
                        <p class="product--name"></p>
                        <p class="product--price"></p>
                        <p class="product--price"></p>
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