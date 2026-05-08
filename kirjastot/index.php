<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kirjastot Config</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="card">
            <h1>Tervetuloa kirjastot-kaavioiden hallintaan</h1>
            <p>Sovellus silmälääkäreille kirjastot-kaavioiden luomiseen, muokkaamiseen ja jakamiseen.</p>
            <?php if(isLoggedIn()): ?>
                <a href="charts.php"><button style="font-size:1.1rem; padding:14px 24px;">Siirry Kaavioihin →</button></a>
            <?php else: ?>
                <a href="login.php"><button style="font-size:1.1rem; padding:14px 24px;">Kirjaudu Sisään</button></a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>