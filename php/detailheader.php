<?php
$host = 'localhost';
$dbname = 'eteam_safety';
$username = 'eteam';
$password = 'safety';

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // エラー時に例外をスローする設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // POSTからworker_idを取得
    // $worker_id = $_POST['worker_id'] ?? '';
    $worker_id = '0000001';

    // クエリの準備
    $stmt = $pdo->prepare("SELECT worker_name, tel FROM employee WHERE worker_id = :worker_id");
    // パラメータのバインド
    $stmt->bindParam(':worker_id', $worker_id, PDO::PARAM_STR);
    // クエリの実行
    $stmt->execute();
    // 結果を取得
    $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 結果が空の場合
    if (empty($userData)) {
        die("該当するデータが見つかりませんでした");
    }

    // 結果を出力
    echo json_encode($userData);

} catch (PDOException $e) {
    // エラーハンドリング
    die("エラー: " . $e->getMessage());
}
?>
