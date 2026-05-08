<?php
require 'config.php';
requireLogin();

$stmt = $pdo->prepare("SELECT id, name, is_public FROM charts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$charts = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($charts);
?>