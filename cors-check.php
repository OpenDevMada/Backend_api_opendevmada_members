<?php
// Test simple pour vérifier les headers CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

echo json_encode([
    'status' => 'cors-verification',
    'message' => 'Headers CORS actifs',
    'timestamp' => date('Y-m-d H:i:s'),
    'server_info' => [
        'method' => $_SERVER['REQUEST_METHOD'],
        'origin' => $_SERVER['HTTP_ORIGIN'] ?? 'none',
        'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 50)
    ]
]);
?>