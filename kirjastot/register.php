<?php 
require 'config.php';

if ($_POST) {
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $pass]);
        header("Location: login.php?registered=1");
        exit;
    } catch(Exception $e) {
        $error = "Sähköposti on jo käytössä!";
    }
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Rekisteröidy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="card" style="max-width:400px; margin:100px auto;">
        <h2>Luo uusi tili</h2>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Sähköposti" required style="width:100%; padding:10px; margin:10px 0;">
            <input type="password" name="password" placeholder="Salasana" required style="width:100%; padding:10px; margin:10px 0;">
            <button type="submit" style="width:100%; padding:12px;">Rekisteröidy</button>
        </form>
    </div>
</div>
</body>
</html>