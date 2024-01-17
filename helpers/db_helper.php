<?php
    require_once 'config.php';

    // DBに接続する
    function get_db_connect() {
        try{
            $dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
        }
        catch (PDOException $e){
           echo $e->getMessage();
           die();
        }

        return $dbh;
    }

    // DBから全商品分類を取得する。
    function select_goodsgroup_all($dbh)
    {
        $sql = "SELECT * FROM GoodsGroup";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    // DBから全おすすめ商品を取得する
    function select_recommend_goods($dbh)
    {
        $sql = "SELECT * FROM Goods WHERE Recommend = 1";

        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    // DBから指定した商品分類コードの商品を取得する
    function select_goods_by_groupcode($dbh, $groupcode)
    {
        $sql = "SELECT *
                 FROM goods
                 WHERE groupcode = :groupcode
                 ORDER BY recommend DESC";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(":groupcode", $groupcode, PDO::PARAM_INT);
        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    // DBから、引数 $goodscode の商品を取得する
    function select_goods_by_goodscode($dbh, $goodscode)
    {
        $sql = "SELECT * FROM Goods WHERE goodscode = :goodscode";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(":goodscode", $goodscode, PDO::PARAM_STR);
        $stmt->execute();

        $goods = $stmt->fetch(PDO::FETCH_ASSOC);

        return $goods;
    }

    // DBから、メールアドレスとパスワードが一致する会員の会員ID、メールアドレス、会員名を取得する
    function select_member($dbh, $email, $password)
    {
        $sql = "SELECT memberid, email, membername 
                FROM Member 
                WHERE email = :email AND password = :password";
                
        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(":email",    $email,    PDO::PARAM_STR);
        $stmt->bindValue(":password", $password, PDO::PARAM_STR);
        $stmt->execute();

        $member = $stmt->fetch(PDO::FETCH_ASSOC);
        return $member;
    }

    // Saleテーブルに引数の商品データを登録する
    function insert_sale($dbh, $memberId, $goodscode, $num, $date)
    {
        $sql = "INSERT INTO Sale(MemberId, GoodsCode, Num, SaleDate)
                VALUES(:memberId, :goodsCode, :num, :date)";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(":memberId",  $memberId,  PDO::PARAM_INT);
        $stmt->bindValue(":goodsCode", $goodscode, PDO::PARAM_STR);
        $stmt->bindValue(":num",       $num,       PDO::PARAM_INT);
        $stmt->bindValue(":date",      $date,      PDO::PARAM_STR);

        $stmt->execute();
    }

    function insert_member_data($dbh, $email, $membername, $password, $zipcode, $address)
    {
        //$password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO member(email, membername, password, zipcode, address)
                  VALUES (:email, :membername, :password, :zipcode, :address)";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':email',      $email,       PDO::PARAM_STR);
        $stmt->bindValue(':membername', $membername,  PDO::PARAM_STR);
        $stmt->bindValue(':password',   $password,    PDO::PARAM_STR);
        $stmt->bindValue(':zipcode',    $zipcode,     PDO::PARAM_STR);
        $stmt->bindValue(':address',    $address,     PDO::PARAM_STR);

        $stmt->execute();
    }

    function email_exists($dbh, $email) 
    {
        $sql = "SELECT COUNT(*) AS count FROM Member WHERE email = :email";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row['count'] > 0 ){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // DBから、引数のキーワードがある全商品を取り出す
    function select_goods_by_keyword($dbh, $keyword)
    {
        $sql = "SELECT *
                FROM Goods
                WHERE GoodsName LIKE :keyword1 OR Detail LIKE :keyword2
                ORDER BY Recommend DESC";

        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':keyword1', "%". $keyword . "%", PDO::PARAM_STR);
        $stmt->bindValue(':keyword2', "%". $keyword . "%", PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll();
    }
