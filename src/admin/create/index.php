<?php
// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     if (isset($_POST["new_todo"]) && !empty($_POST["new_todo"])) {
//         $newTodo = $_POST["new_todo"];

//         // 新しい ToDo をデータベースに追加
//         $stmt = $dbh->prepare("INSERT INTO todos (text) VALUES (:text)");
//         $stmt->bindParam(":text", $newTodo);
//         $stmt->execute();
//     }
// }

// // リダイレクト
// header("Location: ../../index.php");
// exit();


error_reporting(E_ALL);
ini_set('display_errors', 1);

require("../../dbconnect.php");

session_start(); // セッションの開始

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["todo-text"]) && !empty($_POST["todo-text"])) {
            $newTodo = $_POST["todo-text"];

            // 新しい ToDo をデータベースに追加
            $stmt = $dbh->prepare("INSERT INTO todos (user_id, text) VALUES (:user_id, :text)");
            $stmt->bindValue(":user_id", $_SESSION['id']);
            $stmt->bindParam(":text", $_POST['todo-text']);
            $stmt->execute();
            // 新しく追加された ToDo の id を取得。新しい ToDo をデータベースに追加した後に、JavaScript にその ToDo の ID を返す必要があるため、この処理は非同期通信の一部として追加されます。JavaScript が待機して結果を受け取る必要があるため、この行はリダイレクトの前に置かれます。
            $newlyInsertedId = $dbh->lastInsertId();
            // JavaScript に新しい ToDo の id を返す。返される値は JSON 形式であり、JavaScript がそれを処理できるように、適切な形式で出力される必要があります。そのため、echo json_encode(['id' => $newlyInsertedId]); の行は、HTTP レスポンスの一部として直接出力されます。HTTP リダイレクトが行われると、その時点でレスポンスがクライアントに送信され、以降の処理は行われなくなるためです。
            echo json_encode(['id' => $newlyInsertedId]);
            exit();
        }
    }
    // リダイレクト
    // header("Location: ../../index.php");
    // exit();
} catch (Exception $e) {
    // 例外が発生した場合の処理
    echo "エラーが発生しました: " . $e->getMessage();
}

