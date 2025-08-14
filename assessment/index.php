<?php include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>URL Shortener</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>URL Shortener</h1>
        
        <form action="shorten.php" method="POST">
            <input type="url" name="long_url" placeholder="Enter long URL" required>
            <button type="submit">Shorten</button>
        </form>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['short'])): ?>
            <div class="result">
                <p>Short URL: <a href="<?= htmlspecialchars($_GET['short']) ?>" target="_blank">
                    <?= htmlspecialchars($_GET['short']) ?>
                </a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>