<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    // prod
    $baseUrl = 'https://easyresumepulse.com/en';
    die();
} else {
    // local    
    $baseUrl = 'http://localhost:8080';
}
echo '<script>
    jwtToken = sessionStorage.getItem(\'jwt\');
    userUuid = sessionStorage.getItem(\'uuid\');
    if (jwtToken && userUuid) {
        window.location.href = \'panel.php\'; 
    }
</script>';