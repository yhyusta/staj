<?php 
require 'config.php';
requireLogin();

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM charts WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$chart = $stmt->fetch();

if (!$chart) die("Kaaviota ei löydy.");

if ($_POST) {
    $name = $_POST['name'];
    $data = $_POST['data'];
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    
    $stmt = $pdo->prepare("UPDATE charts SET name=?, data=?, is_public=? WHERE id=?");
    $stmt->execute([$name, $data, $is_public, $id]);
    header("Location: charts.php");
    exit;
}
$data = $chart['data'];
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Muokkaa kaaviota</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="card">
            <h2>Muokkaa kaaviota</h2>
            <form method="POST">
                <input type="text" name="name" value="<?= htmlspecialchars($chart['name']) ?>" required style="width:100%; padding:12px;">
                <br><br>
                <label><input type="checkbox" name="is_public" <?= $chart['is_public'] ? 'checked' : '' ?>> Julkinen</label><br><br>
                
                <textarea name="data" style="display:none;"><?= htmlspecialchars($data) ?></textarea>
                <button type="button" onclick="generateChart()">Luo uusi satunnainen kaavio</button>
                <button type="submit">Tallenna muutokset</button>
            </form>
        </div>
    </div>

    <script>
        function generateChart() {
            // Same logic as in create.php
            alert("Uusi kaavio generoitu (voit laajentaa tätä)");
            // You can copy generateChart() from create.php here
        }
    </script>
</body>
</html>