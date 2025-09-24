<?php

// Information sur la base de donnee
define("DB_HOST", "localhost");
define("DB_NAME", "opendevmad_db");
define("DB_USER", "root");
define("DB_PASSWORD", "");

// Driver de base de données: 'mysql' (par défaut) ou 'sqlite'
define("DB_DRIVER", "sqlite");

// Emplacement du fichier SQLite (utilisé si DB_DRIVER = 'sqlite')
define("DB_SQLITE_PATH", __DIR__ . "/storage/database.sqlite");