<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $basePath = '/home/easyre/public_html/en';
} else {   
    $basePath = '/var/www/html';
}
require_once($basePath . '/api/lib/sanatize/sanatize.php');
require_once($basePath . '/api/lib/db/dbConnection.php');
require_once($basePath . '/api/lib/token/TokenManager.php');

class Template
{
    public function getCss(int $id): ?string {
        $id = sanitizeInput($id);
        $query = "SELECT css FROM templates WHERE id = ? LIMIT 1";
        $db = new DatabaseConnector();
        $result = $db->executeQuery($query, [$id]);
        if ($result === null || empty($result)) {
            return null;
        }
        return $result[0]['css'];
    }
    
}


