<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $basePath = '/home/easyre/public_html/en/';
} else {   
    $basePath = '/var/www/html';
}

$emailPath = $basePath . '/api/internal/email/';

//require_once($basePath . '/api/lib/sanatize/sanatize.php');
//require_once($basePath . '/api/lib/token/TokenManager.php');
//require_once($emailPath . '/vendor/autoload.php');

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

class Email
{
    private static function getEnvValues(): array
    {
        $envFile = __DIR__ . '/.env';
        return parse_ini_file($envFile);
    }

    public function sendEmail($recipient, $subject = 'Contact from EasyResumePulse', $content = '')
    {
        $envValues = self::getEnvValues();
        try {
            $to = "rossi.maxi@gmail.com, kaizenpulse@gmail.com";
            $headers = "From: kaizenpulse@gmail.com"; 
            $message_email = $content;
 
            if (mail($to, $subject, $content, $headers)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // TODO LOG
            return false;
        }
    }

    public function sendEmailRegister($recipient, $subject = 'Contact from EasyResumePulse', $content = '')
    {
        try {
            $to = $recipient . ", rossi.maxi@gmail.com, kaizenpulse@gmail.com";
            $headers = "From: hello@easyresumepulse.com"; 
            $message_email = $content;
            if (mail($to, $subject, $content, $headers)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // TODO LOG
            return false;
        }
    }

}

