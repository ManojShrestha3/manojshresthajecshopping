<?php
    // セッションの開始
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!empty($_SESSION['member'])) {
        // ログイン中なら、セッション変数の会員情報を取得する
        $member = $_SESSION['member'];

        // カートの数量の合計を求める
        $cart_total = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $num) {
                $cart_total += $num;
            }
        }
    }
?>
<header>
    <link href="css/HeaderStyle.css" rel="stylesheet" />

    <div id="logo">
        <a href="index.php">
            <img src="images/JecShoppingLogo.jpg" alt="JecShoppingロゴ" />
        </a>
    </div>

    <div id="link">
        <form action="index.php" method="GET">
            <input type="text" name="keyword" />
            <input type="submit" value="検索" />
        </form>
        <?php if (isset($member)) : ?>
            <?= $member['membername'] ?>さん
            <a href="cart.php">カート(<?= $cart_total ?>)</a>
            <a href="logout.php">ログアウト</a>
        <?php else : ?>
            <a href="login.php">ログイン</a>
        <?php endif; ?>
    </div>

    <div id="clear">
        <hr>
    </div>
</header>