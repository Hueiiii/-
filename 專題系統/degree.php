<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>病蟲害偵測及警示整合系統 - 儀表圖</title>
    <link rel="stylesheet" href="degree.css">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- 引入 jQuery -->
</head>

<body>
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
        <div id="pestCountGauge" class="chart-container"></div>
        <div id="pestSeverityGauge" class="chart-container"></div>
    </div>

    <script>
        window.onload = () => {
            // 使用 AJAX 獲取最新的病蟲數量和蟲害程度
            $.ajax({
                url: 'select.php', // 指向 select.php
                type: 'GET',
                dataType: 'json',
                success: (data) => {
                    // 假設資料結構中 'amount' 是一個數字
                    const pestCount = data.amount[data.amount.length - 1]; // 獲取最新的病蟲數量
                    const severityLevel = getSeverityLevel(pestCount); // 根據病蟲數量獲取蟲害程度

                    // 更新病蟲數量儀表圖
                    const pestCountGaugeData = [{
                        type: "indicator",
                        mode: "gauge+number",
                        value: pestCount,
                        title: { text: "病蟲數量", font: { size: 24 } },
                        gauge: {
                            axis: { range: [0, 400], tickwidth: 1, tickcolor: "darkblue" },
                            bar: { color: "darkblue" },
                            steps: [
                                { range: [0, 100], color: "#32CD32" },
                                { range: [100, 200], color: "#00BFFF" },
                                { range: [200, 300], color: "#FFA500" },
                                { range: [300, 400], color: "#FF4500" }
                            ],
                            threshold: {
                                line: { color: "red", width: 4 },
                                thickness: 0.75,
                                value: pestCount
                            }
                        }
                    }];

                    const pestCountGaugeLayout = {
                        width: 450, // 調整寬度
                        height: 350, // 調整高度
                        margin: { t: 0, b: 0 }
                    };

                    Plotly.newPlot('pestCountGauge', pestCountGaugeData, pestCountGaugeLayout);

                    // 更新蟲害程度儀表圖
                    const pestSeverityGaugeData = [{
                        type: "indicator",
                        mode: "gauge+number",
                        value: severityLevel,
                        title: { text: "蟲害程度", font: { size: 24 } },
                        gauge: {
                            axis: { range: [0, 4], tickwidth: 1, tickcolor: "darkblue" },
                            bar: { color: "darkblue" },
                            steps: [
                                { range: [0, 1], color: "#32CD32" }, // 低程度
                                { range: [1, 2], color: "#00BFFF" }, // 中低程度
                                { range: [2, 3], color: "#FFA500" }, // 中高程度
                                { range: [3, 4], color: "#FF4500" }  // 高程度
                            ],
                            threshold: {
                                line: { color: "red", width: 4 },
                                thickness: 0.75,
                                value: severityLevel
                            }
                        }
                    }];

                    const pestSeverityGaugeLayout = {
                        width: 450, // 調整寬度
                        height: 350, // 調整高度
                        margin: { t: 0, b: 0 }
                    };

                    Plotly.newPlot('pestSeverityGauge', pestSeverityGaugeData, pestSeverityGaugeLayout);
                },
                error: (xhr, status, error) => {
                    console.error("獲取數據失敗: ", error);
                }
            });
        };

        // 根據病蟲數量計算蟲害程度
        function getSeverityLevel(amount) {
            if (amount < 16) {
                return 1; // 一級
            } else if (amount >= 16 && amount < 64) {
                return 2; // 二級
            } else if (amount >= 64 && amount < 256) {
                return 3; // 三級
            } else {
                return 4; // 四級
            }
        }
    </script>
</body>
</html>
