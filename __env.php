<?php
// Production Environment Configuration for Railway
// Database configuration uses Railway environment variables

// Railway Database Configuration - Uses Environment Variables
// Railway provides these automatically when you add MySQL service
define("DB_HOST", $_ENV['MYSQLHOST'] ?? 'localhost');
define("DB_NAME", $_ENV['MYSQLDATABASE'] ?? 'railway');
define("DB_USER", $_ENV['MYSQLUSER'] ?? 'root');
define("DB_PASSWORD", $_ENV['MYSQLPASSWORD'] ?? '');
define("DB_PORT", $_ENV['MYSQLPORT'] ?? 3306);

// Use MySQL for production
define("DB_DRIVER", "mysql");

// SQLite path (not used in production but kept for compatibility)
define("DB_SQLITE_PATH", __DIR__ . "/storage/database.sqlite");

/*
DEPLOYMENT NOTES:
1. Replace 'sql200.infinityfree.com' with your actual MySQL host
2. Replace 'if0_xxxxxxx_opendevmada' with your actual database name
3. Replace 'if0_xxxxxxx' with your actual username
4. Replace 'your_database_password' with your actual password
5. You'll get these details from InfinityFree cPanel → MySQL Databases
*/