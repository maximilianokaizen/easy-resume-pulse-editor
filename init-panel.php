<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    // prod
    $baseUrl = 'https://easyresumepulse.com/en';
} else {
    // local    
    $baseUrl = 'http://localhost:8080';
}
echo '<script>
    const jwtToken = sessionStorage.getItem(\'jwt\');
    const userUuid = sessionStorage.getItem(\'uuid\');
    if (!jwtToken || !userUuid) {
        window.location.href = \'index.php\'; 
    }
</script>';