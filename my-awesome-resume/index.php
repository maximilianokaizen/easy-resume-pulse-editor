<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $basePath = '/home/easyre/public_html/en';
} else {   
    $basePath = '/var/www/html';
}

$uuid = $_GET['uuid'];
require_once($basePath . '/api/internal/links/Links.php');

$link = new Links();

$htmlResume = $link->getHtml($uuid);
die($htmlResume);