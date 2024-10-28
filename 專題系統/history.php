<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>病蟲害偵測及警示整合系統</title>
    <link rel="stylesheet" href="history.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3"></script>
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

    <!-- 主內容區域 -->
    <div class="main-content">
        <!-- 選擇區域 -->
        <div class="content" id="selectionArea">
            <h2>選擇日期範圍和數據類型</h2>
            <label for="startDate">開始日期:</label>
            <input type="date" id="startDate">
            
            <label for="endDate">結束日期:</label>
            <input type="date" id="endDate">
            
            <fieldset>
                <legend>選擇數據類型:</legend>
                <label><input type="checkbox" name="dataType" value="ambientTemperature"> 環境溫度 (°C)</label>
                <label><input type="checkbox" name="dataType" value="ambientHumidity"> 環境濕度 (%)</label>
                <label><input type="checkbox" name="dataType" value="soilTemperature"> 土壤溫度 (°C)</label>
                <label><input type="checkbox" name="dataType" value="soilMoistureContent"> 土壤含水量 (%)</label>
                <label><input type="checkbox" name="dataType" value="amount"> 病蟲數量</label>
            </fieldset>
            
            <button onclick="fetchData()">顯示圖表</button>
        </div>

        <!-- 包含圖表和按鈕的容器 -->
        <div class="content" id="chartContainer" style="display: none;">
            <button onclick="showSelection()">返回選擇</button>
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        function fetchData() {
            const startDate = document.getElementById("startDate").value;
            const endDate = document.getElementById("endDate").value;
            const selectedTypes = Array.from(document.querySelectorAll("input[name='dataType']:checked")).map(checkbox => checkbox.value);

            if (!startDate || !endDate || selectedTypes.length === 0) {
                alert("請填寫完整的日期範圍並選擇至少一個數據類型");
                return;
            }

            fetch("select2.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ startDate, endDate, types: selectedTypes })
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.dates && selectedTypes.every(type => data[type])) {
                    renderCharts(data, selectedTypes);
                    showChart();  // 顯示圖表區域並隱藏選擇區域
                } else {
                    alert("無法獲取數據或數據格式不正確");
                    console.log("資料格式錯誤", data);
                }
            })
            .catch(error => {
                console.error("伺服器錯誤，無法載入", error);
            });
        }

        function renderCharts(data, selectedTypes) {
            const ctx = document.getElementById("myChart").getContext("2d");

            // 檢查並銷毀舊的圖表
            if (window.myChart instanceof Chart) {
                window.myChart.destroy();
            }

            // 設置圖表的數據集
            const datasets = selectedTypes.map((type, index) => ({
                label: getLabel(type),
                data: data.dates.map((date, idx) => ({ x: date, y: data[type][idx] })),
                fill: false,
                borderColor: getRandomColor(index),
                borderWidth: 1,
                pointRadius: 3,
                pointHoverRadius: 5
            }));

            // 建立新的圖表
            window.myChart = new Chart(ctx, {
                type: "line",
                data: {
                    datasets: datasets
                },
                options: {
                    responsive: true, // 啟用響應式設置
                    maintainAspectRatio: true, // 保持比例
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw.y}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: "time",
                            time: {
                                unit: "day"
                            },
                            title: {
                                display: true,
                                text: "日期"
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: "數值"
                            }
                        }
                    }
                }
            });
        }

        // 顯示圖表區域並隱藏選擇區域
        function showChart() {
            document.getElementById("selectionArea").style.display = "none";
            document.getElementById("chartContainer").style.display = "block";
        }

        // 顯示選擇區域並隱藏圖表區域
        function showSelection() {
            document.getElementById("selectionArea").style.display = "block";
            document.getElementById("chartContainer").style.display = "none";
        }

        function getLabel(dataType) {
            switch(dataType) {
                case "ambientTemperature": return "環境溫度 (°C)";
                case "ambientHumidity": return "環境濕度 (%)";
                case "soilTemperature": return "土壤溫度 (°C)";
                case "soilMoistureContent": return "土壤含水量 (%)";
                case "amount": return "病蟲數量";
                default: return "";
            }
        }

        function getRandomColor(index) {
            const colors = ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"];
            return colors[index % colors.length];
        }
    </script>
</body>
</html>
