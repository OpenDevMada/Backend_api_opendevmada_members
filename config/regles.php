<?php 

// Regles pour detecter les ID dans l'URL

$id = null;

// Pour opendev mada membre
if(preg_match('#^/api/opendevmada/membre/(\d+)$#', $uri, $matches)){
    $id = $matches[1];
    $uri = '/api/opendevmada/membre';
}

// Pour opendev mada membre deconnexion
if(preg_match('#^/api/opendevmada/membre-logout/(\d+)$#', $uri, $matches)){
    $id = $matches[1];
    $uri = '/api/opendevmada/membre-logout';
}

// Pour opendev mada membre suppression d'un membre
if(preg_match('#^/api/opendevmada/membre-delete/(\d+)$#', $uri, $matches)){
    $id = $matches[1];
    $uri = '/api/opendevmada/membre-delete';
}

// Pour opendev mada membre mettre à jour  d'un membre
if(preg_match('#^/api/opendevmada/membre-update/(\d+)$#', $uri, $matches)){
    $id = $matches[1];
    $uri = '/api/opendevmada/membre-update';
}
