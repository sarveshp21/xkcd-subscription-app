<?php
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Request unsubscribe code
    if (isset($_POST['unsubscribe_email']) && !isset($_POST['verification_code'])) {
        $email = trim($_POST['unsubscribe_email']);
        $code = generateVerificationCode();

        // Save code to codes.json
        $codesFile = __DIR__ . '/codes.json';
        $codes = file_exists($codesFile) ? json_decode(file_get_contents($codesFile), true) : [];
        $codes[$email] = $code;
        file_put_contents($codesFile, json_encode($codes));

        // Send unsubscribe confirmation email
        $subject = 'Confirm Un-subscription';
        $message = "<p>To confirm un-subscription, use this code: <strong>$code</strong></p>";
        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: no-reply@example.com' . "\r\n";

        mail($email, $subject, $message, $headers);

        echo "<p>Unsubscribe confirmation code sent to $email!</p>";
    }

    // Handle unsubscribe verification
    if (isset($_POST['unsubscribe_email'], $_POST['verification_code'])) {
        $email = trim($_POST['unsubscribe_email']);
        $code = trim($_POST['verification_code']);

        if (verifyCode($email, $code)) {
            unsubscribeEmail($email);
            echo "<p>Email unsubscribed successfully!</p>";
        } else {
            echo "<p>Invalid un-subscription code!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe</title>
</head>
<body>
    <h2>Unsubscribe</h2>
    
    <!-- Unsubscribe Request Form -->
    <form method="post">
        <label for="unsubscribe_email">Email:</label>
        <input type="email" name="unsubscribe_email" required>
        <button type="submit" id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <h2>Confirm Unsubscribe</h2>

    <!-- Unsubscribe Verification Code Form -->
    <form method="post">
        <label for="unsubscribe_email">Email:</label>
        <input type="email" name="unsubscribe_email" required>
        <br>
        <label for="verification_code">Verification Code:</label>
        <input type="text" name="verification_code" maxlength="6" required>
        <button type="submit" id="submit-verification">Verify</button>
    </form>
</body>
</html>
