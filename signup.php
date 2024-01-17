<?php
    require_once 'helpers/db_helper.php';
    require_once 'helpers/extra_helper.php';

    $email      = '';
    $membername = '';
    $password1  = '';
    $password2  = '';
    $zipcode    = '';
    $address    = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email      = get_post('email');
        $membername = get_post('membername');
        $password1  = get_post('password1');
        $password2  = get_post('password2');
        $zipcode    = get_post('zipcode');
        $address    = get_post('address');

        $dbh = get_db_connect();
        $errs = [];

        // 入力値のバリデーション
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errs['email'] = 'メールアドレスの形式が正しくありません。';
        }
        elseif (email_exists($dbh, $email)) {
            $errs['email'] = 'このメールアドレスはすでに登録されています';
        }

        if ($membername == '') {
            $errs['membername'] = 'お名前を入力して下さい。';
        }

        if (!check_words($password1, 4, 20)) {
            $errs['password'] = 'パスワードは4～20文字で入力して下さい。';
        }
        elseif ($password1 != $password2) {
            $errs['password'] = 'パスワードが一致しません。';
        }

        if ($zipcode != '') {
            if (!preg_match('/\A([0-9]{3})-([0-9]{4})\z/', $zipcode)) {
                $errs['zipcode'] = '郵便番号は3桁-4桁で、間にハイフン(-)を入れて下さい。';
            }
        }

        if (empty($errs)) {
            // DBに会員データを追加
            insert_member_data($dbh, $email, $membername, $password1, $zipcode, $address);
            header('Location:signupEnd.php');
            exit;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>新規会員登録</title>
</head>
<body>
    <?php include 'header2.php' ?>
    <h1>会員登録</h1>
    <p>以下の項目を入力し、登録ボタンをクリックしてください（*は必須）</p>

    <form action="" method="POST">
        <table>
            <tr>
                <td>メールアドレス*</td>
                <td>
                    <input type="email" name="email" value="<?= $email ?>" 
                        placeholder="例）xxx@jec.ac.jp" required />
                </td>
                <td><span style="color:red"><?= @$errs['email'] ?></span></td>
            </tr>
            <tr>
                <td>パスワード*(4～20文字)</td>
                <td>
                    <input type="password" name="password1" minlength="4" maxlength="20" required />
                </td>
                <td><span style="color:red"><?= @$errs['password'] ?></span></td>
            </tr>
            <tr>
                <td>パスワード(再入力)*</td>
                <td>
                    <input type="password" name="password2" minlength="4" maxlength="20" required />
                </td>
                <td></td>
            </tr>
            <tr>
                <td>お名前*</td>
                <td>
                    <input type="text" name="membername" value="<?= $membername ?>" 
                        placeholder="例）電子太郎" required />
                </td>
                <td><span style="color:red"><?= @$errs['membername'] ?></span></td>
            </tr>
            <tr>
                <td>郵便番号</td>
                <td>
                    <input type="text" name="zipcode" value="<?= $zipcode ?>" 
                                placeholder="例）000-0000" 
                                pattern="\d{3}-\d{4}"
                                title="郵便番号は、3桁-4桁でハイフン（-）を入れて記入してください。" />
                </td>
                <td><span style="color:red"><?= @$errs['zipcode'] ?></span></td>
            </tr>
            <tr>
                <td>住所</td>
                <td>
                    <input type="text" name="address" value="<?= $address ?>" placeholder="例）新宿区○○…" />
                </td>
                <td></td>
            </tr>
        </table>

        <p><input type="submit" value="登録する"></p>
    </form>
</body>
</html>
