<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("dbconnect.php");

session_start();

$todos = $dbh->query("select * from todos")->fetchALL(PDO::FETCH_ASSOC);
$userId = $_SESSION['id'];
$todos = $dbh->prepare("SELECT * FROM todos WHERE user_id = :user_id");
$todos->bindValue(':user_id', $userId);
$todos->execute();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ph2-todos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./js/script.js" defer></script>
</head>

<body>
    <header class="bg-blue-500 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <?php

            if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
                // ユーザーがログインしている場合
                // ユーザーのメールアドレスを表示
                echo '<span class="text-x1 font-bold">' . htmlspecialchars($_SESSION['email']) . "'s Todo List</span>";
            } else {
                echo '<span class="text-x1 font-bold">サイト名</span>';
            }
            ?>
            <div>
                <form method="POST" action="auth/login.php">
                    <input type="submit" value="ログアウト" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                </form>
            </div>
        </div>
    </header>
    <div class="p-10">
        <div class="w-full flex justify-center items-center flex-col">
            <form action="admin/create/index.php" method="POST">
                <input class="border mb-5 p-2 w-full max-w-lg" type="text" placeholder="新しいToDoを入力してください" id="new-todo" name="new_todo">
                <button class="underline bg-slate-400 hover:bg-blue-700 hover:text-gray-300 text-sky-800 font-bold py-2 px-4 rounded w-52 text-center" id="js-create-todo">追加</button>
            </form>
            <ul class="sp-y-4" id="js-todo-list">
                <?php foreach ($todos as $todo) : ?>
                    <li class="flex items-center js-todo" data-id="<?= $todo['id'] ?>">
                        <?= $todo['text'] ?>
                        <!-- ボタンを横に並べる -->
                        <div class="flex ml-4">
                            <form action="admin/update/index.php" method="POST">
                                <input type="hidden" name="todo_id" value="<?php echo $todo['id']; ?>">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mx-2 my-4" name="update_todo">
                                    <?php echo $todo['completed'] ? 'Undo' : 'Complete'; ?>
                                </button>
                            </form>
                            <a href="admin/edit/index.php?id=<?php echo $todo['id']; ?>&text=<?php echo urldecode($todo['text']); ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 font-bold rounded mx-2 my-4" id="yellow-button">Edit</a>
                            <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded mx-2 my-4 js-delete-todo" data-id="<?php echo $todo['id']; ?>">Delete</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <template id="js-template">
        <li id="js-todo-template" class="flex items-center js-todo">
            <span id="js-todo-text"></span>
            <button type="button" id="js-complete-todo-template" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mx-2 my-4 js-complete-todo" data-id="">
                Complete
            </button>
            <a href="" id="js-edit-todo-template" class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 font-bold rounded mx-2 my-4">Edit</a>
            <button type="button" id="js-delete-todo-template" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded mx-2 my-4 js-delete-todo" data-id="">
                Delete
            </button>
        </li>
    </template>
</body>

</html>