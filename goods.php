<?php
    require_once './helpers/db_helper.php';

    $dbh = get_db_connect();

    // URLリクエストパラメータに商品コードがセットされていれば、
    // DBから商品データを取得する
    if (isset($_GET['goodscode'])) {
        $goodscode = $_GET['goodscode'];
        $goods = select_goods_by_goodscode($dbh, $goodscode);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>商品詳細</title>
</head>
<body>
    <?php include "header.php" ?>

    <table>
        <tr>
            <td rowspan="5">
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
                <?= $goods['recommend'] == 1 ? "おすすめ" : "　" ?>
            </td>
        </tr>
        <tr>
         <td>
            <form action="cart.php" method="POST">
               個数
               <select name="num">
                  <?php for ($i=1; $i<=10; $i++) : ?>
                     <option value="<?= $i ?>"><?= $i ?></option>
                  <?php endfor ?> 
               </select>

               <input type="hidden" name="goodscode" value="<?= $goods['goodscode'] ?>" />
               <input type="submit" name="add" value="カートに入れる" />
            </form>
         </td>
      </tr>
    </table>
</body>
</html>
