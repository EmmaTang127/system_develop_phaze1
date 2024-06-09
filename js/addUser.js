const form = document.getElementById("add_user");

form.addEventListener("submit", (event) => {
    //デフォルト動作をブロック
    event.preventDefault();

    user_data = {};
    user_data["worker_id"] = document
        .getElementsByName("user_id")
        .item(0).value;
    user_data["worker_name"] = document
        .getElementsByName("user_name")
        .item(0).value;
    user_data["date_of_birth"] = document
        .getElementsByName("user_Birth")
        .item(0).value;
    user_data["tel"] = document
        .getElementsByName("user_Tel")
        .item(0).value;
    user_data["admin_id"] = document
        .getElementsByName("user_Admin")
        .item(0).checked;

    console.log(user_data);
    fetch("http://localhost/group/php/API.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            action: "addUser",
            data: user_data
        })
    })
        // //レスポンスをJSONファイルとして受け取る
        .then((response) => response.json())
        //取得してJSONデータを処理
        .then((data) => {
            if (data["success"]) {
                alert('新規ユーザーを作成しました');
            } else {
                alert("IDとパスワードが違います。");
            }
        })
        .catch((error) => {
            alert(error);
        });
});
// 戻るボタンの機能追加
const return_button = document.querySelector("#return_Btn");
 
return_button.addEventListener('click', (event)=> {
    event.preventDefault();
    window.location.href = "http://localhost/TeamE/php/main.php";
})
