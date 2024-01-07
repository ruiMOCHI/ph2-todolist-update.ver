<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("../../dbconnect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["new_todo"]) && !empty($_POST["new_todo"])) {
        $newTodo = $_POST["new_todo"];

        // 新しい ToDo をデータベースに追加
        $stmt = $dbh->prepare("INSERT INTO todos (text) VALUES (:text)");
        $stmt->bindParam(":text", $newTodo);
        $stmt->execute();
    }
}

// リダイレクト
header("Location: ../../index.php");
exit();

