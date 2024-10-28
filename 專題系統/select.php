<?php
$servername = "localhost";
$username = "Hueiii";
$password = "1234";
$database = "病蟲害偵測及警示整合系統";
$dbport = "3306";

// 建立資料庫連接
$conn = new mysqli($servername, $username, $password, $database, $dbport);

// 檢查連接是否成功
if ($conn->connect_error) {
    echo json_encode(["error" => "連接失敗: " . $conn->connect_error]);
    exit();
}
$conn->set_charset("utf8mb4");

// 查詢資料
$sql = "SELECT * FROM `紀錄資料`";
$select_all = $conn->query($sql);

if (!$select_all) {
    echo json_encode(["error" => "查詢失敗: " . $conn->error]);
    exit();
}

// 讀取資料並轉換為 JSON
$data = array();
$data['dataId'] = array();
$data['date'] = array();
$data['ambientTemperature'] = array();
$data['ambientHumidity'] = array();
$data['soilTemperature'] = array();
$data['soilMoistureContent'] = array();
$data['amount'] = array();

while ($text = $select_all->fetch_assoc()) {
    array_push($data['dataId'], $text['dataId']);
    array_push($data['date'], $text['date']);
    array_push($data['ambientTemperature'], $text['ambientTemperature']);
    array_push($data['ambientHumidity'], $text['ambientHumidity']);
    array_push($data['soilTemperature'], $text['soilTemperature']);
    array_push($data['soilMoistureContent'], $text['soilMoistureContent']);
    array_push($data['amount'], $text['amount']);
}

$conn->close();
echo json_encode($data);
?>
