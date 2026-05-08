<?php require_once 'config.php'; ?>
<header>
    <div class="logo">Snellen Config</div>
    <nav>
        <a href="index.php">Etusivu</a>
        <a href="charts.php">Kaaviot</a>
        <?php if(isLoggedIn()): ?>
            <a href="logout.php">Kirjaudu ulos (<?= $_SESSION['email'] ?? '' ?>)</a>
        <?php else: ?>
            <a href="login.php">Kirjaudu</a>
            <a href="register.php">Rekisteröidy</a>
        <?php endif; ?>
    </nav>
</header>