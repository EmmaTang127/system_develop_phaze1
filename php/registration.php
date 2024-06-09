<?php
//セッションの開始
session_start();
//ログイン以外の通信でセッションIDが存在しない場合ログインページにリダイレクトする
if (!isset($_SESSION['worker_id'])) {
  // ログインページにリダイレクト
  header('Location: ../html/login.html');
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/registration.css">
  <title>Information registration</title>
</head>
<body>
  <header>
    <h1>安否登録</h1>
  </header>
  <main>

    <!-- 状況選択 -->
    <form id="registration">
    <div id="situation" class="item">
        <select name="situation" id="status_id" size="1" class="select_Situation">
          <option value="0">安全</option>
          <option value="1">注意</option>
          <option value="2">危険</option>
        </select>
    </div>

    <!-- コメント入力 -->
    <div id="comment_Registration" class="item">
      <textarea name="comment" class="comment" cols="30" rows="10" placeholder="comment"></textarea>
    </div>

    <!-- 登録ボタン -->
    <div>
      <button class="registration_Btn">登録</button>
    </div>
    </form>
  </main>
  <script src="../js/registration.js"></script>
</body>
</html>