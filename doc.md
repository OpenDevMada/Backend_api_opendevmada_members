# Documentation technique — OpenDevMada Members API (Express)

## Aperçu

- API REST **Node.js / Express 4** assurant la gestion des membres (authentification simple + CRUD complet).
- Prend en charge **SQLite** (développement) et **MySQL** (production) via **Knex**.
- Upload d’images identique à l’ancienne version PHP (`public/images/<Nom>/...`).
- CORS configurable par variable d’environnement (`CORS_ORIGINS`).

## Architecture applicative

```text
src/
├── app.js                # Configuration Express, middlewares et routes
├── index.js              # Bootstrap (init DB + start serveur)
├── config/
│   └── env.js            # Chargement/normalisation des variables d’env
├── controllers/
│   └── membre.controller.js   # Validation et orchestration HTTP
├── db/
│   └── index.js          # Knex + création automatique du schéma
├── middleware/
│   ├── db.middleware.js  # Attache la connexion Knex à chaque requête
│   └── error.middleware.js# 404 + gestion globale des erreurs
├── routes/
│   └── membre.routes.js  # Déclaration des endpoints `/api/opendevmada`
├── services/
│   └── membre.service.js # Accès aux données + règles métier
└── utils/
    └── file-system.js    # Gestion des dossiers / images
```

## Cycle de requête

1. `src/index.js` initialise la base (`initialiseDatabase`) puis lance le serveur Express.
2. `app.js` applique `helmet`, `cors`, parse JSON/form-data et sert `/images` en statique.
3. `db.middleware` attache `req.db` (instance Knex) pour la durée de la requête.
4. `membre.routes` dirige vers les contrôleurs selon l’endpoint.
5. Les contrôleurs valident les entrées (`express-validator`), orchestrent les appels service et renvoient des réponses JSON uniformes.
6. `error.middleware` capture les exceptions, sérialise les erreurs et renvoie un statut cohérent.

## Couche base de données

- **Knex** crée un pool unique par process.
- `env.config` décide du client (`sqlite3` ou `mysql2`).
- `db/index.js` :
  - Crée le dossier SQLite si nécessaire.
  - Vérifie l’existence de la table `membres` et la crée avec le schéma attendu si besoin.
  - Expose `initialiseDatabase`, `getDB` et `destroyDatabase`.
- Les services `membre.service.js` centralisent toutes les requêtes SQL :
  - `listMembers`, `getMemberById`, `getMemberByEmail`
  - `createMember` (hash `bcryptjs`, insertion, retour du nouvel enregistrement)
  - `updateMember` (mise à jour partielle + hash conditionnel)
  - `deleteMember` et `touchDerniereConnexion`

## Gestion des fichiers

- `utils/file-system.js` s’assure que `public/images` existe et crée un sous-dossier par membre (`buildMemberImageDir`).
- Les images uploadées via `multer` (stockage mémoire) sont persistées par `saveImageBuffer` avec suffixe unique.
- `deleteMemberPhoto` supprime le fichier associé lors d’un `DELETE` ou d’un remplacement.

## Validation et formats de réponses

- `express-validator` garantit les champs requis (`email`, `password`, `nom`, `phone`, etc.).
- Schéma de réponse standard :

  ```json
  {
    "status": "success" | "error",
    "message": "Texte explicatif",
    "data": {...} | [] | null
  }
  ```

- En cas d’erreur de validation, statut HTTP `400` + message du premier échec.
- Ressource inexistante → `404` avec `{ "status": "error", "message": "Membre non trouvé." }`.

## Variables d’environnement clés

| Clé | Description | Défaut |
| --- | --- | --- |
| `NODE_ENV` | `development` / `production` | `development` |
| `PORT` | Port HTTP | `8000` |
| `DB_CLIENT` | `sqlite3` ou `mysql2` | `sqlite3` |
| `SQLITE_PATH` | Chemin fichier SQLite | `storage/database.sqlite` |
| `DB_HOST` / `DB_PORT` / `DB_USER` / `DB_PASSWORD` / `DB_NAME` | Connexion MySQL | Variables Railway/PlanetScale |
| `CORS_ORIGINS` | CSV d’origines autorisées (`*` = tout) | `*` |

## Différences majeures vs version PHP

- Plus de session côté serveur : la réponse login retourne simplement les métadonnées du membre.
- Routage centralisé dans Express (`/api/opendevmada/...`) sans `.htaccess`.
- Gestion des erreurs unifiée (middleware `errorHandler`).
- Création de schéma automatisée via Knex (plus besoin de scripts PHP personnalisés).
- Configuration et déploiement pensés pour Railway/Render (Node natif).

## Tests rapides

```bash
npm install
cp .env.example .env
npm run dev
# puis tester GET http://localhost:8000/api/opendevmada/membres
```

## Prochaines pistes

- Introduire des tests automatisés (Jest/Supertest).
- Ajouter une authentification basée sur tokens (JWT) si besoin de sessions persistantes.
- Restreindre les rôles/permissions pour certaines opérations (actuellement ouvert).
