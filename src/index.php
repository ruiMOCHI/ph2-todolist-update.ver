<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("dbconnect.php");

session_start();

$todos = $dbh->query("select * from todos")->fetchALL(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ph2-todos</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <button class="underline bg-slate-400 hover:bg-blue-700 hover:text-gray-300 text-sky-800 font-bold py-2 px-4 rounded w-52 text-center" id="add-button">追加</button>
            </form>
            <ul class="sp-y-4" id="todo-list">
                <?php foreach ($todos as $todo) : ?>
                    <li class="flex items-center">
                        <span><?php echo isset($todo['text']) ? htmlspecialchars($todo['text']) : ''; ?></span>
                        <!-- ボタンを横に並べる -->
                        <div class="flex ml-4">
                            <form action="admin/update/index.php" method="POST">
                                <input type="hidden" name="todo_id" value="<?php echo $todo['id']; ?>">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mx-2 my-4" name="update_todo">
                                    <?php echo $todo['completed'] ? 'Undo' : 'Complete'; ?>
                                </button>
                            </form>
                            <a href="admin/edit/index.php?id=<?php echo $todo['id']; ?>&text=<?php echo urldecode($todo['text']); ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 font-bold rounded mx-2 my-4" id="yellow-button">edit</a>
                            <form action="admin/delete/index.php" method="POST">
                                <input type="hidden" name="todo_id" value="<?php echo $todo['id']; ?>">
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded mx-2 my-4" name="delete_todo">delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <!-- <script>
        function updateStatus(todoId) {
            // AJAXを使用してステータスを更新
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../../admin/update/index.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                console.log(xhr.readyState, xhr.status);
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // 更新が成功した場合、ページをリロード
                    location.reload();
                }
            };
            xhr.send('todo_id=' + encodeURIComponent(todoId));
        }
    </script> -->
</body>

</html>