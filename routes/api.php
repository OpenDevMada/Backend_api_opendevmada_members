<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    header('Content-Type: application/json; charset=utf-8');
    
    // Import des contrôleurs
    require_once __DIR__ . '/../controllers/MembreController.php';
    
    
    // configuration http url
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // methode de http post, put, get et delete
    $method = $_SERVER['REQUEST_METHOD'];
    
    
    // Regle pour detecter les ID dans url
    require_once __DIR__ . '/../config/regles.php';

    switch($uri){

        // Route pour projet annuaire opendev mada
        // liste  tout les membres
        case '/api/opendevmada/membres':
            $controller = new MembreController();

            if($method === 'GET'){

                $controller->getMembers();
                
            }
            break;
        
        // Avoir un membre
        case '/api/opendevmada/membre':
            $controller = new MembreController();
    
            if($method === 'GET'){
    
                if($id){
                    $controller->getMember($id);
                }
                    
            }
            break;
        
        // Route pour login d'un membre
        case '/api/opendevmada/membre-login':
            $controller = new MembreController();

            if($method === 'POST'){
                $data = json_decode(file_get_contents("php://input"), true);
                $controller->login($data);
            }
            break;

        // Route pour deconnexion d'un membre
        case '/api/opendevmada/membre-logout':
            $controller = new MembreController();

            if($method === 'POST'){

                if($id){
                    $controller->logout($id);
                }
                
            }
            break;

        // Route pour supprimer d'un membre
        case '/api/opendevmada/membre-delete':
            $controller = new MembreController();

            if($method === 'DELETE'){

                if($id){
                    $controller->deleteMembre($id);
                }
                
            }
            break;
        
        // Route pour creer  d'un membre
        case '/api/opendevmada/membre-create':
            $controller = new MembreController();

            if($method === 'POST'){

                $controller->create($id);
                
            }
            break;

        // Route pour mettre à jour  d'un membre
        case '/api/opendevmada/membre-update':
            $controller = new MembreController();

            if($method === 'POST'){

                if($id){
                    $controller->update($id);
                }
                
            }
            break;
            
    }
    ?>
    