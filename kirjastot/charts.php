<?php 
require 'config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Snellen Chart Editor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h2>List of charts</h2>
                <button onclick="newChart()" class="btn">+ Add chart</button>
            </div>

            <div class="chart-editor">
                <!-- LEFT SIDEBAR -->
                <div class="sidebar">
                    <div id="chartList"></div>
                </div>

                <!-- MAIN EDITOR -->
                <div>
                    <input type="text" id="chartName" placeholder="Chart name" 
                           style="width:100%; padding:12px; font-size:1.2rem; margin-bottom:15px;">

                    <div class="controls">
                        <button onclick="addRow()">+ Add row</button>
                        <button onclick="addColumn()">+ Add column</button>
                        <button onclick="randomizeAllLetters()">🔀 Randomize</button>
                        <button onclick="printChart()">🖨️ Print</button>
                        <button onclick="togglePublish()" id="publishBtn">Publish and share</button>
                    </div>

                    <div class="font-sizes" id="fontSizes"></div>
                    <div id="snellenPreview" class="snellen-chart"></div>

                    <div style="margin-top:20px; text-align:center;">
                        <button onclick="saveCurrentChart()" style="background:#28a745; padding:14px 32px; font-size:1.1rem;">
                            💾 Save Chart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const letters = ['C','D','E','F','L','O','P','T','Z'];
        let currentChart = { id: null, name: "", rows: [], is_public: false };
        let currentChartId = null;

        function randomLetter() {
            return letters[Math.floor(Math.random() * letters.length)];
        }

        function createDefaultRows(count = 5) {
            const rows = [];
            let size = 60;
            for (let i = 0; i < count; i++) {
                rows.push({
                    size: Math.max(12, size),
                    letters: Array.from({ length: 5 }, () => randomLetter())
                });
                size -= 10;
            }
            return rows;
        }

        function randomizeAllLetters() {
            currentChart.rows.forEach(row => {
                row.letters = row.letters.map(() => randomLetter());
            });
            renderChart();
        }

        async function loadChartList() {
            try {
                const res = await fetch('load_charts.php');
                const charts = await res.json();
                
                let html = '';
                charts.forEach(chart => {
                    html += `
                        <div class="chart-item ${chart.id == currentChartId ? 'active' : ''}" 
                             onclick="loadChart(${chart.id})" style="display:flex; justify-content:space-between; align-items:center; padding:10px;">
                            <span>${chart.name}</span>
                            <button onclick="event.stopImmediatePropagation(); deleteChart(${chart.id});" 
                                    style="background:#dc3545; color:white; border:none; padding:5px 8px; font-size:0.8rem; border-radius:3px; cursor:pointer;">
                                🗑
                            </button>
                        </div>`;
                });
                document.getElementById('chartList').innerHTML = html || '<p style="color:#999; padding:15px;">Ei kaavioita vielä</p>';
            } catch(e) {
                console.error(e);
            }
        }

        async function loadChart(id) {
            currentChartId = id;
            const res = await fetch(`load_chart.php?id=${id}`);
            const data = await res.json();
            
            currentChart = {
                id: data.id,
                name: data.name,
                rows: JSON.parse(data.data),
                is_public: data.is_public
            };
            
            document.getElementById('chartName').value = currentChart.name;
            document.getElementById('publishBtn').textContent = currentChart.is_public ? '✓ Published' : 'Publish and share';
            renderChart();
            loadChartList();
        }

        async function deleteChart(id) {
            if (!confirm('Haluatko varmasti poistaa tämän kaavion pysyvästi?')) return;
            
            try {
                const res = await fetch('delete_chart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`
                });
                
                const result = await res.text();
                
                if (result.includes("success") || res.ok) {
                    alert('Kaavio poistettu onnistuneesti');
                    loadChartList();
                    
                    if (currentChartId === id) {
                        newChart();
                    }
                } else {
                    alert('Poisto epäonnistui');
                }
            } catch(e) {
                alert('Virhe poistettaessa: ' + e.message);
            }
        }

        function newChart() {
            currentChart = { id: null, name: "New Snellen Chart", rows: createDefaultRows(), is_public: false };
            currentChartId = null;
            document.getElementById('chartName').value = currentChart.name;
            document.getElementById('publishBtn').textContent = 'Publish and share';
            renderChart();
            loadChartList();
        }

        function addRow() {
            const lastSize = currentChart.rows.length ? currentChart.rows[currentChart.rows.length-1].size : 60;
            currentChart.rows.push({
                size: Math.max(12, lastSize - 10),
                letters: Array.from({ length: 5 }, () => randomLetter())
            });
            renderChart();
        }

        function addColumn() {
            currentChart.rows.forEach(row => row.letters.push(randomLetter()));
            renderChart();
        }

        function deleteRow(index) {
            if (confirm(`Poistetaanko rivi ${index + 1}?`)) {
                currentChart.rows.splice(index, 1);
                renderChart();
            }
        }

        function changeFontSize(index) {
            const currentSize = currentChart.rows[index].size;
            const newSize = prompt(`Uusi koko riville ${index + 1}:`, currentSize);
            if (newSize !== null) {
                const parsed = parseInt(newSize);
                if (!isNaN(parsed) && parsed > 5) {
                    currentChart.rows[index].size = parsed;
                    renderChart();
                }
            }
        }

        function renderChart() {
            const container = document.getElementById('snellenPreview');
            let html = '';

            currentChart.rows.forEach((row, index) => {
                html += `<div class="row" style="font-size: ${row.size}px; position:relative;">`;
                row.letters.forEach(letter => html += `<span class="letter">${letter}</span>`);
                
                html += `
                    <button onclick="deleteRow(${index}); event.stopImmediatePropagation();" 
                            style="position:absolute; top:8px; right:8px; background:#dc3545; color:white; border:none; padding:3px 8px; font-size:0.9rem; cursor:pointer; border-radius:3px;">
                        ✕
                    </button>`;
                html += `</div>`;
            });

            container.innerHTML = html || '<p style="padding:100px 20px; text-align:center; color:#888;">+ Add row aloittaaksesi</p>';

            let sizeHTML = '<strong>Rivien fonttikoot:</strong><br><br>';
            currentChart.rows.forEach((row, i) => {
                sizeHTML += `<div class="font-size-btn" onclick="changeFontSize(${i})">${row.size}</div>`;
            });
            document.getElementById('fontSizes').innerHTML = sizeHTML;
        }

        async function saveCurrentChart() {
            const name = document.getElementById('chartName').value.trim() || "Untitled Chart";
            const data = JSON.stringify(currentChart.rows);
            
            const formData = new FormData();
            formData.append('id', currentChart.id || '');
            formData.append('name', name);
            formData.append('data', data);
            formData.append('is_public', currentChart.is_public ? '1' : '0');
            
            await fetch('save_chart.php', { method: 'POST', body: formData });
            alert('✅ Kaavio tallennettu!');
            loadChartList();
        }

        function printChart() { window.print(); }
        function togglePublish() {
            currentChart.is_public = !currentChart.is_public;
            document.getElementById('publishBtn').textContent = currentChart.is_public ? '✓ Published' : 'Publish and share';
        }

        window.onload = () => {
            loadChartList();
            newChart();
        };
    </script>
</body>
</html>