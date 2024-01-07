<?php
// データベースに接続するためのファイルを読み込む
require("../../dbconnect.php");

// ToDoの編集画面で使う変数を初期化
$editedText = '';

// フォームが送信されたかどうかを確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ToDoの更新を処理

    // フォームから送られたデータを取得
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $editedText = isset($_POST['edited-todo']) ? $_POST['edited-todo'] : null;

    // データベースでToDoを更新するための手続きを用意
    $stmt = $dbh->prepare("UPDATE todos SET text = :text WHERE id = :id");
    $stmt->bindParam(':text', $editedText);
    $stmt->bindParam(':id', $id);

    // データベースの更新を試みる
    try {
        // トランザクションを開始
        $dbh->beginTransaction();
        // 実際の更新を実行
        $stmt->execute();
        // トランザクションをコミット（確定）
        $dbh->commit();
        // 更新が成功したらトップページに戻る
        header("Location: ../../index.php");
        exit();
    } catch (Exception $e) {
        // 更新に失敗した場合はトランザクションをロールバック（取り消し）してエラーメッセージを表示
        $dbh->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}

// フォームがまだ送信されていないか、処理に失敗した場合
// ToDoの詳細を取得する
$id = isset($_GET['id']) ? $_GET['id'] : null;
$text = isset($_GET['text']) ? urldecode($_GET['text']) : null;
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <!-- 文書の文字コードやビューポートの設定 -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ページのタイトル -->
    <title>ToDoを編集</title>
    <!-- Tailwind CSS（スタイルを簡単に追加できるライブラリ）を読み込む -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- ページのメインコンテンツ -->
    <div class="p-10">
        <div class="w-full flex justify-center items-center flex-col">
            <!-- ToDoの編集フォーム -->
            <form action="index.php" method="POST">
                <!-- ToDoのIDを隠しフィールドで送信 -->
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <!-- 編集したいToDoのテキストを入力するテキストボックス -->
                <input class="border mb-5 p-2 w-full max-w-lg" type="text" placeholder="ToDoを編集してください" name="edited-todo" id="edited-todo" value="<?php echo isset($text) ? htmlspecialchars($text) : ''; ?>">
                <!-- 更新ボタン -->
                <button class="underline bg-slate-400 hover:bg-blue-700 hover:text-gray-300 text-sky-800 font-bold py-2 px-4 rounded w-52 text-center" id="edit-button">更新</button>
            </form>
        </div>
    </div>
</body>

</html>
