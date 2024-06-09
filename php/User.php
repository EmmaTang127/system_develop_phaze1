<?php
$errors = array(); // エラーメッセージ配列

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送信されたデータをチェック
    if (empty($_POST["user_name"])) {
        $errors['user_name'] = "名前を入力してください。";
    }
    if (empty($_POST["user_id"])) {
        $errors['user_id'] = "IDを入力してください。";
    }
    if (empty($_POST["user_Birth"])) {
        $errors['user_Birth'] = "生年月日を入力してください。";
    }
    if (empty($_POST["user_Tel"])) {
        $errors['user_Tel'] = "電話番号を入力してください。";
    }

    // エラーチェック
    if (empty($errors)) {
        // データベースへの接続
        $host = "localhost";
        $username = "eteam";
        $password = "safety";
        $dbname = "eteam_safety";

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            // エラーモードを例外モードに設定
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // IDがデータベースに存在するかチェック
            $check_id_sql = "SELECT worker_id FROM employee WHERE worker_id = :user_id";
            $check_stmt = $conn->prepare($check_id_sql);
            $check_stmt->bindParam(':user_id', $_POST['user_id']);
            $check_stmt->execute();
            $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $errors['user_id'] = "このIDは既に存在します。";
            } else {
                
                // フォームからデータを取得
                $user_name = $_POST["user_name"];
                $user_id = $_POST["user_id"];
                $user_birth = $_POST["user_Birth"];
                $user_tel = $_POST["user_Tel"];
                $user_admin = isset($_POST["user_Admin"]) ? 1 : 0;

                // SQLクエリ準備
                $insert_sql = "INSERT INTO employee (worker_id, worker_name, date_of_birth, admin_id, tel) 
                        VALUES (:user_id, :user_name, :user_birth, :user_admin, :user_tel)";
                $stmt = $conn->prepare($insert_sql);

                
                $conn->beginTransaction();

                // パラメータに値をバインドし、クエリを実行
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':user_name', $user_name);
                $stmt->bindParam(':user_birth', $user_birth);
                $stmt->bindParam(':user_admin', $user_admin);
                $stmt->bindParam(':user_tel', $user_tel);

                // クエリ実行と結果のチェック
                $stmt->execute();
                
                
                $conn->commit();

                echo "新しいユーザーを追加しました。";
            }
        } catch(PDOException $e) {
            // エラーが発生した場合、ロールバックしてエラーメッセージを表示
            $conn->rollback();
            echo "エラー: " . $e->getMessage();
        }

        
        $conn = null;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../css/user_conecte.css">
  <title>ユーザー登録</title>
  <style>
    .error {
      color: red;
    }
  </style>
</head>
<body>
  <header>
    <h1 class="bungee">ANPI</h1>
  </header>
  <main>
    <div id="user_item">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div id="user_name" class="login_item">
          <input type="text" name="user_name" placeholder="NAME" value="<?php echo isset($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : ''; ?>">
          <?php if(isset($errors['user_name'])) { echo '<span class="error">' . $errors['user_name'] . '</span>'; } ?>
        </div>
        <div id="user_ID" class="login_item">
          <input type="text" name="user_id" placeholder="ID" value="<?php echo isset($_POST['user_id']) ? htmlspecialchars($_POST['user_id']) : ''; ?>">
          <?php if(isset($errors['user_id'])) { echo '<span class="error">' . $errors['user_id'] . '</span>'; } ?>
        </div>
        <div id="user_Birth" class="login_item">
          <input type="date" name="user_Birth" class="user_Birth" value="<?php echo isset($_POST['user_Birth']) ? htmlspecialchars($_POST['user_Birth']) : ''; ?>">
          <?php if(isset($errors['user_Birth'])) { echo '<span class="error">' . $errors['user_Birth'] . '</span>'; } ?>
        </div>
        <div id="user_Tel"class="login_item">
          <input type="tel" name="user_Tel" placeholder="TEL" value="<?php echo isset($_POST['user_Tel']) ? htmlspecialchars($_POST['user_Tel']) : ''; ?>">
          <?php if(isset($errors['user_Tel'])) { echo '<span class="error">' . $errors['user_Tel'] . '</span>'; } ?>
        </div>
        <div id="user_Admin">
          <input type="checkbox" name="user_Admin" >
          <label for="user_Admin">ADMIN</label>
        </div>
        <button type="submit" id="conecte_Btn" class="bungee">CONECTE</button>
      </form>
    </div>
  </main>
</body>
</html>
