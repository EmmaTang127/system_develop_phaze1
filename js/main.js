// ボタンをそれぞれ変数を作る
const menu = document.querySelector("#menu");
const sidebarClose = document.querySelector(".sidebarClose");
const sidebar = document.querySelector(".sidebar");

// メニューボタン押すとメニューを表示する
menu.addEventListener("click", () => {
    sidebar.classList.add("show-sidebar");
});

//　×を押すとメニューを閉じる
sidebarClose.addEventListener("click", () => {
    sidebar.classList.remove("show-sidebar");
});

//　閉じるボタンとメニューの外側を押すと、メニュー閉じる
document.addEventListener("click", (event) => {
    if (!sidebar.contains(event.target) && !menu.contains(event.target)) {
        sidebar.classList.remove("show-sidebar");
    }
});

//セッションストレージにある情報を取得する
const my_data = JSON.parse(sessionStorage.getItem("my_data"));

//自分の情報表示を行う場所を特定
const mySafety = document.getElementById("mysafety");

/*
    他の従業員の安否状況のリストを操作する部分
    絞り込み条件に応じて色の変更とデータの取得、表示
*/
//絞り込み条件の位置を格納する
const select_status = document.getElementById("Safety_select");

//絞り込み条件のボーダー変更場所
const select_border = document.getElementById("Select");

//list_viewを格納する親要素
const safety_list = document.getElementById("Safety");

//ダイアログの要素を格納する
const detail_dialog = document.getElementById("detail_dialog");

//ダイアログの表示する詳細情報の連想配列
let idToDetail = [];

//ダイアログのグラデーション色の定義
const line_colors = {
    3: "#C1C1C1",
    2: "#FF7878",
    1: "#FFF178",
    0: "#9FFF78",
};

/*
    ページをロードしたときに、自分の情報表示と、他の従業員の情報を取得を行う
*/
window.addEventListener("load", () => {
    setMyStatus();
    getStatus();
});

//絞り込みステータスが変更されたときに更新する
select_status.addEventListener("change", () => {
    getStatus();
});

//自身の情報表示を行う
function setMyStatus() {
    //自身のステータスによってブロックのカラーを変更する
    switch (my_data["status_id"].toString()) {
        case "0":
            mySafety.classList.add("safe_color");
            break;
        case "1":
            mySafety.classList.add("caution_color");
            break;
        case "2":
            mySafety.classList.add("danger_color");
            break;
        case "3":
            mySafety.classList.add("unregistered_color");
    }

    //データを挿入する
    mySafety.insertAdjacentHTML(
        "afterbegin",
        `<div id="safety_color" class="status_color"></div>
        <div id="myName" class="text-test item_color">${my_data["worker_name"]}</div>
        <div id="mycomment" class="item_color" cols="10" rows="2">${my_data["comment"]}</div>
        `
    );
}

//他の社員のデータを挿入する雛形を作成する
const list_view = document.createElement("div");
list_view.classList.add("list_view");

/*
    ステータスの絞り込み条件をもとに見た目と表示する情報の取得を行う
*/
function getStatus() {
    //絞り込み条件を取得し変数に格納
    let params = { status_id: select_status.value };

    //安否一覧の表示をリセットする
    while (safety_list.firstChild) {
        safety_list.removeChild(safety_list.firstChild);
    }

    //ダイアログに表示する詳細情報の連想配列をリセット
    idToDetail = [];

    //ダイアログに使う配列変数
    let dialogElements = []; //ダイアログのトリガーとなる要素

    /*
        見た目の変更をする
        親要素で色のクラスを指定すると、子孫にもテーマが反映される
    */

    //色の設定をリセットする
    safety_list.classList.remove(
        "safe_color",
        "caution_color",
        "danger_color",
        "unregistered_color"
    );
    select_border.classList.remove(
        "safe_color",
        "caution_color",
        "danger_color",
        "unregistered_color"
    );

    //絞り込み条件によって色を変える
    switch (params.status_id) {
        case "0":
            select_border.classList.add("safe_color");
            safety_list.classList.add("safe_color");
            select_status.style.backgroundImage = "url(../img/arrow.png)";
            break;
        case "1":
            select_border.classList.add("caution_color");
            safety_list.classList.add("caution_color");
            select_status.style.backgroundImage = "url(../img/arrow_y.png)";
            break;
        case "2":
            select_border.classList.add("danger_color");
            safety_list.classList.add("danger_color");
            select_status.style.backgroundImage = "url(../img/arrow_r.png)";
            break;
        case "3":
            select_border.classList.add("unregistered_color");
            safety_list.classList.add("unregistered_color");
            select_status.style.backgroundImage = "url(../img/arrow_g.png)";
    }

    /*
        表示する内容を取得する部分
    */
    //mainAPIにPOSTを送り、非同期通信で表示項目のJSONファイルを受け取る
    fetch("http://localhost/TeamE/php/API.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            action: "getStatus",
            data: { status_id: select_status.value },
        }),
    })
        //レスポンスをJSONファイルとして受け取る
        .then((response) => response.json())
        //取得してJSONデータを処理
        .then((data) => {
            //データを一人ずつに切り分ける
            data.forEach((item) => {
                //データを雛形に挿入する
                const worker_id = item["worker_id"];
                const tel = item["tel"];
                const worker_name = item["worker_name"];
                const comment = item["comment"];

                //社員IDと詳細の組みを配列に追加
                idToDetail[worker_id] = [worker_name, tel];

                /*
                    挿入する要素の作成
                */
                if (worker_id != my_data["worker_id"]) {
                    //雛形を複製
                    const insertElement = list_view.cloneNode(false);
                    insertElement.insertAdjacentHTML(
                        "beforeend",
                        `
                <div class="list_color list_item status_color"></div>
                <div class="list_Name list_item item_color">${worker_name}</div>
                <div class="list_comment list_item item_color" cols="10" rows="2">${comment}</div>
                `
                    );

                    //要素を表示する
                    insertElement.classList.add(worker_id);
                    safety_list.appendChild(insertElement);

                    //ダイアログを表示するトリガーの配列
                    dialogElements.push(insertElement);
                }
            });

            //ダイアログのイベントリスナーを設定
            dialogElements.forEach((item) => {
                item.addEventListener("click", openDialog);
            });
        });
}

//　閉じるボタンとDialogの外側を押すと、メニュー閉じる
detail_dialog.addEventListener("click", (event) => {
    if (
        event.target == detail_dialog ||
        event.target.classList == "material-icons"
    ) {
        detail_dialog.close();
    }
});

function openDialog(event) {
    //押されたトリガーがどの社員のものか特定
    const eventItem =
        event.currentTarget.classList[event.currentTarget.classList.length - 1];

    //名前,ID,電話番号の挿入位置を特定
    const dialogHeaderChildren =
        document.getElementById("dialog_header").children;
    //各要素を挿入
    dialogHeaderChildren[0].textContent = idToDetail[eventItem][0];
    dialogHeaderChildren[1].textContent = eventItem;
    dialogHeaderChildren[2].textContent = idToDetail[eventItem][1];

    //登録履歴を挿入する要素を格納する変数
    const insertPosition = document.getElementById("detail_items");

    //ダイアログの中身をリセットする
    while (insertPosition.firstChild) {
        insertPosition.removeChild(insertPosition.firstChild);
    }

    //安否情報の登録履歴を取得する
    //APIにPOSTを送り、非同期通信で表示項目のJSONファイルを受け取る
    fetch("http://localhost/TeamE/php/API.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            action: "getDetail",
            data: { worker_id: eventItem },
        }),
    })
        //レスポンスをJSONファイルとして受け取る
        .then((response) => response.json())
        //取得してJSONデータを処理
        .then((data) => {
            //ステータスバーのグラデーションに使う配列
            let status_gra = [];

            data.forEach((item) => {
                //グラデーションに使う
                status_gra.push(item["status_id"]);

                //要素を配置する
                insertPosition.insertAdjacentHTML(
                    "beforeend",
                    `
                    <div class="dialog_main ${item["status_name"]}_dialog_color">
                        <div class="dialog_safety">
                            <div class="color_g"></div>
                            <div class="safety">${item["status_name"]}</div>
                        </div>
                        <div class="dialog_content">
                            <div class="line"></div>
                            <div class="dialog_content_items">
                                <div class="date dialog_item">
                                    ${item["reporting_time"]}
                                </div>
                                <div class="location dialog_item">
                                    ${item["current_location"]}
                                </div>
                                <div class="comment dialog_item ">
                                    ${item["comment"]}
                                </div>
                            </div>
                        </div>
                    </div>
                    `
                );
            });

            //グラデーション設定
            for (let i = 0; i < status_gra.length; i++) {
                insertPosition.children[
                    i
                ].lastElementChild.firstElementChild.style.background = `linear-gradient(${
                    line_colors[status_gra[i]]
                }, ${
                    status_gra.length - 1 > i
                        ? line_colors[status_gra[i + 1]]
                        : line_colors[status_gra[i]]
                })`;
            }
        });

    //ダイアログの表示
    detail_dialog.showModal();
}
