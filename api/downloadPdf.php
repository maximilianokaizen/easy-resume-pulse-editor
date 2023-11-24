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

$requiredParams = ['token', 'htmlContent', 'template'];

foreach ($requiredParams as $param) {
    validateParameter($param);
}

$token = $requestData['token'];
$htmlWithoutCss = $requestData['htmlContent'];
$template = $requestData['template'];

// Obtener el CSS personalizado del template desde la base de datos
$customCss = getTemplateCustomCss($template);

// Insertar el CSS dentro de los tags <head> y </head> del HTML
$htmlWithCss = insertCssIntoHtmlHead($htmlWithoutCss, $customCss);

$dompdf = new Dompdf();
$dompdf->loadHtml($htmlWithCss);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream();

function getTemplateCustomCss($template) {
    
    return "
    @import url(https://fonts.cdnfonts.com/css/glacial-indifference-2');

    body {
      margin: 0;
      padding: 0;
    }
    
    .mt-2 {
      margin-top: 20px;
    }
    
    .mt-3 {
      margin-top: 30px;
    }
    
    .mb-2 {
      margin-bottom: 20px;
    }
    
    .container {
        width: 780px; 
        margin: 0 auto; 
        box-sizing: border-box; 
    }
    
    .box-1 {
      width: 30%;
      float: left;
      height: 100%;
      position: relative;
      border-right: 2px solid #a6a6a64e;
      padding-right: 37px;
      box-sizing: border-box;
    }
    
    .box-2 {
      width: 60%;
      float: right;
      height: 100%;
      position: relative;
      padding-left: 37px;
      box-sizing: border-box;
    }
    
    .subtitle {
      margin-bottom: 20px;
      margin-top: 40px;
      font-family: 'Glacial Indifference', sans-serif;
      letter-spacing: 3.5px;
      font-weight: bold;
    }
    
    .img-profile {
      width: 188px;
      height: 188px;
      border-radius: 50%;
      margin-bottom: 15px;
    }
    
    .list-contact {
      list-style: none;
      padding-left: 0;
      font-family: 'Roboto', sans-serif;
    }
    
    .list-contact li {
      margin-top: 5px;
    }
    
    h1 {
      font-size: 54px;
      line-height: 30px;
      font-family: 'Playfair Display', serif;
      font-weight: 100;
    }
    
    .list-experience {
      padding-left: 25px;
      font-family: 'Roboto', sans-serif;
    }
    
    .separator,
    hr {
      margin: 18px 0;
      border: none;
      border-top: 1px solid #a6a6a64e;
    }
    ";
}

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
