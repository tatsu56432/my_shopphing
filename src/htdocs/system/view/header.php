<header class="header">
    <div class="header__inner">
        <p class="header--ttl"><a href="/">my shopping site</a></p>

        <div class="userBlock">
            <p class="userName">ようこそ！<?php if (isset($_SESSION['login_name'])) echo $_SESSION['login_name'] ?>さん</p>
            <p class="icon--curt"><a href="cart.php"><img src="/assets/img/header/icon--cart.png" alt="カート"></a></p>
            <p class="logout"><a href="logout.php">ログアウト</a></p>
        </div>
    </div>
</header>


