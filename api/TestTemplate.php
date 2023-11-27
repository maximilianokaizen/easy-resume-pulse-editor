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
  $requiredParams = ['htmlContent'];

  if (!isset($requestData['htmlContent'])) {
    http_response_code(400);
    die("htmlContent parameter is required");
  }

  $html = $requestData['htmlContent'];
  $html = addFooter($html);
  $dompdf = new Dompdf();
  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4', 'landscape');
  $dompdf->render();
  $dompdf->stream();
}

function addFooter($html) {
    $footer = "
    <div class= 'footer-kaizen' style='width: 100%;text-align:center;padding:10px 0;font-size:16px; margin: 0px auto;clear:both'>
    Generated with https://easyresumepulse.com | Created by https://kaizenpulse.com/index-en.html
    </div>
    </body></html>";
    $result = str_replace("</body></html>", $footer, $html);
    return $result;
  }

  
?>