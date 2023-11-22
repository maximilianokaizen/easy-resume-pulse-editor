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

require 'vendor/autoload.php';
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$jsonInput = file_get_contents('php://input');

$requestData = json_decode($jsonInput, true);

function validateParameter($paramName) {
    global $requestData;

    if (!isset($requestData[$paramName]) || empty($requestData[$paramName])) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => "El parámetro $paramName es requerido"));
        exit;
    }
}

$requiredParams = ['htmlContent', 'css'];

foreach ($requiredParams as $param) {
    validateParameter($param);
}

$html = $requestData['htmlContent'];
$css = $requestData['css'];

$htmlWithCss = insertCssIntoHtmlHead($html, $css);

$dompdf = new Dompdf();
$dompdf->loadHtml($htmlWithCss);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream();

function insertCssIntoHtmlHead($htmlWithoutCss, $customCss) {
    $headPattern = '/<head.*?>(.*?)<\/head>/is'; // Patrón para encontrar el contenido dentro de <head> y </head> tags

    if (preg_match($headPattern, $htmlWithoutCss, $matches)) {
        $headContent = $matches[1]; // Contenido dentro de <head> y </head> tags
        $modifiedHeadContent = "<style>$customCss</style>" . $headContent; // Inserta el CSS dentro del contenido de <head>
        $htmlWithCss = preg_replace($headPattern, "<head>$modifiedHeadContent</head>", $htmlWithoutCss); // Reemplaza el contenido de <head> con el contenido modificado
        return $htmlWithCss;
    }

    // Si no se encuentra el tag <head>, simplemente agrega el CSS al principio del HTML
    return "<style>$customCss</style>" . $htmlWithoutCss;
}
