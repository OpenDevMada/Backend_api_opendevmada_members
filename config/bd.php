
<?php

require_once __DIR__ . "/../__env.php";

// Attraper une erreur
try{
    if (defined('DB_DRIVER') && DB_DRIVER === 'sqlite') {
        // Connexion SQLite
        $dbPath = DB_SQLITE_PATH;
        $dir = dirname($dbPath);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('PRAGMA foreign_keys = ON');

        // Schéma minimal requis par le projet
        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS membres (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nom TEXT NOT NULL,
                prenom TEXT NOT NULL,
                email TEXT,
                mot_de_passe TEXT,
                date_naissance TEXT,
                sexe TEXT,
                adresse TEXT,
                ville TEXT,
                pays TEXT,
                telephone TEXT,
                photo_profil TEXT,
                dernier_connexion TEXT DEFAULT CURRENT_TIMESTAMP,
                role TEXT DEFAULT 'membre',
                statut TEXT DEFAULT 'active'
            );"
        );
    } else {
        // Connexion MySQL (valeur par défaut)
        $db_name = DB_NAME;
        $db_host = DB_HOST;
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    
}catch(PDOException $e){
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
