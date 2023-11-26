<?php

$email = new Email();
$recipient = 'rossi.maxi@gmail.com';
$subject = 'Hello World';
$content = '<p>Helllooooo</p>';

$result = $email->sendEmail($recipient, $subject, $content);
return $resul;
