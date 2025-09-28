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

## 🌐 Autres plateformes compatibles

- **Render** : config similaire avec `npm start`. Prévoir un add-on PostgreSQL/MySQL ou PlanetScale externe.
- **Vercel** : adaptés aux fonctions serverless, mais pas idéal pour une API Express complète. Préférer Railway/Render.
- **VPS / Docker** : lancer `npm install` puis `npm start` (penser au reverse proxy + HTTPS).

## 🗃️ Legacy (ancienne version PHP)

Les étapes d’hébergement InfinityFree concernaient l’ancienne version PHP et ne sont plus maintenues. Elles ont été retirées de ce document pour éviter toute confusion.
