# Production Environment Configuration for InfinityFree
# Copy this content to __env.php after setting up your database on InfinityFree

<?php

// Production Database Configuration for InfinityFree
// Use your actual InfinityFree database credentials from the MySQL Databases page

// InfinityFree Database Settings (Based on your account)
define("DB_HOST", "sql306.infinityfree.com");    // Your MySQL Host
define("DB_NAME", "if0_40033946_opendevmada");   // Your Database Name
define("DB_USER", "if0_40033946");               // Your Username (same as account)
define("DB_PASSWORD", "opendevmada");   // Your vPanel login password

// Use MySQL for production (InfinityFree doesn't support SQLite)
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