<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $basePath = '/home/easyre/public_html/en';
} else {   
    $basePath = '/var/www/html';
}
require_once($basePath . '/api/lib/sanatize/sanatize.php');
require_once($basePath . '/api/lib/db/dbConnection.php');
require_once($basePath . '/api/lib/token/TokenManager.php');

class Links
{
    public function getHtml(string $uuid): ?string {
        $uuid = sanitizeInput($uuid);
        $now = new DateTime();
        $dateToCompare = $now->format('Y-m-d H:i:s');
        $query = "SELECT * FROM links WHERE uuid = ? AND expiration_date > ?";
        $db = new DatabaseConnector();
        $link = $db->executeQuery($query, [$uuid, $dateToCompare]);
        if ($link === null || empty($link)) {
            return '';
        }else{
            $resumeUuid = $link[0]['resume_uuid'];
            $query = "SELECT html,css FROM resumes WHERE uuid = ?";
            $resume = $db->executeQuery($query, [$resumeUuid]);
            if ($resume === null || empty($resume)) {
                return '';
            }else{
                return $resume[0]['html'];
            }
        }
        return '';
    }
}



