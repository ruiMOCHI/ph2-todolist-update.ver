<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("./dbconnect.php");

session_start(); /*$_SESSIONを使うにはこの session_start();が必要*/

// SQLインジェクション対策: プリペアドステートメントを使用してSQLクエリを実行
if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $todosQuery = $dbh->prepare("SELECT * FROM todos WHERE user_id = :user_id"); // プリペアドステートメントを準備
    $todosQuery->bindValue(':user_id', $userId, PDO::PARAM_INT); // プレースホルダーに値をバインド
    $todosQuery->execute(); // クエリを実行
    $todos = $todosQuery->fetchAll(PDO::FETCH_ASSOC); //結果を取得
} else {
    // セッションが存在しない場合の処理
    // 何かの処理を追加するか、リダイレクトするなどの処理を行う
    header("Location: login.php");
    exit(); // 必ずexit()で処理を終了する
}

/*SQLインジェクションは、悪意のあるユーザーが不正なSQLクエリをデータベースに注入し、データベースの動作を意図せずに変更または操作するセキュリティ上の脆弱性です。
一般的に、SQLインジェクションは、Webアプリケーションやデータベースに対する攻撃の一形態です。攻撃者は、不正なSQLクエリをアプリケーションに送り込み、それがデータベースに直接組み込まれる場合、悪意のある操作が実行される可能性があります。
例えば、ログインフォームにおいて、ユーザーがユーザー名とパスワードを提供し、その情報をデータベースと照合して認証するとします。しかし、入力を適切に検証せずにSQLクエリを構築する場合、攻撃者は不正なSQLコードを入力し、データベースに対して実行される可能性があります。
例えば、次のようなクエリが考えられます：

sql
SELECT * FROM users WHERE username='username' AND password='password'
攻撃者が次のようなユーザー名を提供すると、クエリは以下のようになります：

bash
username' OR '1'='1
これにより、クエリは次のようになります：

sql
SELECT * FROM users WHERE username='username' OR '1'='1' AND password='password'
この場合、'1'='1'は常に真であるため、条件は常に真となり、パスワードの検証がスキップされます。攻撃者は合法的な認証なしにログインできる可能性があります。

SQLインジェクションは深刻なセキュリティリスクであり、悪用されると機密データの漏洩、データベースの破壊、システムへの不正アクセスなどの深刻な影響を引き起こす可能性があります。そのため、適切な対策を講じることが重要です。*/


/*プリペアドステートメント（Prepared Statement）は、SQLインジェクションを防ぐための効果的な手法の1つです。通常、SQLクエリは実行される前にデータベースエンジンによって解析され、最適な実行プランが決定されます。しかし、プリペアドステートメントでは、SQLクエリの構文がデータベースに送信される前に、あらかじめ解析され、最適化された実行プランが準備されます。
具体的なプリペアドステートメントの手順は以下のようになります：

1 SQLクエリの文を準備しますが、変数の部分はプレースホルダー（通常は「?」や「:name」など）に置き換えます。
2 準備されたSQLクエリをデータベースに送信し、データベースエンジンが解析、コンパイル、最適化を行います。この時点では、プレースホルダーの値は不明です。
3 クエリが準備され、サーバー側で保持されます。
4 クライアント側からクエリを実行する際に、プレースホルダーに具体的な値をバインドして、クエリを実行します。
このプロセスにより、データベースエンジンはクエリを事前に準備し、バインドされる値が入力される前にクエリを解析するため、SQLインジェクション攻撃から保護されます。また、同じクエリを複数回実行する場合、プリペアドステートメントを使用することで、データベースエンジンはクエリの解析、コンパイル、最適化のオーバーヘッドを一度だけ行います。*/

/*小学生にも分かるver
プリペアドステートメントは、データベースに特別な命令を送る方法の一つです。これは、データベースに対して安全に質問をするためのトリックです。
プリペアドステートメントを使うと、まず質問の形を決めますが、具体的な答えの部分は空っぽにしておきます。そして、その質問をデータベースに送ります。データベースはその質問を受け取ると、質問の形だけを覚えておいて、具体的な答えは後で入れてもらうように待ちます。
後で具体的な答えを入れて質問を完成させる際には、その答えを特別な場所に入れて、質問をデータベースに送ります。データベースは、答えが入っているその質問をすぐに処理して、答えを教えてくれます。
これによって、悪い人が質問に悪い答えを入れてデータベースを混乱させたり、壊したりすることを防ぐことができます。プリペアドステートメントは、安全で効果的な質問のやり方なのです。*/


/*プレースホルダーに具体的な値をバインドするとは、SQLクエリ内の変数や空欄部分（プレースホルダー）に、実際のデータを埋め込むということです。
具体的な例を考えてみましょう。データベースにユーザーの情報を追加するとき、名前や年齢などの情報が必要ですが、その情報はユーザーごとに異なります。そこで、SQLクエリ内の名前や年齢の部分には、特別な場所（プレースホルダー）を用意しておきます。
例えば、次のようなSQLクエリがあります：

mysql
INSERT INTO users (name, age) VALUES (?, ?)
このクエリの中で、?がプレースホルダーです。ここに、実際のデータを埋め込む必要があります。その際、プレースホルダーに具体的な値をバインドすることになります。

具体的な値をバインドするとき、プログラムは次のような手順を踏みます：

1 プレースホルダーの位置に、実際のデータをバインドします。つまり、名前は名前の場所に、年齢は年齢の場所にそれぞれバインドします。
2 データベースにこのSQLクエリを送信します。その際、バインドされた実際のデータが一緒に送られます。
データベースは、バインドされたデータをクエリ内の対応する場所に挿入し、クエリを実行します。これによって、プレースホルダーを使って安全にSQLクエリを構築し、実際のデータを安全にデータベースに送ることができます。そして、SQLインジェクションなどの攻撃からデータベースを守ることができます。*/


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

            // セッションが存在し、ユーザーがログインしている場合
            if (isset($_SESSION['id']) && isset($_SESSION['email'])) {
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
                    <li class="flex items-center justify-center js-todo" data-id="<?= htmlspecialchars($todo['id']) ?>">
                        <span><?= htmlspecialchars($todo['text']) ?></span>
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mx-2 my-4 js-complete-todo" name="toggle-id" data-id="<?= htmlspecialchars($todo['id']); ?>">
                            <?= $todo['completed'] ? 'Undo' : 'Complete'; ?>
                        </button>
                        <a href="admin/edit/index.php?id=<?= htmlspecialchars($todo['id']); ?>&text=<?= htmlspecialchars($todo['text']) ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 font-bold rounded mx-2 my-4" id="yellow-button">Edit</a>
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded mx-2 my-4 js-delete-todo" data-id="<?= htmlspecialchars($todo['id']); ?>">
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
</body>

</html>
