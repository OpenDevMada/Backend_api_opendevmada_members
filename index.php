<?php
    // Point d'entrée

    // CORS Headers - Simple and direct
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    
    // Handle OPTIONS preflight
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    // Production settings - disable error display for security
    ini_set('display_errors', 0);
    error_reporting(0);
    
    // Development settings - uncomment for debugging only
    // ini_set('display_errors', 1);
    // error_reporting(E_ALL);

    // configuration url ou recuperer le chemin demandé
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Compatible with all PHP versions
    if(strpos($uri, '/api/') === 0){
        require_once "routes/api.php"; //Route pour les APIs d'opendevMada
    }




