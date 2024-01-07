<?php

require("../../dbconnect.php");

// 削除フォームが送信されたかどうかを確認
if (isset($_POST['delete_todo'])) {
    $todoId = $_POST['todo_id'];

    // 削除ロジックを実装
    $stmt = $dbh->prepare("DELETE FROM todos WHERE id = ?");
    $stmt->execute([$todoId]);

    // リダイレクト
    header("Location: ../../index.php");
    exit();
}
