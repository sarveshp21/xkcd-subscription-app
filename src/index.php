<?php 
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Handle Email Registration
    if (isset($_POST['email']) && !isset($_POST['verification_code'])) {
        $email = trim($_POST['email']);
        $code = generateVerificationCode();

        // Save code to codes.json
        $codesFile = __DIR__ . '/codes.json';
        $codes = file_exists($codesFile) ? json_decode(file_get_contents($codesFile), true) : [];
        $codes[$email] = $code;
        file_put_contents($codesFile, json_encode($codes));

        // Send verification email
        sendVerificationEmail($email, $code);

        echo "<p>Verification code sent to $email!</p>";
    }

    // Handle Email Verification for Subscription
    elseif (isset($_POST['email'], $_POST['verification_code'])) {
        $email = trim($_POST['email']);
        $code = trim($_POST['verification_code']);

        if (verifyCode($email, $code)) {
            registerEmail($email);
            echo "<p>Email verified and subscribed successfully!</p>";
        } else {
            echo "<p>Invalid verification code!</p>";
        }
    }

    // Handle Unsubscribe Request
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

    // Handle Unsubscribe Verification
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

// ✅ Handle sending XKCD updates to subscribers via GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['send_updates'])) {
    sendXKCDUpdatesToSubscribers();
    echo "<p>XKCD comics sent to all subscribers!</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>XKCD Subscription Service</title>
</head>
<body>

<h2>Subscribe with your Email</h2>
<form method="post">
    <input type="email" name="email" required>
    <button type="submit" id="submit-email">Submit</button>
</form>

<h2>Verify your Email</h2>
<form method="post">
    <input type="email" name="email" required>
    <input type="text" name="verification_code" maxlength="6" required>
    <button type="submit" id="submit-verification">Verify</button>
</form>

<h2>Unsubscribe</h2>
<form method="post">
    <input type="email" name="unsubscribe_email" required>
    <button type="submit" id="submit-unsubscribe">Unsubscribe</button>
</form>

<h2>Confirm Unsubscription</h2>
<form method="post">
    <input type="email" name="unsubscribe_email" required>
    <input type="text" name="verification_code" maxlength="6" required>
    <button type="submit" id="submit-verification">Verify</button>
</form>

<!-- ✅ Send Comics to Subscribers button -->
<h2>Send Comics to Subscribers</h2>
<form method="get">
    <input type="hidden" name="send_updates" value="1">
    <button type="submit">Send Comics</button>
</form>

</body>
</html>
