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
    $db = new DatabaseConnector();
    $success = [
        'success' => true,
        'message' => 'Conexión exitosa a la base de datos.'
    ];
    header('Content-Type: application/json');
    echo json_encode($success);
} catch (Exception $e) {
    $error = [
        'success' => false,
        'message' => 'Error en la conexión: ' . $e->getMessage()
    ];
    header('Content-Type: application/json');
    echo json_encode($error);
}