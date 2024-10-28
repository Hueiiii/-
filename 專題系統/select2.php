<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// 接收前端傳送的 JSON 資料
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['startDate']) || !isset($input['endDate']) || !isset($input['types'])) {
    echo json_encode(["error" => "無效的請求參數"]);
    exit();
}

$startDate = $input['startDate'];
$endDate = $input['endDate'];
$types = $input['types']; // 選擇的數據類型陣列

$servername = "localhost";
$username = "Hueiii";
$password = "1234";
$database = "病蟲害偵測及警示整合系統";
$dbport = "3306";

// 建立資料庫連接
$conn = new mysqli($servername, $username, $password, $database, $dbport);
if ($conn->connect_error) {
    echo json_encode(["error" => "連接失敗: " . $conn->connect_error]);
    exit();
}
$conn->set_charset("utf8mb4");

// 根據選擇的數據類型來動態構建 SQL 查詢
$columns = implode(", ", $types); // 將選擇的類型組合成查詢列
$sql = "SELECT date, $columns FROM `紀錄資料` WHERE date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "查詢準備失敗: " . $conn->error]);
    exit();
}

$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

$data = ["dates" => []];
foreach ($types as $type) {
    $data[$type] = [];
}

// 讀取資料並轉換為 JSON
while ($row = $result->fetch_assoc()) {
    $data["dates"][] = $row["date"];
    foreach ($types as $type) {
        $data[$type][] = $row[$type];
    }
}

$stmt->close();
$conn->close();

echo json_encode($data);
exit;
