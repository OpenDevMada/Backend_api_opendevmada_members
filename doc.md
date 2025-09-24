# Documentation Technique — Backend API OpenDevMada Members

## Aperçu
- API REST en PHP (sans framework) avec MySQL pour la gestion des membres de l’annuaire OpenDev Mada
- Fonctionnalités principales: Authentification (login/logout), CRUD membres, upload d’image de profil
- Réponses JSON uniformes

## Pile technique
- PHP 8.x
- MySQL / MariaDB
- SQLite (pour tests rapides, optionnel)
- Apache (réécriture via `.htaccess`)

## Structure du projet
```
Backend_api_opendevmada_members/
├── .htaccess                 # Réécriture vers index.php
├── __env.php                 # Variables d'environnement (DB)
├── API.md                    # Ancienne doc API
├── config/
│   ├── bd.php                # Connexion PDO (utilise __env.php)
│   └── regles.php            # Réécriture des URIs + extraction d'ID
├── controllers/
│   └── MembreController.php  # Logique métier (auth + CRUD)
├── db/
│   └── opendevmad_db.sql     # Script SQL (schéma + seed)
├── index.php                 # Point d'entrée HTTP
├── models/
│   └── Membre.php            # Modèle de données (POPO)
├── routes/
│   └── api.php               # Définition des routes et CORS
└── readme.md
```

## Entrée et routage HTTP
- `index.php`
  - Active l’affichage d’erreurs (dev)
  - Parse l’URI via `parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)`
  - Délègue les routes commençant par `/api/` à `routes/api.php`
- `.htaccess`
  - Redirige toutes les requêtes vers `index.php` (si le fichier/dossier n’existe pas)
  - Permet un routage unique et propre côté PHP

## Définition des routes et CORS
- `routes/api.php`
  - CORS: `Access-Control-Allow-Origin: *`, `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`, `Access-Control-Allow-Headers: Content-Type`
  - Forçage JSON: `Content-Type: application/json; charset=utf-8`
  - Charge `controllers/MembreController.php`
  - Calcule `$_SERVER['REQUEST_METHOD']`
  - Normalise l’URI et extrait `id` si présent via `config/regles.php`
  - Routes disponibles:
    - `GET    /api/opendevmada/membres` → liste des membres
    - `GET    /api/opendevmada/membre/{id}` → détail membre
    - `POST   /api/opendevmada/membre-login` → login JSON
    - `POST   /api/opendevmada/membre-logout/{id}` → logout + mise à jour `dernier_connexion`
    - `DELETE /api/opendevmada/membre-delete/{id}` → suppression
    - `POST   /api/opendevmada/membre-create` → création (multipart/form-data)
    - `POST   /api/opendevmada/membre-update/{id}` → mise à jour (multipart/form-data)

## Règles de normalisation d’URI
- `config/regles.php`
  - Détecte et extrait les IDs numériques dans l’URL via regex, puis réécrit l’URI-cible
  - Exemples:
    - `/api/opendevmada/membre/12` → `id=12` et `uri=/api/opendevmada/membre`
    - `/api/opendevmada/membre-logout/12` → `id=12` et `uri=/api/opendevmada/membre-logout`
    - `/api/opendevmada/membre-delete/12` → `id=12` et `uri=/api/opendevmada/membre-delete`
    - `/api/opendevmada/membre-update/12` → `id=12` et `uri=/api/opendevmada/membre-update`

## Modèle de données
- `models/Membre.php`
  - Propriétés: `id, nom, prenom, email, mot_de_passe, date_naissance, sexe, adresse, ville, pays, telephone, photo_profil, dernier_connexion, role, statut`
  - Getters/Setters pour chaque propriété
  - Remarques:
    - `setAdresse()` ne prend pas de paramètre mais assigne `$adresse` (manquant) → incohérence à corriger
    - Méthodes `getLastConexion`/`setLastConexion` orthographe “Conexion” (pas bloquant mais incohérent)

## Contrôleur: `controllers/MembreController.php`
- `findByMail($email)`
  - Récupère un membre par email
- `login(array $data)`
  - Entrée: JSON `{ email, password }`
  - Vérifie email, compare le mot de passe avec `password_verify`
  - Démarre une session et stocke des infos de membre
  - Codes HTTP: `400` (email manquant), `200` (ok), `401` (credentials invalides), `500` (erreur BDD)
- `logout(int $id)`
  - Met à jour `dernier_connexion = CURRENT_TIMESTAMP` (compatible MySQL/SQLite) puis `session_destroy()`
  - Renvoie JSON de confirmation
- `getMembers()`
  - Récupère et renvoie la liste ordonnée desc par `id`
  - Renvoie un tableau vide avec message si aucun membre
- `getMember(int $id)`
  - Détail d’un membre par `id`
  - Remarque: utilise `count($membre)` sur un fetch; prévoir un test `if (!$membre)`
- `create()`
  - Attend `multipart/form-data`
  - Champs requis (dans le code): `nom`, `prenom`, `phone`, `image`
  - Autres champs utilisés: `email`, `password`, `birthday (d/m/Y)`, `sexe`, `address`, `city`, `contry`, `role`, `statut`
  - Gère l’upload image: stocke dans `public/images/{nom}/`
  - Hache le mot de passe via `password_hash`
  - Insertion en BDD puis JSON succès/erreur
- `update($id)`
  - `multipart/form-data`, tous les champs optionnels: `email`, `password`, `address`, `city`, `phone`, `role`, `statut`, `image`
  - Gère remplacement d’image (supprime l’ancienne si une nouvelle est fournie)
  - Met à jour les colonnes correspondantes

## Base de données
- Script: `db/opendevmad_db.sql`
- Table `membres`
  - Colonnes principales: `id` (PK, AI), `nom`, `prenom`, `email` (UNIQUE), `mot_de_passe`, `date_naissance`, `sexe`, `adresse`, `ville`, `pays`, `telephone`, `photo_profil`, `date_inscription`, `dernier_connexion`, `role` (def. `membre`), `statut` (def. `actif`)
  - Index: `PRIMARY KEY(id)`, `UNIQUE(email)`
  - Un enregistrement exemple est fourni

## Configuration & Environnement
- `__env.php`
  - Définit les constantes `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`
  - Ajoute `DB_DRIVER` (`mysql` par défaut, peut être `sqlite`) et `DB_SQLITE_PATH` (chemin du fichier DB)
- `config/bd.php`
  - Crée un objet PDO global `$pdo`
  - Si `DB_DRIVER=sqlite`:
    - Connexion `sqlite:...`
    - Active `PRAGMA foreign_keys = ON`
    - Crée automatiquement la table `membres` si elle n’existe pas
  - Sinon (MySQL): connexion habituelle en UTF-8

### Support SQLite (tests rapides)
1. Dans `Backend_api_opendevmada_members/__env.php`, mettre:
   ```php
   define("DB_DRIVER", "sqlite")
   // Optionnel: modifier le chemin si besoin
   define("DB_SQLITE_PATH", __DIR__ . "/storage/database.sqlite")
   ```
2. Aucun script SQL à exécuter: le schéma minimal est créé automatiquement au premier accès
3. Le fichier sera créé sous `Backend_api_opendevmada_members/storage/database.sqlite`
4. Pour réinitialiser la base de test: supprimer le fichier `.sqlite` (il sera régénéré)
5. Tester rapidement:
   ```bash
   curl http://localhost:2001/api/opendevmada/membres
   ```

## Sécurité & CORS
- CORS ouvert à `*` pour faciliter le dev front
- Authentification par session (pas de JWT ni token)
- Mots de passe hachés via `password_hash`
- À prévoir en production: sécurisation CORS, validation serveur, contrôle d’accès par rôle, gestion fine des codes HTTP

## Réponses et gestion d’erreurs
- Format commun des erreurs:
```json
{
  "status": "error",
  "message": "Message d'erreur explicite"
}
```
- Codes HTTP explicitement utilisés: `200`, `400`, `401`, `500` (selon méthodes)

## Incohérences/Points d’attention (à corriger)
- Harmoniser l’orthographe des méthodes `getLastConexion`/`setLastConexion` (suggestion: `getLastConnexion`/`setLastConnexion`)

## Historique des changements
- Version initiale: Auth (login/logout), CRUD membres, upload d’images, CORS par défaut, routage simple via `.htaccess`

## Correctifs appliqués — 2025-09-24
- Correction du chemin d’inclusion du modèle dans `controllers/MembreController.php` (casse Linux)
- Correction de `setAdresse($adresse)` dans `models/Membre.php`
- Suppression du doublon de règle pour `membre-logout` dans `config/regles.php`
- Harmonisation des libellés de messages: remplacement de « Article » par « Membre » dans `create()` et `update()`
- Correction de l’appel à `create()` dans `routes/api.php` (suppression de l’argument inutile)

## Améliorations suggérées
- Harmoniser les noms de fichiers et inclusions (casse)
- Corriger `setAdresse()` et messages "Article"
- Gérer proprement les 404/422 et les `OPTIONS` pour CORS
- Ajouter une validation serveur stricte (email, formats, contraintes)
- Introduire un mécanisme d’authentification par token (JWT) si nécessaire
