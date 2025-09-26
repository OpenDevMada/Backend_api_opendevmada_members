# Test de l'API OpenDevMada

## Tests à effectuer

### 1. Test basique de l'API
Ouvrez votre navigateur et allez sur :
```
https://opendevmadaannuaire.infinityfree.me
```

Vous devriez voir une page ou une erreur 404. Si c'est une erreur de serveur (500), il y a un problème de configuration.

### 2. Test direct de l'endpoint
Testez directement l'endpoint dans le navigateur :
```
https://opendevmadaannuaire.infinityfree.me/api/opendevmada/membres
```

### 3. Test avec curl (si vous avez curl installé)
```bash
curl -v https://opendevmadaannuaire.infinityfree.me/api/opendevmada/membres
```

### 4. Test avec Postman ou équivalent
- Method: GET
- URL: https://opendevmadaannuaire.infinityfree.me/api/opendevmada/membres

## Solutions possibles

### Si l'API n'est pas déployée :
1. Suivez le DEPLOYMENT_GUIDE.md
2. Uploadez tous vos fichiers sur InfinityFree
3. Importez la base de données

### Si l'API retourne une erreur 500 :
1. Activez temporairement le debug dans index.php :
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```
2. Vérifiez les credentials de base de données dans __env.php
3. Vérifiez que tous les fichiers sont uploadés

### Si CORS ne fonctionne toujours pas :
Ajoutez ces headers dans routes/api.php :
```php
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
```

## Résultat attendu

L'endpoint `/api/opendevmada/membres` devrait retourner :
```json
{
  "status": "success",
  "data": [...]
}
```

Ou une erreur JSON explicite si il y a un problème.