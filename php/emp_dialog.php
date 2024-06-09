<?php
$mysqli = new mysqli('localhost', "eteam", "safety", "eteam_safety");
if (mysqli_connect_error()){
  die("データベースの接続に失敗しました");
}
//JSからのIDをゲット
$emp_no=filter_input(INPUT_GET,"worker_id");
$emp_no = '0000001';

//workerIDをもとにして条件検索
$result = $mysqli->query("SELECT reporting_time,status_id,ST_AsText(current_location),comment FROM safety_report WHERE worker_id = $emp_no");
// WHERE worker_id = $emp_no
if (!$result) { 
  die("クエリの実行に失敗しました");
}
$userData = array();

//JSONにするために配列に入れる
while($row = $result->fetch_assoc()){
  $userData[]=array(
    'reporting_time'=>$row['reporting_time'],
    'status_id'=>$row['status_id'],
    'current_location'=>$row['ST_AsText(current_location)'],
    'comment'=>$row['comment']
  );
}
//jsonとして出力
header('Content-type: application/json');
echo json_encode($userData);

?>