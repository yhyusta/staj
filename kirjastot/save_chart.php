<?php
require 'config.php';
requireLogin();

$id = $_POST['id'] ?? null;
$name = trim($_POST['name']);
$data = $_POST['data'];
$is_public = $_POST['is_public'] ?? 0;

if ($id) {
    // Update existing
    $stmt = $pdo->prepare("UPDATE charts SET name=?, data=?, is_public=? WHERE id=? AND user_id=?");
    $stmt->execute([$name, $data, $is_public, $id, $_SESSION['user_id']]);
} else {
    // Create new
    $stmt = $pdo->prepare("INSERT INTO charts (user_id, name, data, is_public) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $name, $data, $is_public]);
}

echo "Success";
?>