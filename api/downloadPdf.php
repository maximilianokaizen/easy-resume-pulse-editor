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

  $footerCss = "
  <style>
  .footer-kaizen {
    width: 100%;
    border-top: 1px solid #ccc; /* Línea bordeada en la parte superior */
    text-align: center; /* Centrado del texto */
    padding: 10px 0; /* Espaciado interno */
    font-size: 12px; /* Tamaño de fuente */
    color: #666; /* Color de texto */
  }
</style>
  ";

  $footerHtml = "
  <div class='footer-kaizen'>
  Generated with easyresumepulse.com. | Created by kaizenpulse.com
</div>
  ";

  $customCss = getTemplateCustomCss($template);
  $htmlWithCss = insertCssIntoHtmlHead($htmlWithoutCss, $customCss);
  $htmlWithCss =  $htmlWithCss . $footerHtml;
  $htmlWithCss = insertCssIntoHtmlHead($htmlWithCss, $footerCss);
  die($htmlWithCss);

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

function deleteFromElement($html, $id) {
  $dom = new DOMDocument();
  $dom->loadHTML($html);
  $elements = $dom->getElementById($id);
  if ($elements) {
      foreach ($elements as $element) {
          $currentValue = $element->nodeValue;
          $newValue = str_replace($stringToDelete, '', $currentValue);
          $element->nodeValue = $newValue;
      }
  }
  $modifiedHtml = $dom->saveHTML();
  return $modifiedHtml;
}