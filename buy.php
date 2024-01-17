<?php
    // 'helpers/db_helper.php'を読み込む
    require_once 'helpers/db_helper.php';

    // セッションを開始する
    session_start();

    // 未ログインの場合、ログインページに移動する
    if (empty($_SESSION['member'])) {
        header('Location: login.php');
        exit;
    }

    $member = $_SESSION['member']; 
    $cart   = $_SESSION['cart']; 
    $date   = date('Y-m-d H:i:s');

    // カートの商品をSaleテーブルに記録する
    $dbh = get_db_connect();
    foreach ($cart as $goodscode => $num) {
        insert_sale($dbh, $member['memberid'], $goodscode, $num, $date);
    }
    // カートを空にする
    $_SESSION['cart'] = [];

    // buyend.php に遷移する
    header('Location:buyend.php');
    exit;