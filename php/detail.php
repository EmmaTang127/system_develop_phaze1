<?php
$mysqli = new mysqli('localhost', "eteam", "safety", "eteam_safety");
if (mysqli_connect_error()){
    die("データベースの接続に失敗しました");
}

//GETから読み取った値
$status_id=file_get_contents('php://input');

//詳細検索 
$result = $mysqli->query("SELECT * FROM detail_view WHERE status_id = $status_id");

if (!$result) { 
    die("クエリの実行に失敗しました");
}
$userData = array();

//JSONにするために配列に入れる
while($row = $result->fetch_assoc()){
  $userData[]=array(
      'reporting_time'=>$row['reporting_time'],
      'status_id'=>$row['status_id'],
      'current_location'=>$row['current_location'],
      'comment'=>$row['comment']
  );
}
//jsonとして出力
header('Content-type: application/json');
echo json_encode($userData);
?>