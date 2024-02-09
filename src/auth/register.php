<?php
require("../dbconnect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // クライアント側バリデーション
    if (empty($email) || empty($password)) {
        $msg = 'メールアドレスとパスワードは必須項目です。';
        $link = '<a href="signup.php">戻る</a>';
    } else {
        // サーバー側バリデーション
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = 'メールアドレスの形式が正しくありません。';
            $link = '<a href="signup.php">戻る</a>';
        } else {
            // メールがすでに登録されていないかチェック
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            // ユーザーの取得
            $member = $stmt->fetch();

            // ユーザーが存在するかチェック
            if ($member !== false) {
                $msg = '同じメールアドレスが存在します。';
                $link = '<a href="signup.php">戻る</a>';
            } else {
                // 登録されていなければinsert 
                $sql = "INSERT INTO users(email, password) VALUES (:email, :password)";
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':password', $password);
                $stmt->execute();
                $msg = '会員登録が完了しました';
                $link = '<a href="login.php">ログインページ</a>';
            }
        }
    }
}
header("Location: login.php");
exit; // リダイレクト後にスクリプトの実行を終了する

?>

<!-- こっちでもできる（その場合はheader関数とexitを消去） -->
<!-- <!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="0;url=login.php">
    <title>リダイレクト中...</title>
</head>

<body>
    <p>リダイレクト中です。お待ちください...</p>
</body>

</html> -->
