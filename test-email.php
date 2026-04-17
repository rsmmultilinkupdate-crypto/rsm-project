<?php
/**
 * Email Test Script
 * Test if server can send emails
 */

echo "<h2>RSM Multilink - Email Test</h2>";

$to = "rsmmultilinkenquiry@gmail.com";
$subject = "Test Email from RSM Server - " . date('Y-m-d H:i:s');
$message = "This is a test email from RSM Multilink server.\n\n";
$message .= "If you receive this, server mail is working!\n\n";
$message .= "Server: " . $_SERVER['SERVER_NAME'] . "\n";
$message .= "PHP Version: " . phpversion() . "\n";
$message .= "Time: " . date('Y-m-d H:i:s') . "\n";

$headers = "From: RSM Multilink <noreply@rsmmultilink.com>\r\n";
$headers .= "Reply-To: noreply@rsmmultilink.com\r\n";
$headers .= "Return-Path: noreply@rsmmultilink.com\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Message-ID: <" . time() . "-test@rsmmultilink.com>\r\n";

echo "<p><strong>Testing email to:</strong> $to</p>";
echo "<p><strong>Subject:</strong> $subject</p>";

$result = mail($to, $subject, $message, $headers);

if ($result) {
    echo "<p style='color: green;'><strong>✓ mail() returned TRUE</strong></p>";
    echo "<p>Email command executed successfully.</p>";
    echo "<p><strong>Note:</strong> This doesn't guarantee delivery. Check your inbox/spam folder.</p>";
} else {
    echo "<p style='color: red;'><strong>✗ mail() returned FALSE</strong></p>";
    echo "<p>Email command failed. Server mail configuration issue.</p>";
}

echo "<hr>";
echo "<h3>Server Mail Configuration:</h3>";
echo "<pre>";
echo "sendmail_path: " . ini_get('sendmail_path') . "\n";
echo "SMTP: " . ini_get('SMTP') . "\n";
echo "smtp_port: " . ini_get('smtp_port') . "\n";
echo "</pre>";

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li>Check inbox: rsmmultilinkenquiry@gmail.com</li>";
echo "<li>Check spam folder</li>";
echo "<li>If not received, contact hosting provider to enable mail</li>";
echo "<li>Alternative: Use SMTP with correct credentials</li>";
echo "</ul>";

echo "<hr>";
echo "<p><em>Delete this file after testing for security.</em></p>";
?>
