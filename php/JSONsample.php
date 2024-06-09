<?php
$mysqli = new mysqli('localhost', "dbuser", "ecc", "studb");
if (mysqli_connect_error()){
    die("データベースの接続に失敗しました");
}
//GETから読み取った値
$GET=

$result = $mysqli->query("SELECT * FROM product WHERE status = $GET");

if (!$result) {
    die("クエリの実行に失敗しました");
}
$userData = array();

// while($row = $sth->fetch(PDO::FETCH_ASSOC)){
//   $userData[]=array(
//   'PRODUCT_NO'=>$row['PRODUCT_NO'],
//   'PNAME'=>$row['PNAME'],
//   'CATEGORY'=>$row['CATEGORY'],
//   'PRICE'=>$row['PRICE']
//   );
// }
while($row = $result->fetch_assoc()){
  $userData[]=array(
      'PRODUCT_NO'=>$row['PRODUCT_NO'],
      'PNAME'=>$row['PNAME'],
      'CATEGORY'=>$row['CATEGORY'],
      'PRICE'=>$row['PRICE']
  );
}
//jsonとして出力
header('Content-type: application/json');
echo json_encode($userData);

// $jsonstr =  json_encode($userData, JSON_UNESCAPED_UNICODE);
// echo json_encode($jsonstr);
?>