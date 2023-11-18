<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$jsonInput = file_get_contents('php://input');

// Decodifica el JSON a un array asociativo
$requestData = json_decode($jsonInput, true);

// Función para validar un parámetro
function validateParameter($paramName) {
    global $requestData;

    if (!isset($requestData[$paramName]) || empty($requestData[$paramName])) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => "El parámetro $paramName es requerido"));
        exit;
    }
}

$requiredParams = ['token', 'htmlContent', 'template'];

foreach ($requiredParams as $param) {
    validateParameter($param);
}

$token = $requestData['token'];
$html = $requestData['htmlContent'];
$template = $requestData['template'];

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream();