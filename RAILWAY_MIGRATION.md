# Migration vers Railway - Guide Rapide

## Pourquoi Railway ?
- ✅ Support PHP complet
- ✅ CORS natif
- ✅ Base de données MySQL gratuite
- ✅ Déploiement automatique depuis GitHub
- ✅ Logs d'erreur complets
- ✅ SSL automatique

## Étapes de migration (15 minutes)

### 1. Créer compte Railway
- Aller sur: https://railway.app
- Se connecter avec GitHub
- Lier le repo: Backend_api_opendevmada_members

### 2. Configuration Railway
```bash
# Railway détecte automatiquement PHP
# Ajouter variables d'environnement:
DATABASE_HOST=mysql-container-host
DATABASE_NAME=railway_db
DATABASE_USER=root
DATABASE_PASS=auto-generated
```

### 3. Modification __env.php pour Railway
```php
<?php
// Configuration Railway
define('DB_HOST', $_ENV['MYSQLHOST'] ?? 'localhost');
define('DB_NAME', $_ENV['MYSQLDATABASE'] ?? 'railway');
define('DB_USER', $_ENV['MYSQLUSER'] ?? 'root');
define('DB_PASS', $_ENV['MYSQLPASSWORD'] ?? '');
define('DB_PORT', $_ENV['MYSQLPORT'] ?? '3306');
?>
```

### 4. Deploy automatique
- Railway détecte les changements GitHub
- Build et deploy automatique
- URL: https://votre-app.up.railway.app

## Avantages vs InfinityFree
| Feature | InfinityFree | Railway |
|---------|--------------|---------|
| CORS | ❌ Bloqué | ✅ Complet |
| PHP | ⚠️ Limité | ✅ Complet |
| Database | ⚠️ Basique | ✅ MySQL complet |
| Logs | ❌ Aucun | ✅ Détaillés |
| Deploy | 🐌 Manuel | 🚀 Auto Git |
| SSL | ⚠️ Limité | ✅ Auto |

## Temps estimé: 15 minutes pour migration complète