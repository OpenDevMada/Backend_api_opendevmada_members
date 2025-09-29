# 🚀 Deployment Guide – OpenDevMada Express API

Cette version de l’API fonctionne entièrement avec **Node.js / Express**. Choisissez un hébergeur qui supporte Node (Railway, Render, VPS, etc.). Les instructions ci-dessous couvrent le scénario recommandé avec Railway, puis donnent quelques alternatives.

## ✅ Plateforme recommandée : Railway

1. **Créer un compte et un projet**
   - Rendez-vous sur [railway.app](https://railway.app) et connectez votre compte GitHub.
   - Importez ce dépôt (`Backend_api_opendevmada_members`).

2. **Configurer les variables d’environnement**
   - Dans l’onglet *Variables*, ajoutez les clés présentes dans `.env.example`.
   - Pour MySQL, Railway crée automatiquement `MYSQLHOST`, `MYSQLPORT`, `MYSQLUSER`, `MYSQLPASSWORD`, `MYSQLDATABASE`.

3. **Choisir la base de données**
   - **PoC / développement** : laissez `DB_CLIENT=sqlite3`. Aucune action supplémentaire n’est nécessaire.
   - **Production** : passez `DB_CLIENT=mysql2` et reliez un service MySQL Railway (ou PlanetScale). Copiez les identifiants dans les variables `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME` si vous n’utilisez pas les valeurs automatiques de Railway.

4. **Déployer**
   - Railway installe automatiquement les dépendances et lance `npm start`.
   - L’URL générée ressemble à `https://votre-app.up.railway.app`.

5. **Mettre à jour le frontend**
   - Remplacez l’ancienne URL InfinityFree par `https://votre-app.up.railway.app/api/opendevmada/...`.

## 🔁 Commandes utilisées par Railway

```bash
npm install
npm run build   # non requis pour cette API mais peut être défini si besoin
npm start
```

*(Railway lancera `npm start` par défaut. Aucun build supplémentaire n’est nécessaire.)*

## 🔧 Post-déploiement – checklist

- [ ] `GET /api/opendevmada/membres` répond bien `200`.
- [ ] Upload de fichier testé via `POST /membre-create` (FormData).
- [ ] CORS autorise votre frontend (`CORS_ORIGINS` dans `.env`).
- [ ] Base de données correctement initialisée (voir logs Railway).
- [ ] Logs Railway propres (aucune exception répétée).

## 🌐 Alternative complète : Render

Render supporte très bien les apps Node et fournit un disque persistant pour les fichiers uploadés. Deux façons de déployer :

### Option A : Utiliser `render.yaml`

1. **Pousser le fichier** `render.yaml` (déjà présent à la racine du dépôt) vers la branche principale.
2. **Sur [render.com](https://render.com)**, créez un *Blueprint* → *New Blueprint Instance*.
3. Sélectionnez votre dépôt GitHub et votre branche.
4. Render détectera le service `opendevmada-express-api` défini dans le blueprint et provisionnera :
   - Build : `npm install`
   - Start : `npm start`
   - Disque persistant monté sur `public/images` (1 Go par défaut, ajustable).
5. **Renseignez les secrets MySQL** (host, port, user, password, database) dans l’onglet *Environment*. Le blueprint attend des secrets nommés `db-host`, `db-port`, etc. Vous pouvez les créer côté Render (*Secrets*) puis relancer le déploiement.
6. **Ajustez `CORS_ORIGINS`** pour inclure vos domaines frontend.

### Option B : Création manuelle du service web

1. Sur Render, cliquez sur *New Web Service*.
2. Connectez le dépôt puis choisissez la branche.
3. Configurez :
   - **Environment** : `Node`
   - **Build Command** : `npm install`
   - **Start Command** : `npm start`
   - **Node Version** : `18`
4. Ajoutez un **Persistent Disk** (ex. 1 Go) monté sur `public/images` pour les uploads. Si vous souhaitez rester sur SQLite en production, ajoutez un second disque monté sur `storage` et définissez `SQLITE_PATH=/opt/render/project/src/storage/database.sqlite`.
5. Dans l’onglet *Environment*, définissez au minimum :
   - `NODE_ENV=production`
   - `DB_CLIENT=mysql2`
   - Les variables MySQL (`DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`) issues de votre base (Render MySQL, PlanetScale, etc.)
   - `CORS_ORIGINS` incluant vos domaines frontend.
6. Déployez. Render injecte automatiquement la variable `PORT` utilisée par Express.

### Notes importantes pour Render

- **Base de données** : la plateforme propose PostgreSQL gratuitement. Comme l’API accepte MySQL/SQLite uniquement, utilisez un service MySQL géré (PlanetScale, Render MySQL payant) ou adaptez le code pour PostgreSQL si besoin.
- **Uploads** : l’API sauvegarde les images sur disque. Le disque persistant configuré ci-dessus est indispensable. À défaut, migrez vers un stockage objet (S3, Cloudinary) et adaptez `file-system.js`.
- **SQLite** : toléré pour des démos uniquement. Sans disque persistant, la base serait réinitialisée à chaque déploiement.
- **Logs et santé** : Render expose les logs et un onglet *Events* pour suivre les déploiements. L’endpoint `/health` reste disponible pour les pings externes.

## 🔄 Autres options rapides

- **Vercel** : adapté aux fonctions serverless, mais nécessite de réécrire l’API en handlers serverless et de déporter les uploads. À envisager uniquement après refactor.
- **VPS / Docker** : provisionnez une VM, `npm install`, configurez un `pm2` ou un service systemd, et placez un reverse proxy (Nginx + SSL).

## 🗃️ Legacy (ancienne version PHP)

Les étapes d’hébergement InfinityFree concernaient l’ancienne version PHP et ne sont plus maintenues. Elles ont été retirées de ce document pour éviter toute confusion.
