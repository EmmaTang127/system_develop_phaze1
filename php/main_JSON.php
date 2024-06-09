<?php
$mysqli = new mysqli('localhost', "eteam", "safety", "eteam_safety");
if (mysqli_connect_error()){
    die("データベースの接続に失敗しました");
}

//GETから読み取った値
// $status_id=filter_input(INPUT_GET,"status_id");
$status_id = 1;

$status_all = 4;
//条件指定検索

if($status_id == $status_all){
  //条件検索
  $result = $mysqli->query("SELECT * FROM safety_view");
}else{
  //全件検索  
  $result = $mysqli->query("SELECT * FROM safety_view WHERE status_id = $status_id");
}


if (!$result) { 
    die("クエリの実行に失敗しました");
}
$userData = array();

//JSONにするために配列に入れる
while($row = $result->fetch_assoc()){
  $userData[]=array(
      'worker_id'=>$row['worker_id'],
      'worker_name'=>$row['worker_name'],
      'status_id'=>$row['status_id'],
      'comment'=>$row['comment']
  );
}
//jsonとして出力
header('Content-type: application/json');
echo json_encode($userData);
?>