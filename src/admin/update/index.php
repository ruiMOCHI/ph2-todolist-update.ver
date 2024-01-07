<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("../../dbconnect.php");

// output buffering を開始
ob_start();

// POSTリクエストで送られてきたToDoのIDを取得
$todoId = isset($_POST['todo_id']) ? intval($_POST['todo_id']) : 0;
if ($todoId > 0) {
    // ToDoのステータスを取得
    $stmt = $dbh->prepare("SELECT completed FROM todos WHERE id = ?");
    $stmt->execute([$todoId]);
    $status = $stmt->fetchColumn();

    // ステータスを更新
    $newStatus = ($status === 1) ? 0 : 1;
    $stmt = $dbh->prepare("UPDATE todos SET completed = ? WHERE id = ?");
    $stmt->execute([$newStatus, $todoId]);

    // 更新が成功したことを通知
    echo $newStatus;
} else {
    // ToDoのIDが不正な場合はエラーを返す
    echo 'error';
}

// リダイレクト
header("Location: ../../index.php");
exit();

// output buffering を終了して出力
ob_end_flush();

?>
