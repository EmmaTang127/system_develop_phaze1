<?php
// セッション開始
session_start();

// すべてのセッション変数を削除
$_SESSION = array();

// セッションを破棄する
session_destroy();

header("Location: login.php");
?>