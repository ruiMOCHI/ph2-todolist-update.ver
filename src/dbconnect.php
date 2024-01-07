<?php
$dsn = 'mysql:host=db;dbname=posse;charset=utf8';
$user = 'root';
$password = 'root';

$dbh = new PDO($dsn, $user, $password);

//普通は上まででいい。これは、ブラウザに接続したときにちゃんと接続出来たかを確認するためのもの
// try {
//     $dbh = new PDO($dsn, $user, $password);
//      echo 'Connection to DB';
// } catch (PDOException $e) {
//     echo 'Connection failed: ' . $e->getMessage();
// }