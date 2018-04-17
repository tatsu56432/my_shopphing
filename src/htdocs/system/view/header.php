<header class="header">
    <div class="header__inner">
        <p class="header--ttl"><a href="/">my shopping site</a></p>

        <div class="userBlock">
            <p class="userName">ようこそ！<?php if (isset($_SESSION['login_name'])) echo $_SESSION['login_name'] ?>さん</p>
            <div class="icon--curt">
                <a href="/cart/cart.php"><img src="/assets/img/header/icon--cart.png" alt="カート"></a>
                <p class="icon--curt__stock">
                    <span class="icon--curt__stock--num"><?php if(isset($cart_sum_amount_result)){ echo $cart_sum_amount_result;}else{ echo "0";}?></span>
                </p>
            </div>
            <p class="logout"><a href="/logout.php">ログアウト</a></p>
        </div>
    </div>
</header>


