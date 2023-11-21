<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    // prod
    $baseUrl = 'https://easyresumepulse.com';
} else {
    // local    
    $baseUrl = 'http://localhost:8080';
}