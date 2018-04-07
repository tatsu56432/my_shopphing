<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>loginページ</title>
</head>
<body>

<header class="header">
    <div class="header__inner">
        my shopping site
    </div>
</header>


<?php echo $view ?>
<div class="container">
    <div class="container__inner">

        <div class="l-container--login">
            <div class="loginForm">
                <form action="" method="post">
                    <div>
                        <label for="login_name">名前</label>
                        <input type="text" name="login_name" id="login_name">
                    </div>
                    <div>
                        <label for="password">パスワード</label>
                        <input type="password" name="password" id="password">
                    </div>

                    <div>
                        <label for="">ログイン</label>
                        <input type="submit" class="">
                    </div>
                    <input type="hidden" name="ticket">
                </form>

            </div>
        </div>

    </div>
</div>

<footer class="footer">
    <div class="footer__inner">

    </div>
</footer>
</body>
</html>