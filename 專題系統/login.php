<?php
$servername = "localhost";
$username = "Hueiii"; // 請確保這裡的使用者名稱正確
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
$account = $_POST['account'] ?? '';
$password = $_POST['password'] ?? '';

// 使用準備語句以防止 SQL 注入
$stmt = $conn->prepare("SELECT name FROM `會員資料` WHERE account = ? AND password = ?");
$stmt->bind_param("ss", $account, $password);
$stmt->execute();
$result = $stmt->get_result();

$response = [];

if ($result->num_rows > 0) {
    // 帳號密碼匹配
    $row = $result->fetch_assoc();
    $response['success'] = true;
    $response['name'] = $row['name'];
} else {
    // 帳號密碼不匹配
    $response['success'] = false;
}

// 關閉聲明和連接
$stmt->close();
$conn->close();

// 返回 JSON 格式的回應
header('Content-Type: application/json');
echo json_encode($response);
?>
