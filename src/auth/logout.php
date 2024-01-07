<?php
session_start();
$_SESSION = array();//セッションの中身をすべて削除
session_destroy();//セッションを破壊

// ログアウト後はログインページにリダイレクト
header('Location: ../index.php');
exit();