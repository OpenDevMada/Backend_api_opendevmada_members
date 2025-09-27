<?php
// Test CORS ultra-simple
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

echo json_encode([
    'status' => 'test-ok',
    'message' => 'CORS test simple',
    'server' => $_SERVER['HTTP_HOST'],
    'method' => $_SERVER['REQUEST_METHOD'],
    'headers' => getallheaders()
]);
?>