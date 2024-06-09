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
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <title>main</title>
</head>

<body>
  <header>
    <!--メニューボタン -->
    <div id="menu">
      <img src="../img/menu_bar.png" alt="menu_bar" id="menu_bar">
    </div>

    <!-- サイドバーコンテナ -->
    <div class="sidebar">
      <div class="sidebar_content">
        <img src="../img/close.png" alt="" class="sidebarClose">
        <div id="menu_content">
          <div><a href="../php/registration.php">安否登録</a></div>
          <?php if (isset($_SESSION['admin_id']))
            echo ('<div><a href="user_conecte.php">ユーザー作成</a></div>') ?>
            <a href="logout.php" id="Logout">Logout</a>
          </div>
        </div>
      </div>

      <h1>Safety Status</h1>

    </header>

    <main>
      <!-- 自分の登録状況 -->
      <div id="mysafety">
        <!-- <div id="safety_color"></div>
        <div id="myName" class="text-test">name</div>
        <div id="mycomment" cols="10" rows="2">comment</div> -->
      </div>

      <!-- 状況選択 -->
      <div id="Select">
        <select name="Safety" id="Safety_select">
          <option value="2">Danger</option>
          <option value="1">Caution</option>
          <option value="0">Safety</option>
          <option value="3">Unregistered</option>
        </select>
      </div>

      <div id="Safety_List">

        <!---リストを表示する枠-->
        <div id="Safety" class="Safety_List danger_color">

          <!-- <div class="list_view">
            <div class="list_color list_item status_color"></div>
            <div class="list_Name list_item item_color">name</div>
            <div class="list_comment list_item item_color" cols="10" rows="2">comment</div>
          </div> -->

        </div>
      </div>
    </main>

    <!--詳細ダイアログ-->
    <dialog id="detail_dialog">

      <!-- 詳細情報 -->
      <div id="detail">

        <!-- 名前、ID、電話番号 -->
        <div id="dialog_header">
          <span></span>
          <span></span>
          <span></span>
        </div>
        <!-- 詳細情報の履歴を表示する枠 -->
        <div id="detail_items">
          <!-- 情報全体 -->
          <div class="dialog_main">

            <!-- 安全状況 -->
            <div id="dialog_safety">
              <div id="color_g"></div>
              <div id="safety">safe</div>
            </div>

            <!-- 日付、場所、コメント -->
            <div id="dialog_content">
              <div id="line"></div>
              <div id="dialog_item">
                <div id="date" class="dialog_item">2024/01/01 00:00</div>
                <div id="location" class="dialog_item">場所</div>
                <div class="dialog_item comment">避難所にいますすすすすすす</div>
              </div>
            </div>
          </div>
        </div>


      </div>

    </dialog>

    <script src="../js/main.js"></script>
  </body>

  </html>