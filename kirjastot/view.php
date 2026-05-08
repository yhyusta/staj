<?php 
require 'config.php';

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM charts WHERE id = ?");
$stmt->execute([$id]);
$chart = $stmt->fetch();

if (!$chart || (!$chart['is_public'] && (!isLoggedIn() || $chart['user_id'] !== $_SESSION['user_id']))) {
    die("Kaaviota ei löydy tai sinulla ei ole oikeuksia.");
}
$data = json_decode($chart['data'], true);
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($chart['name']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="card">
            <h2><?= htmlspecialchars($chart['name']) ?></h2>
            <button onclick="window.print()">🖨️ Tulosta</button>
            
            <div class="kirjastot-chart">
                <?php foreach($data as $row): ?>
                    <div class="row" style="font-size: <?= $row['size'] ?>px;">
                        <?php foreach($row['letters'] as $letter): ?>
                            <span class="letter"><?= htmlspecialchars($letter) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>