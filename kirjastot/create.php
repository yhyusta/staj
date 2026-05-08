<?php 
require 'config.php';
requireLogin();

function createDefaultChartData() {
    $letters = ['C','D','E','F','L','O','P','T','Z'];
    $data = [];
    for ($i = 0; $i < 11; $i++) {
        $size = max(12, 38 - $i * 2.4);
        $row = [];
        for ($j = 0; $j < 5; $j++) {
            $row[] = $letters[array_rand($letters)];
        }
        $data[] = ['size' => $size, 'letters' => $row];
    }
    return $data;
}

if ($_POST) {
    $name = trim($_POST['name']);
    $data = trim($_POST['data']);
    $is_public = isset($_POST['is_public']) ? 1 : 0;

    if ($data === '' || json_decode($data, true) === null && json_last_error() !== JSON_ERROR_NONE) {
        $data = json_encode(createDefaultChartData());
    }

    $stmt = $pdo->prepare("INSERT INTO charts (user_id, name, data, is_public) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $name, $data, $is_public]);
    
    header("Location: charts.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Luo uusi kaavio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="card">
            <h2>Luo uusi Snellen-kaavio</h2>
            <form method="POST">
                <input type="text" name="name" placeholder="Kaavion nimi" required style="width:100%; padding:12px; margin:10px 0;">
                
                <label><input type="checkbox" name="is_public"> Julkinen (kaikki näkevät ilman kirjautumista)</label><br><br>
                
                <textarea name="data" id="chartData" rows="8" style="width:100%; display:none;"></textarea>
                
                <button type="button" onclick="generateChart()">Luo Oletuskaavio</button>
                <button type="submit" style="background:#28a745;">Tallenna Kaavio</button>
            </form>

            <div id="preview" class="snellen-chart"></div>
        </div>
    </div>

    <script>
        function generateChart() {
            const letters = ['C','D','E','F','L','O','P','T','Z'];
            let html = '';
            let data = [];
            
            for (let i = 0; i < 11; i++) {
                const size = Math.max(12, 38 - i * 2.4);
                let row = [];
                let rowHtml = `<div class="row" style="font-size:${size}px">`;
                
                for (let j = 0; j < 5; j++) {
                    const letter = letters[Math.floor(Math.random() * letters.length)];
                    row.push(letter);
                    rowHtml += `<span class="letter">${letter}</span>`;
                }
                rowHtml += `</div>`;
                html += rowHtml;
                data.push({size: size, letters: row});
            }
            
            document.getElementById('preview').innerHTML = html;
            document.getElementById('chartData').value = JSON.stringify(data);
        }
    </script>
</body>
</html>