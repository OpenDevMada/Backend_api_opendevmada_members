<?php
// Diagnostic simple InfinityFree
header('Content-Type: application/json');

echo json_encode([
    'status' => 'diagnostic',
    'php_version' => phpversion(),
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown',
    'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'unknown',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
    'current_directory' => __DIR__,
    'files_exist' => [
        'index.php' => file_exists(__DIR__ . '/index.php') ? 'YES' : 'NO',
        'routes/api.php' => file_exists(__DIR__ . '/routes/api.php') ? 'YES' : 'NO',
        'config/bd.php' => file_exists(__DIR__ . '/config/bd.php') ? 'YES' : 'NO',
        'controllers/MembreController.php' => file_exists(__DIR__ . '/controllers/MembreController.php') ? 'YES' : 'NO'
    ],
    'timestamp' => date('Y-m-d H:i:s')
]);
?>