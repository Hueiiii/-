<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>病蟲害偵測及警示整合系統</title>
    <link rel="stylesheet" href="results.css">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
</head>
<body>
    <!-- 導覽列 -->
    <div class="navbar">
        <h1>病蟲害偵測及警示整合系統</h1>
        <nav>
            <a href="index.php">首頁</a>
            <a href="results.php">偵測結果</a>
            <a href="degree.php">蟲害程度</a>
            <a href="history.php">歷史紀錄</a>
        </nav>
    </div>

    <div class="content">
        <!-- 儀表圖的容器，分為上下兩排 -->
        <div class="gauge-container">
            <!-- 上排：環境儀表圖 -->
            <div class="row">
                <div id="gaugeEnvTemp"></div>
                <div id="gaugeEnvHumidity"></div>
            </div>
            <!-- 下排：土壤儀表圖 -->
            <div class="row">
                <div id="gaugeSoilTemp"></div>
                <div id="gaugeSoilMoisture"></div>
            </div>
        </div>
    </div>

    <script>
        window.onload = () => {
            fetch("select.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);  // 用於檢查數據格式

                // 獲取最新的數據
                const ambientTemperature = data["ambientTemperature"].slice(-1)[0];  // 最新的環境溫度
                const ambientHumidity = data["ambientHumidity"].slice(-1)[0];        // 最新的環境濕度
                const soilTemperature = data["soilTemperature"].slice(-1)[0];        // 最新的土壤溫度
                const soilMoisture = data["soilMoistureContent"].slice(-1)[0];       // 最新的土壤濕度

                // 環境溫度儀表圖
                var envTempGauge = {
                    type: "indicator",
                    mode: "gauge+number",
                    value: ambientTemperature,
                    title: { text: "環境溫度 (°C)", font: { size: 24 } },
                    gauge: {
                        axis: { range: [0, 50], tickwidth: 1 },
                        bar: { color: "darkblue" },
                        steps: [
                            { range: [0, 10], color: "#4CAF50" },
                            { range: [10, 20], color: "#00BCD4" },
                            { range: [20, 30], color: "#FFC107" },
                            { range: [30, 50], color: "#F44336" }
                        ]
                    }
                };

                // 環境濕度儀表圖
                var envHumidityGauge = {
                    type: "indicator",
                    mode: "gauge+number",
                    value: ambientHumidity,
                    title: { text: "環境濕度 (%)", font: { size: 24 } },
                    gauge: {
                        axis: { range: [0, 100], tickwidth: 1 },
                        bar: { color: "darkblue" },
                        steps: [
                            { range: [0, 25], color: "#4CAF50" },
                            { range: [25, 50], color: "#00BCD4" },
                            { range: [50, 75], color: "#FFC107" },
                            { range: [75, 100], color: "#F44336" }
                        ]
                    }
                };

                // 土壤溫度儀表圖
                var soilTempGauge = {
                    type: "indicator",
                    mode: "gauge+number",
                    value: soilTemperature,
                    title: { text: "土壤溫度 (°C)", font: { size: 24 } },
                    gauge: {
                        axis: { range: [0, 50], tickwidth: 1 },
                        bar: { color: "darkblue" },
                        steps: [
                            { range: [0, 10], color: "#4CAF50" },
                            { range: [10, 20], color: "#00BCD4" },
                            { range: [20, 30], color: "#FFC107" },
                            { range: [30, 50], color: "#F44336" }
                        ]
                    }
                };

                // 土壤濕度儀表圖
                var soilMoistureGauge = {
                    type: "indicator",
                    mode: "gauge+number",
                    value: soilMoisture,
                    title: { text: "土壤濕度 (%)", font: { size: 24 } },
                    gauge: {
                        axis: { range: [0, 100], tickwidth: 1 },
                        bar: { color: "darkblue" },
                        steps: [
                            { range: [0, 25], color: "#4CAF50" },
                            { range: [25, 50], color: "#00BCD4" },
                            { range: [50, 75], color: "#FFC107" },
                            { range: [75, 100], color: "#F44336" }
                        ]
                    }
                };

                const layout = {
                    width: 300,
                    height: 300,
                    margin: { t: 30, b: 30 }
                };

                Plotly.newPlot('gaugeEnvTemp', [envTempGauge], layout);
                Plotly.newPlot('gaugeEnvHumidity', [envHumidityGauge], layout);
                Plotly.newPlot('gaugeSoilTemp', [soilTempGauge], layout);
                Plotly.newPlot('gaugeSoilMoisture', [soilMoistureGauge], layout);
            })
            .catch(error => {
                console.error('伺服器錯誤，無法載入', error);
            });
        };
    </script>
</body>
</html>