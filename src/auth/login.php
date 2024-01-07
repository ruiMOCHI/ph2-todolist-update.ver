<?php
require("../dbconnect.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// 初期化
$msg = '';
$link = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // クライアント側バリデーション
    if (empty($email) || empty($password)) {
        $msg = 'メールアドレスとパスワードは必須項目です。';
        $link = '<a href="login.php">戻る</a>';
    } else {
        // サーバー側バリデーション
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $member = $stmt->fetch();

        // パスワードが一致しているか確認
        if ($member && password_verify($password, $member['password'])) {
            $_SESSION['id'] = $member['id'];
            $_SESSION['email'] = $member['email']; // ユーザーのメールアドレスをセッションに保存
            // ログイン成功時にindex.phpにリダイレクト
            header("Location: ../index.php");
            exit();
        } else {
            $msg = 'メールアドレスもしくはパスワードが間違っています。';
            $link = '<a href="login.php">戻る</a>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6">ログイン</h1>
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-600">メールアドレス：</label>
                <input type="email" name="email" id="email" class="mt-1 p-2 w-full border rounded" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-600">パスワード：</label>
                <input type="password" name="password" id="password" class="mt-1 p-2 w-full border rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none">
                ログイン
            </button>
        </form>
        <?php if (!empty($msg)) : ?>
            <h1 style="color: red;"><?php echo $msg; ?></h1>
            <?php echo $link; ?>
        <?php endif; ?>
        <p class="mt-4">新規登録は<a href="signup.php" class="text-blue-500">こちら</a></p>
    </div>
</body>

<!-- ... 以後のコード ... -->


</html>