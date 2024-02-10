<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("./dbconnect.php");

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
    <div class="p-8">
        <div class="w-full flex justify-center items-center flex-col">
            <div class="mb-5 text-center">
                <input class="border mb-5 p-2 w-full max-w-lg" type="text" placeholder="新しいToDoを入力してください" id="js-todo-text" name="new_todo">
                <button type="button" class="underline bg-slate-400 hover:bg-blue-700 hover:text-gray-300 text-sky-800 font-bold py-2 px-4 rounded w-52 text-center" id="js-create-todo">
                    追加
                </button>
            </div>
                <ul class="sp-y-4 text-center" id="js-todo-list">
                    <?php foreach ($todos as $todo) : ?>
                        <li class="flex items-center justify-center js-todo" data-id="<?= $todo['id'] ?>">
                            <span><?= $todo['text'] ?></span>
                                <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mx-2 my-4 js-complete-todo" name="toggle-id" data-id="<?= $todo['id']; ?>"> <!--このdata-idをまず検索-->
                                    <?php echo $todo['completed'] ? 'Undo' : 'Complete'; ?>
                                </button>
                                <a href="admin/edit/index.php?id=<?= $todo['id']; ?>&text=<?= $todo['text'] ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 font-bold rounded mx-2 my-4" id="yellow-button">Edit</a>
                                <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded mx-2 my-4 js-delete-todo" data-id="<?= $todo['id']; ?>">
                                Delete
                                </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
    </div>
    <template id="js-template">
        <li id="js-todo-template" class="flex items-center justify-center js-todo">
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
    <script>
        const addTodoElement = (text, id) => {
            const template = document.getElementById('js-template').content.cloneNode(true);
            template.getElementById('js-todo-text').textContent = text; //cloneNode() 関数を使って <template> 内のコンテンツをクローンし、新たな To-Do 要素の基礎部分を生成

            const todoElement = template.getElementById("js-todo-template");
            todoElement.setAttribute("data-id", id); //<li> 要素を取得し、data-id 属性に id を設定する（スタータス更新の処理で使います）

            const completeButton = template.getElementById('js-complete-todo-template');
            completeButton.setAttribute("data-id", id);
            completeButton.addEventListener('click', () => {
                updateTodo(id);
            }); //ステータス更新ボタンの要素を取得し、data-id 属性に id や イベントリスナーを設定する

            template.getElementById('js-edit-todo-template').href = `admin/edit/index.php?id=${id}&text=${text}`;
            //引数を用いて To-Do のテキストと編集用のリンクを設定する

            const deleteButton = template.getElementById('js-delete-todo-template');
            deleteButton.setAttribute('data-id', id);
            deleteButton.addEventListener('click', () => {
                deleteTodo(id, deleteButton.parentNode);
            }); //削除ボタンの要素を取得し、data-id 属性に id や イベントリスナーを設定する

            document.getElementById('js-todo-list').appendChild(template);
            //appendChild() 関数を使って新しい To-Do を元のリストに追加する
        }
    </script>
</body>

</html>