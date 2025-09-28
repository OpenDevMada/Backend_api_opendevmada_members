# OpenDev Mada Members API (Express Edition)

Cette nouvelle version du backend OpenDev Mada repose désormais sur **Node.js / Express**, tout en conservant les mêmes fonctionnalités que l’implémentation PHP originale. L’API offre une gestion complète des membres (CRUD), l’authentification simple et la prise en charge de l’upload d’images. Elle peut fonctionner indifféremment avec **SQLite** (développement) ou **MySQL** (production).

## ✨ Points clés

- Express 4 + middleware modernes (CORS, Helmet, Multer, Validator)
- Couche d’accès aux données partagée via **Knex** (support SQLite & MySQL)
- Structure claire : routes → contrôleurs → services → couche DB
- Upload de photos identique à la version PHP (`public/images/<Nom>/...`)
- Configuration par fichier `.env` (voir `.env.example`)

## 🗂️ Nouvelle structure principale

```text
├── public/
│   └── images/                # Dossier partagé pour les photos
├── src/
│   ├── app.js                 # Configuration Express
│   ├── index.js               # Point d'entrée
│   ├── config/
│   │   └── env.js             # Chargement des variables d'environnement
│   ├── controllers/
│   │   └── membre.controller.js
│   ├── db/
│   │   └── index.js           # Initialisation Knex + auto-création de table
│   ├── middleware/
│   │   ├── db.middleware.js
│   │   └── error.middleware.js
│   ├── routes/
│   │   └── membre.routes.js
│   ├── services/
│   │   └── membre.service.js
│   └── utils/
│       └── file-system.js
├── package.json
├── .env.example
└── README.md
```

## 🚀 Démarrage rapide

```bash
# 1. Installer les dépendances
npm install

# 2. Copier la configuration d'exemple
cp .env.example .env

# 3. Lancer en mode développement (SQLite par défaut)


# ou démarrer en production
npm start
```

### Variables d’environnement

| Clé | Description | Valeur par défaut |
|-----|-------------|-------------------|
| `NODE_ENV` | Environnement (`development`, `production`) | `development` |
| `PORT` | Port d'écoute Express | `8000` |
| `DB_CLIENT` | `sqlite3` ou `mysql2` | `sqlite3` |
| `SQLITE_PATH` | Chemin du fichier SQLite | `./storage/database.sqlite` |
| `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` | Paramètres MySQL (utilisés si `DB_CLIENT=mysql2`) | variables Railway / PlanetScale |
| `CORS_ORIGINS` | Liste d’origines autorisées (`*` pour tout accepter) | `*` |

## 🧪 Endpoints (identiques à la version PHP)

Base URL : `http://localhost:8000/api/opendevmada`

| Méthode | Route | Description |
|---------|-------|-------------|
| `GET` | `/membres` | Liste tous les membres (ordre décroissant) |
| `GET` | `/membre/:id` | Retourne un membre précis |
| `POST` | `/membre-login` | Authentifie un membre (`{ email, password }`) |
| `POST` | `/membre-logout/:id` | Met à jour la dernière connexion |
| `POST` | `/membre-create` | Crée un membre (FormData + image) |
| `POST` | `/membre-update/:id` | Met à jour les informations + photo |
| `DELETE` | `/membre-delete/:id` | Supprime un membre et sa photo |

Consultez `API.md` pour les exemples détaillés de payloads.

## 🛢️ Choix de la base de données

### Développement (par défaut)

- L’API utilise `SQLite` en local (`storage/database.sqlite`).
- Knex crée automatiquement la table `membres` si elle n’existe pas.

### Production (Railway, PlanetScale, etc.)

1. Passer `DB_CLIENT=mysql2` dans `.env`.
2. Renseigner `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`.
3. Redémarrer le serveur – aucune autre modification nécessaire.

## 📷 Upload d'images

- Les images sont stockées dans `public/images/<Nom_membre>/`.
- Les noms sont sanitisés et un identifiant unique est ajouté pour éviter les collisions.
- Lors d'une mise à jour, l’ancienne image est supprimée automatiquement.

## 🤝 Contribution

```bash
git clone https://github.com/OpenDevMada/Backend_api_opendevmada_members.git
cd Backend_api_opendevmada_members
npm install
```

1. Créez une branche (`git checkout -b feature/ma-fonction`)
2. Implémentez / testez
3. Soumettez une PR 💡

## 📄 Licence

Projet sous licence MIT.

---

Merci à **Landrosse RADIMSON** et à toute l'équipe OpenDev Mada pour ce projet ! 🚀
