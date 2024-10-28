const members = [
    { username: 'user1', password: 'pass1' },
    { username: 'user2', password: 'pass2' }
];

// 登入功能
function login() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const member = members.find(m => m.username === username);

    if (member) {
        if (member.password === password) {
            alert("登入成功！");
            loadContent('results.html'); // 登入成功後載入結果頁面
        } else {
            // 顯示密碼錯誤訊息
            document.getElementById('passwordError').style.display = 'block';
            document.getElementById('errorMessage').style.display = 'none'; // 隱藏其他錯誤訊息
        }
    } else {
        // 登入失敗，顯示帳號或密碼錯誤訊息，並顯示註冊表單
        document.getElementById('errorMessage').style.display = 'block';
        document.getElementById('passwordError').style.display = 'none'; // 隱藏密碼錯誤訊息
        showRegister(); // 隱藏登入表單並顯示註冊表單
    }
}

// 顯示建立會員資料的表單
function showRegister() {
    document.getElementById('loginContainer').classList.add('hidden'); // 隱藏登入表單
    document.getElementById('registerContainer').classList.remove('hidden'); // 顯示註冊表單
    document.getElementById('errorMessage').style.display = 'none'; // 隱藏錯誤訊息
    document.getElementById('passwordError').style.display = 'none'; // 隱藏密碼錯誤訊息
}

// 建立會員資料功能
function register() {
    const regName = document.getElementById('regName').value;
    const regPhone = document.getElementById('regPhone').value;
    const regUsername = document.getElementById('regUsername').value;
    const regPassword = document.getElementById('regPassword').value;
    const regSize = document.getElementById('regSize').value;

    // 檢查帳號是否已存在
    const existingMember = members.find(m => m.username === regUsername);
    if (existingMember) {
        alert("此帳號已存在，請選擇其他帳號。");
        return;
    }

    // 新增會員資料
    members.push({
        username: regUsername,
        password: regPassword,
        name: regName,
        phone: regPhone,
        size: regSize
    });

    alert("會員資料建立成功！");
    // 清空表單
    document.getElementById('registerForm').reset();
    // 重新顯示登入表單
    document.getElementById('registerContainer').classList.add('hidden');
    document.getElementById('loginContainer').classList.remove('hidden');
}

// 動態載入頁面內容
function loadContent(page) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', page, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('content').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}