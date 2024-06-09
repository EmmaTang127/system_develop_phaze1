<?php

//データをJsから取得
$data = json_decode(file_get_contents('php://input'),true);

//データを入れる
$user_id = $data["user_id"];
$status = $data["status"];
$lati = $data["lati"];
$long = $data["long"];
$comment = $data["comment"];

//結果を返す配列
$result["message"] = "入力なし";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //DBに接続
  $host = "localhost";
  $username = "eteam";
  $password = "safety";
  $dbname = "eteam_safety";

  // PDO接続
  try {
    $PDO = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // DBへ出力
    $sql = "INSERT INTO safety_report VALUES(:worker_id,now(),:status_id,ST_GEOMFROMTEXT('POINT($lati $long)', 4326),:comment);";
    $stmt = $PDO->prepare($sql);
    //バインド
    $stmt->bindParam('worker_id', $user_id, PDO::PARAM_STR);
    $stmt->bindParam('status_id', $status, PDO::PARAM_INT);
    $stmt->bindParam('comment', $comment, PDO::PARAM_STR);
    //実行
    $stmt->execute();
    //結果
    $result["message"] = true;

  } catch (PDOException $e) {
    //結果
    $result["message"] = $e->getMessage();
  }
  //DB切断
  $stmt = null;
  $db = null;
  
}

echo json_encode($result); // json形式にして返す

// header("Location: main.php");	//kadai10_1.phpへ画面遷移

// INSERT INTO safety_report VALUES('1234501',now(),0,ST_GEOMFROMTEXT('POINT(39.744972 135.675889)', 4326),'1111'); -->
?>

