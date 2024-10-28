<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>病蟲害偵測及警示整合系統</title>
    <link rel="stylesheet" href="index.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
</head>
<body background="背景.jpg">
    <!-- 導覽列 -->
    <div class="navbar">
        <h1>病蟲害偵測及警示整合系統</h1>
        <nav id="navMenu" class="hidden">
            <a href="index.php">首頁</a>
            <a href="results.php">偵測結果</a>
            <a href="degree.php">蟲害程度</a>
            <a href="history.php">歷史紀錄</a>
        </nav>
    </div>
    <!-- 內容區域 -->
    <div class="content" id="content">
        <!-- 登入頁面 -->
        <div class="login-container" id="loginContainer">
            <h2>會員登入</h2>
            <form id="loginForm">
                <input type="text" id="username" placeholder="帳號" required>
                <input type="password" id="password" placeholder="密碼" required>
                <button type="button" onclick="login()">登入</button>
                <button type="button" class="register-button" onclick="showRegister()">註冊</button>
            </form>
        </div>
        <!-- 註冊頁面 -->
        <div class="register-container hidden" id="registerContainer">
            <h2>建立會員資料</h2>
            <form id="registerForm">
                <input type="text" id="regName" placeholder="會員姓名" required>
                <input type="text" id="regPhone" placeholder="電話" required>
                <input type="text" id="regUsername" placeholder="帳號" required>
                <input type="password" id="regPassword" placeholder="密碼" required>
                <input type="number" id="regSize" placeholder="種地大小(公頃)" required>
                <button type="button" id="add">建立會員資料</button>
                <button type="button" onclick="showLogin()">登入</button>
            </form>
        </div>
        <!-- 歡迎訊息 -->
        <div class="welcome-message hidden" id="welcomeMessage">
            <h2 id="welcomeText"></h2>
            <a href="#" id="logoutLink" onclick="logout()">登出</a>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", checkLoginStatus);

        function checkLoginStatus() {
            if (sessionStorage.getItem("isLoggedIn") === "true") {
                showLoggedInUI(sessionStorage.getItem("username"));
            }
        }

        function showRegister() {
            document.getElementById("loginContainer").classList.add("hidden");
            document.getElementById("registerContainer").classList.remove("hidden");
        }

        function showLogin() {
            document.getElementById("registerContainer").classList.add("hidden");
            document.getElementById("loginContainer").classList.remove("hidden");
        }

        function login() {
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            fetch("login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    account: username,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    sessionStorage.setItem("isLoggedIn", "true");
                    sessionStorage.setItem("username", data.name);
                    showLoggedInUI(data.name);
                } else {
                    alert("帳號或密碼錯誤!");
                }
            })
            .catch(() => {
                alert("發生錯誤，無法連接伺服器!");
            });
        }

        function showLoggedInUI(username) {
            document.getElementById("welcomeText").innerText = username + " 您好!";
            document.getElementById("loginContainer").classList.add("hidden");
            document.getElementById("registerContainer").classList.add("hidden");
            document.getElementById("welcomeMessage").classList.remove("hidden");
            document.getElementById("navMenu").classList.remove("hidden");
        }

        function logout() {
            sessionStorage.removeItem("isLoggedIn");
            sessionStorage.removeItem("username");
            document.getElementById("welcomeMessage").classList.add("hidden");
            document.getElementById("loginContainer").classList.remove("hidden");
            document.getElementById("navMenu").classList.add("hidden");
        }

        document.querySelector("#add").onclick = () => {
            const name = document.getElementById("regName").value.trim();
            const phone = document.getElementById("regPhone").value.trim();
            const account = document.getElementById("regUsername").value.trim();
            const password = document.getElementById("regPassword").value.trim();
            const size = document.getElementById("regSize").value.trim();

            if (!name || !phone || !account || !password || !size) {
                alert("所有欄位都必須填寫！");
                return;
            }

            fetch("add.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    name: name,
                    phone: phone,
                    account: account,
                    password: password,
                    size: size
                })
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "true") {
                    alert("新增成功!");
                    window.location.replace("index.php");
                } else {
                    alert("新增失敗!");
                }
            })
            .catch(() => {
                alert("發生錯誤，無法連接伺服器!");
            });
        };
    </script>
</body>
</html>
