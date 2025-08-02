# OpenDev Mada API Backend

Bienvenue dans le projet **OpenDev Mada API Backend**. Ce dépôt contient le backend en PHP de l'annuaire de membres pour l'organisation OpenDev Mada.

## Description du projet

Ce projet est une API RESTful développée en **PHP (sans framework)** avec **MySQL** pour la gestion des membres (CRUD), l'authentification (Login/Logout) et la gestion des images de profil.

Le frontend pourra consommer cette API via des requêtes **JSON** ou **FormData** (dans le cas d'envoi de fichiers/images).

## Structure du projet

```
├── controllers/
│   └── MembreController.php
├── config/
│   └── database.php
│   └── regles.php
├── routes/
│   └── api.php
├── public/
│   └── images/
├── index.php
├── README.md
├── API.md
└── ...
```

## Technologies utilisées

* PHP 8.x
* MySQL
* JavaScript (pour tests API via fetch/AJAX)

## Fonctionnalités principales

* CRUD Membres (Créer, Lire, Mettre à jour, Supprimer)
* Authentification : Login / Logout
* Upload d'image de profil (FormData)
* Architecture MVC simplifiée
* API REST sans framework

## Installation

1. **Cloner le dépôt** :

   ```bash
   git clone https://github.com/OpenDevMada/Backend_api_opendevmada_members.git
   ```

2. **Configurer la base de données** :

   * Crée une base de données MySQL.
   * Importer le script SQL (non inclus ici).
   * Configurer les accès DB dans `config/database.php`.

3. **Lancer le serveur local (XAMPP ou autre)**

4. Accéder à l'API via :

   ```
   http://localhost/chemin-vers-projet/index.php
   ```

## Endpoints

Voir le fichier **API.md** pour les détails des endpoints et les méthodes à utiliser (POST, GET, DELETE).

## Règles d'envoi des données

* **Requête JSON** :

  * Pour le Login
* **FormData** :

  * Pour la création et mise à jour d’un membre (surtout pour les images)

## Auteur

* **Landrosse RADIMSON**

  * Backend Developer
  * Projet OpenDev Mada

---

## Remarques importantes

* Les routes sont testées en local.
* La gestion de sécurité (token, auth avancée) n’est pas encore implémentée.
* Prêt à être intégré par le frontend.

## Licence

Ce projet est sous licence MIT.

---

Tu veux contribuer ? Contacte-moi ! 🚀
