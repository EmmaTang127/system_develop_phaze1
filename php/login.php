<?php
session_start();

$error_message = ""; // エラーメッセージ

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $host = "localhost";
    $username = "eteam";
    $password = "safety";
    $dbname = "eteam_safety";

    // PDO接続を試みる
    try {
        $PDO = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        die("接続に失敗しました: " . $e->getMessage());
    }

    $user_id = $_POST['user_id'];
    $user_password = $_POST['user_password'];

    // プリペアドステートメントを使用する
    $sql = "SELECT * FROM login_view WHERE worker_id=:user_id AND password=:user_password";
    $stmt = $PDO->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':user_password', $user_password);

    try {
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if($result["worker_id"] == $user_id&&$result["password"] == $user_password){
              // ユーザーデータをセッションに保存
              $_SESSION['user_id'] = $user_id;
              $_SESSION['password'] = $user_password;
              $_SESSION['user_comment']=$result['comment'];
              $_SESSION['user_name'] = $result['worker_name'];
              // メインページにリダイレクト
              header("Location: main.php");
              exit();
            }
        } else {
            $error_message = "IDまたはパスワードが正しくありません。"; 
        }
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }

    // PDO接続を閉じる
    $PDO = null;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/login.css">
  <title>login</title>
</head>
<body>
  <header>
    <h1 class="bungee">ANPI</h1>
  </header>
  <main>
    <div id="login_item" action="main.php" method="post"><!--ログイン要素（ボタン以外）-->
      <form id="login_form">
        <div id="login_ID">
          <input type="text" id="user_id" name="user_id"  class="login_item" placeholder="ID" >
        </div>
        <div id="login_Password">
          <input type="password" id="user_password" name="user_password" class="login_item" placeholder="PASSWORD">
        </div>
        <button id="login_Btn" class="bungee login_item">LOGIN</button>
      </form>
    </div>
  </main>

  <script src="../js/login.js"></script>
</body>
</html>