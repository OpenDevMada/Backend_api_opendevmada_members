<?php
// CORS Ultimate Test - Maximum compatibilité
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, Cache-Control, Pragma");
header("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Max-Age: 3600");
header("Content-Type: application/json; charset=utf-8");

// Force flush headers
if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
}

// Handle preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Response immédiate
echo json_encode([
    'status' => 'cors-ultimate-test',
    'message' => 'CORS Headers Maximum envoyés !',
    'timestamp' => date('Y-m-d H:i:s'),
    'request_info' => [
        'method' => $_SERVER['REQUEST_METHOD'],
        'origin' => $_SERVER['HTTP_ORIGIN'] ?? 'direct-access',
        'referer' => $_SERVER['HTTP_REFERER'] ?? 'none',
        'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 100)
    ],
    'cors_debug' => [
        'allow_origin' => '*',
        'allow_methods' => 'GET, POST, PUT, DELETE, OPTIONS, PATCH',
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown'
    ]
]);
?>