<?php 
require 'config.php';
requireLogin();

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("DELETE FROM charts WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

header("Location: charts.php");
exit;
?>