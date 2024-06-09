// 戻るボタンの機能追加
const return_button = document.querySelector("#return_Btn");

return_button.addEventListener('click', (event)=> {
    defaultpreventDefault();
    window.location.href = 'http://localhost/TeamE/php/main.php';
})

