<?php
//セッションの開始
session_start();
//管理者権限を持っていない人がこのページに来たときメインページに返す
if (!isset($_SESSION['admin_id'])) {
  // ログインページにリダイレクト
  header('Location: main.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/user_conecte.css">

  <title>user_conecte</title>
</head>
<body>
  <header>
    <h1 class="bungee">ANPI</h1>
  </header>
  <main>
    <div id="user_item">
      <form id="add_user" method="post">

        <div id="user_name" class="login_item">
          <input type="text" name="user_name" placeholder="NAME" required>
        </div>

        <div id="user_ID" class="login_item">
          <input type="text" name="user_id" placeholder="ID" required>
        </div>

        <div id="user_Birth" class="login_item">
          <input type="date" name="user_Birth" class="user_Birth" required>
        </div>

        <div id="user_Tel"class="login_item">
          <input type="tel" name="user_Tel" placeholder="TEL" required>
        </div>

        <div id="user_Admin">
          <input type="checkbox" name="user_Admin">
          <label for="user_Admin">ADMIN</label>
        </div>
        <div id="buttons">
          <button id="return_Btn"class="bungee">return</button>
          <button type="submit" id="conecte_Btn" class="bungee">CONECTE</button>
        </div>
      </form>
    </div>
  </main>
  <script src="../js/addUser.js?v=1"></script>
</body>
</html>