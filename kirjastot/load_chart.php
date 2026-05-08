<?php
require 'config.php';
requireLogin();

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM charts WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$chart = $stmt->fetch();

if (!$chart) {
    echo json_encode(['error' => 'Not found']);
    exit;
}

header('Content-Type: application/json');
echo json_encode($chart);
?>