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

  $dompdf = new Dompdf();
  $dompdf->loadHtml($htmlWithoutCss);
  $dompdf->setPaper('A4', 'landscape');
  $dompdf->render();
  $dompdf->stream();
}


