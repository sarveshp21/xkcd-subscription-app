<?php

/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode() {
  return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Send a verification code to an email.
 */
function sendVerificationEmail($email, $code) {
  $subject = 'Your Verification Code';
  $message = "<p>Your verification code is: <strong>$code</strong></p>";
  $headers  = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= 'From: no-reply@example.com' . "\r\n";

  mail($email, $subject, $message, $headers);
}

/**
 * Register an email by storing it in a file.
 */
function registerEmail($email) {
  $file = __DIR__ . '/registered_emails.txt';

  // Check if email already exists
  $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  if (in_array($email, $emails)) {
      return false; // already registered
  }

  // Add new email to the file
  file_put_contents($file, $email . PHP_EOL, FILE_APPEND | LOCK_EX);
  return true;
}

/**
 * Unsubscribe an email by removing it from the list.
 */
function unsubscribeEmail($email) {
  $file = __DIR__ . '/registered_emails.txt';

  // Read all emails into an array
  $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

  // Remove the given email
  $updatedEmails = array_filter($emails, function($e) use ($email) {
      return trim($e) !== trim($email);
  });

  // Save the updated list back to the file
  file_put_contents($file, implode(PHP_EOL, $updatedEmails) . PHP_EOL, LOCK_EX);
}

function verifyCode($email, $code) {
  $file = __DIR__ . '/codes.json';

  // Load existing codes
  $codes = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

  // Check if code matches for the email
  if (isset($codes[$email]) && $codes[$email] == $code) {
      unset($codes[$email]); // remove it after successful verification
      file_put_contents($file, json_encode($codes));
      return true;
  }

  return false;
}

/**
 * Fetch random XKCD comic and format data as HTML.
 */
function fetchAndFormatXKCDData(): string {
  // Get the latest comic number
  $latestComicData = json_decode(file_get_contents('https://xkcd.com/info.0.json'), true);
  $latestComicNum = $latestComicData['num'];

  // Pick a random comic number
  $randomComicNum = random_int(1, $latestComicNum);

  // Fetch random comic data
  $comicData = json_decode(file_get_contents("https://xkcd.com/$randomComicNum/info.0.json"), true);

  // Build HTML content
  $html = "<h2>XKCD Comic</h2>";
  $html .= "<img src=\"{$comicData['img']}\" alt=\"XKCD Comic\">";
  $html .= "<p><a href=\"#\" id=\"unsubscribe-button\">Unsubscribe</a></p>";

  return $html;
}

/**
 * Send the formatted XKCD updates to registered emails.
 */
function sendXKCDUpdatesToSubscribers(): void {
  $file = __DIR__ . '/registered_emails.txt';

  // Fetch comic HTML
  $comicHtml = fetchAndFormatXKCDData();

  // Email headers
  $headers  = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= 'From: no-reply@example.com' . "\r\n";

  // Read all emails
  $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

  // Send email to each subscriber
  foreach ($emails as $email) {
      $subject = 'Your XKCD Comic';
      mail($email, $subject, $comicHtml, $headers);
  }
}
