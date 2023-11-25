<?php
ini_set('memory_limit', '1024M'); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

require 'vendor/autoload.php';
require_once 'dompdf/autoload.inc.php';
require_once('internal/templates/Templates.php');

use Dompdf\Dompdf;

$jsonInput = file_get_contents('php://input');

$requestData = json_decode($jsonInput, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $jsonInput = file_get_contents('php://input');
  $requestData = json_decode($jsonInput, true);

  $requiredParams = ['token', 'htmlContent', 'template'];

  $token = $requestData['token'];
  $htmlWithoutCss = $requestData['htmlContent'];
  $template = $requestData['template'];
    
  $customCss = getTemplateCustomCss($template);
  $htmlWithCss = insertCssIntoHtmlHead($htmlWithoutCss, $customCss);
  
  $dompdf = new Dompdf();
  $dompdf->loadHtml($htmlWithCss);
  $dompdf->setPaper('A4', 'landscape');
  $dompdf->render();
  $dompdf->stream();
}

function getTemplateCustomCss($templateId) {
    $template = new Template();
    $css = $template->getCss(intval($templateId));
    return $css;
}

function insertCssIntoHtmlHead($htmlWithoutCss, $customCss) {
    $headPattern = '/<head.*?>(.*?)<\/head>/is'; 
    if (preg_match($headPattern, $htmlWithoutCss, $matches)) {
        $headContent = $matches[1]; 
        $modifiedHeadContent = "<style>$customCss</style>" . $headContent;
        $htmlWithCss = preg_replace($headPattern, "<head>$modifiedHeadContent</head>", $htmlWithoutCss); 
        return $htmlWithCss;
    }
    return "<style>$customCss</style>" . $htmlWithoutCss;
}

