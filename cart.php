<?php
    require_once 'helpers/db_helper.php';
    // セッションを開始する
    session_start();

    // 未ログインの場合login.phpに遷移する
    if (empty($_SESSION['member'])) {
        header('Location: login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add'])) {
            // カートに商品を追加する。
            $goodscode = $_POST['goodscode'];
            $num       = $_POST['num'];

            if (empty($_SESSION['cart'][$goodscode])) {
                $_SESSION['cart'][$goodscode] = $num;
            } else {
                $_SESSION['cart'][$goodscode] += $num;
            }
        }
        else if (isset($_POST['change'])) {
            // カートの数量を変更する
            $goodscode = $_POST['goodscode'];
            $num       = $_POST['num'];
            $_SESSION['cart'][$goodscode] = $num;
        }
        else if (isset($_POST['delete'])) {
            // カートの商品を削除する
            $goodscode = $_POST['goodscode'];
            unset($_SESSION['cart'][$goodscode]);
        }

        header("Location:" . $_SERVER['PHP_SELF']);
        exit;
    }

    $cart = [];
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    }

    // DBから商品データを取得する
    $dbh = get_db_connect();
    foreach (array_keys($cart) as $goodscode) {
        $goods = select_goods_by_goodscode($dbh, $goodscode);
        $goods_list[$goodscode] = $goods;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ショッピングカート</title>
</head>
<body>
    <?php include "header.php" ?>

    <?php if (empty($cart)) : ?>
        <p>カートに商品はありません。</p>
    <?php else : ?>
        <?php foreach($cart as $goodscode => $num) : ?>
            <?php $goods = $goods_list[$goodscode] ?>

            <table>
                <tr>
                    <td rowspan="4">
                        <img src="images/goods/<?= $goods['goodsimage'] ?>" />
                    </td>
                    <td>
                        <?= $goods['goodsname'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $goods['detail'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        \<?= number_format($goods['price']) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <form action="" method="POST">
                            数量
                            <input type="number" name="num" value="<?= $num ?>" min="1" />
                            <input type="hidden" name="goodscode" value="<?= $goods['goodscode'] ?>" />
                            <input type="submit" name="change" value="変更" />
                            <input type="submit" name="delete" value="削除" />
                        </form>
                    </td>
                </tr>
            </table>
            <hr />
        <?php endforeach; ?>

        <form action="buy.php" method="POST">
            <input type="submit" value="商品を購入する" />
        </form>

    <?php endif; ?>
</body>
</html>
