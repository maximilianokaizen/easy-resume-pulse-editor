<?php

function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }

    $input = strip_tags($input);

    /*
    if (!get_magic_quotes_gpc()) {
        $input = addslashes($input);
    }
    */
    
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    return $input;
}