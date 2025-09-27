# Guide Migration Railway - Backend OpenDevMada

## 🚀 Migration en 10 Minutes

### Étape 1: Préparation du Code (2 minutes)

#### A. Modifier __env.php pour Railway
```php
<?php
// Configuration Railway avec variables d'environnement
define('DB_HOST', $_ENV['MYSQLHOST'] ?? 'localhost');
define('DB_NAME', $_ENV['MYSQLDATABASE'] ?? 'railway');
define('DB_USER', $_ENV['MYSQLUSER'] ?? 'root');
define('DB_PASS', $_ENV['MYSQLPASSWORD'] ?? '');
define('DB_PORT', $_ENV['MYSQLPORT'] ?? 3306);

// URL base pour CORS (Railway fournit automatiquement)
define('FRONTEND_URL', $_ENV['FRONTEND_URL'] ?? 'https://opendevmada-annuaire.vercel.app');
?>
```

#### B. Créer railway.json (optionnel)
```json
{
  "$schema": "https://raw.githubusercontent.com/railwayapp/cli/master/schema.json",
  "build": {
    "builder": "heroku/php"
  },
  "deploy": {
    "startCommand": "heroku-php-apache2 public/"
  }
}
```

### Étape 2: Déploiement Railway (3 minutes)

1. **Aller sur Railway:** https://railway.app/new
2. **Se connecter avec GitHub**
3. **Sélectionner:** Backend_api_opendevmada_members
4. **Deploy automatique** lancé !

### Étape 3: Configuration Base de Données (3 minutes)

1. **Dans Railway Dashboard:**
   - Cliquer "Add Service" → "Database" → "MySQL"
   - Railway génère automatiquement les variables

2. **Variables créées automatiquement:**
   ```
   MYSQLHOST=containers-us-west-xxx.railway.app
   MYSQLDATABASE=railway
   MYSQLUSER=root
   MYSQLPASSWORD=xxx-generated-xxx
   MYSQLPORT=3306
   MYSQLURL=mysql://root:password@host:port/railway
   ```

### Étape 4: Import Base de Données (2 minutes)

1. **Télécharger Railway CLI:**
   ```bash
   npm install -g @railway/cli
   railway login
   ```

2. **Importer votre DB:**
   ```bash
   railway connect mysql
   # Puis importer votre fichier SQL
   source db/opendevmad_db.sql
   ```

## 🎯 Résultats Attendus

### URLs après déploiement:
- **API Backend:** `https://votre-app-name.up.railway.app`
- **Test API:** `https://votre-app-name.up.railway.app/api/opendevmada/membres`

### Modifications à faire dans votre Frontend Vercel:
```javascript
// Remplacer l'URL InfinityFree par Railway
const API_BASE = 'https://votre-app-name.up.railway.app';

// Les appels CORS fonctionneront parfaitement !
fetch(`${API_BASE}/api/opendevmada/membres`)
  .then(response => response.json())
  .then(data => console.log('🎉 SUCCESS:', data));
```

## ✅ Avantages vs InfinityFree

| Feature | InfinityFree | Railway |
|---------|--------------|---------|
| **CORS** | ❌ Bloqué | ✅ Natif |
| **PHP Version** | ⚠️ 8.0 | ✅ 8.3+ |
| **MySQL** | ⚠️ Limité | ✅ Complet |
| **Logs Erreurs** | ❌ Aucun | ✅ Temps réel |
| **File Uploads** | ⚠️ Restrictions | ✅ Complet |
| **SSL/HTTPS** | ⚠️ Basique | ✅ Auto |
| **Deploy** | 🐌 Manuel FTP | 🚀 Git Auto |
| **Variables ENV** | ❌ Non | ✅ Interface |

## 🚀 Action Maintenant

**Temps total: 10 minutes**
**Coût: Gratuit (500h/mois)**
**Résultat: API fonctionnelle + CORS résolu**

Voulez-vous que je vous guide étape par étape ?