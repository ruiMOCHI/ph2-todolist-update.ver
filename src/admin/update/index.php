<?php

require("../../dbconnect.php");

session_start();

// output buffering を開始
ob_start();

if (isset($_POST['toggle-id'])) {
    try {
        $stmt = $dbh->prepare("update todos set completed = not completed where id = :id");
        $stmt->bindValue(':id', $_POST['toggle-id']);
        $stmt->execute();

        $stmt = $dbh->prepare("select completed from todos where id = :id");
        $stmt->bindValue('id', $_POST['toggle-id']);
        $stmt->execute();
        $result = $stmt->fetch();

        echo json_encode(['completed' => $result['completed']]);
    } catch (PDOException $e) {
        header('HTTP/1.1 500 Internal Server Error');
        exit;
    }
} else {
    // $_POST['toggle-id'] が定義されていない場合の処理
    // 例えばエラーメッセージを出力するなどの適切な処理を行う
    echo "Error: toggle-id is not defined in the POST request.";
}


// output buffering を終了して出力
ob_end_flush();

