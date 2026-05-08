<?php 
require 'config.php';

if ($_POST) {
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: charts.php");
        exit;
    } else {
        $error = "Virheelliset tunnukset!";
    }
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Kirjaudu sisään</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="card" style="max-width:400px; margin:100px auto;">
        <h2>Kirjaudu sisään</h2>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Sähköposti" required style="width:100%; padding:10px; margin:10px 0;">
            <input type="password" name="password" placeholder="Salasana" required style="width:100%; padding:10px; margin:10px 0;">
            <button type="submit" style="width:100%; padding:12px;">Kirjaudu</button>
        </form>
        <p style="margin-top:15px;">Ei tiliä? <a href="register.php">Rekisteröidy täällä</a></p>
    </div>
</div>
</body>
</html>