<?php
/*
declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include_once('api/internal/email/Email.php');

// Example usage:
$email = new Email();
$recipient = 'rossi.maxi@gmail.com';
$subject = 'Hello World';
$content = '<p>Helllooooo</p>';

$result = $email->sendEmail($recipient, $subject, $content);
echo $result;