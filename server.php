<?php
// Router pour le serveur PHP intégré
// Sert les fichiers statiques si présents, sinon route tout vers index.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Si le fichier/ressource demandé existe physiquement, on le sert tel quel
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
  return false;
}

// Sinon, on délègue à l'application
require_once __DIR__ . '/index.php';
