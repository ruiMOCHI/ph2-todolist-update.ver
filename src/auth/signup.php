<?php
require("../dbconnect.php");
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規会員登録</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6">新規会員登録</h1>
        <form action="login.php" method="post">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-600">メールアドレス：</label>
                <input type="text" name="email" id="email" class="mt-1 p-2 w-full border rounded" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-600">パスワード：</label>
                <input type="password" name="password" id="password" class="mt-1 p-2 w-full border rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none">
                新規登録
            </button>
        </form>
        <p class="mt-4">すでに登録済みの方は<a href="login.php" class="text-blue-500">こちら</a></p>
    </div>
</body>

</html>
