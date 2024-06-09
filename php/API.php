<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-type: application/json');

//セッションの開始
session_start();


//POSTされたJSONを受け取る
$params = json_decode(file_get_contents('php://input'), true);
$action = $params['action'];
$data = $params['data'];


//受け取ったデータ型に応じて問い合わせるSQL文を選択する
$sql = '';
switch ($action) {
	case 'getStatus':
		$sql = "SELECT * FROM safety_view WHERE status_id = :status_id";
		break;
	case 'getDetail':
		$sql = "SELECT * FROM detail_view WHERE worker_id = :worker_id";
		break;
	case 'login':
		$sql = "SELECT * FROM login_view WHERE worker_id = :worker_id AND password= :password";
		break;
	case 'registration':
		$sql = "INSERT INTO safety_report VALUES (:worker_id, NOW(), :status_id, ST_GEOMFROMTEXT(:location), :comment)";
		break;
	case 'addUser':
		$sql = "INSERT INTO employee VALUES (:worker_id, :worker_name, :date_of_birth, :admin_id, :tel)";

}

//データベース接続に必要なステータス群
$host = 'localhost'; // データベースのホスト
$db_name = 'eteam_safety'; // データベース名
$username = 'eteam'; // データベースのユーザー名
$password = 'safety'; // データベースのパスワード
$options = [
	//エラーが発生したときに例外を投げる
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	//MySQLの場合はOFFが推奨
	PDO::ATTR_EMULATE_PREPARES => false
];
$dsn = 'mysql:host=' . $host . ';dbname=' . $db_name . '; charset=utf8mb4';




//DB接続
try {

	//PDOインスタンス生成
	$db = new PDO($dsn, $username, $password, $options);

	// トランザクションの開始
	$db->beginTransaction();


	//sqlステートメント実行
	$stmt = $db->prepare($sql);
	//値をバインド
	foreach ($data as $key => $value) {
		$stmt->bindValue($key, $value);
	}

	//実行
	$stmt->execute();


	//ログイン処理と、その他の場合で値の返し方を分ける
	switch ($action) {
		case 'login' :
			if ($stmt->rowCount() == 1) {
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				//セッションの管理
				$_SESSION['worker_id'] = $row['worker_id'];
				if ($row['admin_id'] == 1) $_SESSION['admin_id'] = 'admin';

				//jsonに表示しない要素の削除
				unset($row['password']);
				unset($row['admin_id']);

				echo json_encode($row);

				$db->commit();
			} else {
				echo json_encode(array("error" => "login"));
				$db->rollBack();
			}
			break;
		case 'registration' :
		case 'addUser' :
			//処理結果が1件のみの場合コミットする
			if ($stmt->rowCount() == 1) {
				$db->commit();
				echo json_encode(array("success" => $action));
			} else {
				$db->rollBack();
				echo json_encode(array("error" => $action));
			}
			break;
		default :
			//返すものを格納する連想配列
			$response_data = [];
			//すべての行の情報を連想配列に格納する
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$response_data[] = $row;
			}
			$db->commit();

			//返す
			echo json_encode($response_data);
	}

} catch (PDOException $ex) {
	$db->rollBack();

	exit('接続エラー' . $ex->getMessage());

} finally {
	$stmt = null;
	$db = null;
}

?>