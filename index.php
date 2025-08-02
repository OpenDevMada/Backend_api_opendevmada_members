<?php
    // Point d'entrée

    // Afficher les erreurs pour les dev (à retirer en prod)
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // configuration url ou recuperer le chemin demandé
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if(str_starts_with($uri, '/api/')){
        require_once "routes/api.php"; //Route pour les APIs d'opendevMada
    }




