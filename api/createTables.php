<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('lib/db/dbConnection.php');

try {
    $dbConnector = new DatabaseConnector();
  
    // Array con las consultas SQL de creación de tablas
    $queries = [
        // Users
        /*
        "CREATE TABLE IF NOT EXISTS users (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            uuid VARCHAR(255) UNIQUE NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            premium BOOLEAN DEFAULT FALSE NOT NULL,
            plan VARCHAR(255) DEFAULT '' NOT NULL,
            plan_start DATETIME NOT NULL,
            plan_end DATETIME NOT NULL,
            social BOOLEAN DEFAULT FALSE NOT NULL,
            social_name VARCHAR(255) DEFAULT '' NULL,
            user_active BOOLEAN DEFAULT FALSE NOT NULL,
            created_at DATETIME NULL,
            deleted_at DATETIME NULL,
            modified_at DATETIME NULL
        )",
        */
        // Templates
        /*
        "CREATE TABLE templates (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            uuid VARCHAR(255) UNIQUE NOT NULL,
            name VARCHAR(255) NOT NULL,
            html TEXT NOT NULL,
            css TEXT NOT NULL,
            premium BOOLEAN DEFAULT FALSE NOT NULL,
            created_at DATETIME NULL,
            deleted_at DATETIME NULL,
            modified_at DATETIME NULL
        );",
        */
        // Resumes
        "CREATE TABLE resumes (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            uuid VARCHAR(255) UNIQUE NOT NULL,
            user_id INT(11) NOT NULL,
            html TEXT NOT NULL,
            css TEXT NOT NULL,
            created_at DATETIME NULL,
            deleted_at DATETIME NULL,
            modified_at DATETIME NULL,
            FOREIGN KEY (user_id) REFERENCES users(id)
        );"
    ];

    $success = true;
    $error_message = '';

    // Ejecutar cada consulta y verificar si hay errores
    foreach ($queries as $query) {
        $result = $dbConnector->executeQuery($query);
        if ($result === null) {
            $success = false;
            $error_message = 'Hubo un error al ejecutar una o más consultas.';
            break; // Si hay un error, detener la ejecución
        }
    }

    // Verificar si todas las consultas se ejecutaron correctamente
    if ($success) {
        $response = ['success' => true, 'message' => 'Todas las consultas se ejecutaron correctamente.'];
    } else {
        $response = ['success' => false, 'message' => $error_message];
    }

    // Mostrar la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    // Cerrar la conexión a la base de datos al finalizar
    //$dbConnector->disconnect();

} catch (Exception $e) {
    $error = [
        'success' => false,
        'message' => 'Error en la conexión: ' . $e->getMessage()
    ];
    header('Content-Type: application/json');
    echo json_encode($error);
}