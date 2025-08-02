
<?php

require_once __DIR__ . "/../__env.php";

// Attraper une erreur
try{

    $db_name = DB_NAME;
    $db_host = DB_HOST;

    $bdd = "mysql:host=$db_host;dbname=$db_name;charset=utf8";

    $pdo = new PDO($bdd, DB_USER, DB_PASSWORD);
    
}catch(PDOException $e){
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
