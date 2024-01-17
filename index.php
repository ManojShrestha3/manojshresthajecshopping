<?php
    require_once './helpers/db_helper.php';

    $dbh = get_db_connect();

    $goodsgroup_all = select_goodsgroup_all($dbh);

    if (isset($_GET['groupcode'])) {
        $groupcode = $_GET['groupcode'];
        $goods_list = select_goods_by_groupcode($dbh, $groupcode);
    }
    else if(isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
        $goods_list = select_goods_by_keyword($dbh, $_GET['keyword']);
    }
    else {
        $goods_list = select_recommend_goods($dbh);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>JecShopping</title>
    <link href="css/IndexStyle.css" rel="stylesheet" />
</head>
<body>
    <?php include "header.php" ?>

    <table id="goodsgroup">
        <?php foreach($goodsgroup_all as $goodsgroup) : ?>
            <tr>
                <td>
                    <a href="index.php?groupcode=<?= $goodsgroup['groupcode'] ?>">
                        <?= $goodsgroup['groupname'] ?>
                    </a>
                </td>
            </tr>
        <?php endforeach ?>
    </table>

    <?php if (!empty($keyword)) : ?>
        検索結果：<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>
    <?php endif; ?>

    <div id="goodslist">
        <?php foreach($goods_list as $goods) : ?> 
            <table align="left">
                <tr>
                    <td>
                        <a href="goods.php?goodscode=<?= $goods['goodscode'] ?>">
                            <img src="images/goods/<?= $goods['goodsimage'] ?>" />
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="goods.php?goodscode=<?= $goods['goodscode'] ?>">
                            <?= $goods['goodsname'] ?>
                        </a>
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
            </table>
        <?php endforeach ?>
    </div>
</body>
</html>
