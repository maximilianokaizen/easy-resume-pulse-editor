<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $basePath = '/home/easyre/public_html/en';
} else {   
    $basePath = '/var/www/html';
}

require_once($basePath . '/api/lib/sanatize/sanatize.php');
require_once($basePath . '/api/lib/db/dbConnection.php');
require_once($basePath . '/api/lib/token/TokenManager.php');

class User
{
    public function register(
        string $uuid,
        string $email,
        string $password,
        string $name,
        string $lastName,
        bool $premium = false,
        string $plan = 'free',
        bool $social = false,
        string $socialName = '',
        int $active = 0
    ): array {
        $defaults = [
            'premium' => false,
            'plan' => 'free',
            'social' => false,
            'socialName' => '',
            'active' => false,
            'plan_start' =>  date('Y-m-d H:i:s'),
        ];
    
        $userData = [
            'uuid' => sanitizeInput($uuid),
            'email' => sanitizeInput($email),
            'password' => sanitizeInput(md5($password)),
            'name' => sanitizeInput($name),
            'lastName' => sanitizeInput($lastName),
            'premium' => 0,
            'plan' => $plan,
            'plan_start' => date('Y-m-d H:i:s'),
            'social' => $social,
            'socialName' => sanitizeInput($socialName),
            'active' => $active,
            'created_at' => date('Y-m-d H:i:s'),
        ];
       
        $query = "INSERT INTO users (uuid, email, password, name, last_name, premium, plan, plan_start, social, social_name, user_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $userData['uuid'],
            $userData['email'],
            $userData['password'],
            $userData['name'],
            $userData['lastName'],
            $userData['premium'],
            $userData['plan'],
            $userData['plan_start'],
            $userData['social'],
            $userData['socialName'],
            $userData['active'],
            $userData['created_at'] = date('Y-m-d H:i:s'), // created_at
        ];

        $db = new DatabaseConnector();
        
        try {
            $result = $db->executeQuery($query, $params);
            if ($result === null) {
                return ['success' => true];
            }
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }

        return ['success' => true]; 
    }

    public function exist(string $email): bool {
        $query = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $db = new DatabaseConnector();
        $result = $db->executeQuery($query, [$email]);
        if ($result === null || empty($result)) {
            return false;
        }
        $userCount = (int)$result[0]['count'];
        return $userCount > 0;
    }

    public function getUuidByEmail(string $email): ?string {
        $query = "SELECT uuid FROM users WHERE email = ? AND user_active = 1 LIMIT 1";
        $db = new DatabaseConnector();
        $result = $db->executeQuery($query, [$email]);
        if ($result === null || empty($result)) {
            return null; // No se encontró ningún usuario activo con este correo electrónico
        }
        return $result[0]['uuid'];
    }

}


