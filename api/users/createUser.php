<?php
// check auth

declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// libs
require_once('../lib/sanatize/sanatize.php');
include_once('../lib/db/dbConnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $jsonData = json_decode(file_get_contents('php://input'), true);
        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar el JSON: " . json_last_error_msg());
        }
    
        $defaults = [
            'premium' => false,
            'plan' => 'free',
            'plan_start' => date('Y-m-d H:i:s'), 
            'plan_end' => null,
            'social' => 0,
            'social_name' => '',
            'user_active' => false,
            'created_at' => date('Y-m-d H:i:s'), 
            'deleted_at' => null,
            'modified_at' => null
        ];
    
        $validPlans = ['free', 'month', 'year', 'unique_pay'];
        $validSocialNames = ['google', 'linkedin', 'facebook', null];
    
        $userData = [
            'uuid' => sanitizeInput($jsonData['uuid'] ?? ''),
            'email' => sanitizeInput($jsonData['email'] ?? ''),
            'password' => sanitizeInput(md5($jsonData['password'])),
            'name' => sanitizeInput($jsonData['name'] ?? ''),
            'premium' => isset($jsonData['premium']) ? (bool) $jsonData['premium'] : $defaults['premium'],
            'plan' => in_array($jsonData['plan'] ?? '', $validPlans) ? sanitizeInput($jsonData['plan']) : $defaults['plan'],
            'plan_start' => $defaults['plan_start'],
            'plan_end' => $defaults['plan_end'],
            'social' => isset($jsonData['social']) ? (bool) $jsonData['social'] : $defaults['social'],
            'social_name' => in_array($jsonData['social_name'] ?? '', $validSocialNames) ? sanitizeInput($jsonData['social_name']) : $defaults['social_name'],
            'user_active' => isset($jsonData['user_active']) ? (bool) $jsonData['user_active'] : $defaults['user_active'],
            'created_at' => $defaults['created_at'],
            'deleted_at' => $defaults['deleted_at'],
            'modified_at' => $defaults['modified_at']
        ];
        
        $params = [
            $userData['uuid'],
            $userData['email'],
            $userData['password'],
            $userData['name'],
            $userData['premium'],
            $userData['plan'],
            $userData['plan_start'],
            $userData['plan_end'],
            $userData['social'],
            $userData['social_name'],
            $userData['user_active'],
            $userData['created_at'],
            $userData['deleted_at'],
            $userData['modified_at']
        ];

        $query = "INSERT INTO users (uuid, email, password, name, premium, plan, plan_start, plan_end, social, social_name, user_active, created_at, deleted_at, modified_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $db = new DatabaseConnector();

        try {
            $result = $db->executeQuery($query, $params);
            if ($result === null) {
                die(json_encode(['success' => true])); 
            }
        }catch (Exception $e) {
            die(json_encode(['success' => false, 'error' => $e->getMessage()]));
        }
    
        die(json_encode(['success' => true]));
        // Resto del código para ejecutar la consulta y manejar la inserción en la base de datos
    } catch (Exception $e) {
        // Captura cualquier excepción y devuelve un mensaje de error
        die(json_encode(['success' => false, 'error' => $e->getMessage()]));
    }

    // Envía una respuesta de éxito
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Usuario insertado correctamente.']);
} else {
    // Envía una respuesta de error si no es POST
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido. Se esperaba una solicitud POST.']);
}
?>