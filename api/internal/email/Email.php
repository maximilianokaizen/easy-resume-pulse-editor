<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $basePath = '/home/easyre/public_html/en/';
} else {   
    $basePath = '/var/www/html';
}

$emailPath = $basePath . '/api/internal/email/';

require_once($basePath . '/api/lib/sanatize/sanatize.php');
require_once($basePath . '/api/lib/token/TokenManager.php');
require_once($emailPath . '/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email
{
    private static function getEnvValues(): array
    {
        $envFile = __DIR__ . '/.env';
        return parse_ini_file($envFile);
    }

    public function sendEmail($recipient, $subject, $content)
    {
        $envValues = self::getEnvValues();
        $mail = new PHPMailer(true);

        try {
            // Gmail SMTP server configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $envValues['EMAIL_ACCOUNT']; 
            $mail->Password = $envValues['EMAIL_PASSWORD']; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            // Email setup
            $mail->setFrom($envValues['EMAIL_ACCOUNT'], 'Sender');
            $mail->addAddress($recipient);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $content;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

