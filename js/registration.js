const registration = document.getElementById("registration");

let registration_data = {};
registration.addEventListener("submit", async (event) => {
    //submitを止める
    event.preventDefault();


    //送信するデータの取得
    const my_data = JSON.parse(sessionStorage.my_data);
    const status_id = document.getElementById("status_id").value;
    const comment = document.getElementsByName("comment").item(0).value;

    //データを連想配列に入れる
    registration_data["worker_id"] = my_data["worker_id"];
    registration_data["status_id"] = status_id;
    registration_data["comment"] = comment;


    // 位置情報の取得を待つ
    try {
        const position = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject);
        });

        // 位置情報を登録データに追加
        registration_data["location"] = `POINT(${position.coords.latitude} ${position.coords.longitude})`;

    } catch (error) {
        // 位置情報取得に失敗した場合
        registration_data["location"] = null;
    }

     //mainAPIにPOSTを送り、非同期通信で表示項目のJSONファイルを受け取る
    fetch("../PHP/API.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            action: "registration",
            data: registration_data
        }),
    })
        //レスポンスをJSONファイルとして受け取る
        .then((response) => response.json())
        //取得してJSONデータを処理
        .then((data) => {
            //データを一人ずつに切り分ける
            if (data['success']) {
                my_data['status_id'] = document.getElementById("status_id").value;
                my_data['comment'] = document.getElementsByName("comment").item(0).value;

                sessionStorage.setItem('my_data', JSON.stringify(my_data));
                alert('情報を登録しました。\nメインページに戻ります。');

                window.location.href = "http://localhost/group/php/main.php";

            }
        });
});
