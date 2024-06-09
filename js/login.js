// ユーザーのIDとパスワードの要素を特定
const user_id = document.getElementById('user_id');
const user_password = document.getElementById('user_password');
//フォームを特定
const login_form = document.getElementById('login_form');

// LocalStorageから値を取得する
const previous_login_data = JSON.parse(localStorage.getItem('previous_login_data'));

// LocalStorageに保存されたログイン情報があれば挿入
if(previous_login_data){
    user_id.value = previous_login_data['previous_id'];
    user_password.value = previous_login_data['previous_password'];
}

// Loginボタン押したときの処理
login_form.addEventListener('submit', (event) => {
    event.preventDefault();

    // 各入力項目から入力値を取得（trimメソッドで入力値の前後の空白は除去する）
    let login_id = user_id.value.trim();                  // user ID
    let login_password = user_password.value.trim();      // user Password

    console.log(login_id);
    console.log(login_password);

    //入力されたデータをAPIに
    fetch("http://localhost/TeamE/php/API.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            action: "login",
            data:
            {
                worker_id: login_id,
                password: login_password
            }
        }

        )
    })

    // //レスポンスをJSONファイルとして受け取る
    .then((response) =>response.json())
    //取得してJSONデータを処理
    .then((data) => {

        if (data['error']) {
            user_id.value = "";
            user_password.value = "";
            alert('IDとパスワードが違います。');
        } else {
        //セッションストレージに現在のログイン者の情報を保存
        sessionStorage.setItem('my_data', JSON.stringify(data));

        //ローカルストレージに成功したログイン情報を格納
        localStorage.setItem('previous_login_data', JSON.stringify({ previous_id: login_id, previous_password: login_password}));
        window.location.href = "http://localhost/TeamE/php/main.php";

        }
    })
    .catch((error) => {
        alert(error);
    });

});
