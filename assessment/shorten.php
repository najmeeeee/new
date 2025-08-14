<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['long_url'])) {
    $long_url = filter_var($_POST['long_url'], FILTER_VALIDATE_URL);
    
    if (!$long_url) {
        header("Location: index.php?error=Invalid+URL+format");
        exit();
    }

    // Generate unique short code
    do {
        $short_code = substr(md5(uniqid(rand(), true)), 0, 6);
        $stmt = $conn->prepare("SELECT id FROM urls WHERE short_code = ?");
        $stmt->bind_param("s", $short_code);
        $stmt->execute();
        $exists = $stmt->fetch();
        $stmt->close();
    } while ($exists);

    // Store in database
    $stmt = $conn->prepare("INSERT INTO urls (short_code, long_url) VALUES (?, ?)");
    $stmt->bind_param("ss", $short_code, $long_url);
    
    if ($stmt->execute()) {
        $short_url = "http://" . $_SERVER['HTTP_HOST'] . "/" . $short_code;
        header("Location: index.php?short=" . urlencode($short_url));
    } else {
        header("Location: index.php?error=Failed+to+create+short+URL");
    }
    
    $stmt->close();
    $conn->close();
    exit();
}

header("Location: index.php");
?>