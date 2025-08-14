<?php
include 'db.php';

// Verify code exists and is 6 chars
if (!isset($_GET['code']) || !preg_match('/^[a-zA-Z0-9]{6}$/', $_GET['code'])) {
    header("Location: index.php?error=invalid_url");
    exit();
}

$code = $_GET['code'];

try {
    // Look up URL
    $stmt = $conn->prepare("SELECT long_url FROM urls WHERE short_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->bind_result($long_url);
    
    if ($stmt->fetch()) {
        // Update click count
        $update = $conn->query("UPDATE urls SET clicks = clicks + 1 WHERE short_code = '$code'");
        
        // Redirect
        header("Location: $long_url", true, 301);
        exit();
    } else {
        header("Location: index.php?error=url_not_found");
        exit();
    }
} catch (Exception $e) {
    error_log("Redirect Error: " . $e->getMessage());
    header("Location: index.php?error=server_error");
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>