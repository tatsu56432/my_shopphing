<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>registerページ</title>
</head>
<body class="register">

<header class="header">
    <div class="header__inner">
        my shopping site register
    </div>
</header>

<?php if($register_result === true){ echo "<p class='success'>ユーザーIDとパスワードの登録に成功しました。<p>"; }else{ echo "<p class='error'>同じユーザー名がすでに登録されています。<p>"; }?>

<div class="container">
    <div class="container__inner">
        <div class="l-container--register">
            <div class="loginForm">
                <form action="" method="post">
                    <div>
                        <label for="login_name">名前</label>
                        <input type="text" name="login_name" id="login_name">
                        <?php if (isset($error['login_name'])) echo '<p class="error">'. $error['login_name'] . '</p>' ;?>
                    </div>
                    <div>
                        <label for="password">パスワード</label>
                        <input type="password" name="password" id="password">
                        <?php if (isset($error['password'])) echo '<p class="error">'. $error['password'] . '</p>' ;?>
                    </div>

                    <div>
                        <label for="submit_register">登録する</label>
                        <input type="submit" class="" name="submit_register" id="submit_register">
                    </div>
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