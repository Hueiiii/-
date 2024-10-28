<?php
$servername = "localhost";
$username = "Hueiii"; // 確保此處的使用者名稱正確
$password = "1234";
$database = "病蟲害偵測及警示整合系統";
$dbport = "3306";

// 建立資料庫連接
$conn = new mysqli($servername, $username, $password, $database);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// 確保從 POST 請求中獲取資料
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$account = $_POST['account'] ?? '';
$password = $_POST['password'] ?? '';
$size = $_POST['size'] ?? 0;

// 準備 SQL 語句
$stmt = $conn->prepare("INSERT INTO `會員資料` (name, phone, account, password, size) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $name, $phone, $account, $password, $size); // 's' for string, 'i' for integer

// Execute query
if ($stmt->execute()) {
    echo "true"; // 或任何成功消息
} else {
    echo "false"; // 或任何錯誤消息
}

// 關閉聲明和連接
$stmt->close();
$conn->close();
?>
